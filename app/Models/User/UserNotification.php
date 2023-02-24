<?php

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserNotification extends Model
{
    use HasFactory;

    protected $table = 'user_notification';

    protected $fillable = [
        'user_id',
        'product_update',
        'new_follow',
        'weekly_report',
        'received_empty_weekly_report'
    ];

    /**
     * Get the user.
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Read user configuration for current user.
     * @param int|null $userID
     * @return UserNotification
     */
    public static function read(int $userID = null) : UserNotification {
        if($userID === null) $userID = Auth::id();
        return self::where('user_id','=',$userID)->first();
    }

    /**
     * Create default user notifications.
     * @param int $userID
     */
    public static function createDefault(int $userID){
        self::create([
            'user_id' => $userID
        ]);
    }

    /**
     * Checks if $user should receive follow notification.
     * @param User $user
     * @return bool
     */
    public static function shouldReceiveFollowNotifications(User $user) : bool {
        if($user->email_verified_at === null || !self::read($user->id)->new_follow || $user->username === 'labchess') return false;

        $userFollowEmailHistory = DB::table('user_follow_email_history')
            ->where('follower_id', Auth::id())->where('user_id', '=', $user->id)->first();
        
        if($userFollowEmailHistory === null){
            DB::table('user_follow_email_history')->insert([
                'follower_id' => Auth::id(),
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return true;
        }
        if(Carbon::now()->diffInDays($userFollowEmailHistory->created_at) >= 1){
            DB::table('user_follow_email_history')
                ->where('follower_id', Auth::id())->where('user_id', '=', $user->id)
                ->update(['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            return true;
        }
        return false;
    }
}
