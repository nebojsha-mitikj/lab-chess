<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCourseLectureTables extends Migration
{
    public function up(){
        Schema::table('lecture', function (Blueprint $table) {
            $table->dropForeign(['lecture_type_id']);
            $table->dropColumn('lecture_type_id');
            $table->string('type')->after('course_id');
            $table->string('description')->nullable()->change();
        });
        Schema::table('course', function (Blueprint $table) {
            $table->dropForeign(['course_level_id']);
            $table->dropColumn('course_level_id');
            $table->dropForeign(['course_type_id']);
            $table->dropColumn('course_type_id');
            $table->dropColumn('description');
        });
        Schema::dropIfExists('lecture_type');
        Schema::dropIfExists('user_course');
        Schema::dropIfExists('course_level');
        Schema::dropIfExists('course_type');
    }
}
