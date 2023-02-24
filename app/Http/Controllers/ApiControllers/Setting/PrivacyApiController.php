<?php

namespace App\Http\Controllers\ApiControllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdatePrivacyRequest;
use App\Models\User\UserConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PrivacyApiController extends Controller
{
    /**
     * Update privacy setting.
     * @param UpdatePrivacyRequest $request
     * @return JsonResponse
     */
    public function update(UpdatePrivacyRequest $request) : JsonResponse {
        UserConfiguration::where('user_id','=',Auth::id())->update(['public_profile' => $request->public_profile]);
        return response()->json(['message' => 'Privacy settings updated successfully.']);
    }
}
