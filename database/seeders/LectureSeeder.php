<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LectureSeeder extends Seeder
{

    private $data = [
        ['id' => 1, 'course_id' => 1, 'type' => 'lesson', 'free' => true, 'number' => '1.1', 'description' => 'Description 1'],
        ['id' => 2, 'course_id' => 1, 'type' => 'exercise', 'free' => true, 'number' => '1.2', 'description' => 'Description 2'],
        ['id' => 3, 'course_id' => 1, 'type' => 'lesson', 'free' => true, 'number' => '1.1', 'description' => 'Description 3'],

        ['id' => 4, 'course_id' => 2, 'type' => 'lesson', 'free' => true, 'number' => '1.1', 'description' => 'Description 4'],
        ['id' => 5, 'course_id' => 2, 'type' => 'exercise', 'free' => true, 'number' => '1.2', 'description' => 'Description 5'],
        ['id' => 6, 'course_id' => 2, 'type' => 'lesson', 'free' => true, 'number' => '2.1', 'description' => 'Description 6'],

        ['id' => 7, 'course_id' => 3, 'type' => 'lesson', 'free' => true, 'number' => '1.1', 'description' => 'Description 7'],
        ['id' => 8, 'course_id' => 3, 'type' => 'exercise', 'free' => true, 'number' => '1.2', 'description' => 'Description 8'],
        ['id' => 9, 'course_id' => 3, 'type' => 'lesson', 'free' => true, 'number' => '2.1', 'description' => 'Description 9'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeederHelper::addTime($this->data);
        DB::table('lecture')->insert($this->data);
    }
}
