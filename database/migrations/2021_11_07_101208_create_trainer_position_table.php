<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainerPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainer_position', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();

            $table->bigInteger('trainer_id')->unsigned();
            $table->foreign('trainer_id')->references('id')->on('trainer');

            $table->bigInteger('variant_id')->unsigned();
            $table->foreign('variant_id')->references('id')->on('trainer_variant');

            $table->smallInteger('number')->unsigned();
            $table->text('position');
            $table->char('target')->default('w');

            $table->unique(['trainer_id', 'variant_id', 'number']);
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
        Schema::dropIfExists('trainer_position');
    }
}
