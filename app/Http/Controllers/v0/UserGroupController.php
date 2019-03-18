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
	 *	   @SWG\Response(
     *         response="422",
     *         description="Failed operation",
     *		   examples={"":{
	 *			    "message": "The given data was invalid.",
	 *			    "errors": {
	 *			        "name": 
	 *			            "The name has already been taken."
	 *			    }
	 *			}
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
     *		),
     *	   @SWG\Response(
     *         response="404",
     *         description="Failed operation",
     *			examples={"":{ "message": 
     *						"No query results for model [App\\User] 71" 
     *						}
     *			}
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
	 *	   @SWG\Response(
     *         response="404",
     *         description="Failed operation",
     *		   examples={{ "message": "Not found user" },
     *					{ "message": "Not found group" }
	 *			}),
     * )
     */
	public function addUserToGroup($userId, $groupId){
		$user = User::find($userId);
		$group = UserGroup::find($groupId);
		if ($user && $group){
		   $result = $group->users()->sync($user);
		   return BaseFunctions::generateJSON(true);
		}
		abort_unless($user, 404,"Not found user");
		abort(404,"Not found group");
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
	 *	   @SWG\Response(
     *         response="404",
     *         description="Failed operation",
     *		   examples={"":{ 
	 *        			"message": "Not found group"
	 *		  		}
	 *			}),
     * )
     */

	public static function deleteGroup($groupId){
		abort_unless((bool) UserGroup::where('id', $groupId)->delete(), 404, "Not found group");
		return BaseFunctions::generateJSON();
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
	 *	   @SWG\Response(
     *         response="404",
     *         description="Failed operation",
     *		   examples={{ "message": "Not found user" },
     *			{ "message": "Not found group" }
	 *			}),
     * )
     */
	public function deleteUsetByGroup($userId, $groupId){
		$user = User::find($userId);
		$group = UserGroup::find($groupId);
		if ($user && $group){
			$group->users()->detach($user);
			return BaseFunctions::generateJSON(true);
		}
		abort_unless($user, 404,"Not found user");
		abort(404,"Not found group");
	}
}
