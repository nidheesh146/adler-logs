<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;
use App\Mail\quotation;


use App\Http\Controllers\Controller;




class inv_purchase_req_quotation extends Model
{
    protected $table = 'inv_purchase_req_quotation';
    protected $primary_key = 'quotation_id';
    protected $guarded = [];
    public $timestamps = false;
   
    protected static function booted()
    {
        static::addGlobalScope('inv_purchase_req_quotation.status', function (Builder $builder) {
            $builder->where('inv_purchase_req_quotation.status', '!=', 2);
        });
    }

    function insert_data($data,$request){
        $quotation_id = $this->insertGetId($data);
        if($quotation_id){
            foreach($request->Supplier as $supplier_id){
                DB::table('inv_purchase_req_quotation_supplier')->insert(['supplier_id'=>$supplier_id,'quotation_id'=>$quotation_id]);
                foreach($request->purchase_requisition_item as $purchase_requisition_item){
                    $item_id = DB::table('inv_purchase_req_item')->where('inv_purchase_req_item.requisition_item_id','=',$purchase_requisition_item)->pluck('Item_code')->first();
                    $fixed = DB::table('inv_supplier_itemrate')
                                ->select('inv_supplier_itemrate.*')
                                ->where('inv_supplier_itemrate.supplier_id','=',$supplier_id)
                                ->where('inv_supplier_itemrate.item_id','=',$item_id)->first();
                    $now = date('Y-m-d');
                    if($fixed && $fixed->rate_expiry_startdate<=$now && $fixed->rate_expiry_enddate>=$now)
                    {
                        $delivery_within = $fixed->delivery_within;
                        $qty = DB::table('inv_purchase_req_item')->where('inv_purchase_req_item.requisition_item_id','=',$purchase_requisition_item)->pluck('actual_order_qty')->first();
                        DB::table('inv_purchase_req_quotation_item_supp_rel')->insert(['quotation_id'=>$quotation_id,'item_id'=>$purchase_requisition_item,'supplier_id'=>$supplier_id,'rate'=>$fixed->rate,'gst'=>$fixed->gst,'discount'=>$fixed->discount,'currency'=>$fixed->currency,'quantity'=>$qty,'selected_item'=>1,'committed_delivery_date'=>date('Y-m-d', strtotime("+".$delivery_within." days"))]);  
                    }else {
                        DB::table('inv_purchase_req_quotation_item_supp_rel')->insert(['quotation_id'=>$quotation_id,'item_id'=>$purchase_requisition_item,'supplier_id'=>$supplier_id]);
                    }
                }
                    // cron job
                    $mailData = new \stdClass();
                    $mailData->module = 'add_quotation';
                    $mailData->subject = "Adler";
                    $mailData->to = ['shilma33@gmail.com','komal.murali@gmail.com'];
                    $supp = DB::table('inv_supplier')->select(['vendor_id','vendor_name','email'])->where(['id'=>$supplier_id])->first();
                    //$mailData->to =   json_decode($supp->email,true);
                    $mailData->vendor_id = $supp->vendor_id;
                    $mailData->vendor_name = $supp->vendor_name;

                    if(!empty($mailData->to) && count($mailData->to) > 0 ){
                    $mailData->url = url('request-for-quotation/'.(new Controller)->encrypt($quotation_id).'/'.(new Controller)->encrypt($supplier_id));
                    $job = (new \App\Jobs\EmailJobs($mailData))
                    ->delay(
                        now()
                            ->addSeconds(3)
                    );
                    dispatch($job);
                }
            }
        }
        return true;
    }

    function insert_fixed_item_data($data,$request){
        $quotation_id = $this->insertGetId($data);
        if($quotation_id){
           
                DB::table('inv_purchase_req_quotation_supplier')->insert(['supplier_id'=>$request->Supplier,'quotation_id'=>$quotation_id]);
                foreach($request->purchase_requisition_item as $purchase_requisition_item)
                {
                    $item_id = DB::table('inv_purchase_req_item')->where('inv_purchase_req_item.requisition_item_id','=',$purchase_requisition_item)->pluck('Item_code')->first();
                    $fixed = DB::table('inv_supplier_itemrate')
                                ->select('inv_supplier_itemrate.*')
                                ->where('inv_supplier_itemrate.supplier_id','=',$request->Supplier)
                                ->where('inv_supplier_itemrate.item_id','=',$item_id)->first();
                    $now = date('Y-m-d');
                    if($fixed && $fixed->rate_expiry_startdate<=$now && $fixed->rate_expiry_enddate>=$now)
                    {
                        $delivery_within = $fixed->delivery_within;
                        $qty = DB::table('inv_purchase_req_item')->where('inv_purchase_req_item.requisition_item_id','=',$purchase_requisition_item)->pluck('actual_order_qty')->first();
                        DB::table('inv_purchase_req_quotation_item_supp_rel')->insert(['quotation_id'=>$quotation_id,'item_id'=>$purchase_requisition_item,'supplier_id'=>$request->Supplier,'rate'=>$fixed->rate,'gst'=>$fixed->gst,'discount'=>$fixed->discount,'currency'=>$fixed->currency,'quantity'=>$qty,'selected_item'=>1,'committed_delivery_date'=>date('Y-m-d', strtotime("+".$delivery_within." days"))]);  
                    }else {
                        DB::table('inv_purchase_req_quotation_item_supp_rel')->insert(['quotation_id'=>$quotation_id,'item_id'=>$purchase_requisition_item,'supplier_id'=>$request->Supplier]);
                    }
                }
                    // cron job
                    $mailData = new \stdClass();
                    $mailData->module = 'add_quotation';
                    $mailData->subject = "Adler";
                    $mailData->to = ['shilma33@gmail.com','komal.murali@gmail.com'];
                    $supp = DB::table('inv_supplier')->select(['vendor_id','vendor_name','email'])->where(['id'=>$request->Supplier])->first();
                    //$mailData->to =   json_decode($supp->email,true);
                    $mailData->vendor_id = $supp->vendor_id;
                    $mailData->vendor_name = $supp->vendor_name;

                    if(!empty($mailData->to) && count($mailData->to) > 0 )
                    {
                        $mailData->url = url('request-for-quotation/'.(new Controller)->encrypt($quotation_id).'/'.(new Controller)->encrypt($request->Supplier));
                        $job = (new \App\Jobs\EmailJobs($mailData))
                        ->delay(
                            now()
                                ->addSeconds(3)
                        );
                        dispatch($job);
                    }
                // }
        }
        return true;
    }



    function get_count(){
        return $this->get()->count();
    }
    function updatedata($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_quotation($condition){
        return $this->select('inv_purchase_req_quotation.*','inv_purchase_req_master.PR_SR')
                    ->rightjoin('inv_purchase_req_quotation_item_supp_rel','inv_purchase_req_quotation_item_supp_rel.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                    ->rightjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_quotation_item_supp_rel.item_id')
                    ->rightjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                    ->rightjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    //->join('inv_purchase_req_quotation_supplier','inv_purchase_req_quotation_supplier.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                    ->where($condition)
                    //->where('inv_purchase_req_master.PR_SR','=','SR')
                    ->distinct('inv_purchase_req_quotation.quotation_id')
                    ->orderby('inv_purchase_req_quotation.quotation_id','desc')
                    ->paginate(15);

    }
    function get_quotation_single($condition){

        return $this->select('*')
                    ->where($condition)
                    ->first();

    }

    function get_quotation_number($condition){
        return $this->select('rq_no')
                    ->where($condition)
                    ->pluck('rq_no')
                    ->first();
    }

    function get_master_filter($condition1){
       $query =  $this->select(['inv_purchase_req_quotation.quotation_id as id','inv_purchase_req_quotation.rq_no as text'])
                  //  ->join('inv_purchase_req_quotation_supplier','inv_purchase_req_quotation_supplier.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                    ->whereNotIn('inv_purchase_req_quotation.quotation_id',function($query) {
                        $query->select('inv_final_purchase_order_master.rq_master_id')->from('inv_final_purchase_order_master');
                      })
                    ->where($condition1)
                    ->get();
                    return $query;

    //  return    DB::table('inv_final_purchase_order_master')
    //     ->select(['inv_purchase_req_quotation.quotation_id as id','inv_purchase_req_quotation.rq_no as text'])
    //     ->join('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
    //     ->where($condition2)
    //     ->union($query)
    //     ->get();

    }
    function get_rq_final_purchase($condition1){
        $query =  $this->select('inv_purchase_req_quotation.*')
                    ->leftjoin('inv_purchase_req_quotation_supplier','inv_purchase_req_quotation_supplier.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_supplier.supplier_id')
                    ->leftjoin('inv_purchase_req_quotation_item_supp_rel','inv_purchase_req_quotation_item_supp_rel.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                    ->distinct('inv_purchase_req_quotation.quotation_id')
                    ->orderby('inv_purchase_req_quotation.quotation_id','desc')
                   //  ->join('inv_purchase_req_quotation_supplier','inv_purchase_req_quotation_supplier.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                     ->whereNotIn('inv_purchase_req_quotation.quotation_id',function($query) {
                         $query->select('inv_final_purchase_order_master.rq_master_id')->from('inv_final_purchase_order_master');
                       })
                     ->where($condition1)
                     ->where('inv_purchase_req_quotation_item_supp_rel.selected_item','=',1)
                     ->paginate(15);
                     return $query;
    }

    function get_rq_nos()
    {
        return $this->select('quotation_id','rq_no')->get();
    }


}
