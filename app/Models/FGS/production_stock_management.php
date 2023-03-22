<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class production_stock_management extends Model
{
    protected $table = 'production_stock_management';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
}
