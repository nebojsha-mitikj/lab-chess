<?php

namespace App\Http\Controllers\WebControllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class PasswordController extends Controller
{
    /**
     * Read settings password view.
     * @return View
     */
    public function index(): View {
        return view('settings.password');
    }
}
