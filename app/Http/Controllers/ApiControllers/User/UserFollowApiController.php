<?php

namespace App\Http\Controllers\ApiControllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\FollowUserRequest;
use App\Http\Requests\User\UnfollowUserRequest;
use App\Http\Requests\User\UserFollowersRequest;
use App\Http\Requests\User\UserFollowingRequest;
use App\Models\User\User;
use App\Models\User\UserFollow;
use App\Mail\UserFollow as UserFollowMail;
use App\Models\User\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserFollowApiController extends Controller
{
    /**
     * Read profile followers.
     * @param UserFollowersRequest $request
     * @return JsonResponse
     */
    public function followers(UserFollowersRequest $request): JsonResponse {
        $user = User::where('username','=',$request->username)->first();
        if($user == null){
            return response()->json([
                'message' => 'Data not found.',
                'errors' => ['username' => ['Profile not found.']]
            ], 404);
        }
        $followers = DB::table('following')
            ->join('user','follower_id','=','user.id')
            ->join('user_configuration','user.id', '=','user_configuration.user_id')
            ->where('following.user_id','=', $user->id)
            ->where('public_profile','=', true)
            ->select(['user.id','username','profile_picture_url'])
            ->orderBy('following.created_at', 'DESC')
            ->paginate(5);
        return response()->json(['message' => 'Success', 'followers' => $followers, 'hasMore' => $followers->hasMorePages()]);
    }

    /**
     * Read profile follows.
     * @param UserFollowingRequest $request
     * @return JsonResponse
     */
    public function following(UserFollowingRequest $request): JsonResponse{
        $user = User::where('username','=',$request->username)->first();
        if($user == null){
            return response()->json([
                'message' => 'Data not found.',
                'errors' => ['username' => ['Profile not found.']]
            ], 404);
        }
        $following = DB::table('following')
            ->join('user','user_id','=','user.id')
            ->join('user_configuration','user.id', '=','user_configuration.user_id')
            ->where('follower_id','=', $user->id)
            ->where('public_profile','=', true)
            ->select(['user.id','username','profile_picture_url'])
            ->orderBy('following.created_at', 'DESC')
            ->paginate(5);
        return response()->json(['message' => 'Success', 'following' => $following, 'hasMore' => $following->hasMorePages()]);
    }

    /**
     * Follow Profile
     * @param FollowUserRequest $request
     * @return JsonResponse
     */
    public function follow(FollowUserRequest $request): JsonResponse {
        $profile = User::where('username','=',$request->username)->first();
        if(!$profile){
            return response()->json(['message' => 'User '.$request->username.' was not found.'], 404);
        }
        UserFollow::firstOrCreate([
            'follower_id' => Auth::id(),
            'user_id' => $profile->id
        ]);
        if(UserNotification::shouldReceiveFollowNotifications($profile)){
            Mail::to($profile->email)->send(new UserFollowMail($profile, Auth::user()->username));
        }
        return response()->json(['message' => 'You are now following '.$profile->username]);
    }

    /**
     * Unfollow Profile
     * @param UnfollowUserRequest $request
     * @return JsonResponse
     */
    public function unfollow(UnfollowUserRequest $request): JsonResponse {
        $profile = User::where('username','=',$request->username)->first();
        if(!$profile){
            return response()->json(['message' => 'User '.$request->username.' was not found.'], 404);
        }
        UserFollow::where('follower_id', '=', Auth::id())->where('user_id','=',$profile->id)->delete();
        return response()->json(['message' => 'You are no longer following '.$profile->username]);
    }
}
