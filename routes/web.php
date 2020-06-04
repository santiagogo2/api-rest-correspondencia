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

Route::get('/', function () {
    return view('welcome');
});

// RUTAS PARA LOS PAISES
Route::resource('/api/country', 'CountryController');

// RUTAS PARA LOS DEPARTAMENTOS
Route::resource('/api/department', 'DeparmentController');
Route::get('/api/department/country/{country_id}', 'DeparmentController@showByCountryId');

// RUTAS PARA LOS MUNICIPIOS
Route::resource('/api/municipality', 'MunicipalityController');
Route::get('/api/municipality/department/{department_id}', 'MunicipalityController@showMunicipalitiesByDepartmentId');

// RUTAS PARA LAS DEPENDENCIAS
Route::resource('/api/dependence', 'DependenceController');

// RUTAS PARA LA CLASIFICACIÓN DE LOS USUARIOS
Route::resource('/api/user-clasification', 'UserClasificationController');

// RUTAS PARA LOS USUARIOS. ESTOS USUARIOS HACEN PARTE DE LA RADICACIÓN Y NO DEL INGRESO AL APLICATIVO
Route::resource('/api/app-users', 'AppUsersController');
Route::get('/api/app-users/text/clasification/{text}/{clasification}', 'AppUsersController@showByNameAndClasification');

// RUTAS PARA LA CREACIÓN DE DOCUMENTOS
Route::resource('/api/documents', 'DocumentsController');
Route::get('/api/filed/get-file/{filename}', 'DocumentsController@getFile');
Route::post('/api/filed/upload-file', 'DocumentsController@uploadFile');
Route::delete('/api/filed/delete-file/{filename}', 'DocumentsController@deleteFile');

// RUTAS PARA LA ADMINISTRACIÓN DE LOS ADJUNTOS
Route::resource('/api/attached', 'AttachedController');

// RUTAS PARA LOS RADICADOS DE ENTRADA
Route::resource('/api/filed-in', 'FiledInController');
Route::get('/api/filed-in/search-appuser/{userId}', 'FiledInController@showByAppUsersId');
Route::get('/api/filed-in/search-affair/{affair}', 'FiledInController@showByAffair');

// RUTAS PARA LOS RADICADOS DE SALIDA
Route::resource('/api/filed-out', 'FiledOutController');
Route::get('/api/filed-out/search-appuser/{userId}', 'FiledOutController@showByAppUsersId');
Route::get('/api/filed-out/search-affair/{affair}', 'FiledOutController@showByAffair');
Route::put('/api/filed-out/update-document/{id}', 'FiledOutController@updateDocument');

// RUTAS PARA LOS RADICADOS INTERNOS O MEMORANDOS
Route::resource('/api/memorandum', 'MemorandumController');
Route::get('/api/memorandum/search-appuser/{userId}', 'MemorandumController@showByAppUsersId');
Route::get('/api/memorandum/search-affair/{affair}', 'MemorandumController@showByAffair');

// RUTAS PARA LOS USUARIOS QUE ACCEDEN AL SISTEMA
Route::resource('/api/user', 'UserController')->middleware('api-auth');
Route::put('/api/user/update-password/{id}', 'UserController@updatePassword')->middleware('api-auth');
Route::post('/api/user/login','UserController@login');

//RUTAS PARA EL CONTROLADOR DEL EMAIL
Route::post('api/sendemail/send', 'SendEmailController@send');

// RUTAS PARA EL SERVICIO WEB SOAP
Route::get('api/prueba', 'SoapController@clima');