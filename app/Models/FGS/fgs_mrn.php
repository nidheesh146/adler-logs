<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_mrn extends Model
{
    protected $table = 'fgs_mrn';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    // added sreya

    function get_single_mrn($condition)
    {
        return $this->select('fgs_mrn.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1',
        'customer_supplier.firm_name','customer_supplier.pan_number','customer_supplier.gst_number','product_stock_location.id as location_id',
        'customer_supplier.shipping_address','customer_supplier.billing_address','customer_supplier.sales_type','customer_supplier.contact_person','customer_supplier.sales_type','customer_supplier.city',
        'customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email','currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_mrn.product_category')
                    ->leftJoin('product_stock_location','product_stock_location.id','fgs_mrn.stock_location')
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_mrn.supplier_doc_number')
                    ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                    ->leftJoin('state','state.state_id','=','customer_supplier.state')
                    ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                    ->where($condition)
                    ->distinct('fgs_mrn.id')
                    ->first();
    }
}
