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
                              ,'inv_supplier.vendor_id','inv_supplier.vendor_name'])
                    ->join('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_supplier.supplier_id')
                    ->where($condition)->first();
     }



}
