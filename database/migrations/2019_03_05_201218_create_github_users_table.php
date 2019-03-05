<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGithubUsersTable extends Migration
{
    public function up()
    {
        Schema::create('github_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
        });
    }

    public function down()
    {
        Schema::dropIfExists('github_users');
    }
}
