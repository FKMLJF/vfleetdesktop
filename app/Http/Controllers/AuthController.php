<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth as JWTAuth;

class AuthController extends Controller
{
    use Notifiable;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
      public function login(Request $request)
      {
          $user = User::where('bejelentkezesi_nev', request(['bejelentkezesi_nev'][0]))->first();
          $email = (!empty($user))?$user->email:null;

          $credentials = ['email' => $email, 'password' => request(['jelszo'][0])];
            //dd($credentials);
          if ( $this->guard('api')->attempt($credentials)) {
                if( $user->tiltott == 1)
                {
                    return response()->json(['error' => 'Tiltott felhasználó!'], 401);
                }
              $start_date = Carbon::createFromTimeString($user->token_expiration);
              $now =  $now = Carbon::now()->addHours(1)->toDateTimeString();
              $diff = $start_date->diffInMinutes($now);
              if(env('TOKEN_EXPIRATION_MINUTE', 30) >= $diff )
              {
                  $user->token_expiration = Carbon::now()->addHours(1);
                  $user->save();
                  $token = $user->api_token;
              }
              else
              {
                  $token = Str::uuid()->toString();
                  $user->api_token = $token;
                  $user->token_expiration = Carbon::now()->addHours(1);
                  $user->save();
              }

              return $this->respondWithToken($token, $user);
          }

          return response()->json(['error' => 'Rossz felhasználónév vagy jelszó!'], 401);
      }

    function logout(Request $request)
    {
        $user = User::where('api_token', $request->post('api_token') )->first();
        if(!empty($user))
        {
            $user->api_token = null;
            $user->token_expiration = null;
        }
        else
        {
            return response()->json(['error' => 'A felhasználó nem létezik!'], 403);
        }
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'bearer',
            'expires_in' => date('y-M-D h:m:s')
        ]);
    }


    public function guard()
    {
        return Auth::guard();
    }


}
