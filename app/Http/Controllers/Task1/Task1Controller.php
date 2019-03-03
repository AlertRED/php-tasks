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
    		$uuid1 = Uuid::uuid1();
    		
    		return response()->json([
			    "success" => true,
			    "data" => [
			    	"uuid" => $uuid1
			      ]
			]);
    }

    public function data_from_config(Request $request){
			return response()->json([
						    "success" => true,
						    "data" => [
						    	"config" => [
						    			"test_config_value" => env('TEST_CONFIG_VALUE')
						    		]
						      ]
						]);
	}

}
