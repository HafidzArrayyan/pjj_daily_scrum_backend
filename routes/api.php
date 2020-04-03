<?php

use Illuminate\Http\Request;

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');

Route::group(['middleware'=> ['jwt.verify']], function() {
    Route::get('login/check', "UserController@LoginCheck");
    Route::post('logout', "UserController@logout");

    //data activity
    Route::post('activity', "DailyscrumController@store");
    Route::get('activity', "DailyscrumController@getAll");
});