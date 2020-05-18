<?php


namespace App\Http\Controllers;

use App\Models\Autok;
use App\Models\Futasteljesitmeny;
use App\Models\Hibajegy;
use App\ViewModels\HibakView;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class hibakTorzsController
 * @package App\Http\Controllers
 */
class HibakController
{
    public function index()
    {
        return view('hibak.index');
    }

    /**
     * @return mixed
     */
    public function indexData(Request $request)
    {
        //(array)User::where('root_user', '=', \Auth::id())->get('id')
        $model = HibakView::query();
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->filter(function ($query) {
                if (request()->has('leiras')) {
                    $query->where('leiras', 'like', "%" . request('leiras') . "%");
                }
                if (request()->has('auto_azonosito')) {
                    $query->where('auto_azonosito', 'like', "%" . request('auto_azonosito') . "%");
                }
          //      $query->whereRaw(" user_id IN (Select id from users where root_user=?) or user_id = ?", [\Auth::id(), \Auth::id()]);

            }, true)
            ->toJson();
    }

    public function create()
    {
        $select = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select id from users where root_user=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        return view('hibak.formview', compact('select'));
    }

    public function update($azonosito)
    {
        $select = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select id from users where root_user=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        $model = (array)DB::table('hibajegy')->where("azonosito", $azonosito)->get()->first();
        return view('hibak.formview', compact("model", "azonosito", "select"));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'leiras' => 'required',
            'auto_azonosito' => 'required|numeric',
            'km_ora' => 'required|numeric',
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
                return redirect('hibak/create')
                    ->withErrors($validator)
                    ->withInput($request->input());
            } else if ($method == "modositas") {
                return redirect('hibak/szerkesztes/' . $request->post('azonosito'))
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
        }

        if ($method == "mentes") {
            $ia = new Hibajegy();
            $ia->leiras = $request->post('leiras');
            $ia->km_ora = $request->post('km_ora');
            $ia->created_at = $request->post('created_at');
            $ia->auto_azonosito = $request->post('auto_azonosito');
            $ia->user_id = \Auth::id();
            $ia->save();
            return Redirect::to('hibak/create')->with('success', 'Sikeres rögzítés!');
        } else if ($method == "modositas") {
            DB::table('hibajegy')->where('azonosito', $request->post('azonosito'))->update(array(
                "leiras" => $request->post('leiras'),
                "created_at" => $request->post('created_at'),
                "auto_azonosito" => $request->post('auto_azonosito'),
                "user_id" => \Auth::id()
            ));
            return Redirect::to("hibak/szerkesztes/" . $request->post('azonosito'))->with('success', 'Sikeres módosítás!');
        }


    }

    public function visible(Request $r)
    {
        $hibak = Hibajegy::where('azonosito', $r->post('id'))->first();
        if (!empty($hibak)) {
            $hibak->rejtett = ($r->post('checked') == "true") ? 1 : 0;
            $hibak->save();
        }

    }

    public function delete(Request $request)
    {
        return DB::table('hibajegy')->where('azonosito', $request->post('azonosito'))->delete();
    }

}
