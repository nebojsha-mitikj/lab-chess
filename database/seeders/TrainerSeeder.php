<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerSeeder extends Seeder
{

    private $data = [
        ['id' => 1, 'code' => 'elementary-mates', 'name' => 'Elementary Mates', 'pieces' => 'K'],
        ['id' => 2, 'code' => 'pawn-endgames', 'name' => 'Pawn Endgames', 'pieces' => 'P'],
        ['id' => 3, 'code' => 'bishop-endgames', 'name' => 'Bishop Endgames', 'pieces' => 'B'],
        ['id' => 4, 'code' => 'knight-endgames', 'name' => 'Knight Endgames', 'pieces' => 'N'],
        ['id' => 5, 'code' => 'pieces-endgames', 'name' => 'Pieces Endgames', 'pieces' => 'NB'],
        ['id' => 6, 'code' => 'rook-and-pawn-endgames', 'name' => 'Rook & Pawn Endgames', 'pieces' => 'RP'],
        ['id' => 7, 'code' => 'rook-and-pieces-endgames', 'name' => 'Rook & Pieces Endgames', 'pieces' => 'RNB'],
        ['id' => 8, 'code' => 'queen-endgames', 'name' => 'Queen Endgames', 'pieces' => 'Q']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeederHelper::addTime($this->data);
        DB::table('trainer')->insert($this->data);
    }
}
