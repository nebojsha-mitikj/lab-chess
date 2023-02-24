<?php

namespace App\Models\Trainer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Trainer extends Model
{
    use HasFactory;

    protected $table = 'trainer';

    protected $fillable = [
        'code',
        'name',
        'pieces'
    ];

    /**
     * Get trainer variants.
     * @return HasMany
     */
    public function variants(): HasMany {
        return $this->hasMany(TrainerVariant::class, 'trainer_id');
    }

    /**
     * Get trainer positions.
     * @return HasMany
     */
    public function trainerPositions(): HasMany {
        return $this->hasMany(TrainerPosition::class, 'trainer_id');
    }

    /**
     * Reads trainers with total_positions and finished_positions for user.
     * @param int $userID
     * @return array
     */
    private static function getTrainersWithTotalAndFinishedPositionsForUser(int $userID) : array {
        return DB::select("
        SELECT t.id, t.code, t.pieces, t.name, COUNT(tp.id) as total_positions, IFNULL(finished_positions,0) as finished_positions
        FROM labchess.trainer as t
        INNER JOIN labchess.trainer_position as tp
        ON t.id = tp.trainer_id
        LEFT JOIN (
	        SELECT trainer_id, COUNT(position_id) as finished_positions
	        FROM labchess.user_trainer_position as utp
	        WHERE user_id = $userID
	        GROUP BY trainer_id
        ) AS utp
        ON t.id = utp.trainer_id
        GROUP BY t.id;
        ");
    }

    /**
     * Find trainer in trainers.
     * @param string $code
     * @param Collection $trainers
     * @return mixed|null
     */
    public static function findTrainerInTrainersOrAbort(string $code, Collection $trainers){
        $trainer = null;
        foreach($trainers as $trainerRow){
            if($trainerRow->code === $code){
                $trainer = $trainerRow;
                break;
            }
        }
        if(!$trainer) abort(404);
        return $trainer;
    }

    /**
     * Get trainers with user progress.
     * @param int $userID
     * @param bool $onlyStartedTrainers
     * @return array
     */
    public static function trainerProgress(int $userID, bool $onlyStartedTrainers = false) {
        $trainers = self::getTrainersWithTotalAndFinishedPositionsForUser($userID);
        if($onlyStartedTrainers){
            return array_filter($trainers, function($trainer){
                return $trainer->finished_positions > 0;
            });
        }
        return $trainers;
    }
}
