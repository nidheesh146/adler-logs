<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class delivery_challan_item extends Model
{
    use HasFactory;
    protected $table = 'delivery_challan_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data,$dc_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('delivery_challan_item_rel')->insert(['master'=>$dc_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function getItems($condition)
    {
        return $this->select('delivery_challan_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftjoin('delivery_challan_item_rel','delivery_challan_item_rel.item','=', 'delivery_challan_item.id')
                        ->leftjoin('delivery_challan','delivery_challan.id','=','delivery_challan_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','delivery_challan_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','delivery_challan_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','delivery_challan_item.mrn_item_id')
                        ->where($condition)
                        ->where('delivery_challan.status','=',1)
                        ->where('delivery_challan_item.status','=',1)
                        ->orderBy('delivery_challan_item.id','ASC')
                        ->distinct('delivery_challan_item.id')
                        ->paginate(15);
    }
}
