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

    public function get_roles(){
        return $this->select(['role_id','role_name'])
                    ->get();
    }
}
