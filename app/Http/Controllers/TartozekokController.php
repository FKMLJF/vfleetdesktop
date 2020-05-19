<?php


namespace App\Http\Controllers;

use App\Models\Autok;
use App\Models\AutokTartozekok;
use App\Models\Futasteljesitmeny;
use App\Models\Tankola;
use App\Models\tartozek;
use App\ViewModels\TartozekokView;
use App\ViewModels\TartozekView;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class tartozekTorzsController
 * @package App\Http\Controllers
 */
class TartozekokController
{
    public function index()
    {
        return view('tartozekok.index');
    }

    /**
     * @return mixed
     */
    public function indexData(Request $request)
    {
        $model = TartozekokView::query();
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->filter(function ($query) {
                if (request()->has('auto')) {
                    $query->where('auto', 'like', "%" . request('auto') . "%");
                }

                $query->whereRaw(" user_id IN (Select root_user from users where id=?) or user_id = ?", [\Auth::id(), \Auth::id()]);

            }, true)
            ->toJson();
    }

    public function create()
    {
        $select = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select root_user from users where id=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        return view('tartozekok.formview', compact('select'));
    }

    public function update($azonosito)
    {
        $select = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select root_user from users where id=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        $model = (array)DB::table('autok_tartozekok')->where("azonosito", $azonosito)->get()->first();
        return view('tartozekok.formview', compact("model", "azonosito", "select"));
    }

    public function store(Request $request)
    {
        $method = $request->post("store_method");

        $validator = \Validator::make($request->all(), [
            'tartozek_neve' => 'required',
            'mennyiseg' => 'required|numeric',
            'auto_azonosito' => 'required|numeric',
            'lejarat' => 'date|nullable',
            'ertesites_nap' => 'numeric|nullable',
        ]);


        if ($validator->fails()) {
            if ($method == "mentes") {
                return redirect('tartozek/create')
                    ->withErrors($validator)
                    ->withInput($request->input());
            } else if ($method == "modositas") {
                return redirect('tartozek/szerkesztes/' . $request->post('azonosito'))
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
        }

        if ($method == "mentes") {
            $ia = new AutokTartozekok();
            $ia->tartozek_neve = $request->post('tartozek_neve');
            $ia->mennyiseg = $request->post('mennyiseg');
            $ia->leiras = $request->post('leiras');
            $ia->lejarat = $request->post('lejarat');
            $ia->ertesites_nap = $request->post('ertesites_nap');
            $ia->cimzettek = $request->post('cimzettek');
            $ia->auto_azonosito = $request->post('auto_azonosito');
            $ia->user_id = \Auth::id();
            $ia->save();
            return Redirect::to('tartozek/create')->with('success', 'Sikeres rögzítés!');
        } else if ($method == "modositas") {
            DB::table('autok_tartozekok')->where('azonosito', $request->post('azonosito'))->update(array(
                "tartozek_neve" => $request->post('tartozek_neve'),
                "mennyiseg" => $request->post('mennyiseg'),
                "leiras" => $request->post('leiras'),
                "lejarat" => $request->post('lejarat'),
                "ertesites_nap" => $request->post('ertesites_nap'),
                "cimzettek" => $request->post('cimzettek'),
                "auto_azonosito" => $request->post('auto_azonosito'),
                "updated_at" => DB::raw('Now()'),
                "user_id" => \Auth::id()
            ));
            return Redirect::to("tartozek/szerkesztes/" . $request->post('azonosito'))->with('success', 'Sikeres módosítás!');
        }


    }

    public function visible(Request $r)
    {
        $tartozek = AutokTartozekok::where('azonosito', $r->post('id'))->first();
        if (!empty($tartozek)) {
            $tartozek->rejtett = ($r->post('checked') == "true") ? 1 : 0;
            $tartozek->save();
        }

    }

    public function delete(Request $request)
    {
        if(session('role', 0) == 3) {
            return DB::table('autok_tartozekok')->where('azonosito', $request->post('azonosito'))->delete();
        }
    }

}
