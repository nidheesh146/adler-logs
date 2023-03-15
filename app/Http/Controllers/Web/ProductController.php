<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\product;
use App\Models\product_input_material;
use Validator;
use Redirect;
use DB;
class ProductController extends Controller
{
    public function __construct()
    {
       
        $this->product = new product;
        $this->product_input_material = new product_input_material;
    }

    public function productList()
    {
        $data['products'] = $this->product->get_products([]);
        return view('pages/product/product-list',compact('data'));
    }
    public function addInputMaterial(Request $request,$product_id=null)
    {
        if ($request->isMethod('post')) 
        {
            $validation['product_id'] = ['required'];
            $validation['moreItems.*.Itemcode'] = ['required'];
            $validation['moreItems.*.quantity'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                foreach ($request->moreItems as $key => $value) 
                {
                    $Request = [
                                    "product_id"=>$request['product_id'],
                                    "item_id" => $value['Itemcode'],
                                    "quantity"=> $value['quantity'],
                                    "created_at" => date('Y-m-d H:i:s'),

                                ];
                    $add[]=$this->product_input_material->insert_data($Request);
                }
                if(count($add)==count($request->moreItems))
                $request->session()->flash('success', "Input material added successfully..");
                else
                $request->session()->flash('error', "Input material adding failed..");
                return redirect("product/add-input-material?product_id=".$request->product_id);
            }
            if($validator->errors()->all())
            {
                return redirect("product/add-input-material?product_id=".$request->product_id)->withErrors($validator)->withInput();
            }
        }
        else
        {
            $product = product::find($request->product_id);
            $materials = $this->product_input_material->getAllData(['product_input_material.product_id'=>$request->product_id]);
            //print_r($materials);exit;
            return view('pages/product/add-input-material', compact('product','materials'));
        }
    }

    public function deleteInputMaterial(Request $request)
    {
        $delete = product_input_material::where('id','=',$request->id)->delete();
        if($delete)
        $request->session()->flash('succ', "Input material deleted successfully..");
        else
        $request->session()->flash('err', "Input material deleting failed..");
        return Redirect::back();
    }
    public function getProductUpload()
    {
        return view('pages/product/product-upload');
    }

    public function productFileUpload(Request $request)
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
            $no_column = 59;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
            if($sheet1_column_count == $no_column)
            {
                $res = $this->Excelsplitsheet($ExcelOBJ);
                 //print_r($res);exit;
                 if($res)
                 {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('product/file/upload');
                 }
                 else{
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect('product/file/upload');
                 }
            }
            else 
            {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect('product/file/upload');
            }
            
            //dd($ExcelOBJ->worksheetData);
            //exit;
        }
    }
    public function Excelsplitsheet($ExcelOBJ)
    {
        ini_set('max_execution_time', 500);
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;
        //print_r($ExcelOBJ->worksheetData); exit;
        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) 
        {
            $ExcelOBJ->sectionName = '';
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            //echo $ExcelOBJ->sheetName;exit;
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
        
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            //print_r(json_encode($ExcelOBJ->excelworksheet));exit;
            // $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            // $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
           // echo $ExcelOBJ->sheetname;exit;
            $res = $this->insert_product($ExcelOBJ);
            //return $res;
            //print_r($ExcelOBJ);exit;
        }
       // print_r($ExcelOBJ);exit;
       
    }
    public function insert_product($ExcelOBJ)
    {
        //print_r(json_encode($ExcelOBJ->excelworksheet));exit;
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 0) 
            {   
                    $data['minimum_stock'] = $excelsheet[44];
                    $data['maximum_stock'] = "0";
                    if ($excelsheet[47] && preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace('/\s+/', '', $excelsheet[47])) != 'NA' && preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace('/\s+/', '', $excelsheet[47])) != 'N. A.') {
                        $data['min_stock_set_date']  = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[47]))->format('Y-m-d');
                    } else {
                            $data['min_stock_set_date'] = null;
                    }
                    $data['over_stock_level'] = $excelsheet[46];
                    $data['discription'] = $excelsheet[2];
                    $data['is_active'] = 1;
                    if ($excelsheet[6] && $excelsheet[6] != 'NA' && $excelsheet[6] != 'N. A.') {
                        $data['published_date'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d');
                    } else {
                        $data['published_date'] = null;
                    }
                    $data['record_file_no'] = $excelsheet[22];
                    $data['technical_file'] = $excelsheet[23];
                    $data['instruction_for_use'] = $excelsheet[24];
                    $data['classification_mdd'] = $excelsheet[25];
                    $data['item_type'] = $excelsheet[26];
                    $data['family_name'] = $excelsheet[28];

                    $data['sku_name'] =  $excelsheet[29];
                    $data['snn_description']= $excelsheet[29];

                    $data['label_format_number'] = $excelsheet[30];
                    $data['is_sterile'] = ($excelsheet[31] == 'Yes') ? 1 : 0;
                    $data['is_non_sterile_logo'] =  ($excelsheet[32] == 'Yes') ? 1 : (($excelsheet[32] == 'No') ? 0 : NULL) ;
                    $data['sterilization_type'] = $excelsheet[33];
                    $data['is_sterile_expiry_date'] = ($excelsheet[34] == 'Yes') ? 1 : (($excelsheet[34] == 'No') ? 0 : NULL) ;
                    // $label_image = preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace('/\s+/', '', $excelsheet[35]));
                    // if ($excelsheet[35] && $label_image != 'NA' && $label_image != 'N. A.') {
                    //     $data['label_image'] = 'label_image/'.$excelsheet[35];
                    // } else {
                    //     $data['label_image'] = null;
                    // }
                    $data['is_ce_marked'] = ($excelsheet[36] == 'Yes') ? 1 : (($excelsheet[36] == 'No') ? 0 : NULL) ;
                    $data['notified_body_number'] = $excelsheet[37];
                    $data['is_ear_log_address'] = ($excelsheet[38] == 'Yes') ? 1 : (($excelsheet[38] == 'No') ? 0 : NULL) ;
                    
                    $data['is_read_instruction_logo'] = ($excelsheet[39] == 'Yes') ? 1 : (($excelsheet[39] == 'No') ? 0 : NULL) ;
                    
                    $data['is_instruction'] = ($excelsheet[39] == 'Yes') ? 1 : 0;
                    
                    $data['is_temperature_logo'] = ($excelsheet[40] == 'Yes') ? 1 : (($excelsheet[40] == 'No') ? 0 : NULL) ;
                    
                    $data['is_donot_reuse_logo'] = ($excelsheet[41] == 'Yes') ? 1 : (($excelsheet[41] == 'No') ? 0 : NULL) ;
                    
                    $data['is_reusable'] = ($excelsheet[41] == 'Yes') ? 1 : 0;
                    
                    $data['drug_license_number'] = $excelsheet[42];
                    $data['hsn_code'] = $excelsheet[43];
                    if ($excelsheet[44] && preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace('/\s+/', '', $excelsheet[44])) != 'NA' && preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace('/\s+/', '', $excelsheet[44])) != 'N. A.' ) {
                        $data['quantity_per_pack'] = $excelsheet[44];
                    } else {
                        $data['quantity_per_pack'] = NULL;
                    }
                    $data['hierarchy_path'] = $excelsheet[45];
                    if ($excelsheet[46] && preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace('/\s+/', '', $excelsheet[46])) != 'NA' && preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace('/\s+/', '', $excelsheet[46])) != 'N. A.') {
                        $data['unit_weight_kg'] = $excelsheet[46];
                    }else{
                        $data['unit_weight_kg'] = NULL;
                    }
                    $data['revision_record'] = $excelsheet[47];
                    $data['is_inactive'] = ($excelsheet[48] == 'Y') ? 1 : (($excelsheet[48] == 'N') ? 0 : NULL);
                    $data['gs1_code'] = $excelsheet[50];
                    $data['is_control_sample_applicable'] = ($excelsheet[51] == 'Yes') ? 1 : (($excelsheet[51] == 'No') ? 0 : NULL);
                    $data['is_doc_applicability'] =($excelsheet[52] == 'Yes') ? 1 : (($excelsheet[52] == 'No') ? 0 : NULL);
                    $data['is_instruction_for_reuse_symbol'] = $excelsheet[53] ? $excelsheet[53]  : NULL;
                    $data['is_donot_reuse_symbol'] = $excelsheet[54] ? $excelsheet[54] : NULL ; 
                    $data['ce_logo'] = $excelsheet[56]  ? $excelsheet[56]  : NULL; 
                    $data['ad_sp1'] =  $excelsheet[57];
                    $data['ad_sp2'] = $excelsheet[58];
                    $data['groups'] = $excelsheet[49] ;
                    $data['family'] =  $excelsheet[28];
                    $data['brand'] = $excelsheet[27];
                    $data['sku_code'] = $excelsheet[1];
                    //print_r(json_encode($data));exit;
                    $product_product = DB::table('product_product')->where('sku_code',$excelsheet[1])->first();
                    if(!$product_product)
                    {
                        $res[] = DB::table('product_product')->insert($data); 
                    }
                    else
                    {
                        //echo "yes";exit;
                        $res[] = DB::table('product_product')->where('sku_code', $excelsheet[1])->update($data);
                    }
            }
            
        }
        if($res)
        return 1;
        else 
        return 0;
       
    }
}
