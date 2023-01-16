<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_stock_to_production_item extends Model
{
    protected $table = 'inv_stock_to_production_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
}
