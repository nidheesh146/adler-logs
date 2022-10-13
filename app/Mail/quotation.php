<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class quotation extends Mailable
{
    use Queueable, SerializesModels;
    protected $mailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(!empty($this->mailData)){
            $mailData = $this->mailData;
            return $this->view('emails.add_quotation',compact('mailData'))
          // ->text('email.laraemail_plain')
          // ->from($from, $name)
          // ->cc($address, $name)
          // ->bcc($cc, $name)
          // ->replyTo($from, $name)
             ->subject($this->mailData->subject);
          //  ->with(['mailMessage' => $this->mailData]);;
    }
  }
}
