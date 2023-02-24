<?php

namespace App\Http\Controllers\ApiControllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateDailyGoalRequest;
use App\Models\Goal\DailyGoal;
use App\Models\User\UserConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DailyGoalApiController extends Controller
{
    /**
     * Update daily goal setting.
     * @param UpdateDailyGoalRequest $request
     * @return JsonResponse
     */
    public function update(UpdateDailyGoalRequest $request): JsonResponse {
        if(!DailyGoal::where('id','=',$request->daily_goal_id)->exists()){
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['daily_goal_id' => ['The provided daily goal id does not exist.']]
            ], 422);
        }
        UserConfiguration::where('user_id','=',Auth::id())->update(['daily_goal_id' => $request->daily_goal_id]);
        return response()->json(['message' => 'Daily goal updated successfully.']);
    }
}
