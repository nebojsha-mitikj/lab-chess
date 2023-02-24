<?php

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class UserGoal extends Model
{
    use HasFactory;

    protected $table = 'user_goal';

    protected $fillable = [
        'user_id',
        'last_goal_reach_date',
        'goal_reach_count'
    ];

    /**
     * Get the user.
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create default user goal.
     * @param int $userID
     */
    public static function createDefault(int $userID){
        self::create([
            'user_id' => $userID
        ]);
    }

    /**
     * Get the user daily goal status.
     * @return string [ already-marked-as-reached / just-reached / not-reached ]
     */
    public static function status(){
        $user = User::with(['userGoal','userConfiguration.dailyGoal'])->where('id',Auth::id())->firstOrFail();
        $currentDateForUser = Carbon::now($user->timezone)->format('Y-m-d');

        if(!empty($user->userGoal->last_goal_reach_date)){
            $lastGoalReachDate = Carbon::createFromFormat('Y-m-d H:i:s', $user->userGoal->last_goal_reach_date, 'UTC')
                ->setTimezone($user->timezone)
                ->format('Y-m-d');
            if($currentDateForUser === $lastGoalReachDate) return 'already-marked-as-reached';
        }

        $totalXpForUserForToday = User::totalXpForToday();
        $neededXpToFinishDailyGoal = (int)$user->userConfiguration->dailyGoal->experience;

        return $totalXpForUserForToday >= $neededXpToFinishDailyGoal ? 'just-reached' : 'not-reached';
    }

    /**
     * Check if the last goal reach date is 2 days different from current date for user.
     * @return bool
     */
    public static function shouldBeReset() : bool{
        $userGoal = self::where('user_id', Auth::id())->first();
        if($userGoal->goal_reach_count === 0) return false;
        $timezone = Auth::user()->timezone;
        if(!empty($userGoal->last_goal_reach_date)){
            $lastReachedDateString = Carbon::createFromFormat('Y-m-d H:i:s', $userGoal->last_goal_reach_date, 'UTC')
                ->setTimezone($timezone)->format('Y-m-d');
            $currentDateCarbon = Carbon::createFromFormat('Y-m-d', Carbon::now($timezone)->format('Y-m-d'));
            $lastReachedDateCarbon = Carbon::createFromFormat('Y-m-d', $lastReachedDateString);
            return $currentDateCarbon->diffInDays($lastReachedDateCarbon) >= 2;
        }
        return false;
    }

    /**
     * Mark daily user goal as reached.
     */
    public static function markAsReached(){
        $currentDate = Carbon::now();
        $userGoal = UserGoal::where('user_id', Auth::id())->first();
        $userGoal->last_goal_reach_date = $currentDate;
        $userGoal->goal_reach_count = $userGoal->goal_reach_count + 1;
        $userGoal->save();
        return $userGoal->goal_reach_count;
    }

    /**
     * Reset daily user goal.
     */
    public static function reset(){
        $userGoal = self::where('user_id', Auth::id())->first();
        $userGoal->goal_reach_count = 0;
        $userGoal->save();
    }
}
