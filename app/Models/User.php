<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class User extends Model
{
           
    protected $table = 'user';

    protected $primary_key = 'user_id';

    protected $guarded = [];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('user.status', function (Builder $builder) {
            $builder->where('user.status', '!=', 2);
        });
    }
    function login($condition){
         return  $this->select(['user_id','status'])->where($condition)->first();
    }
    function get_user_details($condition){
        return  $this->select(['user_id','email','username','f_name','l_name','phone','employee_id','date_of_hire','address','department.dept_name as department','user.status as status'])
                    ->leftjoin('department','department.id','=','user.department')
                    ->where($condition)
                    ->first();
   }

}
