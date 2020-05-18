<?php

namespace App\Http\Controllers;

use App\Models\Felhasznalok;
use App\Models\Licensz;
use App\Models\Szerepkor;
use App\Models\SzerepkorKapcsolo;
use App\User;
use App\ViewModels\UserView;
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
        $this->middleware(['auth', '2fa', "check_role"]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $licenscnt = Licensz::whereRaw(" root_user IN (Select id from users where root_user=?) or root_user = ? ", [\Auth::id(), \Auth::id()])->first();

        return view('felhasznalok.index', compact('licenscnt'));
    }

    public function indexData(Request $request)
    {
        $model = UserView::query();
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->filter(function ($query) {
                if (request()->has('username')) {
                    $query->where('username', 'like', "%" . request('username') . "%");
                }

            }, true)
            ->toJson();
    }

    public function userStatusChange(Request $r)
    {
        $user = User::where('id', $r->post('id'))->first();
        if (!empty($user)) {
            if ($r->post('eventtype') == "megerositett") {
                $user->megerositve = ($r->post('checked') == "true") ? 1 : 0;
                $user->save();
            } else if ($r->post('eventtype') == "tiltott") {
                if(($r->post('checked') == "true")){
                    $user->tiltott = ($r->post('checked') == "true") ? 1 : 0;
                    $user->save();
                    return json_encode(['license' => true]);
                }
                $usercnt = User::whereRaw(" (id IN (Select id from users where root_user=?) or id = ?) and tiltott = 0 ", [\Auth::id(), \Auth::id()])->count();
                $licenscnt = Licensz::whereRaw(" root_user IN (Select id from users where root_user=?) or root_user = ? ", [\Auth::id(), \Auth::id()])->first();

                if (intval($usercnt) >= intval($licenscnt->felhasznalo)) {
                    return json_encode(['license' => false]);
                }
                $user->tiltott = ($r->post('checked') == "true") ? 1 : 0;
                $user->save();
            }
        }
    }

    public function getQrcode(Request $r)
    {
        $user = User::where('id', $r->post('id'))->first();
        $QR_Image = '';
        if (!empty($user)) {
            $google2fa = app('pragmarx.google2fa');
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $user->google2fa_secret
            );
            return json_encode(['qr' => $QR_Image, 'name' => $user->name]);
        }
        return [$QR_Image, ''];
    }

    public function edit($azonosito)
    {
        $user = User::where('id', $azonosito)->first();
        $select = Szerepkor::all('azonosito', 'nev')->toArray();
        $role = SzerepkorKapcsolo::where('user_id', $azonosito)->first();
        if (!empty($role)) {
            $role = $role->szerepkor_id;
        } else {
            $role = -1;
        }
        if (!empty($user)) {
            return view('felhasznalok.edit', compact('user', 'select', 'role'));
        }
    }

    public function changepassword($azonosito)
    {
        $user = User::where('id', $azonosito)->first();
        if (!empty($user)) {
            return view('felhasznalok.changepassword', compact('user'));
        }
    }

    public function create()
    {
        $select = Szerepkor::all('azonosito', 'nev')->toArray();
        return view('felhasznalok.create', compact('select'));
    }

}
