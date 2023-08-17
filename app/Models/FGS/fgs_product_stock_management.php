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
        return $this->select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                    ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                    ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                    ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                    ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                    ->where($condition)
                    ->where('fgs_product_stock_management.quantity','!=',0)
                    ->distinct('fgs_product_stock_management.id')
                    ->orderBy('fgs_product_stock_management.id','DESC')
                    ->paginate(15);
    }
    function get_stock_export($condition)
    {
        return $this->select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                    ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                    ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                    ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                    ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                    ->where($condition)
                    ->where('fgs_product_stock_management.quantity','!=',0)
                    ->distinct('fgs_product_stock_management.id')
                    ->orderBy('fgs_product_stock_management.id','DESC')
                    ->get();
    }
}
