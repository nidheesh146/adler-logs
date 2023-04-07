<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer_supplier extends Model
{
    protected $table = 'customer_supplier';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_all($condition)
    {
        return $this->select('customer_supplier.*')
                    ->where($condition)
                    ->where('customer_supplier.is_active','=',1)
                    ->orderby('customer_supplier.id','desc')
                    ->paginate(15);

    }
    function get_customer_data($condition)
    {
        return $this->select(['customer_supplier.id','customer_supplier.firm_name as text','customer_supplier.billing_address',
        'customer_supplier.shipping_address','zone.zone_name'])
                    ->leftjoin('zone','zone.id','=','customer_supplier.zone')
                    ->where('firm_name','like','%'.$condition.'%')
                    ->get()->toArray();
    }
    function get_domestic_customer_data($condition)
    {
        return $this->select(['customer_supplier.id','customer_supplier.firm_name as text','customer_supplier.billing_address',
        'customer_supplier.shipping_address','zone.zone_name'])
                    ->leftjoin('zone','zone.id','=','customer_supplier.zone')
                    ->where('zone.zone_name','!=','Export')
                    ->where('firm_name','like','%'.$condition.'%')
                    ->get()->toArray();
    }
    function get_export_customer_data($condition)
    {
        return $this->select(['customer_supplier.id','customer_supplier.firm_name as text','customer_supplier.billing_address',
        'customer_supplier.shipping_address','zone.zone_name'])
                    ->leftjoin('zone','zone.id','=','customer_supplier.zone')
                    ->where('zone.zone_name','=','Export')
                    ->where('firm_name','like','%'.$condition.'%')
                    ->get()->toArray();
    }
}
