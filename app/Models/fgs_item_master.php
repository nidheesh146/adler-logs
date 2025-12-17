<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_item_master extends Model
{
    use HasFactory;
    protected $table = 'fgs_item_master';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_product_data($data){
        return $this->select(['fgs_item_master.id as id','sku_code as text','discription','process_sheet_no'])
        ->leftjoin('product_input_material','fgs_item_master.id','=','product_input_material.product_id')
        ->leftjoin('fgs_transfer','fgs_transfer.pr_item_id','=','product_input_material.item_id1')
                    ->where('sku_code','like','%'.$data.'%')
                    ->get()->toArray();
        
    }
    function get_label_filter($condition){
        return $this->select(['sku_code','mrp'])
                    ->where($condition)
                    ->get();
        
    }
    function get_product_info_for_oef($data,$condition)
    {
        return $this->select(['fgs_item_master.id','fgs_item_master.sku_code as text','fgs_item_master.discription','product_productgroup.group_name','fgs_item_master.hsn_code','product_price_master.sales','fgs_item_master.gst'])
                    ->leftjoin('product_productgroup','product_productgroup.id','=','fgs_item_master.product_group_id')
                    ->leftjoin('product_price_master','product_price_master.product_id','=','fgs_item_master.id')
                    ->where('fgs_item_master.sku_code','like','%'.$data.'%')
                    ->where($condition)
                    ->get()->toArray();
    }
    function get_products($condition)
{
    return $this->select([
            'fgs_item_master.*',
            'product_productfamily.family_name',
            'product_productgroup.group_name',
            'product_productbrand.brand_name',
            'product_group1.group_name as group1_name',
            'fgs_product_category.category_name',
            'product_type.product_type_name'
        ])
        ->leftJoin('product_productfamily', 'product_productfamily.id', '=', 'fgs_item_master.product_family_id')
        ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'fgs_item_master.product_group_id')
        ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
        ->leftJoin('product_productbrand', 'product_productbrand.id', '=', 'fgs_item_master.brand_details_id')
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
        ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
        ->where($condition)
        ->where(function ($query) {
            $query->whereIn('fgs_item_master.item_type', ['finished goods', 'semifinished goods'])
                  ->orWhereIn('new_product_category_id', [1, 2, 3]);
        })        ->orderBy('fgs_item_master.id', 'desc')
        ->distinct('fgs_item_master.id')
        ->paginate(15);
}

    
    function get_products_prdct($condition){
        
        return $this->select(['fgs_item_master.*','product_productfamily.family_name','product_productgroup.group_name','product_productbrand.brand_name',
        'product_group1.group_name as group1_name','fgs_product_category.category_name','product_type.product_type_name'])
                    ->leftjoin('product_productfamily','product_productfamily.id','=','fgs_item_master.product_family_id')
                    ->leftjoin('product_productgroup','product_productgroup.id','=','fgs_item_master.product_group_id')
                    ->leftjoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
                    ->leftjoin('product_productbrand','product_productbrand.id','=','fgs_item_master.brand_details_id')
                    ->leftjoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
                    ->leftjoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
                    ->where($condition)
                    // ->where('fgs_item_master.item_type','!=','SEMIFINISHED GOODS')
                    ->orderBy('fgs_item_master.id','desc')
                    ->distinct('fgs_item_master.id')
                    ->paginate(15);
    }
    // function get_all_products($condition){
    //     return $this->select([
    //         'fgs_item_master.*',
    //         'product_productfamily.family_name',
    //         'product_productgroup.group_name',
    //         'product_productbrand.brand_name',
    //         'product_group1.group_name as group1_name',
    //         'product_oem.oem_name',
    //         'product_type.product_type_name',
    //         'fgs_product_category.category_name',
    //         'fgs_product_category_new.category_name as new_category_name'  // Add this line
    //     ])
    //     ->leftjoin('product_productfamily', 'product_productfamily.id', '=', 'fgs_item_master.product_family_id')
    //     ->leftjoin('product_productgroup', 'product_productgroup.id', '=', 'fgs_item_master.product_group_id')
    //     ->leftjoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
    //     ->leftjoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
    //     ->leftjoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
    //     ->leftjoin('product_productbrand', 'product_productbrand.id', '=', 'fgs_item_master.brand_details_id')
    //     ->leftjoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
    //     ->leftjoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')  // Add this line
    //     ->where($condition)
    //     //->where('fgs_item_master.item_type', '!=', 'SEMIFINISHED GOODS')
    //     ->where(function ($query) {
    //         $query->whereIn('fgs_item_master.item_type', ['finished goods', 'semifinished goods'])
    //               ->orWhereIn('new_product_category_id', [1, 2, 3]);
    //     })        ->orderBy('fgs_item_master.id', 'desc')
    //     ->distinct('fgs_item_master.id')
    //     //->orderBy('fgs_item_master.id', 'desc')
    //     //->distinct('fgs_item_master.id')
    //     ->get();
    // }
    function get_all_products($condition)
    {
        return $this->select([
                'fgs_item_master.*',
                'product_productfamily.family_name',
                'product_productgroup.group_name',
                'product_productbrand.brand_name',
                'product_group1.group_name as group1_name',
                'fgs_product_category.category_name',
                'product_type.product_type_name'
            ])
            ->leftJoin('product_productfamily', 'product_productfamily.id', '=', 'fgs_item_master.product_family_id')
            ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'fgs_item_master.product_group_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('product_productbrand', 'product_productbrand.id', '=', 'fgs_item_master.brand_details_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->where($condition)
            ->where(function ($query) {
                $query->whereIn('fgs_item_master.item_type', ['finished goods', 'semifinished goods'])
                      ->orWhereIn('new_product_category_id', [1, 2, 3]);
            })        ->orderBy('fgs_item_master.id', 'desc')
            ->distinct('fgs_item_master.id')
            ->get();
    }
    
    function get_product_info($data){
        return $this->select(['fgs_item_master.id','fgs_item_master.sku_code as text','fgs_item_master.discription','product_productgroup.group_name','fgs_item_master.hsn_code','fgs_item_master.is_sterile','fgs_item_master.process_sheet_no'])
                    ->leftjoin('product_productgroup','product_productgroup.id','=','fgs_item_master.product_group_id')
                    ->where('fgs_item_master.sku_code','like','%'.$data.'%')
                    ->get()->toArray();
        
    }
    function get_product_info_fgs($data,$condition){
        return $this->select(['fgs_item_master.id','fgs_item_master.sku_code as text','fgs_item_master.discription','product_productgroup.group_name','fgs_item_master.hsn_code',
        'fgs_item_master.is_sterile','fgs_item_master.process_sheet_no','fgs_product_category.category_name'])
                    ->leftjoin('product_productgroup','product_productgroup.id','=','fgs_item_master.product_group_id')
                    ->leftjoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
                    ->where('fgs_item_master.sku_code','like','%'.$data.'%')
                    ->where($condition)
                    ->get()->toArray();
        
    }

    // function get_product_info_trade($data){
    //     return $this->select(['fgs_item_master.id','fgs_item_master.sku_code as text','fgs_item_master.discription','product_productgroup.group_name','fgs_item_master.hsn_code','fgs_item_master.is_sterile','fgs_item_master.process_sheet_no'])
    //                 ->leftjoin('product_productgroup','product_productgroup.id','=','fgs_item_master.product_group_id')
    //                 ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.item_code','=','fgs_item_master.sku_code')
    //                 ->leftjoin('inv_purchase_req_item','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
    //                 ->leftjoin('inv_mac_item','inv_purchase_req_item.requisition_item_id','=','inv_mac_item.pr_item_id')
    //                 ->leftjoin('fgs_transfer','inv_mac_item.id','=','fgs_transfer.pr_item_id')
    //                 ->where('inventory_rawmaterial.item_code','like','%'.$data.'%')
    //                 ->get()->toArray();
        
    // }
   
    function get_product_mrn($condition){
        return $this->select(['fgs_item_master.id','fgs_item_master.sku_code as text','fgs_item_master.discription','product_productgroup.group_name','fgs_item_master.hsn_code'])
                    ->leftjoin('product_productgroup','product_productgroup.id','=','fgs_item_master.product_group_id')
                    ->where($condition)
                    ->get()->toArray();
        
    }
     function get_single_product($condition)
    {
        
     return $this->select('fgs_item_master.*')
                    ->where($condition)
                   ->first();

    }


    

}
