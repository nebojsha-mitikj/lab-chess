<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_configuration', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->unique();
            $table->foreign('user_id')->references('id')->on('user');
            $table->bigInteger('board_theme_id')->unsigned();
            $table->foreign('board_theme_id')->references('id')->on('board_theme');
            $table->bigInteger('piece_theme_id')->unsigned();
            $table->foreign('piece_theme_id')->references('id')->on('piece_theme');
            $table->bigInteger('daily_goal_id')->unsigned();
            $table->foreign('daily_goal_id')->references('id')->on('daily_goal');
            $table->boolean('sound_effects')->default(true);
            $table->boolean('animation')->default(true);
            $table->boolean('public_profile')->default(true);
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
        Schema::dropIfExists('user_configuration');
    }
}
