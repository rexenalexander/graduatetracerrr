<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $body;
    public $footer;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param string $data
     */
    public function __construct($data)
    {
        $this->data = $data->name ?? '';
        $this->body = $data->message_body ?? '';
        $this->footer = $data->message_footer ?? '';
        $this->password = $data->password ?? '';
    }

    /**
     * Build the message.
     *
     * @return $this    
    */ 
    public function build()
    {
    return $this->subject('Graduate Tracer Study Employment Tracker Survey!')
        ->view('emails.notifyemail')
        ->with([
            'data' => $this->data,
            'body' => $this->body,
            'footer' => $this->footer,
            'password' => $this->password,
        ]);
    }
}