<?php


namespace App\Http\Controllers;

use App\Models\Autok;
use App\Models\Dokumentumok;
use App\Models\DokumentumTipusok;
use App\Models\Futasteljesitmeny;
use App\Models\Hibajegy;
use App\User;
use App\ViewModels\dokumentumokView;
use App\ViewModels\InputAnyagok;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class dokumentumokTorzsController
 * @package App\Http\Controllers
 */
class DokumentumokController
{
    public function index()
    {
        return view('dokumentumok.index');
    }

    /**
     * @return mixed
     */
    public function indexData(Request $request)
    {
        //(array)User::where('root_user', '=', \Auth::id())->get('id')
        $model = DokumentumokView::query();
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->filter(function ($query) {
                if (request()->has('tipus_id')) {
                    $query->where('tipus_id', 'like', "%" . request('tipus_id') . "%");
                }
                if (request()->has('auto_azonosito')) {
                    $query->where('auto_azonosito', 'like', "%" . request('auto_azonosito') . "%");
                }
                $query->whereRaw(" user_id IN (Select id from users where root_user=?) or user_id = ?", [\Auth::id(), \Auth::id()]);

            }, true)
            ->toJson();
    }

    public function create()
    {
        $select = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select id from users where root_user=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        $select2 = DokumentumTipusok::all()->toArray();
        return view('dokumentumok.formview', compact('select', 'select2'));
    }

    public function update($azonosito)
    {
        $select2 = DokumentumTipusok::all()->toArray();
        $select = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select id from users where root_user=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        $model = Dokumentumok::where('azonosito', $azonosito)->first()->toArray();
        return view('dokumentumok.formview', compact("model", "azonosito", "select", "select2"));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tol' => 'required|date',
            'ig' => 'required|date',
            'auto_azonosito' => 'required|numeric',
            'tipus_id' => 'required',
        ]);
        $method = $request->post("store_method");


        $validator->after(function ($validator) use ($request, $method) {
            if ($request->post('auto_azonosito') == -1) {
                $validator->errors()->add('auto_azonosito', "A(z) " . $request->post('auto_azonosito') . " kötelező !");
            }

        });

        if ($validator->fails()) {
            if ($method == "mentes") {
                return redirect('dokumentumok/create')
                    ->withErrors($validator)
                    ->withInput($request->input());
            } else if ($method == "modositas") {
                return redirect('dokumentumok/szerkesztes/' . $request->post('azonosito'))
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
        }

        if ($method == "mentes") {
            $ia = new Dokumentumok();
            $ia->tipus_id = $request->post('tipus_id');
            $ia->tol = $request->post('tol');
            $ia->ig = $request->post('ig');
            $ia->auto_azonosito = $request->post('auto_azonosito');
            $ia->leiras = $request->post('leiras');
            $ia->netto_ar = $request->post('netto_ar');
            $ia->user_id = \Auth::id();
            $ia->save();
            return Redirect::to('dokumentumok/create')->with('success', 'Sikeres rögzítés!');
        } else if ($method == "modositas") {
            DB::table('dokumentumok')->where('azonosito', $request->post('azonosito'))->update(array(
                "tipus_id" => $request->post('tipus_id'),
                "tol" => $request->post('tol'),
                "ig" => $request->post('ig'),
                "auto_azonosito" => $request->post('auto_azonosito'),
                "leiras" => $request->post('leiras'),
                "netto_ar" => $request->post('netto_ar'),
                "user_id" => \Auth::id()
            ));
            return Redirect::to("dokumentumok/szerkesztes/" . $request->post('azonosito'))->with('success', 'Sikeres módosítás!');
        }


    }

    public function visible(Request $r)
    {
        $dokumentumok = dokumentumok::where('azonosito', $r->post('id'))->first();
        if (!empty($dokumentumok)) {
            $dokumentumok->rejtett = ($r->post('checked') == "true") ? 1 : 0;
            $dokumentumok->save();
        }

    }

    public function delete(Request $request)
    {
        return DB::table('dokumentumok')->where('azonosito', $request->post('azonosito'))->delete();
    }

}
