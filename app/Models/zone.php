<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class zone extends Model
{
    protected $table = 'zone';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function get_zones($condition){
        return $this->where($condition)->get();
    }
}
