<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_product_stock_management extends Model
{
    protected $table = 'fgs_product_stock_management';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_stock($condition)
    {
        return $this->select('fgs_product_stock_management.*','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no')
                    ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                    ->where($condition)
                    ->where('fgs_product_stock_management.quantity','!=',0)
                    ->distinct('fgs_product_stock_management.id')
                    ->orderBy('fgs_product_stock_management.id','DESC')
                    ->paginate(15);
    }
}
