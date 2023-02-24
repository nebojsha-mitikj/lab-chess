<?php

namespace App\Http\Controllers\ApiControllers\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\CompleteTrainerPositionRequest;
use App\Models\Experience\Experience;
use App\Models\Trainer\TrainerPosition;
use App\Models\User\User;
use App\Models\User\UserGoal;
use App\Models\User\UserTrainerPosition;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TrainerPositionApiController extends Controller
{
    /**
     * Complete position.
     * @param CompleteTrainerPositionRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function complete(CompleteTrainerPositionRequest $request, string $uuid): JsonResponse {
        $user = User::with(['userConfiguration.dailyGoal'])->where('id',Auth::id())->firstOrFail();
        $trainerPosition = TrainerPosition::where('uuid', $uuid)->firstOrFail();
        $experienceToAdd = $request->takeBack ? 10 : 15;
        Experience::add($experienceToAdd);
        User::addExperience($experienceToAdd);
        UserTrainerPosition::completePosition($trainerPosition, $request->takeBack);
        $userGoal = UserGoal::where('user_id', Auth::id())->first();
        $goalStatus = UserGoal::status();
        if($goalStatus === 'just-reached') $userGoal->goal_reach_count = UserGoal::markAsReached();

        return response()->json([
            'message' => 'Position completed successfully.',
            'goal_status' => $goalStatus,
            'added_xp' => $experienceToAdd,
            'total_xp' => User::totalXpForToday(),
            'needed_xp' => (int)$user->userConfiguration->dailyGoal->experience,
            'goal_count' => $userGoal->goal_reach_count
        ]);
    }

}
