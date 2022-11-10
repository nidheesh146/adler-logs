<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_miq_rel extends Model
{
    protected $table = 'inv_miq_rel';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
