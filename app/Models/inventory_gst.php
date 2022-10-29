<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inventory_gst extends Model
{
    protected $table = 'inventory_gst';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function get_gst(){
        return $this->get();
    }
    function get_single_gst($condition){
        return $this->where($condition)->first();
    }

}
