<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_cmtq_item extends Model
{
    
    protected $table = 'fgs_cmtq_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$mtq_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_cmtq_item_rel')->insert(['master'=>$mtq_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function getMTQItems($condition)
    {
        return $this->select('fgs_cmtq_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no')
                        ->leftjoin('fgs_cmtq_item_rel','fgs_cmtq_item_rel.item','=','fgs_cmtq_item.id')
                        ->leftjoin('fgs_cmtq','fgs_cmtq.id','=','fgs_cmtq_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_cmtq_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_cmtq_item.batchcard_id')
                        ->where($condition)
                        ->orderBy('fgs_cmtq_item.id','ASC')
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
                        ->orderBy('fgs_mtq_item.id','ASC')
                        ->get();
    }
    function getAllItems($condition)
    {
        return $this->select('fgs_cmtq_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_mtq_item.manufacturing_date','fgs_mtq_item.expiry_date')
                        ->leftjoin('fgs_cmtq_item_rel','fgs_cmtq_item_rel.item','=','fgs_cmtq_item.id')
                        ->leftjoin('fgs_cmtq','fgs_cmtq.id','=','fgs_cmtq_item_rel.master')
                        ->leftjoin('fgs_mtq_item','fgs_mtq_item.id','=','fgs_cmtq_item.mtq_item_id')
                        ->leftjoin('product_product','product_product.id','=','fgs_mtq_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mtq_item.batchcard_id')
                        ->where($condition)
                        ->orderBy('fgs_cmtq_item.id','ASC')
                        ->get();
    }
}
