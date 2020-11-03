<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data = array();

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //,compact(array("data"=>$this->data))
        //return $this->subject($this->data['subject'])->view('mails/reg');
        return $this->subject($this->data['subject'])->view('mails/' . $this->data['template']);
    }
}
