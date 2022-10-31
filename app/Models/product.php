<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $table = 'product_product';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
     
    function get_product_data($data){
        return $this->select(['id','sku_code as text'])
                    ->where('sku_code','like','%'.$data.'%')
                    ->get()->toArray();
        
    }
    function get_label_filter($condition){
        return $this->select(['sku_code','mrp'])
                    ->where($condition)
                    ->get();
        
    }



    

}
