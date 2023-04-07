<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    function get_all_pi_for_dni($condition)
    {
        return $this->select('fgs_pi.*','customer_supplier.firm_name')
            // ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
            // ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
            // ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
            //->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
            ->whereNotIn('fgs_pi.id',function($query) {

                $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
            
            })
            ->where($condition)
            ->orderBy('fgs_pi.id','DESC')
            ->distinct('fgs_pi.id')
            ->get();
    }
}
