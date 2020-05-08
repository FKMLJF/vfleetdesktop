<?php

namespace App\Http\Controllers;

use App\Models\Fajlok;
use App\Models\Felhasznalok;
use App\Models\Termenyek;
use App\Models\Ugyfelek;
use App\ViewModels\InputAnyagok;
use App\ViewModels\SzolgaltatasTorzs;
use App\ViewModels\Szolgaltatsok;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function widgetdata()
    {
        $usercnt = Felhasznalok::where('tiltott', '0')->count() . ' db';
        $inpcnt = InputAnyagok::all()->count() . ' db';
        $servicetcnt = SzolgaltatasTorzs::all()->count() . ' db';
        $customercnt = Ugyfelek::all()->count() . ' db';
        $templatecnt = Fajlok::all()->count() . ' db';
        $termcnt = Termenyek::all()->count() . ' db';
        $kintlevoseg = DB::select("SELECT sum(fizetve) as tartozas FROM ugyfel_szolgaltatas_torzs WHERE teljesitve = 1 and YEAR(mikor) = YEAR(now())")[0]->tartozas;
        $servicecnt =DB::select("SELECT sum(nettoosszeg) as osszeg FROM `ugyfel_szolgaltatas_torzs` WHERE YEAR(mikor) = YEAR(now())")[0]->osszeg;
        $servicecnt =DB::select("SELECT sum(nettoosszeg) as osszeg FROM `ugyfel_szolgaltatas_torzs` WHERE YEAR(mikor) = YEAR(now())")[0]->osszeg;
        $fizetve =  "Fizetettség: ".number_format((($kintlevoseg/$servicecnt)*100),2,",","")." %";
        $kintlevoseg = "Fizetve: ".number_format($kintlevoseg,0,".", " "). " Ft";
        $servicecnt = "Összesen: ".number_format($servicecnt,0,".", " "). " Ft";

        return json_encode(array($usercnt, $inpcnt, $customercnt, $servicecnt,$servicetcnt,$templatecnt,$termcnt,$kintlevoseg, $fizetve));
    }


    public function reauthenticate(Request $request)
    {
        // get the logged in user
        $user = \Auth::user();

        // initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // generate a new secret key for the user
        $user->google2fa_secret = $google2fa->generateSecretKey();

        // save the user
        $user->save();

        // generate the QR image
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        // Pass the QR barcode image to our view.
        return view('google2fa.register', ['QR_Image' => $QR_Image,
            'secret' => $user->google2fa_secret,
            'reauthenticating' => true
        ]);
    }

    public function office()
    {
        $file = public_path('teszt.docx');

        $phpword = new \PhpOffice\PhpWord\TemplateProcessor($file);

        $phpword->setValue('{ugyfelnev}','Teszt Elek');
        $phpword->setValue('{szuletesihely}','Kiskunfélegyháza');

        $phpword->saveAs('teszt.docx');

    }
}
