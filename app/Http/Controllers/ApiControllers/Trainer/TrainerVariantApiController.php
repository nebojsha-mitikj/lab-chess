<?php

namespace App\Http\Controllers\ApiControllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Trainer\TrainerVariant;
use Illuminate\Http\JsonResponse;

class TrainerVariantApiController extends Controller
{
    /**
     * Go to trainer variant page.
     * @param string $code
     * @param integer $variantNumber
     * @return JsonResponse
     */
    public function index(string $code, int $variantNumber = 1): JsonResponse {
        return response()->json(TrainerVariant::index($code, $variantNumber));
    }
}
