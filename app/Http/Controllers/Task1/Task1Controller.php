<?php

namespace App\Http\Controllers\Task1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class Task1Controller extends Controller
{

    public function helloWorld(){
    	return "Hello world";
    }

    public function uuid(Request $request){
    	try {
    		$uuid1 = Uuid::uuid1();
    		
    		return response()->json([
			    "success" => true,
			    "data" => [
			    	"uuid" => $uuid1
			      ]
			]);
    	} catch (Exception $e) {
    		return $e;
    	}
    	
    }

    public function data_from_config(Request $request){
    	try {
			return response()->json([
						    "success" => true,
						    "data" => [
						    	"config" => [
						    			"test_config_value" => env('TEST_CONFIG_VALUE')
						    		]
						      ]
						]);
	    } catch (Exception $e) {
	    	return $e;
	    }
	}

}
