<?php

namespace App\Models\FGS;

use App\Models\PurchaseDetails\customer_supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_oef extends Model
{
    use HasFactory;

    protected $table = 'fgs_oef';
    protected $primaryKey = 'id'; // Correct property name
    protected $guarded = []; // Allow mass assignment
    public $timestamps = false;

    // Insert data and get the inserted ID
    public function insert_data(array $data)
    {
        return $this->insertGetId($data);
    }

    // Update data based on condition
    public function update_data(array $condition, array $data)
    {
        return $this->where($condition)->update($data);
    }
    public function find_oef_num_for_grs(array $condition)
{
    return $this->select('fgs_oef.oef_number as text', 'fgs_oef.id')
        ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.master', '=', 'fgs_oef.id')
        ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_oef_item_rel.item')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
         ->where('fgs_oef_item.quantity_to_allocate', '!=', 0)
        ->where('fgs_oef.status', '=', 1)
         ->where('fgs_oef_item.coef_status', '=', 0)
         ->where('fgs_oef_item.status', '=', 1)
        ->where($condition)
        // ->when(function ($query) {
        //     // Remove or keep based on your exact requirement
        //     $query->whereNull('customer_supplier.dl_expiry_date')
        //           ->orWhere('customer_supplier.dl_expiry_date', '=', '0000-00-00');
        // })
        ->distinct('fgs_oef.id')
        ->get();
}

    

    
       

    // Get a single OEF with related data
    public function get_single_oef(array $condition)
    {
        return $this->select(
            'fgs_oef.*',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'customer_supplier.firm_name',
            'customer_supplier.pan_number',
            'customer_supplier.gst_number',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
            'customer_supplier.sales_type',
            'customer_supplier.contact_person',
            'customer_supplier.city',
            'customer_supplier.payment_terms',
            'customer_supplier.contact_number',
            'customer_supplier.designation',
            'customer_supplier.email',
            'currency_exchange_rate.currency_code',
            'zone.zone_name',
            'state.state_name',
            'customer_supplier.dl_number1',
            'customer_supplier.dl_number2',
            'customer_supplier.dl_number3',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name'
        )
        ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
        ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
        ->leftJoin('fgs_product_category_new','fgs_product_category_new.id','fgs_oef.new_product_category')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
        ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
        ->leftJoin('currency_exchange_rate', 'currency_exchange_rate.currency_id', '=', 'customer_supplier.currency')
        ->where($condition)
        ->distinct('fgs_oef.id')
        ->first();
    }

    // Find OEF data with conditions
    public function find_oef_datas(array $condition)
    {
        return $this->select(
            'fgs_oef.*',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            'fgs_product_category.category_name'
        )
        ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
        ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        ->where($condition)
        ->where('fgs_oef.status', '=', 1)
        ->first();
    }

    // Find OEF numbers for COEF with conditions
    public function find_oef_num_for_coef(array $condition)
    {
        return $this->select('fgs_oef.oef_number as text', 'fgs_oef.id')
            ->where($condition)
            ->where('fgs_oef.status', '=', 1)
            ->get();
    }

    // Get master data with related information
    public function get_master_data(array $condition)
    {
        return $this->select(
            'fgs_oef.*',
            'order_fulfil.*',
            'transaction_type.*',
            'customer_supplier.*',
            'fgs_product_category.category_name'
        )
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
        ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
        ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        ->where($condition)
        ->first();
    }
}
