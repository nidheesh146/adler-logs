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
    return $this->select(
            'product_price_master.*', 
            'fgs_item_master.discription', 
            'fgs_item_master.sku_code', 
            'product_group1.group_name', 
            'fgs_item_master.hsn_code'
        )
        ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'product_price_master.product_id')
        ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
        ->where($condition)
        ->where('product_price_master.is_active', '=', 1)
        ->where(function ($query) {
            $query->where('fgs_item_master.item_type', '=', 'finished goods')
                  ->orWhere('fgs_item_master.item_type', '=', 'semifinished goods')
                  ->orWhereIn('new_product_category_id', [1, 2, 3]);
        })
        ->orderBy('product_price_master.id', 'desc')
        ->distinct('product_price_master.id')
        ->paginate(15);
}

// function get_single_product_price($condition)
// {
//     return $this->select('product_price_master.*', 'fgs_item_master.discription', 'fgs_item_master.sku_code', 'product_productgroup.group_name', 'fgs_item_master.hsn_code')
//                 ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'product_price_master.product_id')
//                 ->leftjoin('product_productgroup', 'product_productgroup.id', '=', 'fgs_item_master.product_group_id')
//                 ->where('product_price_master.id', '=', $condition['id'])  // Specify the table for id
//                 ->where(function ($query) {
//                     $query->where('fgs_item_master.item_type', '=', 'FINISHED GOODS')
//                         ->orWhere('fgs_item_master.item_type', '=', 'SEMIFINISHED GOODS');
//                 })
//                 ->where('product_price_master.is_active', '=', 1)
//                 ->first();
// }
function get_single_product_price($condition)
{
    // Check if 'id' exists in the $condition array before querying
    if (isset($condition['id'])) {
        return $this->select(
                    'product_price_master.*', 
                    'fgs_item_master.discription', 
                    'fgs_item_master.sku_code', 
                    'product_productgroup.group_name', 
                    'fgs_item_master.hsn_code'
                )
                ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'product_price_master.product_id')
                ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'fgs_item_master.product_group_id')
                ->where('product_price_master.id', $condition['id'])  // Check 'id' safely
                // ->where(function ($query) {
                //     $query->where('fgs_item_master.item_type', 'FINISHED GOODS')
                //           ->orWhere('fgs_item_master.item_type', 'SEMIFINISHED GOODS');
                // })
                ->where('product_price_master.is_active', 1)
                ->first();
    } 
    elseif (isset($condition['product_id'])) {
        return $this->select(
                    'product_price_master.*', 
                    'fgs_item_master.discription', 
                    'fgs_item_master.sku_code', 
                    'product_productgroup.group_name', 
                    'fgs_item_master.hsn_code'
                )
                ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'product_price_master.product_id')
                ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'fgs_item_master.product_group_id')
                ->where('product_price_master.product_id', $condition['product_id'])  // Use 'product_id' safely
                // ->where(function ($query) {
                //     $query->where('fgs_item_master.item_type', 'FINISHED GOODS')
                //           ->orWhere('fgs_item_master.item_type', 'SEMIFINISHED GOODS');
                // })
                ->where('product_price_master.is_active', 1)
                ->first();
    }

    // Return null if no valid condition is found
    return null;
}
}
