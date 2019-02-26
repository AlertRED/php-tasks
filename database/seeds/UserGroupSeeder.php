<?php

use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 15; $i++) {

        DB::table('user_group')->insert([
            'name' => 'Group '.$i,
            ]);
    	}
    }
}
