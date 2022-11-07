<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;


class inv_purchase_req_quotation_supplier extends Model
{
    protected $table = 'inv_purchase_req_quotation_supplier';
    protected $primary_key = 'requisition_item_id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$prm_id){
        $item_id =  $this->insertGetId($data);
    }

    function updatedata($condition,$data){
        return $this->where($condition)->update($data);
    }
    function getItem($condition){
       return $this->select('supplier_id')->where($condition)->get();
    }
    function get_Item($condition){
        return $this->select(['inv_supplier.id','inv_supplier.vendor_id','inv_supplier.vendor_name'])
        ->join('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_supplier.supplier_id')
        ->where($condition)->get();
     }
     function get_single_item($condition){
        return $this->select(['inv_purchase_req_quotation_supplier.supplier_quotation_num','inv_purchase_req_quotation_supplier.commited_delivery_date',
                              'inv_purchase_req_quotation_supplier.quotation_date','inv_purchase_req_quotation_supplier.contact_number', 'inv_supplier.id'
                              ,'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_purchase_req_quotation_supplier.freight_charge'])
                    ->join('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_supplier.supplier_id')
                    ->where($condition)->first();
     }

     function get_suppliers($condition){
        return $this->select(['inv_supplier.id','inv_supplier.vendor_id', 'inv_supplier.vendor_name', 'inv_purchase_req_quotation_supplier.commited_delivery_date','inv_purchase_req_quotation_supplier.quotation_id'])
                    ->join('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_supplier.supplier_id')
                    ->where($condition)
                    ->get();
    }

    function get_suppliers_single($condition){
        return $this->select(['*'])
                    ->where($condition)
                    ->first();
    }


    function checkQuotation($condition)
    {
        return $this->select('selected_supplier')
                    ->where($condition)
                    ->pluck('selected_supplier')
                    ->first();
    }
    function inv_purchase_req_quotation_data($condition){
        return $this->select(['inv_purchase_req_quotation.rq_no','inv_purchase_req_quotation_supplier.supplier_quotation_num','inv_purchase_req_quotation_supplier.commited_delivery_date'
        ,'inv_purchase_req_quotation_supplier.quotation_date','inv_purchase_req_quotation_supplier.contact_number','inv_purchase_req_quotation_supplier.supplier_id','inv_purchase_req_quotation_supplier.quotation_id',
        'inv_supplier.vendor_id','inv_supplier.vendor_name'])
        ->join('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','inv_purchase_req_quotation_supplier.quotation_id')
        ->join('inv_supplier','inv_supplier.id','inv_purchase_req_quotation_supplier.supplier_id')
        ->where($condition)
        ->get();


    }
    function get_quotation_all($condition){
     return  $this->select('inv_purchase_req_quotation.*')
        ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_purchase_req_quotation_supplier.quotation_id')
        ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_supplier.supplier_id')
        ->whereNotIn('inv_purchase_req_quotation.quotation_id',function($query) {
            $query->select('inv_final_purchase_order_master.rq_master_id')->from('inv_final_purchase_order_master');
          })
        ->distinct('inv_purchase_req_quotation.quotation_id')
        ->orderby('inv_purchase_req_quotation.quotation_id','desc')
        ->where($condition)
        ->paginate(15);

    }




}
