<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'role';
    protected $primary_key = 'role_id';
    protected $guarded = [];
    public $timestamps = false;

    function get_role($role_id){
        return $this->select(['*'])
       ->where('role_id',$role_id)
        ->first();
    }
    public function get_roles(){
        return $this->select(['role_id','role_name','role_description'])
                    ->get();
    }
    function insert_role($data){
        return $this->insert($data);   
    }
    function update_role($data,$role_id){
        return $this->where('role_id',$role_id)->update($data);
    }
    function delete_role($role_id){
        return $this->where('role_id', $role_id)->delete();
    }
}
