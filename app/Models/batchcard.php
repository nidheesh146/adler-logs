<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use DB;

class batchcard extends Model
{
    protected $table = 'batchcard_batchcard';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('batchcard_batchcard', function (Builder $builder) {
            $builder->leftjoin('product_product', function ($join) {
                $join->on('batchcard_batchcard.product_id', '=', 'product_product.id');
            });
        });
    }
    function get_label($condition)
    {
        return $this->select(['batchcard_batchcard.id', 'product_product.discription', ''])
            ->where($condition)->get();
    }
    function get_label_filter($condition)
    {
        return $this->select(['batchcard_batchcard.batch_no', 'product_product.sku_code', 'product_product.mrp'])
            ->where($condition)->get();
    }


    function insertdata($data)
    {
        return $this->insertGetId($data);
    }
    function update_data($condition, $data)
    {
        return $this->where($condition)->update($data);
    }

    function get_all_batchcard_list($condition)
    {
        return $this->select(['batchcard_batchcard.*', 'product_product.sku_code', 'product_product.process_sheet_no'])
            //->leftjoin('product_product', 'product_product.id','=','batchcard_batchcard.product_id')
            ->where($condition)
            ->where('batchcard_batchcard.is_active', '=', 1)
            ->where('batchcard_batchcard.is_trade', '=', 0)
            ->orderBy('batchcard_batchcard.id', 'desc')
            ->paginate(15);
        //->get();
    }
    function get_all_batchcard_list_quality($condition)
    {
        return $this->select(['batchcard_batchcard.*', 'product_product.sku_code','p1.item_type', 'product_product.process_sheet_no','product_product.sku_name','product_product.sku_name','inv_lot_allocation.lot_number'])
             ->leftJoin('product_product as p1', 'batchcard_batchcard.product_id', '=', 'p1.id') 
             ->leftJoin('product_productgroup', 'p1.product_group_id', '=', 'product_productgroup.id') 
            ->leftJoin('add_quality', 'batchcard_batchcard.batch_no','=','add_quality.batch_no')
            ->leftJoin('inv_stock_to_production_item', 'batchcard_batchcard.id','=','inv_stock_to_production_item.batchcard_id')
            ->leftJoin('inv_stock_to_production_item_rel','inv_stock_to_production_item.id','=','inv_stock_to_production_item_rel.item')
            ->leftjoin('inv_stock_to_production','inv_stock_to_production_item_rel.master','=','inv_stock_to_production.id')
            ->leftJoin('inv_lot_allocation', 'inv_lot_allocation.id','=','inv_stock_to_production.lot_id')
            ->where($condition)
            ->where('batchcard_batchcard.is_active', '=', 1)
            ->where('batchcard_batchcard.is_trade', '=', 0)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('add_quality')
                      ->whereRaw('add_quality.batch_no = batchcard_batchcard.batch_no')
                      ->where('add_quality.status', '=', 1);
            })
            ->orderBy('batchcard_batchcard.id', 'desc')
            ->paginate(15);
        //->get();
    }
    function get_all_batchcard($condition)
    {
        return $this->select(['batchcard_batchcard.*','p1.discription', 'p1.sku_code','p1.item_type', 'p1.process_sheet_no','p1.sku_name','p1.sku_code','product_productgroup.group_name as groupname','inv_lot_allocation.lot_number'])
            ->leftJoin('product_product as p1', 'batchcard_batchcard.product_id', '=', 'p1.id') 
            ->leftJoin('product_productgroup', 'p1.product_group_id', '=', 'product_productgroup.id') 
            ->leftJoin('add_quality', 'batchcard_batchcard.batch_no','=','add_quality.batch_no')
            ->leftJoin('inv_stock_to_production_item', 'batchcard_batchcard.id','=','inv_stock_to_production_item.batchcard_id')
            ->leftJoin('inv_stock_to_production_item_rel','inv_stock_to_production_item.id','=','inv_stock_to_production_item_rel.item')
            ->leftjoin('inv_stock_to_production','inv_stock_to_production_item_rel.master','=','inv_stock_to_production.id')
            ->leftJoin('inv_lot_allocation', 'inv_lot_allocation.id','=','inv_stock_to_production.lot_id')
            ->where($condition)
            ->where('batchcard_batchcard.is_active', '=', 1)
            ->where('batchcard_batchcard.is_trade', '=', 0)
            ->orderBy('batchcard_batchcard.id', 'desc')
            ->paginate(15);
        //->get();
    }


    function get_batchcard_not_in_sip($condition)
    {
        return $this->select(['batchcard_batchcard.batch_no as text', 'batchcard_batchcard.id'])
            //->leftjoin('inv_purchase_req_item','inv_purchase_req_item.item_code','batchcard_batchcard.input_material')
            ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id', 'batchcard_batchcard.input_material')
            // ->where('batchcard_batchcard.is_assemble','=',0)
            ->where($condition)
            ->whereNotIn('batchcard_batchcard.id', function ($query) {

                $query->select('inv_stock_to_production.batch_no_id')->from('inv_stock_to_production');
            })
            ->orderBy('batchcard_batchcard.batch_no', 'desc')
            ->get();
    }

    function get_all_batchcards_assembly($condition)
    {
        return $this->select('batchcard_batchcard.id', 'batchcard_batchcard.batch_no as text')
            ->where($condition)
            // ->where('batchcard_batchcard.is_trade','=',0)
            ->orderBy('batchcard_batchcard.id', 'desc')
            ->get()->toArray();
    }
    function get_all_batchcards($condition)
    {
        return $this->select('batchcard_batchcard.id', 'batchcard_batchcard.batch_no')
            ->where($condition)
            // ->where('batchcard_batchcard.is_trade','=',0)
            ->orderBy('batchcard_batchcard.id', 'desc')
            ->get()->toArray();
    }
    function get_batchcards($condition)
    {
        return $this->select('batchcard_batchcard.id', 'batchcard_batchcard.batch_no as text', 'product_product.sku_code', 'product_product.discription', 'batchcard_batchcard.quantity', 'product_product.id as product_id')
            ->where($condition)
            ->where('batchcard_batchcard.is_trade', '=', 0)
            ->orderBy('batchcard_batchcard.id', 'desc')
            ->get()->toArray();
    }


    function get_batchcard($condition)
    {
        return $this->select([
            'batchcard_batchcard.id', 'batchcard_batchcard.batch_no as text', 'batchcard_batchcard.quantity', 'batchcard_batchcard.input_material', 'product_product.sku_code', 'product_product.discription',
            'inventory_rawmaterial.item_code', 'product_product.discription', 'inv_unit.unit_name', 'product_product.sku_code', 'inventory_rawmaterial.id as rawmaterial_id', 'batchcard_batchcard.is_assemble'
        ])
            ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id','=', 'batchcard_batchcard.input_material')
            //->leftjoin('product_product', 'product_product.id','=','batchcard_batchcard.product_id')
            ->leftjoin('inv_unit', 'inv_unit.id', '=', 'inventory_rawmaterial.issue_unit_id')
            ->where($condition)
            ->first();
    }
    function get_batch_card($condition)
    {
        return $this->select([
            'batchcard_batchcard.*', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.quantity', 'product_product.process_sheet_no',
            'inventory_rawmaterial.item_code', 'product_product.discription', 'inv_unit.unit_name', 'product_product.sku_code', 'inventory_rawmaterial.id as rawmaterial_id'
        ])
            ->leftjoin('batchcard_materials', 'batchcard_materials.batchcard_id', '=', 'batchcard_batchcard.id')
            ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id', 'batchcard_materials.item_id')
            //->leftjoin('product_product', 'product_product.id','=','batchcard_batchcard.product_id')
            ->leftjoin('inv_unit', 'inv_unit.id', '=', 'inventory_rawmaterial.issue_unit_id')

            ->where($condition)
            ->distinct('batchcard_batchcard.id')
            ->first();
    }

    function get_all_batch_item_list($condition)
    {
        return $this->select([
            'batchcard_batchcard.batch_no', 'batchcard_batchcard.id as batch_id', 'inventory_rawmaterial.item_code', 'inventory_rawmaterial.discription','inv_supplier.vendor_name',
            'inv_purchase_req_master.pr_no', 'inv_purchase_req_master.date as req_date', 'inv_purchase_req_item.actual_order_qty as req_qty',
        ])
            ->leftjoin('inv_stock_to_production_item', 'inv_stock_to_production_item.batchcard_id', 'batchcard_batchcard.id')
            ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id', 'inv_stock_to_production_item.material_id')
            ->leftjoin('inv_stock_to_production_item_rel', 'inv_stock_to_production_item_rel.item', 'inv_stock_to_production_item.id')
            ->leftjoin('inv_stock_to_production', 'inv_stock_to_production.id', 'inv_stock_to_production_item_rel.master')
            ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id', 'inv_stock_to_production.pr_item_id')
            ->leftjoin('inv_purchase_req_master_item_rel', 'inv_purchase_req_master_item_rel.item', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_purchase_req_master', 'inv_purchase_req_master.master_id', 'inv_purchase_req_master_item_rel.master')
            // ->leftjoin('inv_mac_item', 'inv_mac_item.pr_item_id', 'inv_stock_to_production.pr_item_id')
            // ->leftjoin('inv_mac_item_rel', 'inv_mac_item_rel.item', 'inv_mac_item.id')
            // ->leftjoin('inv_mac', 'inv_mac.id', 'inv_mac_item_rel.master')
            ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item', 'inv_supplier_invoice_item.id')
            ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id', 'inv_supplier_invoice_rel.master')
            ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
            // ->leftjoin('inv_final_purchase_order_item', 'inv_final_purchase_order_item.id', 'inv_supplier_invoice_item.po_item_id')
            // ->leftjoin('inv_final_purchase_order_rel', 'inv_final_purchase_order_rel.item', 'inv_final_purchase_order_item.id')
            // ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id', 'inv_final_purchase_order_rel.master')
            // ->leftjoin('inv_purchase_req_quotation', 'inv_purchase_req_quotation.quotation_id', 'inv_final_purchase_order_master.rq_master_id')
            // ->leftjoin('inv_purchase_req_quotation_item_supp_rel', 'inv_purchase_req_quotation_item_supp_rel.quotation_id', 'inv_purchase_req_quotation.quotation_id')
            //->leftjoin('inv_lot_allocation', 'inv_lot_allocation.id', 'inv_stock_to_production.lot_id')
            //->leftjoin('inv_miq_item', 'inv_miq_item.lot_number', 'inv_lot_allocation.lot_number')
            //->leftjoin('inv_miq_item_rel', 'inv_miq_item_rel.item', 'inv_miq_item.id')
            //->leftjoin('inv_miq', 'inv_miq.id', 'inv_miq_item_rel.master')
            //->leftjoin('inv_mrd_item', 'inv_mrd_item.invoice_item_id', 'inv_supplier_invoice_item.id')
            //->leftjoin('inv_mrd_item_rel', 'inv_mrd_item_rel.item', 'inv_mrd_item.id')
            //->leftjoin('inv_mrd', 'inv_mrd.id', 'inv_mrd_item_rel.master')
            //->leftjoin('inv_mrr_item', 'inv_mrr_item.pr_item_id', 'inv_purchase_req_item.requisition_item_id')
            //->leftjoin('inv_mrr_item_rel', 'inv_mrr_item_rel.item', 'inv_mrr_item.id')
            //->leftjoin('inv_mrr', 'inv_mrr.id', 'inv_mrr_item_rel.master')
            ->leftjoin('inventory_rawmaterial_issue_unit', 'inventory_rawmaterial_issue_unit.id', 'inventory_rawmaterial.issue_unit_id')
            //->leftjoin('customer_supplier', 'customer_supplier.id', 'inv_supplier_invoice_master.supplier_id')
             //->leftjoin('inv_stock_to_production_item_rel', 'inv_stock_to_production_item_rel.item', 'inv_stock_to_production_item.id')

            ->where($condition)
            ->orderBy('batchcard_batchcard.id', 'desc')
            ->whereNotNull('inv_stock_to_production_item.batchcard_id')
            ->paginate(15);
    }
    function get_all_batch_item_list_export($condition)
    {
        return $this->select([
            'batchcard_batchcard.batch_no', 'inventory_rawmaterial.item_code', 'inventory_rawmaterial.discription',
            'inv_purchase_req_master.pr_no', 'customer_supplier.firm_name', 'inv_purchase_req_master.date as req_date',
            'inv_purchase_req_item.actual_order_qty as req_qty', 'inv_mac.mac_number', 'inv_mac.mac_date',
            'inv_mac_item.accepted_quantity as mac_qty', 'inv_supplier_invoice_master.invoice_number',
            'inv_supplier_invoice_master.invoice_date', 'inv_supplier_invoice_item.order_qty as inv_qty', 'inv_purchase_req_quotation.rq_no', 'inv_purchase_req_quotation.date as rq_date',
            'inv_purchase_req_quotation_item_supp_rel.quantity as rq_qty', 'inv_miq.miq_number', 'inv_miq.miq_date', 'inv_mrd.mrd_number', 'inv_mrd.mrd_date', 'inv_mrd_item.rejected_quantity as mrd_qty', 'inv_stock_to_production.sip_number', 'inv_stock_to_production.created_at as sip_date', 'inv_stock_to_production.qty_to_production as sip_qty', 'inv_final_purchase_order_master.po_number',
            'inv_final_purchase_order_master.po_date', 'inv_final_purchase_order_item.order_qty as po_qty', 'inv_mrr.mrr_number', 'inv_mrr.mrr_date', 'inventory_rawmaterial_issue_unit.unit_name',
        ])
            ->leftjoin('inv_stock_to_production_item', 'inv_stock_to_production_item.batchcard_id', 'batchcard_batchcard.id')
            ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id', 'inv_stock_to_production_item.material_id')
            ->leftjoin('inv_stock_to_production_item_rel', 'inv_stock_to_production_item_rel.item', 'inv_stock_to_production_item.id')
            ->leftjoin('inv_stock_to_production', 'inv_stock_to_production.id', 'inv_stock_to_production_item_rel.master')
            ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id', 'inv_stock_to_production.pr_item_id')
            ->leftjoin('inv_purchase_req_master_item_rel', 'inv_purchase_req_master_item_rel.item', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_purchase_req_master', 'inv_purchase_req_master.master_id', 'inv_purchase_req_master_item_rel.master')
            ->leftjoin('inv_mac_item', 'inv_mac_item.id', 'inv_stock_to_production.mac_item_id')
            ->leftjoin('inv_mac_item_rel', 'inv_mac_item_rel.item', 'inv_mac_item.id')
            ->leftjoin('inv_mac', 'inv_mac.id', 'inv_mac_item_rel.master')
            ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id', 'inv_mac_item.invoice_item_id')
            ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item', 'inv_supplier_invoice_item.id')
            ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id', 'inv_supplier_invoice_rel.master')
            ->leftjoin('inv_final_purchase_order_item', 'inv_final_purchase_order_item.id', 'inv_supplier_invoice_item.po_item_id')
            ->leftjoin('inv_final_purchase_order_rel', 'inv_final_purchase_order_rel.item', 'inv_final_purchase_order_item.id')
            ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id', 'inv_final_purchase_order_rel.master')
            ->leftjoin('inv_purchase_req_quotation', 'inv_purchase_req_quotation.quotation_id', 'inv_final_purchase_order_master.rq_master_id')
            ->leftjoin('inv_purchase_req_quotation_item_supp_rel', 'inv_purchase_req_quotation_item_supp_rel.quotation_id', 'inv_purchase_req_quotation.quotation_id')
            ->leftjoin('inv_lot_allocation', 'inv_lot_allocation.id', 'inv_stock_to_production.lot_id')
            ->leftjoin('inv_miq_item', 'inv_miq_item.lot_number', 'inv_lot_allocation.lot_number')
            ->leftjoin('inv_miq_item_rel', 'inv_miq_item_rel.item', 'inv_miq_item.id')
            ->leftjoin('inv_miq', 'inv_miq.id', 'inv_miq_item_rel.master')
            ->leftjoin('inv_mrd_item', 'inv_mrd_item.invoice_item_id', 'inv_supplier_invoice_item.id')
            ->leftjoin('inv_mrd_item_rel', 'inv_mrd_item_rel.item', 'inv_mrd_item.id')
            ->leftjoin('inv_mrd', 'inv_mrd.id', 'inv_mrd_item_rel.master')
            ->leftjoin('inv_mrr_item', 'inv_mrr_item.pr_item_id', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_mrr_item_rel', 'inv_mrr_item_rel.item', 'inv_mrr_item.id')
            ->leftjoin('inv_mrr', 'inv_mrr.id', 'inv_mrr_item_rel.master')
            ->leftjoin('inventory_rawmaterial_issue_unit', 'inventory_rawmaterial_issue_unit.id', 'inventory_rawmaterial.issue_unit_id')
            ->orderBy('batchcard_batchcard.id', 'desc')

            ->where($condition)
            ->whereNotNull('inv_stock_to_production_item.batchcard_id')
            ->get();
    }
    public function get_batch_item_more($id)
    {
        return $this->select([
            'batchcard_batchcard.batch_no', 'inventory_rawmaterial.hsn_code', 'inventory_rawmaterial.item_code', 'inventory_rawmaterial.discription',
            'inv_purchase_req_master.pr_no', 'inv_purchase_req_master.requestor_id', 'inv_purchase_req_master.date as req_date', 'inv_purchase_req_item.actual_order_qty as req_qty', 'inv_mac.mac_number', 'inv_mac.mac_date', 'inv_mac_item.accepted_quantity as mac_qty','inv_mac.created_by as maccreated', 'inv_supplier_invoice_master.invoice_number', 'inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.created_by as inv_created', 'inv_supplier_invoice_item.order_qty as inv_qty',
            'inv_purchase_req_quotation.rq_no', 'inv_purchase_req_quotation.date as rq_date', 'inv_purchase_req_quotation_item_supp_rel.quantity as rq_qty', 'inv_miq.miq_number', 'inv_miq_item.lot_number','inv_miq.miq_date', 'inv_miq.created_by as miqcreated', 'inv_mrd.mrd_number', 'inv_mrd.mrd_date', 'inv_mrd.created_by as mrdcreated','inv_mrd_item.rejected_quantity as mrd_qty', 'inv_stock_to_production.sip_number', 'inv_stock_to_production.created_at as sip_date', 'inv_stock_to_production.qty_to_production as sip_qty',
            'inv_purchase_req_quotation.created_user','inv_final_purchase_order_master.po_number', 'inv_final_purchase_order_master.po_date', 'inv_final_purchase_order_item.order_qty as po_qty', 'inv_mrr.mrr_number', 'inv_mrr.mrr_date', 'inventory_rawmaterial_issue_unit.unit_name', 'customer_supplier.firm_name', 'user.f_name'
            ,'inv_purchase_req_quotation_item_supp_rel.discount','inv_purchase_req_quotation_item_supp_rel.supplier_id as qtnsupplier','currency_exchange_rate.currency_code','inventory_gst.igst',
            'inventory_gst.cgst','inv_final_purchase_order_master.supplier_id','inv_final_purchase_order_master.processed_by','inv_final_purchase_order_master.processed_date',
            'inventory_gst.sgst','inv_stock_from_production.sir_number','inv_stock_from_production.created_at as sirdate','inv_stock_from_production.qty_to_return as sirqty',
            'inv_purchase_req_quotation_item_supp_rel.rate','fgs_grs.grs_number','fgs_grs.customer_id as grs_customer','fgs_grs.created_at as grsdate','fgs_grs_item.batch_quantity as grsqty',
            'fgs_product_category.category_name','inv_item_type.type_name'
            ])
            // ->leftjoin('inv_stock_to_production_item', 'inv_stock_to_production_item.batchcard_id', 'batchcard_batchcard.id')
            // ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id', 'inv_stock_to_production_item.material_id')
            // ->leftjoin('inv_stock_to_production_item_rel', 'inv_stock_to_production_item_rel.item', 'inv_stock_to_production_item.id')
            // ->leftjoin('inv_stock_to_production', 'inv_stock_to_production.id', 'inv_stock_to_production_item_rel.master')
            // ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id', 'inv_stock_to_production.pr_item_id')
            // ->leftjoin('inv_purchase_req_master_item_rel', 'inv_purchase_req_master_item_rel.item', 'inv_purchase_req_item.requisition_item_id')
            // ->leftjoin('inv_purchase_req_master', 'inv_purchase_req_master.master_id', 'inv_purchase_req_master_item_rel.master')
            // ->leftjoin('inv_mac_item', 'inv_mac_item.id', 'inv_stock_to_production.mac_item_id')
            // ->leftjoin('inv_mac_item_rel', 'inv_mac_item_rel.item', 'inv_mac_item.id')
            // ->leftjoin('inv_mac', 'inv_mac.id', 'inv_mac_item_rel.master')
            // ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id', 'inv_mac_item.invoice_item_id')
            // ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item', 'inv_supplier_invoice_item.id')
            // ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id', 'inv_supplier_invoice_rel.master')
            // ->leftjoin('inv_final_purchase_order_item', 'inv_final_purchase_order_item.id', 'inv_supplier_invoice_item.po_item_id')
            // ->leftjoin('inv_final_purchase_order_rel', 'inv_final_purchase_order_rel.item', 'inv_final_purchase_order_item.id')
            // ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id', 'inv_final_purchase_order_rel.master')
            // ->leftjoin('inv_purchase_req_quotation', 'inv_purchase_req_quotation.quotation_id', 'inv_final_purchase_order_master.rq_master_id')
            // ->leftjoin('inv_purchase_req_quotation_item_supp_rel', 'inv_purchase_req_quotation_item_supp_rel.quotation_id', 'inv_purchase_req_quotation.quotation_id')
            // ->leftjoin('inv_lot_allocation', 'inv_lot_allocation.id', 'inv_stock_to_production.lot_id')
            // ->leftjoin('inv_miq_item', 'inv_miq_item.lot_number', 'inv_lot_allocation.lot_number')
            // ->leftjoin('inv_miq_item_rel', 'inv_miq_item_rel.item', 'inv_miq_item.id')
            // ->leftjoin('inv_miq', 'inv_miq.id', 'inv_miq_item_rel.master')
            // ->leftjoin('inv_mrd_item', 'inv_mrd_item.invoice_item_id', 'inv_supplier_invoice_item.id')
            // ->leftjoin('inv_mrd_item_rel', 'inv_mrd_item_rel.item', 'inv_mrd_item.id')
            // ->leftjoin('inv_mrd', 'inv_mrd.id', 'inv_mrd_item_rel.master')
            // ->leftjoin('inv_mrr_item', 'inv_mrr_item.pr_item_id', 'inv_purchase_req_item.requisition_item_id')
            // ->leftjoin('inv_mrr_item_rel', 'inv_mrr_item_rel.item', 'inv_mrr_item.id')
            // ->leftjoin('inv_mrr', 'inv_mrr.id', 'inv_mrr_item_rel.master')
            ->leftjoin('inventory_rawmaterial_issue_unit', 'inventory_rawmaterial_issue_unit.id', 'inventory_rawmaterial.issue_unit_id')
            ->leftjoin('customer_supplier', 'customer_supplier.id', 'inv_supplier_invoice_master.supplier_id')
            ->leftjoin('user', 'user.user_id', '=', 'inv_purchase_req_master.requestor_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'inv_purchase_req_quotation_item_supp_rel.gst')
            ->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id', 'inv_purchase_req_quotation_item_supp_rel.currency')
            ->leftjoin('inv_stock_from_production', 'inv_stock_from_production.batch_id', 'batchcard_batchcard.id')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.batchcard_id', 'batchcard_batchcard.id')
            ->leftjoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', 'fgs_grs_item.id')
            ->leftjoin('fgs_grs', 'fgs_grs.id', 'fgs_grs_item_rel.master')
            ->leftjoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
            ->leftjoin('inv_item_type','inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
            ->where('batchcard_batchcard.id', $id)
            ->whereNotNull('inv_stock_to_production_item.batchcard_id')
            ->first();
    }
   
}