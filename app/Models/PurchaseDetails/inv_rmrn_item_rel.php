<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_rmrn_item_rel extends Model
{
    protected $table = 'inv_rmrn_item_rel';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
