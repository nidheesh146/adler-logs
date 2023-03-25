<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction_type extends Model
{
    protected $table = 'transaction_type';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
