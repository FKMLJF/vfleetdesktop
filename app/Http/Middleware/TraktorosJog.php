<?php

namespace App\Http\Middleware;

use App\Models\Felhasznalok;
use App\User;
use Closure;
use Auth;

class TraktorosJog
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

        if(Auth::check())
        {
            /** @var Felhasznalok $user */
            $user = Auth::user()->toFelhasznalo();

            dd($user->jogoks);

            return $next($request);
        }
        return redirect('login');
    }
}
