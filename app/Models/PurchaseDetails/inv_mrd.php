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
        return $this->select('inv_mrd.id as mrd_id','inv_mrd.mrd_number','inv_mrd.mrd_date','inv_supplier_invoice_master.invoice_number','inv_mrd.created_by',
                            'inv_supplier.vendor_name','inv_supplier.vendor_id','user.f_name','user.l_name')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_mrd.invoice_id')
                    ->leftjoin('user','user.user_id','=','inv_mrd.created_by')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')

                    ->where($condition)
                    ->where('inv_mrd.status','=',1)
                    ->orderBy('inv_mrd.id','DESC')
                    ->paginate(15);
    }

    function find_mrd_data($condition)
    {
        return $this->select(['inv_mrd.mrd_number','inv_mrd.id','inv_mrd.created_at','inv_mrd.created_by','user.f_name','user.l_name','inv_mrd.mrd_date',
        'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.id as invoice_id'])
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_mrd.invoice_id')
                    ->leftjoin('inv_miq','inv_miq.invoice_master_id','=','inv_supplier_invoice_master.id')
                    ->leftjoin('user','user.user_id','=','inv_miq.created_by')
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
    // function find_mrd_not_in_rmrn($condition)
    // {
    //     return $this->select(['inv_mrd.mrd_number as text','inv_mrd.id'])
    //     ->where($condition)
    //     ->whereNotIn('inv_mrd.id',function($query) {

    //         $query->select('inv_rmrn.mrd_id')->from('inv_rmrn');
        
    //     })->where('inv_mrd.status','=',1)
    //     ->get();
    // }
    public function find_mrd_not_in_rmrn($condition)
{
    return $this->select(['inv_mrd.mrd_number as text', 'inv_mrd.id'])
        ->where($condition)
        ->where('inv_mrd.status', '=', 1)
        ->whereExists(function($query) {
            $query->select(DB::raw(1))
                ->from('inv_mrd_item_rel as mir')
                ->leftJoin('inv_rmrn_item as rri', 'mir.item', '=', 'rri.mrd_item_id') // adjust column names if different
                ->whereRaw('mir.master = inv_mrd.id')
                ->whereNull('rri.id'); // Item not yet used in RMRN
        })
        ->get();
}


}
