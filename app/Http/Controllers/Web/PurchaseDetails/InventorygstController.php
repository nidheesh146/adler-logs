<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class InventorygstController extends Controller
{
    public function get_data()
    {
        $gst_details = DB::table('inventory_gst')
            ->orderBy('id', 'DESC')
            ->get();
        return view('pages/inventory/gst/inventory-gst', compact('gst_details'));
    }
    public function add_gst_details(Request $request)
    {
        DB::table('inventory_gst')
            ->insert(
                [

                    'igst' => $request->igst,
                    'cgst' => $request->cgst,
                    'sgst' => $request->sgst
                ]
            );
        $gst_details = DB::table('inventory_gst')
            ->orderBy('id', 'DESC')
            ->get();
        return view('pages/inventory/gst/inventory-gst', compact('gst_details'));
    }
    // public function get_itemtype()
    // {
    //     $inv_item=DB::table('inv_item_type_2')->get();
    //     return view('pages/inventory/gst/inventory-itemtype-add',compact('inv_item'));
    // }
    public function Add_itemtype(Request $request)
    {
        // $
        // $inv_item=DB::table('inv_item_type_2')->get();
        // return view('pages/inventory/gst/inventory-itemtype-add',compact('inv_item'));
        $validator = Validator::make($request->all(), [
            'role' => ['required', 'min:1', 'max:20'],

        ]);
        if (!$validator->errors()->all()) {
            $datas['type_name'] = $request->role;

            if (!$request->id) {

                DB::table('inv_item_type_2')->insert($datas);
                $request->session()->flash('success', 'Type has been successfully inserted');
                return redirect("inventory/inventory-itemtype-add");
            }
            DB::table('inv_item_type_2')->where('id', $request->id)->update($datas);

            $request->session()->flash('success', 'Type has been successfully updated');
            return redirect("inventory/inventory-itemtype-add");
        }
        $data['type'] = DB::table('inv_item_type_2')->get();
        if ($request->id) {
            //$edit = $this->Role->get_role($request->role_id);
            $edit = DB::table('inv_item_type_2')->where('id', $request->id)->first();
            //print_r($edit);exit;
            return view('pages/inventory/gst/inventory-itemtype-add', compact('data', 'edit'));
        } else
            return view('pages/inventory/gst/inventory-itemtype-add', compact('data'));
    }

    public function add_inv_item()
    {
        return view('pages/inventory/gst/inventory-item-upload');
    }
    public function invitemFileUpload(Request $request)
    {
        $file = $request->file('file');
        if ($file) {

            $ExcelOBJ = new \stdClass();

            // CONF
            $path = storage_path() . '/app/' . $request->file('file')->store('temp');

            $ExcelOBJ->inputFileName = $path;
            $ExcelOBJ->inputFileType = 'Xlsx';

            // $ExcelOBJ->filename = 'Book1.xlsx';
            // $ExcelOBJ->inputFileName = 'C:\xampp7.4\htdocs\mel\sampleData\Book1.xlsx';
            $ExcelOBJ->spreadsheet = new Spreadsheet();
            $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
            $ExcelOBJ->reader->setReadDataOnly(true);
            $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
            $no_column = 11;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
            if ($sheet1_column_count == $no_column) {
                $res = $this->Excelsplitsheet($ExcelOBJ);
                //print_r($res);exit;
                if ($res) {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('inventory/inv-item-upload');
                } else {
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect('inventory/inv-item-upload');
                }
            } else {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect('inventory/inv-item-upload');
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
        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) {
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
            return $res;
            //print_r($ExcelOBJ);exit;
        }
        // print_r($ExcelOBJ);exit;

    }
    public function insert_product($ExcelOBJ)
    {
        //print_r(json_encode($ExcelOBJ->excelworksheet));exit;
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
            if ($key > 0) {
                //echo $excelsheet[4];exit;

                if ($excelsheet[4] && $excelsheet[4] != 'NA' && $excelsheet[4] != 'N/A') {
                    if ($excelsheet[4] == 'Assembly') {
                        $data['item_id3'] = 0;
                    } else {
                        $item_id = DB::table('inventory_rawmaterial')->where('item_code', $excelsheet[4])->first();
                        $data['item_id3'] = $item_id->id;
                    }
                } else {
                    $data['item_id3'] = null;
                }
                if ($excelsheet[3] && $excelsheet[3] != 'NA' && $excelsheet[3] != 'N/A') {
                    if ($excelsheet[3] == 'Assembly') {
                        $data['item_id2'] = 0;
                    } else {
                        $item_id = DB::table('inventory_rawmaterial')->where('item_code', $excelsheet[3])->first();
                        $data['item_id2'] = $item_id->id;
                    }
                } else {
                    $data['item_id2'] = null;
                }
                if ($excelsheet[2] && $excelsheet[2] != 'NA' && $excelsheet[2] != 'NA') {
                    if ($excelsheet[2] == 'Assembly') {
                        $data['item_id1'] = 0;
                    } else {
                        $item_id = DB::table('inventory_rawmaterial')->where('item_code', $excelsheet[2])->first();

                        $data['item_id1'] = $item_id->id;
                    }
                } else {
                    $data['item_id1'] = null;
                }

                $product_id = DB::table('product_product')->where('sku_code', $excelsheet[0])->first();
                if ($product_id) {
                    $data['product_id'] = $product_id->id;
                }


                $product_product = DB::table('product_input_material')
                    ->select('product_input_material.*')
                    ->join('product_product', 'product_product.id', '=', 'product_input_material.product_id')
                    ->where('product_product.sku_code', $excelsheet[0])->first();




                //print_r(json_encode($data));exit;
                //$product_product = DB::table('product_product')->where('sku_code',$excelsheet[1])->first();
                if (empty($product_product)) {
                    // $res[] = DB::table('product_input_material')->insert([
                    //     'product_id' => $data['product_id'],
                    //     'item_id1' => $data['item_id1'],
                    //     'item_id2' => $data['item_id2'],
                    //     'item_id3' => $data['item_id3']
                    // ]);
                    $item =[
                        'product_id'=>$data['product_id'],
                        'item_id1'=>$data['item_id1'],
                        'item_id2'=>$data['item_id2'],
                        'item_id2'=>$data['item_id3'],
                        
                    ];
                    $res[] = DB::table('product_input_material')->insert($item); 
                }
            }
        }
        
        if (!empty($res))
            return 1;
        else
            return 0;
    }

    //echo "yes";exit;
    //     $da['label_format_number'] = $excelsheet[30];
    //     $da['drug_license_number'] = $excelsheet[42];
    //     $da['is_sterile'] = (strtolower($excelsheet[31]) == 'yes') ? 1 : 0;
    //     $da['process_sheet_no'] = $excelsheet[16];
    //     $da['groups'] = $excelsheet[49];
    //     $da['family'] =  $excelsheet[28];
    //     $da['brand'] = $excelsheet[27];
    //     $da['is_ce_marked'] = (strtolower($excelsheet[36]) == 'yes') ? 1 : ((strtolower($excelsheet[36]) == 'no') ? 0 : NULL) ;
    //     $da['notified_body_number'] = $excelsheet[37];
    //     $da['is_ear_log_address'] = (strtolower($excelsheet[38]) == 'yes') ? 1 : ((strtolower($excelsheet[38]) == 'no') ? 0 : NULL) ;

    //     $da['is_read_instruction_logo'] = (strtolower($excelsheet[39]) == 'yes') ? 1 : ((strtolower($excelsheet[39]) == 'no') ? 0 : NULL) ;

    //     $da['is_instruction'] = (strtolower($excelsheet[39]) == 'yes') ? 1 : 0;

    //     $da['is_temperature_logo'] = (strtolower($excelsheet[40]) == 'yes') ? 1 : ((strtolower($excelsheet[40]) == 'no') ? 0 : NULL) ;

    //     $da['is_donot_reuse_logo'] = (strtolower($excelsheet[41]) == 'yes') ? 1 : ((strtolower($excelsheet[41]) == 'no') ? 0 : NULL) ;

    //     $da['is_reusable'] = (strtolower($excelsheet[41]) == 'yes') ? 1 : 0;

    //     $da['drug_license_number'] = $excelsheet[42];
    //     //$da['hsn_code'] = $excelsheet[43];
    //     // $da['product_group_id'] = ((($excelsheet[49]) == '') ? NULL : $this->identify_id($excelsheet[49],"product group"));
    //     // $da['brand_details_id'] = ((($excelsheet[27]) == '') ? NULL : $this->identify_id($excelsheet[27],"product brand"));
    //     // $da['product_family_id'] = ((($excelsheet[28]) == '') ? NULL : $this->identify_id($excelsheet[28],"product family"));
    //     if(empty($excelsheet[49]))
    //     $da['product_group_id'] = NULL;
    //     else
    //     $da['product_group_id'] = $this->identify_id($excelsheet[49],"product group");
    //     if($excelsheet[27]=='N. A.' || $excelsheet[27]=='')
    //     $da['brand_details_id'] = NULL;
    //     else
    //     $da['brand_details_id'] = $this->identify_id($excelsheet[27],"product brand");
    //     $da['snn_description']= $excelsheet[29];
    //     $da['revision_record'] = $excelsheet[47];
    //     $da['is_inactive'] = (strtolower($excelsheet[48]) == 'y') ? 1 : ((strtolower($excelsheet[48]) == 'n') ? 0 : NULL);
    //     $da['gs1_code'] = $excelsheet[50];
    //     $da['is_control_sample_applicable'] = (strtolower($excelsheet[51]) == 'yes') ? 1 : ((strtolower($excelsheet[51]) == 'no') ? 0 : NULL);
    //     $da['is_doc_applicability'] =(strtolower($excelsheet[52]) == 'yes') ? 1 : ((strtolower($excelsheet[52]) == 'no') ? 0 : NULL);
    //     $da['is_instruction_for_reuse_symbol'] = $excelsheet[53] ? $excelsheet[53]  : NULL;
    //     $da['is_donot_reuse_symbol'] = $excelsheet[54] ? $excelsheet[54] : NULL ; 
    //     $da['ce_logo'] = $excelsheet[56]  ? $excelsheet[56]  : NULL; 
    //     $da['ad_sp1'] =  $excelsheet[57];
    //     $da['ad_sp2'] = $excelsheet[58];
    //     $da['groups'] = $excelsheet[49] ;
    //     $da['family'] =  $excelsheet[28];
    //     $da['brand'] = $excelsheet[27];
    //     // if($excelsheet[28]=='N. A.' || $excelsheet[28]=='')
    //     // $da['product_family_id'] = NULL;
    //     // else
    //     // $da['product_family_id'] = $this->identify_id($excelsheet[27],"product family");
    //    // $data['product_group_id'] = ((($excelsheet[49]) == '') ? NULL : $this->identify_id($excelsheet[49],"product group")) ;
    //     $res[] = DB::table('product_product')->where('id', $product_product->id)->update($da);
    // }

}
