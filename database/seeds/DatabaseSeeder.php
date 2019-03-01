<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 15; $i++) {
	            DB::table('users')->insert([
	            'name' => str_random(10),
	            'email' => str_random(10).'@mail.ru',
	            'password' => bcrypt('secret'),
                'api_token' => str_random(30)
	        ]);
    	}
    }
}
