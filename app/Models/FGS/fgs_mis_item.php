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
        return $this->select('product_product.sku_code','product_product.hsn_code','product_product.discription','batchcard_batchcard.batch_no',
        'fgs_mtq_item.quantity','fgs_mtq_item.manufacturing_date','fgs_mtq_item.expiry_date')
                    ->leftjoin('fgs_mis_item_rel','fgs_mis_item_rel.item','=', 'fgs_mis_item.id')
                    ->leftjoin('fgs_mtq_item','fgs_mtq_item.id','=', 'fgs_mis_item.mtq_item_id')
                    ->leftjoin('product_product','product_product.id','=','fgs_mis_item.product_id')
                    ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mtq_item.batchcard_id')
                    ->where($condition)
                    ->orderBy('fgs_mis_item.id','DESC')
                    ->distinct('fgs_mis_item.id')
                    ->paginate(15);
    }

    function get_items($condition)
    {
        return $this->select('fgs_mis_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_mtq_item.manufacturing_date','fgs_mtq_item.expiry_date','fgs_mtq_item.quantity')
                        ->leftjoin('fgs_mis_item_rel','fgs_mis_item_rel.item','=','fgs_mis_item.id')
                        ->leftjoin('fgs_mis','fgs_mis.id','=','fgs_mis_item_rel.master')
                          ->leftjoin('fgs_mtq_item','fgs_mtq_item.id','=','fgs_mis_item.mtq_item_id')
                        ->leftjoin('product_product','product_product.id','=','fgs_mtq_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mtq_item.batchcard_id')
                        ->where($condition)
                        ->orderBy('fgs_mis_item.id','DESC')
                        ->get();
    }
}
