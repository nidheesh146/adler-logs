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
    function get_label_filter($condition){
        return $this->select(['batchcard_batchcard.batch_no','product_product.sku_code','product_product.mrp'])
        ->where($condition)->get();
    }


    function insertdata($data)
    {
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_all_batchcard_list($condition)
    {
        return $this->select(['batchcard_batchcard.*','product_product.sku_code','product_product.process_sheet_no'])
                    //->leftjoin('product_product', 'product_product.id','=','batchcard_batchcard.product_id')
                    ->where($condition)
                    ->where('batchcard_batchcard.is_active','=',1)
                    ->where('batchcard_batchcard.is_trade','=',0)
                    ->orderBy('batchcard_batchcard.id', 'desc')
                    ->paginate(15);
                    //->get();
    }


    function get_batchcard_not_in_sip($condition)
    {
        return $this->select(['batchcard_batchcard.batch_no as text','batchcard_batchcard.id'])
        //->leftjoin('inv_purchase_req_item','inv_purchase_req_item.item_code','batchcard_batchcard.input_material')
        ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','batchcard_batchcard.input_material')
       // ->where('batchcard_batchcard.is_assemble','=',0)
        ->where($condition)
         ->whereNotIn('batchcard_batchcard.id',function($query) {

            $query->select('inv_stock_to_production.batch_no_id')->from('inv_stock_to_production');
        
         })
         ->orderBy('batchcard_batchcard.batch_no', 'desc')
        ->get();

    }

    function get_all_batchcards($condition)
    {
        return $this->select('batchcard_batchcard.id','batchcard_batchcard.batch_no')
            ->where($condition)
           // ->where('batchcard_batchcard.is_trade','=',0)
            ->orderBy('batchcard_batchcard.id', 'desc')
            ->get()->toArray();
    }
    function get_batchcards($condition)
    {
        return $this->select('batchcard_batchcard.id','batchcard_batchcard.batch_no as text','product_product.sku_code','product_product.discription','batchcard_batchcard.quantity','product_product.id as product_id')
            ->where($condition)
            ->where('batchcard_batchcard.is_trade','=',0)
            ->orderBy('batchcard_batchcard.id', 'desc')
            ->get()->toArray();
    }


    function get_batchcard($condition)
    {
        return $this->select(['batchcard_batchcard.id','batchcard_batchcard.batch_no as text','batchcard_batchcard.quantity','batchcard_batchcard.input_material','product_product.sku_code','product_product.discription',
        'inventory_rawmaterial.item_code','product_product.discription','inv_unit.unit_name','product_product.sku_code','inventory_rawmaterial.id as rawmaterial_id','batchcard_batchcard.is_assemble'])
        ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','batchcard_batchcard.input_material')
        //->leftjoin('product_product', 'product_product.id','=','batchcard_batchcard.product_id')
        ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
        ->where($condition)
        ->first();
    }
      function get_batch_card($condition)
    {
        return $this->select(['batchcard_batchcard.*','batchcard_batchcard.batch_no','batchcard_batchcard.quantity','product_product.process_sheet_no',
        'inventory_rawmaterial.item_code','product_product.discription','inv_unit.unit_name','product_product.sku_code','inventory_rawmaterial.id as rawmaterial_id'])
          ->leftjoin('batchcard_materials','batchcard_materials.batchcard_id','=','batchcard_batchcard.id')
        ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','batchcard_materials.item_id')
        //->leftjoin('product_product', 'product_product.id','=','batchcard_batchcard.product_id')
        ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
      
        ->where($condition)
         ->distinct('batchcard_batchcard.id')
        ->first();
    }




}
