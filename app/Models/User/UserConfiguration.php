<?php

namespace App\Models\User;

use App\Models\Borad\BoardTheme;
use App\Models\Goal\DailyGoal;
use App\Models\Piece\PieceTheme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class UserConfiguration extends Model
{
    use HasFactory;

    protected $table = 'user_configuration';

    protected $fillable = [
        'user_id',
        'board_theme_id',
        'piece_theme_id',
        'daily_goal_id',
        'sound_effects',
        'animation',
        'public_profile'
    ];

    /**
     * Get the user.
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the board theme.
     */
    public function boardTheme() : BelongsTo
    {
        return $this->belongsTo(BoardTheme::class);
    }

    /**
     * Get the piece theme.
     */
    public function pieceTheme() : BelongsTo
    {
        return $this->belongsTo(PieceTheme::class);
    }

    /**
     * Get the daily goal.
     */
    public function dailyGoal() : BelongsTo
    {
        return $this->belongsTo(DailyGoal::class);
    }

    /**
     * Read user configuration for current user.
     * @return mixed
     */
    public static function read(){
        return self::where('user_id','=',Auth::id())->first();
    }

    /**
     * Create default user configurations.
     * @param int $userID
     */
    public static function createDefault(int $userID){
        $normalGoalLevelId = DailyGoal::where('level', '=', 'Normal')->pluck('id')[0];
        self::create([
            'user_id' => $userID,
            'board_theme_id' => BoardTheme::min('id'),
            'piece_theme_id' => PieceTheme::min('id'),
            'daily_goal_id' => $normalGoalLevelId,
        ]);
    }

}
