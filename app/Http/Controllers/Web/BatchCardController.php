<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use DB;
use Validator;

use App\Models\batchcard;
use App\Models\product;

class BatchCardController extends Controller
{
    public function __construct()
    {
        $this->batchcard = new batchcard;
        $this->product = new product;
    }
    public function productsearch(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Product is not valid'], 500); 
        }
        $data =  $this->product->get_product_data(strtoupper($request->q));
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Product is not exist'], 500); 
        }
    }
    public function BatchcardAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['product'] = ['required'];
            $validation['batchcard'] = ['required'];
            $validation['quantity'] = ['required'];
            $validation['start_date'] = ['required'];
            $validation['target_date'] = ['required'];
            $validation['description'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
                $datas['product_id'] = $request->product;
                $datas['process_sheet_id'] = 1;
                $datas['batch_no'] = $request->batchcard;
                $datas['quantity'] = $request->quantity;
                $datas['start_date'] = date('Y-m-d',strtotime($request->start_date));
                $datas['target_date'] = date('Y-m-d',strtotime($request->target_date));
                $datas['description'] = $request->description;
                $datas['is_active'] = 1;
                $datas['created'] = date('Y-m-d H:i:s');
                $datas['updated'] = date('Y-m-d H:i:s');
                $batchcard =  $this->batchcard->insertdata($datas);
                $request->session()->flash('success',  "You have successfully inserted a batchcard !");
                return redirect('batchcard/batchcard-add');
            }
            if ($validator->errors()->all()) {
                return redirect("batchcard/batchcard-add")->withErrors($validator)->withInput();
            }
        }
        return view('pages/batchcard/batchcard-add');
    }
    public function getBatchcardUpload()
    {
        return view('pages/batchcard/batchcard-upload');
    }

    public function batchcardUpload(Request $request) 
    {
        $file = $request->file('file');
        if ($file) {

            $ExcelOBJ = new \stdClass();

            // CONF
            $path = storage_path().'/app/'.$request->file('file')->store('temp');

            $ExcelOBJ->inputFileName = $path;
            $ExcelOBJ->inputFileType = 'Xlsx';

            // $ExcelOBJ->filename = 'Book1.xlsx';
            // $ExcelOBJ->inputFileName = 'C:\xampp7.4\htdocs\mel\sampleData\Book1.xlsx';
            $ExcelOBJ->spreadsheet = new Spreadsheet();
            $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
            $ExcelOBJ->reader->setReadDataOnly(true);
            $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
            $no_column = 15;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            if($sheet1_column_count == $no_column)
            {
                 $res = $this->Excelsplitsheet($ExcelOBJ);
                 //print_r($res);exit;
                 if($res)
                 {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('batchcard/batchcard-upload');
                 }
                 else{
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect('batchcard/batchcard-upload');
                 }
            }
            else 
            {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect('batchcard/batchcard-upload');
            }
            
            //dd($ExcelOBJ->worksheetData);
            //exit;
        }
    }

    public function Excelsplitsheet($ExcelOBJ)
    {
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;

        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) 
        {
            $ExcelOBJ->sectionName = '';
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
           // print_r(json_encode($ExcelOBJ->worksheet));exit;
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
            $res = $this->insert_batchcard_batchcard($ExcelOBJ);
            return $res;
        }
         //print_r($res);exit;

       
    }

    function insert_batchcard_batchcard($ExcelOBJ)
    {
        $data = [];
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 1 &&  $excelsheet[0]) 
            {
                $product = DB::table('product_product')->select(['is_sterile','id'])->where('sku_code', $excelsheet[1])->first();
                $batchcard =  DB::table('batchcard_batchcard')->select(['*'])->where('batch_no', $excelsheet[0])->first();
                if(!($batchcard) && $product)
                {
                    $data = [
                        'batch_no' =>$excelsheet[0],
                        'quantity'=>$excelsheet[10],
                        'description'=>$excelsheet[2],
                        'product_id'=>$product->id,
                        'is_active'=>1,
                        'created'=>date('Y-m-d H:i:s'),
                        'updated'=>date('Y-m-d H:i:s'),
                        'start_date' => ($excelsheet[3]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL,
                        'target_date' => ($excelsheet[9]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[9]))->format('Y-m-d')) : NULL,

                    ];
                    $res = DB::table('batchcard_batchcard')->insert($data);
                }
                    
            }
            // if( count($data) > 0){
            // $res = DB::table('batchcard_batchcard')->insert($data);  
            // }   
        }
        return $data;
    
            
    }
    

}
