<?php

namespace App\Models\User;

use App\Models\Course\Course;
use App\Models\Course\Lecture;
use App\Models\Experience\Experience;
use App\Notifications\PasswordReset;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Paddle\Billable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'username',
        'full_name',
        'role',
        'password',
        'profile_picture_url',
        'experience',
        'biography',
        'social_media_links',
        'timezone',
        'uuid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send password reset notification.
     * @param string $token
     */
    public function sendPasswordResetNotification($token){
        $this->notify(new PasswordReset($token));
    }

    /**
     * Get the user notifications.
     */
    public function userNotification() : HasOne
    {
        return $this->hasOne(UserNotification::class);
    }

    /**
     * Get the user goal.
     */
    public function userGoal() : HasOne
    {
        return $this->hasOne(UserGoal::class);
    }


    /**
     * Get the user configuration.
     */
    public function userConfiguration() : HasOne
    {
        return $this->hasOne(UserConfiguration::class);
    }

    /**
     * Get user experience.
     */
    public function experience() : HasMany
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * The courses that belong to the user.
     */
    public function courses() : BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'user_course');
    }

    /**
     * The lectures that belong to the user.
     */
    public function lectures() : BelongsToMany
    {
        return $this->belongsToMany(Lecture::class, 'user_lecture');
    }

    /**
     * The users that the user follows.
     */
    public function following() : BelongsToMany
    {
        return $this->belongsToMany(User::class,'following','user_id','follower_id');
    }

    /**
     * The users that follow the user.
     */
    public function followers() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'following', 'follower_id','user_id');
    }

    /**
     * Read the followers and following count.
     * @param int $user_id
     * @return array
     */
    public static function followCount(int $user_id): array {

        $userFollowData = DB::table('following')
            ->where('user_id', '=', $user_id)
            ->orWhere('follower_id', '=', $user_id)
            ->select('user_id', 'follower_id')->get();

        $followers = $following = 0;
        $follows = false;

        foreach($userFollowData as $row){
            $row->user_id === $user_id ? ++$followers : ++$following;
            if($row->follower_id === Auth::id()) $follows = true;
        }

        return [
            'followers' => $followers,
            'following' => $following,
            'follows' => $follows
        ];
    }

    /**
     * Add User Experience.
     * @param $experience
     */
    public static function addExperience($experience)
    {
        self::where('id',Auth::id())->update([
            'experience' => Auth::user()->experience + $experience
        ]);
    }

    /**
     * Get the total XP for today for user in user's timezone.
     * @return int
     */
    public static function totalXpForToday() : int {
        $user = Auth::user();
        $currentDateForUser = Carbon::now($user->timezone)->format('Y-m-d');

        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $currentDateForUser.' 00:00:00', $user->timezone)->setTimezone('UTC');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $currentDateForUser.' 23:59:59', $user->timezone)->setTimezone('UTC');

        $totalXpForUserForToday = (int)DB::table('experience')
            ->where('user_id',$user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('experience');

        return $totalXpForUserForToday;
    }

    /**
     * Get user rank.
     * @return int
     */
    public static function getRank(): int {
        return DB::select('
                SELECT count(id) as user_rank FROM user
                WHERE
                      experience > (SELECT experience FROM user WHERE id = '.Auth::id().')
                      AND email != "labchess97@gmail.com"'
        )[0]->user_rank + 1;
    }

    /**
     * Get the subscription status which can be:
     * paid => The subscription is active and not on grace.
     * grace => The subscription is active, but on grace.
     * canceled => The subscription is canceled.
     * free => The user never subscribed.
     * @return string
     */
    public function subscriptionStatus() : string {
        $subscription = $this->subscription(env('PADDLE_SUBSCRIPTION'));
        if($subscription == null) return 'free';
        if($subscription->active() && $subscription->onGracePeriod()) return  'grace';
        return $subscription->active() ? 'paid' : 'canceled';
    }
}
