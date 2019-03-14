<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use App\Jobs\SendEmail;

class BaseFunctions
{	

	public static function generateJSON(bool $success = true, string $key = null, $value = null){
		if (!$key)
			return response()->json([
							    "success" => $success,
							]);

		return response()->json([
							    "success" => $success,
							    "data" => [
							    	$key => $value
							      ]
							]);
	}

	public static function sendMailToAdmin(Mailable $Mail){
		$users = User::where('role','Admin')->get();
		foreach ($users as $user) {
			\Queue::pushOn('emails', new SendEmail($user, $Mail));
		}
	}

}