<?php

namespace App\Http\Middleware;

use App\Models\Felhasznalok;
use App\User;
use Carbon\Carbon;
use Closure;
use Auth;
use http\Env;

class PerpMobil
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        header('Access-Control-Allow-Origin:  http://localhost:4200');
        header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Authorization, Origin');
        header('Access-Control-Allow-Methods:  POST, PUT');

        if($request->getPathInfo() == "/api/auth/login")
        {
            return $next($request)->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE,
        OPTIONS')
                ->header('"Access-Control-Allow-Headers"', '*');
        }

        //dd($request->input());
        $user = User::where('api_token', $request->header('Authorization') )->first();
        if(!empty($user))
        {

            $start_date = Carbon::createFromTimeString($user->token_expiration);
            $now =  $now = Carbon::now()->addHours(1)->toDateTimeString();
            $diff = $start_date->diffInMinutes($now);
            if(env('TOKEN_EXPIRATION_MINUTE', 30) >= $diff )
            {
                $user->token_expiration = Carbon::now()->addHours(1);
                $user->save();
                return $next($request)->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            }
            else
            {
                return response()->json(['error' => 'Token érvényessége lejárt'], 401);
            }

        }

        return response()->json($request->header('api_token'), 401);
    }
}
