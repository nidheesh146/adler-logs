<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class inv_miq extends Model
{
    protected $table = 'inv_miq';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_all_data($condition){
        return $this->select('inv_miq.id as miq_id','inv_miq.miq_number','inv_miq.miq_date','inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.created_by',
                            'inv_supplier.vendor_name','inv_supplier.vendor_id','user.f_name','user.l_name')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_miq.invoice_master_id')
                    ->leftjoin('user','user.user_id','inv_miq.created_by')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')

                    ->where($condition)
                    ->where('inv_miq.status','=',1)
                    ->orderBy('inv_miq.id','DESC')
                    ->paginate(15);
    }
    function get_data($condition)
    {
        return $this->select('inv_miq.id as miq_id','inv_miq.miq_number','inv_miq.miq_date','inv_supplier_invoice_master.invoice_number','inv_miq.invoice_master_id',
                            'inv_miq.created_by','inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_name','inv_supplier.vendor_id','user.f_name','user.l_name')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_miq.invoice_master_id')
                    ->leftjoin('user','user.user_id','inv_miq.created_by')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->where($condition)
                    ->where('inv_miq.status','=',1)
                    ->first();
    }

    function find_miq_num($condition)
    {
        return $this->select(['inv_miq.miq_number as text','inv_miq.id'])
        ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_miq.invoice_master_id')
        ->where($condition)
        ->whereNotIn('inv_miq.id',function($query) {

            $query->select('inv_mac.miq_id')->from('inv_mac');
        
        })->where('inv_miq.status','=',1)
        ->get();
    }

    function find_miq_data($condition)
    {
        return $this->select(['inv_miq.miq_number','inv_miq.id','inv_miq.created_at','user.f_name','user.l_name','inv_miq.miq_date',
        'inv_supplier.vendor_id','inv_supplier.vendor_name'])
                    ->join('user','user.user_id','=','inv_miq.created_by')
                    ->join('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_miq.invoice_master_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->where($condition)
                    ->first();
    }
    function find_miq_num_for_mrd($condition)
    {
        return $this->select(['inv_miq.miq_number as text','inv_miq.id'])
        ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_miq.invoice_master_id')
        ->where($condition)
        ->whereNotIn('inv_miq.id',function($query) {

            $query->select('inv_mrd.miq_id')->from('inv_mrd');
        
        })->where('inv_miq.status','=',1)
        ->get();
    }

    function deleteData($condition)
    {
         DB::table('inv_miq_item') 
            ->join('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
            ->where(['inv_miq_item_rel.master'=>$condition['id']])
            ->delete();
        DB::table('inv_miq_item_rel')
            ->where(['inv_miq_item_rel.master'=>$condition['id']])
            ->delete();
     return  $this->where($condition)->delete();
    }

     


     function get_all_datas($condition){
       return $this->select('inv_miq.id as miq_id','inv_miq.miq_number','inv_miq.miq_date','inv_supplier_invoice_master.invoice_number','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_item_type.type_name','user.f_name','user.l_name','inv_miq_item.*','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id','inv_supplier.vendor_name','inv_miq_item.value_inr','currency_exchange_rate.currency_code',
                    'inv_miq_item.conversion_rate')
                    ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.master','=','inv_miq.id')
                    ->leftjoin('inv_miq_item','inv_miq_item.id','=','inv_miq_item_rel.item')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_miq.invoice_master_id')
                    ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.master','inv_supplier_invoice_master.id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','inv_supplier_invoice_rel.item')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    //->leftjoin('inv_mac','inv_mac.invoice_id','=','inv_supplier_invoice_master.id')
                   // ->leftjoin('inv_mac_item_rel','inv_mac_item_rel.master','=','inv_mac.id')
                    //->leftjoin('inv_mac_item','inv_mac_item.id','=','inv_mac_item_rel.item')
                    ->leftjoin('user','user.user_id','inv_miq.created_by')
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
                
                     ->whereNotIn('inv_miq_item.invoice_item_id',function($query) {
                      $query->select('inv_mac_item.invoice_item_id')->from('inv_mac_item');
                
                    })

                    ->where($condition)
                    ->where('inv_miq.status','=',1)
                    ->orderBy('inv_miq.id','DESC')
                    ->paginate(15);
    }
}


