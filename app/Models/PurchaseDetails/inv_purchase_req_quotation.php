<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;

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
        $quotation_id =  $this->insertGetId($data);
        if($quotation_id){
            foreach($request->Supplier as $supplier_id){
                DB::table('inv_purchase_req_quotation_supplier')->insert(['supplier_id'=>$supplier_id,'quotation_id'=>$quotation_id]);
                foreach($request->purchase_requisition_item as $purchase_requisition_item){
                    DB::table('inv_purchase_req_quotation_item_supp_rel')->insert(['quotation_id'=>$quotation_id,'item_id'=>$purchase_requisition_item,'supplier_id'=>$supplier_id]);
                }
            }
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

        return $this->select('inv_purchase_req_quotation.*')
                    //->join('inv_purchase_req_quotation_supplier','inv_purchase_req_quotation_supplier.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                    ->where($condition)
                    ->orderby('quotation_id','desc')
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

    function get_master_filter($condition1,$condition2){
       $query =  $this->select(['inv_purchase_req_quotation.quotation_id as id','inv_purchase_req_quotation.rq_no as text'])
                    ->join('inv_purchase_req_quotation_supplier','inv_purchase_req_quotation_supplier.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                    ->where($condition1);
                   // ->get();

     return    DB::table('inv_final_purchase_order_master')
        ->select(['inv_purchase_req_quotation.quotation_id as id','inv_purchase_req_quotation.rq_no as text'])
        ->join('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
        ->where($condition2)
        ->union($query)
        ->get();

    }

    function get_rq_nos()
    {
        return $this->select('quotation_id','rq_no')->get();
    }


}
