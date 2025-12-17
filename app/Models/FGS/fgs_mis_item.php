<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_mis_item extends Model
{
    protected $table = 'fgs_mis_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$mis_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_mis_item_rel')->insert(['master'=>$mis_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function getMISItems($condition)
    {
        return $this->select('fgs_item_master.sku_code','fgs_item_master.hsn_code','fgs_item_master.discription','batchcard_batchcard.batch_no',
        'fgs_mtq_item.quantity','fgs_mtq_item.manufacturing_date','fgs_mtq_item.expiry_date')
                    ->leftjoin('fgs_mis_item_rel','fgs_mis_item_rel.item','=', 'fgs_mis_item.id')
                    ->leftjoin('fgs_mtq_item','fgs_mtq_item.id','=', 'fgs_mis_item.mtq_item_id')
                    ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_mis_item.product_id')
                    ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mtq_item.batchcard_id')
                    ->where($condition)
                    ->orderBy('fgs_mis_item.id','ASC')
                    ->distinct('fgs_mis_item.id')
                    ->paginate(15);
    }

    function get_items($condition)
    {
        return $this->select('fgs_mis_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','batchcard_batchcard.batch_no','fgs_mtq_item.manufacturing_date','fgs_mtq_item.expiry_date','fgs_mtq_item.quantity')
                        ->leftjoin('fgs_mis_item_rel','fgs_mis_item_rel.item','=','fgs_mis_item.id')
                        ->leftjoin('fgs_mis','fgs_mis.id','=','fgs_mis_item_rel.master')
                          ->leftjoin('fgs_mtq_item','fgs_mtq_item.id','=','fgs_mis_item.mtq_item_id')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_mtq_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mtq_item.batchcard_id')
                        ->where($condition)
                        ->orderBy('fgs_mis_item.id','ASC')
                        ->get();
    }
}
