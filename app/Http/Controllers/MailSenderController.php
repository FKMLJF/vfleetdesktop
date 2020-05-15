<?php


namespace App\Http\Controllers;


use App\Models\Autok;
use App\Models\Ertesitesek;
use App\Models\Futasteljesitmeny;
use DB;
use Illuminate\Http\Request;
use Mail;

class MailSenderController
{
    public function sendmail()
    {
        $ertesitesek = Ertesitesek::whereRaw("(select max(km_ora)
            from futasteljesitmeny where auto_azonosito =ertesitesek.auto_azonosito) >= ertesitesek.utoljarakm
            and  (select rejtett from autok where azonosito=ertesitesek.auto_azonosito) = 0
            and ( user_id IN (Select id from users where root_user=?) or user_id = ? )", [\Auth::id(), \Auth::id()])->get()->toArray();
        if (!empty($ertesitesek)) {
            try {
                foreach ($ertesitesek as $item) {
                    $auto = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select id from users where root_user=?) or user_id = ? ) and azonosito = ?", [\Auth::id(), \Auth::id(), $item['auto_azonosito']])->first()->toArray();
                    $aktkm = Futasteljesitmeny::where('auto_azonosito', $item['auto_azonosito'])->max('km_ora');
                    $cimek = explode(';', $item['cimzettek']);
                    if (count($cimek) > 0) {
                        foreach ($cimek as $cim) {
                            $data = array(
                                'title' => "VFleet Értesítés",
                                'ertesites' => $item['nev'],
                                "auto" => $auto["marka"] . " " . $auto["tipus"] . " (" . $auto["rendszam"] . ") ",
                                "km_ora" => $aktkm
                            );

                            Mail::send('mail', ['data' => $data], function ($message) use ($cim, $item) {
                                $message->to($cim, $item['nev'])->subject
                                ('VFleet Értesítés');
                                $message->from('vfleetpostafleetposta@gmail.com', 'VFleet');
                            });
                        }
                        DB::table('ertesitesek')->where('azonosito', $item['azonosito'])->update(array(
                            "updated_at" => DB::raw('now()'),
                            "utoljarakm" => intval($aktkm) + intval($item['gyakorisag']),

                        ));
                    }
                }
            } catch (\Exception $e) {
                    return json_encode(["success" => false]);
            }

        }
        return json_encode(["success" => true]);
    }
}
