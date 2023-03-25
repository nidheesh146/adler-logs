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
        return $this->select(['id','firm_name as text','billing_address','shipping_address'])
                    ->where('firm_name','like','%'.$condition.'%')
                    ->get()->toArray();
    }
}
