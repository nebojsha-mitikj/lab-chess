<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyProgressReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $earnedXP;
    public $completedLectures;
    public $completedTrainers;
    public $daysActiveInWeek;
    public $username;
    public $uuid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data){
        $this->earnedXP = $data['earnedXP'];
        $this->completedLectures = $data['completedLectures'];
        $this->completedTrainers = $data['completedTrainers'];
        $this->daysActiveInWeek = $data['daysActiveInWeek'];
        $this->username = $data['username'];
        $this->uuid = $data['uuid'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Weekly labchess progress report 📈♟️')->view('mails.weekly-progress-report');
    }
}
