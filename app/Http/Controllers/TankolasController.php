<?php


namespace App\Http\Controllers;

use App\Models\Autok;
use App\Models\Futasteljesitmeny;
use App\Models\Tankola;
use App\Models\tankolas;
use App\ViewModels\TankolasView;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class TankolasTorzsController
 * @package App\Http\Controllers
 */
class TankolasController
{
    public function index()
    {
        return view('tankolas.index');
    }

    /**
     * @return mixed
     */
    public function indexData(Request $request)
    {
        $model = TankolasView::query();
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
        return view('tankolas.formview', compact('select'));
    }

    public function update($azonosito)
    {
        $select = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select root_user from users where id=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        $model = (array)DB::table('tankolas')->where("azonosito", $azonosito)->get()->first();
        return view('tankolas.formview', compact("model", "azonosito", "select"));
    }

    public function store(Request $request)
    {
        $method = $request->post("store_method");
        if ($method == 'mentes') {
            $validator = \Validator::make($request->all(), [
                'liter' => 'required|numeric',
                'osszeg' => 'required|numeric',
                'auto_azonosito' => 'required|numeric',
                'km_ora' => 'required|numeric',
            ]);
        }
        else{
            $validator = \Validator::make($request->all(), [
                'liter' => 'required|numeric',
                'osszeg' => 'required|numeric',
            ]);
        }


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
                return redirect('tankolas/create')
                    ->withErrors($validator)
                    ->withInput($request->input());
            } else if ($method == "modositas") {
                return redirect('tankolas/szerkesztes/' . $request->post('azonosito'))
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
        }

        if ($method == "mentes") {
            $ia = new Tankola();
            $ia->osszeg = $request->post('osszeg');
            $ia->km_ora = $request->post('km_ora');
            $ia->liter = $request->post('liter');
            $ia->auto_azonosito = $request->post('auto_azonosito');
            $ia->user_id = \Auth::id();
            $ia->save();
            return Redirect::to('tankolas/create')->with('success', 'Sikeres rögzítés!');
        } else if ($method == "modositas") {
            DB::table('tankolas')->where('azonosito', $request->post('azonosito'))->update(array(
                "liter" => $request->post('liter'),
                "osszeg" => $request->post('osszeg'),
                "update_at" => DB::raw('Now()'),
                "user_id" => \Auth::id()
            ));
            return Redirect::to("tankolas/szerkesztes/" . $request->post('azonosito'))->with('success', 'Sikeres módosítás!');
        }


    }

    public function visible(Request $r)
    {
        $Tankolas = tankolas::where('azonosito', $r->post('id'))->first();
        if (!empty($Tankolas)) {
            $Tankolas->rejtett = ($r->post('checked') == "true") ? 1 : 0;
            $Tankolas->save();
        }

    }

    public function delete(Request $request)
    {
        if(session('role', 0) == 3) {
            return DB::table('tankolas')->where('azonosito', $request->post('azonosito'))->delete();
        }
    }

}
