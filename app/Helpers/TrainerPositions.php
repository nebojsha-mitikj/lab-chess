<?php

namespace App\Helpers;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TrainerPositions {

    /**
     * Read trainer positions.
     * @return Collection
     */
    public static function read(): Collection {
        return DB::table('trainer_position as tp')
            ->select('tp.id as pi', 'tp.number as pn', 'tv.number as vn', 't.id as ti')
            ->join('trainer_variant as tv', 'tp.variant_id', '=', 'tv.id')
            ->join('trainer as t', 'tp.trainer_id', '=', 't.id')
            ->orderBy('t.id')
            ->orderBy('tv.number')
            ->orderBy('tp.number')
            ->get();
    }

    /**
     * @return Collection
     */
    public static function readTrainers(): Collection {
        return DB::table('trainer')->select('id', 'code', 'name', 'pieces')->get();
    }

    /**
     * Get trainer by code.
     * @param string $code
     * @return mixed|null
     */
    public static function getTrainerByCodeOrFail(string $code){
        $trainers = self::readTrainers();
        foreach($trainers as $trainer)
            if($trainer->code === $code) return $trainer;
        abort(404);
        return null;
    }
}
