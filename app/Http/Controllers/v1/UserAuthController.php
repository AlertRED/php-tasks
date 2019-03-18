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

    /**
     * @SWG\GET(
     *     path="/auth/login",
     *     summary="Вход",
     *     tags={"UserAuth"},
     *      @SWG\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email пользователя",
     *         required=true,
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="password",
     *         in="query",
     *         description="Пароль пользователя",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *         examples={"":{
     *                       "success": true,
     *                       "data": {
     *                           "token": "BAeURuQs7BZ8zGyBF57JDaXh2HqUwdcN"
     *                       }
     *                   }
     *          }),
     *     @SWG\Response(
     *         response="401",
     *         description="Failed operation",
     *         examples={"":{
     *                       "message": "Incorrect login or password"
     *                   }
     *          }),
     * )
     */
    public function userLogin(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        abort_unless($user && password_verify($request['password'],$user['password']), 401,"Incorrect login or password");
        return BaseFunctions::generateJSON(true ,'token', $user['api_token']);
    }

    /**
     * @SWG\GET(
     *     path="/auth/logout",
     *     summary="Выход",
     *     tags={"UserAuth"},
     *      @SWG\Parameter(
     *         name="api_token",
     *         in="query",
     *         description="Api_token пользователя",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *         examples={"":{
     *                       "success": true,
     *                   }
     *          }),
     *     @SWG\Response(
     *         response="404",
     *         description="Failed operation",
     *         examples={"":{
     *                   "message": "Incorrect api"
     *               }
     *          }),
     * )
     */
    public function userLogout(Request $request)
    {   
        $user = User::where('api_token', $request['api_token'])->first();
        if ($user){ 
            $result = $user->update(['api_token' => str_random(30)]);
            return BaseFunctions::generateJSON($result);
        }
        abort(404,"Incorrect api");
    }

    /**
     * @SWG\GET(
     *     path="/users",
     *     summary="Получить список пользователей",
     *     tags={"UserAuth"},
     *      @SWG\Parameter(
     *         name="api_token",
     *         in="query",
     *         description="Api_token пользователя",
     *         required=true,
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email пользователя",
     *         required=true,
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="password",
     *         in="query",
     *         description="Пароль пользователя",
     *         required=true,
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         description="Страница списка",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *         examples={"":{
     *                       "success": true,
     *                       "data":
     *                       {
     *                           "id": 4,
     *                           "name": "nyqj8NdeYB",
     *                           "email": "0DRR9VJtVN@mail.ru",
     *                           "role": "User",
     *                           "banned": false
     *                       },
     *                       {
     *                           "id": 5,
     *                           "name": "ElUqT66dSG",
     *                           "email": "Xpt4kijhPC@mail.ru",
     *                           "role": "User",
     *                           "banned": false
     *                       }
     *                   }
     *          }),
     *     @SWG\Response(
     *         response="401",
     *         description="Failed operation",
     *         examples={"":{
     *                   "success": false,
     *                   "message": "Unauthorized"
     *               }
     *          }),
     * )
     */

    public function getUsers(RequestGetUsers $request)
    {
        $users = User::paginate(self::itemOnPage)->items();
        $format_users = [];
        foreach ($users as $user) 
            $format_users[] = fractal()->item($user)->transformWith(new UserTransformer)->toArray()['data'];
        return BaseFunctions::generateJSON(true, 'users', $format_users);
    } 


    /**
     * @SWG\PATCH(
     *     path="/user/{userId}",
     *     summary="Изменить данные пользователя",
     *     tags={"UserAuth"},
     *      @SWG\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         type="integer",
     *     ),
     *      @SWG\Parameter(
     *         name="role",
     *         in="query",
     *         description="Новая роль пользователя",
     *         required=true,
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="name",
     *         in="query",
     *         description="Новое имя пользователя",
     *         required=true,
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="banned",
     *         in="query",
     *         description="Новый статус бана пользователя",
     *         required=true,
     *         type="boolean",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *         examples={"":{
     *                       "success": true,
     *                       "data":
     *                       {
     *                           "id": 4,
     *                           "name": "nyqj8NdeYB",
     *                           "email": "0DRR9VJtVN@mail.ru",
     *                           "role": "User",
     *                           "banned": false
     *                       }
     *                   }
     *          }),
     *     @SWG\Response(
     *         response="401",
     *         description="Failed operation",
     *         examples={"":{
     *                   "success": false,
     *                   "message": "Unauthorized"
     *               }
     *          }),
     *     @SWG\Response(
     *         response="404",
     *         description="Failed operation",
     *         examples={"":{
     *                   "message": "Not found user"
     *               }
     *          }),
     * )
     */
    public function patchUser(RequestPatchUser $request, $userId)
    {
        $user = User::where('id', $userId)->first();
        abort_unless($user, 404,"Not found user");
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