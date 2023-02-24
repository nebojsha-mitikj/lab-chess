<?php

namespace App\Http\Controllers\ApiControllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordApiController extends Controller
{
    /**
     * Change password
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function update(UpdatePasswordRequest $request) : JsonResponse {
        $user = Auth::user();
        if(Hash::check($request->current_password, $user->password)){
            $user->password = Hash::make($request->new_password);
            $user->save();
            return response()->json(['message' => 'Password updated successfully.']);
        }else{
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['current_password' => ['Incorrect current password.']]
            ], 422);
        }
    }
}
