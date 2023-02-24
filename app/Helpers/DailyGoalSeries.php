<?php

namespace App\Helpers;

use App\Models\Experience\Experience;
use App\Models\User\User;
use App\Models\User\UserGoal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DailyGoalSeries {

    /**
     * Get user goal series data.
     * @return object
     */
    public static function read(): object {
        $userData = User::with(['userGoal', 'userConfiguration.dailyGoal'])->where('id',Auth::id())->first();
        $currentDateForUser = Carbon::now(Auth::user()->timezone)->format('Y-m-d');
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $currentDateForUser.' 00:00:00', Auth::user()->timezone)->setTimezone('UTC');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $currentDateForUser.' 23:59:59', Auth::user()->timezone)->setTimezone('UTC');
        $experience = Experience::where('user_id',Auth::id())->whereBetween('created_at', [$startDate, $endDate])->get();
        $totalXPToday = 0;
        foreach($experience as $row) $totalXPToday += $row->experience;
        $requiredXP = $userData->userConfiguration->dailyGoal->experience;
        $userData->userGoal->percentage = $totalXPToday >= $requiredXP ? 100 : floor($totalXPToday * 100 / $requiredXP);
        $userData->userGoal->user_xp = $totalXPToday;
        $goalIsReached = UserGoal::status() !== 'not-reached';
        $userData->userGoal->goal_reached = $goalIsReached;
        return $userData;
    }

}
