<?php

namespace App\Models\Title;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Medal extends Model
{
    use HasFactory;

    protected $table = 'medal';

    protected $fillable = ['type'];

    public static $bronze = 3;
    public static $silver = 2;
    public static $gold = 1;

    /**
     * Count Medals For User By Type.
     * @param int $userID
     * @param null $trainerID
     * @return array
     */
    public static function countMedals(int $userID, $trainerID = null): array{
        return DB::select("
            SELECT trainer.id trainer_id, type medal_type, COUNT(type) medal_count FROM user_trainer_position
            INNER JOIN medal
            ON user_trainer_position.medal_id = medal.id
            INNER JOIN trainer_position
            ON user_trainer_position.position_id = trainer_position.id
            INNER JOIN trainer ON trainer.id = trainer_position.trainer_id
            WHERE user_id = $userID
            ".($trainerID !== null ? "AND trainer.id = $trainerID" : "")."
            GROUP BY trainer.id,medal_id;
        ");
    }

    /**
     * Count medal points by medals.
     * @param array $medals
     * @param int $trainerID
     * @return int
     */
    public static function medalPoints(array $medals, int $trainerID) : int {
        $finished = 0;
        for($i = 0; $i < count($medals); $i++){
            if($trainerID === $medals[$i]->trainer_id){
                $times = 1;
                if($medals[$i]->medal_type === 'gold') $times = 3;
                else if($medals[$i]->medal_type === 'silver') $times = 2;
                $finished += ($medals[$i]->medal_count * $times);
            }
        }
        return $finished;
    }

}
