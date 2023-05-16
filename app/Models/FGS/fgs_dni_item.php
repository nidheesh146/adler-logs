<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_dni_item extends Model
{
    protected $table = 'fgs_dni_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function getItems($condition)
    {
        return $this->select('fgs_dni_item.*')
                ->leftjoin('fgs_dni_item_rel','fgs_dni_item_rel.item','=','fgs_dni_item.id')
                ->where($condition)
                ->distinct('fgs_dni_item.id')
                ->get();
    }
}
