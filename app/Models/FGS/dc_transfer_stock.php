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
    public function get_stock($condition)
{
    return $this->select(
            'fgs_item_master.sku_code',
            'batchcard_batchcard.batch_no',
            'dc_transfer_stock.*',
            'fgs_product_category.category_name',
            'fgs_item_master.hsn_code',
            'product_stock_location.location_name',
            'customer_supplier.firm_name'
        )
        ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'dc_transfer_stock.product_id')
        ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'dc_transfer_stock.batchcard_id')
        ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'dc_transfer_stock.stock_location_id')
        
        // ✅ JOIN delivery_challan_item with product_id + batchcard_id
        ->leftJoin('delivery_challan_item', function ($join) {
            $join->on('delivery_challan_item.product_id', '=', 'dc_transfer_stock.product_id')
                 ->on('delivery_challan_item.batchcard_id', '=', 'dc_transfer_stock.batchcard_id');
        })

        // ✅ JOIN item rel and challan
        ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
        ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')

        // ✅ JOIN customer
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')

        // ✅ JOIN category
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')

        // ✅ Filters
        ->where($condition)
        ->where('dc_transfer_stock.quantity', '!=', 0)

        // ✅ Remove duplicates
        ->distinct('dc_transfer_stock.id')

        // ✅ Order + paginate
        ->orderBy('dc_transfer_stock.id', 'DESC')
        ->paginate(15);
}

    function get_stock_satellite_export($condition){

        return $this->select('fgs_item_master.sku_code','batchcard_batchcard.batch_no','dc_transfer_stock.*','fgs_product_category.category_name','fgs_item_master.hsn_code','product_stock_location.location_name')
                   ->leftJoin('fgs_item_master','fgs_item_master.id','=','dc_transfer_stock.product_id')
                   ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','dc_transfer_stock.batchcard_id' )
                    ->leftJoin('product_stock_location','product_stock_location.id','=','dc_transfer_stock.stock_location_id' )
                   // ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
                   // ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
                   // ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
                   ->where($condition)
                   ->whereIn('dc_transfer_stock.stock_location_id', [15, 16, 17, 18, 20]) // ✅ this is key
                   ->where('dc_transfer_stock.quantity','!=',0)
                   ->distinct('dc_transfer_stock.id')
                   ->orderBy('dc_transfer_stock.id','DESC')
                  ->get();
   }
    function get_stock_consignment($condition, $location_ids = [])
{
    $query = $this->select(
                'fgs_item_master.sku_code',
                'batchcard_batchcard.batch_no',
                'dc_transfer_stock.*',
                'fgs_product_category.category_name',
                'customer_supplier.firm_name',
                'fgs_item_master.hsn_code',
                'product_stock_location.location_name'
            )
            ->leftJoin('delivery_challan_item', 'delivery_challan_item.product_id', '=', 'dc_transfer_stock.product_id')

            // Join delivery_challan_item_rel to link challan items to delivery_challan
            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
    
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
    
            ->leftJoin('customer_supplier','customer_supplier.id','=','delivery_challan.customer_id')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'dc_transfer_stock.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'dc_transfer_stock.batchcard_id')
            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'dc_transfer_stock.stock_location_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->where($condition)
            ->where('dc_transfer_stock.quantity', '!=', 0);

    // ✅ Apply stock location filter only if provided
    if (!empty($location_ids)) {
        $query->whereIn('dc_transfer_stock.stock_location_id', $location_ids);
    }

    return $query
        ->distinct('dc_transfer_stock.id')
        ->orderBy('dc_transfer_stock.id', 'DESC')
        ->paginate(15);
}
function get_stock_consignment_report($condition, $location_ids = [])
{
    $query = $this->select(
                'fgs_item_master.sku_code',
                'batchcard_batchcard.batch_no',
                'dc_transfer_stock.*',
                'fgs_product_category.category_name',
                'customer_supplier.firm_name',
                'fgs_item_master.hsn_code',
                'product_stock_location.location_name'
            )
            ->leftJoin('delivery_challan_item', 'delivery_challan_item.product_id', '=', 'dc_transfer_stock.product_id')

            // Join delivery_challan_item_rel to link challan items to delivery_challan
            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
    
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
    
            ->leftJoin('customer_supplier','customer_supplier.id','=','delivery_challan.customer_id')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'dc_transfer_stock.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'dc_transfer_stock.batchcard_id')
            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'dc_transfer_stock.stock_location_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->where($condition)
            ->where('dc_transfer_stock.quantity', '!=', 0);

    // ✅ Apply stock location filter only if provided
    if (!empty($location_ids)) {
        $query->whereIn('dc_transfer_stock.stock_location_id', $location_ids);
    }

    return $query
        ->distinct('dc_transfer_stock.id')
        ->orderBy('dc_transfer_stock.id', 'DESC')
        ->get();
}
    public function get_stock_scheme($condition, $location_ids = [])
{
    $query = $this->select(
        'fgs_item_master.sku_code',
        'batchcard_batchcard.batch_no',
        'dc_transfer_stock.*',
        'fgs_product_category.category_name',
        'fgs_item_master.hsn_code',
        'product_stock_location.location_name'
    )
    ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'dc_transfer_stock.product_id')
    ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'dc_transfer_stock.batchcard_id')
    ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'dc_transfer_stock.stock_location_id')
    ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
    ->where($condition)
    ->where('dc_transfer_stock.quantity', '!=', 0);

    // ✅ Apply the correct location filter here
    if (!empty($location_ids)) {
        $query->whereIn('dc_transfer_stock.stock_location_id', $location_ids);
    }

    return $query->distinct('dc_transfer_stock.id')
                 ->orderBy('dc_transfer_stock.id', 'DESC')
                 ->paginate(15);
}

//     function get_stock_export($condition){

//         return $this->select('fgs_item_master.sku_code','batchcard_batchcard.batch_no','dc_transfer_stock.*','fgs_product_category.category_name',
//         'fgs_item_master.hsn_code','product_stock_location.location_name', 'fgs_item_master.discription as prdt_description')
//                    ->leftJoin('fgs_item_master','fgs_item_master.id','=','dc_transfer_stock.product_id')
//                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','dc_transfer_stock.batchcard_id' )
//                     ->leftJoin('product_stock_location','product_stock_location.id','=','dc_transfer_stock.stock_location_id' )
//                    // ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
//                    // ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
//                     ->leftJoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
//                    // ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
//                    ->where($condition)
//                    ->where('dc_transfer_stock.quantity','!=',0)
//                    ->distinct('dc_transfer_stock.id')
//                    ->orderBy('dc_transfer_stock.id','DESC')
//                    ->get();
//    }
function get_stock_export($condition) {
     return $this->select(
        'fgs_item_master.sku_code',
        'fgs_item_master.discription',
        'batchcard_batchcard.batch_no',
        'customer_supplier.firm_name', // ✅ Added customer name

        'dc_transfer_stock.*',
        'fgs_product_category.category_name',
        'fgs_item_master.hsn_code',
        'product_stock_location.location_name'
    )
    ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'dc_transfer_stock.product_id')
    ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'dc_transfer_stock.batchcard_id')
    ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'dc_transfer_stock.stock_location_id')
    ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
    ->leftJoin('delivery_challan_item', 'delivery_challan_item.product_id', '=', 'dc_transfer_stock.product_id')
        ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
        ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
    ->where($condition)
    ->whereIn('dc_transfer_stock.stock_location_id', [25, 23,22, 20, 18, 17, 16, 15, 14, 13, 12, 9, 8])
    ->where('dc_transfer_stock.quantity', '!=', 0)
    ->distinct('dc_transfer_stock.id')
    ->get();
}

   function get_stock_export_consignment($condition){

    return $this->select('fgs_item_master.sku_code','batchcard_batchcard.batch_no','dc_transfer_stock.*','fgs_product_category.category_name',
    'fgs_item_master.hsn_code','product_stock_location.location_name', 'fgs_item_master.discription as prdt_description')
               ->leftJoin('fgs_item_master','fgs_item_master.id','=','dc_transfer_stock.product_id')
               ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','dc_transfer_stock.batchcard_id' )
                ->leftJoin('product_stock_location','product_stock_location.id','=','dc_transfer_stock.stock_location_id' )
               // ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
               // ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
                ->leftJoin('fgs_product_category','fgs_product_category.id','=','fgs_item_master.product_category_id')
               // ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
               ->where($condition)
               ->where('dc_transfer_stock.quantity','!=',0)
               ->distinct('dc_transfer_stock.id')
               ->orderBy('dc_transfer_stock.id','DESC')
               ->get();
}
}
