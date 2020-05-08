<?php

namespace App\Http\Controllers;

use App\Models\Felhasznalok;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FelhasznalokController extends Controller
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
        return view('felhasznalok.index');
    }

    public function indexData(Request $request)
    {
        $model = Felhasznalok::query();
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->filter(function ($query) {
                if (request()->has('bejelentkezesi_nev')) {
                    $query->where('bejelentkezesi_nev', 'like', "%" . request('bejelentkezesi_nev') . "%");
                }

            }, true)
            ->toJson();
    }

    public function userStatusChange(Request $r)
    {
        $user = User::where('azonosito', $r->post('id') )->first();
       if(!empty($user)) {
           if ($r->post('eventtype') == "megerositett") {
               $user->megerositve = ($r->post('checked') == "true")?1:0;
               $user->save();
           } else if ($r->post('eventtype') == "tiltott")
           {
               $user->tiltott = ($r->post('checked') == "true")?1:0;
               $user->save();
           }
       }
    }

    public function getQrcode(Request $r){
        $user = User::where('azonosito', $r->post('id') )->first();
        $QR_Image = '';
        if(!empty($user)) {
            $google2fa = app('pragmarx.google2fa');
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $user->google2fa_secret
            );
            return json_encode([ 'qr' => $QR_Image, 'username' => $user->bejelentkezesi_nev ]);
        }
            return [$QR_Image, ''];
    }

    public function edit($azonosito)
    {
        $user = User::where('azonosito', $azonosito )->first();
        if(!empty($user)) {
            return view('felhasznalok.edit', compact('user'));
        }
    }

    public function changepassword($azonosito)
    {
        $user = User::where('azonosito', $azonosito )->first();
        if(!empty($user)) {
            return view('felhasznalok.changepassword', compact('user'));
        }
    }

}
