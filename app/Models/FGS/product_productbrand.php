<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_productbrand extends Model
{
    protected $table = 'product_productbrand';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
}
