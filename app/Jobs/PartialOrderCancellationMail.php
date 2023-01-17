<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use PDF;
use App\Models\PurchaseDetails\inv_final_purchase_order_item;
use App\Models\PurchaseDetails\inv_final_purchase_order_rel;
use App\Models\PurchaseDetails\inv_final_purchase_order_master;
use App\Mail\PartialOrderCancellation;

class PartialOrderCancellationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $PartialcancelDatas;
    public $timeout = 7200; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($PartialcancelDatas)
    {
        $this->partial_cancellation_data = $PartialcancelDatas;
        //$this->inv_final_purchase_order_item = new inv_final_purchase_order_item;
    }
   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!empty($this->PartialcancelDatas))
        {
            $data = $this->PartialcancelDatas;
            Log::debug(json_encode($this->PartialcancelDatas));
            $pdf = PDF::loadView('pages.purchase-details.final-purchase.partialcancel-pdf', $data);
            $pdf->set_paper('A4', 'landscape');
            $po_master = $this->inv_final_purchase_order_master->find_po_data(['inv_final_purchase_order_master.id' => $data['final_purchase']['po_id']]);
            $message = new PartialOrderCancellation($po_master);
            //$message->attachData($pdf->output(), "partial-order-cancellation-report.pdf");
            Mail::to('shilma33@gmail.com')->send($message);
        }
    }
}
