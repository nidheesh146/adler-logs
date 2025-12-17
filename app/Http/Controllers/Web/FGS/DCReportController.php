<?php

namespace App\Http\Controllers\Web\fgs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\Models\FGS\delivery_challan_item;
use App\Models\FGS\fgs_cdc_item;
use DB;
use PDF;
use App\Models\FGS\fgs_product_stock_management;
use App\Exports\DCReportExport;
use App\Exports\CDCReportExport;

class DCReportController extends Controller
{
    public function __construct()
    {
        $this->delivery_challan_item = new delivery_challan_item;
        $this->fgs_cdc_item = new fgs_cdc_item;
    }

    
    public function DCReport(Request $request)
    {
        $condition = [];
        if($request->doc_no)
        {
            $condition[] = ['delivery_challan.doc_no','like', '%' . $request->doc_no . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->oef_no)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_no . '%'];
        }
        if($request->sku_code)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        $dc_items= $this->delivery_challan_item->getItems($condition);
        return view('pages/FGS/Delivery_challan/dc_report', compact('dc_items'));
    }
    public function DCInvTransactionReport(Request $request)
    {
        $condition = [];
        if($request->doc_no)
        {
            $condition[] = ['delivery_challan.doc_no','like', '%' . $request->doc_no . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->oef_no)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_no . '%'];
        }
        if($request->sku_code)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        $dc_items= $this->delivery_challan_item->getItems($condition);
        return view('pages/FGS/Delivery_challan/dc_inv_transcation_report', compact('dc_items'));
    }
    public function DCReportExport(Request $request)
    {
        $condition = [];
        if($request->doc_no)
        {
            $condition[] = ['delivery_challan.doc_no','like', '%' . $request->doc_no . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->oef_no)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_no . '%'];
        }
        if($request->sku_code)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        $dc_items= $this->delivery_challan_item->getAllItems($condition);
        return Excel::download(new DCReportExport($dc_items), 'DCReportExport' . date('d-m-Y') . '.xlsx');

    }
    public function CDCReport(Request $request)
    {
        $condition = [];
        if($request->dc_no)
        {
            $condition[] = ['delivery_challan.doc_no','like', '%' . $request->dc_no . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->cdc_no)
        {
            $condition[] = ['fgs_cdc.cdc_number','like', '%' . $request->cdc_no . '%'];
        }
        if($request->sku_code)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        $cdc_items= $this->fgs_cdc_item->getItemsReport($condition);
        return view('pages/FGS/CDC/cdc_report', compact('cdc_items'));
    }
    public function CDCReportExport(Request $request)
    {
       // dd('hi');
        $condition = [];
        if($request->dc_no)
        {
            $condition[] = ['delivery_challan.doc_no','like', '%' . $request->dc_no . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->cdc_no)
        {
            $condition[] = ['fgs_cdc.cdc_number','like', '%' . $request->cdc_no . '%'];
        }
        if($request->sku_code)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        $cdc_items= $this->fgs_cdc_item->getAllItemsReport($condition);
        return Excel::download(new CDCReportExport($cdc_items), 'CDCReportExport' . date('d-m-Y') . '.xlsx');

    }
}
