<?php

namespace App\Http\Controllers\Web\fgs;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_maa_stock_management;
use App\Exports\StockLocationExport;
use Validator;
class StockManagementController extends Controller
{
    public function __construct()
    {
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->production_stock_management = new production_stock_management;
    }
    public function allLocations(Request $request)
    {
        $title ="All Location - Stock";
        $location = 'all';
        $stock  = $this->fgs_product_stock_management->get_stock([]);
        //print_r(json_encode($stock));exit;
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    }
    public function location1Stock(Request $request)
    {
        $title ="Location1 - Stock";
        $location = 'location1';
        $stock  = $this->fgs_product_stock_management->get_stock(['product_stock_location.location_name'=>'Location-1']);
        //print_r(json_encode($stock));exit;
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    }
    public function location2Stock(Request $request)
    {
        $title ="Location2 - Stock";
        $location = 'location2';
        $stock  = $this->fgs_product_stock_management->get_stock(['product_stock_location.location_name'=>'Location-2']);
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    }
    public function location3Stock(Request $request)
    {
        $title ="Location3 - Stock";
        $location = 'location3';
        $stock  = $this->fgs_product_stock_management->get_stock(['product_stock_location.location_name'=>'Location-3']);
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    }
    public function locationSNN(Request $request)
    {
        $title ="SNN Mktd - Stock";
        $location = 'SNN_Mktd';
        $stock  = $this->fgs_product_stock_management->get_stock(['product_stock_location.location_name'=>'SNN Mktd']);
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    }
    public function locationAHPL(Request $request)
    {
        $title ="AHPL Mktd - Stock";
        $location = 'AHPL_Mktd';
        $stock  = $this->fgs_product_stock_management->get_stock(['product_stock_location.location_name'=>'AHPL Mktd']);
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    }
    public function MAAStock(Request $request)
    {
        $title ="Material Allocation Area(MAA) - Stock";
        $location = 'MAA';
        $stock=fgs_maa_stock_management::select('fgs_maa_stock_management.*','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no')
                            ->leftJoin('product_product','product_product.id','=','fgs_maa_stock_management.product_id')
                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_maa_stock_management.batchcard_id' )
                            ->where('fgs_maa_stock_management.quantity','!=',0)
                            ->distinct('fgs_maa_stock_management.id')
                            ->orderBy('fgs_maa_stock_management.id','DESC')
                            ->paginate(15);
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    }
    public function quarantineStock(Request $request)
    {
        $title ="Quarantine - Stock";
        // $stock ='' ;
        return view('pages/FGS/stock-management/quarantine_stock', compact('title'));
    }
    
    public function AlllocationExport(Request $request)
    {
            $location ='all';
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        //->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            return Excel::download(new StockLocationExport($stock), 'all-location-stock' . date('d-m-Y') . '.xlsx');
    
    }
    public function location1Export(Request $request)
    {
            $location ='location1';
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','Location-1')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            
            return Excel::download(new StockLocationExport($stock), 'location1-stock' . date('d-m-Y') . '.xlsx');
    
    }
    public function location2Export(Request $request)
    {
        
            $location ='location2';
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','Location-2')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            return Excel::download(new StockLocationExport($stock), 'location2-stock' . date('d-m-Y') . '.xlsx');
    }
    public function location3Export(Request $request)
    {
        
            $location ='location3';
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','Location-3')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            return Excel::download(new StockLocationExport($stock), 'location3-stock' . date('d-m-Y') . '.xlsx');
    }
    public function SNNExport(Request $request)
    {
        
            $location ='SNN';
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','SNN Mktd')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            return Excel::download(new StockLocationExport($stock), 'SNN-stock' . date('d-m-Y') . '.xlsx');
    }
    public function AHPLExport(Request $request)
    {
        
            $location ='AHPL';
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','AHPL Mktd')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            return Excel::download(new StockLocationExport($stock), 'AHPL-stock' . date('d-m-Y') . '.xlsx');
    }
    public function MAAExport(Request $request)
    {
        
            $location ='MAA';
            $stock = fgs_maa_stock_management::select('fgs_maa_stock_management.*','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no')
                            ->leftJoin('product_product','product_product.id','=','fgs_maa_stock_management.product_id')
                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_maa_stock_management.batchcard_id' )
                            ->where('fgs_maa_stock_management.quantity','!=',0)
                            ->distinct('fgs_maa_stock_management.id')
                            ->orderBy('fgs_maa_stock_management.id','DESC')
                            ->get();
            return Excel::download(new StockLocationExport($stock), 'MAA-stock' . date('d-m-Y') . '.xlsx');
    }
    public function productionStockList()
    {
        $stock = production_stock_management::select('product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','production_stock_management.stock_qty')
                    ->leftJoin('product_product','product_product.id','=','production_stock_management.product_id')
                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','production_stock_management.batchcard_id' )
                    ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                    ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                    ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                    ->distinct('production_stock_management.id')
                    ->where('production_stock_management.stock_qty','!=',0)
                    ->orderBy('production_stock_management.id','DESC')
                    ->paginate(11);
        return view('pages/FGS/stock-management/production-stock-list',compact('stock'));
    }
    public function productionStockAdd(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $validation['product'] = ['required'];
            $validation['batchcard_no'] = ['required'];
            $validation['stock_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 
                        $data['product_id'] = $request->product;
                        $data['batchcard_id'] = $request->batchcard_no;
                        $data['stock_qty'] = $request->stock_qty;
                        $this->production_stock_management->insert_data($data);
                        $request->session()->flash('success',"You have successfully added a price master !");
                        return redirect("fgs/production-stock/list");

            }
            if($validator->errors()->all()) 
            { 
                return redirect("fgs/production-stock/Add")->withErrors($validator)->withInput();
            }
        }
        else
        {
            return view('pages/FGS/stock-management/production-stock-add');
        }
    }
}
