<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_min_item extends Model
{
    protected $table = 'fgs_min_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$min_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_min_item_rel')->insert(['master'=>$min_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_items($condition)
    {
        return $this->select('fgs_min_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_min.min_number')
                        ->leftjoin('fgs_min_item_rel','fgs_min_item_rel.item','=','fgs_min_item.id')
                        ->leftjoin('fgs_min','fgs_min.id','=','fgs_min_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_min_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_min_item.batchcard_id')
                        ->where($condition)
                        //->where('inv_mac.status','=',1)
                        ->orderBy('fgs_min_item.id','DESC')
                        ->distinct('fgs_min_item.id')
                        ->paginate(15);
    }
}
