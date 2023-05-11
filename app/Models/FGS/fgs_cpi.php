<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_cpi extends Model
{
    protected $table = 'fgs_cpi';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

  
    function get_single_cpi($condition)
    {
        return $this->select('fgs_pi.*','fgs_oef.oef_number','fgs_oef.oef_date','order_fulfil.order_fulfil_type','fgs_oef.order_number','fgs_oef.order_date','fgs_grs.grs_number','fgs_grs.grs_date',
        'transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.pan_number','customer_supplier.gst_number',
        'customer_supplier.shipping_address','customer_supplier.billing_address','customer_supplier.sales_type','customer_supplier.contact_person',
        'customer_supplier.sales_type','customer_supplier.city','customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email',
        'currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3','fgs_cpi.cpi_date') 
            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_cpi.pi_id')
            ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.master','=','fgs_pi.id')
            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
            ->leftJoin('fgs_grs','fgs_grs.id','fgs_pi_item.grs_id')
            ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
            ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
            ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
            ->leftJoin('zone','zone.id','=','customer_supplier.zone')
            ->leftJoin('state','state.state_id','=','customer_supplier.state')
            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
            ->where($condition)
            ->where('fgs_grs.status','=',1)
            ->first();
    }
     function find_pi_data($condition)
    {
        return $this->select('fgs_cpi.*','fgs_product_category.category_name','product_stock_location.location_name')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_cpi.product_category')
                        ->leftJoin('product_stock_location','product_stock_location.id','fgs_cpi.stock_location')
                        ->where($condition)
                          ->first();
    }
      function find_pi_datas($condition)
    {
        return $this->select(['fgs_cpi.*'])
                    ->where($condition)
                    ->where('fgs_cpi.status','=',1)
                    ->first();
    }
      function find_pi_num_for_cpi($condition) 
    {
        return $this->select(['fgs_cpi.pi_number as text','fgs_cpi.id'])->where($condition)
        ->where('fgs_cpi.status','=',1)
        ->where($condition)
        ->get();
    }
     function get_master_data($condition){
        return $this->select(['fgs_cpi.*'])
                    ->where($condition)
                    ->first();
    }
}
