<?php

namespace App\Http\Controllers\WebControllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Trainer\TrainerVariant;
use Illuminate\Contracts\View\View;

class TrainerVariantController extends Controller
{
    /**
     * Go to trainer variant page.
     * @param string $code
     * @param integer $variantNumber
     * @return View
     */
    public function index(string $code, int $variantNumber = 1): View {
        return view('trainer.trainer-variant')->with(
            TrainerVariant::index($code, $variantNumber)
        );
    }
}
