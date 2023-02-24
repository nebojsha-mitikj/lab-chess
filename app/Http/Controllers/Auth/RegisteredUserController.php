<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserFollow;
use App\Models\User\UserGoal;
use App\Models\User\UserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email|max:255|unique:user',
            'password' => ['required',Rules\Password::defaults()],
            'username' => 'required|string|max:255|min:3|unique:user',
            'privacyPolicy' => 'accepted'
        ]);

        $userData = [
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'uuid' => Str::uuid()->toString()
        ];

        if($request->has('timezone')) $userData['timezone'] = $request->timezone;

        $user = User::create($userData);
        UserConfiguration::createDefault($user->id);
        UserNotification::createDefault($user->id);
        UserGoal::createDefault($user->id);
        UserFollow::followLabChess($user->id);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/');
    }
}
