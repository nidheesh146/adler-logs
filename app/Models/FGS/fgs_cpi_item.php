<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class fgs_cpi_item extends Model
{
    protected $table = 'fgs_cpi_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    function insert_data($data,$cpi_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_cpi_item_rel')->insert(['master'=>$cpi_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
     function get_items($cpi_id)
    {
        return $this->select('fgs_grs.grs_number','product_product.sku_code','product_product.hsn_code','product_product.discription',
        'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code')
                        ->leftjoin('fgs_cpi_item_rel','fgs_cpi_item_rel.item','=','fgs_cpi_item.id')
                        ->leftjoin('fgs_cpi','fgs_cpi.id','=','fgs_cpi_item_rel.master')
                         ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_cpi.customer_id')
                        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_cpi_item.grs_id')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_cpi_item.grs_item_id')
                        ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                        ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_cpi_item.mrn_item_id')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                        ->where('fgs_cpi_item_rel.master','=', $cpi_id)
                        ->where('fgs_grs.status','=',1)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->paginate(15);

    }
    function getItems($condition)
    {
         return $this->select('fgs_pi_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_cpi.cpi_number')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_cpi_item.pi_item_id')
                        ->leftjoin('fgs_cpi_item_rel','fgs_cpi_item_rel.item','=','fgs_cpi_item.id')
                        ->leftjoin('fgs_cpi','fgs_cpi.id','=','fgs_cpi_item_rel.master')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_cpi_item.grs_item_id')
                         ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_cpi_item.mrn_item_id')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                       ->where($condition)
                    ->orderBy('fgs_cpi_item.id','DESC')
                    ->get();
    }

    function get_pi_item($condition){
        return $this->select(['fgs_cpi_item.*'])
                   ->join('fgs_cpi_item_rel','fgs_cpi_item_rel.item','=','fgs_cpi_item.id')
                ->where($condition)
                   ->get();
    }
    
}
