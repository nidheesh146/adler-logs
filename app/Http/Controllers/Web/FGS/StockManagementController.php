<?php

namespace App\Http\Controllers\Web\fgs;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_maa_stock_management;
use App\Exports\StockLocationExport;
class StockManagementController extends Controller
{
    public function __construct()
    {
        $this->fgs_product_stock_management = new fgs_product_stock_management;
    }
    public function location1Stock(Request $request)
    {
        $title ="Location1 - Stock";
        $location = 'location1';
        $stock  = $this->fgs_product_stock_management->get_stock(['product_stock_location.location_name'=>'Location-1']);
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    }
    public function location2Stock(Request $request)
    {
        $title ="Location2 - Stock";
        $location = 'location2';
        $stock  = $this->fgs_product_stock_management->get_stock(['product_stock_location.location_name'=>'Location-2']);
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    }
    public function MAAStock(Request $request)
    {
        $title ="MAA - Stock";
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
        $title ="Qurantine - Stock";
        // $stock ='' ;
        return view('pages/FGS/stock-management/quarantine_stock', compact('title'));
    }

    public function location1Export(Request $request)
    {
            $location ='location1';
            return Excel::download(new StockLocationExport($location), 'location1-stock' . date('d-m-Y') . '.xlsx');
    
    }
    public function location2Export(Request $request)
    {
        
            $location ='location2';
            return Excel::download(new StockLocationExport($location), 'location2-stock' . date('d-m-Y') . '.xlsx');
    }
    public function MAAExport(Request $request)
    {
        
            $location ='MAA';
            return Excel::download(new StockLocationExport($location), 'MAA-stock' . date('d-m-Y') . '.xlsx');
    }
}
