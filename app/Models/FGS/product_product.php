<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_product extends Model
{
    protected $table = 'product_product';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
}
