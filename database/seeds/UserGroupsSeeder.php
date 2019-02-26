<?php

use Illuminate\Database\Seeder;

class UserGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 25; $i++) {

        DB::table('user_groups')->insert([
            'user_id' => rand(1,15),
            'group_id' => rand(1,15)
            ]);
    }
    }
}
