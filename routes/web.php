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
Route::get('eth','App\Http\Controllers\EthController@frontpage');
Route::get('testabi','App\Http\Controllers\EthController@testAbiFunction');
Route::get('echo ','App\Http\Controllers\EthController@echoLog');
Route::get('stackEx ','App\Http\Controllers\EthController@stackEx');
Route::get('did ','App\Http\Controllers\EthController@DecetralizeID');
