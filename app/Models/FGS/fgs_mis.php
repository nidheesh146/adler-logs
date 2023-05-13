<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_mis extends Model
{
    protected $table = 'fgs_mis';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_all_mis($condition) 
    {
        return $this->select('fgs_mis.*','fgs_mtq.mtq_number','fgs_product_category.category_name','product_stock_location.location_name as location_name')
                    ->leftJoin('fgs_mtq','fgs_mtq.id','fgs_mis.mtq_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_mis.product_category_id')
                    ->leftJoin('product_stock_location','product_stock_location.id','fgs_mis.stock_location_id')
                    ->where($condition)
                    ->distinct('fgs_mis.id')
                    ->paginate(15);
    }
}
