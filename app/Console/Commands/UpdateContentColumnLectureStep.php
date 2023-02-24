<?php

namespace App\Console\Commands;

use App\Models\Course\LectureStep;
use App\Models\Course\LectureStepBody;
use Illuminate\Console\Command;

class UpdateContentColumnLectureStep extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'update-content:lecture-step';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Populates content column in lecture_step table using lecture_step_body.';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(){
        $lectureStepBodyData = LectureStepBody::orderBy('lecture_step_id', 'ASC')->orderBy('number', 'ASC')->get();
        $lectureStepsMap = [];
        foreach ($lectureStepBodyData as $lectureStepBody) {
            if (!array_key_exists($lectureStepBody['lecture_step_id'], $lectureStepsMap))
                $lectureStepsMap[$lectureStepBody['lecture_step_id']] = [];
            $lectureStepsMap[$lectureStepBody['lecture_step_id']][] = [
                'number' => $lectureStepBody['number'],
                'type' => $lectureStepBody['type'],
                'content' => strpos($lectureStepBody['type'], 'chess') !== false ? json_decode($lectureStepBody['content']) : $lectureStepBody['content']
            ];
        }

        foreach($lectureStepsMap as $lectureStepId => $content){
            LectureStep::where('id', '=', $lectureStepId)->update(['content' => json_encode($content)]);
        }
    }

}
