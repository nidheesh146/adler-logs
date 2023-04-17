<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\batchcard;
use App\Models\PurchaseDetails\product_price_master;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Validator;
use DB;
class PriceController extends Controller
{
    public function __construct()
    {
         $this->product = new product;
         $this->product_price_master = new product_price_master;
    }

    public function priceList(Request $request)
    {
        //$this->priceMasterUpload();
       // $this->productFgsUpload();
       //$this->fgsStockUpload();
        $prices = $this->product_price_master->get_all([]);
        return view('pages/FGS/price-master/price-master-list',compact('prices'));
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
    public function productFgsUpload()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName ='C:\xampp\htdocs\itemMaster.xlsx';
        $ExcelOBJ->spreadsheet = new Spreadsheet();
        $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
        $ExcelOBJ->reader->setReadDataOnly(true);
        $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
        $this->productFgsExcelsplitsheet($ExcelOBJ);
        exit;
    }

    public function productFgsExcelsplitsheet($ExcelOBJ)
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
            $this->insert_fgs_product_master($ExcelOBJ);     
            //die("done");
        }
        exit('done');
    }
    function insert_fgs_product_master($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
    
            if ($key > 1 &&  $excelsheet[1]) 
             {
                //echo $excelsheet[17];exit;
                $not_exist =[];
                $product_id = product::where('sku_code','=',$excelsheet[0])->pluck('id')->first();
                if($product_id)
                {
                    $data['hsn_code'] = $excelsheet[4];
                    $data['product_type_id'] = $this->identify_id($excelsheet[3],"PRODUCT TYPE");
                    $data['product_oem_id'] = $this->identify_id($excelsheet[8],"PRODUCT OEM");
                    $data['product_group1_id'] = $this->identify_id($excelsheet[7],"PRODUCT GROUP1");
                    $data['product_category_id'] = $this->identify_id($excelsheet[6],"PRODUCT CATEGORY");
                    $data['updated'] = date('Y-m-d H:i:s');
                    $res[]=DB::table('product_product')->where('id','=',$product_id)->update($data);
                }
                else
                {
                    $not_exist[] = $excelsheet[0];
                    $res[] = 1;
                    // $data['sku_code'] = $excelsheet[0];
                    // $data['discription'] = $excelsheet[1];
                    // $data['hsn_code'] = $excelsheet[3];
                    // $data['product_type_id'] = $this->identify_id($excelsheet[6],"PRODUCT TYPE");
                    // $data['product_oem_id'] = $this->identify_id($excelsheet[6],"PRODUCT OEM");
                    // $data['product_group1_id'] = $this->identify_id($excelsheet[6],"PRODUCT GROUP1");
                    // $data['product_category_id'] = $this->identify_id($excelsheet[6],"PRODUCT CATEGORY");
                    // $data['created_at'] = date('Y-m-d H:i:s');
                    // $data['updated_at'] = date('Y-m-d H:i:s');
                    // $res[]=DB::table('product_product')->insert($data);
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
    function identify_id($data,$type)
    {
        if($type=='PRODUCT TYPE'){
           return DB::table('product_type')->where('product_type_name',$data)->first()->id;
        }
        if($type=='PRODUCT OEM'){
           return DB::table('product_oem')->where('oem_name',$data)->first()->id;
        }
        if($type=='PRODUCT GROUP1'){
         return   DB::table('product_group1')->where('group_name',$data)->first()->id;
        }
    
        if($type=='PRODUCT CATEGORY')
        {
            return   DB::table('fgs_product_category')->where('category_name',$data)->first()->id;
        }
       
    }

    public function priceMasterUpload()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName ='C:\xampp\htdocs\priceMaster.xlsx';
        $ExcelOBJ->aircraft = 'B737-MAX';
        $ExcelOBJ->spreadsheet = new Spreadsheet();
        $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
        $ExcelOBJ->reader->setReadDataOnly(true);
        $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
        $this->priceMasterExcelsplitsheet($ExcelOBJ);
        exit;
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
        exit('done');
    }
    function insert_price_master($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
    
            if ($key > 1 &&  $excelsheet[1]) 
             {
                //echo $excelsheet[17];exit;
                $product_id = product::where('sku_code','=',$excelsheet[0])->pluck('id')->first();
                $price_master=product_price_master::where('product_id','=',$product_id)->first();
                if($price_master)
                {
                    $data['product_id'] = $product_id;
                    $data['mrp'] = $excelsheet[17];
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $res[]=DB::table('product_price_master')->where('id','=',$price_master['id'])->update($data);
                }
                else
                {
                    $data['product_id'] = $product_id;
                    $data['mrp'] = $excelsheet[17];
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
        $ExcelOBJ->inputFileName ='C:\xampp\htdocs\fgs_stock.xlsx';
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
}
