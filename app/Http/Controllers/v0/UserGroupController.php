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
	
	public static function postGroup(Request $request){
		Validator::make($request->all(), ['name' => 'required|unique:user_group|max:255'])->validate();
		$name = $request['name'];
		$group = UserGroup::create(['name' => $name]);
		BaseFunctions::sendMailToAdmin(new addGroup($group));
		return BaseFunctions::generateJSON(true, "created_group", $group);
	}

	public function getGroupsByUser($userId){
		$groups = User::findOrFail($userId)->groups;
		foreach ($groups as $value)
			unset($value["pivot"]);
		return BaseFunctions::generateJSON(true, "groups", $groups);
	}

	public function addUserToGroup($userId, $groupId){
		$user = User::find($userId);
		$group = UserGroup::find($groupId);
		if ($user && $group){
		   $result = $group->users()->save($user);
		   return BaseFunctions::generateJSON($user && $group);
		}
		abort(404);
	}

	public static function deleteGroup($groupId){
		return BaseFunctions::generateJSON((bool) UserGroup::where('id', $groupId)->delete());
	}

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
