<?php

namespace App\Http\Controllers\WebControllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User\UserConfiguration;
use Illuminate\Contracts\View\View;

class PrivacyController extends Controller
{
    /**
     * Read settings privacy view.
     * @return View
     */
    public function index(): View {
        return view('settings.privacy')->with([
            'userConfiguration' => UserConfiguration::read()
        ]);
    }
}
