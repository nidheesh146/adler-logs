<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_grs extends Model
{
    protected $table = 'fgs_grs';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_all_grs($condition)
    {
        return $this->select('fgs_grs.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1',
        'stock_location.location_name as location_name2','fgs_oef.oef_number','customer_supplier.firm_name')
            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
            ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
            ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
            ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
            ->where($condition)
            ->orderBy('fgs_grs.id','DESC')
            ->distinct('fgs_grs.id')
            ->paginate(15);
    }
    function get_all_grs_for_pi($condition)
    {
        return $this->select('fgs_grs.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1',
        'stock_location.location_name as location_name2','fgs_oef.oef_number','customer_supplier.firm_name')
            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
            ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
            ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
            ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
            ->where($condition)
            ->orderBy('fgs_grs.id','DESC')
            ->distinct('fgs_grs.id')
            ->get();
    }
}
