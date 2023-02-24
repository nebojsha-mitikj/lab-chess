<?php

namespace App\Models\Course;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $table = 'course';

    protected $fillable = [
        'name',
        'image_url'
    ];

    /**
     * The users that belong to the course.
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_course');
    }

    /**
     * Get course lectures.
     */
    public function lectures() : HasMany
    {
        return $this->hasMany(Lecture::class)->orderBy('number', 'ASC');
    }

    /**
     * Get courses with user progress.
     * @param int $userID
     * @param bool $onlyStartedCourses
     * @return array
     */
    public static function courseProgress(int $userID, bool $onlyStartedCourses = false) : array
    {
        $toReturn = [];
        $courses = Course::with([
            'lectures.allUserLectures' => function($query) use ($userID) {
                return $query->where('user_id', '=', $userID);
            }
        ])->get();

        foreach($courses as $course){
            $tempCourse = new \stdClass();
            $tempCourse->name = $course->name;
            $tempCourse->total_lectures = 0;
            $tempCourse->finished_lectures = 0;
            foreach($course->lectures as $lecture){
                $tempCourse->total_lectures++;
                if(count($lecture->allUserLectures) > 0) $tempCourse->finished_lectures++;
            }
            $toReturn[] = $tempCourse;
        }
        if($onlyStartedCourses){
            return array_filter($toReturn, function($course){
                return $course->finished_lectures > 0;
            });
        }
        return $toReturn;
    }
}
