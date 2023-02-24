<?php

namespace App\Http\Controllers\WebControllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Course\Lecture;
use App\Models\User\UserConfiguration;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LectureController extends Controller
{
    /**
     * Go to lecture page.
     * @param string $courseName
     * @param string $lecture
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(string $courseName, string $lecture) {
        if(substr_count($lecture,'.') != 1) return redirect()->route('courses');
        $courseName = str_replace('-', ' ', $courseName);
        $course = Course::where('name',$courseName)->first();
        if(!$course) return  redirect()->route('courses');
        $lecture = Lecture::where('course_id',$course->id)
            ->where('number',$lecture)
            ->with('lectureStep')
            ->first();
        if(!$lecture) return  redirect()->route('courses');
        $subscription = Auth::user()->subscription(env('PADDLE_SUBSCRIPTION'));
        if(($subscription == null || !$subscription->active()) && !$lecture->free)
            return redirect()->route('premium');
        $userConfiguration = UserConfiguration::where('user_id',Auth::id())->with(['boardTheme', 'pieceTheme'])->first();
        return view('courses.lecture')->with([
            'course' => $course,
            'userConfiguration' => $userConfiguration,
            'lecture' => $lecture
        ]);
    }
}
