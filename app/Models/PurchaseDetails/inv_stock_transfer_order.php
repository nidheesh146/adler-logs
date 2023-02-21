<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_stock_transfer_order extends Model
{
    protected $table = 'inv_stock_transfer_order';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function deleteData($condition)
    {
        return  $this->where($condition)->delete();
    }


    function get_all_data($condition)
    {
        return $this->select(['inv_stock_transfer_order.*','user.f_name','user.l_name'])
        ->leftjoin('user','user.user_id','=','inv_stock_transfer_order.created_user')
        ->where($condition)
        ->where('inv_stock_transfer_order.status','=',1)
        ->orderby('inv_stock_transfer_order.id','desc')
        ->paginate(15);
    }

}
