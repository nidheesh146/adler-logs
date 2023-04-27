<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_min extends Model
{
    protected $table = 'fgs_min';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_single_min($condition) 
    {
        return $this->select('fgs_min.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1')
        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_min.product_category')
            ->leftJoin('product_stock_location','product_stock_location.id','fgs_min.stock_location')
                  
                    ->where($condition)
                    ->distinct('fgs_min.id')
                    ->first();
    }

     function find_min_data($condition)
    {
        return $this->select('fgs_min.*','fgs_product_category.category_name','product_stock_location.location_name')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_min.product_category')
                        ->leftJoin('product_stock_location','product_stock_location.id','fgs_min.stock_location')
                        ->where($condition)
                          ->first();
    }
      function find_min_datas($condition)
    {
        return $this->select(['fgs_min.*'])
                    ->where($condition)
                    ->where('fgs_min.status','=',1)
                    ->first();
    }
      function find_min_num_for_cmin($condition)
    {
        return $this->select(['fgs_min.min_number as text','fgs_min.id'])->where($condition)
        ->where('fgs_min.status','=',1)
        ->where($condition)
        ->get();
    }
     function get_master_data($condition){
        return $this->select(['fgs_min.*','product_stock_location.location_name'])
              ->leftJoin('product_stock_location','product_stock_location.id','fgs_min.stock_location')
                    ->where($condition)
                    ->first();
    }
}
