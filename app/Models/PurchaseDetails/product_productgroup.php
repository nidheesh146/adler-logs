<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_productgroup extends Model
{
    protected $table = 'product_productgroup';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
}
