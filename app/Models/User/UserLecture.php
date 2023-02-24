<?php

namespace App\Models\User;

use App\Models\Course\Lecture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserLecture extends Model
{
    use HasFactory;

    protected $table = 'user_lecture';

    protected $fillable = ['user_id', 'lecture_id'];

    /**
     * Complete Lecture
     * @param int $lectureId
     */
    public static function completeLecture(int $lectureId) {
        Lecture::findOrFail($lectureId);
        if(!UserLecture::where('user_id', Auth::id())->where('lecture_id', $lectureId)->first()){
            $userLecture = new UserLecture();
            $userLecture->user_id = Auth::id();
            $userLecture->lecture_id = $lectureId;
            $userLecture->save();
        }
    }
}
