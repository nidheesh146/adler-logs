<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $table = 'permissions';
    protected $primary_key = 'permission_id';
    protected $guarded = [];

    function get_modules()
    {
        return $this->select(['permission_id','per_module'])
                ->get();
    }
}
