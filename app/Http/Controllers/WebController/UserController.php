<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\v0\UserGroupController;
use App\Http\Controllers\v0\UserProfilesController;

use App\Models\User\UserGroup;
use App\Models\User\UserProfile;

class UserController extends Controller
{
    public function getProfiles(Request $request){
    	$profiles = UserProfile::get();
    	return view('layouts.profilesUser', ['profiles'=> $profiles]);
    } 

    public function deleteProfile(Request $request,UserProfile $profileId){
            UserProfilesController::deleteProfile($profileId);
            return redirect('/profiles');
    }    

    public function patchProfile(Request $request,UserProfile $profileId){
            UserProfilesController::patchProfileName($request, $profileId);
            return redirect('/profiles');
    }

    public function createProfile(Request $request){
            UserProfilesController::postProfile($request);
            return redirect('/profiles');
    }


    public function getGroups(Request $request){
    	$groups = UserGroup::get();
    	return view('layouts.groupsUser', ['groups' => $groups]);
    }

    public function deleteGroup(Request $request, $groupId){
        UserGroupController::deleteGroup($groupId);
        return redirect('/groups');
    }
    
    public function createGroup(Request $request){
    	UserGroupController::postGroup($request);
    	return redirect('/groups');
    }
}
