<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGithubIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('github_issues', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('github_id');
            $table->unsignedInteger('number');
            $table->string('title');
            $table->string('state');

            $table->unsignedInteger('repository_id ');
            $table->foreign('repository_id ')->references('id')->on('github_reposiories');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('github_issues');
    }
}
