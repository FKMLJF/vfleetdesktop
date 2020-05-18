<?php


namespace App\Http\Controllers;

use App\Models\Autok;
use App\Models\Ertesitesek;
use App\Models\Futasteljesitmeny;
use App\ViewModels\ErtesitesekView;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class ertesitesekTorzsController
 * @package App\Http\Controllers
 */
class ErtesitesekController
{
    public function index()
    {
        return view('ertesitesek.index');
    }

    /**
     * @return mixed
     */
    public
    function indexData(Request $request)
    {
        $model = ErtesitesekView::query();
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->filter(function ($query) {
                if (request()->has('nev')) {
                    $query->where('nev', 'like', "%" . request('nev') . "%");
                }
                if (request()->has('auto_azonosito')) {
                    $query->where('auto_azonosito', 'like', "%" . request('auto_azonosito') . "%");
                }
                $query->whereRaw(" user_id IN (Select id from users where root_user=?) or user_id = ?", [\Auth::id(), \Auth::id()]);

            }, true)
            ->toJson();
    }

    public
    function create()
    {
        $select = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select id from users where root_user=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        return view('ertesitesek.formview', compact('select'));
    }

    public
    function update($azonosito)
    {
        $select = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select id from users where root_user=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        $model = (array)DB::table('ertesitesek')->where("azonosito", $azonosito)->get()->first();
        return view('ertesitesek.formview', compact("model", "azonosito", "select"));
    }

    public
    function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nev' => 'required',
            'km_ora' => 'required|numeric',
            'gyakorisag' => 'required|numeric',
            'cimzettek' => 'required',
            'auto_azonosito' => 'required|numeric',
        ]);
        $method = $request->post("store_method");
        // dd($request->post());

        $validator->after(function ($validator) use ($request, $method) {
            if ($method == 'mentes') {
                $minkm = Futasteljesitmeny::where('auto_azonosito', $request->post('auto_azonosito'))->max('km_ora');
                if (floatval($request->post('km_ora')) < floatval($minkm)) {
                    $validator->errors()->add('km_ora', 'Nem lehet kisebb mint az elöző kmóra állás!');
                }
            }
            if ($request->post('auto_azonosito') == -1) {
                $validator->errors()->add('auto_azonosito', "A(z) " . $request->post('auto_azonosito') . " kötelező !");
            }

        });

        if ($validator->fails()) {
            if ($method == "mentes") {
                return redirect('ertesitesek/create')
                    ->withErrors($validator)
                    ->withInput($request->input());
            } else if ($method == "modositas") {
                return redirect('ertesitesek/szerkesztes/' . $request->post('azonosito'))
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
        }

        if ($method == "mentes") {
            $ia = new Ertesitesek();
            $ia->nev = $request->post('nev');
            $ia->gyakorisag = $request->post('gyakorisag');
            $ia->km_ora = $request->post('km_ora');
            $ia->auto_azonosito = $request->post('auto_azonosito');
            $ia->utoljarakm = intval($ia->km_ora) + intval($ia->gyakorisag);
            $ia->cimzettek = $request->post('cimzettek');
            $ia->user_id = \Auth::id();
            $ia->save();
            return Redirect::to('ertesitesek/create')->with('success', 'Sikeres rögzítés!');
        } else if ($method == "modositas") {
            DB::table('ertesitesek')->where('azonosito', $request->post('azonosito'))->update(array(
                "nev" => $request->post('nev'),
                "gyakorisag" => $request->post('gyakorisag'),
                "km_ora" => $request->post('km_ora'),
                "utoljarakm" => intval($request->post('km_ora')) + intval($request->post('gyakorisag')),
                "cimzettek" => $request->post('cimzettek'),
                "auto_azonosito" => $request->post('auto_azonosito'),
                "user_id" => \Auth::id()
            ));
            return Redirect::to("ertesitesek/szerkesztes/" . $request->post('azonosito'))->with('success', 'Sikeres módosítás!');
        }


    }

    public
    function visible(Request $r)
    {
        $ertesitesek = Ertesitesek::where('azonosito', $r->post('id'))->first();
        if (!empty($ertesitesek)) {
            $ertesitesek->rejtett = ($r->post('checked') == "true") ? 1 : 0;
            $ertesitesek->save();
        }

    }

    public
    function delete(Request $request)
    {
        return DB::table('ertesitesek')->where('azonosito', $request->post('azonosito'))->delete();
    }

    public
    function minkm(Request $request)
    {
        return json_encode(array('km' => number_format(Futasteljesitmeny::where('auto_azonosito', $request->post('auto_azonosito'))->max('km_ora'), 0, ".", " ") . " Km"));
    }

}
