<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class label_print_report extends Model
{
    protected $table = 'label_print_report';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
