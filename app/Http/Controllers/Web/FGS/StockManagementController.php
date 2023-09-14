<?php

namespace App\Http\Controllers\Web\fgs;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_qurantine_stock_management;
use App\Models\FGS\product_product;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_mrn_item;
use App\Exports\StockLocationExport;
//use App\Models\FGS\batchcard_batchcard;
use Validator;
use DB;
use App\Exports\BatchTraceExport;
class StockManagementController extends Controller
{
    public function __construct()
    {
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->production_stock_management = new production_stock_management;
        $this->fgs_qurantine_stock_management = new fgs_qurantine_stock_management;
        $this->product_product = new product_product;
        $this->fgs_product_category = new fgs_product_category;
        $this->fgs_mrn_item = new fgs_mrn_item;
    }
    public function allLocations(Request $request)
    {
        $condition =[];
         if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
        if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $title ="All Location - Stock";
        $location = 'all';
        $stock  = $this->fgs_product_stock_management->get_stock($condition);
        $pcondition = $this->product_product->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        //print_r(json_encode($stock));exit;
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location','pcondition','pcategory'));
    }
    public function location1Stock(Request $request)
    {
        $condition =[];
          if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
        if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $title ="Location1 - Stock";
        $location = 'location1';
         $condition[] = ['product_stock_location.location_name','like', '%' . 'Location-1(Std.)' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition);
        $pcondition = $this->product_product->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        //print_r(json_encode($stock));exit;
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location','pcondition','pcategory'));
    }
    public function location2Stock(Request $request)
    {
        $condition =[];
          if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
         if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $title ="Location2 - Stock";
        $location = 'location2';
        $condition[] = ['product_stock_location.location_name','like', '%' . 'Location-2(Non-Std.)' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition);
        $pcondition = $this->product_product->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location','pcondition','pcategory'));
    }
    public function location3Stock(Request $request)
    {
        $condition =[];
         if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
        if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $title ="Location3 - Stock";
        $location = 'location3';
        $condition[] = ['product_stock_location.location_name','like', '%' . 'Location-3(CSL)' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition);
        $pcondition = $this->product_product->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location','pcondition','pcategory'));
    }
    public function locationSNN(Request $request)
    {
        $condition =[];
         if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
         if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $title ="SNN Mktd - Stock";
        $location = 'SNN_Mktd';
        $condition[] = ['product_stock_location.location_name','like', '%' . 'SNN Mktd' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition);
        $pcondition = $this->product_product->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location','pcondition','pcategory'));
    }
    public function locationSNNTrade(Request $request)
    {
        $condition =[];
         if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
         if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $title ="SNN Trade";
        $location = 'SNN_Trade';
        $condition[] = ['product_stock_location.location_name','like', '%' . 'SNN Trade' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition);
        $pcondition = $this->product_product->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location','pcondition','pcategory'));
    }
    public function locationAHPL(Request $request)
    {
        $condition =[];
         if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
        if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $title ="AHPL Mktd - Stock";
        $location = 'AHPL_Mktd';
        $condition[] = ['product_stock_location.location_name','like', '%' . 'AHPL Mktd' . '%'];
        $stock  = $this->fgs_product_stock_management->get_stock($condition);
        $pcondition = $this->product_product->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location','pcondition','pcategory'));
    }
    public function MAAStock(Request $request)
    {
        $condition =[];
         if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
        if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $title ="Material Allocation Area(MAA) - Stock";
        $location = 'MAA';
        $stock=fgs_maa_stock_management::select('fgs_maa_stock_management.*','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_product.hsn_code','product_type.product_type_name')
                            ->leftJoin('product_product','product_product.id','=','fgs_maa_stock_management.product_id')
                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_maa_stock_management.batchcard_id' )
                            //->leftJoin('product_stock_location','product_stock_location.id','=',4 )
                            ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                            ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                            ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                            ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                            ->where('fgs_maa_stock_management.quantity','!=',0)
                            ->where($condition)
                            ->distinct('fgs_maa_stock_management.id')
                            ->orderBy('fgs_maa_stock_management.id','DESC')
                            ->paginate(15);
        foreach($stock as $stck)
        {
            $stck['location_name'] = 'Material Allocation Area(MAA)';
        }
        $pcondition = $this->product_product->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        return view('pages/FGS/stock-management/location1stock',compact('title','stock','location','pcondition','pcategory'));
    }
    public function quarantineStock(Request $request)
    {
        $condition =[];
         if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
        if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $title ="Quarantine - Stock";
        $location = 'Quarantine';
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        $stock=fgs_qurantine_stock_management::select('fgs_qurantine_stock_management.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','product_type.product_type_name',
        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile')
                            ->leftJoin('product_product','product_product.id','=','fgs_qurantine_stock_management.product_id')
                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_qurantine_stock_management.batchcard_id' )
                            ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                            ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                            ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                            ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                            ->where('fgs_qurantine_stock_management.quantity','!=',0)
                            ->where($condition)
                            ->distinct('fgs_qurantine_stock_management.id')
                            ->orderBy('fgs_qurantine_stock_management.id','DESC')
                            ->paginate(15);
        foreach($stock as $stck)
        {
            $stck['location_name'] = 'Quarantine';
        }
        $pcondition = $this->product_product->get()->unique('is_sterile');
        return view('pages/FGS/stock-management/quarantine_stock', compact('title','stock','location','pcondition','pcategory'));
    }
    
    public function AlllocationExport(Request $request)
    {
            $location ='all';
            $condition =[];
            if($request->sku_code)
            {
                $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
            }
            if($request->batch_no)
            {
                $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
            }
            if($request->category_name)
            {
                $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
            }
            if($request->is_sterile == 1)
            {
                $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
            }
            if($request->is_sterile == 0)
            {
                $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
            }
            //$title ="All Location - Stock";
            $location = 'all';
            //$stock  = $this->fgs_product_stock_management->get_stock_export($condition);
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
                        ->where($condition)
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get(); 
            return Excel::download(new StockLocationExport($stock), 'all-location-stock' . date('d-m-Y') . '.xlsx');
    
    }
    public function location1Export(Request $request)
    {
            $location ='location1';
            $condition =[];
            if($request->sku_code)
           {
               $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
           }
           if($request->batch_no)
           {
               $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
           }
           if($request->category_name)
           {
               $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
           }
            if($request->is_sterile == 1)
           {
               $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
           }
           if($request->is_sterile == 0)
           {
               $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
           }
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','Location-1(Std.)')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->where($condition)
                        //->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            
            return Excel::download(new StockLocationExport($stock), 'location1-stock' . date('d-m-Y') . '.xlsx');
    
    }
    public function location2Export(Request $request)
    {
        $condition =[];
        if($request->sku_code)
       {
           $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
       }
       if($request->batch_no)
       {
           $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
       }
       if($request->category_name)
       {
           $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
       }
        if($request->is_sterile == 1)
       {
           $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
       }
       if($request->is_sterile == 0)
       {
           $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
       }
        
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
                        ->where('product_stock_location.location_name','=','Location-2(Non-Std.)')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        //->distinct('fgs_product_stock_management.id')
                        ->where($condition)
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            return Excel::download(new StockLocationExport($stock), 'location2-stock' . date('d-m-Y') . '.xlsx');
    }
    public function location3Export(Request $request)
    {
        $condition =[];
        if($request->sku_code)
       {
           $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
       }
       if($request->batch_no)
       {
           $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
       }
       if($request->category_name)
       {
           $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
       }
        if($request->is_sterile == 1)
       {
           $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
       }
       if($request->is_sterile == 0)
       {
           $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
       }
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
                        ->where('product_stock_location.location_name','=','Location-3(CSL)')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        //->distinct('fgs_product_stock_management.id')
                        ->where($condition)
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            return Excel::download(new StockLocationExport($stock), 'location3-stock' . date('d-m-Y') . '.xlsx');
    }
    public function SNNExport(Request $request)
    {

        $condition =[];
        if($request->sku_code)
       {
           $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
       }
       if($request->batch_no)
       {
           $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
       }
       if($request->category_name)
       {
           $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
       }
        if($request->is_sterile == 1)
       {
           $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
       }
       if($request->is_sterile == 0)
       {
           $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
       }
        
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
                        //->distinct('fgs_product_stock_management.id')
                        ->where($condition)
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
        return Excel::download(new StockLocationExport($stock), 'SNNMKtd-stock' . date('d-m-Y') . '.xlsx');
    }
    public function SNNTradeExport(Request $request)
    {
        $condition =[];
         if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->category_name)
        {
            $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
        }
         if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        $location = 'SNN_Trade';
        $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
                        'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                                    ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                                    ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                                    ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                                    ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                                    ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                                    ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                                    ->where('product_stock_location.location_name','=','SNN Trade')
                                    ->where($condition)
                                    ->where('fgs_product_stock_management.quantity','!=',0)
                                    //->distinct('fgs_product_stock_management.id')
                                    ->orderBy('fgs_product_stock_management.id','DESC')
                                    ->get();
        return Excel::download(new StockLocationExport($stock), 'SNNTrade-stock' . date('d-m-Y') . '.xlsx');
    }
    public function AHPLExport(Request $request)
    {
        $condition =[];
        if($request->sku_code)
       {
           $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
       }
       if($request->batch_no)
       {
           $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
       }
       if($request->category_name)
       {
           $condition[] = ['fgs_product_category.category_name','like', '%' . $request->category_name . '%'];
       }
        if($request->is_sterile == 1)
       {
           $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
       }
       if($request->is_sterile == 0)
       {
           $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
       }
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
                        //->distinct('fgs_product_stock_management.id')
                        ->where($condition)
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            return Excel::download(new StockLocationExport($stock), 'AHPL-stock' . date('d-m-Y') . '.xlsx');
    }
    public function MAAExport(Request $request)
    {
        
            $location ='MAA';
            $stock = fgs_maa_stock_management::select('fgs_maa_stock_management.*','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','batchcard_batchcard.batch_no','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile')
                            ->leftJoin('product_product','product_product.id','=','fgs_maa_stock_management.product_id')
                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_maa_stock_management.batchcard_id' )
                            ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                            ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                            ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                            ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                            //->leftJoin('product_stock_location','product_stock_location.id','=',4 )
                            ->where('fgs_maa_stock_management.quantity','!=',0)
                            //->distinct('fgs_maa_stock_management.id')
                            ->orderBy('fgs_maa_stock_management.id','DESC')
                            ->get();
            foreach($stock as $stck)
            {
                $stck['location_name'] = 'Material Allocation Area(MAA)';
            }
            return Excel::download(new StockLocationExport($stock), 'MAA-stock' . date('d-m-Y') . '.xlsx');
    }
    public function QurantineExport(Request $request)
    {
        
            $location ='Quarantine';
            $stock=fgs_qurantine_stock_management::select('fgs_qurantine_stock_management.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile')
                                ->leftJoin('product_product','product_product.id','=','fgs_qurantine_stock_management.product_id')
                                ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_qurantine_stock_management.batchcard_id' )
                                ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                                ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                                ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                                ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                                //->leftJoin('product_stock_location','product_stock_location.id','=',5 )
                                ->where('fgs_qurantine_stock_management.quantity','!=',0)
                                ->where($condition)
                                //->distinct('fgs_qurantine_stock_management.id')
                                ->orderBy('fgs_qurantine_stock_management.id','DESC')
                                ->get();
            foreach($stock as $stck)
            {
                $stck['location_name'] = 'Quarantine';
            }
            return Excel::download(new StockLocationExport($stock), 'Qurantine-stock' . date('d-m-Y') . '.xlsx');
    }
    public function productionStockList(Request $request)
    {
         $condition =[];
        if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
       if($request->is_sterile == 1)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->is_sterile == 0)
        {
            $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        }
        if($request->group_name)
        {
            $condition[] = ['product_group1.group_name','like', '%' . $request->group_name . '%'];
        }
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
                    ->where($condition)
                    ->orderBy('production_stock_management.id','DESC')
                    ->paginate(11);
        $pcondition = $this->product_product->get()->unique('is_sterile');
        
        return view('pages/FGS/stock-management/production-stock-list',compact('stock','pcondition'));
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
    public function batchTraceReport(Request $request)
    {
        $condition =[];
        if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        $mrn_item = fgs_mrn_item::select('fgs_mrn_item.*','fgs_mrn_item.product_id as mrnprd','fgs_mrn.mrn_number','fgs_mrn.mrn_date','fgs_mrn.stock_location as mrn_stklocation','batchcard_batchcard.batch_no','product_product.sku_code','product_product.discription',
        'product_product.hsn_code','fgs_grs.grs_number','fgs_grs.grs_date','fgs_grs_item.remaining_qty_after_cancel as grsremaining','fgs_grs_item.batch_quantity as grs_qty','fgs_pi.pi_number','fgs_pi.pi_date','fgs_pi_item.remaining_qty_after_cancel as piremaining','fgs_pi_item.batch_qty as pi_qty',
        'fgs_dni.dni_number','fgs_dni.dni_date','fgs_min.min_number','fgs_min.min_date','fgs_min_item.quantity as minqty','fgs_min.stock_location as min_stkloc','fgs_min_item.product_id as minpr','fgs_min_item.batchcard_id as minbat'
        ,'fgs_mtq_item.quantity as mtqqty','fgs_mtq.mtq_date','fgs_mtq.mtq_number','fgs_cmin.cmin_number','fgs_cmin.stock_location as cminstk','fgs_cmin_item.quantity as cminqty','fgs_cmin_item.batchcard_id as cminbtch','fgs_cmin_item.product_id as cminprd',
        'fgs_cmin_item.product_id as cminprd','fgs_cmin.cmin_number','fgs_cpi_item.quantity as cpiqty','fgs_cpi.cpi_number','fgs_cpi_item.batchcard_id as cpibatchid','fgs_cpi_item.product_id')
                            
        ->leftJoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                            ->leftJoin('fgs_mrn','fgs_mrn.id','=', 'fgs_mrn_item_rel.master')
                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                            ->leftJoin('product_product','product_product.id','=','fgs_mrn_item.product_id')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.mrn_item_id','=','fgs_mrn_item.id')
                            ->leftJoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=','fgs_grs_item.id')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.mrn_item_id','=','fgs_mrn_item.id')
                            ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                            ->leftJoin('fgs_dni_item','fgs_dni_item.pi_item_id','=','fgs_pi_item.id')
                            ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','=','fgs_dni_item.id')
                            ->leftJoin('fgs_dni','fgs_dni.id','=','fgs_dni_item_rel.master')

                            ->leftjoin('fgs_min_item','fgs_min_item.batchcard_id','batchcard_batchcard.id')
                            ->leftJoin('fgs_min_item_rel','fgs_min_item_rel.item','=','fgs_min_item.id')
                            ->leftJoin('fgs_min','fgs_min.id','=','fgs_min_item_rel.master')

                            ->leftjoin('fgs_mtq_item','fgs_mtq_item.batchcard_id','batchcard_batchcard.id')
                            ->leftJoin('fgs_mtq_item_rel','fgs_mtq_item_rel.item','=','fgs_mtq_item.id')
                            ->leftJoin('fgs_mtq','fgs_mtq.id','=','fgs_mtq_item_rel.master')

                            ->leftjoin('fgs_cmin_item','fgs_cmin_item.batchcard_id','batchcard_batchcard.id')
                            ->leftJoin('fgs_cmin_item_rel','fgs_cmin_item_rel.item','=','fgs_cmin_item.id')
                            ->leftJoin('fgs_cmin','fgs_cmin.id','=','fgs_cmin_item_rel.master')

                            ->leftjoin('fgs_cpi_item','fgs_cpi_item.batchcard_id','batchcard_batchcard.id')
                            ->leftJoin('fgs_cpi_item_rel','fgs_cpi_item_rel.item','=','fgs_cpi_item.id')
                            ->leftJoin('fgs_cpi','fgs_cpi.id','=','fgs_cpi_item_rel.master')

                            ->where('fgs_mrn.status','=',1)
                            ->where($condition)
                            ->distinct('fgs_mrn_item.id')
                            ->paginate(15);
                             //dd($mrn_item);
        
        return view('pages/FGS/stock-management/batch-trace',compact('mrn_item'));
    }

    public function batchTraceReportExport(Request $request)
    {
        $condition =[];
        if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        $mrn_item = fgs_mrn_item::select('fgs_mrn_item.*','fgs_mrn_item.product_id as mrnprd','fgs_mrn.mrn_number','fgs_mrn.mrn_date','fgs_mrn.stock_location as mrn_stklocation','batchcard_batchcard.batch_no','product_product.sku_code','product_product.discription',
        'product_product.hsn_code','fgs_grs.grs_number','fgs_grs.grs_date','fgs_grs_item.remaining_qty_after_cancel as grsremaining','fgs_grs_item.batch_quantity as grs_qty','fgs_pi.pi_number','fgs_pi.pi_date','fgs_pi_item.remaining_qty_after_cancel as piremaining','fgs_pi_item.batch_qty as pi_qty',
        'fgs_dni.dni_number','fgs_dni.dni_date','fgs_min.min_number','fgs_min.min_date','fgs_min_item.quantity as minqty','fgs_min.stock_location as min_stkloc','fgs_min_item.product_id as minpr','fgs_min_item.batchcard_id as minbat'
        ,'fgs_mtq_item.quantity as mtqqty','fgs_mtq.mtq_date','fgs_mtq.mtq_number','fgs_cmin.cmin_number','fgs_cmin.stock_location as cminstk','fgs_cmin_item.quantity as cminqty','fgs_cmin_item.batchcard_id as cminbtch','fgs_cmin_item.product_id as cminprd',
        'fgs_cmin_item.product_id as cminprd','fgs_cmin.cmin_number','fgs_cpi_item.quantity as cpiqty','fgs_cpi.cpi_number','fgs_cpi_item.batchcard_id as cpibatchid','fgs_cpi_item.product_id')
                            
        ->leftJoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                            ->leftJoin('fgs_mrn','fgs_mrn.id','=', 'fgs_mrn_item_rel.master')
                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                            ->leftJoin('product_product','product_product.id','=','fgs_mrn_item.product_id')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.mrn_item_id','=','fgs_mrn_item.id')
                            ->leftJoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=','fgs_grs_item.id')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.mrn_item_id','=','fgs_mrn_item.id')
                            ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                            ->leftJoin('fgs_dni_item','fgs_dni_item.pi_item_id','=','fgs_pi_item.id')
                            ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','=','fgs_dni_item.id')
                            ->leftJoin('fgs_dni','fgs_dni.id','=','fgs_dni_item_rel.master')

                            ->leftjoin('fgs_min_item','fgs_min_item.batchcard_id','batchcard_batchcard.id')
                            ->leftJoin('fgs_min_item_rel','fgs_min_item_rel.item','=','fgs_min_item.id')
                            ->leftJoin('fgs_min','fgs_min.id','=','fgs_min_item_rel.master')

                            ->leftjoin('fgs_mtq_item','fgs_mtq_item.batchcard_id','batchcard_batchcard.id')
                            ->leftJoin('fgs_mtq_item_rel','fgs_mtq_item_rel.item','=','fgs_mtq_item.id')
                            ->leftJoin('fgs_mtq','fgs_mtq.id','=','fgs_mtq_item_rel.master')

                            ->leftjoin('fgs_cmin_item','fgs_cmin_item.batchcard_id','batchcard_batchcard.id')
                            ->leftJoin('fgs_cmin_item_rel','fgs_cmin_item_rel.item','=','fgs_cmin_item.id')
                            ->leftJoin('fgs_cmin','fgs_cmin.id','=','fgs_cmin_item_rel.master')

                            ->leftjoin('fgs_cpi_item','fgs_cpi_item.batchcard_id','batchcard_batchcard.id')
                            ->leftJoin('fgs_cpi_item_rel','fgs_cpi_item_rel.item','=','fgs_cpi_item.id')
                            ->leftJoin('fgs_cpi','fgs_cpi.id','=','fgs_cpi_item_rel.master')

                            ->where('fgs_mrn.status','=',1)
                            ->where('fgs_grs.status','=',1)
                            ->where('fgs_pi.status','=',1)
                            ->where('fgs_dni.status','=',1)
                            ->where($condition)
                            ->distinct('fgs_mrn_item.id')
                            //->paginate(15);
                            ->get();
             return Excel::download(new BatchTraceExport($mrn_item), 'fgs-batchtrace-report' . date('d-m-Y') . '.xlsx');
    }
    public function product_qty($pr_id,$batch_id,$loc_id)
    {
        $stk_qty=fgs_product_stock_management::where('product_id',$pr_id)
        ->where('batchcard_id',$batch_id)
        ->where('stock_location_id',$loc_id)
        ->pluck('quantity')[0];
        return $stk_qty;
    
    }
    public function product_btch($pr_id,$batch_id,$loc_id)
    {
        $stk_qty=DB::table('batchcard_batchcard')
        ->where('id',$batch_id)
       // ->where('stock_location_id',$loc_id)
        ->pluck('quantity');
        return $stk_qty;
    
    }
    // public function product_grs($id)
    // {
        
    //     $grs_item = fgs_mrn_item::select('fgs_grs.*','fgs_grs_item.remaining_qty_after_cancel as grs_qty',)
    //                         ->leftJoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
    //                         ->leftJoin('fgs_mrn','fgs_mrn.id','=', 'fgs_mrn_item_rel.master')
    //                         // ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
    //                         // ->leftJoin('product_product','product_product.id','=','fgs_mrn_item.product_id')
    //                         ->leftJoin('fgs_grs_item','fgs_grs_item.mrn_item_id','=','fgs_mrn_item.id')
    //                         ->leftJoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=','fgs_grs_item.id')
    //                         ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
    //                         // ->leftJoin('fgs_pi_item','fgs_pi_item.mrn_item_id','=','fgs_mrn_item.id')
    //                         // ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
    //                         // ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
    //                         // ->leftJoin('fgs_dni_item','fgs_dni_item.pi_item_id','=','fgs_pi_item.id')
    //                         // ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','=','fgs_dni_item.id')
    //                         // ->leftJoin('fgs_dni','fgs_dni.id','=','fgs_dni_item_rel.master')
    //                         ->where('fgs_mrn.status','=',1)
    //                         ->where('fgs_mrn_item.product_id',$id)
    //                         //->distinct('fgs_mrn_item.id')
    //                         ->first();
    //     return $grs_item;
    // }
    // public function product_pi($id)
    // {
        
    //     $pi_item = fgs_mrn_item::select('fgs_mrn_item.*','fgs_mrn.mrn_number','batchcard_batchcard.batch_no','product_product.sku_code','product_product.discription',
    //     'product_product.hsn_code','fgs_grs.grs_number','fgs_grs_item.remaining_qty_after_cancel as grs_qty','fgs_pi.pi_number','fgs_pi_item.remaining_qty_after_cancel as pi_qty')
    //                         ->leftJoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
    //                         ->leftJoin('fgs_mrn','fgs_mrn.id','=', 'fgs_mrn_item_rel.master')
    //                         ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
    //                         ->leftJoin('product_product','product_product.id','=','fgs_mrn_item.product_id')
    //                         ->leftJoin('fgs_grs_item','fgs_grs_item.mrn_item_id','=','fgs_mrn_item.id')
    //                         ->leftJoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=','fgs_grs_item.id')
    //                         ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
    //                         ->leftJoin('fgs_pi_item','fgs_pi_item.mrn_item_id','=','fgs_mrn_item.id')
    //                         ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
    //                         ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
    //                         // ->leftJoin('fgs_dni_item','fgs_dni_item.pi_item_id','=','fgs_pi_item.id')
    //                         // ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','=','fgs_dni_item.id')
    //                         // ->leftJoin('fgs_dni','fgs_dni.id','=','fgs_dni_item_rel.master')
    //                         ->where('fgs_mrn.status','=',1)
    //                         ->where('fgs_mrn_item.product_id',$id)
    //                         //->distinct('fgs_mrn_item.id')
    //                         ->first();
    //     return $pi_item;
    // }
}
