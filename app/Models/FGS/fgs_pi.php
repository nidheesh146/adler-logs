<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class fgs_pi extends Model
{
    protected $table = 'fgs_pi';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    public function get_all_pi_for_dni($condition)
    {
        return $this->select(
                'fgs_pi.*',
                'customer_supplier.firm_name',
                'fgs_product_category_new.category_name as new_category_name', // New category name if available
               // 'fgs_product_category.category_name as category_name' // Original category name if no new category
            )
            ->leftJoin('fgs_cpi', 'fgs_cpi.pi_id', '=', 'fgs_pi.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
            ->leftJoin('fgs_multiple_pi', 'fgs_multiple_pi.id', '=', 'fgs_pi.merged_pi_id')
            ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.master', '=', 'fgs_pi.id') // Join with PI item relation
            ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_pi_item_rel.item') // Join with PI item
            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id') // Join with GRS
            //->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_grs.product_category') // Original product category
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_grs.new_product_category') // New product category (related to GRS)
            ->whereNotIn('fgs_pi.id', function($query) {
                $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
            })
            ->where($condition)
            ->where('fgs_pi.status', '=', 1)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('fgs_pi_item_rel')
                    ->join('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_pi_item_rel.item')
                    ->whereRaw('fgs_pi_item_rel.master = fgs_pi.id')
                    ->where('fgs_pi_item.cpi_status', '!=', 1);
            })
                        ->orderBy('fgs_pi.id', 'DESC')
            ->distinct('fgs_pi.id')
            ->get();
    }
    function get_all_pi_for_label($condition)
    {
        return $this->select('fgs_pi.*','fgs_pi.pi_number as doc_number','customer_supplier.firm_name')
            // ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
            // ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
            // ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
            //->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
            ->where($condition)
            ->where('fgs_pi.status','=',1)
            ->orderBy('fgs_pi.id','DESC')
            ->distinct('fgs_pi.id')
            ->get();
    }

    function get_single_pi($condition)
    {
        return $this->select('fgs_pi.*','fgs_oef.oef_number','fgs_oef.id as oef_id','fgs_oef.remarks as oef_remarks','fgs_oef.oef_date','order_fulfil.order_fulfil_type','fgs_oef.order_number','fgs_oef.order_date','fgs_grs.grs_number','fgs_grs.grs_date',
        'transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.pan_number','customer_supplier.gst_number','customer_supplier.payment_terms',
        'customer_supplier.shipping_address','customer_supplier.billing_address','customer_supplier.sales_type','customer_supplier.contact_person',
        'customer_supplier.sales_type','customer_supplier.city','customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email','fgs_product_category.category_name','fgs_product_category_new.category_name as new_category_name',
        'currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3',
        'fgs_oef.dummy_billing_address','fgs_oef.dummy_shipping_address')
            ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.master','=','fgs_pi.id')
            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
            ->leftJoin('fgs_grs','fgs_grs.id','fgs_pi_item.grs_id')
            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
            ->leftJoin('fgs_product_category_new','fgs_product_category_new.id','fgs_grs.new_product_category')
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
        return $this->select('fgs_pi.*','fgs_product_category.category_name','product_stock_location.location_name')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_pi.product_category')
                        ->leftJoin('product_stock_location','product_stock_location.id','fgs_pi.stock_location')
                        ->where($condition)
                          ->first();
    }
      function find_pi_datas($condition)
    {
        return $this->select(['fgs_pi.*'])
                    ->where($condition)
                    ->where('fgs_pi.status','=',1)
                    ->first();
    }

    function find_pi_num_for_cpi($condition)
    {
        return $this->select(['fgs_pi.pi_number as text','fgs_pi.id'])
        ->whereNotIn('fgs_pi.id',function($query) {
                $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
         })
        ->where('fgs_pi.status','=',1)
        ->where($condition)
        ->get();
    }
    function get_master_data($condition)
    {
        return $this->select(['fgs_pi.*','customer_supplier.firm_name','customer_supplier.city','zone.zone_name','state.state_name'])
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                    ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                    ->leftJoin('state','state.state_id','=','customer_supplier.state')
                    ->where($condition)
                    ->first();
    }
}
