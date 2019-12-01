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

use App\Services\CurlExecService;
use Illuminate\Support\Facades\Storage;
use KubAT\PhpSimple\HtmlDomParser;

Route::get('/', function () {
    return redirect()->route('cars.index');
});
Route::resource('cars', 'CarController')->only(['index', 'store']);
