<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role_permission_rel extends Model
{
    protected $table = 'role_permission_rel';

    protected $primary_key = 'id';

    protected $guarded = [];

    public $timestamps = false;
   
    function delete_permission($role_id){
      return  $this->where('role_id', $role_id)->delete();
    }
   
    function insert_permission($data){
      return  $this->insert($data);
    }
    function select_permission($data){
        return $this->select(['*'])
        ->where($data)
        ->first();
      }
}
