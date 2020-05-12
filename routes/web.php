<?php

Route::get('/incompatible_browser', function () {
    return 'Hiba';
})->name("incompatible_browser");
//incompatible_browser
//<editor-fold desc="Auth">
/*
--------------------------------------------------------------------------
Auth
--------------------------------------------------------------------------
*/

Route::post('/widgetdata', 'HomeController@widgetdata')->name('widgetdata');


Route::get('/', 'HomeController@index')->middleware(array('auth', '2fa'));

Route::get('/hirek', function () {
    return view('hirek');
})->middleware(array('auth', '2fa'))->name('hirek');

Route::get('privacypolicy', function (){
    return view('pp');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware(array('auth', '2fa'));
Route::get('/logout', function(){
    Auth::logout();
    Session::flush();
    return Redirect::to('/');
});
/*
--------------------------------------------------------------------------
Auth
--------------------------------------------------------------------------
*/
//</editor-fold>

//<editor-fold desc="Google 2FA">
/*
--------------------------------------------------------------------------
Google2FA
--------------------------------------------------------------------------
*/
Route::get('/complete-registration', 'Auth\RegisterController@completeRegistration')->name('cregistration');

Route::post('/2fa', function () {
    return redirect(URL()->previous());
})->name('2fa')->middleware('2fa');

Route::get('/re-authenticate', 'HomeController@reauthenticate');

/*
--------------------------------------------------------------------------
Google2FA
--------------------------------------------------------------------------
*/
//</editor-fold>

Route::middleware(['auth','2fa' ])->group(function () {

    Route::get('/office', 'HomeController@office');

});

Route::middleware(['auth','2fa' ])->group(function () {

    Route::as('felhasznalok.')
        ->prefix('felhasznalok')
        ->group(function () {

            Route::get('index', 'FelhasznalokController@index')->name('index');

            Route::get('szerkesztes/{azonosito}', 'FelhasznalokController@edit')->name('edit')->where(array('azonosito' => '[0-9]+'));

            Route::get('ujjelszo/{azonosito}', 'FelhasznalokController@changepassword')->name('changepassword')->where(array('azonosito' => '[0-9]+'));


            Route::get('register','FelhasznalokController@create')->name('register');

            /** AJAX */

            Route::get('indexdata', 'FelhasznalokController@indexData')->name('indexdata');

            Route::post('userstatuschange', 'FelhasznalokController@userStatusChange')->name('userstatuschange');

            Route::post('getqrcode', 'FelhasznalokController@getQrcode')->name('getqrcode');

        });


    Route::as('autok.')
        ->prefix('autok')
        ->group(function () {

            Route::get('index', 'CarController@index')->name('index');

            Route::get('create', 'CarController@create')->name('create');

            Route::get('szerkesztes/{azonosito}', 'CarController@update')->name('szerkesztes')->where(array('azonosito' => '[0-9]+'));

            Route::post('store', 'CarController@store')->name('store');

            /**AJAX*/

            Route::get('indexdata', 'CarController@indexData')->name('indexdata');

            Route::post('visible', 'CarController@visible')->name('visible');
        });

    Route::as('munkalapok.')
        ->prefix('munkalapok')
        ->group(function () {

            Route::get('index', 'MunkalapokController@index')->name('index');

            Route::get('create', 'MunkalapokController@create')->name('create');

            Route::get('szerkesztes/{azonosito}', 'MunkalapokController@update')->name('szerkesztes')->where(array('azonosito' => '[0-9]+'));

            Route::post('store', 'MunkalapokController@store')->name('store');

            /**AJAX*/

            Route::get('indexdata', 'MunkalapokController@indexData')->name('indexdata');

            Route::post('visible', 'MunkalapokController@visible')->name('visible');
        });
});
