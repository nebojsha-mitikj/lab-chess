<?php

namespace App\Models\Error;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    use HasFactory;

    protected $table = 'error';

    protected $fillable = ['user_id', 'env', 'code', 'file', 'line', 'message', 'trace'];
}
