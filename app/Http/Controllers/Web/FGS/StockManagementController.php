<?php

namespace App\Http\Controllers\Web\fgs;
use App\Exports\MAAStockExport;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_qurantine_stock_management;
use App\Models\FGS\product_product;
use App\Models\fgs_item_master;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_mrn_item;
use App\Models\product;
use App\Models\batchcard;
use App\Exports\StockLocationExport;
use App\Exports\StockLocationAllExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use App\Models\FGS\batchcard_batchcard;
use Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

use DB;
use App\Exports\BatchTraceExport;

class StockManagementController extends Controller
{
    public function __construct()
    {
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->production_stock_management = new production_stock_management;
        $this->fgs_qurantine_stock_management = new fgs_qurantine_stock_management;
        $this->fgs_maa_stock_management = new fgs_maa_stock_management;
        $this->product_product = new product_product;
        $this->fgs_item_master = new fgs_item_master;
        $this->fgs_product_category = new fgs_product_category;
        $this->fgs_mrn_item = new fgs_mrn_item;
    }   
    public function allLocations(Request $request)
    {
       // dd('test');
       // $this->upload_stock();
        $condition = [];
        $wherein = [1,2,3,6,7,10,11,16,24];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
         //$condition[] = ['product_stock_location.location_name', 'like', '%' . 'Location-1(Std.)' . '%'];
        // $condition[] = ['product_stock_location.location_name', 'like', '%' . 'Location-2(Non-Std.)' . '%'];
        // // $condition[] = ['product_stock_location.location_name', 'like', '%' . 'Location-3(CSL)' . '%'];
        // $condition[] = ['product_stock_location.location_name', 'like', '%' . 'SNN Mktd' . '%'];
        // $condition[] = ['product_stock_location.location_name', 'like', '%' . 'AHPL Mktd' . '%'];
        // $condition[] = ['product_stock_location.location_name', 'like', '%' . 'SNN Trade' . '%'];
        // $condition[] = ['product_stock_location.location_name', 'like', '%' . 'SNN OEM' . '%'];
        $title = "All Location - Stock";
        $location = 'all';
        $stock  = $this->fgs_product_stock_management->get_stock($condition,$wherein);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        //print_r(json_encode($stock));exit;
        return view('pages/FGS/stock-management/location1stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    }
    public function location1Stock(Request $request)
    {
      //  dd('location');
        $condition = [];
        $wherein = [1];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $title = "Location1 - Stock";
        $location = 'location1';
        $condition[] = ['product_stock_location.location_name', 'like', '%' . 'Location-1(Std.)' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition, $wherein);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        //print_r(json_encode($stock));exit;
        return view('pages/FGS/stock-management/location1stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    }
    public function location2Stock(Request $request)
    {//dd('hi');
        $condition = [];
        $wherein = [2];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $title = "Location2 - Stock";
        $location = 'location2';
        $condition[] = ['product_stock_location.location_name', 'like', '%' . 'Location-2(Non-Std.)' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition,$wherein);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    }
    public function location3Stock(Request $request)
    {
        $condition = [];
        $wherein = [3];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $title = "Location3 - Stock";
        $location = 'location3';
        $condition[] = ['product_stock_location.location_name', 'like', '%' . 'Location-3(CSL)' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition,$wherein);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    }
    public function locationSNN(Request $request)
    {
        $condition = [];
        $wherein = [6];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $title = "SNN Mktd - Stock";
        $location = 'SNN_Mktd';
        $condition[] = ['product_stock_location.location_name', 'like', '%' . 'SNN Mktd' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition,$wherein);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    }
    public function JayonStock(Request $request)
    {
        $condition = [];
        $wherein = [10];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $title = "Jayon Mktd";
        $location = 'Jayon_Mktd';
        $condition[] = ['product_stock_location.location_name', 'like', '%' . 'Jayon_Mktd' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition,$wherein);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    
    }
    public function locationSNNTrade(Request $request)
    {
        $condition = [];
        $wherein = [10];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $title = "SNN Trade";
        $location = 'SNN_Trade';
        $condition[] = ['product_stock_location.location_name', 'like', '%' . 'SNN Trade' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition,$wherein);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    }
    public function locationAHPL(Request $request)
    {
        //dd('hi');
        $condition = [];
        $wherein = [7];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $title = "AHPL Mktd - Stock";
        $location = 'AHPL_Mktd';
        $condition[] = ['product_stock_location.location_name', 'like', '%' . 'AHPL Mktd' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition,$wherein);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    }
    public function MAAStock(Request $request)
    {
       // dd('hi');
        $condition = [];
    
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->customer_name) {
            $condition[] = ['customer_supplier.firm_name', 'like', '%' . $request->customer_name . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile !== null) {
            $condition[] = ['fgs_item_master.is_sterile', '=', $request->is_sterile];
        }
    
        $grsQuery = DB::table('fgs_grs_item')
            ->select(
                DB::raw("'grs' as source"),
                'fgs_grs.id as ref_id',
                'fgs_grs.grs_date as ref_date',
                'fgs_grs.grs_number as ref_number',
                'fgs_item_master.sku_code',
                'fgs_item_master.hsn_code',
                'batchcard_batchcard.batch_no',
                'fgs_item_master.discription',
                'fgs_product_category.category_name',
                'fgs_product_category_new.category_name as new_category_name',
                'product_group1.group_name',
                'fgs_grs_item.remaining_qty_after_cancel as quantity',
                'fgs_oef_item.rate',
                'fgs_oef_item.discount',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'customer_supplier.firm_name',
                'fgs_item_master.is_sterile',
                'fgs_mrn_item.manufacturing_date',
                'fgs_mrn_item.expiry_date',
            )
            ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
           ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_grs.customer_id') // ✅ THIS is correct
            
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_grs.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_grs.new_product_category')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
            ->where('fgs_grs.status', '=', 1)
            ->where('fgs_grs_item.status', '=', 1)
            ->where('fgs_oef_item.status', '=', 1)
            ->where('fgs_oef_item.coef_status', '=', 0)
            ->where('fgs_grs_item.cgrs_status', '=', 0)
            ->whereNotIn('fgs_grs_item.id', function ($query) {
                $query->select('fgs_pi_item.grs_item_id')->from('fgs_pi_item');
            })
            ->where($condition);
    
            $piQuery = DB::table('fgs_pi_item_rel')
            ->select(
                DB::raw("'pi' as source"),
                'fgs_pi.id as ref_id',
                'fgs_pi.pi_date as ref_date',
                'fgs_pi.pi_number as ref_number',
                'fgs_item_master.sku_code',
                'fgs_item_master.hsn_code',
                'customer_supplier.firm_name',
                'batchcard_batchcard.batch_no',
                'fgs_item_master.discription',
                'fgs_product_category.category_name',
                'fgs_product_category_new.category_name as new_category_name',
                'product_group1.group_name',
                'fgs_pi_item.batch_qty as quantity',
                'fgs_oef_item.rate',
                'fgs_oef_item.discount',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'fgs_mrn_item.manufacturing_date',
                'fgs_mrn_item.expiry_date',
                'fgs_item_master.is_sterile'
            )
            ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_pi_item_rel.item')
            ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
            ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
            ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_grs_item.mrn_item_id')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
            ->where('fgs_grs.status', '=', 1)
            ->where('fgs_pi.status', '=', 1)
            ->where('fgs_pi_item.status', '=', 1)
            ->where('fgs_pi_item.batch_qty', '!=', 0)
            ->where('fgs_pi_item.cpi_status', '=', 0)
            ->whereNotIn('fgs_pi_item.id', function ($query) {
                $query->select('fgs_dni_item.pi_item_id')->from('fgs_dni_item');
            })
            ->where($condition);
        
    
        $stock = $grsQuery->unionAll($piQuery)
            ->orderByDesc('ref_id')
            ->paginate(15);
    
        foreach ($stock as $item) {
            $item->location_name = 'Material Allocation Area(MAA)';
        }
    
        $title = "Material Allocation Area(MAA) - Stock";
        $location = 'MAA';
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
    
        return view('pages/FGS/stock-management/location1maastock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    }
    public function MAAStockExport(Request $request)
{
   // dd('hi');
    $condition = [];

    if ($request->sku_code) {
        $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
    }
    if ($request->batch_no) {
        $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
    }
    if ($request->customer_name) {
        $condition[] = ['customer_supplier.firm_name', 'like', '%' . $request->customer_name . '%'];
    }
    if ($request->category_name) {
        $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
    }
    if ($request->is_sterile !== null) {
        $condition[] = ['fgs_item_master.is_sterile', '=', $request->is_sterile];
    }

    $grsQuery = DB::table('fgs_grs_item')
        ->select(
            DB::raw("'grs' as source"),
            'fgs_grs.id as ref_id',
            'fgs_grs.grs_date as ref_date',
            'fgs_grs.grs_number as ref_number',
            'fgs_item_master.sku_code',
            'fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no',
            'fgs_item_master.discription',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_group1.group_name',
            'fgs_grs_item.remaining_qty_after_cancel as quantity',
            'fgs_oef_item.rate',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',
            'customer_supplier.firm_name',
            'fgs_item_master.is_sterile',
            'fgs_mrn_item.manufacturing_date',
            'fgs_mrn_item.expiry_date'
        )
        ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
        ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
       ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_grs.customer_id') // ✅ THIS is correct
        
        ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
        ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
        ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
        ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
        ->leftJoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
        ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
        ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_grs.product_category')
        ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_grs.new_product_category')
        ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
        ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
               ->where('fgs_grs.status', 1)
        ->where('fgs_grs_item.status', 1)
        ->where('fgs_oef_item.status', 1)
        ->where('fgs_oef_item.coef_status', 0)
        ->where('fgs_grs_item.cgrs_status', 0)
        ->whereNotIn('fgs_grs_item.id', function ($query) {
            $query->select('fgs_pi_item.grs_item_id')->from('fgs_pi_item');
        })
        ->where($condition);

    $piQuery = DB::table('fgs_pi_item_rel')
        ->select(
            DB::raw("'pi' as source"),
            'fgs_pi.id as ref_id',
            'fgs_pi.pi_date as ref_date',
            'fgs_pi.pi_number as ref_number',
            'fgs_item_master.sku_code',
            'fgs_item_master.hsn_code',
            'customer_supplier.firm_name',
            'batchcard_batchcard.batch_no',
            'fgs_item_master.discription',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_group1.group_name',
            'fgs_pi_item.batch_qty as quantity',
            'fgs_oef_item.rate',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',
            'fgs_mrn_item.manufacturing_date',
            'fgs_mrn_item.expiry_date',
            'fgs_item_master.is_sterile'
        )
        ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_pi_item_rel.item')
        ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
        ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
        ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
        ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
        ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
        ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
        ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
        ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_grs_item.mrn_item_id')
        ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
        ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
        ->leftJoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
        ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
        ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
                ->where('fgs_grs.status', '=', 1)
        ->where('fgs_pi.status', '=', 1)
        ->where('fgs_pi_item.status', '=', 1)
        ->where('fgs_pi_item.batch_qty', '!=', 0)
        ->where('fgs_pi_item.cpi_status', '=', 0)
        ->whereNotIn('fgs_pi_item.id', function ($query) {
            $query->select('fgs_dni_item.pi_item_id')->from('fgs_dni_item');
        })
        ->where($condition);

    $data = $grsQuery->unionAll($piQuery)->orderByDesc('ref_id')->get();

    foreach ($data as $item) {
        $item->location_name = 'Material Allocation Area(MAA)';
    }

    return Excel::download(new MAAStockExport($data), 'FGS_OFF_SHELF_STOCK_MAA_Report_' . date('d-m-Y') . '.xlsx');
}
    public function quarantineStock(Request $request)
    {
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $title = "Quarantine - Stock";
        $location = 'Quarantine';
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        $stock = fgs_qurantine_stock_management::select(
            'fgs_qurantine_stock_management.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no',
            'product_type.product_type_name',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile'
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_qurantine_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_qurantine_stock_management.batchcard_id')
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            ->where('fgs_qurantine_stock_management.quantity', '!=', 0)
            ->where($condition)
            ->distinct('fgs_qurantine_stock_management.id')
            ->orderBy('fgs_qurantine_stock_management.id', 'DESC')
            ->paginate(15);
        foreach ($stock as $stck) {
            $stck['location_name'] = 'Quarantine';
        }
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        return view('pages/FGS/stock-management/quarantine_stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory'));
    }

    public function AlllocationExport(Request $request)
    {
       // dd('test');
        $location = 'all';
        $condition = [];
        $wherein = [1,2,3,6,7,10,11,16,24];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        //$title ="All Location - Stock";
        $location = 'all';
        //$stock  = $this->fgs_product_stock_management->get_stock_export($condition);
        $stock = fgs_product_stock_management::select(
            'fgs_product_stock_management.manufacturing_date',
            'fgs_product_stock_management.expiry_date',
            'fgs_product_stock_management.quantity',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'fgs_item_master.hsn_code',
            'product_type.product_type_name',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile',
            'product_stock_location.location_name'
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_product_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_product_stock_management.stock_location_id')
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            ->where('fgs_product_stock_management.quantity', '>', 0)
            ->whereIn('product_stock_location.id',$wherein)
            //->distinct('fgs_product_stock_management.id')
            ->where($condition)
            ->groupBy('fgs_product_stock_management.id')
            ->orderBy('fgs_product_stock_management.id', 'DESC')
            ->get();
        /*$maa = fgs_maa_stock_management::select(
            'fgs_maa_stock_management.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile',
            'fgs_item_master.hsn_code',
            'product_type.product_type_name',
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_maa_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_maa_stock_management.batchcard_id')
            //->leftJoin('fgs_mrn_item', 'fgs_mrn_item.batchcard_id', '=', 'batchcard_batchcard.id')
            //->leftJoin('product_stock_location','product_stock_location.id','=',4 )
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            ->where('fgs_maa_stock_management.quantity', '!=', 0)
            ->where($condition)
            ->distinct('fgs_maa_stock_management.id')
            ->orderBy('fgs_maa_stock_management.id', 'DESC')
            ->get();
            foreach ($maa as $stck) 
            {
                $fgs_stock = DB::table('fgs_mrn_item')->where('batchcard_id','=',$stck['batchcard_id'])->where('product_id','=',$stck['product_id'])->where('status','=',1)->first();
                if( $fgs_stock)
                {
                $stck['manufacturing_date'] = $fgs_stock->manufacturing_date;
                $stck['expiry_date'] = $fgs_stock->expiry_date;
                }
                else
                {
                    $stck['manufacturing_date'] = '0000-00-00';
                    $stck['expiry_date'] = '0000-00-00';
                }
            }*/
            $qurantine = fgs_qurantine_stock_management::select(
                'fgs_qurantine_stock_management.*',
                'fgs_item_master.sku_code',
                'fgs_item_master.discription',
                'fgs_item_master.hsn_code',
                'batchcard_batchcard.batch_no',
                'product_type.product_type_name',
                'product_group1.group_name',
                'fgs_product_category.category_name',
                'fgs_product_category_new.category_name as new_category_name',
                'product_oem.oem_name',
                'fgs_item_master.quantity_per_pack',
                'fgs_item_master.is_sterile'
            )
                ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_qurantine_stock_management.product_id')
                ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_qurantine_stock_management.batchcard_id')
                ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
                ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
                ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
                ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
                ->where('fgs_qurantine_stock_management.quantity', '!=', 0)
                ->where($condition)
                ->distinct('fgs_qurantine_stock_management.id')
                ->orderBy('fgs_qurantine_stock_management.id', 'DESC')
                ->get();
                return Excel::download(new StockLocationAllExport($stock, $qurantine), 'FGS-on shelf stock_All (' . date('d-m-Y') . ').xlsx');
            }
    public function location1Export(Request $request)
{
    //dd('test');
    $location = 'location1';
    $condition = [];
    $wherein = [1]; // same as location1Stock

    if ($request->sku_code) {
        $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
    }
    if ($request->batch_no) {
        $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
    }
    if ($request->category_name) {
        $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
    }
    if ($request->is_sterile === "1" || $request->is_sterile === "0") {
        $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
    }

    $condition[] = ['product_stock_location.location_name', 'like', '%' . 'Location-1(Std.)' . '%'];

    // Use the same method as location1Stock
    $stock = $this->fgs_product_stock_management->get_location1_stock($condition, $wherein);
    
    return Excel::download(new StockLocationExport($stock), 'FGS-on shelf_location1-stock-' . date('d-m-Y') . '.xlsx');
}
public function location2Export(Request $request)
{
    $condition = [];
    $wherein = [2];

    if ($request->sku_code) {
        $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
    }
    if ($request->batch_no) {
        $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
    }
    if ($request->category_name) {
        $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
    }
    if ($request->is_sterile === '1' || $request->is_sterile === '0') {
        $condition[] = ['fgs_item_master.is_sterile', '=', $request->is_sterile];
    }

    // Add location name filter just like in blade
    $condition[] = ['product_stock_location.location_name', 'like', '%Location-2(Non-Std.)%'];

    // Reuse same model method from Blade
    $stock = $this->fgs_product_stock_management->get_location2_stock($condition, $wherein);

    return Excel::download(new StockLocationExport($stock), 'FGS-on shelf_location2-stock-' . date('d-m-Y') . '.xlsx');
}

    // public function location2Export(Request $request)
    // {
    //     $condition = [];
    //     if ($request->sku_code) {
    //         $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
    //     }
    //     if ($request->batch_no) {
    //         $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
    //     }
    //     if ($request->category_name) {
    //         $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
    //     }
    //     if ($request->is_sterile == 1) {
    //         $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
    //     }
    //     if ($request->is_sterile == 0) {
    //         $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
    //     }

    //     $location = 'location2';
    //     $stock = fgs_product_stock_management::select(
    //         'fgs_product_stock_management.manufacturing_date',
    //         'fgs_product_stock_management.expiry_date',
    //         'fgs_product_stock_management.quantity',
    //         'fgs_item_master.sku_code',
    //         'fgs_item_master.discription',
    //         'batchcard_batchcard.batch_no',
    //         'fgs_item_master.hsn_code',
    //         'product_type.product_type_name',
    //         'product_group1.group_name',
    //         'fgs_product_category.category_name',
    //         'fgs_product_category_new.category_name as new_category_name',
    //         'product_oem.oem_name',
    //         'fgs_item_master.quantity_per_pack',
    //         'fgs_item_master.is_sterile',
    //         'product_stock_location.location_name'
    //     )
    //         ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_product_stock_management.product_id')
    //         ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
    //         ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_product_stock_management.stock_location_id')
    //         ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
    //         ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
    //         ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
    //         ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
    //         ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
    //         ->where('product_stock_location.location_name', '=', 'Location-2(Non-Std.)')
    //         ->where('fgs_product_stock_management.quantity', '!=', 0)
    //         //->distinct('fgs_product_stock_management.id')
    //         ->where($condition)
    //         ->orderBy('fgs_product_stock_management.id', 'DESC')
    //         ->get();
    //     return Excel::download(new StockLocationExport($stock), 'location2-stock' . date('d-m-Y') . '.xlsx');
    // }
    public function location3Export(Request $request)
    {
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $location = 'location3';
        $stock = fgs_product_stock_management::select(
            'fgs_product_stock_management.manufacturing_date',
            'fgs_product_stock_management.expiry_date',
            'fgs_product_stock_management.quantity',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'fgs_item_master.hsn_code',
            'product_type.product_type_name',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile',
            'product_stock_location.location_name'
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_product_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_product_stock_management.stock_location_id')
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            ->where('product_stock_location.location_name', '=', 'Location-3(CSL)')
            ->where('fgs_product_stock_management.quantity', '!=', 0)
            //->distinct('fgs_product_stock_management.id')
            ->where($condition)
            ->orderBy('fgs_product_stock_management.id', 'DESC')
            ->get();
        return Excel::download(new StockLocationExport($stock), 'FGS-on shelf_location3-stock' . date('d-m-Y') . '.xlsx');
    }
    public function SNNExport(Request $request)
    {

        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }

        $location = 'SNN';
        $stock = fgs_product_stock_management::select(
            'fgs_product_stock_management.manufacturing_date',
            'fgs_product_stock_management.expiry_date',
            'fgs_product_stock_management.quantity',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'fgs_item_master.hsn_code',
            'product_type.product_type_name',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile',
            'product_stock_location.location_name'
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_product_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_product_stock_management.stock_location_id')
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            ->where('product_stock_location.location_name', '=', 'SNN Mktd')
            ->where('fgs_product_stock_management.quantity', '!=', 0)
            //->distinct('fgs_product_stock_management.id')
            ->where($condition)
            ->orderBy('fgs_product_stock_management.id', 'DESC')
            ->get();
        return Excel::download(new StockLocationExport($stock), 'FGS-on shelf_SNNMKtd-stock' . date('d-m-Y') . '.xlsx');
    }
    public function SNNTradeExport(Request $request)
    {
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $location = 'SNN_Trade';
        $stock = fgs_product_stock_management::select(
            'fgs_product_stock_management.manufacturing_date',
            'fgs_product_stock_management.expiry_date',
            'fgs_product_stock_management.quantity',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'fgs_item_master.hsn_code',
            'product_type.product_type_name',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile',
            'product_stock_location.location_name'
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_product_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_product_stock_management.stock_location_id')
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            ->where('product_stock_location.location_name', '=', 'SNN Trade')
            ->where($condition)
            ->where('fgs_product_stock_management.quantity', '!=', 0)
            //->distinct('fgs_product_stock_management.id')
            ->orderBy('fgs_product_stock_management.id', 'DESC')
            ->get();
        return Excel::download(new StockLocationExport($stock), 'FGS-on shelf_SNNTrade-stock' . date('d-m-Y') . '.xlsx');
    }
    public function AHPLExport(Request $request)
    {
        $condition = [];
        $wherein = [7]; // same as in Blade
    
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile === '1' || $request->is_sterile === '0') {
            $condition[] = ['fgs_item_master.is_sterile', '=', $request->is_sterile];
        }
    
        // Add location condition like Blade method
        $condition[] = ['product_stock_location.location_name', 'like', '%AHPL Mktd%'];
    
        // Reuse Blade logic
        $stock = $this->fgs_product_stock_management->get_AHPL_stock($condition, $wherein);
    
        return Excel::download(new StockLocationExport($stock), 'FGS-on_shelf_AHPL-stock-' . date('d-m-Y') . '.xlsx');
    }
    
    public function MAAExport(Request $request)
    {

        $location = 'MAA';
        $stock = fgs_maa_stock_management::select(
            'fgs_maa_stock_management.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile',
            'fgs_item_master.hsn_code',
            'product_type.product_type_name',
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_maa_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_maa_stock_management.batchcard_id')
            //->leftJoin('fgs_mrn_item', 'fgs_mrn_item.batchcard_id', '=', 'batchcard_batchcard.id')
            //->leftJoin('product_stock_location','product_stock_location.id','=',4 )
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            ->where('fgs_maa_stock_management.quantity', '!=', 0)
            ->distinct('fgs_maa_stock_management.id')
            ->orderBy('fgs_maa_stock_management.id', 'DESC')
            ->get();
            foreach ($stock as $stck) 
            {
                $fgs_stock = DB::table('fgs_mrn_item')->where('batchcard_id','=',$stck['batchcard_id'])->where('product_id','=',$stck['product_id'])->where('status',1)->first();
                if( $fgs_stock)
                {
                $stck['manufacturing_date'] = $fgs_stock->manufacturing_date;
                $stck['expiry_date'] = $fgs_stock->expiry_date;
                }
                else
                {
                    $stck['manufacturing_date'] = '0000-00-00';
                    $stck['expiry_date'] = '0000-00-00';
                }
            $stck['location_name'] = 'Material Allocation Area(MAA)';

            }
           
        // $stock = fgs_maa_stock_management::select(
        //     'fgs_maa_stock_management.*',
        //     'fgs_item_master.sku_code',
        //     'fgs_item_master.discription',
        //     'batchcard_batchcard.batch_no',
        //     'fgs_item_master.hsn_code',
        //     'batchcard_batchcard.batch_no',
        //     'product_type.product_type_name',
        //     'product_group1.group_name',
        //     'fgs_product_category.category_name',
        //     'product_oem.oem_name',
        //     'fgs_item_master.quantity_per_pack',
        //     'fgs_item_master.is_sterile',
            
        // )
        //     ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_maa_stock_management.product_id')
        //     ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_maa_stock_management.batchcard_id')
        //     ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
        //     ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
        //     ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
        //     ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
        //     //->leftJoin('fgs_mrn_item','fgs_mrn_item.batchcard_id','=','fgs_maa_stock_management.batchcard_id' )
        //     ->where('fgs_maa_stock_management.quantity', '!=', 0)
        //     ->distinct('fgs_maa_stock_management.id')
        //     ->distinct('fgs_maa_stock_management.batchcard_id')
        //     ->orderBy('fgs_maa_stock_management.id', 'DESC')
        //     ->get();
        // foreach ($stock as $stck) {
        //     $fgs_stock = DB::table('fgs_mrn_item')->where('batchcard_id','=',$stck['batchcard_id'])->where('product_id','=',$stck['product_id'])->first();
        //     if( $fgs_stock)
        //     {
        //     $stck['manufacturing_date'] = $fgs_stock->manufacturing_date;
        //     $stck['expiry_date'] = $fgs_stock->expiry_date;
        //     }
        //     else
        //     {
        //         $stck['manufacturing_date'] = '0000-00-00';
        //         $stck['expiry_date'] = '0000-00-00';
        //     }
        //     $stck['location_name'] = 'Material Allocation Area(MAA)';
        // }
        return Excel::download(new StockLocationExport($stock), 'MAA-stock' . date('d-m-Y') . '.xlsx');
    }
    public function QurantineExport(Request $request)
    {

        $location = 'Quarantine';
        $stock = fgs_qurantine_stock_management::select(
            'fgs_qurantine_stock_management.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no',
            'product_type.product_type_name',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile'
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_qurantine_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_qurantine_stock_management.batchcard_id')
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            //->leftJoin('product_stock_location','product_stock_location.id','=',5 )
            ->where('fgs_qurantine_stock_management.quantity', '!=', 0)
            ->where($condition)
            //->distinct('fgs_qurantine_stock_management.id')
            ->orderBy('fgs_qurantine_stock_management.id', 'DESC')
            ->get();
        foreach ($stock as $stck) {
            $stck['location_name'] = 'Quarantine';
        }
        return Excel::download(new StockLocationExport($stock), 'Qurantine-stock' . date('d-m-Y') . '.xlsx');
    }
    public function productionStockList(Request $request)
    {
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->group_name) {
            $condition[] = ['product_group1.group_name', 'like', '%' . $request->group_name . '%'];
        }
        $stock = production_stock_management::select(
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'fgs_item_master.hsn_code',
            'product_type.product_type_name',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile',
            'production_stock_management.stock_qty'
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'production_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'production_stock_management.batchcard_id')
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            ->distinct('production_stock_management.id')
            ->where('production_stock_management.stock_qty', '!=', 0)
            ->where($condition)
            ->orderBy('production_stock_management.id', 'DESC')
            ->paginate(11);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');

        return view('pages/FGS/stock-management/production-stock-list', compact('stock', 'pcondition'));
    }
    public function productionStockAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['product'] = ['required'];
            $validation['batchcard_no'] = ['required'];
            $validation['stock_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                $data['product_id'] = $request->product;
                $data['batchcard_id'] = $request->batchcard_no;
                $data['stock_qty'] = $request->stock_qty;
                $this->production_stock_management->insert_data($data);
                $request->session()->flash('success', "You have successfully added a price master !");
                return redirect("fgs/production-stock/list");
            }
            if ($validator->errors()->all()) {
                return redirect("fgs/production-stock/Add")->withErrors($validator)->withInput();
            }
        } else {
            return view('pages/FGS/stock-management/production-stock-add');
        }
    }
    public function batchTraceReport(Request $request)
    {
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
       // $mrn_item = fgs_mrn_item::select(
        // $mrn_item = fgs_mrn_item::select(
        //     'fgs_mrn_item.*',
        //     //'fgs_mrn_item.product_id as mrnprd',
        //     'fgs_mrn.id as mrn_id',
        //     'fgs_mrn.mrn_number',
        //     'fgs_mrn.mrn_date',
        //     'fgs_mrn.stock_location as mrn_stklocation',
        //     'batchcard_batchcard.batch_no',
        //     'fgs_item_master.sku_code',
        //     'fgs_item_master.discription',
        //     'fgs_item_master.hsn_code',
        //     'fgs_grs.grs_number',
        //     'fgs_grs.grs_date',
        //     'fgs_grs_item.remaining_qty_after_cancel as grsremaining',
        //     'fgs_grs_item.batch_quantity as grs_qty',
        //     'fgs_pi.pi_number',
        //     'fgs_pi.pi_date',
        //     'fgs_pi_item.remaining_qty_after_cancel as piremaining',
        //     'fgs_pi_item.batch_qty as pi_qty',
        //     'fgs_dni.dni_number',
        //     'fgs_dni.dni_date',
        //     'fgs_min.min_number',
        //     'fgs_min.min_date',
        //     'fgs_min_item.quantity as minqty',
        //     'fgs_min.stock_location as min_stkloc',
        //     'fgs_min_item.product_id as minpr',
        //     'fgs_min_item.batchcard_id as minbat',
        //     'fgs_mtq_item.quantity as mtqqty',
        //     'fgs_mtq.mtq_date',
        //     'fgs_mtq.mtq_number',
        //     'fgs_cmin.cmin_number',
        //     'fgs_cmin.stock_location as cminstk',
        //     'fgs_cmin_item.quantity as cminqty',
        //     'fgs_cmin_item.batchcard_id as cminbtch',
        //     'fgs_cmin_item.product_id as cminprd',
        //     'fgs_cpi_item.quantity as cpiqty',
        //     'fgs_cpi.cpi_number',
        //     'fgs_cpi_item.batchcard_id as cpibatchid',
        //     'fgs_cpi_item.product_id as cpiprd',
        //     'fgs_cgrs.cgrs_number',
        //     'fgs_cgrs_item.batch_quantity as cgrsqty'
        // )

        //     ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
        //     ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')

        //     ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
        //     ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')

        //     ->leftJoin('fgs_grs_item', 'fgs_grs_item.mrn_item_id', '=', 'fgs_mrn_item.id')
        //     ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
        //     ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')

        //     ->leftJoin('fgs_pi_item', 'fgs_pi_item.mrn_item_id', '=', 'fgs_mrn_item.id')
        //     ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
        //     ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')

        //     ->leftJoin('fgs_dni_item', 'fgs_dni_item.pi_item_id', '=', 'fgs_pi_item.id')
        //     ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
        //     ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')

        //     ->leftjoin('fgs_min_item', 'fgs_min_item.batchcard_id', 'batchcard_batchcard.id')
        //     ->leftJoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
        //     ->leftJoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')

        //     ->leftjoin('fgs_mtq_item', 'fgs_mtq_item.batchcard_id', 'batchcard_batchcard.id')
        //     ->leftJoin('fgs_mtq_item_rel', 'fgs_mtq_item_rel.item', '=', 'fgs_mtq_item.id')
        //     ->leftJoin('fgs_mtq', 'fgs_mtq.id', '=', 'fgs_mtq_item_rel.master')

        //     // ->leftjoin('fgs_cmin_item', 'fgs_cmin_item.batchcard_id', 'batchcard_batchcard.id')
        //     // ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
        //     // ->leftJoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')
        //     ->leftJoin('fgs_cmin', 'fgs_cmin.min_id', '=','fgs_min.id')
        //     ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.master', '=', 'fgs_cmin.id')
        //     ->leftJoin('fgs_cmin_item', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')

        //     ->leftjoin('fgs_cpi_item', 'fgs_cpi_item.batchcard_id', 'batchcard_batchcard.id')
        //     ->leftJoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
        //     ->leftJoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master')

        //     ->leftJoin('fgs_cgrs', 'fgs_cgrs.grs_id', '=','fgs_grs.id')
        //     ->leftJoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.master', '=', 'fgs_cgrs.id')
        //     ->leftJoin('fgs_cgrs_item', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')

        //     ->where('fgs_mrn.status',1)
        //     //->where('fgs_mrn_item.status',1)

        //     ->where($condition)
        //     ->groupBy('fgs_mrn.id')

        //     // ->distinct('fgs_mrn.id')
        //     // ->distinct('fgs_grs.id')
        //     ->paginate(15);
        //dd($mrn_item);

        return view('pages/FGS/stock-management/batch-trace');
    }

    // public function batchTraceReportExport(Request $request)
    // {
    //     $condition = [];
    //     if ($request->sku_code) {
    //         $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
    //     }
    //     if ($request->batch_no) {
    //         $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
    //     }
    //     $mrn_item = fgs_mrn_item::select(
    //         'fgs_mrn_item.*',
    //         //'fgs_mrn_item.product_id as mrnprd',
    //         'fgs_mrn.id as mrn_id',
    //         'fgs_mrn.mrn_number',
    //         'fgs_mrn.mrn_date',
    //         'fgs_mrn.stock_location as mrn_stklocation',
    //         'batchcard_batchcard.batch_no',
    //         'fgs_item_master.sku_code',
    //         'fgs_item_master.discription',
    //         'fgs_item_master.hsn_code',
    //         'fgs_grs.grs_number',
    //         'fgs_grs.grs_date',
    //         'fgs_grs_item.remaining_qty_after_cancel as grsremaining',
    //         'fgs_grs_item.batch_quantity as grs_qty',
    //         'fgs_pi.pi_number',
    //         'fgs_pi.pi_date',
    //         'fgs_pi_item.remaining_qty_after_cancel as piremaining',
    //         'fgs_pi_item.batch_qty as pi_qty',
    //         'fgs_dni.dni_number',
    //         'fgs_dni.dni_date',
    //         'fgs_min.min_number',
    //         'fgs_min.min_date',
    //         'fgs_min_item.quantity as minqty',
    //         'fgs_min.stock_location as min_stkloc',
    //         'fgs_min_item.product_id as minpr',
    //         'fgs_min_item.batchcard_id as minbat',
    //         'fgs_mtq_item.quantity as mtqqty',
    //         'fgs_mtq.mtq_date',
    //         'fgs_mtq.mtq_number',
    //         'fgs_cmtq.cmtq_number',
    //         'fgs_cmin.cmin_number',
    //         'fgs_cmin.stock_location as cminstk',
    //         'fgs_cmin_item.quantity as cminqty',
    //         'fgs_cmin_item.batchcard_id as cminbtch',
    //         'fgs_cmin_item.product_id as cminprd',
    //         'fgs_cpi_item.quantity as cpiqty',
    //         'fgs_cpi.cpi_number',
    //         'fgs_cpi_item.batchcard_id as cpibatchid',
    //         'fgs_cpi_item.product_id as cpiprd',
    //         'fgs_cgrs.cgrs_number',
    //         'fgs_cgrs_item.batch_quantity as cgrsqty',
    //         'delivery_challan.doc_no',
    //         'fgs_cdc.cdc_number'
    //     )

    //         ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
    //         ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
    //         ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
    //         ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
    //         ->leftJoin('fgs_grs_item', 'fgs_grs_item.mrn_item_id', '=', 'fgs_mrn_item.id')
    //         ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
    //         ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
    //         ->leftJoin('fgs_pi_item', 'fgs_pi_item.mrn_item_id', '=', 'fgs_mrn_item.id')
    //         ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
    //         ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
    //         ->leftJoin('fgs_dni_item', 'fgs_dni_item.pi_item_id', '=', 'fgs_pi_item.id')
    //         ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
    //         ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')

    //         ->leftjoin('fgs_min_item', 'fgs_min_item.batchcard_id', 'batchcard_batchcard.id')
    //         ->leftJoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
    //         ->leftJoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')

    //         ->leftjoin('fgs_mtq_item', 'fgs_mtq_item.batchcard_id', 'batchcard_batchcard.id')
    //         ->leftJoin('fgs_mtq_item_rel', 'fgs_mtq_item_rel.item', '=', 'fgs_mtq_item.id')
    //         ->leftJoin('fgs_mtq', 'fgs_mtq.id', '=', 'fgs_mtq_item_rel.master')

    //         ->leftjoin('fgs_cmtq_item', 'fgs_cmtq_item.batchcard_id', 'batchcard_batchcard.id')
    //         ->leftJoin('fgs_cmtq_item_rel', 'fgs_cmtq_item_rel.item', '=', 'fgs_cmtq_item.id')
    //         ->leftJoin('fgs_cmtq', 'fgs_cmtq.id', '=', 'fgs_cmtq_item_rel.master')
    //         // ->leftjoin('fgs_cmin_item', 'fgs_cmin_item.batchcard_id', 'batchcard_batchcard.id')
    //         // ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
    //         // ->leftJoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')
    //         ->leftJoin('fgs_cmin_item', 'fgs_min_item.id', '=', 'fgs_cmin_item.cmin_item_id')
    //         ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
    //         ->leftJoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')

    //         ->leftjoin('fgs_cpi_item', 'fgs_cpi_item.batchcard_id', 'batchcard_batchcard.id')
    //         ->leftJoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
    //         ->leftJoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master')

    //         ->leftJoin('fgs_cgrs_item', 'fgs_grs_item.id', '=', 'fgs_cgrs_item.grs_item_id')
    //          ->leftJoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
    //         ->leftJoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')

    //         ->leftJoin('delivery_challan_item', 'delivery_challan_item.mrn_item_id', '=', 'fgs_mrn_item.id')
    //         ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
    //         ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')

    //         ->leftJoin('fgs_cdc_item', 'delivery_challan_item.id', '=', 'fgs_cdc_item.dc_item_id')
    //         ->leftJoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
    //         ->leftJoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')

    //         ->where('fgs_mrn.status', '=', 1)
    //         // ->where('fgs_grs.status', '=', 1)
    //         // ->where('fgs_pi.status', '=', 1)
    //         // ->where('fgs_dni.status', '=', 1)
    //         ->where($condition)

    //          ->groupBy('fgs_mrn_item.id')

    //         // ->distinct('fgs_mrn.id')
    //         // ->distinct('fgs_grs.id')
    //         //->paginate(15);
    //         ->get();
    //     //   dd($mrn_item);  
    //     $trace = [];
    //     $i = 1;
    //     foreach ($mrn_item as $item) {
    //         $stks = fgs_product_stock_management::where('product_id', $item->product_id)
    //         ->where('batchcard_id', $item->batchcard_id)
    //         ->first();
    //         if($stks)
    //         $stk_qty=$stks->quantity;
    //         else
    //         $stk_qty=0;      
    //          //->where('stock_location_id', $loc_id)
    //         // ->pluck('quantity');
    //         if($item->expiry_date=='NULL' || $item->expiry_date=='0000-00-00')
    //         {
    //             $exp='NA';
    //         }else{
    //             $exp=date('d-m-Y', strtotime($item->expiry_date));
    //         }
    //         $trace[] = [
    //             '#' => $i++,
    //             'sku_code' => $item->sku_code,
    //             'hsn_code' => $item->hsn_code,
    //             'description' => $item->discription,
    //             'batch_no' => $item->batch_no,
    //             'manufacture_date' => date('d-m-Y', strtotime($item->manufacturing_date)),
    //             'expiry_date' => $exp,
    //             'Supplier/Customer'=>'',
    //             'doc_name' => 'MRN',
    //             'doc_no' => $item->mrn_number,
    //             'doc_date' => $item->mrn_date,
    //             'doc_qty' => $item->quantity ,
    //             'rem_qty' => $stk_qty ,
    //         ];
    //         $minqtyrem=$stk_qty;
    //         if($item['min_number']!=null){
    //             foreach ($this->product_min($item['id']) as $min) {
    //                 $minqtyrem=$minqtyrem-$min['minqty'];
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer'=>'',
    //                     'doc_name' => 'MIN',
    //                     'doc_no' => $min->min_number,
    //                     'doc_date' => $min->min_date,
    //                     'doc_qty' => $min->minqty,
    //                     'rem_qty' => $stk_qty 
    //                 ];
    //             }   
    //         }
    //         $cminqtyre=$minqtyrem;
    //         if($item['cmin_number']!=null){
    //             foreach ($this->product_cmin($item['id']) as $cmin) {
    //                 $cminqtyre=$cminqtyre+$cmin['cminqty'];

    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer'=>'',
    //                     'doc_name' => 'CMIN',
    //                     'doc_no' => $cmin->cmin_number,
    //                     'doc_date' => $cmin->cmin_date,
    //                     'doc_qty' => $cmin->cminqty,
    //                     'rem_qty' => $stk_qty
    //                 ];
    //             }   
    //         }
    //         $grsqty=$stk_qty;
    //         if($item['grs_number']!=null){

    //             foreach ($this->product_grs($item['id']) as $grs) {
	// 							$grsqty=$grsqty-(int)$grs['grs_qty'];
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer'=>$grs->firm_name,
    //                     'doc_name' => 'GRS',
    //                     'doc_no' => $grs->grs_number,
    //                     'doc_date' => $grs->grs_date,
    //                     'doc_qty' => $grs->grs_qty,
    //                     'rem_qty' => $stk_qty
    //                 ];
    //             }   
    //         }
    //         $cgrsqty=$grsqty;
    //         if($item['cgrs_number']!=null){

    //             foreach ($this->product_cgrs($item['id']) as $cgrs) {
                    
    //                 $cgrsqty=$cgrsqty+$cgrs['cgrs_qty'];

								
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer'=>$cgrs->firm_name,
    //                     'doc_name' => 'GRS',
    //                     'doc_no' => $cgrs->cgrs_number,
    //                     'doc_date' => $cgrs->cgrs_date,
    //                     'doc_qty' => $cgrs->cgrs_qty ,
    //                     'rem_qty' => $stk_qty ,
    //                 ];
    //             }   
    //         }
    //         if($item['pi_number']!=null){
    //             foreach ($this->product_pi($item['id']) as $pi) {
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer'=>$pi->firm_name,
    //                     'doc_name' => 'PI',
    //                     'doc_no' => $pi->pi_number,
    //                     'doc_date' => $pi->pi_date,
    //                     'doc_qty' => $pi->pi_qty ,
    //                     'rem_qty' => $stk_qty
    //                 ];
    //             }   
    //         }
    //         if($item['cpi_number']!=null){
    //             foreach ($this->product_cpi($item['id']) as $cpi) {
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer'=>$cpi->firm_name,
    //                     'doc_name' => 'CPI',
    //                     'doc_no' => $cpi->cpi_number,
    //                     'doc_date' => $cpi->cpi_date,
    //                     'doc_qty' => $cpi->cpi_qty ,
    //                     'rem_qty' => $stk_qty 
    //                 ];
    //             }   
    //         }
    //         if($item['dni_number']!=null){
    //             foreach ($this->product_dni($item['id']) as $dni) {
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer'=>$dni->firm_name,
    //                     'doc_name' => 'DNI/EXI',
    //                     'doc_no' => $dni->dni_number,
    //                     'doc_date' => $dni->dni_date,
    //                     'doc_qty' => $dni->pi_qty ,
    //                     'rem_qty' => $stk_qty
    //                 ];
    //             }   
    //         }
    //         if ($item['mtq_number'] != null) {
    //             foreach ($this->product_mtq($item['id']) as $mtq) {
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer' => $mtq->firm_name,
    //                     'doc_name' => 'MTQ',
    //                     'doc_no' => $mtq->mtq_number,
    //                     'doc_date' => $mtq->mtq_date,
    //                     'doc_qty' => $mtq->mtqqty,
    //                     'rem_qty' => $stk_qty
    //                 ];
    //             }
    //         }
    //         if ($item['cmtq_number'] != null) {
    //             foreach ($this->product_cmtq($item['id']) as $cmtq) {
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer' => $cmtq->firm_name,
    //                     'doc_name' => 'CMTQ',
    //                     'doc_no' => $cmtq->cmtq_number,
    //                     'doc_date' => $cmtq->cmtq_date,
    //                     'doc_qty' => $cmtq->cmtqqty,
    //                     'rem_qty' => $stk_qty
    //                 ];
    //             }
    //         }
    //         if ($item['doc_no'] != null) {
    //             foreach ($this->product_dc($item['id']) as $dc) {
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer' => $dc->firm_name,
    //                     'doc_name' => 'DC',
    //                     'doc_no' => $dc->doc_no,
    //                     'doc_date' => $dc->doc_date,
    //                     'doc_qty' => $dc->dc_qty,
    //                     'rem_qty' => $stk_qty
    //                 ];
    //             }
    //         }
    //         if ($item['cdc_number'] != null) {
    //             foreach ($this->product_cdc($item['id']) as $cdc) {
    //                 $trace[] = [
    //                     '#' => '',
    //                     'sku_code' => '',
    //                     'hsn_code' => '',
    //                     'description' => '',
    //                     'batch_no' => '',
    //                     'manufacture_date' => '',
    //                     'expiry_date' => '',
    //                     'Supplier/Customer' => $cdc->firm_name,
    //                     'doc_name' => 'CDC',
    //                     'doc_no' => $cdc->cdc_number,
    //                     'doc_date' => $cdc->cdc_date,
    //                     'doc_qty' => $cdc->cdc_qty,
    //                     'rem_qty' => $stk_qty
    //                 ];
    //             }
    //         }
            
    //     }
    //     $header_style = (new StyleBuilder())->setFontBold()->build();
        
    //     $exportPath = storage_path('app/batchtrace/Batchtrace_' . date('d-m-Y') . '.xlsx');
    //     (new FastExcel($trace))->headerStyle($header_style)->export($exportPath);
    //     // Return the Excel file as a downloadable response
    //     return response()->download($exportPath)->deleteFileAfterSend(true);

    // }
    public function batchTraceReportExport(Request $request) {
        $condition = [];
        
        // Add conditions for filtering
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
    
        // Query to get the data
        $mrn_items = fgs_mrn_item::select(
            'batchcard_batchcard.batch_no',
            'fgs_mrn_item.manufacturing_date',  // Fetch manufacturing date from `fgs_mrn_item`
            'batchcard_batchcard.id as batchcard_id',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code'
        )
        ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
        ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
        ->where($condition)
        ->distinct('batchcard_batchcard.id')
        ->get();
    
        // Handling filename creation
        $firstItem = $mrn_items->first(); // Get the first item to extract fields for filename
        $manufacturing_date = $firstItem->manufacturing_date ?? 'unknown_date'; 
        $sku_code = $request->sku_code ?? 'unknown_sku';
        $batch_no = $firstItem->batch_no ?? 'unknown_batch';
    
        // Generate the filename dynamically
        $filename = "Batch-trace-{$manufacturing_date}-{$sku_code}-{$batch_no}.xlsx";
    
        // Export to Excel with the dynamic filename
        return Excel::download(new BatchTraceExport($mrn_items), $filename);
    }
    
    public function product_qty($pr_id, $batch_id, $loc_id)
    {
        $stk_qty = fgs_product_stock_management::where('product_id', $pr_id)
            ->where('batchcard_id', $batch_id)
            ->where('stock_location_id', $loc_id)
            ->pluck('quantity')[0];
        return $stk_qty;
    }
    public function product_btch($pr_id, $batch_id, $loc_id)
    {
        $stk_qty = DB::table('batchcard_batchcard')
            ->where('id', $batch_id)
            // ->where('stock_location_id',$loc_id)
            ->pluck('quantity');
        return $stk_qty;
    }
    public function product_grs($id)
    {

        $grs_item = fgs_mrn_item::select('fgs_grs.grs_number','customer_supplier.firm_name', 'fgs_grs.grs_date', 'fgs_grs_item.remaining_qty_after_cancel as grsremaining', 'fgs_grs_item.batch_quantity as grs_qty')
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftJoin('fgs_grs_item', 'fgs_grs_item.mrn_item_id', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_grs.customer_id')

            //->where('fgs_grs_item.cgrs_status', '=', 1)
            ->where('fgs_grs_item.status','=',1)
            ->where('fgs_mrn_item.id', $id)
            // ->distinct('fgs_mrn.id')
            ->get();
        return $grs_item;
    }
    public function product_cgrs($id)
    {

        $cgrs_item = fgs_mrn_item::select('fgs_cgrs.cgrs_number','customer_supplier.firm_name', 'fgs_cgrs.cgrs_date', 'fgs_cgrs_item.batch_quantity as cgrs_qty')
        ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
        ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
        // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
        // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
        ->leftJoin('fgs_grs_item', 'fgs_grs_item.mrn_item_id', '=', 'fgs_mrn_item.id')
        ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
        ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_grs.customer_id')
        ->leftJoin('fgs_cgrs_item', 'fgs_grs_item.id', '=', 'fgs_cgrs_item.grs_item_id')
         ->leftJoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
        ->leftJoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')
        ->where('fgs_grs_item.cgrs_status', '=', 1)
        ->where('fgs_grs_item.status', '=', 1)
        ->where('fgs_cgrs.status', '=', 1)
        ->where('fgs_mrn_item.id', $id)
        // ->distinct('fgs_mrn.id')
        ->get();
        return $cgrs_item;
    }
    public function product_min($id)
    {

        $min_item = fgs_mrn_item::select('fgs_min.min_number', 'fgs_min.min_date', 'fgs_min_item.quantity as minqty', 'fgs_min.stock_location as min_stkloc', 'fgs_min_item.product_id as minpr', 'fgs_min_item.batchcard_id as minbat')
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftjoin('fgs_min_item', 'fgs_min_item.batchcard_id', 'fgs_mrn_item.batchcard_id')
            ->leftJoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
            ->leftJoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')
            ->where('fgs_mrn.status', '=', 1)
            ->where('fgs_min.status', '=', 1)
            ->where('fgs_mrn_item.id', $id)
            // ->distinct('fgs_min.id')
            ->get();
        return $min_item;
    }
    public function product_cmin($id)
    {

        $cmin_item = fgs_mrn_item::select(
            'fgs_cmin.cmin_number',
            'fgs_cmin.stock_location as cminstk',
            'fgs_cmin_item.quantity as cminqty',
            'fgs_cmin_item.batchcard_id as cminbtch',
            'fgs_cmin_item.product_id as cminprd',
            'fgs_cmin_item.product_id as cminprd',
            'fgs_cmin.cmin_number',
        )
        ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
        ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
        // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
        // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
        // ->leftjoin('fgs_cmin_item', 'fgs_cmin_item.batchcard_id', 'batchcard_batchcard.id')
        // ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
        // ->leftJoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')

        ->leftjoin('fgs_min_item', 'fgs_min_item.batchcard_id', 'fgs_mrn_item.batchcard_id')
        ->leftJoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
        ->leftJoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')
        ->leftJoin('fgs_cmin_item', 'fgs_min_item.id', '=', 'fgs_cmin_item.cmin_item_id')
        ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
        ->leftJoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')
        ->where('fgs_mrn.status', '=', 1)
        //->where('fgs_min.status', '=', 1)
        ->where('fgs_mrn_item.id', $id)
        // ->distinct('fgs_cmin.id')
        ->get();
        return $cmin_item;
    }
    public function product_pi($id)
    {

        $pi_item = fgs_mrn_item::select(
            'fgs_pi.pi_number',
            'fgs_pi.pi_date',
            'fgs_pi_item.remaining_qty_after_cancel as piremaining',
            'fgs_pi_item.batch_qty as pi_qty',
            'customer_supplier.firm_name',
        )
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftJoin('fgs_pi_item', 'fgs_pi_item.mrn_item_id', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
            ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
            // ->where('fgs_mrn.status', '=', 1)
            // ->where('fgs_pi.status', '=', 1)
            ->where('fgs_mrn_item.id', $id)
            // ->distinct('fgs_pi.id')
            ->get();
        return $pi_item;
    }
    public function product_cpi($id)
    {

        $cpi_item = fgs_mrn_item::select(
            'fgs_cpi_item.quantity as cpiqty',
            'fgs_cpi.cpi_number',
            'fgs_cpi_item.batchcard_id as cpibatchid',
            'fgs_cpi_item.product_id',
            'customer_supplier.firm_name',
        )
        ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
        ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
        // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
        // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
        ->leftjoin('fgs_cpi_item', 'fgs_cpi_item.mrn_item_id', 'fgs_mrn_item.id')
        ->leftJoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
        ->leftJoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_cpi.customer_id')
        ->where('fgs_mrn.status', '=', 1)
        //->where('fgs_min.status', '=', 1)
        ->where('fgs_mrn_item.id', $id)
        // ->distinct('fgs_cpi.id')
        ->get();
        return $cpi_item;
    }
    public function product_dni($id)
    {

        $dni_item = fgs_mrn_item::select(
            'fgs_pi_item.batch_qty as pi_qty',
            'fgs_dni.dni_number',
            'fgs_dni.dni_date',
            'customer_supplier.firm_name',
        )
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftJoin('fgs_pi_item', 'fgs_pi_item.mrn_item_id', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
            ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftJoin('fgs_dni_item', 'fgs_dni_item.pi_item_id', '=', 'fgs_pi_item.id')
            ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_dni.customer_id')
            ->where('fgs_mrn.status', '=', 1)
            ->where('fgs_dni.status', '=', 1)
            // ->where('fgs_mrn.id', $id)
            ->where('fgs_mrn_item.id', $id)
            ->get();
        return $dni_item;
    }
    public function product_mtq($id)
    {

        $mtq_item = fgs_mrn_item::select(
            'fgs_mtq_item.quantity as mtqqty',
            'fgs_mtq.mtq_date',
            'fgs_mtq.mtq_number',
        )
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            //  ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftjoin('fgs_mtq_item', 'fgs_mtq_item.batchcard_id', 'fgs_mrn_item.batchcard_id')
            ->leftJoin('fgs_mtq_item_rel', 'fgs_mtq_item_rel.item', '=', 'fgs_mtq_item.id')
            ->leftJoin('fgs_mtq', 'fgs_mtq.id', '=', 'fgs_mtq_item_rel.master')
            ->where('fgs_mrn.status', '=', 1)
            //->where('fgs_min.status', '=', 1)
            ->where('fgs_mrn_item.id', $id)
            // ->distinct('fgs_mtq.id')
            ->get();
        return $mtq_item;
    }
    public function product_cmtq($id)
    {

        $cmtq_item = fgs_mrn_item::select(
            'fgs_cmtq_item.quantity as cmtqqty',
            'fgs_cmtq.cmtq_date',
            'fgs_cmtq.cmtq_number',
        )
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftjoin('fgs_cmtq_item', 'fgs_cmtq_item.batchcard_id', 'fgs_mrn_item.batchcard_id')
            ->leftJoin('fgs_cmtq_item_rel', 'fgs_cmtq_item_rel.item', '=', 'fgs_cmtq_item.id')
            ->leftJoin('fgs_cmtq', 'fgs_cmtq.id', '=', 'fgs_cmtq_item_rel.master')
            ->where('fgs_mrn.status', '=', 1)
            //->where('fgs_min.status', '=', 1)
            ->where('fgs_mrn_item.id', $id)
            // ->distinct('fgs_cmtq.id')
            ->get();
        return $cmtq_item;
    }
    public function product_dc($id)
    {

        $dc_item = fgs_mrn_item::select('delivery_challan.doc_no', 'customer_supplier.firm_name', 'delivery_challan.doc_date', 'delivery_challan_item.remaining_qty_after_cancel as dcremaining', 'delivery_challan_item.batch_qty as dc_qty')
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftJoin('delivery_challan_item', 'delivery_challan_item.mrn_item_id', '=', 'fgs_mrn_item.id')
            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
            //->where('fgs_grs_item.cgrs_status', '=', 1)
            ->where('delivery_challan.status', '=', 1)
            ->where('fgs_mrn.status', '=', 1)
            ->where('fgs_mrn_item.id', $id)
             ->where('delivery_challan_item.status','=',1)
            ->get();
        return $dc_item;
    }
    public function product_cdc($id)
    {

        $cdc_item = fgs_mrn_item::select('fgs_cdc.cdc_number', 'customer_supplier.firm_name', 'fgs_cdc.cdc_date', 'delivery_challan_item.remaining_qty_after_cancel as cdcremaining', 'fgs_cdc_item.quantity as cdc_qty')
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            // ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftJoin('delivery_challan_item', 'delivery_challan_item.mrn_item_id', '=', 'fgs_mrn_item.id')
            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
            ->leftJoin('fgs_cdc_item', 'fgs_cdc_item.dc_item_id', '=', 'delivery_challan_item.id')
            ->leftJoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
            ->leftJoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master')
            //->where('fgs_grs_item.cgrs_status', '=', 1)
            ->where('fgs_cdc.status', '=', 1)
            ->where('fgs_mrn_item.id', $id)
            // ->distinct('fgs_mrn_item.id')
            ->get();
        return $cdc_item;
    }
    public function SNNOEMExport(Request $request)
    {
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $location = 'SNN_OEM';
        $stock = fgs_product_stock_management::select(
            'fgs_product_stock_management.*',
            'fgs_product_stock_management.manufacturing_date',
            'fgs_product_stock_management.expiry_date',
            'fgs_product_stock_management.quantity',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'batchcard_batchcard.batch_no',
            'fgs_item_master.hsn_code',
            'product_type.product_type_name',
            'product_group1.group_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_oem.oem_name',
            'fgs_item_master.quantity_per_pack',
            'fgs_item_master.is_sterile',
            'product_stock_location.location_name'
        )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_product_stock_management.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_product_stock_management.stock_location_id')
            ->leftJoin('product_type', 'product_type.id', '=', 'fgs_item_master.product_type_id')
            ->leftJoin('product_group1', 'product_group1.id', '=', 'fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
            ->leftJoin('product_oem', 'product_oem.id', '=', 'fgs_item_master.product_oem_id')
            ->where('product_stock_location.location_name', '=', 'SNN OEM')
            ->where($condition)
            ->where('fgs_product_stock_management.quantity', '!=', 0)
            //->distinct('fgs_product_stock_management.id')
            ->orderBy('fgs_product_stock_management.id', 'DESC')
            ->get();
        return Excel::download(new StockLocationExport($stock), 'FGS_ON_STOCK-OEM-stock' . date('d-m-Y') . '.xlsx');
    }
    
    public function locationSNNOEM(Request $request)
    {
        $condition = [];
        $wherein = [11];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }
        if ($request->is_sterile == 1) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        if ($request->is_sterile == 0) {
            $condition[] = ['fgs_item_master.is_sterile', 'like', '%' . $request->is_sterile . '%'];
        }
        $title = "SNN OEM";
        $location = 'SNN_OEM';
        $condition[] = ['product_stock_location.location_name', 'like', '%' . 'SNN OEM' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition,$wherein);
        $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock', compact('title', 'stock', 'location', 'pcondition', 'pcategory')); 
    }
    public function stockUpdate(Request $request)
    {
        if($request->location_name=='Material Allocation Area(MAA)')
        {
            $condition1[] = ['fgs_maa_stock_management.id','=',$request->stock_id];
            $data1['quantity'] = $request->quantity;
            $success = $this->fgs_maa_stock_management->update_data($condition1,$data1);
        }
        elseif($request->location_name=='Quarantine')
        {
            $condition2[] = ['fgs_qurantine_stock_management.id','=',$request->stock_id];
            $data2['quantity'] = $request->quantity;
            $success = $this->fgs_qurantine_stock_management->update_data($condition2,$data2);
        }
        else
        {
            $condition3[] = ['fgs_product_stock_management.id','=',$request->stock_id];
            $data3['quantity'] = $request->quantity;
            $success = $this->fgs_product_stock_management->update_data($condition3,$data3);
        }  
        if($success)
        $request->session()->flash('success', "You have successfully updated Stock !");
        else
        $request->session()->flash('error', "You have failed to update stock !");
        return redirect()->back();
    }

    public function upload_stock()
    {

      
            $ExcelOBJ = new \stdClass();
            //$ExcelOBJ->inputFileName = 'C:\xampp\htdocs\PI.xlsx';
             $ExcelOBJ->inputFileName = 'C:\xampp\htdocs\GRS.xlsx';
            $ExcelOBJ->inputFileType = 'Xlsx';

            // $ExcelOBJ->filename = 'Book1.xlsx';
            // $ExcelOBJ->inputFileName = 'C:\xampp7.4\htdocs\mel\sampleData\Book1.xlsx';
            $ExcelOBJ->spreadsheet = new Spreadsheet();
            $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
            $ExcelOBJ->reader->setReadDataOnly(true);
            $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
            $no_column = 15;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
        
            $res = $this->Excelsplitsheet($ExcelOBJ);
                // print_r($res);exit;
                // if ($res) {
                //     $request->session()->flash('success',  "Successfully uploaded.");
                //     return redirect()->back();
                // } else {
                //     $request->session()->flash('error',  "The data already uploaded.");
                //     return redirect()->back();
                // }
            // } else {
            //     $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
            //     return redirect()->back();
            // }

            //dd($ExcelOBJ->worksheetData);
            exit;
        
    }
    public function Excelsplitsheet($ExcelOBJ)
    {
        //echo $pr_id;exit;
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;

        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) {
            $ExcelOBJ->sectionName = '';
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
            // print_r(json_encode($ExcelOBJ->worksheet));exit;
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
            $res = $this->insert_maa_items($ExcelOBJ);

            die("done");
        }
        //die("done");
        exit;

    }
    function insert_maa_items($ExcelOBJ)
    {
        //echo $pr_id;exit;
        $data = [];
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
            if ($key > 0 &&  $excelsheet[0]) {
                $batchcard_id = batchcard::where('batch_no', '=', $excelsheet[12])->pluck('batchcard_batchcard.id')->first();
                $prdct_id = product::where('sku_code', '=', $excelsheet[9])->pluck('id')->first();
                $maa_stock = fgs_maa_stock_management::select('id', 'quantity')
                    ->where('product_id', '=', $prdct_id)
                    ->where('batchcard_id', '=', $batchcard_id)
                    ->first();
                if ($maa_stock) {
                        $maa_stock_updation = $maa_stock['quantity'] + $excelsheet[13];
                        $update = $this->fgs_maa_stock_management->update_data(['id' => $maa_stock['id']], ['quantity' => $maa_stock_updation]);
                } else {
                        $stock['product_id'] = $prdct_id;
                        $stock['batchcard_id'] = $batchcard_id;
                        $stock['quantity'] = $excelsheet[13];
                        $stock['created_at'] = date('Y-m-d H:i:s');
                        $stock_add = $this->fgs_maa_stock_management->insert_data($stock);
                }

            }

        }
    }
}
