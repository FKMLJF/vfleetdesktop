<?php


namespace App\Http\Controllers;


use App\Models\Autok;
use App\Models\Dokumentumok;
use App\Models\Ertesitesek;
use App\Models\Futasteljesitmeny;
use App\ViewModels\DokumentumokView;
use DB;
use Illuminate\Http\Request;
use Mail;

class MailSenderController
{
    public function sendmail()
    {

        /**
         * ÁLTALÁNOS ÉRTESITÉSEK
         */
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
                                "auto" => $auto["marka"] . " " . $auto["tipus"] ,
                                "rendszam" => $auto["rendszam"] ,
                                "km_ora" => number_format($aktkm,0,"."," "),
                                "kovetkezo" => number_format(intval($aktkm) + intval($item['gyakorisag']), 0, ".", " "),
                            );

                            Mail::send('mail', ['data' => $data], function ($message) use ($cim, $item,$auto) {
                                $message->to($cim, $item['nev'])->subject
                                ('VFleet Értesítés: '.$item['nev'] . " (" . $auto["rendszam"]. ")");
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

        /**
         * DOKUMENTUM LEJÁRTA ÉRTESITÉSEK
         */
        $dokumentumok = Dokumentumok::whereRaw(" `ertesites_nap` is not null and ABS(DATEDIFF(ig, now())) >= ertesites_nap and (ertesitve is null or ertesitve = 0)")->get()->toArray();
        if(!empty($dokumentumok)){
            foreach ($dokumentumok as $item) {
                $auto = Autok::whereRaw(" rejtett = 0 and ( user_id IN (Select id from users where root_user=?) or user_id = ? ) and azonosito = ?", [\Auth::id(), \Auth::id(), $item['auto_azonosito']])->first()->toArray();
                $niceDok = DokumentumokView::where("azonosito", $item['azonosito'])->first()->toArray();
                $cimek = explode(';', $item['cimzettek']);
                if (count($cimek) > 0) {
                    foreach ($cimek as $cim) {
                        $data = array(
                            'title' => "VFleet Értesítés",
                            'tipus' => $niceDok['tipus_id'],
                            "auto" => $niceDok['auto_azonosito'],
                            "rendszam" => $auto["rendszam"],
                            "nap" => $item["ertesites_nap"],
                            "ar" => number_format($item["netto_ar"],0,"."," "),
                            "tol" => $item["tol"],
                            "ig" => $item["ig"],
                           );

                        Mail::send('dokertesites', ['data' => $data], function ($message) use ($cim, $item,$auto, $niceDok) {
                            $message->to($cim, $item['tipus_id'])->subject
                            ('VFleet Értesítés: '.$niceDok['tipus_id'] . " lejártáról (" . $auto["rendszam"]. ")");
                            $message->from('vfleetpostafleetposta@gmail.com', 'VFleet');
                        });
                    }
                    DB::table('dokumentumok')->where('azonosito', $item['azonosito'])->update(array(
                        "updated_at" => DB::raw('now()'),
                        "ertesitve" => DB::raw('1'),

                    ));
                }
            }
        }
        return json_encode(["success" => true]);
    }
}
