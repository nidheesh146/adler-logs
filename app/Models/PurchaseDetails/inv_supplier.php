<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\Builder;
class inv_supplier extends Model
{
    protected $table = 'inv_supplier';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('inv_supplier.status', function (Builder $builder) {
            $builder->where('inv_supplier.status', '!=', 2);
        });
    }

    function get_supplier_data($data){
        return $this->select(['id',DB::raw("CONCAT(vendor_name) as text")])
                    ->where(function ($query) use ($data) {
                        $query->where([['vendor_id','like','%'.$data.'%']])
                            ->orWhere([['vendor_name','like','%'.$data.'%']]);
                    })->get()->toArray();
    }
    function get_supplier($condition){
        return $this->where($condition)->first();
    }
    function get_suppliers($condition){
        return $this->select('*')
        ->where($condition)
        ->orderBy('inv_supplier.id','desc')
        ->paginate(15);
    }

    function get_all_suppliers()
    {
        return $this->select('id','vendor_id', 'vendor_name')->get();
    }
    function updatedata($condition, $data)
    {
        return $this->where($condition)->update($data);
    }
    function insert_data($data){
        return $this->insertGetId($data);
    }
}
