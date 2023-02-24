<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoardThemeSeeder extends Seeder
{
    private $data = [
        [
            'light_square' => '#DBE3E8',
            'dark_square' => '#95A6B5',
            'image_url' => 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/chess-boards/de.png'
        ],
        [
            'light_square' => '#FBFAF2',
            'dark_square' => '#C2B8AD',
            'image_url' => 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/chess-boards/be.png'
        ],
        [
            'light_square' => '#FFF8FA',
            'dark_square' => '#A5A2F2',
            'image_url' => 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/chess-boards/pu.png'
        ],
        [
            'light_square' => '#FBEBEB',
            'dark_square' => '#D98B79',
            'image_url' => 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/chess-boards/to.png'
        ],
        [
            'light_square' => '#EEF7FF',
            'dark_square' => '#51718C',
            'image_url' => 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/chess-boards/fr.png'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeederHelper::addTime($this->data);
        DB::table('board_theme')->insert($this->data);
    }
}
