<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserLevelSeeder extends Seeder
{

    private $data = [
        ['name' => 'Beginner','level' => 1, 'required_xp' => 0],
        ['name' => 'Intermediate','level' => 2, 'required_xp' => 150],
        ['name' => 'Advanced','level' => 3, 'required_xp' => 300],
        ['name' => 'Master','level' => 4, 'required_xp' => 600],
        ['name' => 'Grandmaster','level' => 5, 'required_xp' => 1200],
        ['name' => 'G.O.A.T','level' => 6, 'required_xp' => 2500],
        ['name' => 'Chess Wizzard','level' => 7, 'required_xp' => 5000],
        ['name' => 'God of Chess','level' => 8, 'required_xp' => 10000]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeederHelper::addTime($this->data);
        DB::table('user_level')->insert($this->data);
    }
}
