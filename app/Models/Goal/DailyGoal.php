<?php

namespace App\Models\Goal;

use App\Models\User\UserConfiguration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyGoal extends Model
{
    use HasFactory;

    protected $table = 'daily_goal';

    protected $fillable = [
        'experience',
        'level'
    ];

    /**
     * Get the user configurations.
     */
    public function userConfiguration() : HasMany
    {
        return $this->hasMany(UserConfiguration::class);
    }
}
