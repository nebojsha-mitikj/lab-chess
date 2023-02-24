<?php

namespace App\Http\Controllers\WebControllers\Page;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    /**
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse {
        if(Auth::user() === null){
            return view('welcome')->with(['users' => User::count()]);
        }
        return redirect()->route('profile', ['username' => Auth::user()->username]);
    }
}
