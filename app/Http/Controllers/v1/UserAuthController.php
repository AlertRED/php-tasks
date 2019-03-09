<?php

namespace App\Http\Controllers\v1;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Transformers\UserTransformer;
use App\Http\Requests\RequestGetUsers;
use App\Http\Requests\RequestPatchUser;

use Validator;


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
    public function getUsers(RequestGetUsers $request)
    {
        $users = User::paginate(self::itemOnPage)->items();
        $format_users = [];
        foreach ($users as $user) 
            $format_users[] = fractal()->item($user)->transformWith(new UserTransformer)->toArray()['data'];
        return self::generateJSON('users', $format_users);
    } 

    public function patchUser(RequestPatchUser $request, $userId)
    {
        $user = User::where('id', $userId)->first();
        
        abort_unless($user, 404);

        $user = $user->update(['role' => $request['role'], 'name' => $request['name'], 'banned' => $request['banned']]);
        $user = User::where('id', $userId)->first();
        $user = fractal()->item($user)->transformWith(new UserTransformer)->toArray()['data'];
        return self::generateJSON('user', $user);
    }
}