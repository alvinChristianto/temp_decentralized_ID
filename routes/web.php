<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EthController;
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

Route::get('/cek-log/{id}','App\Http\Controllers\EthController@tokenURI')->name('tokenURI');    //bisa ditambahkan parameter Id log
Route::get('/log-berdasar-address','App\Http\Controllers\EthController@showLogsByOwner');
Route::get('/save-logs','App\Http\Controllers\EthController@saveLogsToRopsten');
