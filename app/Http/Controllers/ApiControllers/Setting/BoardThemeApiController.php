<?php

namespace App\Http\Controllers\ApiControllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateBoardThemeRequest;
use App\Models\Borad\BoardTheme;
use App\Models\User\UserConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BoardThemeApiController extends Controller
{
    /**
     * Update board theme setting.
     * @param UpdateBoardThemeRequest $request
     * @return JsonResponse
     */
    public function update(UpdateBoardThemeRequest $request): JsonResponse {
        if(!BoardTheme::where('id','=',$request->board_theme_id)->exists()){
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['board_theme_id' => ['The provided board theme id does not exist.']]
            ], 422);
        }
        UserConfiguration::where('user_id','=',Auth::id())->update(['board_theme_id' => $request->board_theme_id]);
        return response()->json(['message' => 'Board theme updated successfully.']);
    }
}
