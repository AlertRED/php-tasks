<?php

namespace App\Http\Controllers\v0;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User\UserGroup;
use App\Http\Requests\RequestGroup;
use App\User;
use Validator;

class UserGroupController extends Controller
{	

	private function generateJSON($key, $value){
		return response()->json([
							    "success" => true,
							    "data" => [
							    	$key => $value
							      ]
							]);
	}

	public function postGroup(Request $request){
		Validator::make($request->all(), ['name' => 'required|unique:user_group|max:255'])->validate();
		$name = $request['name'];
		$group = UserGroup::create(['name' => $name]);
		return self::generateJSON("created_group", $group);
	}

	public function getGroupsByUser($userId){
		$groups = User::findOrFail($userId)->groups;
		foreach ($groups as $value)
			unset($value["pivot"]);
		return self::generateJSON("groups", $groups);
	}

	public function addUserToGroup($userId, $groupId){
		$user = User::find($userId);
		$group = UserGroup::find($groupId);
		if ($user && $group)
		   $result = $group->users()->save($user);
		return response()->json(["success"=> $user && $group]);
	}

	public function deleteGroup($groupId){
		return response()->json(["success"=> (bool) UserGroup::where('id', $groupId)->delete()]);
	}

	public function deleteUsetByGroup($userId, $groupId){
		$user = User::find($userId);
		$group = UserGroup::find($userId);
		if ($user && $group)
			$group->users()->delete($user);
		return response()->json(["success"=> $user && $group]);
	}
}
