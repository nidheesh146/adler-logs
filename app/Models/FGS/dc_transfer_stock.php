<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dc_transfer_stock extends Model
{
    protected $table = 'dc_transfer_stock';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_stock($condition){

         return $this->select('product_product.sku_code','batchcard_batchcard.batch_no','dc_transfer_stock.*','fgs_product_category.category_name','product_product.hsn_code','product_stock_location.location_name')
                    ->leftJoin('product_product','product_product.id','=','dc_transfer_stock.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','dc_transfer_stock.batchcard_id' )
                     ->leftJoin('product_stock_location','product_stock_location.id','=','dc_transfer_stock.stock_location_id' )
                    // ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                    // ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                     ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                    // ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                    ->where($condition)
                    ->where('dc_transfer_stock.quantity','!=',0)
                    ->distinct('dc_transfer_stock.id')
                    ->orderBy('dc_transfer_stock.id','DESC')
                    ->paginate(15);
    }
}
