<?php

namespace App\Http\Controllers\v0;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\UserProfile;

class UserProfilesController extends Controller
{
	public function getProfile($id){
		$userProfile = UserProfile::where('id', $id )->first();
		abort_unless($userProfile, 404, "Does not exist id=$id" , ["Content-Type" => "application/json"]);
		return response()->json([
						    "success" => true,
						    "data" => [
						    	"profile" => $userProfile
						      ]
						]);
	}

	public function getProfilesByUser($id){
		$userProfiles = UserProfile::where('user_id', $id )->get();
		abort_unless($userProfiles, 404, "Does not exist Profiles whith id=$id" , ["Content-Type" => "application/json"]);
		return response()->json([
						    "success" => true,
						    "data" => [
						    	"profile" => $userProfiles
						      ]
						]);
	}
	

	public function getAllProfiles(){
			$userProfiles = UserProfile::paginate(5);
			return response()->json([
							    "success" => true,
							    "data" => [
							    	"profile" => $userProfiles
							      ]
							]);
		}

	public function patchProfileName($id){
		$result = array_key_exists('name', $_REQUEST);
		abort_unless($result, 418, "Does not exist name parameter" , ["Content-Type" => "application/json"]);

		$name = $_REQUEST['name'];	
		$userProfiles = UserProfile::where('id', $id)->update(['name' => $name]);
		return $this->getProfile($id);
	}

	public function deleteProfile($id){
		$result = UserProfile::where('id', $id)->delete();
		return response()->json(["success"=> (bool)$result]);
	}



	 public function getProfileDB($id){
	 	$userProfile = DB::table('user_profiles')->get()->where('id', $id)->first();
		if (!$userProfile)
			return response()->json(['code' => 404, 'message' => "Профиль с id=$id не найден"]);
		return response()->json([
						    "success" => true,
						    "data" => [
						    	"profile" => $userProfile
						      ]
						]);
	}

	public function getProfilesByUserDB($id){
		$userProfiles = DB::table('user_profiles')->get()->where('user_id', $id);
		abort_unless($userProfiles, 404, "Does not exist Profiles whith id=$id" , ["Content-Type" => "application/json"]);

		return response()->json([
						    "success" => true,
						    "data" => [
						    	"profile" => $userProfiles
						      ]
						]);
	}

	public function getAllProfilesDB(){
	 	 $userProfiles = DB::table('user_profiles')->paginate(5);
	 	 return response()->json([
							    "success" => true,
							    "data" => [
							    	"profile" => $userProfiles
							      ]
							]);
	}

	public function patchProfileNameDB($id){
		$result = array_key_exists('name', $_REQUEST);
		abort_unless($result, 418, "Does not exist name parameter" , ["Content-Type" => "application/json"]);

		$name = $_REQUEST['name'];	
		DB::table('user_profiles')->where('id', $id)->update(['name' => $name]);		 	 
		return $this->getProfileDB($id);
	}

	public function deleteProfileDB($id){
	 	$result = DB::table('user_profiles')->where('id', $id)->delete();
		return response()->json(["success"=> (bool)$result]);
	}
}
