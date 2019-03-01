<?php

namespace App\Http\Controllers\v1;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{

    const itemOnPage = 5;

    private function generateJSON($key, $value){
        return response()->json([
                                "success" => true,
                                "data" => [
                                    $key => $value
                                  ]
                            ]);
    }

    public function userLogin(Request $request)
    {
        $api_token = User::where('email', $request['email'])->first()['api_token'];
        return self::generateJSON('token', $api_token);
    }

    public function userLogout(Request $request)
    {   
        $user = User::where('api_token', $request['api_token'])->first();
        if ($user){ 
            $result = $user->update(['api_token' => str_random(30)]);
            return response()->json(["success"=> result]);
        }
        abort(404);
    }
    public function getUsers(Request $request)
    {
        $users = User::paginate(self::itemOnPage)->items();

        $new_users = [];
        foreach ($users as $value)
            $new_users[] = 
            ['id' => $value['id'],
            'name' => $value['name'],
            'email' => $value['email'],
            'role' => $value['role'],
            'banned' => $value['banned']];

        return self::generateJSON('users', $new_users);
    } 

    public function patchUser(Request $request, $userId)
    {
        $user = User::where('id', $userId)->first();
        if ($user){ 
            $user = $user->update(['role' => $request['role'], 'name' => $request['name'], 'banned' => $request['banned']]);
            $user = User::where('id', $userId)->first();

            $user = ['id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'banned' => $user['banned']];

            return self::generateJSON('user', $user);
        }
        abort(404);
    }
}