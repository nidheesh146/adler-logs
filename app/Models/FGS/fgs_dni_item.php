<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_dni_item extends Model
{
    protected $table = 'fgs_dni_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function getItems($condition)
    {
        return $this->select('fgs_dni_item.*')
                ->leftjoin('fgs_dni_item_rel','fgs_dni_item_rel.item','=','fgs_dni_item.id')
                ->where($condition)
                ->distinct('fgs_dni_item.id')
                ->orderBy('fgs_dni_item.id','ASC')
                ->get();
    }
    function getSingleItem_label($condition)
    {
        return $this->select(
            'fgs_dni_item.*',
            'fgs_dni.dni_number',
            'fgs_dni.dni_date',
            'fgs_pi.pi_number',
            'fgs_pi.pi_date',
            'fgs_grs.grs_number',
            'fgs_grs.grs_date',
            'fgs_pi_item.remaining_qty_after_cancel',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'fgs_item_master.drug_license_number',
            'batchcard_batchcard.batch_no',
            'fgs_mrn_item.manufacturing_date',
            'fgs_mrn_item.expiry_date',
            'fgs_oef.oef_number',
            'fgs_oef.oef_date',
            'fgs_oef_item.rate',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',
            'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
            'zone.zone_name',
            'product_price_master.mrp',
        )
            ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_dni.customer_id')
            ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_dni_item.pi_id')
            ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
            ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_pi_item.product_id')
            ->leftjoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_pi_item.mrn_item_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftJoin('product_price_master','product_price_master.product_id','=', 'fgs_item_master.id')
            ->where($condition)
            ->where('fgs_pi_item.cpi_status', '=', 0)
            ->first();
    }
}
