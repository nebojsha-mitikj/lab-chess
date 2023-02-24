<?php

namespace App\Console\Commands\Trainer;

use App\Models\Trainer\TrainerPosition;
use App\Models\Trainer\TrainerVariant;
use Illuminate\Console\Command;

class ResetTrainerNumbers extends Command
{

    protected $signature = 'trainer:reset-numbers';

    protected $description = 'Resets trainer numbers.';

    public function __construct(){
        parent::__construct();
    }

    public function resetVariantNumbers(){
        $trainerVariants = TrainerVariant::orderBy('trainer_id', 'ASC')->get();
        $previousTrainerId = $trainerVariants[0]->trainer_id;
        $variantNumber = 0;
        for($i = 0; $i < count($trainerVariants); $i++){
            ++$variantNumber;
            if($trainerVariants[$i]->trainer_id !== $previousTrainerId){
                $previousTrainerId = $trainerVariants[$i]->trainer_id;
                $variantNumber = 1;
            }
            $trainerVariants[$i]->number = $variantNumber;
            $trainerVariants[$i]->save();
        }
        return true;
    }

    public function resetPositionNumbers(){
        $trainerPositions = TrainerPosition::orderBy('trainer_id', 'ASC')->orderBy('variant_id', 'ASC')->get();
        $previousVariantId = $trainerPositions[0]->variant_id;
        $variantNumber = 0;
        for($i = 0; $i < count($trainerPositions); $i++){
            ++$variantNumber;
            if($trainerPositions[$i]->variant_id !== $previousVariantId){
                $previousVariantId = $trainerPositions[$i]->variant_id;
                $variantNumber = 1;
            }
            $trainerPositions[$i]->number = $variantNumber;
            $trainerPositions[$i]->save();
        }
        return true;
    }

    public function handle(){
        $this->resetVariantNumbers();
        $this->resetPositionNumbers();
    }
}
