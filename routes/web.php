<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/topic', 'HomeController@createTopic');

// Route::get('/checkphone/{phone}', 'SmsController@checkPhoneNumber');

Route::post('/phone', 'HomeController@createPhone');

Route::delete('/phone/{id}', 'HomeController@deletePhone');



