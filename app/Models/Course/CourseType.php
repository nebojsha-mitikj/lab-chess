<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseType extends Model
{
    use HasFactory;

    protected $table = 'course_type';

    protected $fillable = [
        'type'
    ];

    /**
     * Get the courses with type
     */
    public function courses() : HasMany
    {
        return $this->hasMany(Course::class);
    }
}
