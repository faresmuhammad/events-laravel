<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Mail\VerificationMail;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail
{
    private Request $request;
    /**
     * Create the event listener.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     * @throws Exception
     */
    public function handle(NewUserRegistered $event): void
    {
        $user = $event->user;
        $uniqueString = bin2hex(random_bytes(20)) . $user->id;
        $verificationLink = "http://127.0.0.1:8000/user/{$user->id}/verify/{$uniqueString}";

//        dd($user,$uniqueString,$verificationLink);
        $user->update([
            'verification_email_string_code' => $uniqueString
        ]);

        Mail::to($user->email)->send(new VerificationMail($verificationLink));
        /*
         * generate a unique string
         * get the user id
         * generate the verification link
         * add the verification route to update the email_verified field
         * send an email
         */
    }
}
