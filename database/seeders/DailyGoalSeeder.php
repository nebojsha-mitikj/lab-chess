<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyGoalSeeder extends Seeder
{

    private $data = [
        ['experience' => 10,'level' => 'Very Easy'],
        ['experience' => 20,'level' => 'Easy'],
        ['experience' => 30,'level' => 'Normal'],
        ['experience' => 40,'level' => 'Hard'],
        ['experience' => 50,'level' => 'Vary Hard']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeederHelper::addTime($this->data);
        DB::table('daily_goal')->insert($this->data);
    }
}
