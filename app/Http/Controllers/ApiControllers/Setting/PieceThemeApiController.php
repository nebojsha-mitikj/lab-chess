<?php

namespace App\Http\Controllers\ApiControllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdatePieceThemeRequest;
use App\Models\Piece\PieceTheme;
use App\Models\User\UserConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PieceThemeApiController extends Controller
{
    /**
     * Update board theme setting.
     * @param UpdatePieceThemeRequest $request
     * @return JsonResponse
     */
    public function update(UpdatePieceThemeRequest $request): JsonResponse {
        if(!PieceTheme::where('id','=',$request->piece_theme_id)->exists()){
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['board_theme_id' => ['The provided piece theme id does not exist.']]
            ], 422);
        }
        UserConfiguration::where('user_id','=',Auth::id())->update(['piece_theme_id' => $request->piece_theme_id]);
        return response()->json(['message' => 'Piece theme updated successfully.']);
    }
}
