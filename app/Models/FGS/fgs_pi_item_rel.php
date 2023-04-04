<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_pi_item_rel extends Model
{
    protected $table = 'fgs_pi_item_rel';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
