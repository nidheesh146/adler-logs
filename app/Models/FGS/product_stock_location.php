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
}
