<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;

class SeederHelper
{
    public static function addTime(array &$data){
        for($i = 0; $i < count($data); $i++){
            $data[$i]['created_at'] = Carbon::now();
            $data[$i]['updated_at'] = Carbon::now();
        }
    }

    public static function addUuid(array &$data){
        for($i = 0; $i < count($data); $i++) $data[$i]['uuid'] = Str::uuid();
    }
}
