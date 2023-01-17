<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartialOrderCancellation extends Mailable
{
    use Queueable, SerializesModels;
    public $po_master;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($po_master)
    {
        $this->po_master = $po_master;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->subject('Partial Order Cancellation')->view('emails.partial-order-cancellation');
    
    }
}
