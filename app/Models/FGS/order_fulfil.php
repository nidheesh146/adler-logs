<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_fulfil extends Model
{
    protected $table = 'order_fulfil';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
