<?php

namespace App\Http\Controllers\ApiControllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateNotificationRequest;
use App\Models\User\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    /**
     * Update notification settings.
     * @param UpdateNotificationRequest $request
     * @return JsonResponse
     */
    public function update(UpdateNotificationRequest $request) : JsonResponse {
        if(!in_array($request->field, ['product_update', 'new_follow', 'weekly_report'])){
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['field' => ['Invalid field value.']]
            ], 422);
        }
        UserNotification::where('user_id','=',Auth::id())->update([$request->field => $request->value]);
        return response()->json(['message' => 'Notification settings updated successfully.']);
    }
}
