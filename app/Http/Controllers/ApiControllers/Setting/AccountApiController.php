<?php

namespace App\Http\Controllers\ApiControllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateAccountRequest;
use App\Models\User\User;
use App\Models\User\UserConfiguration;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AccountApiController extends Controller
{
    /**
     * @var Authenticatable
     */
    private $user;

    /**
     * Updates profile picture.
     * @param $profilePicture
     * @return void
     */
    private function updateProfilePicture($profilePicture) : void {
        $file = Storage::disk('s3')->put('/images/'.$this->user->id, $profilePicture, 'public');
        $url = Storage::disk('s3')->url($file);
        $previousProfilePicture = $this->user->profile_picture_url;
        $this->user->profile_picture_url = $url;
        if($previousProfilePicture != null){
            $explode = explode('/',$previousProfilePicture);
            Storage::disk('s3')->delete('/images/'.$this->user->id.'/'.end($explode));
        }
    }

    /**
     * Update account data.
     * @param UpdateAccountRequest $request
     * @return JsonResponse
     */
    public function update(UpdateAccountRequest $request): JsonResponse {
        $errors = [];
        $this->user = Auth::user();
        if($this->user->email !== $request->email && User::where('email','=',$request->email)->exists()){
            $errors['email'] = ['Email is already taken.'];
        }
        if($this->user->username !== $request->username && User::where('username','=',$request->username)->exists()){
            $errors['username'] = ['Username is already taken.'];
        }
        if(count($errors) > 0){
            return response()->json(['message' => 'Field is already taken.', 'errors' => $errors], 409);
        }
        if($this->user->email !== $request->email){
            $this->user->email = $request->email;
            $this->user->email_verified_at = null;
        }
        if($this->user->username !== $request->username){
            $this->user->username = $request->username;
        }
        UserConfiguration::where('user_id','=',$this->user->id)->update([
            'sound_effects' => $request->sound_effects,
            'animation' => $request->animation
        ]);
        if($request->hasFile('profile_picture_url')){
            $this->updateProfilePicture($request->profile_picture_url);
        }
        $this->user->save();
        return response()->json(['message' => 'Account data update successfully.']);
    }
}
