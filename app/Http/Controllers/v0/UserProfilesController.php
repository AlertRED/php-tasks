<?php

namespace App\Http\Controllers\v0;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User\UserProfile;
use App\Models\User\UserGroup;
use App\Http\Requests\RequestGroup;
use App\User;

class UserProfilesController extends Controller
{	
	const itemOnPage = 5;

	private function getItemRequest(string $field){
		$result = array_key_exists($field, $_REQUEST);
		abort_unless($result, 400, "Missing required parameter $field" , ["Content-Type" => "application/json"]);
		return $_REQUEST[$field];
	}

	private function generateJSON($key, $value){
		return response()->json([
							    "success" => true,
							    "data" => [
							    	$key => $value
							      ]
							]);
	}

	public function getProfile(UserProfile $userProfile){
		abort_unless($userProfile, 404, "Does not exist id=$userProfile" , ["Content-Type" => "application/json"]);
		return self::generateJSON("profile", $userProfile);
	}

	public function getProfilesByUser($userId){
		$userProfiles = UserProfile::where('user_id', $userId)->get();
		abort_unless(filled($userProfiles), 404, "Does not exist Profiles whith id=$id" , ["Content-Type" => "application/json"]);
		return self::generateJSON("profiles", $userProfiles);
	}
	
	public function getAllProfiles(){
			$page = self::getItemRequest('page');
			$userProfiles = UserProfile::paginate(self::itemOnPage)->items();
			return self::generateJSON("profiles", $userProfiles);
		}

	public function patchProfileName(UserProfile $userProfile){
		$name = self::getItemRequest('name');
		$userProfile->update(['name' => $name]);
		return self::getProfile($userProfile);
	}

	public function deleteProfile(UserProfile $userProfile){
		return response()->json(["success"=> (bool) $userProfile->delete()]);
	}

	public function getProfileDB($id){
	 	$userProfile = DB::table('user_profiles')->where('id', $id)->first();
		abort_unless($userProfile, 400, "Does not exist Profile whith id=$id" , ["Content-Type" => "application/json"]);
		return self::generateJSON("profile", $userProfile);
	}

	public function getProfilesByUserDB($userId){
		$userProfiles = DB::table('user_profiles')->where('user_id', $userId)->get();
		abort_unless(filled($userProfiles), 404, "Does not exist Profiles whith userId=$userId" , ["Content-Type" => "application/json"]);
		return self::generateJSON("profiles", $userProfiles);
	}

	public function getAllProfilesDB(){
		 $page = self::getItemRequest('page');
	 	 $userProfiles = DB::table('user_profiles')->paginate(self::itemOnPage)->items();
	 	 abort_unless(filled($userProfiles) and $page, 404, "Does not exist Page" , ["Content-Type" => "application/json"]);
 		return self::generateJSON("profiles", $userProfiles);
	}

	public function patchProfileNameDB($id){
		$name = self::getItemRequest('name');
		DB::table('user_profiles')->where('id', $id)->update(['name' => $name]);		 	 
		return $this->getProfileDB($id);
	}

	public function deleteProfileDB($id){
	 	$result = DB::table('user_profiles')->where('id', $id)->delete();
		return response()->json(["success"=> (bool)$result]);
	}

	public function postGroup(Request $request){
		$name = self::getItemRequest('name');		
		$v = Validator::make($request, ['name' => 'required|unique:user_group|max:255']);
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
