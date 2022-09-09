<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_purchase_req_master_item_rel extends Model
{
    use HasFactory;
    protected $table = 'inv_purchase_req_master_item_rel';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
