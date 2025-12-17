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
use App\Models\product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FgsnetBookingExport;
use App\Exports\FgsnetBillingallExport;
use App\Exports\FgsnetBillingExport;
use App\Models\FGS\fgs_product_category;

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
        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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

            ->get();
        $cgrs_items = fgs_cgrs_item::select(
            'fgs_cgrs.*',
            'fgs_cgrs_item.batch_quantity',
            // 'fgs_grs_item.batch_quantity',
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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

            ->get();

        $cpi_items = fgs_cpi_item::select(
            'fgs_cpi.*',
            'fgs_cpi_item.quantity',
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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

            ->get();
            
        if ($request->download) {
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
    
                ->leftjoin('fgs_cpi_item','fgs_cpi_item.grs_item_id' , '=','fgs_grs_item.id' )
                ->leftjoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
                ->leftjoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master')

                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
                ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'product_product.product_group')
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
                // ->where('fgs_srn.status', 1)
    
                ->where($condition)
    
                // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
                ->get();
                // dd($oef_items);
            return Excel::download(new FgsnetBookingExport($oef_items), 'FgsnetBookingExport' . date('d-m-Y') . '.xlsx');
        }
        return view('pages/FGS/netbilling/net-booking-report', compact('cpi_items', 'cgrs_items', 'oef_items'));
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
            $condition[] = ['fgs_oef.product_category', '=' , $request->category_name];
        }
        if ($request->from) {
            $date= date('Y-m', strtotime('01-' . $request->from));

            $condition1[] = ['fgs_dni.dni_date', 'like', '%' .$date . '%'];
            $condition2[] = ['fgs_srn.srn_date', 'like', '%' .$date . '%'];     
           }
        $dni_items = fgs_dni_item::select(
            'fgs_dni.*',
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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
            ->where('fgs_dni.dni_exi', 'DNI')
            ->distinct('fgs_dni_item.id', 1)

            ->where($condition)
            ->get();

        $exi_items = fgs_dni_item::select(
            'fgs_dni.*',
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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
            ->where('fgs_dni.dni_exi', 'EXI')
            ->distinct('fgs_dni_item.id', 1)

            ->where($condition)
            ->get();

        $srn_items = fgs_srn_item::select(
            'fgs_srn.*',
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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
            ->distinct('fgs_dni_item.id', 1)

            ->where($condition)
            ->get();
        // dd($srn_items);

        if ($request->download) {
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
                ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'product_product.product_group')
    
                ->where('fgs_oef.status', 1)
                   ->where('fgs_dni.status', 1)
                // ->where('fgs_srn.status', 1)
    
                ->where($condition)
    
                // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
                ->get();
                // dd($dni_items);
            return Excel::download(new FgsnetBillingExport($dni_items), 'FgsnetBillingExport' . date('d-m-Y') . '.xlsx');
        }

        return view('pages/FGS/netbilling/net-billing-report', compact('dni_items', 'exi_items', 'srn_items','fgs_product_category'));
    }
    public function NetBillingReportAll(Request $request)
    {
        $condition = [];

        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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
            ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')

            ->where('fgs_oef.status', 1)
            ->where('fgs_oef_item.coef_status', 0)
            ->where($condition)

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

            ->get();

        $cpi_items = fgs_cpi_item::select(
            'fgs_cpi.*',
            'fgs_cpi_item.quantity',
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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

            ->get();
        $dni_items = fgs_dni_item::select(
            'fgs_dni.*',
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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
            ->where('fgs_dni.dni_exi', 'DNI')
            ->distinct('fgs_dni_item.id', 1)

            ->where($condition)
            ->get();

        $exi_items = fgs_dni_item::select(
            'fgs_dni.*',
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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
            ->where('fgs_dni.dni_exi', 'EXI')
            ->distinct('fgs_dni_item.id', 1)

            ->where($condition)
            ->get();

        $srn_items = fgs_srn_item::select(
            'fgs_srn.*',
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
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
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
            ->distinct('fgs_dni_item.id', 1)
            ->where($condition)
            ->get();

        if ($request->download) {
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
    
                ->leftjoin('fgs_cpi_item','fgs_cpi_item.grs_item_id' , '=','fgs_grs_item.id' )
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
                ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('product_productgroup', 'product_productgroup.id', '=', 'product_product.product_group')

                ->where('fgs_oef.status', 1)
                  ->where('fgs_oef_item.status', 1)
                 ->where('fgs_grs_item.status', 1)
    
                ->where($condition)
    
                // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
                ->get();
                //  dd($oef_items);
                return Excel::download(new FgsnetBillingallExport($oef_items), 'Net Booking-Billing Report' . date('d-m-Y') . '.xlsx');
        }
        return view('pages.FGS.netbilling.net-billing-all-report', compact('cpi_items', 'cgrs_items', 'oef_items', 'srn_items', 'exi_items', 'dni_items'));
    }
    public function NetBillingReportAllexp(Request $request)
    {
        $condition = [];

        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
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
            'fgs_srn_item.quantity as srnqty'
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
            ->leftJoin('fgs_coef_item', 'fgs_coef_item.coef_item_id', 'fgs_oef_item.id')
            ->leftJoin('fgs_coef_item_rel', 'fgs_coef_item_rel.item', 'fgs_coef_item.id')
            ->leftJoin('fgs_coef', 'fgs_coef_item_rel.master', 'fgs_coef.id')

            ->leftjoin('fgs_grs_item', 'fgs_grs_item.oef_item_id', '=', 'fgs_oef_item.id')
            ->leftjoin('fgs_cgrs_item', 'fgs_cgrs_item.grs_item_id', '=', 'fgs_grs_item.id')
            ->leftjoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
            ->leftjoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')

            ->leftjoin('fgs_cpi_item','fgs_cpi_item.grs_item_id' , '=','fgs_grs_item.id' )
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

            ->where('fgs_oef.status', 1)
              ->where('fgs_oef_item.status', 1)
            // ->where('fgs_srn.status', 1)

            ->where($condition)

            // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
            ->get();
             dd($oef_items);
            return Excel::download(new FgsnetBillingallExport($oef_items), 'FgsnetBillingallExportAll' . date('d-m-Y') . '.xlsx');

    }
}
