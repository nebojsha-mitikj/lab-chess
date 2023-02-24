<?php

namespace App\Http\Controllers\WebControllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Experience\Experience;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;


class AnalyticsController extends Controller
{

    /**
     * Array of last 30 dates in Y-m-d format
     * @return array
     */
    private function getDates() : array {
        $dates = [];
        for($i = 0; $i <= 29; $i++){
            $date =  Carbon::now()->subDay($i)->format('Y-m-d');
            $dates[] = $date;
        }
        return $dates;
    }

    /**
     * Gets data from queryResults
     * @param array $queryResults
     * @return array
     */
    private function getDataFromQueryResults(array $queryResults) : array {
        $data = $visitedDates = [];
        foreach($queryResults as $result){
            $date = Carbon::createFromFormat('Ymd', $result[2])->format('Y-m-d');
            if(in_array($date, $visitedDates)){
                for($i = 0; $i < count($data); $i++){
                    if($data[$i]['date'] === $date){
                        $data[$i]['total'] = (string)((int)$data[$i]['total'] + (int)$result[3]);
                        break;
                    }
                }
            }else{
                $data[] = [
                    'date' => Carbon::createFromFormat('Ymd', $result[2])->format('Y-m-d'),
                    'total' => $result[3]
                ];
            }
            $visitedDates[] = $date;
        }
        return $data;
    }

    /**
     * Gets views per date for page with $url for last $days
     * @param string $url
     * @param int $days
     * @return array
     */
    private function getViewsPerDatesForUrl(string $url, int $days = 30) : array {
        $queryResults = Analytics::performQuery(
            Period::days($days),
            'ga:pageviews',
            [
                'dimensions' => 'ga:pagePath,ga:pageTitle,ga:date',
                'filters' => 'ga:pagePath=='.$url,
                'max-results' => 30,
            ]
        )->rows;
        return $this->getDataFromQueryResults($queryResults);
    }

    /**
     * Go to analytics position page.
     */
    public function index(): View {
        $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->where('created_at', '>', Carbon::now()->subDay(30))
            ->get();

        $experience = Experience::selectRaw('DATE(created_at) as date, SUM(experience) as total')
            ->groupBy('date')
            ->where('created_at', '>', Carbon::now()->subDay(30))
            ->get();

        $active = DB::table('user_goal')
            ->join('user', 'user_goal.user_id', '=', 'user.id')
            ->select('user.email', 'user.username', 'user_goal.goal_reach_count')
            ->orderBy('user_goal.goal_reach_count', 'DESC')
            ->limit(15)
            ->get();

        return view('analytics.analytics')->with([
            'users' => $users,
            'experience' => $experience,
            'active' => $active,
            'dates' => json_encode($this->getDates()),
            'registerViews' => json_encode($this->getViewsPerDatesForUrl('/'))
        ]);
    }
}
