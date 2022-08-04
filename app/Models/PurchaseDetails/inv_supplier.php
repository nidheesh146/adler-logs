<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Model;
use DB;
class inv_supplier extends Model
{
    protected $table = 'inv_supplier';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function get_supplier_data($data){
        return $this->select(['id',DB::raw("CONCAT(vendor_id,' - ',vendor_name) as text")])
                    ->where(function ($query) use ($data) {
                        $query->where([['vendor_id','like','%'.$data.'%']])
                            ->orWhere([['vendor_name','like','%'.$data.'%']]);
                    })->get()->toArray();



    }

}
