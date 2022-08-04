<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Department extends Model
{
    protected $table = 'department';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
    protected static function booted()
    {
        static::addGlobalScope('department.status', function (Builder $builder) {
            $builder->where('department.status', '!=', 2);
        });
    }
    function get_dept($condition){
        return $this->where($condition)->get();
    }
}
