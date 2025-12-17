<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_dni extends Model
{
    protected $table = 'fgs_dni';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_all_dni($condition)
    {
        return $this->select('fgs_dni.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address','zone.zone_name')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_dni.customer_id')
            ->leftJoin('zone','zone.id','customer_supplier.zone')
            ->where($condition)
            ->orderBy('fgs_dni.id','DESC')
            ->where('status','=',1)
            ->distinct('fgs_dni.id')
            ->paginate(15);
    }
    function get_all_dni_for_label($condition)
    {
        return $this->select('fgs_dni.id','fgs_dni.dni_number as doc_number','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address','zone.zone_name')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_dni.customer_id')
            ->leftJoin('zone','zone.id','customer_supplier.zone')
            ->where($condition)
            ->orderBy('fgs_dni.id','DESC')
            ->where('status','=',1)
            ->distinct('fgs_dni.id')
            ->get();
    }
    function get_single_dni($condition)
    {
        return $this->select('fgs_dni.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address','zone.zone_name','customer_supplier.contact_person',
        'customer_supplier.sales_type','customer_supplier.city','customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email','customer_supplier.payment_terms',
        'currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3','customer_supplier.gst_number',
        'fgs_oef.dummy_billing_address','fgs_oef.dummy_shipping_address','fgs_product_category_new.category_name as new_category_name' )
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_dni.customer_id')
            ->leftJoin('zone','zone.id','customer_supplier.zone')
            ->leftJoin('state','state.state_id','=','customer_supplier.state')
            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
            ->leftjoin('fgs_dni_item_rel','fgs_dni_item_rel.master','=','fgs_dni.id')
            ->leftjoin('fgs_dni_item','fgs_dni_item_rel.item','=','fgs_dni_item.id')
            ->leftjoin('fgs_pi_item','fgs_pi_item.id','=','fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')// Join fgs_grs table
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_grs.new_product_category') // Join for new product category
            ->where($condition)
            ->where('fgs_dni.status','=',1)
            ->first();
    }
    function find_dni_num_for_srn($condition)
    {
        return $this->select(['fgs_dni.dni_number as text', 'fgs_dni.id'])
            ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.master','=','fgs_dni.id')
            ->leftJoin('fgs_dni_item','fgs_dni_item.id','=','fgs_dni_item_rel.item')
            ->where($condition)
            // ->whereNotIn('fgs_dni.id', function ($query) {

            //     $query->select('fgs_srn.dni_id')->from('fgs_srn')->where('fgs_srn.status', '=', 1);
            // })
            ->where('fgs_dni_item.remaining_qty_after_srn','!=',0)
            ->where('fgs_dni.status', '=', 1)
            ->distinct('fgs_dni.dni_number')
            ->orderBy('fgs_dni.id','DESC')
            ->get();
    }
    

}
