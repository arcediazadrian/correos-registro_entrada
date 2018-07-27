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

//Grupo de rutas que utilizan autenticacion(todas)
Route::group(['middleware' => 'auth'], function () {

//Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
#adminlte_routes

    //Rutas que sirven para ir a la pantalla de inicio
    Route::get('/', 'HomeController@index');

    Route::get('home', 'HomeController@index');

    //Ruta de registro principal, sirve para determinar que tipo de registro se mostrara a los usuarios
    Route::get('registro/registro', 'RegistroController@registro');

    //Ruta para guardar todos los registros
    Route::post('registro/guardar', 'RegistroController@guardar');
    
    //Ruta para guardar todos las validaciones
    Route::post('registro/validar', 'RegistroController@validar');
    
    //Ruta que utiliza el empleado de entrega para buscar si los paquetes estan en almacen o no
    Route::get('registro/buscar', 'RegistroController@buscar');
    
    //Ruta que muestra el registro a los empleados de Clasificacion y Almacenaje
    Route::get('registro/registroPaquete', 'RegistroController@registroPaquete');
    
    //Ruta que sirve para las combo box anidadas de pais, ciudad zona en el registro de clasificacion 
    Route::get('registro/registroPaquete/getCiudades/{id}', 'RegistroController@getCiudadesDePais');
    
    //Ruta que sirve para las combo box anidadas de pais, ciudad zona en el registro de clasificacion 
    Route::get('registro/registroPaquete/getZonas/{id}', 'RegistroController@getZonasDeCiudad');

    //Ruta que muestra el registro a los empleados de Entrega
    Route::post('registro/registroEntrega', 'RegistroController@registroEntrega');
    
    //Ruta que muestra el registro de validacion a los supervisores de Clasificacion
    Route::get('registro/validacionPaquete', 'RegistroController@validacionPaquete');

    //Ruta que sirve para mostrar los registros diarios a los usuarios y dar la opcion de generar un reporte
    Route::get('reporte', 'ReporteController@index');

    //Ruta que sirve para crear el pdf del reporte
    Route::get('reporte/pdf', 'ReporteController@reporte');


});
