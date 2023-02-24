<?php

namespace App\Http\Controllers\WebControllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class EmailVerifiedController extends Controller
{
    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function index() {
        return redirect('/');
    }
}
