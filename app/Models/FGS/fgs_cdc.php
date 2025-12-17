<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_cdc extends Model
{
    //use HasFactory;
    protected $table = 'fgs_cdc';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data)
    {
        return $this->insertGetId($data);
    }
    function get_single_cdc($condition)
    {
        return $this->select(
            'delivery_challan.*',
            'fgs_cdc.cdc_number',
            'fgs_cdc.cdc_date',
            'fgs_cdc.id as cdc_id',
            'fgs_cdc.ref_no',
            'fgs_cdc.ref_date',
            'fgs_cdc.product_category',
            'fgs_cdc.transaction_type',
            'fgs_cdc.stock_location_increase',
            'fgs_cdc.transaction_condition',
            'customer_supplier.firm_name',
            'customer_supplier.pan_number',
            'customer_supplier.gst_number',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
            'customer_supplier.sales_type',
            'customer_supplier.contact_person',
            'customer_supplier.payment_terms',
            'customer_supplier.sales_type',
            'customer_supplier.city',
            'customer_supplier.contact_number',
            'customer_supplier.designation',
            'customer_supplier.email',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_stock_location.location_name as location_name1',
            'stock_location.location_name as location_name2',

            'zone.zone_name',
            'state.state_name',
            'customer_supplier.dl_number1',
            'customer_supplier.dl_number2',
            'customer_supplier.dl_number3',
            'fgs_cdc.cdc_date',
            
        )
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'fgs_cdc.dc_id')
            ->leftJoin('fgs_product_category','fgs_product_category.id','delivery_challan.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', 'delivery_challan.new_product_category')
            ->leftJoin('product_stock_location','product_stock_location.id','delivery_challan.stock_location_decrease')
            ->leftJoin('product_stock_location as stock_location','stock_location.id','delivery_challan.stock_location_increase')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_cdc.customer_id')
            ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            ->where($condition)
            ->where('fgs_cdc.status', '=', 1)
            ->first();
    }
}
