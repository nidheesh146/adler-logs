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

    function get_stock($condition,$whereIn)
    {
        return $this->select('fgs_product_stock_management.*','fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','fgs_item_master.sku_code','fgs_item_master.discription','batchcard_batchcard.batch_no','fgs_item_master.hsn_code','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','fgs_item_master.quantity_per_pack','fgs_item_master.is_sterile','product_stock_location.location_name')
                    ->leftJoin('fgs_item_master','fgs_item_master.id','=','fgs_product_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                    ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
                    ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
                    ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
                    ->where($condition)
                    ->whereIn('product_stock_location.id',$whereIn)
                    ->where('fgs_product_stock_management.quantity','>',0)
                    ->distinct('fgs_product_stock_management.id')
                    ->orderBy('fgs_product_stock_management.id','DESC')
                    ->paginate(15);
    }
    function get_stock_exportt($condition,$whereIn)
    {
        return $this->select('fgs_product_stock_management.*','fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','fgs_item_master.sku_code','fgs_item_master.discription','batchcard_batchcard.batch_no','fgs_item_master.hsn_code','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','fgs_item_master.quantity_per_pack','fgs_item_master.is_sterile','product_stock_location.location_name')
                    ->leftJoin('fgs_item_master','fgs_item_master.id','=','fgs_product_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                    ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
                    ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
                    ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
                    ->where($condition)
                    ->whereIn('product_stock_location.id',$whereIn)
                    ->where('fgs_product_stock_management.quantity','>',0)
                    ->distinct('fgs_product_stock_management.id')
                    ->orderBy('fgs_product_stock_management.id','DESC')
                    ->get();
    }
    function get_AHPL_stock($condition,$whereIn)
    {
        return $this->select('fgs_product_stock_management.*','fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','fgs_item_master.sku_code','fgs_item_master.discription','batchcard_batchcard.batch_no','fgs_item_master.hsn_code','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','fgs_item_master.quantity_per_pack','fgs_item_master.is_sterile','product_stock_location.location_name')
                    ->leftJoin('fgs_item_master','fgs_item_master.id','=','fgs_product_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                    ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
                    ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
                    ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
                    ->where($condition)
                    ->whereIn('product_stock_location.id',$whereIn)
                    ->where('fgs_product_stock_management.quantity','!=',0)
                    ->distinct('fgs_product_stock_management.id')
                    ->orderBy('fgs_product_stock_management.id','DESC')
                    ->get();
    }
    function get_location2_stock($condition,$whereIn)
    {
        return $this->select('fgs_product_stock_management.*','fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','fgs_item_master.sku_code','fgs_item_master.discription','batchcard_batchcard.batch_no','fgs_item_master.hsn_code','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','fgs_item_master.quantity_per_pack','fgs_item_master.is_sterile','product_stock_location.location_name')
                    ->leftJoin('fgs_item_master','fgs_item_master.id','=','fgs_product_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                    ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
                    ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
                    ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
                    ->where($condition)
                    ->whereIn('product_stock_location.id',$whereIn)
                    ->where('fgs_product_stock_management.quantity','!=',0)
                    ->distinct('fgs_product_stock_management.id')
                    ->orderBy('fgs_product_stock_management.id','DESC')
                    ->get();
    }
    function get_location1_stock($condition,$whereIn)
    {
        return $this->select('fgs_product_stock_management.*','fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','fgs_item_master.sku_code','fgs_item_master.discription','batchcard_batchcard.batch_no','fgs_item_master.hsn_code','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','fgs_item_master.quantity_per_pack','fgs_item_master.is_sterile','product_stock_location.location_name')
                    ->leftJoin('fgs_item_master','fgs_item_master.id','=','fgs_product_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                    ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
                    ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
                    ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
                    ->where($condition)
                    ->whereIn('product_stock_location.id',$whereIn)
                    ->where('fgs_product_stock_management.quantity','!=',0)
                    ->distinct('fgs_product_stock_management.id')
                    ->orderBy('fgs_product_stock_management.id','DESC')
                    ->get();
    }
    function get_stock_export($condition)
    {
        return $this->select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','fgs_item_master.sku_code','fgs_item_master.discription','batchcard_batchcard.batch_no','fgs_item_master.hsn_code','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','fgs_item_master.quantity_per_pack','fgs_item_master.is_sterile','product_stock_location.location_name')
                    ->leftJoin('fgs_item_master','fgs_item_master.id','=','fgs_product_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                    ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
                    ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
                    ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
                    ->where($condition)
                    //->whereIn('product_stock_location.id',$whereIn)
                    ->where('fgs_product_stock_management.quantity','!=',0)
                    ->distinct('fgs_product_stock_management.id')
                    ->orderBy('fgs_product_stock_management.id','DESC')
                    ->get();
    }
}
