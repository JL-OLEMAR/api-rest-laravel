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

//Cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// RUTAS DEL API

    /*METODOS HHTP comunes
     * GET: Conseguir datos o recursos
     * POST: Guardar daatos o reecursos o hcer logica desde un formulario
     * PUT: Actualizar datos o recursos
     * DELETE:  Eliminar datos o recursos
     */

    //Rutas de prueba
    //Route::get('/usuario/pruebas','UserController@pruebas');
    //Route::get('/categoria/pruebas','CategoryController@pruebas');
    //Route::get('/entrada/pruebas','PostController@pruebas');

    //Rutas del controlador de usuarios
    Route::post('/api/register','UserController@register');
    Route::post('/api/login','UserController@login');
    Route::put('/api/user/update','UserController@update');
    Route::post('/api/user/upload','UserController@upload')->middleware(ApiAuthMiddleware::class);
    Route::get('/api/user/avatar/{filename}','UserController@getImage');
    Route::get('/api/user/detail/{id}','UserController@detail');

    //Rutas del controlador de categorias
    Route::resource('/api/category', 'CategoryController')->middleware(ApiAuthMiddleware::class);;

    //Rutas del controlador de entrada
    Route::resource('/api/entrada', 'PostController')->middleware(ApiAuthMiddleware::class);;
