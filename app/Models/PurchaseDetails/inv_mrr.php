<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class inv_mrr extends Model
{
    protected $table = 'inv_mrr';
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
        return $this->select('inv_mrr.id','inv_mrr.mrr_number','inv_mrr.mrr_date','inv_mac.mac_number','inv_mrr.created_by',
                            'inv_supplier.vendor_name','inv_supplier.vendor_id','user.f_name','user.l_name')
                    ->leftjoin('inv_mac','inv_mac.id','=','inv_mrr.mac_id')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_mac.invoice_id')
                    ->leftjoin('inv_miq','inv_miq.invoice_master_id','=','inv_supplier_invoice_master.id')
                    ->leftjoin('user','user.user_id','=','inv_mac.created_by')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')

                    ->where($condition)
                    ->where('inv_mrr.status','=',1)
                    ->orderBy('inv_mrr.id','DESC')
                    ->paginate(15);
    }
    function find_mrr_data($condition)
    {
        return $this->select(['inv_mrr.mrr_number','inv_mrr.id','inv_mrr.created_at','inv_mrr.mrr_date','inv_mrr.created_by','inv_mac.id as mac_id','inv_mac.mac_number','user.f_name','user.l_name','inv_mac.mac_date','inv_supplier_invoice_master.id as invoiceId',
        'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_supplier.address','inv_supplier.contact_number','inv_miq.miq_number','inv_miq.miq_date','inv_mrd.mrd_number','inv_mrd.mrd_date','inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date'])
                    
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_mrr.invoice_id')
                    ->leftjoin('inv_mac','inv_mac.id','=','inv_mrr.invoice_id')
                    ->leftjoin('user','user.user_id','=','inv_mrr.created_by')
                    ->leftjoin('inv_miq','inv_miq.invoice_master_id','=','inv_supplier_invoice_master.id')
                    ->leftjoin('inv_mrd','inv_mrd.invoice_id','=','inv_supplier_invoice_master.id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->where('inv_mrr.status','=',1)
                    ->where($condition)
                    ->first();
    }
    function deleteData($condition)
    {
         DB::table('inv_mrr_item') 
            ->join('inv_mrr_item_rel','inv_mrr_item_rel.item','=','inv_mrr_item.id')
            ->where(['inv_mrr_item_rel.master'=>$condition['id']])
            ->delete();
        DB::table('inv_mrr_item_rel') 
            ->where(['inv_mrr_item_rel.master'=>$condition['id']])
            ->delete();
     return  $this->where($condition)->delete();
    }
}
