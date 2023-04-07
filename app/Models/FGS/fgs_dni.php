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
    

}
