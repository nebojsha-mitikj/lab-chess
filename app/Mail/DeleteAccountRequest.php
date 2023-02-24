<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteAccountRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, string $link){
        $this->email = $email;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Labchess Delete Account Request Confirmation')->view('mails.delete-account');
    }
}
