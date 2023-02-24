<?php

namespace App\Models\Trainer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainerVariant extends Model
{
    use HasFactory;

    protected $table = 'trainer_variant';

    protected $fillable = [
        'trainer_id',
        'number',
        'pieces'
    ];

    /**
     * Get trainer.
     * @return BelongsTo
     */
    public function trainer(): BelongsTo {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }

    /**
     * Get the trainer positions for trainer variant.
     * @return HasMany
     */
    public function positions(): HasMany {
        return $this->hasMany(TrainerPosition::class, 'variant_id');
    }

    /**
     * @param $number
     * @param $variants
     * @return null
     */
    private static function findVariantInVariantsOrAbort($number, $variants){
        $variant = null;
        foreach ($variants as $variantRow){
            if($variantRow->number === $number){
                $variant = $variantRow;
                break;
            }
        }
        if(!$variant) abort(404);
        return $variant;
    }

    /**
     * Read data for trainer-variant.
     * @param string $code
     * @param int $variantNumber
     * @return array
     */
    public static function index(string $code, int $variantNumber) : array {
        $trainers = Trainer::withCount('variants')->orderBy('id')->get();
        $trainer = Trainer::findTrainerInTrainersOrAbort($code, $trainers);
        $variants = TrainerVariant::where('trainer_id',$trainer->id)->select('id', 'number', 'pieces')->get();
        $variant = self::findVariantInVariantsOrAbort($variantNumber, $variants);
        $allTrainerPositions = TrainerPosition::where('trainer_id',$trainer->id)
            ->with('userTrainerPositions')
            ->select('id','variant_id','trainer_id','number')->get();

        $finishedTrainerPositionsCount = 0;
        foreach($allTrainerPositions as $position) if(count($position->userTrainerPositions) > 0) ++$finishedTrainerPositionsCount;
        if($trainer->name !== 'Elementary Mates') $trainer->name .= ' Endgames';

        return [
            'trainer' => $trainer,
            'trainers' => $trainers,
            'variants' => $variants,
            'variantId' => $variant->id,
            'positions' => $allTrainerPositions,
            'progress' => (100 * $finishedTrainerPositionsCount) / count($allTrainerPositions)
        ];
    }
}
