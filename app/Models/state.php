<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class state extends Model
{
    protected $table = 'state';
    protected $primary_key = 'state_id';
    protected $guarded = [];
    public $timestamps = false;

    function get_states($condition){
        return $this->where($condition)->get();
    }
}
