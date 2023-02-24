<?php

namespace App\Models\User;

use App\Models\Title\Medal;
use App\Models\Trainer\TrainerPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class UserTrainerPosition extends Model
{
    use HasFactory;

    protected $table = 'user_trainer_position';

    protected $fillable = [
        'user_id',
        'trainer_id',
        'position_id',
        'medal_id',
        'solved_counter'
    ];

    /**
     * Get medal type.
     * @return BelongsTo
     */
    public function medal(): BelongsTo
    {
        return $this->belongsTo(Medal::class, 'medal_id');
    }

    /**
     * Complete position.
     * @param TrainerPosition $position
     * @param boolean $takeBack
     */
    public static function completePosition(TrainerPosition $position, bool $takeBack)
    {
        $userTrainerPosition = self::where('user_id', Auth::id())->where('position_id', $position->id)->first();
        if (!$userTrainerPosition) {
            self::create([
                'user_id' => Auth::id(),
                'trainer_id' => $position->trainer_id,
                'position_id' => $position->id,
                'solved_counter' => 1,
                'medal_id' => $takeBack ? Medal::$bronze : Medal::$gold
            ]);
        }else{
            $userTrainerPosition->medal_id = $takeBack ? Medal::$silver : Medal::$gold;
            $userTrainerPosition->solved_counter = $userTrainerPosition->solved_counter + 1;
            $userTrainerPosition->save();
        }
    }
}
