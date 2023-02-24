<?php

namespace App\Http\Controllers\WebControllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use Illuminate\Contracts\View\View;

class CourseController extends Controller
{

    private const ONLY_SHOW = [
        'Elementary Mates',
        'Pawn Endgames',
        'Bishop Endgames',
        'Knight Endgames',
        'Pieces Endgames',
        'Rook Endgames',
        'Rook & Pieces Endgames',
        'Queen Endgames'
    ];

    /**
     * Go to courses page.
     * @return View
     */
    public function index(): View {
        return view('courses.courses')->with([
            'courses' => Course::with('lectures.userLectures')
                ->whereIn('name', self::ONLY_SHOW)
                ->orderBy('id', 'ASC')
                ->get()
        ]);
    }
}
