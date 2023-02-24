<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureStepBody extends Model
{
    use HasFactory;

    protected $table = 'lecture_step_body';

    protected $fillable = ['lecture_step_id', 'number', 'type', 'content'];
}
