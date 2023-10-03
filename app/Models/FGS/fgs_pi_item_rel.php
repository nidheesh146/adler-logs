<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_pi_item_rel extends Model
{
    protected $table = 'fgs_pi_item_rel';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function getAllItems($condition)
    {
        return $this->select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_number','fgs_oef.order_date','product_product.hsn_code','product_product.discription',
        'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity as quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code',
        'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_pi_item.remaining_qty_after_cancel')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                        ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_grs.oef_id')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                        ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_pi_item.mrn_item_id')
                        ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                        ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                        ->where('fgs_grs.status','=',1)
                        ->where('fgs_pi.status','=',1)
                        ->where('fgs_pi_item.status','=',1)
                        ->where($condition)
                        ->orderBy('fgs_grs_item.id','DESC')
                        //->distinct('fgs_grs_item.id')
                        ->get();
    }
}
