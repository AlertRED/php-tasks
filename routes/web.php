<?php

Route::prefix('/task1')->group(function() {
	Route::get('hello_world', 'Task1\Task1Controller@helloWorld');
	Route::get('uuid', 'Task1\Task1Controller@uuid');
	Route::get('data_from_config', 'Task1\Task1Controller@data_from_config');
});


Route::get('/', function () {
    return view('layouts\app');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
