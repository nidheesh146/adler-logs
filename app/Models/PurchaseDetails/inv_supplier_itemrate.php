<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_supplier_itemrate extends Model
{
    protected $table = 'inv_supplier_itemrate';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
