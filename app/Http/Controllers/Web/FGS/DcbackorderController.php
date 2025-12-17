<?php

namespace App\Http\Controllers\Web\fgs;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FGS\delivery_challan;
use App\Models\FGS\delivery_challan_item;
use App\Models\FGS\fgs_cdc;
use App\Models\FGS\fgs_cdc_item;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendingDCExport;
use App\Exports\PendingCDCExport;
use App\Exports\DCBackorderExport;

class DcbackorderController extends Controller
{
    public function GetAllDC(Request $request)
    {
        $condition = [];
        $condition1 = [];
        if ($request->dc_number) {
            $condition[] = ['delivery_challan.doc_no', 'like', '%' . $request->dc_number . '%'];
        }
        if ($request->cdc_number) {
            $condition1[] = ['fgs_cdc.cdc_number', 'like', '%' . $request->cdc_number . '%'];
        }
        $dc_items = delivery_challan_item::select(
            'delivery_challan.*',
            'fgs_item_master.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            //'product_price_master.mrp',
            'delivery_challan_item.remaining_qty_after_cancel',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name',
            'fgs_oef_item.rate as mrp',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',

        )
            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'delivery_challan_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'fgs_item_master.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->where('fgs_oef.status', '=', 1)
            // ->whereNotIn('fgs_oef_item.id', function ($query) {

            //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            // })
            ->where('delivery_challan.status', '=', 1)
            ->where('delivery_challan_item.status', '=', 1)
            ->where('delivery_challan.transaction_condition', '=', 1)
            ->where('fgs_oef_item.quantity_to_allocate', '!=', 0)
            ->where('delivery_challan_item.remaining_qty_after_cancel', '!=', 0)
            ->where('delivery_challan_item.cdc_status', '=', 0)
            ->where($condition)
            ->distinct('delivery_challan_item.id')
            ->orderBy('delivery_challan.id', 'DESC')
            ->get();
        $cdc_items = fgs_cdc_item::select(
            'fgs_cdc.*',
            'fgs_item_master.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            //'product_price_master.mrp',
            'delivery_challan_item.remaining_qty_after_cancel',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name',
            'fgs_oef_item.rate as mrp',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',

        )
            ->leftJoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
            ->leftJoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
            ->leftJoin('delivery_challan_item', 'delivery_challan_item.id', '=', 'fgs_cdc_item.dc_item_id')

            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'delivery_challan_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'fgs_item_master.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')

            //->where('fgs_oef.status', '=', 1)
            // ->whereNotIn('fgs_oef_item.id', function ($query) {

            //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            // })
            // ->where('delivery_challan.status', '=', 1)
            // ->where('delivery_challan_item.status', '=', 1)
            ->where('delivery_challan.transaction_condition', '=', 1)
            ->where('delivery_challan_item.batch_qty', '!=', 0)
            ->where('delivery_challan_item.remaining_qty_after_cancel', '!=', 0)
            ->where('fgs_cdc.status', '=', 1)
            ->where($condition1)
            ->distinct('fgs_cdc_item.id')
            ->orderBy('fgs_cdc.id', 'DESC')
            ->get();
        return view('pages/FGS/Delivery_challan/DC-backorder-report', compact('cdc_items', 'dc_items'));
    }
    public function GetAllDCExport(Request $request)
    {
        $condition = [];
        $condition1 = [];
        if ($request->dc_number) {
            $condition[] = ['delivery_challan.doc_no', 'like', '%' . $request->dc_number . '%'];
        }
        if ($request->cdc_number) {
            $condition1[] = ['fgs_cdc.cdc_number', 'like', '%' . $request->cdc_number . '%'];
        }
        $dc_items = delivery_challan_item::select(
            'delivery_challan.*',
            'fgs_item_master.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            //'product_price_master.mrp',
            'delivery_challan_item.remaining_qty_after_cancel',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name',
            'fgs_oef_item.rate as mrp',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',
            'zone.zone_name',
            'state.state_name',
            'customer_supplier.city'

        )
            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'delivery_challan_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'fgs_item_master.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->where('fgs_oef.status', '=', 1)
            ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            // ->whereNotIn('fgs_oef_item.id', function ($query) {

            //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            // })
            ->where('delivery_challan.status', '=', 1)
            ->where('delivery_challan_item.status', '=', 1)
            ->where('delivery_challan.transaction_condition', '=', 1)
            ->where('fgs_oef_item.quantity_to_allocate', '!=', 0)
            ->where('delivery_challan_item.remaining_qty_after_cancel', '!=', 0)
            ->where('delivery_challan_item.cdc_status', '=', 0)
            ->where($condition)
            ->distinct('delivery_challan_item.id')
            ->orderBy('delivery_challan.id', 'DESC')
            ->get();
        $cdc_items = fgs_cdc_item::select(
            'fgs_cdc.*',
            'fgs_item_master.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            //'product_price_master.mrp',
            'delivery_challan_item.remaining_qty_after_cancel',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name',
            'fgs_oef_item.rate as mrp',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',
            'zone.zone_name',
            'state.state_name',
            'customer_supplier.city'

        )
            ->leftJoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
            ->leftJoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
            ->leftJoin('delivery_challan_item', 'delivery_challan_item.id', '=', 'fgs_cdc_item.dc_item_id')

            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'delivery_challan_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'fgs_item_master.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')

            //->where('fgs_oef.status', '=', 1)
            // ->whereNotIn('fgs_oef_item.id', function ($query) {

            //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            // })
            // ->where('delivery_challan.status', '=', 1)
            // ->where('delivery_challan_item.status', '=', 1)
            ->where('delivery_challan.transaction_condition', '=', 1)
            ->where('delivery_challan_item.batch_qty', '!=', 0)
            ->where('delivery_challan_item.remaining_qty_after_cancel', '!=', 0)
            ->where('fgs_cdc.status', '=', 1)
            ->where($condition1)
            ->distinct('fgs_cdc_item.id')
            ->orderBy('fgs_cdc.id', 'DESC')
            ->get();
        return Excel::download(new DCBackorderExport($dc_items, $cdc_items), 'All-DCBackOrderReport' . date('d-m-Y') . '.xlsx');
    }
    public function PendingDC(Request $request)
    {
       // dd('hi');
        $condition = [];
        if ($request->dc_number) {
            $condition[] = ['delivery_challan.doc_no', 'like', '%' . $request->dc_number . '%'];
        }
        if ($request->customer) {
            $condition[] = ['customer_supplier.firm_name', 'like', '%' . $request->customer . '%'];
        }
        
        if ($request->from) {
            $condition[] = ['delivery_challan.doc_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['delivery_challan.doc_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $dc_items = delivery_challan_item::select(
            'delivery_challan.*',
            'fgs_item_master.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            //'product_price_master.mrp',
            'delivery_challan_item.remaining_qty_after_cancel',
            'delivery_challan_item.batch_qty',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name',
            'fgs_oef_item.rate as mrp',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',


        )
            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'delivery_challan_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'fgs_item_master.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            //->where('fgs_oef.status', '=', 1)
            // ->whereNotIn('fgs_oef_item.id', function ($query) {

            //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            // })
            ->where('delivery_challan.status', '=', 1)
            ->where('delivery_challan_item.status', '=', 1)
            ->where('delivery_challan.transaction_condition', '=', 1)
            // ->where('fgs_oef_item.quantity_to_allocate', '!=', 0)
            // ->where('delivery_challan_item.remaining_qty_after_cancel', '!=', 0)
            ->where('delivery_challan_item.cdc_status', '=', 0)
            ->where($condition)
            // ->distinct('delivery_challan_item.id')
            ->orderBy('delivery_challan.id', 'DESC')
            ->paginate(15);

        

        return view('pages/FGS/Delivery_challan/DC-pending-report', compact('dc_items'));
    }
    public function PendingDCExport(Request $request)
    {
        $condition = [];
        if ($request->dc_number) {
            $condition[] = ['delivery_challan.doc_no', 'like', '%' . $request->dc_number . '%'];
        }

        if ($request->from) {
            $condition[] = ['delivery_challan.doc_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['delivery_challan.doc_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $dc_items = delivery_challan_item::select(
            'delivery_challan.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            //'product_price_master.mrp',
            'delivery_challan_item.remaining_qty_after_cancel',
            'delivery_challan_item.batch_qty',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name',
            'fgs_oef_item.id as oefid',
            'fgs_oef_item.rate',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',
            'zone.zone_name',
            'state.state_name',
            'customer_supplier.city',
            'transaction_type.transaction_name',



        )
            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'delivery_challan_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'fgs_item_master.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'delivery_challan.transaction_type')

            ->where('fgs_oef.status', '=', 1)
            // ->whereNotIn('fgs_oef_item.id', function ($query) {

            //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            // })
            ->where('delivery_challan.status', '=', 1)
            ->where('delivery_challan_item.status', '=', 1)
            ->where('delivery_challan.transaction_condition', '=', 1)
            // ->where('fgs_oef_item.quantity_to_allocate', '!=', 0)
            // ->where('delivery_challan_item.remaining_qty_after_cancel', '!=', 0)
             ->where('delivery_challan_item.cdc_status', '=', 0)
            ->where($condition)
            //->distinct('delivery_challan_item.id')
            ->orderBy('delivery_challan_item.id', 'DESC')
            ->get();

        // $cdc_items = fgs_cdc_item::select(
        //     'fgs_cdc.*',
        //     'fgs_item_master.*',
        //     'customer_supplier.firm_name',
        //     'customer_supplier.shipping_address',
        //     'customer_supplier.contact_person',
        //     'customer_supplier.contact_number',
        //     //'product_price_master.mrp',
        //     'delivery_challan.*',
        //     'delivery_challan_item.batch_qty',
        //     'fgs_oef_item.quantity_to_allocate',
        //     'fgs_product_category.category_name',
        //     'fgs_oef_item.rate as mrp',
        //     'fgs_oef_item.discount',
        //     'inventory_gst.igst',
        //     'inventory_gst.cgst',
        //     'inventory_gst.sgst',
        //     'zone.zone_name',
        //     'state.state_name',
        //     'customer_supplier.city'

        // )
        //     ->leftJoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
        //     ->leftJoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
        //     ->leftJoin('delivery_challan_item', 'delivery_challan_item.id', '=', 'fgs_cdc_item.dc_item_id')

        //     ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
        //     ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
        //     ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'delivery_challan_item.oef_item_id')
        //     ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
        //     ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
        //     ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
        //     ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'fgs_item_master.id')
        //     ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        //     ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
        //     ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
        //     ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
        //     ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
        //     //->where('fgs_oef.status', '=', 1)
        //     // ->whereNotIn('fgs_oef_item.id', function ($query) {

        //     //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
        //     // })
        //     ->where('delivery_challan.status', '=', 1)
        //     ->where('delivery_challan_item.status', '=', 1)
        //     ->where('delivery_challan.transaction_condition', '=', 1)
        //     // ->where('delivery_challan_item.batch_qty', '!=', 0)
        //     // ->where('delivery_challan_item.remaining_qty_after_cancel', '!=', 0)
        //     ->where('fgs_cdc.status', '=',1)
        //     ->where($condition)
        //     ->distinct('fgs_cdc_item.id')
        //     ->orderBy('fgs_item_master.id', 'DESC')
        //     ->get();
        return Excel::download(new PendingDCExport($dc_items), 'DCBackOrderReport' . date('d-m-Y') . '.xlsx');
    }
    public function PendingCDC(Request $request)
    {
        $condition = [];
        if ($request->cdc_number) {
            $condition[] = ['fgs_cdc.cdc_number', 'like', '%' . $request->cdc_number . '%'];
        }

        if ($request->from) {
            $condition[] = ['fgs_cdc.cdc_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_cdc.cdc_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $cdc_items = fgs_cdc_item::select(
            'fgs_cdc.*',
            'fgs_item_master.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            //'product_price_master.mrp',
            'delivery_challan_item.remaining_qty_after_cancel',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name',
            'fgs_oef_item.rate as mrp',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',

        )
            ->leftJoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
            ->leftJoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
            ->leftJoin('delivery_challan_item', 'delivery_challan_item.id', '=', 'fgs_cdc_item.dc_item_id')

            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'delivery_challan_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'fgs_item_master.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')

            //->where('fgs_oef.status', '=', 1)
            // ->whereNotIn('fgs_oef_item.id', function ($query) {

            //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            // })
            // ->where('delivery_challan.status', '=', 1)
            // ->where('delivery_challan_item.status', '=', 1)
            ->where('delivery_challan.transaction_condition', '=', 1)
            ->where('delivery_challan_item.batch_qty', '!=', 0)
            ->where('delivery_challan_item.remaining_qty_after_cancel', '!=', 0)
            //->where('fgs_cdc.status', '=', 1)
            ->where($condition)
            ->distinct('fgs_cdc_item.id')
            ->orderBy('fgs_cdc.id', 'DESC')
            ->paginate(15);
        return view('pages/FGS/CDC/cdc-pending-report', compact('cdc_items'));
    }
    public function PendingCDCExport(Request $request)
    {
        $condition = [];
        if ($request->cdc_number) {
            $condition[] = ['fgs_cdc.cdc_number', 'like', '%' . $request->cdc_number . '%'];
        }

        if ($request->from) {
            $condition[] = ['fgs_cdc.cdc_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_cdc.cdc_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $cdc_items = fgs_cdc_item::select(
            'fgs_cdc.*',
            'fgs_item_master.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            //'product_price_master.mrp',
            'delivery_challan_item.remaining_qty_after_cancel',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name',
            'fgs_oef_item.rate as mrp',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',
            'zone.zone_name',
            'state.state_name',
            'customer_supplier.city'

        )
            ->leftJoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
            ->leftJoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
            ->leftJoin('delivery_challan_item', 'delivery_challan_item.id', '=', 'fgs_cdc_item.dc_item_id')

            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'delivery_challan_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'fgs_item_master.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            //->where('fgs_oef.status', '=', 1)
            // ->whereNotIn('fgs_oef_item.id', function ($query) {

            //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            // })
            // ->where('delivery_challan.status', '=', 1)
            // ->where('delivery_challan_item.status', '=', 1)
            ->where('delivery_challan.transaction_condition', '=', 1)
            ->where('delivery_challan_item.batch_qty', '!=', 0)
            ->where('delivery_challan_item.remaining_qty_after_cancel', '!=', 0)
            ->where('fgs_cdc.status', '=', 1)
            ->where($condition)
            ->distinct('fgs_cdc_item.id')
            ->orderBy('fgs_cdc.id', 'DESC')
            ->paginate(15);
        return Excel::download(new PendingCDCExport($cdc_items), 'CDCBackOrderReport' . date('d-m-Y') . '.xlsx');
    }
}
