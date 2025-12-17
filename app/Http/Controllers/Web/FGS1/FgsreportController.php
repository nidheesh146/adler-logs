<?php

namespace App\Http\Controllers\Web\FGS;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_coef_item;
use App\Models\FGS\fgs_cgrs_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_cpi_item;
use App\Models\FGS\fgs_min_item;
use App\Models\FGS\fgs_cmin_item;
use App\Models\FGS\fgs_mtq_item;
use App\Models\FGS\fgs_cmtq_item;
use App\Models\FGS\fgs_mis_item;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_dni_item;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FGSTransactionExport;
use App\Exports\FGSInvTransactionExport;

class FgsreportController extends Controller
{
    public function get_sales_data(Request $request)
    {
        // if($request->item_code)
        // {
        //     $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%'];
        //     $product_details = DB::table('product_product')
        //             ->join('batchcard_batchcard', 'product_product.id', '=', 'batchcard_batchcard.product_id')
        //             ->select('product_product.*', 'batchcard_batchcard.batch_no')
        //             ->where($condition)
        //             ->paginate(15);
        // }
        // if($request->from || $request->to)
        // {
        //     $from_date=\Carbon\Carbon::createFromFormat('m-Y',  $request->from)->format('Y-m');
        //     $to_date=\Carbon\Carbon::createFromFormat('m-Y',  $request->to)->format('Y-m');
        //     $product_details = DB::table('product_product')
        //             ->join('batchcard_batchcard', 'product_product.id', '=', 'batchcard_batchcard.product_id')
        //             ->select('product_product.*', 'batchcard_batchcard.batch_no')           
        //             ->whereRaw("DATE_FORMAT(product_product.created,'%Y-%m') BETWEEN '$from_date' AND '$to_date'")
        //             ->paginate(15);
        // }
        // if(!$request->item_code && !$request->from && !$request->to)
        // {
        //     $product_details = DB::table('product_product')
        //             ->join('batchcard_batchcard', 'product_product.id', '=', 'batchcard_batchcard.product_id')
        //             ->select('product_product.*', 'batchcard_batchcard.batch_no')
        //             //->where($condition)
        //             ->paginate(15);
        // }
        
        
        // $from_date = date('m-Y');
        // $to_date = date('m-Y');
        $condition = [];

        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
      if($request->from)
        {
            $condition[] = [DB::raw("DATE_FORMAT(fgs_mrn.mrn_date,'%Y-%m')"), '=', date('Y-m',strtotime('01-'.$request->from))];
            // $condition[] = [DB::raw("DATE_FORMAT(fgs_mrn.mrn_date,'%Y-%m')"), '>=', date('Y-m', strtotime($request->from))];

        //     $condition[] = ['fgs_mrn.mrn_date', '<=', date('Y-m-', strtotime('01-' .  $request->from))];
        // $condition[] = ['fgs_mrn.mrn_date', '>=', date('Y-m-t', strtotime('01-' . $request->from))];
           // $condition[] = ['fgs_mrn.mrn_date', '=', date('Y-m', strtotime( $request->from))];
          // $condition[] = ['fgs_mrn.mrn_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        else{
            $condition[] = [DB::raw("DATE_FORMAT(fgs_mrn.mrn_date,'%Y-%m')"), '=', date('Y-m')];

            // $condition[] = ['fgs_mrn.mrn_date', '<=', date('Y-m'.'-01')];
            // $condition[] = ['fgs_mrn.mrn_date', '>=', date('Y-m'.'-01')];
 
        }
        // if($request->to)
        // {
        //     $condition[] = ['fgs_mrn.mrn_date', '<=', date('Y-m-d', strtotime('01-' .  $request->to))];
        //    // $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->to))];
        // }
        //dd( date('Y-m',strtotime('01-'.$request->from)));
        $items = fgs_mrn_item::select('fgs_mrn_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'batchcard_batchcard.batch_no','batchcard_batchcard.id as batch_id','fgs_mrn.mrn_number','fgs_mrn.mrn_date','fgs_mrn.created_at as mrn_wef','fgs_mrn_item.id as mrn_item_id')
                        ->leftjoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                        ->leftjoin('fgs_mrn','fgs_mrn.id','=','fgs_mrn_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_mrn_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                        ->where($condition)
                        ->where('fgs_mrn_item.status',1)
                        ->distinct('fgs_mrn_item.id')
                        ->orderBy('fgs_mrn_item.id','desc')
                        ->paginate(15);


        return view('pages/FGS/product-master/fin-goods-report', compact('items'));
    }
    

public function get_inv_data(Request $request)
{
    $condition = [];

    if ($request->item_code) {
        $condition[] = ['product_product.sku_code', 'like', '%' . $request->item_code . '%'];
    }

    // Date range filtering
    if ($request->from && $request->to) {
        $fromDate = date('Y-m-d', strtotime($request->from));
        $toDate = date('Y-m-d', strtotime($request->to));
        $condition[] = [DB::raw("DATE(fgs_mrn.mrn_date)"), '>=', $fromDate];
        $condition[] = [DB::raw("DATE(fgs_mrn.mrn_date)"), '<=', $toDate];
    } else if ($request->from) {
        $fromDate = date('Y-m-d', strtotime($request->from));
        $condition[] = [DB::raw("DATE(fgs_mrn.mrn_date)"), '>=', $fromDate];
    } else if ($request->to) {
        $toDate = date('Y-m-d', strtotime($request->to));
        $condition[] = [DB::raw("DATE(fgs_mrn.mrn_date)"), '<=', $toDate];
    } else {
        // Default to current month if no date range is provided
        $condition[] = [DB::raw("DATE_FORMAT(fgs_mrn.mrn_date,'%Y-%m')"), '=', date('Y-m')];
    }

    $items = fgs_mrn_item::select(
        'fgs_mrn_item.*',
        'product_product.sku_code',
        'product_product.discription',
        'product_product.hsn_code',
        'batchcard_batchcard.batch_no',
        'batchcard_batchcard.id as batch_id',
        'fgs_mrn.mrn_number',
        'fgs_mrn.mrn_date',
        'fgs_mrn.created_at as mrn_wef',
        'fgs_mrn_item.id as mrn_item_id'
    )
        ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
        ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
        ->leftjoin('product_product', 'product_product.id', '=', 'fgs_mrn_item.product_id')
        ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
        ->where($condition)
        ->where('fgs_mrn_item.status', 1)
        ->where('fgs_mrn.status', 1)
        ->distinct('fgs_mrn_item.id')
        ->orderBy('fgs_mrn_item.id', 'desc')
        ->paginate(15);

    return view('pages/inventory/FGS-Transfer/fgs-inventory-trans-report', compact('items'));
}

    function getOEFDetails($mrn_item_id)
    {
        $oef_details = fgs_grs_item::select('fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.created_at as oef_wef','fgs_oef_item.remaining_qty_after_cancel','fgs_oef_item.id as oef_item_id')
                            ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                            ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                            ->where('fgs_grs_item.mrn_item_id','=',$mrn_item_id)
                            ->first();
        return $oef_details;
    }
    function get_nongrsOEFDetails($product_id)
    {
        $oef_details = fgs_oef_item::select('fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.created_at as oef_wef','fgs_oef_item.remaining_qty_after_cancel','fgs_oef_item.id as oef_item_id')
                            //->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                            ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                            ->where('fgs_oef_item.product_id','=',$product_id)
                            ->orderBy('fgs_oef_item.id', 'desc')
                            ->first();
                            
        return $oef_details;
    }
    function getCOEFDetails($oef_item_id)
    {
        $coef_details = fgs_coef_item::select('fgs_coef.coef_number','fgs_coef.coef_date','fgs_coef.created_at as coef_wef','fgs_coef_item.quantity')
                            ->leftJoin('fgs_coef_item_rel','fgs_coef_item_rel.item','=','fgs_coef_item.id')
                            ->leftJoin('fgs_coef','fgs_coef.id','=','fgs_coef_item_rel.master')
                            ->where('fgs_coef_item.coef_item_id','=',$oef_item_id)
                            //->where('fgs_coef_item.status','=',1)
                            ->first();
        return $coef_details;
    }
    function getGRSDetails($mrn_item_id)
    {
        $grs_details = fgs_grs_item::select('fgs_grs.grs_number','fgs_grs.grs_date','fgs_grs.created_at as grs_wef','fgs_grs_item.remaining_qty_after_cancel','fgs_grs_item.id as grs_item_id')
                            ->leftJoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=','fgs_grs_item.id')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                            ->where('fgs_grs_item.mrn_item_id','=',$mrn_item_id)
                            ->get();
        return $grs_details;
    }
    function getCGRSDetails($grs_item_id)
    {
        $cgrs_details = fgs_cgrs_item::select('fgs_cgrs.cgrs_number','fgs_cgrs.cgrs_date','fgs_cgrs.created_at as cgrs_wef','fgs_cgrs_item.batch_quantity')
                            ->leftJoin('fgs_cgrs_item_rel','fgs_cgrs_item_rel.item','=','fgs_cgrs_item.id')
                            ->leftJoin('fgs_cgrs','fgs_cgrs.id','=','fgs_cgrs_item_rel.master')
                            ->where('fgs_cgrs_item.grs_item_id','=',$grs_item_id)
                            //->where('fgs_coef_item.status','=',1)
                            ->first();
        return $cgrs_details;
    }
    function getPIDetails($mrn_item_id)
    {
        $pi_details = fgs_pi_item::select('fgs_pi.pi_number','fgs_pi.pi_date','fgs_pi.created_at as pi_wef','fgs_pi_item.remaining_qty_after_cancel','fgs_pi_item.id as pi_item_id')
                            ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                            ->where('fgs_pi_item.mrn_item_id','=',$mrn_item_id)
                            ->get();
        return $pi_details;
    }
    function getCPIDetails($mrn_item_id)
    {
        $cpi_details = fgs_cpi_item::select('fgs_cpi.cpi_number','fgs_cpi.cpi_date','fgs_cpi.created_at as cpi_wef','fgs_cpi_item.quantity','fgs_cpi_item.id as cpi_item_id')
                            ->leftJoin('fgs_cpi_item_rel','fgs_cpi_item_rel.item','=','fgs_cpi_item.id')
                            ->leftJoin('fgs_cpi','fgs_cpi.id','=','fgs_cpi_item_rel.master')
                            ->where('fgs_cpi_item.mrn_item_id','=',$mrn_item_id)
                            ->get();
        return $cpi_details;
    }
    function getMINDetails($batch_id)
    {
        $min_details = fgs_min_item::select('fgs_min.min_number','fgs_min.min_date','fgs_min.created_at as min_wef')
                                    ->leftJoin('fgs_min_item_rel','fgs_min_item_rel.item','=','fgs_min_item.id')
                                    ->leftJoin('fgs_min','fgs_min.id','=','fgs_min_item_rel.master')
                                    ->where('fgs_min_item.batchcard_id','=',$batch_id)
                                    ->where('fgs_min_item.status','=',1)
                                    ->get();
        return $min_details;
    }
    function getCMINDetails($batch_id)
    {
        $cmin_details = fgs_cmin_item::select('fgs_cmin.cmin_number','fgs_cmin.cmin_date','fgs_cmin.created_at as cmin_wef')
                                    ->leftJoin('fgs_cmin_item_rel','fgs_cmin_item_rel.item','=','fgs_cmin_item.id')
                                    ->leftJoin('fgs_cmin','fgs_cmin.id','=','fgs_cmin_item_rel.master')
                                    ->where('fgs_cmin_item.batchcard_id','=',$batch_id)
                                    ->get();
        return $cmin_details;
    }
    function getMTQDetails($batch_id)
    {
        $mtq_details = fgs_mtq_item::select('fgs_mtq.mtq_number','fgs_mtq.mtq_date','fgs_mtq.created_at as mtq_wef','fgs_mtq_item.id as mtq_item_id')
                                    ->leftJoin('fgs_mtq_item_rel','fgs_mtq_item_rel.item','=','fgs_mtq_item.id')
                                    ->leftJoin('fgs_mtq','fgs_mtq.id','=','fgs_mtq_item_rel.master')
                                    ->where('fgs_mtq_item.batchcard_id','=',$batch_id)
                                    ->get();
        return $mtq_details;
    }
    function getCMTQDetails($batch_id)
    {
        $cmtq_details = fgs_cmtq_item::select('fgs_cmtq.cmtq_number','fgs_cmtq.cmtq_date','fgs_cmtq.created_at as cmtq_wef')
                                    ->leftJoin('fgs_cmtq_item_rel','fgs_cmtq_item_rel.item','=','fgs_cmtq_item.id')
                                    ->leftJoin('fgs_cmtq','fgs_cmtq.id','=','fgs_cmtq_item_rel.master')
                                    ->where('fgs_cmtq_item.batchcard_id','=',$batch_id)
                                    ->get();
        return $cmtq_details;
    }
    function getMISDetails($mtq_item_id)
    {
        $mis_details = fgs_mis_item::select('fgs_mis.mis_number','fgs_mis.mis_date','fgs_mis.created_at as mis_wef','fgs_cgrs_item.batch_quantity')
                ->leftJoin('fgs_mis_item_rel','fgs_mis_item_rel.item','=','fgs_mis_item.id')
                ->leftJoin('fgs_mis','fgs_mis.id','=','fgs_mis_item_rel.master')
                ->where('fgs_mis_item.grs_item_id','=',$mtq_item_id)
                //->where('fgs_coef_item.status','=',1)
                ->first();
        return $mis_details;
    }
    function getDNIDetails($mrn_item_id)
    {
        $mis_details = fgs_dni_item::select('fgs_dni.dni_number','fgs_dni.dni_date','fgs_dni.created_at as mis_wef','fgs_pi_item.batch_qty')
                ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','=','fgs_dni_item.id')
                ->leftJoin('fgs_dni','fgs_dni.id','=','fgs_dni_item_rel.master')
                ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_dni_item.pi_item_id')

                ->where('fgs_dni_item.mrn_item_id','=',$mrn_item_id)
                //->where('fgs_coef_item.status','=',1)
                ->first();
        return $mis_details;
    }
    public function get_result(Request $request)
    {
        $from_date=\Carbon\Carbon::createFromFormat('m-Y',  $request->from)->format('Y-m');
        $to_date=\Carbon\Carbon::createFromFormat('m-Y',  $request->to)->format('Y-m');
        $sku=$request->itm_code;
        $product_details = DB::table('product_product')
            ->join('batchcard_batchcard', 'product_product.id', '=', 'batchcard_batchcard.product_id')
            ->select('product_product.*', 'batchcard_batchcard.batch_no')           
            ->whereRaw("DATE_FORMAT(product_product.created,'%Y-%m') BETWEEN '$from_date' AND '$to_date'")
            ->when($sku, function ($query, $sku) {
                return $query->where('product_product.sku_code', 'like', '%'.$sku.'%');
            })
            ->paginate(15);
                
        $from_date = date('m-Y');
        $to_date = date('m-Y');
        return view('pages/FGS/product-master/fin-goods-report', compact('product_details', 'from_date', 'to_date'));
    }
    public function get_mrn($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_mrn_item')
                ->join('fgs_mrn_item_rel', 'fgs_mrn_item.id', '=', 'fgs_mrn_item_rel.item')
                ->join('fgs_mrn', 'fgs_mrn_item_rel.master', '=', 'fgs_mrn.id')
                ->select('fgs_mrn.mrn_number', 'fgs_mrn.mrn_date', 'fgs_mrn.created_at', 'fgs_mrn_item.quantity')
                ->where('fgs_mrn_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function get_oef($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_oef_item')
                ->join('fgs_oef_item_rel', 'fgs_oef_item.id', '=', 'fgs_oef_item_rel.item')
                ->join('fgs_oef', 'fgs_oef_item_rel.master', '=', 'fgs_oef.id')
                ->select('fgs_oef.oef_number', 'fgs_oef.oef_date', 'fgs_oef.created_at', 'fgs_oef_item.quantity')
                ->where('fgs_oef_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function get_pi($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_pi_item')
                ->join('fgs_pi_item_rel', 'fgs_pi_item.id', '=', 'fgs_pi_item_rel.item')
                ->join('fgs_pi', 'fgs_pi_item_rel.master', '=', 'fgs_pi.id')
                ->select('fgs_pi.pi_number', 'fgs_pi.pi_date', 'fgs_pi.created_at', 'fgs_pi_item.batch_qty')
                ->where('fgs_pi_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function get_grs($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_grs_item')
                ->join('fgs_grs_item_rel', 'fgs_grs_item.id', '=', 'fgs_grs_item_rel.item')
                ->join('fgs_grs', 'fgs_grs_item_rel.master', '=', 'fgs_grs.id')
                ->select('fgs_grs.grs_number', 'fgs_grs.grs_date', 'fgs_grs.created_at')
                ->where('fgs_grs_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function get_min($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_min_item')
                ->join('fgs_min_item_rel', 'fgs_min_item.id', '=', 'fgs_min_item_rel.item')
                ->join('fgs_min', 'fgs_min_item_rel.master', '=', 'fgs_min.id')
                ->select('fgs_min.min_number', 'fgs_min.min_date', 'fgs_min.created_at')
                ->where('fgs_min_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function get_cgrs($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_cgrs_item')
                ->join('fgs_cgrs_item_rel', 'fgs_cgrs_item.id', '=', 'fgs_cgrs_item_rel.item')
                ->join('fgs_cgrs', 'fgs_cgrs_item_rel.master', '=', 'fgs_cgrs.id')
                ->select('fgs_cgrs.cgrs_number', 'fgs_cgrs.cgrs_date', 'fgs_cgrs.created_at')
                ->where('fgs_cgrs_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function get_cmin($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_cmin_item')
                ->join('fgs_cmin_item_rel', 'fgs_cmin_item.id', '=', 'fgs_cmin_item_rel.item')
                ->join('fgs_cmin', 'fgs_cmin_item_rel.master', '=', 'fgs_cmin.id')
                ->select('fgs_cmin.cmin_number', 'fgs_cmin.cmin_date', 'fgs_cmin.created_at')
                ->where('fgs_cmin_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function get_cmtq($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_cmtq_item')
                ->join('fgs_cmtq_item_rel', 'fgs_cmtq_item.id', '=', 'fgs_cmtq_item_rel.item')
                ->join('fgs_cmtq', 'fgs_cmtq_item_rel.master', '=', 'fgs_cmtq.id')
                ->select('fgs_cmtq.cmtq_number', 'fgs_cmtq.cmtq_date', 'fgs_cmtq.created_at')
                ->where('fgs_cmtq_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function get_coef($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_coef_item')
                ->join('fgs_coef_item_rel', 'fgs_coef_item.id', '=', 'fgs_coef_item_rel.item')
                ->join('fgs_coef', 'fgs_coef_item_rel.master', '=', 'fgs_coef.id')
                ->select('fgs_coef.coef_number', 'fgs_coef.coef_date', 'fgs_coef.created_at')
                ->where('fgs_coef_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }

    public function get_cpi($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_cpi_item')
                ->join('fgs_cpi_item_rel', 'fgs_cpi_item.id', '=', 'fgs_cpi_item_rel.item')
                ->join('fgs_cpi', 'fgs_cpi_item_rel.master', '=', 'fgs_cpi.id')
                ->select('fgs_cpi.cpi_number', 'fgs_cpi.cpi_date', 'fgs_cpi.created_at')
                ->where('fgs_cpi_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }

    // public function get_dni($id)
    // {
    //     if (!empty($id)) {
    //         $details = DB::table('fgs_dni_item')
    //             ->join('fgs_dni_item_rel', 'fgs_dni_item.id', '=', 'fgs_dni_item_rel.item')
    //             ->join('fgs_dni', 'fgs_dni_item_rel.master', '=', 'fgs_dni.id')
    //             ->select('fgs_dni.dni_number', 'fgs_dni.dni_date', 'fgs_dni.created_at')
    //             ->where('fgs_dni_item.product_id', '=', $id)
    //             ->first();
    //         return $details;
    //     } else {
    //         return 0;
    //     }
    // }
    public function get_mis($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_mis_item')
                ->join('fgs_mis_item_rel', 'fgs_mis_item.id', '=', 'fgs_mis_item_rel.item')
                ->join('fgs_mis', 'fgs_mis_item_rel.master', '=', 'fgs_mis.id')
                ->select('fgs_mis.mis_number', 'fgs_mis.mis_date', 'fgs_mis.created_at')
                ->where('fgs_mis_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function get_mtq($id)
    {
        if (!empty($id)) {
            $details = DB::table('fgs_mtq_item')
                ->join('fgs_mtq_item_rel', 'fgs_mtq_item.id', '=', 'fgs_mtq_item_rel.item')
                ->join('fgs_mtq', 'fgs_mtq_item_rel.master', '=', 'fgs_mtq.id')
                ->select('fgs_mtq.mtq_number', 'fgs_mtq.mtq_date', 'fgs_mtq.created_at')
                ->where('fgs_mtq_item.product_id', '=', $id)
                ->first();
            return $details;
        } else {
            return 0;
        }
    }
    public function fgsExport(Request $request)
    {
        // echo "kk";exit;
        $condition = [];
        ini_set('max_execution_time', 700);

        if ($request->item_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = [DB::raw("DATE_FORMAT(fgs_mrn.mrn_date,'%Y-%m')"), '=', date('Y-m', strtotime('01-' . $request->from))];
        } else {
            $condition[] = [DB::raw("DATE_FORMAT(fgs_mrn.mrn_date,'%Y-%m')"), '=', date('Y-m')];
        }
        // if($request->from)
        // {
        //     $condition[] = ['fgs_mrn.mrn_date', '>=', date('Y-m', strtotime($request->from))];
        //    // $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        // }
        // if($request->to)
        // {
        //     $condition[] = ['fgs_mrn.mrn_date', '<=', date('Y-m', strtotime( $request->to))];
        //    // $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->to))];
        // }
        // $threeMonthsAgo = Carbon::now()->subMonths(2);
        // $dateString = $threeMonthsAgo->format('Y-m-d');

        $datas = fgs_mrn_item::select(

            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_mrn_item.product_id',
            'batchcard_batchcard.batch_no',
            'batchcard_batchcard.id as batch_id',
            // 'fgs_mrn.mrn_number',
            'fgs_mrn.mrn_date',
            // 'fgs_mrn.created_at as mrn_wef',
            'fgs_mrn_item.id as mrn_item_id'
        )
            ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_mrn_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            ->where($condition)
            ->where('fgs_mrn_item.status', 1)
            // ->where(DB::raw("DATE_FORMAT(fgs_mrn.mrn_date, '%Y-%m-%d')"), '>=', $dateString)

            //->where('created_at', '>=', $threeMonthsAgo)
            ->distinct('fgs_mrn_item.id')
            ->orderBy('fgs_mrn_item.id', 'desc')

            ->get();
        //dd($datas);
        return Excel::download(new FGSTransactionExport($datas, $request->from), 'fgs-sales-transaction-report' . date('d-m-Y') . '.xlsx');
    }
    public function fgsinvExport(Request $request)
    {
        // echo "kk";exit;
        $condition = [];
        ini_set('max_execution_time', 500);


        if ($request->item_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if($request->from)
        {
            $condition[] = [DB::raw("DATE_FORMAT(fgs_mrn.mrn_date,'%Y-%m')"), '=', date('Y-m',strtotime('01-'.$request->from))];
           
        }
        else{
            $condition[] = [DB::raw("DATE_FORMAT(fgs_mrn.mrn_date,'%Y-%m')"), '=', date('Y-m')];

        }
        // if ($request->from) {
        //     $condition[] = ['fgs_grs.grs_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        //     $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        // }
        // if ($request->to) {
        //     $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-d', strtotime('01-' . $request->to))];
        //     // $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->to))];
        // }
        // $threeMonthsAgo = Carbon::now()->subMonths(3);
        // $dateString = $threeMonthsAgo->format('Y-m');

        $datas = fgs_mrn_item::select(
            'fgs_mrn_item.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'product_product.mrp',
            'batchcard_batchcard.batch_no',
            'batchcard_batchcard.id as batch_id',
            'fgs_mrn.mrn_number',
            'fgs_mrn.mrn_date',
            'fgs_mrn.created_at as mrn_wef',
            'fgs_mrn_item.id as mrn_item_id'
        )
            ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_mrn_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            ->where($condition)
            ->where('fgs_mrn_item.status', 1)
           // ->where(DB::raw("DATE_FORMAT(fgs_mrn.created_at,'%Y-%m')"), '>=', $dateString)
            ->distinct('fgs_mrn_item.id')
            ->orderBy('fgs_mrn_item.id', 'desc')
            ->get();
       
        return Excel::download(new FGSInvTransactionExport($datas,$request->from), 'fgs-Inv-transaction-report' . date('d-m-Y') . '.xlsx');
    }
}
