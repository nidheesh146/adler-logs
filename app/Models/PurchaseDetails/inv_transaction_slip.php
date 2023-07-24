<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_transaction_slip extends Model
{
    protected $table = 'inv_transaction_slip';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
}
