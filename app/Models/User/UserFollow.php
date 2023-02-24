<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserFollow extends Model
{
    use HasFactory;

    protected $table = 'following';

    protected $fillable = ['user_id', 'follower_id'];

    /**
     * Check if user follows profile.
     * @param int $profileId
     * @return bool
     */
    public static function follows(int $profileId): bool {
        return self::where('follower_id','=',Auth::id())->where('user_id','=',$profileId)->exists();
    }

    /**
     * On registration, everyone follows labchess profile.
     * @param $follower_id
     */
    public static function followLabChess($follower_id){
        $labChessUser = User::where('email','=','labchess97@gmail.com')->first();
        if($labChessUser && $labChessUser->id != $follower_id){
            self::create([
                'user_id' => $labChessUser->id,
                'follower_id' => $follower_id
            ]);
        }
    }
}
