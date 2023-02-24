<?php

namespace App\Http\Controllers\WebControllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Goal\DailyGoal;
use App\Models\User\UserConfiguration;
use Illuminate\Contracts\View\View;

class DailyGoalController extends Controller
{
    /**
     * Read daily goal view.
     * @return View
     */
    public function index(): View {
        return view('settings.coach')->with([
            'dailyGoal' => DailyGoal::all(),
            'userConfiguration' => UserConfiguration::read()
        ]);
    }
}
