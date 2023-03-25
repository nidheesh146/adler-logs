<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_oef_item extends Model
{
    protected $table = 'fgs_oef_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$min_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_oef_item_rel')->insert(['master'=>$min_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function getItems($condition)
    {
        return $this->select('fgs_oef_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','fgs_oef.oef_number',
        'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id')
                        ->leftjoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                        ->leftjoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_oef_item.product_id')
                        ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                        ->where($condition)
                        ->where('fgs_oef.status','=',1)
                        ->orderBy('fgs_oef_item.id','DESC')
                        ->distinct('fgs_oef_item.id')
                        ->paginate(15);
    }
}
