<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGithubReposioriesTable extends Migration
{

    public function up()
    {
        Schema::create('github_reposiories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('github_id');
            $table->string('description');
            $table->string('language');
            $table->string('name');
            $table->boolean('private');

            $table->unsignedInteger('github_user_id');
            $table->foreign('github_user_id')->references('id')->on('github_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('github_reposiories');
    }
}
