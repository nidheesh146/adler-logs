<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_product_stock extends Model
{
    protected $table = 'fgs_product_stock';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
