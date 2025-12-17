<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class fgs_cdc_item extends Model
{
    // use HasFactory;
    protected $table = 'fgs_cdc_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data, $cdc_id)
    {
        $item_id =  $this->insertGetId($data);
        if ($item_id) {
            DB::table('fgs_cdc_item_rel')->insert(['master' => $cdc_id, 'item' => $item_id]);
        }
        return true;
    }
    function get_items($id)
    {
        return $this->select(
            'fgs_item_master.sku_code',
            'fgs_item_master.hsn_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'fgs_cdc_item.*',

        )
            ->leftjoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
            ->leftjoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
            ->leftjoin('delivery_challan', 'delivery_challan.id', '=', 'fgs_cdc.dc_id')

            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_cdc.customer_id')

            ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_cdc_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cdc_item.batchcard_id')
            ->where('fgs_cdc_item_rel.master', '=', $id)
            ->where('delivery_challan.status', '=', 1)
            ->orderBy('fgs_cdc_item.id', 'DESC')
            //->distinct('fgs_cdc_item.id')
            ->paginate(15);
        }
        function get_manual_items($id)
    {
        return $this->select(
            'fgs_item_master.sku_code',
            'fgs_item_master.hsn_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'fgs_cdc_item.*',

        )
            ->leftjoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
            ->leftjoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_cdc.customer_id')

            ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_cdc_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cdc_item.batchcard_id')
            ->where('fgs_cdc_item_rel.master', '=', $id)
            //->where('delivery_challan.status', '=', 1)
            ->orderBy('fgs_cdc_item.id', 'DESC')
            //->distinct('fgs_cdc_item.id')
            ->paginate(15);
        }
        function getItemss($condition)
        {
            return $this->select('fgs_cdc_item.*', 'fgs_item_master.sku_code', 'fgs_item_master.discription', 'fgs_item_master.hsn_code', 'batchcard_batchcard.batch_no', 'fgs_cdc.cdc_number',
             'fgs_mrn_item.manufacturing_date as mrn_manf','fgs_mrn_item.expiry_date as mrn_exp',
            //  'fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date',
            'delivery_challan.stock_location_decrease'
            )
                ->leftjoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
                ->leftjoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
                ->leftjoin('delivery_challan_item','delivery_challan_item.id','=', 'fgs_cdc_item.dc_item_id')
                ->leftjoin('delivery_challan_item_rel','delivery_challan_item_rel.item','=', 'delivery_challan_item.id')
                ->leftjoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
                  ->leftjoin('fgs_product_stock_management','fgs_product_stock_management.batchcard_id','=','fgs_cdc_item.batchcard_id')
                //   ->leftjoin('fgs_product_stock_management','fgs_product_stock_management.stock_location_id','=','stock_location_id.stock_location_decrease')
                // ->leftjoin("fgs_product_stock_management", function ($join)  {
                //     $join->on("fgs_product_stock_management.batchcard_id", "=", "fgs_cdc_item.batchcard_id");
                //     $join->where("fgs_product_stock_management.stock_location_id", "=", "stock_location_id.stock_location_decrease");
                // })
                 ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','delivery_challan_item.mrn_item_id')
                ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_cdc_item.product_id')
                ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cdc_item.batchcard_id')
                ->where($condition)
                //   ->where('fgs_product_stock_management.product_id','=','fgs_cdc_item.product_id')
                //  ->where('fgs_product_stock_management.batchcard_id','=','fgs_cdc_item.batchcard_id')
                ->orderBy('fgs_cdc_item.id', 'ASC')
                ->distinct('fgs_product_stock_management.batchcard_id')
                ->get();
        }
        function getItems($condition)
        {
            return $this->select('fgs_cdc_item.*', 'fgs_item_master.sku_code', 'fgs_item_master.discription', 'fgs_item_master.hsn_code', 'batchcard_batchcard.batch_no', 'fgs_cdc.cdc_number',
             'fgs_mrn_item.manufacturing_date as mrn_manf','fgs_mrn_item.expiry_date as mrn_exp',
            //  'fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date',
            'delivery_challan.stock_location_decrease'
            )
                ->leftjoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
                ->leftjoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
                ->leftjoin('delivery_challan_item','delivery_challan_item.id','=', 'fgs_cdc_item.dc_item_id')
                ->leftjoin('delivery_challan_item_rel','delivery_challan_item_rel.item','=', 'delivery_challan_item.id')
                ->leftjoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
                  ->leftjoin('fgs_product_stock_management','fgs_product_stock_management.batchcard_id','=','fgs_cdc_item.batchcard_id')
                //   ->leftjoin('fgs_product_stock_management','fgs_product_stock_management.stock_location_id','=','stock_location_id.stock_location_decrease')
                // ->leftjoin("fgs_product_stock_management", function ($join)  {
                //     $join->on("fgs_product_stock_management.batchcard_id", "=", "fgs_cdc_item.batchcard_id");
                //     $join->where("fgs_product_stock_management.stock_location_id", "=", "stock_location_id.stock_location_decrease");
                // })
                 ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','delivery_challan_item.mrn_item_id')
                ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_cdc_item.product_id')
                ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cdc_item.batchcard_id')
                ->where($condition)
                //   ->where('fgs_product_stock_management.product_id','=','fgs_cdc_item.product_id')
                //  ->where('fgs_product_stock_management.batchcard_id','=','fgs_cdc_item.batchcard_id')
                ->orderBy('fgs_cdc_item.id', 'ASC')
                ->distinct('fgs_product_stock_management.batchcard_id')
                ->get();
        }
        function getCDCItems($condition)
{
    return $this->select(
        'fgs_cdc_item.*',
        'fgs_item_master.sku_code',
        'fgs_item_master.discription',
        'fgs_item_master.hsn_code',
        'batchcard_batchcard.batch_no',
        'fgs_cdc.cdc_number',
        'fgs_cdc.cdc_date',
        'fgs_oef.oef_number',
        'fgs_oef.oef_date',
        'fgs_mrn_item.manufacturing_date as mrn_manf',
        'fgs_mrn_item.expiry_date as mrn_exp',
        'delivery_challan.doc_no',
        'delivery_challan.doc_date',
        'delivery_challan.ref_no',
        'delivery_challan.ref_date',
        'customer_supplier.firm_name',
        'transaction_type.transaction_name',
        'delivery_challan.transaction_condition',
        'stock_location_decrease.location_name as location_decrease',
        'stock_location_increase.location_name as location_increase'
    )
    ->leftJoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
    ->leftJoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
    ->leftJoin('delivery_challan_item', 'delivery_challan_item.id', '=', 'fgs_cdc_item.dc_item_id')
    ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
    ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
    ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'delivery_challan.oef_id')
    ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
    ->leftJoin('transaction_type', 'transaction_type.id', '=', 'delivery_challan.transaction_type')
    ->leftJoin('product_stock_location as stock_location_decrease', 'stock_location_decrease.id', '=', 'delivery_challan.stock_location_decrease')
    ->leftJoin('product_stock_location as stock_location_increase', 'stock_location_increase.id', '=', 'delivery_challan.stock_location_increase')
    ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'delivery_challan_item.mrn_item_id')
    ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_cdc_item.product_id')
    ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cdc_item.batchcard_id')
    ->where($condition)
    ->orderBy('fgs_cdc_item.id', 'DESC')
    ->distinct('fgs_cdc_item.id') // Use CDC item ID for unique rows
    ->paginate(15);
}

        function getManualItems($condition)
        {
            return $this->select('fgs_cdc_item.*', 'fgs_item_master.sku_code', 'fgs_item_master.discription', 'fgs_item_master.hsn_code', 'batchcard_batchcard.batch_no', 'fgs_cdc.cdc_number',
             'fgs_product_stock_management.manufacturing_date as mrn_manf','fgs_product_stock_management.expiry_date as mrn_exp')
                ->leftjoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
                ->leftjoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
                ->leftjoin('fgs_product_stock_management','fgs_product_stock_management.batchcard_id','=','fgs_cdc_item.batchcard_id')
                 //->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','delivery_challan_item.mrn_item_id')
                ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_cdc_item.product_id')
                ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cdc_item.batchcard_id')
                ->where($condition)
                ->orderBy('fgs_cdc_item.id', 'ASC')
                ->distinct('fgs_product_stock_management.batchcard_id')
                ->get();
        }
        function getItemsReport($condition)
        {
        return $this->select('fgs_cdc_item.quantity','fgs_cdc.cdc_number','fgs_cdc.cdc_date','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','delivery_challan.doc_no',
            'delivery_challan.doc_date','fgs_oef.oef_number','fgs_oef.oef_date','delivery_challan.ref_no','delivery_challan.ref_date',
            'customer_supplier.firm_name','delivery_challan.transaction_condition','fgs_product_category.category_name','transaction_type.transaction_name',
            'stock_location_decrease.location_name as location_decrease','stock_location_increase.location_name as location_increase','zone.zone_name','state.state_name')
                        ->leftjoin('fgs_cdc_item_rel','fgs_cdc_item_rel.item','=', 'fgs_cdc_item.id')
                        ->leftjoin('fgs_cdc','fgs_cdc.id','=','fgs_cdc_item_rel.master')
                        ->leftjoin('delivery_challan_item','delivery_challan_item.id','=', 'fgs_cdc_item.dc_item_id')
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
                        ->orderBy('delivery_challan_item.id','ASC')
                        ->distinct('fgs_cdc_item.id')
                        ->paginate(15);
    }
    function getAllItemsReport($condition)
    {
        return $this->select('fgs_cdc_item.quantity','fgs_cdc.cdc_number','fgs_cdc.cdc_date','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','delivery_challan.doc_no',
            'delivery_challan.doc_date','fgs_oef.oef_number','fgs_oef.oef_date','delivery_challan.ref_no','delivery_challan.ref_date',
            'customer_supplier.firm_name','delivery_challan.transaction_condition','fgs_product_category.category_name','transaction_type.transaction_name',
            'stock_location_decrease.location_name as location_decrease','stock_location_increase.location_name as location_increase','zone.zone_name','state.state_name')
                        ->leftjoin('fgs_cdc_item_rel','fgs_cdc_item_rel.item','=', 'fgs_cdc_item.id')
                        ->leftjoin('fgs_cdc','fgs_cdc.id','=','fgs_cdc_item_rel.master')
                        ->leftjoin('delivery_challan_item','delivery_challan_item.id','=', 'fgs_cdc_item.dc_item_id')
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
                        ->orderBy('delivery_challan_item.id','desc')
                        ->distinct('fgs_cdc_item.id')
                        ->get();
    }


    }

