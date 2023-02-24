<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedalSeeder extends Seeder
{

    private $data = [
        ['id' => 1, 'type' => 'Gold'],
        ['id' => 2, 'type' => 'Silver'],
        ['id' => 3, 'type' => 'Bronze']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeederHelper::addTime($this->data);
        DB::table('medal')->insert($this->data);
    }
}
