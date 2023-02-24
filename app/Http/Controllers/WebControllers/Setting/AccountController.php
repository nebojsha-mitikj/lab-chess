<?php

namespace App\Http\Controllers\WebControllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User\UserConfiguration;
use Illuminate\Contracts\View\View;

class AccountController extends Controller
{
    /**
     * Read settings account view.
     * @return View
     */
    public function index(): View {
        return view('settings.account')->with([
            'userConfiguration' => UserConfiguration::read()
        ]);
    }
}
