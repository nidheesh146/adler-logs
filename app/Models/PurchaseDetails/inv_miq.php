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
                    ->orderBy('inv_supplier_invoice_master.id','DESC')
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
}


