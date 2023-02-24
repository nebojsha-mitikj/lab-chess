<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserLevel extends Model
{
    use HasFactory;

    protected $table = 'user_level';

    protected $fillable = ['name', 'level', 'required_xp'];


    /**
     * @param int $userXP
     * @return UserLevel|mixed
     */
    public static function getUserLevel(int $userXP){
        $userLevels = UserLevel::orderBy('level', 'DESC')->get();

        for($i = 0; $i < count($userLevels); $i++){
            if($userXP >= $userLevels[$i]->required_xp){
                return $userLevels[$i];
            }
        }
        return $userLevels[0];
    }

    /**
     * Read user levels with user percentage.
     * @return UserLevel[]|Collection
     */
    public static function userLevelsWithUserPercentage(){
        $userLevels = UserLevel::all();
        $query = "SELECT COUNT(id) AS total, ";
        for($i = 0; $i < count($userLevels); $i++){
            $userLevel = $userLevels[$i];
            $name = implode('_', explode(' ', implode('',explode('.', strtolower($userLevel->name)))));
            $nextRequiredXp = isset($userLevels[$i+1]) ? $userLevels[$i+1]->required_xp : null;
            if($i === 0){
                $query .= "SUM(IF(experience < $nextRequiredXp, 1, 0)) AS $name, ";
            }else if($i < count($userLevels) - 1){
                $query .= "SUM(IF(experience >= $userLevel->required_xp AND experience < $nextRequiredXp, 1, 0)) AS $name, ";
            }else{
                $query .= "SUM(IF(experience > $userLevel->required_xp, 1, 0)) AS $name ";
            }
        }
        $query .= "FROM user";
        $userLevelsCount = (array)DB::select($query)[0];
        for($i = 0; $i < count($userLevels); $i++){
            $name = implode('_', explode(' ', implode('',explode('.', strtolower($userLevels[$i]->name)))));
            $userLevels[$i]->user_percentage = number_format(100 *  $userLevelsCount[$name] / $userLevelsCount['total'], 1);
        }
        return $userLevels;
    }

}
