<?php

namespace App\Http\Controllers\v0;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User\UserGroup;
use App\Http\Requests\RequestGroup;
use App\User;
use Validator;

use App\Mail\addGroup;
use Illuminate\Mail\Mailable;
use App\BaseFunctions;

class UserGroupController extends Controller
{	
	

	/**
     * @SWG\POST(
     *     path="/users/group",
     *     summary="Создать новую группу",
     *     tags={"Group"},
     *		@SWG\Parameter(
     *         name="name",
     *         in="query",
     *         description="Имя новой группы",
     *         required=true,
     *         type="string",
     *     ),
     *	   @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *		   examples={"":{ 
	 *        			"success": true
	 *		  		}
	 *			}),
     * )
     */
	public static function postGroup(Request $request){
		Validator::make($request->all(), ['name' => 'required|unique:user_group|max:255'])->validate();
		$name = $request['name'];
		$group = UserGroup::create(['name' => $name]);
		BaseFunctions::sendMailToAdmin(new addGroup($group));
		return BaseFunctions::generateJSON(true, "created_group", $group);
	}

	/**
     * @SWG\GET(
     *     path="/user/{userId}/groups",
     *     summary="Получить группы пользователя",
     *     tags={"Group"},
     *	   @SWG\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         type="integer",
     *     ),
     *	   @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *			examples={"":{ 
	 *        			"success": true, 
	 *					"data":{
	 *					"groups": {
	 *		                "id": 9,
	 *		                "name": "Group1"
	 *		            		  },
	 *						  }	
     *					  }}
     *		)
     * )
     */
	public function getGroupsByUser($userId){
		$groups = User::findOrFail($userId)->groups;
		foreach ($groups as $value)
			unset($value["pivot"]);
		return BaseFunctions::generateJSON(true, "groups", $groups);
	}

	/**
     * @SWG\PATCH(
     *     path="/user/{userId}/group/{groupId}",
     *     summary="Добавление пользователя к группе",
     *     tags={"Group"},
     *		@SWG\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         type="integer",
     *     ),
     *		@SWG\Parameter(
     *         name="groupId",
     *         in="path",
     *         description="ID группы",
     *         required=true,
     *         type="integer",
     *     ),
     *	   @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *		   examples={"":{ 
	 *        			"success": true
	 *		  		}
	 *			}),
     * )
     */
	public function addUserToGroup($userId, $groupId){
		$user = User::find($userId);
		$group = UserGroup::find($groupId);
		if ($user && $group){
		   $result = $group->users()->save($user);
		   return BaseFunctions::generateJSON($user && $group);
		}
		abort(404);
	}

	/**
     * @SWG\DELETE(
     *     path="/users/groups/{groupId}",
     *     summary="Удалить группу",
     *     tags={"Group"},
     *		@SWG\Parameter(
     *         name="groupId",
     *         in="path",
     *         description="Имя группы",
     *         required=true,
     *         type="integer",
     *     ),
     *	   @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *		   examples={"":{ 
	 *        			"success": true
	 *		  		}
	 *			}),
     * )
     */

	public static function deleteGroup($groupId){
		return BaseFunctions::generateJSON((bool) UserGroup::where('id', $groupId)->delete());
	}

	/**
     * @SWG\DELETE(
     *     path="/user/{userId}/group/{groupId}",
     *     summary="Удаление пользователя из группы",
     *     tags={"Group"},
     *		@SWG\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         type="integer",
     *     ),
     *		@SWG\Parameter(
     *         name="groupId",
     *         in="path",
     *         description="ID группы",
     *         required=true,
     *         type="integer",
     *     ),
     *	   @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *		   examples={"":{ 
	 *        			"success": true
	 *		  		}
	 *			}),
     * )
     */
	public function deleteUsetByGroup($userId, $groupId){
		$user = User::find($userId);
		$group = UserGroup::find($userId);
		if ($user && $group){
			$group->users()->delete($user);
			return BaseFunctions::generateJSON($user && $group);
		}
		abort(404);
	}
}
