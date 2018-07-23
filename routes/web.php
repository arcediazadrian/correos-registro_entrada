<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/link1', function ()    {
//        // Uses Auth Middleware
//    });

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
    Route::get('registro/registro', 'RegistroController@registro');

    Route::post('registro/guardar', 'RegistroController@guardar');
    
    Route::get('registro/buscar', 'RegistroController@buscar');
    
    Route::get('registro/registroPaquete', 'RegistroController@registroPaquete');

    Route::post('registro/registroEntrega', 'RegistroController@registroEntrega');


    Route::get('/', 'HomeController@home');

    Route::get('home', 'HomeController@home');

    Route::get('reporte', 'HomeController@reporte');

    Route::get('pdf', function(){
        $pdf = PDF::loadview('pdf');
        return $pdf->stream('archivo.pdf');
    });

});
