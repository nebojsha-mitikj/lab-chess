<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PieceThemeSeeder extends Seeder
{

    private $data = [
        ['name' => 'classic']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeederHelper::addTime($this->data);
        DB::table('piece_theme')->insert($this->data);
    }
}
