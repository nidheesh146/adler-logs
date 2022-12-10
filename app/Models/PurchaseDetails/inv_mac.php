<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class inv_mac extends Model
{
    protected $table = 'inv_mac';
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
        return $this->select('inv_mac.id','inv_mac.mac_number','inv_mac.mac_date','inv_miq.miq_number','inv_miq.created_by',
                            'inv_supplier.vendor_name','inv_supplier.vendor_id','user.f_name','user.l_name')
                    ->leftjoin('inv_miq','inv_miq.id','=','inv_mac.miq_id')
                    ->leftjoin('user','user.user_id','=','inv_mac.created_by')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_miq.invoice_master_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')

                    ->where($condition)
                    ->where('inv_mac.status','=',1)
                    ->orderBy('inv_mac.id','DESC')
                    ->paginate(15);
    }

    function find_mac_data($condition)
    {
        return $this->select(['inv_mac.mac_number','inv_mac.id','inv_mac.created_at','inv_mac.created_by','inv_miq.id as miq_id','inv_miq.miq_number','user.f_name','user.l_name','inv_mac.mac_date',
        'inv_supplier.vendor_id','inv_supplier.vendor_name'])
                    ->leftjoin('inv_miq','inv_miq.id','=','inv_mac.miq_id')
                    ->join('user','user.user_id','=','inv_miq.created_by')
                    ->join('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_miq.invoice_master_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->where($condition)
                    ->first();
    }

    function deleteData($condition)
    {
         DB::table('inv_mac_item') 
            ->join('inv_mac_item_rel','inv_mac_item_rel.item','=','inv_mac_item.id')
            ->where(['inv_mac_item_rel.master'=>$condition['id']])
            ->delete();
        DB::table('inv_mac_item_rel') 
            ->where(['inv_mac_item_rel.master'=>$condition['id']])
            ->delete();
     return  $this->where($condition)->delete();
    }

    function find_mac_not_in_mrr($condition)
    {
        return $this->select(['inv_mac.mac_number as text','inv_mac.id'])
        ->where($condition)
        ->whereNotIn('inv_mac.id',function($query) {

            $query->select('inv_mrr.mac_id')->from('inv_mrr');
        
        })->where('inv_mac.status','=',1)
        ->where('inv_mac.mac_number', 'LIKE', 'MAC%')
        ->get();
    }
    function find_woa_not_in_mrr($condition)
    {
        return $this->select(['inv_mac.mac_number as text','inv_mac.id'])
        ->where($condition)
        ->whereNotIn('inv_mac.id',function($query) {

            $query->select('inv_mrr.mac_id')->from('inv_mrr');
        
        })->where('inv_mac.status','=',1)
        ->where('inv_mac.mac_number', 'LIKE', 'WOA%')
        ->get();
    }
}
