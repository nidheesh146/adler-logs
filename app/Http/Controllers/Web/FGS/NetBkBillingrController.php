<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\FGS\fgs_cgrs;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_cpi_item;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_cgrs_item;
use App\Models\FGS\fgs_srn_item;
use App\Models\FGS\fgs_dni_item;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_product_category;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FgsnetBookingExport;
use App\Exports\FgsnetBillingallExport;
use App\Exports\FgsnetBillingExport;
use App\Models\FGS\fgs_srn;

class NetBkBillingrController extends Controller
{
    public function __construct()
    {

        $this->fgs_product_category = new fgs_product_category;
    }
    // public function NetBkBillingReport(Request $request)
    // {
    //     if ($request->sku_code) {
    //         $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
    //     }
    //     return view('pages/FGS/netbilling/net-billing-all-report');
    // }
    public function NetBookingReport(Request $request)
    {
        $condition = [];
        $condition1 = [];
        $condition2 = [];
        $condition3 = [];
        $condition4 = [];
        $fgs_product_category = $this->fgs_product_category->get()->unique('category_name');

        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_oef.product_category', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->from) {
            $date = date('Y-m', strtotime('01-' . $request->from));
            $condition1[] = ['fgs_oef.oef_date', 'like', '%' . $date . '%'];
            $condition2[] = ['fgs_coef.coef_date', 'like', '%' . $date . '%'];
            $condition3[] = ['fgs_cgrs.cgrs_date', 'like', '%' . $date . '%'];
            $condition4[] = ['fgs_cpi.cpi_date', 'like', '%' . $date . '%'];
        }
        $oef_items = fgs_oef_item::select(
            'fgs_oef.*',
            'fgs_oef_item.quantity',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'fgs_product_category.category_name',

            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name'
        )
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_oef_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')

            ->where('fgs_oef.status', 1)
            ->where('fgs_oef_item.coef_status', 0)
            ->where($condition)
            ->where($condition1)
            ->get();
        $cgrs_items = fgs_cgrs_item::select(
            'fgs_cgrs.*',
            'fgs_cgrs_item.batch_quantity',
            // 'fgs_grs_item.batch_quantity',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
            ->leftjoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_cgrs_item.product_id')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_cgrs_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_cgrs.status', 1)
            ->where($condition)
            ->where($condition3)
            ->get();

        $cpi_items = fgs_cpi_item::select(
            'fgs_cpi.*',
            'fgs_cpi_item.quantity',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
            ->leftjoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_cpi_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_cpi_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_cpi.status', 1)
            ->where($condition)
            ->where($condition4)

            ->get();

        // if ($request->download) {
            
        return view('pages/FGS/netbilling/net-booking-report', compact('cpi_items', 'cgrs_items', 'oef_items','fgs_product_category'));
    }
    public function NetBillingReport(Request $request)
    {
        $condition = [];
        $condition1 = [];
        $condition2 = [];
        $fgs_product_category = $this->fgs_product_category->get()->unique('category_name');

        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_oef.product_category', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->from) {
            $date = date('Y-m', strtotime('01-' . $request->from));

            $condition1 = [['fgs_dni.dni_date', 'like', '%' . $date . '%']];
            $condition2 = [['fgs_srn.srn_date', 'like', '%' . $date . '%']];
        }
        //  dd($condition);
        $dni_items = fgs_dni_item::select(
            'fgs_dni.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftjoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
            ->leftjoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_pi_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', 'fgs_oef.new_product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_dni.status', 1)
            ->where('fgs_dni.dni_exi', 'DNI')
            ->where($condition)
            ->where($condition1)
            ->distinct('fgs_dni_item.id')
            ->get();
        // dd($dni_items);
        $exi_items = fgs_dni_item::select(
            'fgs_dni.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftjoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
            ->leftjoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_pi_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_dni.status', 1)
            ->where($condition)
            ->where($condition1)
            ->where('fgs_dni.dni_exi', 'EXI')
            ->distinct('fgs_dni_item.id')

            ->get();

        $srn_items = fgs_srn_item::select(
            'fgs_srn.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_srn_item_rel', 'fgs_srn_item_rel.item', '=', 'fgs_srn_item.id')
            ->leftjoin('fgs_srn', 'fgs_srn.id', '=', 'fgs_srn_item_rel.master')
            ->leftjoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_srn.dni_id')
            ->leftjoin('fgs_dni_item_rel', 'fgs_dni_item_rel.master', '=', 'fgs_dni.id')
            ->leftjoin('fgs_dni_item', 'fgs_dni_item.id', '=', 'fgs_dni_item_rel.item')
            ->leftjoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_pi_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_srn.status', 1)
            ->where($condition)
            ->where($condition2)

            ->distinct('fgs_dni_item.id')

            ->get();
        // dd($srn_items);

        // if ($request->download) {
        //     dd($condition1);


        return view('pages/FGS/netbilling/net-billing-report', compact('dni_items', 'exi_items', 'srn_items', 'fgs_product_category'));
    }
    public function NetBillingReportAll(Request $request)
    {
        $condition = [];
        $condition1 = [];
        $condition2 = [];
        $condition3 = [];
        $condition4 = [];
        $condition5 = [];
        $condition6 = [];

         $fgs_product_category = $this->fgs_product_category->get()->unique('category_name');

        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_oef.product_category', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->from) {
            $date = date('Y-m', strtotime('01-' . $request->from));
            $condition1[] = ['fgs_oef.oef_date', 'like', '%' . $date . '%'];
            $condition2[] = ['fgs_coef.coef_date', 'like', '%' . $date . '%'];
            $condition3[] = ['fgs_cgrs.cgrs_date', 'like', '%' . $date . '%'];
            $condition4[] = ['fgs_cpi.cpi_date', 'like', '%' . $date . '%'];
            $condition5[] = ['fgs_dni.dni_date', 'like', '%' . $date . '%'];
            $condition6[] = ['fgs_srn.srn_date', 'like', '%' . $date . '%'];
        }
        // if ($request->from) {
        //     $date = date('Y-m', strtotime('01-' . $request->from));
        // }
        // dd($date);

        $oef_items = fgs_oef_item::select(
            'fgs_oef.*',
            'fgs_oef_item.quantity',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',

        )
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_oef_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', 'fgs_oef.new_product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')

            ->where('fgs_oef.status', 1)
            ->where('fgs_oef_item.coef_status', 0)
            ->where($condition)
            ->where($condition1)

            //  ->where('fgs_oef.oef_date', 'like', '%' . "2023-11" . '%')

            // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
            ->get();

        $cgrs_items = fgs_cgrs_item::select(
            'fgs_cgrs.*',
            'fgs_cgrs_item.batch_quantity',
            // 'fgs_grs_item.batch_quantity',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            // 'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
            ->leftjoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_cgrs_item.product_id')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_cgrs_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_cgrs.status', 1)
            ->where($condition)
            ->where($condition3)

            //  ->where('fgs_cgrs.cgrs_date', 'like', '%' . "2023-11" . '%')
            ->get();

        $cpi_items = fgs_cpi_item::select(
            'fgs_cpi.*',
            'fgs_cpi_item.quantity',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            // 'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
            ->leftjoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_cpi_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_cpi_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_cpi.status', 1)
            ->where($condition)
            ->where($condition4)

            //  ->where('fgs_cpi.cpi_date', 'like', '%' . "2023-11" . '%')
            ->get();
        $dni_items = fgs_dni_item::select(
            'fgs_dni.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            // 'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftjoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
            ->leftjoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_pi_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_dni.status', 1)
            ->where('fgs_dni.dni_exi', 'DNI')
            ->distinct('fgs_dni_item.id', 1)
            //  ->where('fgs_dni.dni_date', 'like', '%' . "2023-11" . '%')
            ->where($condition)
            ->where($condition5)

            ->get();

        $exi_items = fgs_dni_item::select(
            'fgs_dni.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            // 'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftjoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
            ->leftjoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_pi_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_dni.status', 1)
            ->where('fgs_dni.dni_exi', 'EXI')
            ->distinct('fgs_dni_item.id', 1)
            //  ->where('fgs_dni.dni_date', 'like', '%' . "2023-11" . '%')
            ->where($condition)
            ->where($condition5)

            ->get();

        $srn_items = fgs_srn_item::select(
            'fgs_srn.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_oef_item.rate',
            // 'fgs_oef_item.discount',
            // 'inventory_gst.igst',
            // 'inventory_gst.cgst',
            // 'inventory_gst.sgst',
            // 'inventory_gst.id as gst_id',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'order_fulfil.order_fulfil_type',
            'transaction_type.transaction_name',
            'fgs_product_category.category_name',
            'customer_supplier.firm_name',
            // 'customer_supplier.shipping_address',
            // 'customer_supplier.billing_address',
            'zone.zone_name',
            // 'batchcard_batchcard.batch_no',
        )
            ->leftjoin('fgs_srn_item_rel', 'fgs_srn_item_rel.item', '=', 'fgs_srn_item.id')
            ->leftjoin('fgs_srn', 'fgs_srn.id', '=', 'fgs_srn_item_rel.master')
            ->leftjoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_srn.dni_id')
            ->leftjoin('fgs_dni_item_rel', 'fgs_dni_item_rel.master', '=', 'fgs_dni.id')
            ->leftjoin('fgs_dni_item', 'fgs_dni_item.id', '=', 'fgs_dni_item_rel.item')
            ->leftjoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_pi_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->where('fgs_srn.status', 1)
            ->where($condition)
            ->where($condition6)

            //  ->where('fgs_srn.srn_date', 'like', '%' . "2023-11" . '%')
            ->distinct('fgs_dni_item.id', 1)
            ->get();

        // if ($request->download) {
            
            //  dd($oef_items);
      
        return view('pages.FGS.netbilling.net-billing-all-report', compact('cpi_items', 'cgrs_items', 'oef_items', 'srn_items', 'exi_items', 'dni_items','fgs_product_category'));
    }
    public function NetBillingReportExport(Request $request)
    {

        $condition = [];
        $condition1 = [];
        $condition2 = [];
        // $fgs_product_category = $this->fgs_product_category->get()->unique('category_name');

        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_oef.product_category', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->from) {
            $date = date('Y-m', strtotime('01-' . $request->from));

            $condition1 = [['fgs_dni.dni_date', 'like', '%' . $date . '%']];
            $condition2 = [['fgs_srn.srn_date', 'like', '%' . $date . '%']];
        }
        // dd($condition1);
        $dni_items = fgs_oef_item::select(
            'fgs_oef.*',
            'fgs_oef_item.quantity',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
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
            'fgs_product_category_new.category_name as new_category_name',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
            'zone.zone_name',
            // 'fgs_coef.*',
            // 'fgs_coef_item.quantity as coefqty',
            // 'fgs_cgrs.*',
            // 'fgs_cgrs_item.batch_quantity as cgrsqty',
            // 'fgs_cpi.*',
            // 'fgs_cpi_item.quantity as cpiqty',
            'fgs_dni.*',
            'fgs_dni_item.quantity as dniqty',
            'fgs_srn.*',
            'fgs_srn_item.quantity as srnqty',
            'fgs_product_category.category_name',
            'transaction_type.transaction_name',
            'customer_supplier.city',
            'state.state_name',
            'customer_supplier.sales_type',
            'product_productgroup.group_name',
        )
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_oef_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            // ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_oef.product_category')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            // ->leftJoin('fgs_coef_item', 'fgs_coef_item.coef_item_id', 'fgs_oef_item.id')
            // ->leftJoin('fgs_coef_item_rel', 'fgs_coef_item_rel.item', 'fgs_coef_item.id')
            // ->leftJoin('fgs_coef', 'fgs_coef_item_rel.master', 'fgs_coef.id')

            ->leftjoin('fgs_grs_item', 'fgs_grs_item.oef_item_id', '=', 'fgs_oef_item.id')
            // ->leftjoin('fgs_cgrs_item', 'fgs_cgrs_item.grs_item_id', '=', 'fgs_grs_item.id')
            // ->leftjoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
            // ->leftjoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')

            // ->leftjoin('fgs_cpi_item','fgs_cpi_item.grs_item_id' , '=','fgs_grs_item.id' )
            // ->leftjoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
            // ->leftjoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master')

            ->leftjoin('fgs_pi_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
            ->leftjoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('fgs_dni_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftjoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')

            ->leftjoin('fgs_srn_item', 'fgs_dni_item.id', '=', 'fgs_srn_item.dni_item_id')
            ->leftjoin('fgs_srn_item_rel', 'fgs_srn_item_rel.item', '=', 'fgs_srn_item.id')
            ->leftjoin('fgs_srn', 'fgs_srn.id', '=', 'fgs_srn_item_rel.master')

            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'product_product.product_group_id')
            ->where('fgs_oef.status', 1)
            ->where('fgs_dni.status', 1)
            // ->where('fgs_srn.status', 1)
            ->where($condition)

            ->where($condition1)
            // ->orwhere($condition2)
            // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
            ->get();
            $man_srn=fgs_srn::select('fgs_srn.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
            'customer_supplier.city',
            'state.state_name',
            'customer_supplier.sales_type',
            'zone.zone_name',
            )
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_srn.customer_id')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->where('fgs_srn.dni_id',0)
            ->where($condition2)
            ->get();
        // dd($dni_items);
        return Excel::download(new FgsnetBillingExport($dni_items,$man_srn), 'FgsnetBillingExport' . date('d-m-Y') . '.xlsx');
    }
    public function NetBillingReportAllexport(Request $request)
    {
        $condition = [];
        $condition1 = [];
        $condition2 = [];
        $condition3 = [];
        $condition4 = [];
        $condition5 = [];
        $condition6 = [];
        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_oef.product_category', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->from) {
            $date = date('Y-m', strtotime('01-' . $request->from));
            $condition1 = [['fgs_oef.oef_date', 'like', '%' . $date . '%']];
            $condition2 = [['fgs_coef.coef_date', 'like', '%' . $date . '%']];
            $condition3 = [['fgs_cgrs.cgrs_date', 'like', '%' . $date . '%']];
            $condition4 = [['fgs_cpi.cpi_date', 'like', '%' . $date . '%']];
            $condition5 = [['fgs_dni.dni_date', 'like', '%' . $date . '%']];
            $condition6 = [['fgs_srn.srn_date', 'like', '%' . $date . '%']];
        }
        $oef_items = fgs_oef_item::select(
            'fgs_oef.*',
            'fgs_oef_item.quantity',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
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
            'fgs_product_category_new.category_name as new_category_new',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
            'zone.zone_name',
            'fgs_coef.*',
            'fgs_coef_item.quantity as coefqty',
            'fgs_cgrs.*',
            'fgs_cgrs_item.batch_quantity as cgrsqty',
            'fgs_cpi.*',
            'fgs_cpi_item.quantity as cpiqty',
            'fgs_dni.*',
            'fgs_dni_item.quantity as dniqty',
            'fgs_srn.*',
            'fgs_srn_item.quantity as srnqty',
            'fgs_product_category.category_name',
            'transaction_type.transaction_name',
            'customer_supplier.city',
            'state.state_name',
            'customer_supplier.sales_type',
            'product_productgroup.group_name',

        )
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_oef_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftJoin('fgs_coef_item', 'fgs_coef_item.coef_item_id', 'fgs_oef_item.id')
            ->leftJoin('fgs_coef_item_rel', 'fgs_coef_item_rel.item', 'fgs_coef_item.id')
            ->leftJoin('fgs_coef', 'fgs_coef_item_rel.master', 'fgs_coef.id')

            ->leftjoin('fgs_grs_item', 'fgs_grs_item.oef_item_id', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_cgrs_item', 'fgs_cgrs_item.grs_item_id', '=', 'fgs_grs_item.id')
            ->leftjoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
            ->leftjoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')

            ->leftjoin('fgs_cpi_item', 'fgs_cpi_item.grs_item_id', '=', 'fgs_grs_item.id')
            ->leftjoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
            ->leftjoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master')

            ->leftjoin('fgs_pi_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftjoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
            ->leftjoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('fgs_dni_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            ->leftjoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftjoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')

            ->leftjoin('fgs_srn_item', 'fgs_dni_item.id', '=', 'fgs_srn_item.dni_item_id')
            ->leftjoin('fgs_srn_item_rel', 'fgs_srn_item_rel.item', '=', 'fgs_srn_item.id')
            ->leftjoin('fgs_srn', 'fgs_srn.id', '=', 'fgs_srn_item_rel.master')

            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'product_product.product_group_id')

            ->where('fgs_oef.status', 1)
            ->where('fgs_oef_item.status', 1)
            // ->where('fgs_grs_item.status', 1)
            ->where($condition)
            ->where($condition1)
            // ->orwhere($condition2)
            // ->orwhere($condition3)
            // ->orwhere($condition4)
            // ->orwhere($condition5)
            // ->orwhere($condition6)
            // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
            ->get();
            $man_srn=fgs_srn::select('fgs_srn.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
            'customer_supplier.city',
            'state.state_name',
            'customer_supplier.sales_type',
            'zone.zone_name',
            )
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_srn.customer_id')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->where('fgs_srn.dni_id',0)
            ->where($condition6)
            ->get();
            return Excel::download(new FgsnetBillingallExport($oef_items,$man_srn), 'Net Booking-Billing Report' . date('d-m-Y') . '.xlsx');
        }
    public function NetBookingReportExport(Request $request)
    {
        $condition = [];
        $condition1 = [];
        $condition2 = [];
        $condition3 = [];
        $condition4 = [];
        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_oef.product_category', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->from) {
            $date = date('Y-m', strtotime('01-' . $request->from));
            $condition1 = [['fgs_oef.oef_date', 'like', '%' . $date . '%']];
            $condition2 = [['fgs_coef.coef_date', 'like', '%' . $date . '%']];
            $condition3 = [['fgs_cgrs.cgrs_date', 'like', '%' . $date . '%']];
            $condition4 = [['fgs_cpi.cpi_date', 'like', '%' . $date . '%']];
        }  
        $oef_items = fgs_oef_item::select(
            'fgs_oef.*',
            'fgs_oef_item.quantity',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
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
            'fgs_product_category_new.category_name as new_category_name',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
            'zone.zone_name',
            'fgs_coef.*',
            'fgs_coef_item.quantity as coefqty',
            'fgs_cgrs.*',
            'fgs_cgrs_item.batch_quantity as cgrsqty',
            'fgs_cpi.*',
            'fgs_cpi_item.quantity as cpiqty',

            'fgs_product_category.category_name',
            'transaction_type.transaction_name',
            'customer_supplier.city',
            'state.state_name',
            'customer_supplier.sales_type',
            'product_productgroup.group_name',
            // 'fgs_dni.*',
            // 'fgs_dni_item.quantity as dniqty',
            // 'fgs_srn.*',
            // 'fgs_srn_item.quantity as srnqty'
        )
            ->leftjoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_oef_item.product_id')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
            ->leftJoin('fgs_coef_item', 'fgs_coef_item.coef_item_id', 'fgs_oef_item.id')
            ->leftJoin('fgs_coef_item_rel', 'fgs_coef_item_rel.item', 'fgs_coef_item.id')
            ->leftJoin('fgs_coef', 'fgs_coef_item_rel.master', 'fgs_coef.id')

            ->leftjoin('fgs_grs_item', 'fgs_grs_item.oef_item_id', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_cgrs_item', 'fgs_cgrs_item.grs_item_id', '=', 'fgs_grs_item.id')
            ->leftjoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
            ->leftjoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')

            ->leftjoin('fgs_cpi_item', 'fgs_cpi_item.grs_item_id', '=', 'fgs_grs_item.id')
            ->leftjoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
            ->leftjoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master')

            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'product_product.product_group_id')
            // ->leftjoin('fgs_pi_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            // ->leftjoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
            // ->leftjoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            // ->leftjoin('fgs_dni_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
            // ->leftjoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            // ->leftjoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')

            // ->leftjoin('fgs_srn_item', 'fgs_dni_item.id', '=', 'fgs_srn_item.dni_item_id')
            // ->leftjoin('fgs_srn_item_rel', 'fgs_srn_item_rel.item', '=', 'fgs_srn_item.id')
            // ->leftjoin('fgs_srn', 'fgs_srn.id', '=', 'fgs_srn_item_rel.master')

            ->where('fgs_oef.status', 1)
            ->where('fgs_oef_item.status', 1)
            //  ->where('fgs_srn.status', 1)

            ->where($condition)
            ->orwhere($condition1)
            // ->orwhere($condition2)
            // ->orwhere($condition3)
            // ->orwhere($condition4)


            ->get();
        // dd($oef_items);
        return Excel::download(new FgsnetBookingExport($oef_items), 'FgsnetBookingExport' . date('d-m-Y') . '.xlsx');
    }

}
