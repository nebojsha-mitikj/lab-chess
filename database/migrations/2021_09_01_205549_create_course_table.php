<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_level_id')->unsigned();
            $table->foreign('course_level_id')->references('id')->on('course_level');
            $table->bigInteger('course_type_id')->unsigned();
            $table->foreign('course_type_id')->references('id')->on('course_type');
            $table->string('name')->unique();
            $table->string('description');
            $table->string('image_url');
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
        Schema::dropIfExists('course');
    }
}
