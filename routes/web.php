<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
  return $router->app->version();
});

// User Login
Route::post('/login', 'UserController@login');
Route::post('/user/create', 'KaryawanController@create');

$router->group(['middleware' => 'auth:api'], function () {
  // User change password
  Route::post('/user/changepassword', 'UserController@changePassword');
  // Tipe Karyawan
  Route::get('/employee/type', 'TipeKaryawanController@getAll');
  // Karyawan & user
  Route::get('/user/karyawan/getall', 'KaryawanController@getAll');
  Route::get('/user/karyawan/getallkaryawan', 'KaryawanController@getAllKaryawan');
  Route::get('/user/karyawan/getallkaryawanbysection', 'KaryawanController@getAllKaryawanBySection');
  Route::get('/user/karyawan/getsection', 'KaryawanController@getSection');
  Route::post('/user/getbyid', 'KaryawanController@getById');
  Route::post('/user/getall', 'KaryawanController@getMany');
  Route::put('/user/edit', 'KaryawanController@update');
  Route::delete('/user/delete', 'KaryawanController@delete');
  // Parameter
  Route::get('/parameter/getall', 'ParameterController@getAll');
  Route::post('/parameter/getbyid', 'ParameterController@getById');
  Route::put('/parameter/edit', 'ParameterController@update');
  // Parameter Detail
  Route::get('/parameterdetail/getall', 'ParameterDetailController@getAll');
  Route::post('/parameterdetail/getbyid', 'ParameterDetailController@getById');
  Route::post('/parameterdetail/create', 'ParameterDetailController@create');
  Route::put('/parameterdetail/edit', 'ParameterDetailController@update');
  Route::delete('/parameterdetail/delete', 'ParameterDetailController@delete');
  // Presensi
  Route::post('/presensi/getall', 'PresensiController@getAll');
  Route::post('/presensi/create', 'PresensiController@create');
  Route::put('/presensi/edit', 'PresensiController@update');
  Route::delete('/presensi/delete', 'PresensiController@delete');
  // Penilaian
  Route::post('/penilaian', 'PenilaianController@store');
  Route::post('/nilai/getall', 'PenilaianController@getAll');
  Route::post('/nilai/create', 'PenilaianController@store');
  Route::put('/nilai/edit', 'PenilaianController@update');
  Route::delete('/nilai/delete', 'PenilaianController@delete');
});
