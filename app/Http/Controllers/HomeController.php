<?php

namespace App\Http\Controllers;

use App\Models\Autok;
use App\Models\AutokTartozekok;
use App\Models\Dokumentumok;
use App\Models\Ertesitesek;
use App\Models\Munkalapok;
use App\Models\SzerepkorKapcsolo;
use App\Models\Tankola;
use App\User;
use App\ViewModels\HibakView;
use Auth;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $role = SzerepkorKapcsolo::where('user_id', Auth::id())->first();
            if(!empty($role)){
                $role = $role->szerepkor_id;
            }else{
                $role = -1;
            }

        return view('home', compact('role'));
    }

    public function widgetdata()
    {
        $usercnt = User::where('tiltott', '0')->whereRaw(  " id IN (Select id from users where root_user=?) or id = ?",[\Auth::id(),\Auth::id()])->count() . ' db';
        $carcnt = Autok::where('rejtett', '0')->whereRaw(  " (user_id IN (Select id from users where root_user=?) or user_id = ?)",[\Auth::id(),\Auth::id()])->count() . ' db';
        $munkalapcnt = Munkalapok::whereRaw(" user_id IN (Select root_user from users where id=?) or user_id = ?",[\Auth::id(),\Auth::id()])->count() . ' db';
        $hibacnt = HibakView::whereRaw(" user_id IN (Select root_user from users where id=?) or user_id = ?",[\Auth::id(),\Auth::id()])->count() . ' db';
        $ertcnt = Ertesitesek::whereRaw(" user_id IN (Select root_user from users where id=?) or user_id = ?",[\Auth::id(),\Auth::id()])->count() . ' db';
        $doccnt = Dokumentumok::whereRaw(" user_id IN (Select root_user from users where id=?) or user_id = ?",[\Auth::id(),\Auth::id()])->count() . ' db';
        $store_cnt = AutokTartozekok::whereRaw(" user_id IN (Select root_user from users where id=?) or user_id = ?",[\Auth::id(),\Auth::id()])->count() . ' db';
        $fuelcnt= number_format(Tankola::whereRaw(" Year(inserted_at) = Year(Now())  and (user_id IN (Select root_user from users where id=?) or user_id = ?)",[\Auth::id(),\Auth::id()])->sum('osszeg'),0,".", " ") . ' Ft ('.date('Y').")";

        return json_encode(array($usercnt,$carcnt,$munkalapcnt,$hibacnt,$ertcnt,$doccnt, $fuelcnt,$store_cnt));
    }


    public function reauthenticate(Request $request)
    {
        // get the logged in user
        $user = \Auth::user();

        // initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // generate a new secret key for the user
        $user->google2fa_secret = $google2fa->generateSecretKey();

        // save the user
        $user->save();

        // generate the QR image
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        // Pass the QR barcode image to our view.
        return view('google2fa.register', ['QR_Image' => $QR_Image,
            'secret' => $user->google2fa_secret,
            'reauthenticating' => true
        ]);
    }

    public function office()
    {
        $file = public_path('teszt.docx');

        $phpword = new \PhpOffice\PhpWord\TemplateProcessor($file);

        $phpword->setValue('{ugyfelnev}','Teszt Elek');
        $phpword->setValue('{szuletesihely}','Kiskunfélegyháza');

        $phpword->saveAs('teszt.docx');

    }
}
