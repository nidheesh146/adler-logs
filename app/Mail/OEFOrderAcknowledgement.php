<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use DB;

class OEFOrderAcknowledgement extends Mailable
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
        if (!empty($this->mailData)) {
            $mailData = $this->mailData;
            $customer_id = $mailData->customer_id;
            $supp = DB::table('customer_supplier')
                ->select([
                    'firm_name', 'contact_person', 'billing_address', 'shipping_address', 'email', 'contact_number', 
                    'zm_name', 'zm_email', 'rm_name', 'rm_email', 'asm_name', 'asm_email', 'email', 'me_email'
                ])
                ->where(['id' => $customer_id])->first();

            $cc = [];
            if ($supp->asm_email != NULL)
                $cc[] = $supp->asm_email;
            if ($supp->zm_email != NULL)
                $cc[] = $supp->zm_email;
            if ($supp->rm_email != NULL)
                $cc[] = $supp->rm_email;
            if ($supp->me_email != NULL)
                $cc[] = $supp->me_email;

            // Added Chandrashekhar to CC
            $cc[] = 'chandrashekhar.purohit@adler-healthcare.com'; 

            return $this->view('emails.oef_order_acknowledgement', compact('mailData'))
                ->from('noreply.adlerhealthcare@gmail.com', 'Adler Healthcare')  // From address
                ->replyTo('chandrashekhar.purohit@adler-healthcare.com', 'Chandrashekhar')  // Reply-To address
                ->cc($cc)
                ->subject($this->mailData->subject);
        }
    }
}
