<?php


namespace App\ProcedureModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UgyfelekProcedureModel extends Model
{
    /**
     * @param $ugyfelId
     * @return false|string
     */
    public static function procKapcsolatok ($ugyfelId)
    {
       return DB::select("CALL ugyfel_kapcsolatok (".$ugyfelId.") ");
    }

    /**
     * @param $ugyfelId
     * @return false|string
     */
    public static function procCimek ($ugyfelId)
    {
        return DB::select("CALL ugyfel_cimek (".$ugyfelId.") ");
    }
}
