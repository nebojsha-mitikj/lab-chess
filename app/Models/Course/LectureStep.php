<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureStep extends Model
{
    use HasFactory;

    protected $table = 'lecture_step';

    protected $fillable = ['lecture_id', 'number', 'title', 'content'];

}
