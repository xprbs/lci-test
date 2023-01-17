<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Reset Password Mail',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.index',
        );
    }

    public function attachments()
    {
        return [];
    }



}
