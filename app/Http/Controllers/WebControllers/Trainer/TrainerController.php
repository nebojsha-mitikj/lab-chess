<?php

namespace App\Http\Controllers\WebControllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Trainer\Trainer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class TrainerController extends Controller
{
    /**
     * Go to trainer page.
     * @return View
     */
    public function index(): View
    {
        return view('trainer.trainer')->with([
            'trainers' => Trainer::trainerProgress(Auth::id())
        ]);
    }

}
