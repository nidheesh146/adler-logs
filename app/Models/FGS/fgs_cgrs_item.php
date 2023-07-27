<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_cgrs_item extends Model
{
    protected $table = 'fgs_cgrs_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$grs_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_cgrs_item_rel')->insert(['master'=>$grs_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function getItems($condition)
    {
        return $this->select('fgs_cgrs_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftjoin('fgs_grs_item','fgs_grs_item.id','=','fgs_cgrs_item.grs_item_id')
                        ->leftjoin('fgs_cgrs_item_rel','fgs_cgrs_item_rel.item','=', 'fgs_cgrs_item.id')
                        ->leftjoin('fgs_cgrs','fgs_cgrs.id','=','fgs_cgrs_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->where($condition)
                        ->where('fgs_cgrs.status','=',1)
                        ->orderBy('fgs_cgrs_item.id','ASC')
                        ->distinct('fgs_cgrs_item.id')
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
                        ->where('fgs_grs.status','=',1)
                        ->orderBy('fgs_grs_item.id','ASC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
    }
     function get_items($condition)
    {
        return $this->select('fgs_cgrs_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code')
                        ->leftjoin('fgs_cgrs_item_rel','fgs_cgrs_item_rel.item','=', 'fgs_cgrs_item.id')
                        ->leftjoin('fgs_cgrs','fgs_cgrs.id','=','fgs_cgrs_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_cgrs_item.product_id')
                        ->where($condition)
                        ->where('fgs_cgrs.status','=',1)
                        ->orderBy('fgs_cgrs_item.id','ASC')
                        ->distinct('fgs_cgrs_item.id')
                        ->paginate(15);
    }
}
