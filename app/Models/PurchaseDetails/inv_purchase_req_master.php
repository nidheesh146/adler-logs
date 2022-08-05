<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class inv_purchase_req_master extends Model
{
    protected $table = 'inv_purchase_req_master';
    protected $primary_key = 'master_id';
    protected $guarded = [];
    public $timestamps = false;
   
    protected static function booted()
    {
        static::addGlobalScope('inv_purchase_req_master.status', function (Builder $builder) {
            $builder->where('inv_purchase_req_master.status', '!=', 2);
        });
    }
    function insertdata($data){
        return $this->insertGetId($data);
    }
    function updatedata($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_count($condition){
        return $this->where($condition)->count();
    }
    function get_data($condition){
        return $this->select(['master_id','pr_no','requestor_id','inv_purchase_req_master.department','date','PR_SR','f_name','l_name'])
           ->join('user','user.user_id','=','inv_purchase_req_master.requestor_id')
           ->where($condition)->first();
    }
    function get_inv_purchase_req_master_list(){
        return $this->select(['master_id','pr_no','requestor_id','inv_purchase_req_master.department','date','PR_SR','f_name','l_name','department.dept_name'])
           ->leftjoin('user','user.user_id','=','inv_purchase_req_master.requestor_id')
           ->leftjoin('department','department.id','=','inv_purchase_req_master.department')
           ->orderby('master_id','desc')
           ->paginate(15);
    }
}
