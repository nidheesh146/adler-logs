<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $table = 'product_product';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
     
    function get_product_data($data){
        return $this->select(['id','sku_code as text','discription'])
                    ->where('sku_code','like','%'.$data.'%')
                    ->get()->toArray();
        
    }
    function get_label_filter($condition){
        return $this->select(['sku_code','mrp'])
                    ->where($condition)
                    ->get();
        
    }

    function get_products($condition){
        return $this->select(['product_product.*','product_productfamily.family_name','product_productgroup.group_name','product_productbrand.brand_name'])
                    ->leftjoin('product_productfamily','product_productfamily.id','=','product_product.product_family_id')
                    ->leftjoin('product_productgroup','product_productgroup.id','=','product_product.product_group_id')
                    ->leftjoin('product_productbrand','product_productbrand.id','=','product_product.brand_details_id')
                    ->where($condition)
                    ->orderBy('product_product.id','desc')
                    ->paginate(15);
    }
    function get_product_info($data){
        return $this->select(['product_product.id','product_product.sku_code as text','product_product.discription','product_productgroup.group_name','product_product.hsn_code','product_product.is_sterile'])
                    ->leftjoin('product_productgroup','product_productgroup.id','=','product_product.product_group_id')
                    ->where('product_product.sku_code','like','%'.$data.'%')
                    ->get()->toArray();
        
    }
    function get_product_info_for_oef($data)
    {
        return $this->select(['product_product.id','product_product.sku_code as text','product_product.discription','product_productgroup.group_name','product_product.hsn_code','product_price_master.mrp'])
                    ->leftjoin('product_productgroup','product_productgroup.id','=','product_product.product_group_id')
                    ->leftjoin('product_price_master','product_price_master.product_id','=','product_product.id')
                    ->where('product_product.sku_code','like','%'.$data.'%')
                    ->get()->toArray();
    }
    function get_product_mrn($condition){
        return $this->select(['product_product.id','product_product.sku_code as text','product_product.discription','product_productgroup.group_name','product_product.hsn_code'])
                    ->leftjoin('product_productgroup','product_productgroup.id','=','product_product.product_group_id')
                    ->where($condition)
                    ->get()->toArray();
        
    }


    

}
