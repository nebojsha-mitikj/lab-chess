<?php

namespace App\Models\Experience;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Experience extends Model
{
    use HasFactory;

    protected $table = 'experience';

    protected $fillable = [
        'user_id',
        'experience'
    ];

    /**
     * Get Last Week Dates.
     * @return array
     */
    private static function getLastWeekDates() : array {
        $lastWeekDates = [];
        for($i = 6; $i >= 0; $i--)
            $lastWeekDates[Carbon::now(Auth::user()->timezone)->subDays($i)->format('Y-m-d')] = 0;
        return $lastWeekDates;
    }

    /**
     * Get the user.
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add experience.
     * @param int $experience
     */
    public static function add(int $experience)
    {
        $thirtyOneDaysAgo = Carbon::now()->subDays(31);
        self::where('created_at', '<', $thirtyOneDaysAgo)->delete();
        self::create([
            'user_id' => Auth::id(),
            'experience' => $experience
        ]);
    }

    /**
     * Get XP Activity.
     * @param integer $userId
     * @return array
     */
    public static function xpActivity(int $userId) : array {
        $userTimezone = Auth::user()->timezone;
        $weekEarlierForUser = Carbon::now($userTimezone)->subDays(7);
        $xpActivity = Experience::groupBy('date')
            ->selectRaw("sum(experience) as experience, DATE_FORMAT(DATE(CONVERT_TZ(created_at,'UTC','$userTimezone')), '%Y-%m-%d') as date")
            ->where('user_id',$userId)
            ->where('created_at', '>=', $weekEarlierForUser)
            ->orderBy('date')
            ->pluck('experience', 'date')
            ->toArray();
        $lastWeekDates = self::getLastWeekDates();
        $data = [];
        foreach($lastWeekDates as $key => $value){
            if(array_key_exists($key, $xpActivity)) $lastWeekDates[$key] = (int)$xpActivity[$key];
            $day = Carbon::createFromFormat('Y-m-d', $key)->format('D');
            $data[] = [$day, $lastWeekDates[$key]];
        }
        return $data;
    }
}
