<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\batchcard;
use App\Models\PurchaseDetails\product_price_master;
use App\Models\PurchaseDetails\product_productgroup;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Validator;
use DB;
use App\Models\PurchaseDetails\inventory_rawmaterial;

use App\Exports\PriceMasterExport;
use Maatwebsite\Excel\Facades\Excel; 

class PriceController extends Controller
{ 
    public function __construct()
    {
         $this->product = new product;
         $this->product_price_master = new product_price_master;
         $this->product_productgroup = new product_productgroup;
    }

    public function priceList(Request $request)
    {
        ///$this->priceMasterUpload();
    //$this->productFgsUpload();
       //$this->fgsStockUpload();
    //    $this->item_InputmaterialUpload();

        $condition =[];
        if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->hsn_code)
        {
            $condition[] = ['product_product.hsn_code','like', '%' . $request->hsn_code . '%'];
        }
        if($request->group_name)
        {
            $condition[] = ['product_productgroup.group_name','like', '%' . $request->group_name . '%'];
            $condition[]=['product_product.product_group1_id','!=','null'];
        }
        $price = DB::table('product_group1')->distinct('product_group1.group_name')->get();
        $prices = $this->product_price_master->get_all($condition);
        return view('pages/FGS/price-master/price-master-list',compact('prices','price'));
    }
    public function priceAdd(Request $request,$id=null)
    {
        if ($request->isMethod('post'))
        {
            
            $validation['product'] = ['required'];
            $validation['purchase_price'] = ['required'];
            $validation['sales_price'] = ['required'];
            $validation['transfer_price'] = ['required'];
            $validation['mrp'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 
                
                $data['purchase'] = $request->purchase_price;
                $data['sales'] = $request->sales_price;
                $data['transfer'] = $request->transfer_price;
                $data['mrp'] = $request->mrp;
                $data['status_type'] = $request->status_type;

                if($request->id){
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $data['updated_by'] = config('user')['user_id'];
                    $this->product_price_master->update_data(['id'=>$request->id],$data);
                    $request->session()->flash('success',"You have successfully updated a price master !");
                    return redirect("fgs/price-master/add/".$id);
                }
                else{
                    $data =  $this->product_price_master->get_single_product_price(['product_price_master.product_id'=>$request->product]); 
                    if($data)
                    {
                       $request->session()->flash('error',"Data already exist!"); 
                        return redirect("fgs/price-master/add");
                    }
                    else
                    {
                         $data['purchase'] = $request->purchase_price;
                         $data['sales'] = $request->sales_price;
                         $data['transfer'] = $request->transfer_price;
                         $data['mrp'] = $request->mrp;  
                         
                        $data['product_id'] = $request->product;
                        $data['created_by'] = config('user')['user_id'];
                        $data['created_at'] = date('Y-m-d H:i:s');
                        $data['updated_at'] = date('Y-m-d H:i:s');
                        $data['status_type'] = $request->status_type;
                        $this->product_price_master->insert_data($data);
                        $request->session()->flash('success',"You have successfully added a price master !");
                        return redirect("fgs/price-master/list");
                    }
                }
            }
            if($validator->errors()->all()) 
            { 
            if($request->id)
                return redirect("fgs/price-master/add/".$id)->withErrors($validator)->withInput();
            else
                return redirect("fgs/price-master/add")->withErrors($validator)->withInput();
            }
        }
        else
        {
          if($request->id)
            {
              $data =  $this->product_price_master->get_single_product_price(['product_price_master.id'=>$request->id]); 

             return view('pages/FGS/price-master/price-master-add',compact('data'));
            }
           else
           return view('pages/FGS/price-master/price-master-add'); 
        }   
    }
   
    public function productsearch(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Product is not valid'], 500); 
        }
      
            $data =  $this->product->get_product_info(strtoupper($request->q));   
        
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Product is not exist'], 500); 
        }
    }
    public function productsearch_trade(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Product is not valid'], 500); 
        }
      
           // $data =  $this->product->get_product_info(strtoupper($request->q)); 
          $data= DB::table('product_product')
           ->select(['product_product.id','product_product.sku_code as text','product_product.discription','product_productgroup.group_name','product_product.hsn_code',
           'product_product.is_sterile','product_product.process_sheet_no','inv_stock_management.stock_qty as qty'])
                           ->leftjoin('product_productgroup','product_productgroup.id','=','product_product.product_group_id')
                           ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.item_code','=','product_product.sku_code')
                           ->leftjoin('inv_stock_management','inv_stock_management.item_id','=','inventory_rawmaterial.id')
                           ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.Item_code','=','inventory_rawmaterial.id')
                           ->leftjoin('inv_mac_item','inv_mac_item.pr_item_id','=','inv_purchase_req_item.requisition_item_id')
                           ->leftjoin('fgs_transfer','fgs_transfer.pr_item_id','=','inv_mac_item.pr_item_id')
                           ->where('product_product.sku_code','like','%'.$request->q.'%')
                           ->where('inv_mac_item.fgs_transfer_status',2)
                           ->get()->toArray();  
        
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Product is not exist'], 500); 
        }
    }


    public function priceMasterUpload(Request $request)
    {
        $file = $request->file('file');
        if ($file) 
        {
            $ExcelOBJ = new \stdClass();
            $path = storage_path().'/app/'.$request->file('file')->store('temp');
            $ExcelOBJ->inputFileName = $path;
            $ExcelOBJ->inputFileType = 'Xlsx';
            $ExcelOBJ->spreadsheet = new Spreadsheet();
            $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
            $ExcelOBJ->reader->setReadDataOnly(true);
            $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
            $no_column = 6;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
            if($sheet1_column_count == $no_column)
            {
                $reslt = $this->priceMasterExcelsplitsheet($ExcelOBJ);
                    //print_r($res);exit;
                if($reslt)
                {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('fgs/price-master/upload-excel');
                }
                else{
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect('fgs/price-master/upload-excel');
                }
            }
            else 
            {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect('fgs/price-master/upload-excel');
            }
            //$this->priceMasterExcelsplitsheet($ExcelOBJ);
        }
    }
    public function priceMasterExcelsplitsheet($ExcelOBJ)
    {
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;

        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) {
            $ExcelOBJ->sectionName = '';
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
            $this->insert_price_master($ExcelOBJ);     
            //die("done");
        }
        //exit('done');
        return 1;
    }
    function insert_price_master($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
            if ($key > 0 &&  $excelsheet[0]) 
             {
                $product_id = product::where('sku_code','=',$excelsheet[0])->pluck('id')->first();
                $price_master=product_price_master::where('product_id','=',$product_id)->first();
                if($price_master)
                {
                    // new dp ->sales price
                    $data['product_id'] = $product_id;
                    $data['purchase'] =$excelsheet[2];
                    $data['sales'] =$excelsheet[3];
                    $data['mrp'] = $excelsheet[4];
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $res[]=DB::table('product_price_master')->where('id','=',$price_master['id'])->update($data);
                }
                else
                {
                    //echo 'kk';exit;
                    $data['product_id'] = $product_id;
                    $data['purchase'] =$excelsheet[2];
                    $data['sales'] =$excelsheet[3];
                    $data['mrp'] = $excelsheet[4];
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $res[]=DB::table('product_price_master')->insert($data);
                }
                
            }
        }
        if($res)
        return 1;
        else
        return 0;
    }

    public function fgsStockUpload()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName ='C:\xampp\htdocs\FGS_Stk1.xlsx';
        $ExcelOBJ->aircraft = 'B737-MAX';
        $ExcelOBJ->spreadsheet = new Spreadsheet();
        $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
        $ExcelOBJ->reader->setReadDataOnly(true);
        $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
        $this->fgsStockExcelsplitsheet($ExcelOBJ);
        exit;
    }
    public function fgsStockExcelsplitsheet($ExcelOBJ)
    {
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;

        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) {
            $ExcelOBJ->sectionName = '';
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
            $this->insert_fgs_stock($ExcelOBJ);     
            //die("done");
        }
        exit('done');
    }
    function insert_fgs_stock($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
    
            if ($key > 1 &&  $excelsheet[1]) 
             {
                //echo $excelsheet[17];exit;
                $product_id = product::where('sku_code','=',$excelsheet[0])->pluck('id')->first();
                $batchcard=batchcard::where('batch_no','=',$excelsheet[2])->first();
                if($product_id && $batchcard )
                {
                    $data['product_id'] = $product_id;
                    $data['batchcard_id'] = $batchcard['id'];
                    $data['stock_qty'] = $excelsheet[3];
                    $res[]=DB::table('production_stock_management')->insert($data);
                }
                else
                {
                    $not_exist[] = $excelsheet[0];
                    $res[] = 1;
                }
            }
        }
        if($res)
        {
        print_r($not_exist);
        return 1;
       
        }
        else
        return 0;
    }

     public function PriceMasterExport(Request $request)
    {
        if($request)
        {
            return Excel::download(new PriceMasterExport($request), 'PriceMaster' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new PriceMasterExport($request), 'PriceMaster' . date('d-m-Y') . '.xlsx');
        }
    }
    public function priceUpload()
    {
    return view('pages/FGS/price-master/pricemaster-upload');

    }
    public function item_InputmaterialUpload()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName = 'C:\xampp\htdocs\prd294.xlsx';
        $ExcelOBJ->spreadsheet = new Spreadsheet();
        $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
        $ExcelOBJ->reader->setReadDataOnly(true);
        $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
        $this->productinputExcelsplitsheet($ExcelOBJ);
        exit;
    }

    public function productinputExcelsplitsheet($ExcelOBJ)
    {
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;

        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) {
            $ExcelOBJ->sectionName = '';
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
            $this->insert_input_product_master($ExcelOBJ);     
            //die("done");
        }
        exit('done');
    }
    function insert_input_product_master($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
    
            if($key > 0 &&  $excelsheet[0])
             {
                // echo $excelsheet[0];exit;
                $not_exist =[];
                $product = inventory_rawmaterial::where('item_code', $excelsheet[0])->first();
                if($product)
                {
                    
                    $data = [
                        'is_expiry' => 1,

                    ];                   
                    $res[]=DB::table('inventory_rawmaterial')->where('id','=',$product->id)->update($data);
                }
                else
                {
                    $res[]=0;
                }
                
            }
        }
        // if($res)
        // {
            // print_r($not_exist);
        return 1;
        // }
        // else
        // return 0;   
    }
}
