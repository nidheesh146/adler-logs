<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assembly_batchcards extends Model
{
    protected $table = 'assembly_batchcards';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
