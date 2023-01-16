<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class batchcard_material extends Model
{
    protected $table = 'batchcard_materials';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data)
    {
        return $this->insertGetId($data);  
    }
}
