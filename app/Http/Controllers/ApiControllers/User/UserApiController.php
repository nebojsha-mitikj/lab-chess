<?php

namespace App\Http\Controllers\ApiControllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserSearchRequest;
use App\Mail\DeleteAccountRequest;
use App\Models\Experience\Experience;
use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserDeleteAccountRequest;
use App\Models\User\UserFollow;
use App\Models\User\UserGoal;
use App\Models\User\UserLecture;
use App\Models\User\UserNotification;
use App\Models\User\UserTrainerPosition;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class UserApiController extends Controller
{
    /**
     * Find friends.
     * @param UserSearchRequest $request
     * @return JsonResponse
     */
    public function search(UserSearchRequest $request): JsonResponse {
        $profiles = DB::table('user')
            ->join('user_configuration','user.id', '=','user_id')
            ->where('public_profile', true)
            ->where('user.id','!=',Auth::id())
            ->where(function($q) use ($request) {
                $q->where('email','like','%'.$request->search.'%')
                    ->orWhere('username','like','%'.$request->search.'%')
                    ->orWhere('full_name','like','%'.$request->search.'%');
            })->select(['user.id','username','full_name','profile_picture_url'])
            ->limit(20)
            ->get();

        return response()->json(['message' => $profiles->count() . ' profiles found.','profiles' => $profiles]);
    }

    /**
     * Delete User Request
     * @return JsonResponse
     */
    public function deleteRequest() : JsonResponse {
        $userDeleteAccountRequest = UserDeleteAccountRequest::where('user_id', '=', Auth::id())->first();
        if($userDeleteAccountRequest === null){
            $userDeleteAccountRequest = UserDeleteAccountRequest::createRow();
        }else if(Carbon::now()->diffInDays($userDeleteAccountRequest->created_at) < 1){
            return response()->json(['message' => UserDeleteAccountRequest::$errorMessage]);
        }else{
            $userDeleteAccountRequest->delete();
            $userDeleteAccountRequest = UserDeleteAccountRequest::createRow();
        }
        $deletionLink = URL::to('/') . '/user/deleteRequest/' . $userDeleteAccountRequest->token;
        Mail::to(Auth::user()->email)->send(new DeleteAccountRequest(Auth::user()->email, $deletionLink));
        return response()->json(['message' => UserDeleteAccountRequest::$successMessage]);
    }


    /**
     * Confirm Delete Request.
     * @param string $token
     * @return JsonResponse
     */
    public function confirmDeleteRequest(string $token) : string {
        $userDeleteAccountRequest = UserDeleteAccountRequest::where('token', '=', $token)->first();
        if($userDeleteAccountRequest === null || Carbon::now()->diffInDays($userDeleteAccountRequest->created_at) > 1){
            abort(404);
            return "404";
        }
        $userID = $userDeleteAccountRequest->user_id;
        UserGoal::where('user_id', '=', $userID)->delete();
        UserTrainerPosition::where('user_id', '=', $userID)->delete();
        Experience::where('user_id', '=', $userID)->delete();
        UserDeleteAccountRequest::where('user_id', '=', $userID)->delete();
        UserConfiguration::where('user_id', '=', $userID)->delete();
        UserNotification::where('user_id', '=', $userID)->delete();
        UserFollow::where('user_id', '=', $userID)->orWhere('follower_id', '=', $userID)->delete();
        UserLecture::where('user_id', '=', $userID)->delete();
        User::where('id', '=', $userID)->delete();

        return "We've deleted your data successfully.";
    }

    /**
     * Unsubscribe from emails.
     * @param string $token
     * @return string
     */
    public function unsubscribe(string $token) : string {
        $user = User::where('uuid', '=', $token)->first();
        if($user == null){
            abort(404);
            return "404";
        }
        UserNotification::where('user_id', '=', $user->id)->update([
            'product_update' => false,
            'new_follow' => false,
            'weekly_report' => false,
        ]);
        return "Successfully unsubscribed. If you change your mind, you may subscribe again in Settings -> Notifications.";
    }

}
