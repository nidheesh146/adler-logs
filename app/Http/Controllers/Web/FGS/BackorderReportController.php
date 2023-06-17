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
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AllTransactionExport;
class BackorderReportController extends Controller
{
    public function get_data(Request $request)
    {
        $pi = [];
        $grs = [];
        $oef = [];
        $condition = [];
        // if ($request->pi_number) {
        //     $condition[] = ['fgs_pi.pi_number', 'like', '%' . $request->pi_number . '%'];
        // }
        // if ($request->oef_number) {
        //     $condition[] = ['fgs_oef.oef_number', 'like', '%' . $request->oef_number . '%'];
        // }
        // if ($request->grs_no) {
        //     $condition[] = ['fgs_grs.grs_number','like', '%' . $request->grs_no . '%'];
        // }
        // $pi = fgs_pi::select(
        //     'fgs_pi.*',
        //     'customer_supplier.firm_name',
        //     'customer_supplier.shipping_address',
        //     'customer_supplier.billing_address',
        //     'customer_supplier.contact_person',
        //     'customer_supplier.contact_number',
        //     'fgs_oef.order_number',
        //     'fgs_oef.order_date',
        //     'fgs_grs.grs_number',
        //     'fgs_grs.grs_date',
        //     'fgs_oef.oef_number',
        //     'fgs_oef.oef_date'
        // )
        //     ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
        //     ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.master', '=', 'fgs_pi.id')
        //     ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_pi_item_rel.item')
        //     ->leftJoin('fgs_grs', 'fgs_grs.id', 'fgs_pi_item.grs_id')
        //     ->leftJoin('fgs_oef', 'fgs_oef.id', 'fgs_grs.oef_id')
        //     ->where($condition)
        //     ->whereNotIn('fgs_pi.id', function ($query) {

        //         $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
        //     })->where('fgs_pi.status', '=', 1)
        //     ->distinct('fgs_pi.id')
        //     ->paginate(15);

        // $oef = fgs_oef::select('fgs_oef.*', 'order_fulfil.order_fulfil_type', 'transaction_type.transaction_name', 
        // 'customer_supplier.firm_name', 'customer_supplier.shipping_address', 'customer_supplier.contact_person', 'customer_supplier.contact_number')
        //     ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
        //     ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
        //     ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        //     ->whereNotIn('fgs_oef.id', function ($query) {

        //         $query->select('fgs_grs.oef_id')->from('fgs_grs')->where('fgs_grs.status', '=', 1);
        //     })->where('fgs_oef.status', '=', 1)
        //     ->where($condition)
        //     ->distinct('fgs_oef.id')
        //     ->paginate(15);

        // $grs = fgs_grs::select(
        //     'fgs_grs.*',
        //     'fgs_product_category.category_name',
        //     'product_stock_location.location_name as location_name1',
        //     'stock_location.location_name as location_name2',
        //     'fgs_oef.oef_number',
        //     'customer_supplier.firm_name',
        //     'fgs_oef.order_number',
        //     'fgs_oef.order_date'
        // )
        //     ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
        //     ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_grs.stock_location1')
        //     ->leftJoin('product_stock_location as stock_location', 'stock_location.id', 'fgs_grs.stock_location2')
        //     ->leftJoin('fgs_oef', 'fgs_oef.id', 'fgs_grs.oef_id')
        //     ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        //     ->whereNotIn('fgs_grs.id', function ($query) {

        //         $query->select('fgs_pi_item.grs_id')->from('fgs_pi_item');
        //     })->where('fgs_grs.status', '=', 1)
        //     ->where($condition)
        //     ->orderBy('fgs_grs.id', 'DESC')
        //     ->distinct('fgs_grs.id')
        //     ->paginate(15);
        $data = fgs_oef::select('fgs_oef.*','order_fulfil.order_fulfil_type','transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.shipping_address',
        'customer_supplier.contact_person','customer_supplier.contact_number','fgs_grs.grs_number','fgs_grs.grs_date','fgs_product_category.category_name','fgs_pi.pi_number','fgs_pi.pi_date',
        'product_stock_location.location_name as location_name1','stock_location.location_name as location_name2')
                    ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                    ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                    ->leftJoin('fgs_grs','fgs_grs.oef_id','=','fgs_oef.id')
                    ->leftJoin('fgs_pi_item','fgs_pi_item.grs_id','=','fgs_grs.id')
                    ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                    ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
                    ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
                    ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
                    ->whereNotIn('fgs_pi.id',function($query) {

                        $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item')->where('fgs_grs.status','=',1);
                    
                    })
                    ->where('fgs_oef.status','=',1)
                    //->where('fgs_oef.status','=',1)
                    ->where($condition)
                    ->distinct('fgs_oef.id')
                    ->paginate(15);
       // print_r($oef);exit;

        return view('pages/FGS/PI/back-ordr-report',compact('data'));
    }

    public function allExport(Request $request)
    {
        $condition = [];
        if ($request->pi_number) {
            $condition[] = ['fgs_pi.pi_number', 'like', '%' . $request->pi_number . '%'];
        }
        if ($request->oef_number) {
            $condition[] = ['fgs_oef.oef_number', 'like', '%' . $request->oef_number . '%'];
        }
        if ($request->grs_no) {
            $condition[] = ['fgs_grs.grs_number','like', '%' . $request->grs_no . '%'];
        }
        $info['pi']= fgs_pi::select(
            'fgs_pi.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.billing_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            'fgs_oef.order_number',
            'fgs_oef.order_date',
            'fgs_grs.grs_number',
            'fgs_grs.grs_date',
            'fgs_oef.oef_number',
            'fgs_oef.oef_date'
        )
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
            ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.master', '=', 'fgs_pi.id')
            ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_pi_item_rel.item')
            ->leftJoin('fgs_grs', 'fgs_grs.id', 'fgs_pi_item.grs_id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', 'fgs_grs.oef_id')
            ->where($condition)
            ->whereNotIn('fgs_pi.id', function ($query) {

                $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
            })->where('fgs_pi.status', '=', 1)
            ->distinct('fgs_pi.id')
            ->get();

        $info['oef'] = fgs_oef::select('fgs_oef.*', 'order_fulfil.order_fulfil_type', 'transaction_type.transaction_name', 
        'customer_supplier.firm_name', 'customer_supplier.shipping_address', 'customer_supplier.contact_person', 'customer_supplier.contact_number')
            ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->whereNotIn('fgs_oef.id', function ($query) {

                $query->select('fgs_grs.oef_id')->from('fgs_grs')->where('fgs_grs.status', '=', 1);
            })->where('fgs_oef.status', '=', 1)
            ->where($condition)
            ->distinct('fgs_oef.id')
            ->get();

        $info['grs'] = fgs_grs::select(
            'fgs_grs.*',
            'fgs_product_category.category_name',
            'product_stock_location.location_name as location_name1',
            'stock_location.location_name as location_name2',
            'fgs_oef.oef_number',
            'customer_supplier.firm_name',
            'fgs_oef.order_number',
            'fgs_oef.order_date'
            )
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
            ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_grs.stock_location1')
            ->leftJoin('product_stock_location as stock_location', 'stock_location.id', 'fgs_grs.stock_location2')
            ->leftJoin('fgs_oef', 'fgs_oef.id', 'fgs_grs.oef_id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            ->whereNotIn('fgs_grs.id', function ($query) {

                $query->select('fgs_pi_item.grs_id')->from('fgs_pi_item');
            })->where('fgs_grs.status', '=', 1)
            ->where($condition)
            ->orderBy('fgs_grs.id', 'DESC')
            ->distinct('fgs_grs.id')
            ->get();
            return Excel::download(new AllTransactionExport($info), 'all-fgs-transaction-report' . date('d-m-Y') . '.xlsx');

    }
}
