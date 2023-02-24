<?php

namespace App\Http\Controllers\ApiControllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ContactApiController extends Controller
{
    /**
     * Contact Support.
     * @param ContactRequest $request
     * @return JsonResponse
     */
    public function contact(ContactRequest $request) : JsonResponse {
        $subject = $request->subject;
        $file = $request->file('attachment');
        $email = $request->email;
        $html = "<b>Email Address:</b> ".$request->email ."<br><br>";
        $html .= "<b>Subject:</b> ".$request->subject ."<br><br>";
        $html .= "<b>Message:</b><br>";
        $html .= $request->message;

        Mail::send([], [], function($message) use ($html, $subject, $file, $email) {
            $message->to(env('CONTACT_EMAIL'))
                ->from(env('CONTACT_EMAIL'))
                ->subject($subject)
                ->replyTo($email)
                ->setBody($html, 'text/html');

            if($file != null){
                $message->attach($file->getRealPath(), [
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType()
                ]);
            }
        });
        return response()->json(['message' => 'Thank you for contacting us! We\'ll get back to you soon.']);
    }
}
