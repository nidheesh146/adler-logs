<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_lot_allocation extends Model
{
    protected $table = 'inv_lot_allocation';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insertdata($data){
        return $this->insertGetId($data);
    }

    function getdata(){
        return $this->paginate(10);
    }

}
