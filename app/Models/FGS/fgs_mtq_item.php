<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_mtq_item extends Model
{
    
    protected $table = 'fgs_mtq_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$mtq_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_mtq_item_rel')->insert(['master'=>$mtq_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function getMTQItems($condition)
    {
        return $this->select('fgs_mtq_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_mtq.mtq_number')
                        ->leftjoin('fgs_mtq_item_rel','fgs_mtq_item_rel.item','=','fgs_mtq_item.id')
                        ->leftjoin('fgs_mtq','fgs_mtq.id','=','fgs_mtq_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_mtq_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mtq_item.batchcard_id')
                        ->where($condition)
                        ->orderBy('fgs_mtq_item.id','DESC')
                        ->paginate(15);
    }
    function get_items($condition)
    {
        return $this->select('fgs_mtq_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_mtq.mtq_number')
                        ->leftjoin('fgs_mtq_item_rel','fgs_mtq_item_rel.item','=','fgs_mtq_item.id')
                        ->leftjoin('fgs_mtq','fgs_mtq.id','=','fgs_mtq_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_mtq_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mtq_item.batchcard_id')
                        ->where($condition)
                        ->orderBy('fgs_mtq_item.id','DESC')
                        ->get();
    }
}
