<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_mtq extends Model
{
    protected $table = 'fgs_mtq';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_single_mtq($condition) 
    {
        return $this->select('fgs_mtq.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1', 'stock_location.location_name as location_name2')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_mtq.product_category_id')
                    ->leftJoin('product_stock_location','product_stock_location.id','fgs_mtq.stock_location_id1')
                    ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_mtq.stock_location_id2')
                    ->where($condition)
                    ->distinct('fgs_mtq.id')
                    ->first();
    }
    function get_all_mtq($condition) 
    {
        return $this->select('fgs_mtq.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1','stock_location.location_name as location_name2')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_mtq.product_category_id')
                    ->leftJoin('product_stock_location','product_stock_location.id','fgs_mtq.stock_location_id1')
                    ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_mtq.stock_location_id2')
                    ->where($condition)
                    ->where('fgs_mtq.status','=',1)
                    ->distinct('fgs_mtq.id')
                    ->paginate(15);
    }
    function find_mtq_num_for_mis($condition)
    {
        return $this->select(['fgs_mtq.mtq_number as text','fgs_mtq.id'])
        ->where($condition)
        ->whereNotIn('fgs_mtq.id',function($query) {

            $query->select('fgs_mis.mtq_id')->from('fgs_mis')->where('fgs_mis.status','=',1);
        
        })->where('fgs_mtq.status','=',1)
        ->get();
    }
    function get_master_data($condition)
    {
        return $this->select('fgs_mtq.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1','stock_location.location_name as location_name2')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_mtq.product_category_id')
                    ->leftJoin('product_stock_location','product_stock_location.id','fgs_mtq.stock_location_id1')
                    ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_mtq.stock_location_id2')
                    ->where($condition)
                    ->where('fgs_mtq.status','=',1)
                    ->distinct('fgs_mtq.id')
                    ->first();
    }
    
}
