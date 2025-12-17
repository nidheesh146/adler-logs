<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class delivery_challan_item extends Model
{
    use HasFactory;
    protected $table = 'delivery_challan_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data,$dc_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('delivery_challan_item_rel')->insert(['master'=>$dc_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function getItemspdf($condition)
    {
        return $this->select(
            'delivery_challan_item.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no',
            'fgs_product_stock_management.manufacturing_date',
            'fgs_product_stock_management.expiry_date'
        )
        ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
        ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
        ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
        ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'delivery_challan_item.batchcard_id')
    
        // ✅ New join for correct date source
        ->leftJoin('fgs_product_stock_management', function($join) {
            $join->on('fgs_product_stock_management.product_id', '=', 'delivery_challan_item.product_id')
                 ->on('fgs_product_stock_management.batchcard_id', '=', 'delivery_challan_item.batchcard_id')
                 ->on('fgs_product_stock_management.stock_location_id', '=', 'delivery_challan.stock_location_decrease');
        })
    
        // ❌ Remove old join
        // ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'delivery_challan_item.mrn_item_id')
    
        ->where($condition)
        ->where('delivery_challan.status', '=', 1)
        ->where('delivery_challan_item.status', '=', 1)
        ->orderBy('delivery_challan_item.id', 'ASC')
        ->distinct('delivery_challan_item.id')
        ->get();
    }
    
    function getItemspdfbatch($condition)
{
    return $this->select(
                'delivery_challan_item.*',
                'fgs_item_master.sku_code',
                'fgs_item_master.discription',
                'fgs_item_master.hsn_code',
                'batchcard_batchcard.batch_no',
                'fgs_product_stock_management.manufacturing_date',
                'fgs_product_stock_management.expiry_date'
            )
            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'delivery_challan_item.batchcard_id')
            ->leftJoin('fgs_product_stock_management', function($join) {
                $join->on('fgs_product_stock_management.product_id', '=', 'delivery_challan_item.product_id')
                     ->on('fgs_product_stock_management.batchcard_id', '=', 'delivery_challan_item.batchcard_id');
            })
            ->where($condition)
            ->where('delivery_challan.status', '=', 1)
            ->where('delivery_challan_item.status', '=', 1)
            ->orderBy('delivery_challan_item.id', 'ASC')
            ->distinct('delivery_challan_item.id')
            ->get();
}

function getItems($condition)
{
    return $this->select(
        'delivery_challan_item.*',
        'fgs_item_master.sku_code',
        'fgs_item_master.discription',
        'fgs_item_master.hsn_code',
        'batchcard_batchcard.batch_no',
        'fgs_product_stock_management.manufacturing_date',
        'fgs_product_stock_management.expiry_date',
        'delivery_challan.doc_no',
        'delivery_challan.doc_date',
        'fgs_oef.oef_number',
        'fgs_oef.oef_date',
        'delivery_challan.ref_no',
        'delivery_challan.ref_date',
        'customer_supplier.firm_name',
        'delivery_challan.transaction_condition',
        'fgs_product_category.category_name',
        'transaction_type.transaction_name',
        'stock_location_decrease.location_name as location_decrease',
        'stock_location_increase.location_name as location_increase',
        'zone.zone_name',
        'state.state_name'
    )
    ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
    ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
    ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
    ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'delivery_challan_item.batchcard_id')

    // ✅ New source of dates
    ->leftJoin('fgs_product_stock_management', function($join) {
        $join->on('fgs_product_stock_management.product_id', '=', 'delivery_challan_item.product_id')
             ->on('fgs_product_stock_management.batchcard_id', '=', 'delivery_challan_item.batchcard_id')
             ->on('fgs_product_stock_management.stock_location_id', '=', 'delivery_challan.stock_location_decrease');
    })

    // ❌ Removed old source of dates
    // ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'delivery_challan_item.mrn_item_id')

    ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'delivery_challan.oef_id')
    ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
    ->leftJoin('transaction_type', 'transaction_type.id', '=', 'delivery_challan.transaction_type')
    ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'delivery_challan.product_category')
    ->leftJoin('product_stock_location as stock_location_decrease', 'stock_location_decrease.id', '=', 'delivery_challan.stock_location_decrease')
    ->leftJoin('product_stock_location as stock_location_increase', 'stock_location_increase.id', '=', 'delivery_challan.stock_location_increase')
    ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
    ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
    ->where($condition)
    ->where('delivery_challan.status', '=', 1)
    ->where('delivery_challan_item.status', '=', 1)
    ->orderBy('delivery_challan_item.id', 'DESC')
    ->distinct('delivery_challan_item.id')
    ->paginate(15);
}

    function getAllItems($condition)
    {
        return $this->select('delivery_challan_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','delivery_challan.doc_no',
            'delivery_challan.doc_date','fgs_oef.oef_number','fgs_oef.oef_date','delivery_challan.ref_no','delivery_challan.ref_date',
            'customer_supplier.firm_name','delivery_challan.transaction_condition','fgs_product_category.category_name','transaction_type.transaction_name',
            'stock_location_decrease.location_name as location_decrease','stock_location_increase.location_name as location_increase','zone.zone_name','state.state_name')
                        ->leftjoin('delivery_challan_item_rel','delivery_challan_item_rel.item','=', 'delivery_challan_item.id')
                        ->leftjoin('delivery_challan','delivery_challan.id','=','delivery_challan_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','delivery_challan_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','delivery_challan_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','delivery_challan_item.mrn_item_id')
                        ->leftjoin('fgs_oef','fgs_oef.id','=','delivery_challan.oef_id')
                        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
                        ->leftJoin('transaction_type','transaction_type.id','=','delivery_challan.transaction_type')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','delivery_challan.product_category')
                        ->leftJoin('product_stock_location as stock_location_decrease','stock_location_decrease.id','delivery_challan.stock_location_decrease')
                        ->leftJoin('product_stock_location as stock_location_increase','stock_location_increase.id','delivery_challan.stock_location_increase')
                        ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                        ->leftJoin('state','state.state_id','=','customer_supplier.state')
                        ->where($condition)
                        ->where('delivery_challan.status','=',1)
                        ->where('delivery_challan_item.status','=',1)
                        ->orderBy('delivery_challan_item.id','DESC')
                        ->distinct('delivery_challan_item.id')
                        ->get();
    }
    function getSingleItem($condition)
    {
        return $this->select('delivery_challan_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','fgs_item_master.drug_license_number',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','delivery_challan.doc_no','fgs_oef_item.rate',
            'delivery_challan.doc_date','fgs_oef.oef_number','fgs_oef.oef_date','delivery_challan.ref_no','delivery_challan.ref_date',
            'customer_supplier.firm_name','delivery_challan.transaction_condition','fgs_product_category.category_name','transaction_type.transaction_name',
            'stock_location_decrease.location_name as location_decrease','stock_location_increase.location_name as location_increase','zone.zone_name','state.state_name')
                        ->leftjoin('delivery_challan_item_rel','delivery_challan_item_rel.item','=', 'delivery_challan_item.id')
                        ->leftjoin('delivery_challan','delivery_challan.id','=','delivery_challan_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','delivery_challan_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','delivery_challan_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','delivery_challan_item.mrn_item_id')
                        ->leftjoin('fgs_oef','fgs_oef.id','=','delivery_challan.oef_id')
                        ->leftjoin('fgs_oef_item','fgs_oef_item.id','=','delivery_challan_item.oef_item_id')
                        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
                        ->leftJoin('transaction_type','transaction_type.id','=','delivery_challan.transaction_type')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','delivery_challan.product_category')
                        ->leftJoin('product_stock_location as stock_location_decrease','stock_location_decrease.id','delivery_challan.stock_location_decrease')
                        ->leftJoin('product_stock_location as stock_location_increase','stock_location_increase.id','delivery_challan.stock_location_increase')
                        ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                        ->leftJoin('state','state.state_id','=','customer_supplier.state')
                        ->where($condition)
                        ->where('delivery_challan.status','=',1)
                        ->where('delivery_challan_item.status','=',1)
                        ->first();
    }
    function get_dc_item($condition){
        return $this->select('delivery_challan_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','delivery_challan.doc_no','fgs_item_master.quantity_per_pack','batchcard_batchcard.batch_no')
                        ->join('delivery_challan_item_rel','delivery_challan_item_rel.item','=','delivery_challan_item.id')
                        ->join('delivery_challan','delivery_challan.id','=','delivery_challan_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','delivery_challan_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','delivery_challan_item.batchcard_id')
                       ->where('delivery_challan_item.cdc_status','=',0)
                       ->where('delivery_challan_item.remaining_qty_after_cancel','>',0)
                       ->orderBy('delivery_challan_item.id','ASC')
                       ->where($condition)
                       ->where('delivery_challan.status','=',1)
                       ->where('delivery_challan_item.status','=',1)
                       ->get();
    }
    function getSingleItem_label($condition)
    {
        return $this->select('delivery_challan_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','fgs_item_master.drug_license_number',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','delivery_challan.doc_no','fgs_oef_item.rate',
            'delivery_challan.doc_date','fgs_oef.oef_number','fgs_oef.oef_date','delivery_challan.ref_no','delivery_challan.ref_date','product_price_master.mrp',
            'customer_supplier.firm_name','delivery_challan.transaction_condition','fgs_product_category.category_name','transaction_type.transaction_name',
            'stock_location_decrease.location_name as location_decrease','stock_location_increase.location_name as location_increase','zone.zone_name','state.state_name')
                        ->leftjoin('delivery_challan_item_rel','delivery_challan_item_rel.item','=', 'delivery_challan_item.id')
                        ->leftjoin('delivery_challan','delivery_challan.id','=','delivery_challan_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','delivery_challan_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','delivery_challan_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','delivery_challan_item.mrn_item_id')
                        ->leftjoin('fgs_oef','fgs_oef.id','=','delivery_challan.oef_id')
                        ->leftjoin('fgs_oef_item','fgs_oef_item.id','=','delivery_challan_item.oef_item_id')
                        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
                        ->leftJoin('transaction_type','transaction_type.id','=','delivery_challan.transaction_type')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','delivery_challan.product_category')
                        ->leftJoin('product_stock_location as stock_location_decrease','stock_location_decrease.id','delivery_challan.stock_location_decrease')
                        ->leftJoin('product_stock_location as stock_location_increase','stock_location_increase.id','delivery_challan.stock_location_increase')
                        ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                        ->leftJoin('state','state.state_id','=','customer_supplier.state')
                        ->leftJoin('product_price_master','product_price_master.product_id','=', 'fgs_item_master.id')
                        ->where($condition)
                        ->where('delivery_challan.status','=',1)
                        ->where('delivery_challan_item.status','=',1)
                        ->first();
    }
}
