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
    return $this->select(
        'fgs_grs_item.*',
        'fgs_item_master.sku_code',
        'fgs_item_master.discription',
        'fgs_item_master.hsn_code',
        'batchcard_batchcard.batch_no',
        DB::raw("
            CASE 
                WHEN fgs_grs_item.mrn_item_id IS NULL 
                THEN fgs_product_stock_management.manufacturing_date 
                ELSE fgs_mrn_item.manufacturing_date 
            END AS manufacturing_date
        "),
        DB::raw("
            CASE 
                WHEN fgs_grs_item.mrn_item_id IS NULL 
                THEN fgs_product_stock_management.expiry_date 
                ELSE fgs_mrn_item.expiry_date 
            END AS expiry_date
        ")
    )
    ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
    ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
    ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
    ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
    ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_grs_item.mrn_item_id')
    ->leftJoin('fgs_product_stock_management', function ($join) {
        $join->on('fgs_product_stock_management.batchcard_id', '=', 'fgs_grs_item.batchcard_id')
             ->on('fgs_product_stock_management.product_id', '=', 'fgs_grs_item.product_id')
             ->on('fgs_product_stock_management.stock_location_id', '=', 'fgs_grs.stock_location1');
    })
    ->where('fgs_grs_item.status', 1)
    ->where($condition)
    ->groupBy('fgs_grs_item.id')        // ← here
    ->orderBy('fgs_grs_item.id', 'ASC')
    ->paginate(15);
}
    function getSingleItem($condition)
    {
        return $this->select('fgs_grs_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','fgs_oef.oef_number','fgs_oef_item.rate',
        'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_grs.grs_number','fgs_grs.grs_date','customer_supplier.firm_name','fgs_item_master.drug_license_number')
                    ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                    ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                    ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_grs_item.product_id')
                    ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                    ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                    ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                    ->leftJoin('fgs_oef_item','fgs_oef_item.id','fgs_grs_item.oef_item_id')
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                    ->where($condition)
                    ->where('fgs_grs_item.cgrs_status','=',0)
                    // ->where('fgs_grs_item.remaining_qty_after_cancel','!=',0)
                    // ->where('fgs_grs_item.qty_to_invoice','!=',0)
                    ->where('fgs_grs.status','=',1)
                    ->where('fgs_grs_item.status','=',1)
                    // ->whereNotIn('fgs_grs_item.id',function($query) {

                    //     $query->select('fgs_pi_item.grs_item_id')->from('fgs_pi_item');
                    
                    // })
                    ->first();
    }
    function getAllItems($condition)
    {
        return $this->select(
            'fgs_grs_item.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no',
            DB::raw("
                CASE 
                    WHEN fgs_grs_item.mrn_item_id IS NULL 
                    THEN fgs_product_stock_management.manufacturing_date 
                    ELSE fgs_mrn_item.manufacturing_date 
                END AS manufacturing_date
            "),
            DB::raw("
                CASE 
                    WHEN fgs_grs_item.mrn_item_id IS NULL 
                    THEN fgs_product_stock_management.expiry_date 
                    ELSE fgs_mrn_item.expiry_date 
                END AS expiry_date
            ")
        )
        ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
        ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
        ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
        ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
        ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_grs_item.mrn_item_id')
        ->leftJoin('fgs_product_stock_management', function ($join) {
            $join->on('fgs_product_stock_management.batchcard_id', '=', 'fgs_grs_item.batchcard_id')
                 ->on('fgs_product_stock_management.product_id', '=', 'fgs_grs_item.product_id')
                 ->on('fgs_product_stock_management.stock_location_id', '=', 'fgs_grs.stock_location1');
        })
        ->where('fgs_grs_item.status', 1)
        ->where($condition)
        ->groupBy('fgs_grs_item.id')        // ← here
        ->orderBy('fgs_grs_item.id', 'ASC')
        ->paginate(150);

    }
    function get_all_grs_item_for_pi($condition)
    {
        return $this->select('fgs_grs_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_grs.grs_number','fgs_grs.grs_date','customer_supplier.firm_name')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                        ->where($condition)
                        ->where('fgs_grs_item.cgrs_status','=',0)
                        ->where('fgs_grs_item.remaining_qty_after_cancel','!=',0)
                        ->where('fgs_grs_item.qty_to_invoice','!=',0)
                        ->where('fgs_grs.status','=',1)
                        ->where('fgs_grs_item.status','=',1)
                        // ->whereNotIn('fgs_grs_item.id',function($query) {

                        //     $query->select('fgs_pi_item.grs_item_id')->from('fgs_pi_item');
                        
                        // })
                        ->orderBy('fgs_grs_item.id','ASC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
    }
    function get_items($condition)
    {
        return $this->select('fgs_grs_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->where($condition)
                        ->where('fgs_grs.status','=',1)
                        ->where('fgs_grs_item.status','=',1)
                        ->orderBy('fgs_grs_item.id','ASC')
                        ->distinct('fgs_grs_item.id')
                        ->paginate(15);
    }
    function get_grs_item($condition)
    {
        return $this->select('fgs_grs_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->where($condition)
                        ->where('fgs_grs.status','=',1)
                        ->where('fgs_grs_item.status','=',1)
                        ->where('fgs_grs_item.cgrs_status','=',0)
                        ->orderBy('fgs_grs_item.id','ASC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
    }
    function get_grs_item_for_cgrs($condition)
    {
        return $this->select('fgs_grs_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','fgs_item_master.quantity_per_pack',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->where($condition)
                        ->where('fgs_grs_item.qty_to_invoice','!=',0)
                        ->where('fgs_grs_item.remaining_qty_after_cancel','!=',0)
                        ->where('fgs_grs.status','=',1)
                        ->where('fgs_grs_item.status','=',1)
                        ->where('fgs_grs_item.cgrs_status','=',0)
                        ->orderBy('fgs_grs_item.id','ASC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
    }
    function get_grs_item_for_label($condition)
    {
        return $this->select('fgs_grs_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','fgs_item_master.quantity_per_pack',
            'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_item_master.drug_license_number')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->where($condition)
                        //->where('fgs_grs_item.qty_to_invoice','!=',0)
                        //->where('fgs_grs_item.remaining_qty_after_cancel','!=',0)
                        ->where('fgs_grs.status','=',1)
                        ->where('fgs_grs_item.status','=',1)
                        ->where('fgs_grs_item.cgrs_status','=',0)
                        ->orderBy('fgs_grs_item.id','ASC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
    }
    function getSingleItem_label($condition)
    {
        return $this->select('fgs_grs_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','fgs_oef.oef_number','fgs_oef_item.rate',
        'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_grs.grs_number','fgs_grs.grs_date','customer_supplier.firm_name',
        'fgs_item_master.drug_license_number','product_price_master.mrp')
                    ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                    ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                    ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_grs_item.product_id')
                    ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                    ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                    ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                    ->leftJoin('fgs_oef_item','fgs_oef_item.id','fgs_grs_item.oef_item_id')
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                    ->leftJoin('product_price_master','product_price_master.product_id','=', 'fgs_item_master.id')
                    ->where($condition)
                    ->where('fgs_grs_item.cgrs_status','=',0)
                    //->where('fgs_grs_item.remaining_qty_after_cancel','!=',0)
                    //->where('fgs_grs_item.qty_to_invoice','!=',0)
                    ->where('fgs_grs.status','=',1)
                    ->where('fgs_grs_item.status','=',1)
                    // ->whereNotIn('fgs_grs_item.id',function($query) {

                    //     $query->select('fgs_pi_item.grs_item_id')->from('fgs_pi_item');
                    
                    // })
                    ->first();
    }
}
