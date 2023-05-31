<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_pi_item extends Model
{
    protected $table = 'fgs_pi_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
     function get_items($condition)
    {
        return $this->select('fgs_pi_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_pi.pi_number')
                        ->leftjoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                        ->leftjoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_pi_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_pi_item.batchcard_id')
                        ->where($condition)
                        //->where('inv_mac.status','=',1)
                        ->orderBy('fgs_pi_item.id','DESC')
                        ->distinct('fgs_pi_item.id')
                        ->paginate(15);
    }
    function getItems($condition)
    {
         return $this->select('fgs_pi_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_pi.pi_number')
                        ->leftjoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                        ->leftjoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_pi_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_pi_item.batchcard_id')
                       ->where($condition)
                    ->orderBy('fgs_pi_item.id','DESC')
                    ->get();
    }

    function get_pi_item($condition){
        return $this->select('fgs_pi_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','fgs_pi.pi_number','fgs_grs.grs_number','fgs_grs_item.batch_quantity')
                        ->join('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                        ->join('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                        ->leftjoin('product_product','product_product.id','=','fgs_pi_item.product_id')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                       ->where('fgs_pi_item.cpi_status','=',0)
                       ->where($condition)
                       ->get();
    } 
    
}
