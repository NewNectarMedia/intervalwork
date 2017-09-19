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

// Route::get('profile', function () {
//     // Only authenticated users may enter...
// })->middleware('auth');


Route::match(array('GET', 'POST'), '/incoming', function()
{ 
  //$xml = '<Response><Say>Hello - your app just answered the phone. Neat, eh?</Say></Response>';
  $twiml = new Twilio\Twiml();
  $twiml->say('Hello - your app just answered the phone. Neat, eh?', array('voice' => 'alice'));
  $response = Response::make($twiml, 200);
  $response->header('Content-Type', 'text/xml');
  return $response;
});