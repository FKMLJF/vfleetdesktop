<?php

namespace App\Http\Middleware;

use App\Models\SzerepkorKapcsolo;
use App\Providers\RouteServiceProvider;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;
use Redirect;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        header('Access-Control-Allow-Origin:  http://localhost:4200');
        header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Authorization, Origin');
        header('Access-Control-Allow-Methods:  POST, PUT');

        if (Auth::guard($guard)->check()) {
            return redirect(RouteServiceProvider::HOME);
        }
        $szerepkor = null;
        if($request->getPathInfo() == "/login" && strtolower($request->getMethod()) == "post")
        {
            try{
                $user = User::where('email', $request->post('email'))
                    ->first();
                if(empty($user)) return Redirect::back()->withErrors(['Rossz e-mail vagy jelszó!']);
                $szerepkor = SzerepkorKapcsolo::where('user_id',$user->id)
                    ->first();
                if($szerepkor->szerepkor_id == 1)
                {
                    return Redirect::back()->withErrors(['Nincs joga "Desktop" alkalmazás használatához!']);
                }
                if($user->megerositve == 0)
                {
                    return Redirect::back()->withErrors(['A megadott felhasználó regisztrációja nincs megerősítve!']);
                }
                if($user->tiltott == 1)
                {
                    return Redirect::back()->withErrors(['A megadott felhasználó tiltott!']);
                }
            }
            catch (Exception $e)
            {
                return Redirect::back()->withErrors(['A megadott felhasználó regisztrációja nincs megerősítve!']);
            }

        }
        if(!empty($szerepkor)){
            $request->session()->put('role', $szerepkor->szerepkor_id);
        }

        return $next($request);
    }
}
