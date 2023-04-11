<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_oef extends Model
{
    protected $table = 'fgs_oef';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function find_oef_num_for_grs($condition)
    {
        return $this->select(['fgs_oef.oef_number as text','fgs_oef.id'])
                ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.master','=', 'fgs_oef.id')
                ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_oef_item_rel.item')
                ->where('fgs_oef_item.quantity_to_allocate','!=',0)
                ->where('fgs_oef.status','=',1)
                ->whereNotIn('fgs_oef.id',function($query) {

                    $query->select('fgs_grs.oef_id')->from('fgs_grs');
                
                })
                ->where($condition)
                ->distinct('fgs_oef.id')
                ->get();
    }
    function get_single_oef($condition)
    {
        return $this->select('fgs_oef.*','order_fulfil.order_fulfil_type','transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.pan_number','customer_supplier.gst_number',
        'customer_supplier.shipping_address','customer_supplier.billing_address','customer_supplier.sales_type','customer_supplier.contact_person','customer_supplier.sales_type','customer_supplier.city',
        'customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email','currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3')
                    ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                    ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                    ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                    ->leftJoin('state','state.state_id','=','customer_supplier.state')
                    ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                    ->where($condition)
                    ->distinct('fgs_oef.id')
                    ->first();
    }
}
