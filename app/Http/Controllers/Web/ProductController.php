<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\product;
use App\Models\product_input_material;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\FGS\product_product;
use App\Models\FGS\product_productfamily;
use App\Models\PurchaseDetails\product_productgroup;
use App\Models\FGS\product_productbrand;
use Validator;
use Redirect;
use DB; 
use App\Models\FGS\product_stock_location;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    public function __construct()
    {
        $this->product = new product;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->product_input_material = new product_input_material;
        $this->product_stock_location = new product_stock_location;
        $this->product_product = new product_product;
        $this->product_productfamily = new product_productfamily;
        $this->product_productgroup = new product_productgroup;
        $this->product_productbrand = new product_productbrand;
    }

    public function productList(Request $request)
    {
        //$this->prd_InputmaterialUpload();
         $condition =[];
         
          if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%'.$request->sku_code.'%'];
        }
        if($request->brand_name)
        {
            $condition[] = ['product_productbrand.brand_name','like', '%' . $request->brand_name . '%'];
        }
        if($request->group_name)
        {
            $condition[] = ['product_productgroup.group_name','like', '%' . $request->group_name . '%'];
        }
        // if($request->is_sterile == 1)
        // {
        //     $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        // }
        // if($request->is_sterile == 0)
        // {
        //     $condition[] = ['product_product.is_sterile','like', '%' . $request->is_sterile . '%'];
        // }
        $pcondition = $this->product_product->get()->unique('is_sterile');
        
        $data['products'] = $this->product->get_products($condition);
        
        return view('pages/product/product-list',compact('data','pcondition'));
    }
    public function addInputMaterial(Request $request,$product_id=null)
    {
        if ($request->isMethod('post')) 
        {
            $validation['product_id'] = ['required'];
            $validation['moreItems.*.Itemcode'] = ['required'];
            //$validation['moreItems.*.quantity'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                foreach ($request->moreItems as $key => $value) 
                {
                    $Request = [
                                    "product_id"=>$request['product_id'],
                                    "item_id1" => $value['Itemcode'],
                                    "quantity1"=> $value['quantity'],
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

    public function productAdd(Request $request,$id=null)
    {
        if ($request->isMethod('post')) 
        {
             $validation['sku_code'] = ['required'];
            //  $validation['product_family_id'] = ['required'];
            //  $validation['product_group_id'] = ['required'];
            //  $validation['brand_details_id'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            {
             if ($request->hasFile('image')) {
              $image  = $request->file('image');
              $image_fileName = $request->file('image')->getClientOriginalName(); 
              $image->move(public_path('uploads/process_sheets'), $image_fileName);
             }else{
             $image_fileName =Null;
             } 
                $data['sku_code'] = $request->sku_code;
                $data['sku_name'] = $request->sku_name;
                $data['discription'] = $request->discription;
                $data['short_name'] = $request->short_name;
                $data['ad_sp1'] = $request->ad_sp1;
                $data['given_reason'] = $request->given_reason;
                $data['minimum_stock'] = $request->minimum_stock;
                $data['expiry'] = $request->expiry;
                $data['sterilization_type'] = $request->sterilization_type;
                $data['is_reusable'] = $request->is_reusable;
                $data['is_instruction'] = $request->is_instruction;
                $data['is_sterile'] = $request->is_sterile;
                $data['ad_sp2'] = $request->ad_sp2;
                $data['mrp'] = $request->mrp;
                $data['product_family_id'] = $request->product_family_id;
                $data['product_group_id'] = $request->product_group_id;
                $data['brand_details_id'] = $request->brand_details_id;
                $data['snn_description'] = $request->snn_description;
                $data['drug_license_number'] = $request->drug_license_number;
                $data['hierarchy_path'] = $request->hierarchy_path;
                $data['hsn_code'] = $request->hsn_code;
                $data['instruction_for_use'] = $request->instruction_for_use;
                $data['is_ce_marked'] = $request->is_ce_marked;
                $data['is_control_sample_applicable'] = $request->is_control_sample_applicable;
                $data['is_doc_applicability'] = $request->is_doc_applicability;
                $data['is_donot_reuse_logo'] = $request->is_donot_reuse_logo;
                $data['is_donot_reuse_symbol'] = $request->is_donot_reuse_symbol;
                $data['is_ear_log_address'] = $request->is_ear_log_address;
                $data['is_instruction_for_reuse_symbol'] = $request->is_instruction_for_reuse_symbol;
                $data['is_non_sterile_logo'] = $request->is_non_sterile_logo;
                $data['is_read_instruction_logo'] = $request->is_read_instruction_logo;
                $data['item_type'] = $request->item_type;
                $data['label_format_number'] = $request->label_format_number;
                $data['maximum_stock'] = $request->maximum_stock;
                $data['min_stock_set_date'] = $request->min_stock_set_date;
                $data['notified_body_number'] = $request->notified_body_number;
                $data['over_stock_level'] = $request->over_stock_level;
                $data['quantity_per_pack'] = $request->quantity_per_pack;
                $data['record_file_no'] = $request->record_file_no;
                $data['revision_record'] = $request->revision_record;
                 $data['technical_file'] = $request->technical_file;
                $data['unit_weight_kg'] = $request->unit_weight_kg;
                $data['groups'] = $request->groups;
                $data['family'] = $request->family;
                $data['brand'] = $request->brand;
                $data['is_active'] = 1;
                $data['process_sheet_no'] = $request->process_sheet_no;
               // $data['process_sheet_pdf'] = $image_fileName;

                if($request->id){ 
                    $data['updated'] = date('Y-m-d H:i:s');
                    $data['updated'] = config('user')['user_id'];
                    $this->product->update_data(['id'=>$request->id],$data);
                    $request->session()->flash('success',"You have successfully updated a product !");
                    return redirect("product/list");
                }
                else{
                        
                        $data['created_by_id'] = config('user')['user_id'];
                        $data['created'] = date('Y-m-d H:i:s');
                        $data['updated'] = date('Y-m-d H:i:s');
                        $this->product->insert_data($data);
                        $request->session()->flash('success',"You have successfully added a product !");
                        return redirect("product/list");

                    
                }

            }
            if($validator->errors()->all()) 
            { 
            if($request->id)
            
                return redirect("product/Product-add".$id)->withErrors($validator)->withInput();
            else
                return redirect("product/Product-add")->withErrors($validator)->withInput();
            }
        }
        else
        { 

             if($request->id)
            {

            $data = product::find($request->id);
            $materials = $this->product_input_material->getAllData(['product_input_material.product_id'=>$request->product_id]);
            $family = $this->product_productfamily->distinct('product_productfamily.family_name')->get();
            $group = $this->product_productgroup->distinct('product_productgroup.group_name')->get();
            $brand = $this->product_productbrand->distinct('product_productbrand.brand_name')->get();
            //print_r($materials);exit;
            return view('pages/product/product-add', compact('data','materials','family','group','brand'));
        }
        else
            $materials = $this->product_input_material->getAllData(['product_input_material.product_id'=>$request->product_id]);
            $family = $this->product_productfamily->distinct('product_productfamily.family_name')->get();
            $group = $this->product_productgroup->distinct('product_productgroup.group_name')->get();
            $brand = $this->product_productbrand->distinct('product_productbrand.brand_name')->get();
            //print_r($materials);exit;
            return view('pages/product/product-add', compact('materials','family','group','brand'));
        }

    }

    public function alternativeInputMaterialAdd(Request $request)
    {
        $validation['materialid'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            $update = product_input_material::find($request['materialid']);
            if($request['itemcode2']!=null)
            $update->item_id2 =$request['itemcode2'];
            if($request['itemcode3']!=null)
            $update->item_id3 =$request['itemcode3'];
            if($request['itemcode2']!=null)
            $update->quantity2 =$request['quantity2'];
            if($request['itemcode2']!=null)
            $update->quantity3 =$request['quantity3'];
            $update->save();
            //('id','=',$request['materialid'])->update([$data]);
            $request->session()->flash('success', "Alternative Input material adding success..");
            return redirect()->back();

        }
        else
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        
    }
    public function alternativeInputMaterial(Request $request)
    {
        $product_input_material=product_input_material::select('product_product.sku_code','product_input_material.quantity1','product_input_material.quantity2',
        'product_input_material.quantity3','inventory_rawmaterial.item_code as item_code1','inventory_rawmaterial.discription as discription1','alternative2.discription as discription2',
        'alternative3.discription as discription3','alternative2.item_code as item_code2','alternative3.item_code as item_code3','inv_unit.unit_name as unit1','inv_unit2.unit_name as unit2',
        'inv_unit3.unit_name as unit3','product_input_material.item_id1','product_input_material.item_id2','product_input_material.item_id3')
                                    ->leftJoin('product_product','product_product.id','=','product_input_material.product_id')
                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','product_input_material.item_id1')
                                    ->leftJoin('inventory_rawmaterial as alternative2','alternative2.id','=','product_input_material.item_id2')
                                    ->leftJoin('inventory_rawmaterial as alternative3','alternative3.id','=','product_input_material.item_id3')
                                    ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
                                    ->leftjoin('inv_unit as inv_unit2','inv_unit2.id','=','alternative2.receipt_unit_id')
                                    ->leftjoin('inv_unit as inv_unit3','inv_unit3.id','=','alternative3.receipt_unit_id')
                                    ->where('product_input_material.id','=',$request->materialid)
                                    ->first();
        return $product_input_material;
    }

    public function deleteInputMaterial(Request $request)
    {
        $delete = product_input_material::where('id','=',$request->id)->update(['status'=>0]);
        if($delete)
        $request->session()->flash('succ', "Input material deleted successfully..");
        else
        $request->session()->flash('err', "Input material deleting failed..");
        return Redirect::back();
    }
    public function get_item1_id($material_id)
    {
        $input_material = product_input_material::find($material_id);
        return $input_material['item_id1'];
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
           // echo $sheet1_column_count;exit;
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
    function identify_id($data,$type)
    {
        if($type=='product group'){
           $id =  DB::table('product_productgroup')->where('group_name',$data)->first()->id;
           return $id;  
        }
        if($type=='product brand'){
            $id =  DB::table('product_productbrand')->where('brand_name',$data)->first()->id;
            return $id;  
        }
         if($type=='product family'){
            $id =  DB::table('product_productfamily')->where('family_name',$data)->first()->id;
            return $id;  
        }
        
    }
    public function insert_product($ExcelOBJ)
    {
        //print_r(json_encode($ExcelOBJ->excelworksheet));exit;
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 0) 
            {   
                //echo $excelsheet[49];exit;
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

                    $data['sku_name'] =  $excelsheet[1];
                    $data['snn_description']= $excelsheet[29];

                    $data['label_format_number'] = $excelsheet[30];
                    $data['is_sterile'] = (strtolower($excelsheet[31]) == 'yes') ? 1 : 0;
                    $data['is_non_sterile_logo'] =  (strtolower($excelsheet[32]) == 'yes') ? 1 : ((strtolower($excelsheet[32]) == 'no') ? 0 : NULL) ;
                    $data['sterilization_type'] = $excelsheet[33];
                    $data['is_sterile_expiry_date'] = (strtolower($excelsheet[34]) == 'yes') ? 1 : ((strtolower($excelsheet[34]) == 'no') ? 0 : NULL) ;
                    // $label_image = preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace('/\s+/', '', $excelsheet[35]));
                    // if ($excelsheet[35] && $label_image != 'NA' && $label_image != 'N. A.') {
                    //     $data['label_image'] = 'label_image/'.$excelsheet[35];
                    // } else {
                    //     $data['label_image'] = null;
                    // }
                    $data['is_ce_marked'] = (strtolower($excelsheet[36]) == 'yes') ? 1 : ((strtolower($excelsheet[36]) == 'no') ? 0 : NULL) ;
                    $data['notified_body_number'] = $excelsheet[37];
                    $data['is_ear_log_address'] = (strtolower($excelsheet[38]) == 'yes') ? 1 : ((strtolower($excelsheet[38]) == 'no') ? 0 : NULL) ;
                    
                    $data['is_read_instruction_logo'] = (strtolower($excelsheet[39]) == 'yes') ? 1 : ((strtolower($excelsheet[39]) == 'no') ? 0 : NULL) ;
                    
                    $data['is_instruction'] = (strtolower($excelsheet[39]) == 'yes') ? 1 : 0;
                    
                    $data['is_temperature_logo'] = (strtolower($excelsheet[40]) == 'yes') ? 1 : ((strtolower($excelsheet[40]) == 'no') ? 0 : NULL) ;
                    
                    $data['is_donot_reuse_logo'] = (strtolower($excelsheet[41]) == 'yes') ? 1 : ((strtolower($excelsheet[41]) == 'no') ? 0 : NULL) ;
                    
                    $data['is_reusable'] = (strtolower($excelsheet[41]) == 'yes') ? 1 : 0;
                    
                    $data['drug_license_number'] = $excelsheet[42];
                    $data['hsn_code'] = $excelsheet[43];
                    
                    //$data['product_group_id'] = $this->identify_id($excelsheet[49],"product group");
                    // $data['product_group_id'] = ((($excelsheet[49]) == '') ? NULL : $this->identify_id($excelsheet[49],"product group"));
                    // $data['brand_details_id'] = ((($excelsheet[27]) == '') ? NULL : $this->identify_id($excelsheet[27],"product brand"));
                    // $data['product_family_id'] = ((($excelsheet[28]) == '') ? NULL : $this->identify_id($excelsheet[28],"product family"));
                    if(empty($excelsheet[49]))
                    $data['product_group_id'] = NULL;
                    else
                    $data['product_group_id'] = $this->identify_id($excelsheet[49],"product group");
                    if($excelsheet[27]=='N. A.' || $excelsheet[27]=='')
                    $data['brand_details_id'] = NULL;
                    else
                    $data['brand_details_id'] = $this->identify_id($excelsheet[27],"product brand");
                    // if($excelsheet[28]=='N. A.' || $excelsheet[28]=='')
                    // $data['product_family_id'] = NULL;
                    // else
                    // $data['product_family_id'] = $this->identify_id($excelsheet[27],"product family");
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
                    $data['is_inactive'] = (strtolower($excelsheet[48]) == 'y') ? 1 : ((strtolower($excelsheet[48]) == 'n') ? 0 : NULL);
                    $data['gs1_code'] = $excelsheet[50];
                    $data['is_control_sample_applicable'] = (strtolower($excelsheet[51]) == 'yes') ? 1 : ((strtolower($excelsheet[51]) == 'no') ? 0 : NULL);
                    $data['is_doc_applicability'] =(strtolower($excelsheet[52]) == 'yes') ? 1 : ((strtolower($excelsheet[52]) == 'no') ? 0 : NULL);
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
                        //$res[] = 1;
                    }
                    else
                    {
                        //echo "yes";exit;
                        $da['label_format_number'] = $excelsheet[30];
                        $da['drug_license_number'] = $excelsheet[42];
                        $da['is_sterile'] = (strtolower($excelsheet[31]) == 'yes') ? 1 : 0;
                        $da['process_sheet_no'] = $excelsheet[16];
                        $da['groups'] = $excelsheet[49];
                        $da['family'] =  $excelsheet[28];
                        $da['brand'] = $excelsheet[27];
                        $da['is_ce_marked'] = (strtolower($excelsheet[36]) == 'yes') ? 1 : ((strtolower($excelsheet[36]) == 'no') ? 0 : NULL) ;
                        $da['notified_body_number'] = $excelsheet[37];
                        $da['is_ear_log_address'] = (strtolower($excelsheet[38]) == 'yes') ? 1 : ((strtolower($excelsheet[38]) == 'no') ? 0 : NULL) ;
                        
                        $da['is_read_instruction_logo'] = (strtolower($excelsheet[39]) == 'yes') ? 1 : ((strtolower($excelsheet[39]) == 'no') ? 0 : NULL) ;
                        
                        $da['is_instruction'] = (strtolower($excelsheet[39]) == 'yes') ? 1 : 0;
                        
                        $da['is_temperature_logo'] = (strtolower($excelsheet[40]) == 'yes') ? 1 : ((strtolower($excelsheet[40]) == 'no') ? 0 : NULL) ;
                        
                        $da['is_donot_reuse_logo'] = (strtolower($excelsheet[41]) == 'yes') ? 1 : ((strtolower($excelsheet[41]) == 'no') ? 0 : NULL) ;
                        
                        $da['is_reusable'] = (strtolower($excelsheet[41]) == 'yes') ? 1 : 0;
                        
                        $da['drug_license_number'] = $excelsheet[42];
                        //$da['hsn_code'] = $excelsheet[43];
                        // $da['product_group_id'] = ((($excelsheet[49]) == '') ? NULL : $this->identify_id($excelsheet[49],"product group"));
                        // $da['brand_details_id'] = ((($excelsheet[27]) == '') ? NULL : $this->identify_id($excelsheet[27],"product brand"));
                        // $da['product_family_id'] = ((($excelsheet[28]) == '') ? NULL : $this->identify_id($excelsheet[28],"product family"));
                        if(empty($excelsheet[49]))
                        $da['product_group_id'] = NULL;
                        else
                        $da['product_group_id'] = $this->identify_id($excelsheet[49],"product group");
                        if($excelsheet[27]=='N. A.' || $excelsheet[27]=='')
                        $da['brand_details_id'] = NULL;
                        else
                        $da['brand_details_id'] = $this->identify_id($excelsheet[27],"product brand");
                        $da['snn_description']= $excelsheet[29];
                        $da['revision_record'] = $excelsheet[47];
                        $da['is_inactive'] = (strtolower($excelsheet[48]) == 'y') ? 1 : ((strtolower($excelsheet[48]) == 'n') ? 0 : NULL);
                        $da['gs1_code'] = $excelsheet[50];
                        $da['is_control_sample_applicable'] = (strtolower($excelsheet[51]) == 'yes') ? 1 : ((strtolower($excelsheet[51]) == 'no') ? 0 : NULL);
                        $da['is_doc_applicability'] =(strtolower($excelsheet[52]) == 'yes') ? 1 : ((strtolower($excelsheet[52]) == 'no') ? 0 : NULL);
                        $da['is_instruction_for_reuse_symbol'] = $excelsheet[53] ? $excelsheet[53]  : NULL;
                        $da['is_donot_reuse_symbol'] = $excelsheet[54] ? $excelsheet[54] : NULL ; 
                        $da['ce_logo'] = $excelsheet[56]  ? $excelsheet[56]  : NULL; 
                        $da['ad_sp1'] =  $excelsheet[57];
                        $da['ad_sp2'] = $excelsheet[58];
                        $da['groups'] = $excelsheet[49] ;
                        $da['family'] =  $excelsheet[28];
                        $da['brand'] = $excelsheet[27];
                        // if($excelsheet[28]=='N. A.' || $excelsheet[28]=='')
                        // $da['product_family_id'] = NULL;
                        // else
                        // $da['product_family_id'] = $this->identify_id($excelsheet[27],"product family");
                       // $data['product_group_id'] = ((($excelsheet[49]) == '') ? NULL : $this->identify_id($excelsheet[49],"product group")) ;
                        $res[] = DB::table('product_product')->where('id', $product_product->id)->update($da);
                    }
            }
            
        }
        if($res)
        return 1;
        else 
        return 0;
       
    }

    public function prd_InputmaterialUpload()
    {
        // $file = $request->file('file');
        // if ($file) {

        //     $ExcelOBJ = new \stdClass();

        //     // CONF
        //     $path = storage_path().'/app/'.$request->file('file')->store('temp');

            $ExcelOBJ = new \stdClass();
            $ExcelOBJ->inputFileType = 'Xlsx';
           // $ExcelOBJ->filename = 'SL-1-01.xlsx';
            $ExcelOBJ->inputFileName ='C:\xampp\htdocs\prd294.xlsx';
            // $ExcelOBJ->filename = 'Book1.xlsx';
            // $ExcelOBJ->inputFileName = 'C:\xampp7.4\htdocs\mel\sampleData\Book1.xlsx';
            $ExcelOBJ->spreadsheet = new Spreadsheet();
            $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
            $ExcelOBJ->reader->setReadDataOnly(true);
            $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
            $no_column = 11;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
            if($sheet1_column_count == $no_column)
            {
                $res = $this->ExcelsplitsheetPrdFile($ExcelOBJ);
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
        //}
    }
    public function ExcelsplitsheetPrdFile($ExcelOBJ)
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
            $res = $this->insert_product_inputmaterials($ExcelOBJ);
            //return $res;
            //print_r($ExcelOBJ);exit;
        }
       // print_r($ExcelOBJ);exit;
       
    }
    public function insert_product_inputmaterials($ExcelOBJ)
    {
        //print_r(json_encode($ExcelOBJ->excelworksheet));exit;
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 1 && $excelsheet[0]) 
            {   
               // print_r(json_encode($excelsheet));exit;
                $product = product::where('sku_code',$excelsheet[0])->first();
                if($product)
                {
                    $input_material =product_input_material::Join('product_product','product_product.id','=','product_input_material.product_id')
                                        ->where('product_product.id','=', $excelsheet[0])->first();
                    // //$input_material=DB::table('product_input_material')->where('id','=',1)->first(); 
                    // print_r($input_material);exit;
                    if($excelsheet[2]!='N/A' || $excelsheet[3]!='N/A' || $excelsheet[4]!='N/A')
                    {
                        if($excelsheet[2]!='N/A' || $excelsheet[2]!='NA' )
                        {
                            if($excelsheet[2]=='Assembly')
                            {
                                $item_id1 = 0;
                            }
                            else
                            {
                                $item1 = inventory_rawmaterial::where('item_code',$excelsheet[2])->first();
                                if($item1)
                                $item_id1 = $item1['id'];
                                else
                                $item_id1 = NULL;
                            }
                        }
                        else
                        {
                            $item_id1 = NULL;
                        }
                        if($excelsheet[3]!='N/A' || $excelsheet[3]!='NA' || $excelsheet[3]!='#N/A')
                        {
                            if($excelsheet[3]!='Assembly')
                            {
                                $item2 = inventory_rawmaterial::where('item_code',$excelsheet[3])->first();
                                if($item2)
                                $item_id2 = $item2['id'];
                                else
                                $item_id2 = NULL;
                            }
                            else
                            {
                                $item_id2 = 0;
                            }
                        }
                        else 
                        {
                            $item_id2 = NULL;
                        }
                        if($excelsheet[4]!='N/A' || $excelsheet[4]!='NA')
                        {
                            $item3 = inventory_rawmaterial::where('item_code',$excelsheet[4])->first();
                            if($item3)
                            $item_id3 = $item3['id'];
                            else
                            $item_id3 = NULL;
                        }
                        else 
                        {
                            $item_id3 = NULL;
                        }
                        if(!$input_material)
                        {
                            $data =[
                                'product_id'=>$product['id'],
                                'item_id1'=>$item_id1,
                                'item_id2'=>$item_id2,
                                'item_id2'=>$item_id3,
                                'status'=>1,
                                'created_at'=>date('Y-m-d H:i:s'),
                            ];
                            // $data['status'] = 1;
                            // $data['created_at'] = date('Y-m-d H:i:s');
                        
                            $res[] = DB::table('product_input_material')->insert($data); 
                        }
                    }
                    // else
                    // {
                    //     //echo "yes";exit;
                    //     $data =[
                    //         'product_id'=>$product['id'],
                    //         'item_id1'=>$item_id1,
                    //         'item_id2'=>$item_id2,
                    //         'item_id2'=>$item_id3,
                    //         'status'=>1,
                    //         'created_at'=>date('Y-m-d H:i:s'),
                    //     ];
                    //     $res[] = DB::table('product_input_material')->where('id', $input_material['id'])->update($data);
                    // }
                }
                    
            }
            
        }
        if($res)
        return 1;
        else 
        return 0;
       
    }
       public function locationList(Request $request, $loc_id=null)
    {
        if ($request->isMethod('post'))
        {

        $validator = Validator::make($request->all(), [
            'location' => ['required', 'min:1', 'max:115'],
           ]);
        if (!$validator->errors()->all()) {
            $datas['location_name'] = $request->location;
            if (!$request->loc_id) {
                $this->product_stock_location->insert_location($datas);
                $request->session()->flash('success', 'Location  has been successfully inserted');
                return redirect("product/location");
            }
            else{
            $this->product_stock_location->update_location($datas, $request->loc_id);
            $request->session()->flash('success', 'Location  has been successfully updated');
            return redirect("product/location");
            }
        }
        if($validator->errors()->all()) 
                { 
                   
                    return redirect("product/location")->withErrors($validator)->withInput();
                }
       }
       else{
        if ($request->loc_id) {
            dd($request->loc_id);
             $data['location'] = $this->product_stock_location->get_locations();
            $edit = $this->product_stock_location->get_location($request->loc_id);
            dd($edit);
            //print_r($edit);exit;
            return view('pages.product.product_loc_add',compact('data','edit'));
        }
        else
        {
             $data['location'] = $this->product_stock_location->get_locations();
        return view('pages.product.product_loc_add',compact('data'));
    }
    }
    
}
public function productAddGroup()
{
    $product_group=DB::table('product_productgroup')
    ->orderby('id','DESC')
    //->get();
    ->paginate(15);
    return view('pages.product.product_group_add',compact('product_group'));
}
public function productAddingGroup(Request $request)
{
DB::table('product_productgroup')
->insert([
    'group_name'=>$request->pr_group,
    'created'=>date('Y-m-d')
]);
$product_group=DB::table('product_productgroup')
->orderby('id','DESC')
// ->get();//
->paginate(15);

return view('pages.product.product_group_add',compact('product_group'));
}
public function productAddFamily()
{
    $product_family=DB::table('product_productfamily')
    ->orderby('id','DESC')
    //->get();
    ->paginate(15);
    return view('pages.product.product_family_add',compact('product_family'));
}
public function productAddingFamily(Request $request)
{
$id=DB::table('product_productfamily')
->insertGetId([
   // ->insert([
    'family_name'=>$request->pr_family,
    'created'=>date('Y-m-d')
]);
DB::table('product_productfamily')
->where('id',$id)
->update([
    'specification_no'=>$id,
    
]);
$product_family=DB::table('product_productfamily')
->orderby('id','DESC')
// ->get();//
->paginate(15);

return view('pages.product.product_family_add',compact('product_family'));
}

public function productAddBrand()
{
    $product_brand=DB::table('product_productbrand')
    ->orderby('id','DESC')
    //->get();
    ->paginate(15);
    return view('pages.product.product_brand_add',compact('product_brand'));
}
public function productAddingBrand(Request $request)
{
DB::table('product_productbrand')
->insert([
    'brand_name'=>$request->pr_brand,
    'created'=>date('Y-m-d')
]);
$product_brand=DB::table('product_productbrand')
->orderby('id','DESC')
// ->get();//
->paginate(15);

return view('pages.product.product_brand_add',compact('product_brand'));
}
public function get_product_family($id)
{
    $product_family=DB::table('product_productfamily')
    ->where('id',$id)
    ->pluck('family_name')[0];

    return $product_family;
}
public function get_product_brand($id)
{
    $product_brand=DB::table('product_productbrand')
    ->where('id',$id)
    ->pluck('brand_name')[0];
    
    return $product_brand;
}
public function get_product_group($id)
{
    $product_group=DB::table('product_productgroup')
    ->where('id',$id)
    ->pluck('group_name')[0];
    
    return $product_group;
}
}
