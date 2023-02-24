<?php

namespace App\Console\Commands;
use App\Mail\WeeklyProgressReportMail;
use App\Models\User\UserNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WeeklyProgressReportMails extends Command {

    protected $signature = 'send:weekly-reports';

    protected $description = 'Sends weekly progress report email to users.';

    public function __construct(){
        parent::__construct();
    }

    /**
     * @return Collection
     */
    private function getUsers() : Collection {
        return DB::table('user')
            ->join('user_notification', 'user.id', '=', 'user_notification.user_id')
            ->whereNotNull('email_verified_at')
            ->where('weekly_report', '=', true)
            ->select('user.id as id', 'uuid', 'received_empty_weekly_report', 'username', 'email')
            ->get();
    }

    /**
     * Returns [user_id => total_xp_for_last_week] array map for users.
     * @param array $userIDs
     * @return array
     */
    private function getExperienceMap(array $userIDs) : array {
        $experience = [];
        $usersExperience = DB::table('experience')
            ->select('user_id', DB::raw('SUM(experience) as total'))
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->whereIn('user_id', $userIDs)
            ->groupBy('user_id')
            ->get();
        foreach($usersExperience as $userExperience) $experience[$userExperience->user_id] = (int)$userExperience->total;
        return $experience;
    }

    /**
     * Returns [user_id => total_finished_trainers_last_week] array map for users.
     * @param array $userIDs
     * @return array
     */
    private function getTrainerMap(array $userIDs) : array {
        $trainerMap = [];
        $trainerCount = DB::table('user_trainer_position')
            ->select('user_id', DB::raw('COUNT(id) as total'))
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->whereIn('user_id', $userIDs)
            ->groupBy('user_id')
            ->get();
        foreach($trainerCount as $trainer) $trainerMap[$trainer->user_id] = (int)$trainer->total;
        return $trainerMap;
    }

    /**
     * Returns [user_id => total_finished_lectures_last_week] array map for users.
     * @param array $userIDs
     * @return array
     */
    private function getLectureMap(array $userIDs) : array {
        $lectureMap = [];
        $lectureCount = DB::table('user_lecture')
            ->select('user_id', DB::raw('COUNT(id) as total'))
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->whereIn('user_id', $userIDs)
            ->groupBy('user_id')
            ->get();
        foreach($lectureCount as $lecture) $lectureMap[$lecture->user_id] = (int)$lecture->total;
        return $lectureMap;
    }

    /**
     * Returns [user_id => total_active_days_last_week] array map for users.
     * @param array $userIDs
     * @return array
     */
    private function getUserActivityMap(array $userIDs) : array {
        $weekActivity = DB::table('experience')
            ->select(DB::raw('DISTINCT user_id, DATE_FORMAT(created_at, "%Y-%m-%d") as total'))
            ->whereIn('user_id', $userIDs)
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->get();
        $userActivityMap = [];
        foreach($weekActivity as $row){
            if(!array_key_exists($row->user_id, $userActivityMap)){
                $userActivityMap[$row->user_id] = 1;
            }else{
                ++$userActivityMap[$row->user_id];
            }
        }
        return $userActivityMap;
    }

    /**
     * Build Weekly Progress Mails.
     */
    public function handle(){
        $userIDs = [];
        $notReceivedEmptyReportUserIDs = [];
        $receivedEmptyReportUserIDs = [];
        $users = $this->getUsers();

        foreach($users as $user) $userIDs[] = $user->id;
        $experienceMap = $this->getExperienceMap($userIDs);
        $lectureMap = $this->getLectureMap($userIDs);
        $trainerMaps = $this->getTrainerMap($userIDs);
        $userActivityMap = $this->getUserActivityMap($userIDs);

        foreach($users as $user){
            $earnedXP = array_key_exists($user->id, $experienceMap) ? $experienceMap[$user->id] : 0;
            $receivedEmptyWeeklyReport = $user->received_empty_weekly_report;
            if($earnedXP === 0 && $receivedEmptyWeeklyReport == true){
                continue;
            }else if($earnedXP === 0 && $receivedEmptyWeeklyReport == false){
                $receivedEmptyReportUserIDs[] = $user->id;
            }else if($earnedXP > 0 && $receivedEmptyWeeklyReport == true){
                $notReceivedEmptyReportUserIDs[] = $user->id;
            }

            $emailData = [
                'username' => $user->username,
                'earnedXP' => $earnedXP,
                'uuid' => $user->uuid,
                'completedLectures' => array_key_exists($user->id, $lectureMap) ? $lectureMap[$user->id] : 0,
                'completedTrainers' => array_key_exists($user->id, $trainerMaps) ? $trainerMaps[$user->id] : 0,
                'daysActiveInWeek' => array_key_exists($user->id, $userActivityMap) ? $userActivityMap[$user->id] : 0
            ];

            try{
                Mail::to($user->email)->send(new WeeklyProgressReportMail($emailData));
            }catch (\Exception $e){
                DB::table('email_failure')->insert([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'email_type' => 'WEEKLY_PROGRESS_REPORT',
                    'error_message' => $e->getMessage(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        // Update received_empty_weekly_report column.
        UserNotification::whereIn('user_id', $notReceivedEmptyReportUserIDs)
            ->update(['received_empty_weekly_report' => false]);
        UserNotification::whereIn('user_id', $receivedEmptyReportUserIDs)
            ->update(['received_empty_weekly_report' => true]);
    }
}
