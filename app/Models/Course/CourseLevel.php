<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLevel extends Model
{
    use HasFactory;

    protected $table = 'course_level';

    protected $fillable = [
        'level'
    ];

    /**
     * Get the courses with level
     */
    public function courses() : HasMany
    {
        return $this->hasMany(Course::class);
    }
}
