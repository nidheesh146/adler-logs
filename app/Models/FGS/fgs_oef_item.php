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
                        ->orderBy('fgs_oef_item.id','asc')
                        ->distinct('fgs_oef_item.id')
                        ->paginate(15);
    }
    function getAllItems($condition)
    {
        return $this->select('fgs_oef_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','fgs_oef.oef_number',
        'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id')
                        ->leftjoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                        ->leftjoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_oef_item.product_id')
                        ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                        ->where($condition)
                        ->where('fgs_oef_item.coef_status','=',0)
                        ->where('fgs_oef.status','=',1)
                        ->orderBy('fgs_oef_item.id','asc')
                        ->distinct('fgs_oef_item.id')
                        ->get();
    }
    function getSingleItem($condition)
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
                        ->first();
    }

    function get_items($condition)
    {
        return $this->select('fgs_oef_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','fgs_oef.oef_number',
        'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id')
                        ->leftjoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                        ->leftjoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_oef_item.product_id')
                        ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                        ->where($condition)
                        ->where('fgs_oef.status','=',1)
                        ->orderBy('fgs_oef_item.id','asc')
                        ->distinct('fgs_oef_item.id')
                        ->paginate(15);
    }
    function get_oef_item($condition){
        return $this->select(['fgs_oef_item.*','order_fulfil.order_fulfil_type','transaction_type.transaction_name','customer_supplier.firm_name',
        'customer_supplier.shipping_address','customer_supplier.contact_person','customer_supplier.contact_number','product_product.sku_code','product_product.discription','product_product.hsn_code','fgs_oef.oef_number',
        'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id'])
                   ->join('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                    ->join('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                    ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                    ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                    ->leftjoin('product_product','product_product.id','=','fgs_oef_item.product_id')
                    ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                    ->where('fgs_oef.status','=',1)
                    ->where('fgs_oef_item.coef_status','=',0)
                    ->where($condition)
                    ->distinct('fgs_oef_item.id')
                    ->orderBy('fgs_oef_item.id','asc')
                   ->get();
} 
}
