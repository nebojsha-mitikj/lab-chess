<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTrainerPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_trainer_position', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('user');

            $table->bigInteger('trainer_id')->unsigned()->default(1);
            $table->foreign('trainer_id')->references('id')->on('trainer');

            $table->bigInteger('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('trainer_position');

            $table->bigInteger('medal_id')->unsigned();
            $table->foreign('medal_id')->references('id')->on('medal');

            $table->integer('solved_counter')->unsigned();
            $table->unique(['user_id', 'position_id']);
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
        Schema::dropIfExists('user_trainer_position');
    }
}
