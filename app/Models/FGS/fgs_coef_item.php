<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_coef_item extends Model
{
    protected $table = 'fgs_coef_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$coef_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_coef_item_rel')->insert(['master'=>$coef_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

   
    function getAllItems($condition)
    {
        return $this->select('fgs_coef_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','fgs_coef.coef_number',
        'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id','fgs_oef_item.rate','fgs_oef_item.discount')
                        ->leftjoin('fgs_oef_item','fgs_oef_item.id','=','fgs_coef_item.coef_item_id')
                        ->leftjoin('fgs_coef_item_rel','fgs_coef_item_rel.item','=','fgs_coef_item.id')
                        ->leftjoin('fgs_coef','fgs_coef.id','=','fgs_coef_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_coef_item.product_id')
                        ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                        ->where($condition)
                        ->where('fgs_coef.status','=',1)
                        ->orderBy('fgs_coef_item.id','DESC')
                        ->distinct('fgs_coef_item.id')
                        ->orderBy('fgs_coef_item.id','asc')
                        ->get();
    }
    
    function get_items($condition)
    {
        return $this->select('fgs_coef_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code')
                        ->leftjoin('fgs_coef_item_rel','fgs_coef_item_rel.item','=','fgs_coef_item.id')
                        ->leftjoin('fgs_coef','fgs_coef.id','=','fgs_coef_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_coef_item.product_id')
                         ->where($condition)
                        ->orderBy('fgs_coef_item.id','DESC')
                        ->distinct('fgs_coef_item.id')
                        ->orderBy('fgs_coef_item.id','asc')
                        ->paginate(15);
    }
}
