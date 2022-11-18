<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class inv_mrd extends Model
{
    protected $table = 'inv_mrd';
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
        return $this->select('inv_mrd.id as mrd_id','inv_mrd.mrd_number','inv_mrd.mrd_date','inv_miq.miq_number','inv_miq.created_by',
                            'inv_supplier.vendor_name','inv_supplier.vendor_id','user.f_name','user.l_name')
                    ->leftjoin('inv_miq','inv_miq.id','=','inv_mrd.miq_id')
                    ->leftjoin('user','user.user_id','=','inv_mrd.created_by')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_miq.invoice_master_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')

                    ->where($condition)
                    ->where('inv_mrd.status','=',1)
                    ->orderBy('inv_mrd.id','DESC')
                    ->paginate(15);
    }

    function find_mrd_data($condition)
    {
        return $this->select(['inv_mrd.mrd_number','inv_mrd.id','inv_mrd.created_at','inv_mrd.created_by','inv_miq.id as miq_id','inv_miq.miq_number','user.f_name','user.l_name','inv_mrd.mrd_date',
        'inv_supplier.vendor_id','inv_supplier.vendor_name'])
                    ->leftjoin('inv_miq','inv_miq.id','=','inv_mrd.miq_id')
                    ->join('user','user.user_id','=','inv_miq.created_by')
                    ->join('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_miq.invoice_master_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->where($condition)
                    ->first();
    }

    function deleteData($condition)
    {
         DB::table('inv_mrd_item') 
            ->join('inv_mrd_item_rel','inv_mrd_item_rel.item','=','inv_mrd_item.id')
            ->where(['inv_mrd_item_rel.master'=>$condition['id']])
            ->delete();
        DB::table('inv_mrd_item_rel') 
            ->where(['inv_mrd_item_rel.master'=>$condition['id']])
            ->delete();
     return  $this->where($condition)->delete();
    }

}
