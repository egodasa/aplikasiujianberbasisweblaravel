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
Route::group(['prefix'=>'ujian'],function(){
	Route::get('/','Ujian@showAllUjian');
});
Route::group(['prefix'=>'api/ujian'],function(){
	Route::get('/{id?}','Ujian@getUjian');
	Route::post('/','Ujian@addUjian');
	Route::delete('/{id}','Ujian@deleteUjian');
	Route::put('/{id}','Ujian@updateUjian');
	//Hasil ujian
	Route::get('/{id}/hasil','Ujian@hasilUjian');
	Route::post('/{id}/hasil','Ujian@addHasilUjian');
	//Soal Ujian
	Route::post('/{id}/soal','soalUjian@addSoalUjian');
	Route::delete('/{id}/soal/{idSU}','soalUjian@deleteSoalUjian');
	Route::get('/{id}/soal','soalUjian@getSoalUjian');
	//Peserta ujian
	Route::post('/{id}/peserta','pesertaUjian@addPesertaUjian');
	Route::delete('/{id}/peserta/{idPU}','pesertaUjian@deletePesertaUjian');
	Route::get('/{id}/peserta/{idPeserta?}','pesertaUjian@getPesertaUjian');
});
Route::group(['prefix'=>'api/peserta'],function(){
	Route::get('/{id?}','Peserta@getPeserta');
	Route::post('/','Peserta@addPeserta');
	Route::delete('/{id}','Peserta@deletePeserta');
	Route::put('/{id}','Peserta@updatePeserta');
});
Route::group(['prefix'=>'api/soal'],function(){
	Route::get('/{id?}','Soal@getSoal');
	Route::post('/','Soal@addSoal');
	Route::delete('/{id}','Soal@deleteSoal');
	Route::put('/{id}','Soal@updateSoal');
});
Route::get('/view',function(){
	return view('halaman');
	});
