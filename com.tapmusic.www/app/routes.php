<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	if(!Session::get('userID'))
    {
        return Redirect::to('/auth');
    }

    return View::make('home.index');
});

Route::post('auth', 'HomeController@Auth');
Route::controller('player', 'PlayerController');
Route::controller('queue', 'QueueController');
Route::controller('spotify', 'SpotifyController');
Route::controller('auth', 'AuthController');

// Thanks a lot Angular
Blade::setContentTags('[[', ']]');
Blade::setEscapedContentTags('[[[', ']]]');