<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{

    private $data = [
        ['id' => 1, 'name' => 'Chess Training I', 'image_url' => '1'],
        ['id' => 2, 'name' => 'Chess Training II', 'image_url' => '1'],
        ['id' => 3, 'name' => 'Chess Training III', 'image_url' => '1'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeederHelper::addTime($this->data);
        DB::table('course')->insert($this->data);
    }
}
