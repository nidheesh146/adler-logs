<?php

namespace App\Http\Controllers\Web\FGS;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;

class FgsreportController extends Controller
{
    public function get_data()
    {
        $product_details = DB::table('product_product')
            ->join('batchcard_batchcard', 'product_product.id', '=', 'batchcard_batchcard.product_id')
            ->select('product_product.*', 'batchcard_batchcard.batch_no')

            ->paginate(15);
        $from_date = date('m-Y');
        $to_date = date('m-Y');


        return view('pages/fgs/product-master/fin-goods-report', compact('product_details', 'from_date', 'to_date'));
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
        return view('pages/fgs/product-master/fin-goods-report', compact('product_details', 'from_date', 'to_date'));
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
}
