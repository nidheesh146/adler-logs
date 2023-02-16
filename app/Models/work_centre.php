<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class work_centre extends Model
{
    use HasFactory;
    protected $table = 'work_centre';
    protected $primary_key = 'id';
    protected $guarded = [];
}
