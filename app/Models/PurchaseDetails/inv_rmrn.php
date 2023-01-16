<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class inv_rmrn extends Model
{
    protected $table = 'inv_rmrn';
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
        return $this->select('inv_rmrn.id as rmrn_id','inv_rmrn.rmrn_number','inv_rmrn.rmrn_date','inv_rmrn.created_by','inv_miq.created_by',
                            'inv_supplier.vendor_name','inv_supplier.vendor_id','user.f_name','user.l_name','inv_mrd.mrd_number')
                    ->leftjoin('inv_mrd','inv_mrd.id','=','inv_rmrn.mrd_id')
                    ->leftjoin('inv_miq','inv_miq.id','=','inv_mrd.miq_id')
                    ->leftjoin('user','user.user_id','=','inv_mrd.created_by')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_miq.invoice_master_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')

                    ->where($condition)
                    ->where('inv_rmrn.status','=',1)
                    ->orderBy('inv_rmrn.id','DESC')
                    ->paginate(15);
    }

    function find_rmrn_data($condition)
    {
        return $this->select(['inv_rmrn.rmrn_number','inv_rmrn.id','inv_rmrn.created_at','inv_rmrn.rmrn_date','inv_rmrn.created_by','inv_mrd.id as mrd_id','inv_mrd.mrd_number','user.f_name','user.l_name','inv_mrd.mrd_date',
        'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_supplier.address','inv_supplier.contact_number','inv_supplier_invoice_master.type'])
                    ->leftjoin('inv_mrd','inv_mrd.id','=','inv_rmrn.mrd_id')
                    ->leftjoin('inv_miq','inv_miq.id','=','inv_mrd.miq_id')
                    ->join('user','user.user_id','=','inv_rmrn.created_by')
                    ->join('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_miq.invoice_master_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->where($condition)
                    ->first();
    }

    function deleteData($condition)
    {
         DB::table('inv_rmrn_item') 
            ->join('inv_rmrn_item_rel','inv_rmrn_item_rel.item','=','inv_rmrn_item.id')
            ->where(['inv_rmrn_item_rel.master'=>$condition['id']])
            ->delete();
        DB::table('inv_rmrn_item_rel') 
            ->where(['inv_rmrn_item_rel.master'=>$condition['id']])
            ->delete();
     return  $this->where($condition)->delete();
    }
    function find_mrd_not_in_rmrn($condition)
    {
        return $this->select(['inv_mrd.mrd_number as text','inv_mrd.id'])
        //->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_miq.invoice_master_id')
        ->where($condition)
        ->whereNotIn('inv_mrd.id',function($query) {

            $query->select('inv_rmrn.mrd_id')->from('inv_rmrn');
        
        })->where('inv_mrd.status','=',1)
        ->get();
    }

}
