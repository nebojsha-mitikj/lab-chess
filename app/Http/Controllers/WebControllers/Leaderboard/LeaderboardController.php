<?php

namespace App\Http\Controllers\WebControllers\Leaderboard;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\UserLevel;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{

    /**
     * Get leaderboards
     * @return array
     */
    private function getLeaderboards() : array
    {
        $allTime = User::select('id', 'username', 'profile_picture_url', 'experience')
            ->where('email', '!=', 'labchess97@gmail.com')
            ->orderBy('experience', 'DESC')->limit(10)->get();

        $experienceQueryBuilder = DB::table('experience')
            ->join('user','experience.user_id','=','user.id')
            ->select( DB::raw('SUM(experience.experience) as experience'), 'user.username', 'user.profile_picture_url')
            ->where('email', '!=', 'labchess97@gmail.com')
            ->groupBy('user_id')->orderBy('experience', 'DESC')->limit(10);

        $experienceQueryBuilder1 = clone $experienceQueryBuilder;
        $experienceQueryBuilder2 = clone $experienceQueryBuilder;

        $pastWeek = $experienceQueryBuilder1->where('experience.created_at', '>=', Carbon::now()->subDays(7))->get();
        $pastMonth = $experienceQueryBuilder2->where('experience.created_at', '>=', Carbon::now()->subDays(30))->get();

        return [
            'boards' => [
                ['name' => 'Past 7 days', 'leaders' => $pastWeek],
                ['name' => 'Past 30 days', 'leaders' => $pastMonth],
                ['name' => 'All-time', 'leaders' => $allTime]
            ]
        ];
    }

    /**
     * Go to leaderboard page.
     * @return View
     */
    public function index(): View
    {
        return view('leaderboard.leaderboard')->with([
            'boards' => $this->getLeaderboards()['boards'],
            'userLevels' => UserLevel::userLevelsWithUserPercentage(),
            'userRank' => User::getRank()
        ]);
    }
}
