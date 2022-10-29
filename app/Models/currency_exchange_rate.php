<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class currency_exchange_rate extends Model
{
    protected $table = 'currency_exchange_rate';
    protected $primary_key = 'currency_id';
    protected $guarded = [];
    public $timestamps = false;

    function get_currency($condition){
        return $this->where($condition)->get();
    }
}
