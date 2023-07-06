<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_grs_item extends Model
{
    protected $table = 'fgs_grs_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$grs_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_grs_item_rel')->insert(['master'=>$grs_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function getItems($condition)
    {
        return $this->select('fgs_grs_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->where($condition)
                        ->where('fgs_grs.status','=',1)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->paginate(15);
    }
    function getAllItems($condition)
    {
        return $this->select('fgs_grs_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->where($condition)
                        ->where('fgs_grs_item.cgrs_status','=',0)
                        ->where('fgs_grs.status','=',1)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
    }
    function get_all_grs_item_for_pi($condition)
    {
        return $this->select('fgs_grs_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_grs.grs_number','fgs_grs.grs_date','customer_supplier.firm_name')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                        ->where($condition)
                        ->where('fgs_grs_item.cgrs_status','=',0)
                        ->where('fgs_grs_item.remaining_qty_after_cancel','!=',0)
                        ->where('fgs_grs_item.qty_to_invoice','!=',0)
                        ->where('fgs_grs.status','=',1)
                        // ->whereNotIn('fgs_grs_item.id',function($query) {

                        //     $query->select('fgs_pi_item.grs_item_id')->from('fgs_pi_item');
                        
                        // })
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
    }
    function get_items($condition)
    {
        return $this->select('fgs_grs_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->where($condition)
                        ->where('fgs_grs.status','=',1)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->paginate(15);
    }
    function get_grs_item($condition)
    {
        return $this->select('fgs_grs_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->where($condition)
                        ->where('fgs_grs.status','=',1)
                        ->where('fgs_grs_item.cgrs_status','=',0)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
    }
}
