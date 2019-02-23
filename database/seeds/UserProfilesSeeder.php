<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	for ($i = 1; $i <= 15; $i++) {

        DB::table('user_profiles')->insert([
            'name' => 'Profile '.$i,
            'user_id' => $i
            ]);
    }
    }
}
