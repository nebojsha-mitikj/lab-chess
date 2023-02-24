<?php

namespace App\Models\Piece;

use App\Models\User\UserConfiguration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PieceTheme extends Model
{
    use HasFactory;

    protected $table = 'piece_theme';

    protected $fillable = ['name'];

    /**
     * Get the user configurations.
     */
    public function userConfiguration() : HasMany
    {
        return $this->hasMany(UserConfiguration::class);
    }
}
