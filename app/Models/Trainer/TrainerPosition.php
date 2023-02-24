<?php

namespace App\Models\Trainer;

use App\Helpers\TrainerPositions;
use App\Models\User\UserTrainerPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class TrainerPosition extends Model
{
    use HasFactory;

    protected $table = 'trainer_position';

    protected $fillable = [
        'uuid',
        'variant_id',
        'trainer_id',
        'number',
        'position',
        'target'
    ];

    /**
     * Get the trainer variant.
     * @return BelongsTo
     */
    public function trainerVariant(): BelongsTo {
        return $this->belongsTo(TrainerVariant::class, 'variant_id');
    }

    /**
     * Get the trainer.
     * @return BelongsTo
     */
    public function trainer(): BelongsTo {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }

    /**
     * Get the user trainer position.
     * @return HasMany
     */
    public function userTrainerPositions(): HasMany {
        return $this->hasMany(UserTrainerPosition::class, 'position_id')
            ->where('user_id', Auth::id());
    }

    /**
     * Find next unfinished position for user.
     * @param int $positionId
     * @return array
     */
    public static function nextUnfinishedPosition(int $positionId) : array {
        $positions = TrainerPositions::read();
        $userPositionIds = array_flip(UserTrainerPosition::where('user_id', Auth::id())->pluck('position_id')->toArray());
        $positionsCount = count($positions);
        $positionMatchFound = false;
        $nextPosition = null;
        $firstUnfinishedPosition = null;
        for($i = 0; $i < $positionsCount; $i++){
            $position = $positions[$i];
            if($firstUnfinishedPosition === null && !array_key_exists($position->pi, $userPositionIds)){
                $firstUnfinishedPosition = $position;
            }
            if($positionMatchFound && !array_key_exists($position->pi, $userPositionIds)){
                $nextPosition = $position;
                break;
            }
            if($position->pi === $positionId) $positionMatchFound = true;
        }
        if($nextPosition === null) $nextPosition = $firstUnfinishedPosition;
        $trainers = TrainerPositions::readTrainers();
        if($nextPosition === null) return [
            'position' => 1,
            'variant' => 1,
            'code' => $trainers[0]->code
        ];
        $trainerCode = null;
        foreach($trainers as $trainerRow){
            if($trainerRow->id === $nextPosition->ti){
                $trainerCode = $trainerRow->code;
                break;
            }
        }
        if(!$trainerCode) $trainerCode = $trainers[0]->code;
        return [
            'position' => $nextPosition->pn,
            'variant' => $nextPosition->vn,
            'code' => $trainerCode
        ];
    }
}
