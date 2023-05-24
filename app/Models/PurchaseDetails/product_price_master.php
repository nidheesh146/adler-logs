<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_price_master extends Model
{
    protected $table = 'product_price_master';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_all($condition)
    {
        return $this->select('product_price_master.*','product_product.discription','product_product.sku_code','product_group1.group_name','product_product.hsn_code')
                    ->leftjoin('product_product','product_product.id','=','product_price_master.product_id')
                    ->leftjoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                    ->where($condition)
                    ->where('product_price_master.is_active','=',1)
                    ->orderby('product_price_master.id','desc')
                    ->distinct('product_price_master.id')
                    ->paginate(15);

    }
     function get_single_product_price($condition)
    {
        return $this->select('product_price_master.*','product_product.discription','product_product.sku_code','product_productgroup.group_name','product_product.hsn_code')
                    ->leftjoin('product_product','product_product.id','=','product_price_master.product_id')
                    ->leftjoin('product_productgroup','product_productgroup.id','=','product_product.product_group_id')
                    ->where($condition)
                    ->where('product_price_master.is_active','=',1)
                    ->first();

    }
}
