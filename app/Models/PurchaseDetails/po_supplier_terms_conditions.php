<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;


class po_supplier_terms_conditions extends Model
{
    protected $table = 'po_supplier_terms_conditions';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
    protected static function booted()
    {
        static::addGlobalScope('po_supplier_terms_conditions.status', function (Builder $builder) {
            $builder->where('po_supplier_terms_conditions.status', '!=', 2);
        });
    }
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function updatedata($condition, $data)
    {
        return $this->where($condition)->update($data);
    }
    function get_data($condition){
        return $this->select('*')
        ->where($condition)
        ->orderby('po_supplier_terms_conditions.id','desc')
        ->paginate(15);
    }
    function single_get_data($condition){
        return $this->select('*')
        ->where($condition)
        ->first();
    }

}
