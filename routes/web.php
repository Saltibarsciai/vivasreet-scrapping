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
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://search.vivastreet.co.uk/cars/gb?lb=new&search=1&start_field=1&individual_type=individual&keywords=&cat_1=45&geosearch_text=&searchGeoId=&sp_common_price%5Bstart%5D=&sp_common_price%5Bend%5D=&sp_vehicules_mileage%5Bstart%5D=&sp_vehicules_mileage%5Bend%5D=&sp_common_year%5Bstart%5D=2017&sp_common_year%5Bend%5D=&sp_vehicules_energy=');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; CrawlBot/1.0.0)');
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    curl_close($ch);
    $regex = "|<img class=\"clad__image photo\" src=\"(.*?)\" original=\"(.*?)\"|";
    preg_match_all($regex, $html, $matches);
    return view('partials.master')->with('html', $matches);
});
Route::resource('cars', 'CarController');
