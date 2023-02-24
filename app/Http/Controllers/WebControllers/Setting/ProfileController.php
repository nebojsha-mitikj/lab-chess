<?php

namespace App\Http\Controllers\WebControllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ProfileController extends Controller
{
    /**
     * Read settings profile view.
     * @return View
     */
    public function index(): View {
        return view('settings.profile');
    }
}
