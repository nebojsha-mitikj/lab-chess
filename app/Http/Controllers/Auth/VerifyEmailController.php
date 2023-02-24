<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class VerifyEmailController extends Controller
{

    /**
     * Mark the authenticated user's email address as verified.
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function __invoke(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect('/email-verified')->with('success', 'Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
            return redirect('/email-verified')->with('success', 'Successfully verified email.');
        }

        return redirect('login');
    }
}
