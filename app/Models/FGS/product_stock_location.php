<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_stock_location extends Model
{
    protected $table = 'product_stock_location';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

     function get_location($loc_id){
        return $this->select(['*'])
       ->where('id',$loc_id)
        ->first();
    }
    public function get_locations(){
        return $this->select(['id','location_name'])
                    ->get();
    }

    function insert_location($data){
        return $this->insert($data);   
    }
    function update_location($data,$loc_id){
        return $this->where('id',$loc_id)->update($data);
    }
    function delete_location($loc_id){
        return $this->where('id', $loc_id)->delete();
    }
}
