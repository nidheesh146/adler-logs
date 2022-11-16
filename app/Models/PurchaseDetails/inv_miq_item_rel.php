<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_miq_item_rel extends Model
{
    protected $table = 'inv_miq_item_rel';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
