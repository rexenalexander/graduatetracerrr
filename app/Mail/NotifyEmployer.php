<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyEmployer extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $body;
    public $footer;

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
    }

    /**
     * Build the message.
     *
     * @return $this    
    */ 
    public function build()
    {
    return $this->subject('Employer Feedback Form!')
        ->view('emails.notifyemployer')
        ->with([
            'data' => $this->data,
            'body' => $this->body,
            'footer' => $this->footer,
        ]);
    }
}