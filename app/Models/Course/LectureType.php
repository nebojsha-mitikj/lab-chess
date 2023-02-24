<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LectureType extends Model
{
    use HasFactory;

    protected $table = 'lecture_type';

    protected $fillable = [
        'type'
    ];

    /**
     * Get the lectures with type
     */
    public function lectures() : HasMany
    {
        return $this->hasMany(Lecture::class);
    }
}
