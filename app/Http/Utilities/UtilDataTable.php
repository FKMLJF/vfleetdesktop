<?php


namespace App\Http\Utilities;
use App\Models\Ugyfelek;
use DataTables;
use Illuminate\Http\Request;

class UtilDataTable
{

    public static function getListDataTable($data)
    {
        $model = Ugyfelek::query();
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Részletek</a>';
                return $btn;
            })
            ->filter(function ($query) {
                if (request()->has('rovidnev')) {
                    $query->where('rovidnev', 'like', "%" . request('name') . "%");
                }

                if (request()->has('nev')) {
                    $query->where('nev', 'like', "%" . request('email') . "%");
                }
            }, true)
            ->toJson();

     /*
        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Részletek</a>';
                return $btn;
            })
            ->rawColumns(['action']);


        return $datatable->make(true);*/
    }
}
