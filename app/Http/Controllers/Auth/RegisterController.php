<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Autok;
use App\Models\Licensz;
use App\Models\SzerepkorKapcsolo;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Redirect;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers {
        // change the name of the name of the trait's method in this class
        // so it does not clash with our own register method
        register as registration;
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware(['auth', '2fa']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nev' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'/*, 'unique:felhasznalok'*/],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'nev' => $data['nev'],
            'bejelentkezesi_nev' => $data['bejelentkezesi_nev'],
            'email' => $data['email'],
            'password' => bcrypt($data['jelszo']),
            'google2fa_secret' => $data['google2fa_secret'],
            'api_token' => Uuid::uuid4()->toString()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register(Request $request)
    {
        //dd($request->post());

        if (!empty($request->input('azonosito'))) {

            if (!empty($request->input('edit'))) {
                $user = User::where('id', $request->input('azonosito'))->first();
                if (!empty($user)) {
                    $user->name = $request->post('name');
                    $user->email = $request->post('email');
                    $user->save();
                    $roleUpdate = SzerepkorKapcsolo::where('user_id', $request->input('azonosito'))->first();
                    $roleUpdate->szerepkor_id = $request->input('szerepkor_id');
                    $roleUpdate->save();
                    return Redirect::to("felhasznalok/index")->with('success', $user->name . " felhasználó adatainak módosítása sikeresen lezajlott!");
                }
            }

            if (!empty($request->input('changepassword'))) {
                $user = User::where('id', $request->input('azonosito'))->first();
                if (!empty($user)) {
                    $user->password = bcrypt($request->post('password'));
                    $user->save();
                    return Redirect::to("felhasznalok/index")->with('success', $user->name . " felhasználó új jelszó beállítása sikeresen lezajlott!");
                }
            }
        }
        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');
        if (!empty($request->input('withoutfa'))) {

            $user = User::whereRaw(" id IN (Select id from users where root_user=?) or id = ? and tiltott = 0 ", [\Auth::id(), \Auth::id()])->count();
            $licenscnt = Licensz::whereRaw(" root_user IN (Select id from users where root_user=?) or root_user = ? ", [\Auth::id(), \Auth::id()])->first();
            if (intval($user) >= intval($licenscnt->felhasznalo)) {
                return Redirect::to("felhasznalok/register")->with('error', 'Nincs elegendő felhasználó licensz, maximális licensz:'. $licenscnt->jarmu);
            }
            User::create([
                'name' => $request->input('nev'),
                'username' => $request->input('nev'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'api_token' => Uuid::uuid4()->toString(),
                'google2fa_secret' => $google2fa->generateSecretKey(),
                'root_user' => \Auth::id(),
            ]);
            if (!empty($request->input('withoutfa'))) {
            $szerepkor = new SzerepkorKapcsolo();
            $szerepkor->user_id = User::where('email', $request->input('email') )->first()->id;
            $szerepkor->szerepkor_id = $request->input('szerepkor');
            $szerepkor->save();
            }

            return Redirect::to("felhasznalok/index")->with('success', 'Sikeres felhasználó létrehozás!');
        }
        else{
            $googlefageneratedkey = $google2fa->generateSecretKey();
            User::create([
                'name' => $request->input('nev'),
                'username' => $request->input('nev'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('jelszo')),
                'root_user' => \Auth::id(),
                'api_token' => Uuid::uuid4()->toString(),
                'google2fa_secret' => $googlefageneratedkey,
            ]);
            // Save the registration data in an array
            $registration_data = $request->all();

            // Add the secret key to the registration data
            $registration_data["google2fa_secret"] = $googlefageneratedkey;

            // Save the registration data to the user session for just the next request
            $request->session()->flash('registration_data', $registration_data);

            // Generate the QR image. This is the image the user will scan with their app
            // to set up two factor authentication
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $registration_data['email'],
                $registration_data['google2fa_secret']
            );

            // Pass the QR barcode image to our view
            return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);
        }
        //Validate the incoming request using the already included validator method
        $this->validator($request->all())->validate();


        // Save the registration data in an array
        $registration_data = $request->all();

        // Add the secret key to the registration data
        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();

        // Save the registration data to the user session for just the next request
        $request->session()->flash('registration_data', $registration_data);

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );

        // Pass the QR barcode image to our view
        return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);
    }


    public function completeRegistration(Request $request)
    {
        // add the session data back to the request input
        $request->merge(session('registration_data'));

        //dd($request);

        // Call the default laravel authentication
        return $this->registration($request);
    }


}
