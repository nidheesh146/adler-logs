<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class batchcard extends Model
{
    protected $table = 'batchcard_batchcard';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('batchcard_batchcard', function (Builder $builder) {
            $builder->leftjoin('product_product', function ($join) {
                $join->on('batchcard_batchcard.product_id', '=', 'product_product.id');
            });
        });
    }
    function get_label($condition){
        return $this->select(['batchcard_batchcard.id','product_product.discription',''])
        ->where($condition)->get();

    }
    function insertdata($data)
    {
        return $this->insertGetId($data);
    }



}
