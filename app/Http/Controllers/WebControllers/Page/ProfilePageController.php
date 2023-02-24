<?php

namespace App\Http\Controllers\WebControllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Experience\Experience;
use App\Models\Trainer\Trainer;
use App\Models\User\User;
use App\Models\User\UserLevel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ProfilePageController extends Controller
{
    /**
     * Read profile data.
     * @param string $username
     * @return View
     */
    public function index(string $username): View {
        $profile = User::where('username', '=', $username)->with('userConfiguration', 'userGoal')->first();
        if(!$profile || ($profile->userConfiguration->public_profile == false && Auth::id() != $profile->id))
            abort(404);
        $userLevel = UserLevel::getUserLevel($profile->experience);
        $followCount = User::followCount($profile->id);
        $trainers = Trainer::trainerProgress($profile->id, true);
        $courses = Course::courseProgress($profile->id, true);
        $profile->level = 'Level '.$userLevel->level.' - '.$userLevel->name;
        $profile->subscribed = $profile->subscribed(env("PADDLE_SUBSCRIPTION"));
        foreach($trainers as $trainer){
            if($trainer->name !== 'Elementary Mates'){
                $trainer->name .= ' Endgames';
            }
        }
        return view('pages.profile')->with([
            'profile' => $profile,
            'followCount' => $followCount,
            'userFollows' => $followCount['follows'],
            'trainer' => $trainers,
            'courses' => $courses,
            'xpActivity' => Experience::xpActivity($profile->id)
        ]);
    }
}
