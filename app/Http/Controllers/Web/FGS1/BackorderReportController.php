<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\FGS\fgs_pi;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_grs_item_rel;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_oef_item;

use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BackorderExport;

class BackorderReportController extends Controller
{
    public function get_data(Request $request)
    {
        $condition1 = [];
        $condition2 = [];
        $condition3 = [];
        if ($request->oef_no) {
            $condition1[] = ['fgs_oef.oef_number', 'like', '%' . $request->oef_no . '%'];
        }
        if ($request->grs_no) {
            $condition2[] = ['fgs_grs.grs_number', 'like', '%' . $request->grs_no . '%'];
        }
        if ($request->pi_no) {
            $condition3[] = ['fgs_pi.pi_number', 'like', '%' . $request->pi_no . '%'];
        }
        // $data = fgs_oef::select('fgs_oef.*','order_fulfil.order_fulfil_type','transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.shipping_address',
        // 'customer_supplier.contact_person','customer_supplier.contact_number','fgs_grs.grs_number','fgs_grs.grs_date','fgs_product_category.category_name','fgs_pi.pi_number','fgs_pi.pi_date',
        // 'product_stock_location.location_name as location_name1','stock_location.location_name as location_name2')
        //             ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
        //             ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
        //             ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
        //             ->leftJoin('fgs_grs','fgs_grs.oef_id','=','fgs_oef.id')
        //             ->leftJoin('fgs_pi_item','fgs_pi_item.grs_id','=','fgs_grs.id')
        //             ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
        //             ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
        //             ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
        //             ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
        //             ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
        //             ->whereNotIn('fgs_pi.id',function($query) {

        //                 $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item')->where('fgs_grs.status','=',1);

        //             })
        //             ->where('fgs_oef.status','=',1)
        //             //->where('fgs_oef.status','=',1)
        //             ->where($condition)
        //             ->distinct('fgs_oef.id')
        //             ->orderBy('fgs_oef.id','DESC')
        //             ->paginate(15);
        // print_r($oef);exit;
        // $data = DB::table('product_product')
        //     ->select(
        //         // 'product_product.id',
        //         'fgs_oef.*',
        //         // 'order_fulfil.order_fulfil_type',
        //         // 'transaction_type.transaction_name',
        //         // 'customer_supplier.firm_name',
        //         // 'customer_supplier.shipping_address',
        //         // 'customer_supplier.contact_person',
        //         // 'customer_supplier.contact_number',
        //         // 'fgs_grs.grs_number',
        //         // 'fgs_grs.grs_date',
        //         // 'fgs_product_category.category_name',
        //         // 'fgs_pi.pi_number',
        //         // 'fgs_pi.pi_date',
        //         // 'product_stock_location.location_name as location_name1',
        //         // 'stock_location.location_name as location_name2'
        //     )
        //     ->leftJoin('fgs_oef_item', 'fgs_oef_item.product_id', '=', 'product_product.id')
        //     ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
        //     ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
        //     ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
        //     ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
        //     ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        //     ->leftJoin('fgs_grs', 'fgs_grs.oef_id', '=', 'fgs_oef.id')
        //     ->leftJoin('fgs_pi_item', 'fgs_pi_item.grs_id', '=', 'fgs_grs.id')
        //     ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
        //     ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
        //     ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
        //     ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_grs.stock_location1')
        //     ->leftJoin('product_stock_location as stock_location', 'stock_location.id', 'fgs_grs.stock_location2')
        //     ->whereNotIn('fgs_pi.id', function ($query) {

        //         $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item')->where('fgs_grs.status', '=', 1);
        //     })
        //     ->where('fgs_oef.status', '=', 1)
        //     //->where('fgs_oef.status','=',1)
        //     ->where($condition)
        //     ->distinct('fgs_oef.id')
        //     ->orderBy('fgs_oef.id', 'DESC')
        //     ->paginate(15);
        $data_oef = fgs_oef_item::select(
            'fgs_oef.*',
            'product_product.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
           // 'product_price_master.mrp',
            'fgs_oef_item.remaining_qty_after_cancel',
            'fgs_oef_item.rate as mrp'
            
        )
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('product_product', 'product_product.id', '=', 'fgs_oef_item.product_id')
            ->leftjoin('product_price_master','product_price_master.product_id','=','product_product.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->where('fgs_oef.status', '=', 1)
            ->where('fgs_oef_item.status', '=', 1)
            ->where('fgs_oef_item.coef_status', '=', 0)
            ->whereNotIn('fgs_oef_item.id', function ($query) {

                $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            })
            //->where('fgs_oef.status','=',1)
            ->where($condition1)
            ->distinct('fgs_oef.id')
            ->orderBy('fgs_oef.id', 'DESC')
            ->get();
            //->paginate(15);
            $data_grs = fgs_grs_item::select(
                'fgs_grs.*',
                'product_product.*',
                'customer_supplier.firm_name',
                'customer_supplier.shipping_address',
                'customer_supplier.contact_person',
                'customer_supplier.contact_number',
                //'product_price_master.mrp',
                'fgs_grs_item.remaining_qty_after_cancel',
                'fgs_oef_item.rate as mrp',
                'fgs_oef.order_date',
                'fgs_oef.order_number'
                
            )
                ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
                ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
                ->leftJoin('product_product', 'product_product.id', '=', 'fgs_grs_item.product_id')
               // ->leftjoin('product_price_master','product_price_master.product_id','=','product_product.id')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_grs.customer_id')
                ->leftjoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                ->where('fgs_grs.status', '=', 1)
                ->whereNotIn('fgs_grs_item.id', function ($query) {

                    $query->select('fgs_pi_item.grs_item_id')->from('fgs_pi_item');
                })
                //->where('fgs_oef.status','=',1)
                ->where($condition2)
                ->where('fgs_grs.status','=',1)
                ->where('fgs_grs_item.status','=',1)
                ->where('fgs_oef_item.status', '=', 1)
                ->where('fgs_oef_item.coef_status', '=', 0)
                ->where('fgs_grs_item.cgrs_status', '=', 0)
                ->distinct('fgs_grs.id')
                ->orderBy('fgs_grs.id', 'DESC')
                ->get();
                //->paginate(15);

                $data_pi = fgs_pi_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
        'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number','fgs_pi.pi_date',
        'fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_date','fgs_oef.order_number','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_pi_item.batch_qty',
        'fgs_pi_item.remaining_qty_after_cancel','fgs_pi.created_at as pi_created_at','customer_supplier.firm_name')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                        ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                        ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                        ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        //->where('fgs_pi_item_rel.master','=', $items['pi_id'])
                        ->where($condition3)
                        ->whereNotIn('fgs_pi.id',function($query) {

                            $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
                        
                        })->where('fgs_grs.status','=',1)
                        ->where('fgs_pi.status','=',1)
                        ->where('fgs_pi_item.status','=',1)
                        ->where('fgs_pi_item.cpi_status','=',0)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
                    // ->paginate(15);
        return view('pages/FGS/PI/back-ordr-report', compact('data_oef','data_grs','data_pi'));
    }

    public function allExport(Request $request)
    {
        $condition1 = [];
        $condition2 = [];
        $condition3 = [];
        if ($request->oef_no) {
            $condition1[] = ['fgs_oef.oef_number', 'like', '%' . $request->oef_no . '%'];
        }
        if ($request->grs_no) {
            $condition2[] = ['fgs_grs.grs_number', 'like', '%' . $request->grs_no . '%'];
        }
        if ($request->pi_no) {
            $condition3[] = ['fgs_pi.pi_number', 'like', '%' . $request->pi_no . '%'];
        }
        // $info = fgs_oef::select(
        //     'fgs_oef.*',
        //     'order_fulfil.order_fulfil_type',
        //     'transaction_type.transaction_name',
        //     'customer_supplier.firm_name',
        //     'customer_supplier.shipping_address',
        //     'customer_supplier.contact_person',
        //     'customer_supplier.contact_number',
        //     'fgs_grs.grs_number',
        //     'fgs_grs.grs_date',
        //     'fgs_product_category.category_name',
        //     'fgs_pi.pi_number',
        //     'fgs_pi.pi_date',
        //     'product_stock_location.location_name as location_name1',
        //     'stock_location.location_name as location_name2'
        // )
        //     ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
        //     ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
        //     ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        //     ->leftJoin('fgs_grs', 'fgs_grs.oef_id', '=', 'fgs_oef.id')
        //     ->leftJoin('fgs_pi_item', 'fgs_pi_item.grs_id', '=', 'fgs_grs.id')
        //     ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
        //     ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
        //     ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
        //     ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_grs.stock_location1')
        //     ->leftJoin('product_stock_location as stock_location', 'stock_location.id', 'fgs_grs.stock_location2')
        //     ->whereNotIn('fgs_pi.id', function ($query) {

        //         $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item')->where('fgs_grs.status', '=', 1);
        //     })
        //     ->where('fgs_oef.status', '=', 1)
        //     //->where('fgs_oef.status','=',1)
        //     ->where($condition)
        //     ->distinct('fgs_oef.id')
        //     ->orderBy('fgs_oef.id', 'DESC')
        //     ->get();
        $data_oef = fgs_oef_item::select(
            'fgs_oef.*',
            'product_product.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            //'product_price_master.mrp',
            'fgs_oef_item.remaining_qty_after_cancel',
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
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('product_product', 'product_product.id', '=', 'fgs_oef_item.product_id')
            ->leftjoin('product_price_master','product_price_master.product_id','=','product_product.id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'product_product.product_category_id')
            ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
            ->leftJoin('zone','zone.id','=','customer_supplier.zone')
            ->leftJoin('state','state.state_id','=','customer_supplier.state')
            ->where('fgs_oef.status', '=', 1)
            // ->whereNotIn('fgs_oef_item.id', function ($query) {

            //     $query->select('fgs_grs_item.oef_item_id')->from('fgs_grs_item');
            // })
            ->where('fgs_oef.status', '=', 1)
            ->where('fgs_oef_item.status', '=', 1)
            ->where('fgs_oef_item.quantity_to_allocate', '!=', 0)
            ->where('fgs_oef_item.remaining_qty_after_cancel', '!=', 0)
            ->where('fgs_oef_item.coef_status', '=', 0)
            ->where($condition1)
            ->distinct('fgs_oef.id')
            ->orderBy('fgs_oef.id', 'DESC')
            ->get();
            $data_grs = fgs_grs_item::select(
                'fgs_grs.*',
                'product_product.*',
                'customer_supplier.firm_name',
                'customer_supplier.shipping_address',
                'customer_supplier.contact_person',
                'customer_supplier.contact_number',
                //'product_price_master.mrp',
                'fgs_grs_item.remaining_qty_after_cancel',
                'fgs_product_category.category_name',
                'fgs_oef_item.rate as mrp',
                'fgs_oef_item.discount',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'fgs_oef.order_number',
                'fgs_oef.order_date',
                'zone.zone_name',
                'state.state_name',
                'customer_supplier.city'
                
            )
                ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
                ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
                ->leftJoin('product_product', 'product_product.id', '=', 'fgs_grs_item.product_id')
                ->leftjoin('product_price_master','product_price_master.product_id','=','product_product.id')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_grs.customer_id')
                ->leftjoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'product_product.product_category_id')
                ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                ->leftJoin('state','state.state_id','=','customer_supplier.state')
                ->where('fgs_grs.status', '=', 1)
                // ->whereNotIn('fgs_grs_item.id', function ($query) {

                //     $query->select('fgs_pi_item.grs_item_id')->from('fgs_pi_item');
                // })
                //->where('fgs_oef.status','=',1)
                ->where($condition2)
                ->where('fgs_grs.status','=',1)
                ->where('fgs_grs_item.status','=',1)
                ->where('fgs_oef_item.status', '=', 1)
                // ->where('fgs_oef_item.coef_status', '=', 0)
                // ->where('fgs_grs_item.cgrs_status', '=', 0)
                ->where('fgs_grs_item.qty_to_invoice','!=',0)
                ->where('fgs_grs_item.remaining_qty_after_cancel','!=',0)
                ->distinct('fgs_grs.id')
                ->orderBy('fgs_grs.id', 'DESC')
                ->get();

                $data_pi = fgs_pi_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
        'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number','fgs_pi.pi_date',
        'fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_date','fgs_oef.order_number','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_pi_item.batch_qty',
        'fgs_pi_item.batch_qty as pi_qty','fgs_pi.created_at as pi_created_at','customer_supplier.firm_name','fgs_product_category.category_name',
        'fgs_pi_item.remaining_qty_after_cancel as pi_qty_balance','inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst', 'zone.zone_name',
        'state.state_name','customer_supplier.city')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                        ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                        ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                        ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'product_product.product_category_id')
                        ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                        ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                        ->leftJoin('state','state.state_id','=','customer_supplier.state')
                        //->where('fgs_pi_item_rel.master','=', $items['pi_id'])
                        ->where($condition3)
                        ->whereNotIn('fgs_pi.id',function($query) {

                            $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
                        
                        })->where('fgs_grs.status','=',1)
                        ->where('fgs_pi.status','=',1)
                        ->where('fgs_pi_item.status','=',1)
                        ->where('fgs_pi_item.batch_qty','!=',0)
                        ->where('fgs_pi_item.cpi_status','=',0)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
        return Excel::download(new BackorderExport($data_oef,$data_grs,$data_pi), 'Back- Order Export' . date('d-m-Y') . '.xlsx');
    }
}
