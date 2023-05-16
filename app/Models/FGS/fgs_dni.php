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
            ->distinct('fgs_dni.id')
            ->paginate(15);
    }
    function get_single_dni($condition)
    {
        return $this->select('fgs_dni.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address','zone.zone_name','customer_supplier.contact_person',
        'customer_supplier.sales_type','customer_supplier.city','customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email',
        'currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_dni.customer_id')
            ->leftJoin('zone','zone.id','customer_supplier.zone')
            ->leftJoin('state','state.state_id','=','customer_supplier.state')
            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
            ->where($condition)
            ->where('fgs_dni.status','=',1)
            ->first();
    }
    function find_dni_num_for_srn($condition)
    {
        return $this->select(['fgs_dni.dni_number as text','fgs_dni.id'])
        ->where($condition)
        ->whereNotIn('fgs_dni.id',function($query) {

            $query->select('fgs_srn.dni_id')->from('fgs_srn')->where('fgs_srn.status','=',1);
        
        })->where('fgs_dni.status','=',1)
        ->get();
    }
    

}
