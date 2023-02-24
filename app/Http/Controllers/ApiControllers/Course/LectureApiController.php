<?php

namespace App\Http\Controllers\ApiControllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Experience\Experience;
use App\Models\User\User;
use App\Models\User\UserGoal;
use App\Models\User\UserLecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LectureApiController extends Controller {

    /**
     * When user completes lecture.
     * @param int $lectureId
     * @return JsonResponse
     */
    public function complete(int $lectureId) : JsonResponse {
        UserLecture::completeLecture($lectureId);
        $user = User::with(['userConfiguration.dailyGoal'])->where('id',Auth::id())->firstOrFail();
        Experience::add(10);
        User::addExperience(10);
        $userGoal = UserGoal::where('user_id', Auth::id())->first();
        $goalStatus = UserGoal::status();
        if($goalStatus === 'just-reached') $userGoal->goal_reach_count = UserGoal::markAsReached();

        return response()->json([
            'message' => 'Lecture completed successfully.',
            'goal_status' => $goalStatus,
            'added_xp' => 10,
            'total_xp' => User::totalXpForToday(),
            'needed_xp' => (int)$user->userConfiguration->dailyGoal->experience,
            'goal_count' => $userGoal->goal_reach_count
        ]);
    }

}
