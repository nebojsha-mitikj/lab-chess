<?php

namespace App\Mail;

use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserFollow extends Mailable
{
    use Queueable, SerializesModels;

    public $profile;

    public $followerUsername;

    public function __construct(User $profile, string $followerUsername){
        $this->profile = $profile;
        $this->followerUsername = $followerUsername;
    }

    public function build(){
        return $this->subject('New labchess follower! 🎉')->view('mails.user-follow');
    }
}
