<?php


namespace App\Http\Controllers;

use App\Models\Autok;
use App\Models\Licensz;
use App\User;
use App\ViewModels\InputAnyagok;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class autokTorzsController
 * @package App\Http\Controllers
 */
class CarController
{
    public function index()
    {
        $licenscnt = Licensz::whereRaw(" root_user IN (Select id from users where root_user=?) or root_user = ? ", [\Auth::id(), \Auth::id()])->first();
        return view('autok.index', compact('licenscnt'));
    }

    /**
     * @return mixed
     */
    public function indexData(Request $request)
    {
        $model = Autok::query();
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->filter(function ($query) {
                $query->whereRaw(" user_id IN (Select id from users where root_user=?) or user_id = ?", [\Auth::id(), \Auth::id()]);
                $query->orWhere('user_id', '=', User::where('id', '=', \Auth::id())->first()->root_user);

                if (request()->has('nev')) {
                    $query->where('nev', 'like', "'%" . request('nev') . "%'");
                }

            }, true)
            ->toJson();
    }

    public function create()
    {
        $year = null;
        for ($i = date('Y'); $i > 1900; $i--) {
            $year [] = $i;
        }
        return view('autok.formview', compact('year'));
    }

    public function update($azonosito)
    {
        $year = null;
        for ($i = date('Y'); $i > 1900; $i--) {
            $year [] = $i;
        }
        $model = Autok::where("azonosito", "=", $azonosito)->first()->toArray();
        return view('autok.formview', compact("model", "azonosito", "year"));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'rendszam' => 'required',
        ]);
        $method = $request->post("store_method");
        // dd($request->post());

        $validator->after(function ($validator) use ($request, $method) {

            $cnt = Autok::where("rendszam", "=", $request->post('rendszam'))->count();

            $carcnt = Autok::whereRaw(" user_id IN (Select id from users where root_user=?) or user_id = ? and rejtett = 0 ", [\Auth::id(), \Auth::id()])->count();
            $licenscnt = Licensz::whereRaw(" root_user IN (Select id from users where root_user=?) or root_user = ? ", [\Auth::id(), \Auth::id()])->first();
            if (intval($carcnt) >= intval($licenscnt->jarmu)) {
                $validator->errors()->add('licensz', "Elfogyott a licensz! Maximális járműlicensz: " . $licenscnt->jarmu);
            }
            if ($method == "mentes") {
                if ($cnt > 0) {
                    $validator->errors()->add('rendszam', "A(z) " . $request->post('rendszam') . " már létezik !");
                }
                if ($request->post('uzemmod') == -1) {
                    $validator->errors()->add('uzemmod', "A(z) " . $request->post('uzemmod') . " kötelező !");
                }
            } else if ($method == "modositas") {
                if ($cnt > 1) {
                    $validator->errors()->add('rendszam', "A(z) " . $request->post('rendszam') . " már létezik !");
                }
            }

        });

        if ($validator->fails()) {
            if ($method == "mentes") {
                return redirect('autok/create')
                    ->withErrors($validator)
                    ->withInput($request->input());
            } else if ($method == "modositas") {
                return redirect('autok/szerkesztes/' . $request->post('azonosito'))
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
        }

        if ($method == "mentes") {
            $ia = new Autok();
            $ia->rendszam = strtoupper($request->post('rendszam'));
            $ia->uzemmod = $request->post('uzemmod');
            $ia->alvazszam = strtoupper($request->post('alvazszam'));
            $ia->motorszam = strtoupper($request->post('motorszam'));
            $ia->gyartas_ev = $request->post('gyartas_ev');
            $ia->forgalomba_helyezes_ev = $request->post('forgalomba_helyezes_ev');
            $ia->marka = $request->post('marka');
            $ia->tipus = $request->post('tipus');
            $ia->teljesitmeny = $request->post('teljesitmeny');
            $ia->egyuttestomeg = $request->post('egyuttestomeg');
            $ia->hengerurtartalom = $request->post('hengerurtartalom');
            $ia->tomeg = $request->post('tomeg');
            $ia->user_id = \Auth::id();
            $ia->save();
            return Redirect::to('autok/create')->with('success', 'Sikeres rögzítés!');
        } else if ($method == "modositas") {
            $ia = Autok::where('azonosito', $request->post('azonosito'))->first();
            $ia->rendszam = strtoupper($request->post('rendszam'));
            $ia->uzemmod = $request->post('uzemmod');
            $ia->alvazszam = strtoupper($request->post('alvazszam'));
            $ia->motorszam = strtoupper($request->post('motorszam'));
            $ia->gyartas_ev = $request->post('gyartas_ev');
            $ia->forgalomba_helyezes_ev = $request->post('forgalomba_helyezes_ev');
            $ia->marka = $request->post('marka');
            $ia->tipus = $request->post('tipus');
            $ia->teljesitmeny = $request->post('teljesitmeny');
            $ia->egyuttestomeg = $request->post('egyuttestomeg');
            $ia->hengerurtartalom = $request->post('hengerurtartalom');
            $ia->tomeg = $request->post('tomeg');
            $ia->user_id = \Auth::id();
            $ia->save();
            return Redirect::to("autok/szerkesztes/" . $request->post('azonosito'))->with('success', 'Sikeres módosítás!');
        }


    }

    public function visible(Request $request)
    {

        if(($request->post('checked') == "true")){
            $autok = Autok::where('azonosito', $request->post('id'))->first();
            if (!empty($autok)) {
                $autok->rejtett = 1;
                $autok->save();
            }
            return json_encode(['license' => true]);
        }
        $carcnt = Autok::whereRaw(" user_id IN (Select id from users where root_user=?) or user_id = ? and rejtett = 0 ", [\Auth::id(), \Auth::id()])->count();
        $licenscnt = Licensz::whereRaw(" root_user IN (Select id from users where root_user=?) or root_user = ? ", [\Auth::id(), \Auth::id()])->first();

            if (intval($carcnt) >= intval($licenscnt->jarmu)) {
                return json_encode(['license' => false]);
            }

        $autok = Autok::where('azonosito', $request->post('id'))->first();
        if (!empty($autok)) {
            $autok->rejtett = ($request->post('checked') == "true") ? 1 : 0;
            $autok->save();
        }
        return json_encode(['license' => true]);

    }
}
