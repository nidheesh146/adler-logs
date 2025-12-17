<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Model;

class inventory_rawmaterial extends Model
{
    protected $table = 'inventory_rawmaterial';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;


    function insertdata($data)
    {
        return $this->insertGetId($data);
    }
    function updatedata($condition, $data)
    {
        return $this->where($condition)->update($data);
    }

    function get_inv_raw_data($condition)
    {

        return $this->select([
            'inventory_rawmaterial.id', 'inventory_rawmaterial.item_code as text', 'inventory_rawmaterial.short_description', 'inventory_rawmaterial.discription', 'inv_item_type.type_name', 'hsn_code', 'unit_name', 'min_stock',
            'max_stock', 'opening_quantity', 'availble_quantity', 'inv_item_type_2.type_name as type2_name'
        ])
            ->leftjoin('inv_unit', 'inv_unit.id', '=', 'inventory_rawmaterial.receipt_unit_id')
            ->leftjoin('inv_item_type', 'inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
            ->leftjoin('inv_item_type_2', 'inv_item_type_2.id', '=', 'inventory_rawmaterial.item_type_id_2')
            ->where($condition)
            ->where('inventory_rawmaterial.is_product', '!=', 0)
            ->where('inventory_rawmaterial.is_active', '=', 1)
            ->get()->toArray();
    }

    function get_items()
    {
        return $this->select('id', 'item_code')->where('inventory_rawmaterial.is_product', '!=', 0)->get();
    }
    function getItems($condition)
    {
        return $this->select('id', 'item_code as text')->where($condition)->where('inventory_rawmaterial.is_product', '!=', 0)->get();
    }
    function getFilterDescription($condition, $length, $start)
    {
        return $this->select(['inventory_rawmaterial.item_code', 'inventory_rawmaterial.discription', 'inventory_rawmaterial.id'])
            ->where($condition)->skip($start)->take($length)->get()->toArray();
    }
    function getFilterDescription1($condition)
    {
        return $this->select(['inventory_rawmaterial.discription', 'inventory_rawmaterial.id'])
            ->where('inventory_rawmaterial.is_product', '!=', 0)
            ->where($condition)->get()->count();
    }
    function getSingleDescription($condition)
    {
        return $this->select(['inventory_rawmaterial.id', 'inventory_rawmaterial.item_code as text', 'inventory_rawmaterial.discription', 'inv_item_type.type_name'])
            ->join('inv_item_type', 'inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')->where($condition)->first();
    }
    function get_single_data($condition)
    {
        return $this->select(['inventory_rawmaterial.*'])
            ->where($condition)->first();
    }
    function getData($condition)
    {
        return $this->select(['inventory_rawmaterial.*', 'inv_item_type.type_name as type1', 'unit_name', 'inv_item_type_2.type_name as type2'])
            ->leftjoin('inv_unit', 'inv_unit.id', '=', 'inventory_rawmaterial.receipt_unit_id')
            ->leftjoin('inv_item_type', 'inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
            ->leftjoin('inv_item_type_2', 'inv_item_type_2.id', '=', 'inventory_rawmaterial.item_type_id_2')
            //->where('inventory_rawmaterial.is_active', '=', 1)
            ->where('inventory_rawmaterial.is_product', '!=', 0)
            ->where($condition)
            ->orderBy('inventory_rawmaterial.id', 'desc')
            ->paginate(15);
    }

    public function get_batch_item_details($id)
    {
        return $this->select([
            'batchcard_batchcard.batch_no','batchcard_batchcard.quantity as batchcard_qty','batchcard_batchcard.created_by_id as batchcared_created_by',
            'inventory_rawmaterial.hsn_code', 'inventory_rawmaterial.item_code', 'inventory_rawmaterial.discription','product_product.process_sheet_no',
            'inv_purchase_req_master.pr_no', 'inv_purchase_req_master.requestor_id', 'inv_purchase_req_master.date as req_date',
             'inv_purchase_req_item.actual_order_qty as req_qty','inv_purchase_req_quotation.rq_no', 'inv_purchase_req_quotation.date as rq_date','inv_purchase_req_quotation.created_user',
             'inv_purchase_req_quotation_item_supp_rel.quantity as rq_qty','inv_purchase_req_quotation_item_supp_rel.supplier_id as rqsupplier','inv_purchase_req_quotation.quotation_id',
             'inv_final_purchase_order_master.po_number', 'inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.supplier_id','inv_final_purchase_order_master.processed_by',
             'inv_final_purchase_order_master.processed_date','inventory_rawmaterial_issue_unit.unit_name','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.invoice_number',
             'inv_supplier_invoice_master.created_by as invoice_created','inv_supplier_invoice_item.order_qty as inv_qty', 'inv_miq.miq_number', 'inv_miq.miq_date', 'inv_miq.created_by as miqcreated',
             'inv_mrd.mrd_number', 'inv_mrd.mrd_date', 'inv_mrd.created_by as mrdcreated','inv_mrd_item.rejected_quantity as mrd_qty','inv_mrr.mrr_number', 'inv_mrr.mrr_date','inv_mrr.created_by as mrrcreated',
             'inv_mac.created_by as maccreated','inv_mac.mac_number', 'inv_mac.mac_date', 'inv_mac_item.accepted_quantity as mac_qty','inv_stock_to_production.sip_number', 'inv_stock_to_production.created_at as sip_date',
             'inv_stock_to_production.qty_to_production as sip_qty','inv_item_type.type_name','fgs_product_category.category_name','inv_stock_from_production.sir_number',
             'inv_stock_from_production.created_at as sirdate','inv_stock_from_production.qty_to_return as sirqty','currency_exchange_rate.currency_code',
             'inventory_gst.sgst','inventory_gst.cgst','inventory_gst.igst','user.f_name','customer_supplier.firm_name','inv_final_purchase_order_item.order_qty as po_qty',
             'inv_purchase_req_quotation_item_supp_rel.discount','inv_purchase_req_quotation_item_supp_rel.rate','inv_lot_allocation.lot_number','inv_item_type_2.type_name as type2_name',
             'inv_lot_allocation.qty_received as lotqty','inv_lot_allocation.transporter_name','inv_lot_allocation.vehicle_number','inv_final_purchase_order_item.rate as po_rate','inv_final_purchase_order_item.gst',
             'inv_final_purchase_order_item.discount as podisc','inv_purchase_req_item_approve.updated_at as req_approved_date','inv_purchase_req_item_approve.created_user as req_approved_user','inv_purchase_req_item.actual_order_qty',
             'inv_stock_transfer_order.sto_number','inv_stock_transfer_order.created_at as stodate','inv_stock_transfer_order.created_user as stouser','inv_stock_transfer_order_item.transfer_qty as stoqty','inv_stock_transfer_order_item.transfer_reason',
             'inv_stock_management.stock_qty','product_product.sku_code','product_product.hsn_code as prdcthsn','product_group1.group_name as group1_name','fgs_product_category.category_name','batchcard_batchcard.id as batch_id'])
            ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.Item_code', 'inventory_rawmaterial.id')
            ->leftjoin('inv_purchase_req_master_item_rel', 'inv_purchase_req_master_item_rel.item', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id','inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_purchase_req_master', 'inv_purchase_req_master.master_id', 'inv_purchase_req_master_item_rel.master')
            ->leftjoin('inv_purchase_req_quotation_item_supp_rel', 'inv_purchase_req_quotation_item_supp_rel.item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_purchase_req_quotation', 'inv_purchase_req_quotation.quotation_id', 'inv_purchase_req_quotation_item_supp_rel.quotation_id')
            ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.rq_master_id', 'inv_purchase_req_quotation.quotation_id')
            ->leftjoin('inv_final_purchase_order_rel', 'inv_final_purchase_order_rel.master', 'inv_final_purchase_order_master.id')
            ->leftjoin('inv_final_purchase_order_item', 'inv_final_purchase_order_item.id', 'inv_final_purchase_order_rel.item')
            ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item', 'inv_supplier_invoice_item.id')
            ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id', 'inv_supplier_invoice_rel.master')
            ->leftjoin('inv_lot_allocation', 'inv_lot_allocation.pr_item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inventory_rawmaterial_issue_unit', 'inventory_rawmaterial_issue_unit.id', 'inventory_rawmaterial.issue_unit_id')
            ->leftjoin('inv_miq_item', 'inv_miq_item.item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_miq_item_rel', 'inv_miq_item_rel.item', 'inv_miq_item.id')
            ->leftjoin('inv_miq', 'inv_miq.id', 'inv_miq_item_rel.master')
            ->leftjoin('inv_mrd_item', 'inv_mrd_item.pr_item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_mrd_item_rel', 'inv_mrd_item_rel.item', 'inv_mrd_item.id')
            ->leftjoin('inv_mrd', 'inv_mrd.id', 'inv_mrd_item_rel.master')
            ->leftjoin('inv_mrr_item', 'inv_mrr_item.pr_item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_mrr_item_rel', 'inv_mrr_item_rel.item', 'inv_mrr_item.id')
            ->leftjoin('inv_mrr', 'inv_mrr.id', 'inv_mrr_item_rel.master')
            ->leftjoin('inv_mac_item', 'inv_mac_item.pr_item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_mac_item_rel', 'inv_mac_item_rel.item', 'inv_mac_item.id')
            ->leftjoin('inv_mac', 'inv_mac.id', 'inv_mac_item_rel.master')
            ->leftjoin('inv_stock_to_production', 'inv_stock_to_production.pr_item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_stock_to_production_item_rel', 'inv_stock_to_production_item_rel.master', 'inv_stock_to_production.id')
            ->leftjoin('inv_stock_to_production_item', 'inv_stock_to_production_item.id', 'inv_stock_to_production_item_rel.item')
            ->leftjoin('inv_stock_from_production','inv_stock_from_production.batch_id','inv_stock_to_production_item.batchcard_id')
            ->leftjoin('inv_stock_transfer_order_item','inv_stock_transfer_order_item.lot_id','inv_lot_allocation.id')
            ->leftjoin('inv_stock_transfer_order_item_rel', 'inv_stock_transfer_order_item_rel.item', 'inv_stock_transfer_order_item.id')
            ->leftjoin('inv_stock_transfer_order', 'inv_stock_transfer_order.id', 'inv_stock_transfer_order_item_rel.master')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', 'inv_stock_to_production_item.batchcard_id')
            ->leftjoin('inv_stock_management','inv_stock_management.lot_id','inv_lot_allocation.id')
            ->leftjoin('customer_supplier', 'customer_supplier.id', 'inv_supplier_invoice_master.supplier_id')
            ->leftjoin('user', 'user.user_id', '=', 'inv_purchase_req_master.requestor_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'inv_purchase_req_quotation_item_supp_rel.gst')
            ->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id', 'inv_purchase_req_quotation_item_supp_rel.currency')
            ->leftjoin('product_product','product_product.id','batchcard_batchcard.product_id')
            ->leftjoin('product_group1','product_group1.id','=','product_product.product_group1_id')
            ->leftjoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
            //  ->leftjoin('fgs_grs_item', 'fgs_grs_item.batchcard_id', 'batchcard_batchcard.id')
            //  ->leftjoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', 'fgs_grs_item.id')
            // ->leftjoin('fgs_grs', 'fgs_grs.id', 'fgs_grs_item_rel.master')
            //->leftjoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
            ->leftjoin('inv_item_type','inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
            ->leftjoin('inv_item_type_2','inv_item_type_2.id', '=', 'inventory_rawmaterial.item_type_id')
            ->where('batchcard_batchcard.id', $id)
            //->whereNotNull('inv_purchase_req_master.pr_no')
            ->whereNotNull('inv_stock_to_production_item.batchcard_id')
            ->first();
    }
}
