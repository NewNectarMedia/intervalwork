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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/topic', 'HomeController@createTopic');

Route::post('/phone', 'HomeController@createPhone');

Route::delete('/phone/{id}', 'HomeController@deletePhone');

// Route::get('profile', function () {
//     // Only authenticated users may enter...
// })->middleware('auth');


