<?php

namespace App\Models\Course;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Lecture extends Model
{
    use HasFactory;

    protected $table = 'lecture';

    protected $fillable = [
        'course_id',
        'type',
        'number',
        'free',
        'description'
    ];

    /**
     * User lectures for active user.
     */
    public function userLectures() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_lecture', 'lecture_id', 'user_id')
            ->where('user_id', '=', Auth::id())->select('user.id');
    }

    /**
     * All user lectures.
     * @return BelongsToMany
     */
    public function allUserLectures() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_lecture', 'lecture_id', 'user_id')
            ->select('user.id');
    }

    /**
     * Get the lecture course.
     */
    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get lecture steps.
     */
    public function lectureStep() : HasMany {
        return $this->hasMany(LectureStep::class, 'lecture_id');
    }
}
