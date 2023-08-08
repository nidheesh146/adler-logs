<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_srn extends Model
{
    protected $table = 'fgs_srn';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_single_srn($condition)
    {
        return $this->select('fgs_srn.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address','zone.zone_name','customer_supplier.contact_person',
        'customer_supplier.sales_type','customer_supplier.city','customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email','customer_supplier.gst_number',
        'currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3')
            ->leftJoin('fgs_dni','fgs_dni.id','=','fgs_srn.dni_id')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_dni.customer_id')
            ->leftJoin('zone','zone.id','customer_supplier.zone')
            ->leftJoin('state','state.state_id','=','customer_supplier.state')
            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
            ->where($condition)
            ->where('fgs_srn.status','=',1)
            ->first();
    }
    function get_all_srn($condition) 
    {
        return $this->select('fgs_srn.*','fgs_dni.dni_number','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address','zone.zone_name')
                    ->leftJoin('fgs_dni','fgs_dni.id','fgs_srn.dni_id')
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_dni.customer_id')
                    ->leftJoin('zone','zone.id','customer_supplier.zone')
                    ->orderBy('fgs_srn.id','DESC')
                    ->where($condition)
                    ->distinct('fgs_srn.id')
                    ->paginate(15);
    }
}
