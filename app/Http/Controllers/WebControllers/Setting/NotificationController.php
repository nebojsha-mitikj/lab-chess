<?php

namespace App\Http\Controllers\WebControllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User\UserNotification;
use Illuminate\Contracts\View\View;

class NotificationController extends Controller
{
    /**
     * @return View
     */
    public function index(): View {
        return view('settings.notifications')->with([
            'userNotification' => UserNotification::read()
        ]);
    }
}
