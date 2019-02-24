<?php

namespace App\Http\Controllers\v0;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\UserProfile;

class UserProfilesController extends Controller
{	
	const itemOnPage = 5;

	private function getItemRequest(string $field){
		$result = array_key_exists($field, $_REQUEST);
		abort_unless($result, 400, "Missing required parameter $field" , ["Content-Type" => "application/json"]);
		return $_REQUEST[$field];
	}

	public function getProfile(UserProfile $userProfile){
		abort_unless($userProfile, 404, "Does not exist id=$userProfile" , ["Content-Type" => "application/json"]);
		return response()->json([
						    "success" => true,
						    "data" => [
						    	"profile" => $userProfile
						      ]
						]);
	}

	public function getProfilesByUser($userId){
		$userProfiles = UserProfile::where('user_id', $userId)->get();
		abort_unless(filled($userProfiles), 404, "Does not exist Profiles whith id=$id" , ["Content-Type" => "application/json"]);
		return response()->json([
						    "success" => true,
						    "data" => [
						    	"profiles" => $userProfiles
						      ]
						]);
	}
	
	public function getAllProfiles(){
			$page = self::getItemRequest('page');
			$userProfiles = UserProfile::paginate(self::itemOnPage)->items();
			return response()->json([
							    "success" => true,
							    "data" => [
							    	"profiles" => $userProfiles
							      ]
							]);
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
		return response()->json([
						    "success" => true,
						    "data" => [
						    	"profile" => $userProfile
						      ]
						]);
	}

	public function getProfilesByUserDB($userId){
		$userProfiles = DB::table('user_profiles')->where('user_id', $userId)->get();
		abort_unless(filled($userProfiles), 404, "Does not exist Profiles whith userId=$userId" , ["Content-Type" => "application/json"]);
		return response()->json([
						    "success" => true,
						    "data" => [
						    	"profiles" => $userProfiles
						      ]
						]);
	}

	public function getAllProfilesDB(){
		 $page = self::getItemRequest('page');
	 	 $userProfiles = DB::table('user_profiles')->paginate(self::itemOnPage)->items();
	 	 abort_unless(filled($userProfiles) and $page, 404, "Does not exist Page" , ["Content-Type" => "application/json"]);
	 	 return response()->json([
							    "success" => true,
							    "data" => [
							    	"profiles" => $userProfiles
							      ]
							]);
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
}
