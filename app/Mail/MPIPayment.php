<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;
use DB;
class MPIPayment extends Mailable
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
        if(!empty($this->mailData))
        {
          $multiple_pis  = DB::table('fgs_multiple_pi_item_rel')->select('fgs_multiple_pi_item.pi_id')
                      ->leftJoin('fgs_multiple_pi_item','fgs_multiple_pi_item.id','fgs_multiple_pi_item_rel.item')
                      ->where('fgs_multiple_pi_item_rel.master','=',$this->mailData->mpi_id)
                      ->distinct('fgs_multiple_pi_item.id')
                      ->get();
          $item_count = count($multiple_pis);
          $i = 1;
          foreach($multiple_pis as $multiple_pi)
          {
            $data['pi'] = DB::table('fgs_pi')->select('fgs_pi.*','fgs_oef.oef_number','fgs_oef.oef_date','order_fulfil.order_fulfil_type','fgs_oef.order_number','fgs_oef.order_date','fgs_grs.grs_number','fgs_grs.grs_date',
          'transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.pan_number','customer_supplier.gst_number','customer_supplier.payment_terms',
          'customer_supplier.shipping_address','customer_supplier.billing_address','customer_supplier.sales_type','customer_supplier.contact_person','fgs_oef.remarks as oef_remarks',
          'customer_supplier.sales_type','customer_supplier.city','customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email','fgs_product_category.category_name',
          'currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3')
              ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.master','=','fgs_pi.id')
              ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
              ->leftJoin('fgs_grs','fgs_grs.id','fgs_pi_item.grs_id')
              ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
              ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
              ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
              ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
              ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
              ->leftJoin('zone','zone.id','=','customer_supplier.zone')
              ->leftJoin('state','state.state_id','=','customer_supplier.state')
              ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
              ->where('fgs_pi.id','=', $multiple_pi->pi_id)
              ->where('fgs_grs.status','=',1)
              ->first();
              $pi_number = $data['pi']->pi_number;
           $data['items'] =DB::table('fgs_pi_item_rel')->select('fgs_grs.grs_number','fgs_grs.grs_date','fgs_item_master.sku_code','fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_number','fgs_oef.order_date','fgs_item_master.hsn_code','fgs_item_master.discription',
           'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity as quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code',
           'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_pi_item.remaining_qty_after_cancel')
                           ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                           ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                           ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                           ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                           ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                           ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_grs.oef_id')
                           ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                           ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_pi_item.mrn_item_id')
                           ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                           ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                           ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_grs_item.product_id')
                           ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                           ->where('fgs_grs.status','=',1)
                           ->where('fgs_pi.status','=',1)
                           ->where('fgs_pi_item.status','=',1)
                           ->where('fgs_pi_item_rel.master', '=' ,$multiple_pi->pi_id)
                           ->orderBy('fgs_grs_item.id','DESC')
                           //->distinct('fgs_grs_item.id')
                           ->get();
            $pdf = PDF::loadView('pages.FGS.PI.pi-attachment', $data);
            $pdf->set_paper('A4', 'landscape');
            $this->attachData($pdf->output(), "PI".$pi_number."-report.pdf");
            $i++;
            
          }
            $mailData = $this->mailData;
            $customer_id = $mailData->customer_id;
            $supp = DB::table('customer_supplier')
                    ->select(['firm_name','contact_person','billing_address','shipping_address','email','contact_number','zm_name','zm_email','rm_name','rm_email',
                    'asm_name','asm_email','email','me_email'])
                    ->where(['id'=>$customer_id])->first();
            $cc[] = 'binil.m@adler-healthcare.com';
            $cc[] = 'sudhakar.salvi@adler-healthcare.com';
            if($supp->asm_email!=NULL)
            $cc[] = $supp->asm_email;
            if($supp->zm_email!=NULL)
            $cc[] = $supp->zm_email;
            if($supp->rm_email!=NULL)
            $cc[] = $supp->rm_email;
            if($supp->me_email!=NULL)
            $cc[] = $supp->me_email;
             $this->view('emails.mpi_payment',compact('mailData'))
                ->cc($cc)
                ->bcc('chandrashekhar.purohit@adler-healthcare.com', 'chandrashekhar')
                ->subject($this->mailData->subject);
            for($i=1;$i<=$item_count;$i++)
            {
               //$this->attachData($pdf.$i->output(), "PI".$i."-report.pdf");
            }
            return $this;
                        //  ->with(['mailMessage' => $this->mailData]);;
    }
  }
}
