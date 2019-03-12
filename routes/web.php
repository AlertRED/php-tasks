<?php

Route::prefix('/task1')->group(function() {
	Route::get('hello_world', 'Task1\Task1Controller@helloWorld');
	Route::get('uuid', 'Task1\Task1Controller@uuid');
	Route::get('data_from_config', 'Task1\Task1Controller@data_from_config');
});




Route::get('/groups', 'WebController\UserController@getGroups');
Route::delete('/group/{groupId}', 'WebController\UserController@deleteGroup');
Route::post('/group', 'WebController\UserController@createGroup');

Route::get('/profiles', 'WebController\UserController@getProfiles');
Route::delete('/profile/{profileId}', 'WebController\UserController@deleteProfile');
Route::post('/profile', 'WebController\UserController@createProfile');





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
