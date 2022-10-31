<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
 

use App\Mail\quotation;


use Mail;



class EmailJobs implements ShouldQueue
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
    if(!empty($this->mailData)){
        $mailData = $this->mailData;

      //  Log::debug(json_encode($this->mailData));
        if($this->mailData->module == 'add_quotation'){
            Mail::to(   $this->mailData->to)->send(new quotation($mailData));
        }
    }
    }
}
