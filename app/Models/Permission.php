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
        return $this->select(['per_module'])
                //->groupBy('permissions.per_module')
                ->distinct('permissions.per_module')
                ->get()->toArray();
    }

    function get_permission(){
        
        return $this->select(['*'])
        //->join('permission_type_rel','permission_type_rel.permission_id','=','permissions.permission_id')
        //->where('type_id',$type_id)
        ->orderBy('permissions.per_order', 'asc')
        ->get()->toArray();
    }
    
}
