<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\MPIPayment;
use Mail;
use PDF;
use DB;
class MPIPaymentMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mailData;
    public $timeout = 7200; // 2 hours

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->mailData)) {
            $mailData = $this->mailData;
            
            if ($this->mailData->module == 'Material Ready for Dispatch') {  
                Mail::to($this->mailData->to)
                    ->cc('chandrashekhar.purohit@adler-healthcare.com') // Adding CC recipient
                    ->send(new MPIPayment($mailData));
            }
        }
    }
    
}
