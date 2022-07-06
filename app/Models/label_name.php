<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class label_name extends Model
{
       
    protected $table = 'label_name';

    protected $primary_key = 'id';

    protected $guarded = [];

    public $timestamps = false;

    function insert($data){
        return $this->insertGetId($data);
    }
    public function get_label_name($condition)
    {
        return $this->select(['*'])
        ->orderBy('label_name.id', 'DESC')
        ->paginate(11);
    }
    function get_single_data($condition){
        return $this->select(['*'])
        ->where($condition)
        ->first(); 
    }
    public function update_label_name($condition,$data)
    {
          return $this->where($condition)->update($data);
    }
}
