<?php

namespace Tests;

use App\Models\User\UserGroup;
use App\User;

class DAO
{
    public static function createUserRandom(string $role='User'){
	        $id = User::insert([
	            'name' => str_random(10),
	            'email' => str_random(10).'@mail.ru',
	            'password' => bcrypt('secret'),
                'api_token' => str_random(30),
                'role' => $role
	        ]);
	        return User::where('id', $id)->first();
    }

    public static function deleteUser($id){
    	User::where('id', $id)->delete();
    }

    public static function createGroup(string $name){
    	return UserGroup::insert([
            'name' => $name,
            ]);
    }

    public static function deleteGroup($id){
    	UserGroup::where('id', $id)->delete();
    }

}
