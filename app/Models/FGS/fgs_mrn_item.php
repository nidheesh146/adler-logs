<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_mrn_item extends Model
{
    protected $table = 'fgs_mrn_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$mrn_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_mrn_item_rel')->insert(['master'=>$mrn_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function getItems($condition)
    {
        return $this->select('fgs_mrn_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'batchcard_batchcard.batch_no','fgs_mrn.mrn_number','fgs_mrn.mrn_date','fgs_mrn.created_at as mrn_wef')
                        ->leftjoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                        ->leftjoin('fgs_mrn','fgs_mrn.id','=','fgs_mrn_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_mrn_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                        ->where($condition)
                        ->where('fgs_mrn_item.status',1)
                        ->distinct('fgs_mrn_item.id')
                        ->orderBy('fgs_mrn_item.id','asc')
                        ->get();
    }
    function getMRNItems($condition)
    {
        return $this->select('fgs_mrn_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_mrn.mrn_number')
                        ->leftjoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                        ->leftjoin('fgs_mrn','fgs_mrn.id','=','fgs_mrn_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_mrn_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                       ->where($condition)
                       ->where('fgs_mrn_item.status',1)
                        ->orderBy('fgs_mrn_item.id','asc')
                        ->paginate(15);
    }
}
