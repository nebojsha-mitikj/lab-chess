<?php

namespace App\Models\Borad;

use App\Models\User\UserConfiguration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardTheme extends Model
{
    use HasFactory;

    protected $table = 'board_theme';

    protected $fillable = [
        'light_square',
        'dark_square',
        'image_url'
    ];

    /**
     * Get the user configurations.
     */
    public function userConfiguration() : HasMany
    {
        return $this->hasMany(UserConfiguration::class);
    }
}
