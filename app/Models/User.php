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
        return  $this->select(['user_id','email','username','f_name','l_name','phone','employee_id','date_of_hire','address','department.dept_name as department','user.status as status','user.profile_img','user.role_permission'])
                    ->leftjoin('department','department.id','=','user.department')
                    ->where($condition)
                    ->first();
   }
   function get_all_users($condition){
        return  $this->select(['user_id','email','username','f_name','l_name','employee_id', 'dept_name', 'designation','phone','email'])
                    ->leftjoin('department','id','=','user.department')
                    ->where($condition)
                    ->where(['user.status'=>1])
                    ->get();
   }
   function all_users($condition){
        return  $this->select(['user_id','email','username','f_name','l_name','employee_id', 'dept_name', 'designation','phone','email','date_of_hire'])
                    ->leftjoin('department','id','=','user.department')
                    ->where($condition)
                    ->where(['user.status'=>1])
                    ->orderby('user_id','desc')
                    ->paginate(15);
   }

   function insert_data($data)
   {
        return $this->insertGetId($data);
   }
   function update_data($condition,$data)
   {
        return $this->where($condition)->update($data);
   }

   function get_user($condition) {
    return  $this->select(['user_id','email','username','f_name','l_name','employee_id','address' ,'department', 'designation','phone','email','date_of_hire','role_permission'])
        //->leftjoin('department','id','=','user.department')
        ->where(['status'=>1])
        ->where($condition)
        ->first();
   }

}
