<?php

namespace App\Http\Controllers\ApiControllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateProfilePictureRequest;
use App\Http\Requests\Setting\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileApiController extends Controller
{
    /**
     * Update profile data.
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request) : JsonResponse {
        $user = Auth::user();
        if($request->social_media_links != null){
            $socialMediaLinks = explode(PHP_EOL, $request->social_media_links);
            foreach($socialMediaLinks as $url){
                if (filter_var(trim($url), FILTER_VALIDATE_URL) === FALSE) {
                    return response()->json([
                        'message' => 'Invalid URL.',
                        'errors' => ['social_media_links' => ['Invalid URL: '.$url]]
                    ],400);
                }
            }
        }
        $user->social_media_links = $request->social_media_links;
        $user->full_name = $request->has('full_name') ? $request->full_name : null;
        $user->biography = $request->has('biography') ? $request->biography : null;
        $user->save();
        return response()->json(['message' => 'Profile data updated successfully.']);
    }

    /**
     * Update profile picture.
     * @param UpdateProfilePictureRequest $request
     * @return JsonResponse
     */
    public function updateProfilePicture(UpdateProfilePictureRequest $request) : JsonResponse {
        $user = Auth::user();
        $previousProfilePicture = $user->profile_picture_url;
        $file = Storage::disk('s3')->put('/images/'.$user->id, $request->profile_picture_url, 'public');
        $url = Storage::disk('s3')->url($file);
        $user->profile_picture_url = $url;
        $user->save();
        if($previousProfilePicture != null){
            $explode = explode('/',$previousProfilePicture);
            Storage::disk('s3')->delete('/images/'.$user->id.'/'.end($explode));
        }
        return response()->json(['status' => 'success', 'message' => 'Profile picture updated successfully.', 'url' => $url]);
    }
}
