<?php

namespace App\Http\Controllers\v1;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Transformers\UserTransformer;
use App\Http\Requests\RequestGetUsers;
use App\Http\Requests\RequestPatchUser;

use Validator;
use App\BaseFunctions;
use App\Mail\patchUser;


class UserAuthController extends Controller
{

    const itemOnPage = 5;

    public function userLogin(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        abort_unless($user && password_verify($request['password'],$user['password']), 404);
        return BaseFunctions::generateJSON(true ,'token', $user['api_token']);

    }

    public function userLogout(Request $request)
    {   
        $user = User::where('api_token', $request['api_token'])->first();
        if ($user){ 
            $result = $user->update(['api_token' => str_random(30)]);
            return BaseFunctions::generateJSON($result);
        }
        abort(404);
    }

    public function getUsers(RequestGetUsers $request)
    {
        $users = User::paginate(self::itemOnPage)->items();
        $format_users = [];
        foreach ($users as $user) 
            $format_users[] = fractal()->item($user)->transformWith(new UserTransformer)->toArray()['data'];
        return BaseFunctions::generateJSON(true, 'users', $format_users);
    } 

    public function patchUser(RequestPatchUser $request, $userId)
    {
        $user = User::where('id', $userId)->first();
        
        abort_unless($user, 404);
        $old_user = clone $user;
        $user = $user->update(['role' => $request['role'], 'name' => $request['name'], 'banned' => $request['banned']]);
        if ($user){
            $user = User::where('id', $userId)->first();
            $user = fractal()->item($user)->transformWith(new UserTransformer)->toArray()['data'];
            BaseFunctions::sendMailToAdmin(new patchUser($request['email'], $old_user, $user));
            return BaseFunctions::generateJSON(true, 'user', $user);
        }
        return BaseFunctions::generateJSON(false);
    }
}