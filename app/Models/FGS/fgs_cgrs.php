<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_cgrs extends Model
{
    protected $table = 'fgs_cgrs';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_all_grs($condition)
    {
        return $this->select('fgs_grs.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1',
        'stock_location.location_name as location_name2','fgs_oef.oef_number','customer_supplier.firm_name', 'fgs_oef.order_number','fgs_oef.order_date')
            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
            ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
            ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
            ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
            ->where($condition)
            ->orderBy('fgs_grs.id','DESC')
            ->distinct('fgs_grs.id')
            ->paginate(15);
    }
    function get_all_grs_for_pi($condition)
    {
        return $this->select('fgs_grs.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1',
        'stock_location.location_name as location_name2','fgs_oef.oef_number','customer_supplier.firm_name')
            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
            ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
            ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
            ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
            ->whereNotIn('fgs_grs.id',function($query) {

                $query->select('fgs_pi_item.grs_id')->from('fgs_pi_item');
            
            })
            ->where($condition)
            ->orderBy('fgs_grs.id','DESC')
            ->distinct('fgs_grs.id')
            ->get();
    }
    function get_single_cgrs($condition)
    {
        return $this->select('fgs_grs.*','fgs_product_category.category_name','fgs_product_category_new.category_name as new_category_name','product_stock_location.location_name as location_name1','fgs_oef.remarks as oef_remarks',
        'stock_location.location_name as location_name2','fgs_oef.oef_number','fgs_oef.oef_date','order_fulfil.order_fulfil_type','fgs_oef.order_number','fgs_oef.order_date',
        'transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.pan_number','customer_supplier.gst_number',
        'customer_supplier.shipping_address','customer_supplier.billing_address','customer_supplier.sales_type','customer_supplier.contact_person',
        'customer_supplier.sales_type','customer_supplier.city','customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email',
        'currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3','fgs_cgrs.cgrs_number','fgs_cgrs.cgrs_date')
            ->leftJoin('fgs_grs','fgs_grs.id','fgs_cgrs.grs_id')
            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', 'fgs_grs.new_product_category')
            ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
            ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
            ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
            ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
            ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
            ->leftJoin('zone','zone.id','=','customer_supplier.zone')
            ->leftJoin('state','state.state_id','=','customer_supplier.state')
            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
            ->where($condition)
            ->where('fgs_cgrs.status','=',1)
            ->first();
    }
}
