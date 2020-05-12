<?php


namespace App\Http\Controllers;

use App\Models\Autok;
use App\Models\munkalapok;
use App\User;
use App\ViewModels\InputAnyagok;
use App\ViewModels\MunkalapokView;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class munkalapokTorzsController
 * @package App\Http\Controllers
 */
class MunkalapokController
{
    public function index()
    {
        return view('munkalapok.index');
    }

    /**
     * @return mixed
     */
    public function indexData(Request $request)
    {
        //(array)User::where('root_user', '=', \Auth::id())->get('id')
        $model = MunkalapokView::query();
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->filter(function ($query) {
                if (request()->has('nev')) {
                    $query->where('nev', 'like', "%" . request('nev') . "%");
                }
                if (request()->has('auto_azonosito')) {
                    $query->where('auto_azonosito', 'like', "%" . request('auto_azonosito') . "%");
                }

                $query->whereRaw(  "user_id IN (Select id from users where root_user=?) or user_id = ?",[\Auth::id(),\Auth::id()]);

            }, true)
            ->toJson();
    }

    public function create()
    {
        $select = Autok::whereRaw(  " rejtett = 0 and user_id IN (Select id from users where root_user=?) or user_id = ?",[\Auth::id(),\Auth::id()])->get()->toArray();
        return view('munkalapok.formview', compact( 'select'));
    }

    public function update($azonosito)
    {
        $select = Autok::whereRaw(  " rejtett = 0 and user_id IN (Select id from users where root_user=?) or user_id = ?",[\Auth::id(),\Auth::id()])->get()->toArray();
        $model = munkalapok::where("azonosito", "=", $azonosito)->first()->toArray();
        return view('munkalapok.formview', compact("model", "azonosito","select"));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nev' => 'required',
            'ar' => 'required|numeric',
            'auto_azonosito' => 'required|numeric',
        ]);
        $method = $request->post("store_method");
        // dd($request->post());

        $validator->after(function ($validator) use ($request, $method) {
            if ($request->post('auto_azonosito') == -1) {
                $validator->errors()->add('auto_azonosito', "A(z) " . $request->post('auto_azonosito') . " kötelező !");
            }

        });

        if ($validator->fails()) {
            if ($method == "mentes") {
                return redirect('munkalapok/create')
                    ->withErrors($validator)
                    ->withInput($request->input());
            } else if ($method == "modositas") {
                return redirect('munkalapok/szerkesztes/' . $request->post('azonosito'))
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
        }

        if ($method == "mentes") {
            $ia = new munkalapok();
            $ia->nev = $request->post('nev');
            $ia->ar = $request->post('ar');
            $ia->leiras = $request->post('leiras');
            $ia->created_at = $request->post('created_at');
            $ia->auto_azonosito = $request->post('auto_azonosito');
            $ia->user_id = \Auth::id();
            $ia->save();
            return Redirect::to('munkalapok/create')->with('success', 'Sikeres rögzítés!');
        } else if ($method == "modositas") {
            $ia = munkalapok::where('azonosito','=',  $request->post('azonosito'))->first();
            $ia->nev = $request->post('nev');
            $ia->ar = $request->post('ar');
            $ia->leiras = $request->post('leiras');
            $ia->created_at = $request->post('created_at');
            $ia->auto_azonosito = $request->post('auto_azonosito');
            $ia->user_id = \Auth::id();
            $ia->save();
            return Redirect::to("munkalapok/szerkesztes/" . $request->post('azonosito'))->with('success', 'Sikeres módosítás!');
        }


    }

    public function visible(Request $r)
    {
        $munkalapok = munkalapok::where('azonosito', $r->post('id'))->first();
        if (!empty($munkalapok)) {
            $munkalapok->rejtett = ($r->post('checked') == "true") ? 1 : 0;
            $munkalapok->save();
        }

    }

}