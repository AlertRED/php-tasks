<?php

use Illuminate\Http\Request;

Route::prefix('v0/')->group(function() {
Route::get('users/profile/{id}', 'v0\UserProfilesController@getProfile');
Route::patch('users/profile/{id}', 'v0\UserProfilesController@patchProfileName');
Route::delete('users/profile/{id}', 'v0\UserProfilesController@deleteProfile');

Route::get('user/{userId}/profiles', 'v0\UserProfilesController@getProfilesByUser');
Route::get('users/profiles', 'v0\UserProfilesController@getAllProfiles');
});


Route::prefix('v0/db/')->group(function() {
	Route::get('users/profile/{id}', 'v0\UserProfilesController@getProfile');
	Route::patch('users/profile/{id}', 'v0\UserProfilesController@patchProfileName');
	Route::delete('users/profile/{id}', 'v0\UserProfilesController@deleteProfile');

	Route::get('user/{userId}/profiles', 'v0\UserProfilesController@getProfilesByUser');
	Route::get('users/profiles', 'v0\UserProfilesController@getAllProfiles');
});




// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
