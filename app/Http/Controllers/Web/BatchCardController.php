<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use DB;

class BatchCardController extends Controller
{
    public function getBatchcardUpload()
    {
        return view('pages/batchcard/batchcard-upload');
    }

    public function batchcardUpload(Request $request) 
    {
        $file = $request->file('file');
        if ($file) {
            // $path = storage_path().'/app/'.$request->file('file')->store('temp');
            // $reader = new ReaderXlsx;
            // $reader->setReadDataOnly(TRUE);
            // $spreadsheet = $reader->load($path);
            // $sheet = $spreadsheet->getActiveSheet();
            // $worksheetInfo = $reader->listWorksheetInfo($path);
            // $data_array =  $sheet->toArray();
            // $this->Excelsplitsheet($data_array);
            // foreach ($worksheetInfo as $worksheet) {
            //     $sheetName = $worksheet['worksheetName'];   
            //   
            //     /**  Load $inputFileName to a Spreadsheet Object  **/
            //     $reader->setLoadSheetsOnly($sheetName);
            //     $spreadsheet = $reader->load($path);
            //     $worksheet = $spreadsheet->getActiveSheet();
            //     print_r($worksheet->toArray());
                
            // }
            // exit;
            $ExcelOBJ = new \stdClass();

            // CONF
            $path = storage_path().'/app/'.$request->file('file')->store('temp');
            $ExcelOBJ->inputFileType = 'Xlsx';
            $ExcelOBJ->filename = 'SL-1-01.xlsx';
            //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/17-07-2022/Top sheet creater_BAtch card to sheet 16 JULY.xlsx';
            $ExcelOBJ->inputFileName = $path;
            // $ExcelOBJ->filename = 'Book1.xlsx';
            // $ExcelOBJ->inputFileName = 'C:\xampp7.4\htdocs\mel\sampleData\Book1.xlsx';
            $ExcelOBJ->aircraft = 'B737-MAX';
            $ExcelOBJ->spreadsheet = new Spreadsheet();
            $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
            $ExcelOBJ->reader->setReadDataOnly(true);
            $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
            $no_column =18;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            if($sheet1_column_count == $no_column)
            {
                 $res = $this->Excelsplitsheet($ExcelOBJ);
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
                $request->session()->flash('error',  "Column not matching..");
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
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
            $res = $this->insert_batchcard_batchcard($ExcelOBJ);
            
        }
        //echo $res;exit;
        return $res;
       
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
                //print_r($batchcard);exit;
                if(!($batchcard) && $product)
                {
                    $data[] = [
                        'batch_no' =>$excelsheet[0],
                        'quantity'=>$excelsheet[10],
                        'description'=>$excelsheet[2],
                        'product_id'=>$product->id,
                        'is_active'=>1,
                        'created'=>date('Y-m-d H:i:s'),
                        'updated'=>date('Y-m-d H:i:s'),
                        'start_date' => ($excelsheet[3]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ("NULL"),
                        'target_date' => ($excelsheet[9]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[9]))->format('Y-m-d')) : ("NULL"),

                    ];
                        // $data['batch_no'] = $excelsheet[0];
                        // $data['quantity'] =  $excelsheet[10];
                        // $data['description'] = $excelsheet[2];
                        // $data['product_id']  =$product->id;
                        // $data['is_active'] = 1;
                        // $data['created'] = date('Y-m-d H:i:s');
                        // $data['updated'] = date('Y-m-d H:i:s');
                        // $data['start_date'] = ($excelsheet[3]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : (NULL);
                        // $data['target_date'] = ($excelsheet[9]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[9]))->format('Y-m-d')) : (NULL);
                        
                         //DB::table('batchcard_batchcard')->insert($data);
                       //print_r($data);die;
                }
                    
            }
            $res = DB::table('batchcard_batchcard')->insert($data);     
        }
        //dd($data);exit;
        //echo $res;exit;
        return $res;
            
    }
    

}
