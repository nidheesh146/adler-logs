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
        
        $data['products'] = $this->product->get_products_prdct($condition);
        
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


    public function productAdd(Request $request, $id = null)
    {
        //dd('product');
       // print_r($_POST);
        if ($request->isMethod('post')) {

            if (!empty($request->pimage)) {

                $validation['pimage'] = ['image', 'mimes:jpeg,png,jpg'];
            }
            if (!empty($request->pdf)) {
                $validation['pdf'] = ['required|mimes:pdf|max:10240']; // 10MB limit
            }

            $validation['sku_code'] = ['required'];
            //  $validation['product_family_id'] = ['required'];
            //  $validation['product_group_id'] = ['required'];
            //  $validation['brand_details_id'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if ($request->pimage) {

                $imageFile = $request->file('pimage');

                // Check if the uploaded file is not empty
                if ($imageFile->isValid()) {
                    $imageName = $request->sku_code . '.' . $imageFile->getClientOriginalExtension();

                    $imageFile->move(public_path('img/label_image'), $imageName);
                }
            }
            if ($request->pdf) {

                $pdfFile = $request->file('pdf');

                // Check if the uploaded file is not empty
                if ($pdfFile->isValid()) {
                    // if($request->process_sheet_no){
                    $pdfName = $request->sku_code . '.' . $pdfFile->getClientOriginalExtension();
                    $pdfFile->move(public_path('pdf'), $pdfName);
                    // }else{
                    //     $request->session()->flash('error', "Please Add Process sheet no!");
                    //     return redirect()->back();                    }

                }
            }

            // if(!$validator->errors()->all()) 
            // {
            //  if ($request->hasFile('image')) {
            // //   $image  = $request->file('image');
            // //   $image_fileName = $request->file('image')->getClientOriginalName(); 
            // //   $image->move(public_path('uploads/product_img'), $image_fileName);
            // $imageName = $request->sku_code.'.'.$request->pimage->getClientOriginalExtension();  
            // $request->pimage->move(public_path('img/productimg'), $imageName);
            //$datas = $imageName;
            //  }else{
            //  $imageName =Null;
            //  }
            if (!empty($request->sku_code)) {
                $data['sku_code'] = $request->sku_code;
            }
            if (!empty($request->sku_name)) {
                $data['sku_name'] = $request->sku_name;
            }
            if (!empty($request->discription)) {
                $data['discription'] = $request->discription;
            }
            if (!empty($request->short_name)) {
                $data['short_name'] = $request->short_name;
            }
            if (!empty($request->ad_sp1)) {
                $data['ad_sp1'] = $request->ad_sp1;
            }
            if (!empty($request->given_reason)) {
                $data['given_reason'] = $request->given_reason;
            }
            if (!empty($request->minimum_stock)) {
                $data['minimum_stock'] = $request->minimum_stock;
            }
            if (!empty($request->expiry)) {
                $data['expiry'] = $request->expiry;
            }
            if (!empty($request->sterilization_type)) {
                $data['sterilization_type'] = $request->sterilization_type;
            }
            if (!empty($request->is_reusable)) {
                $data['is_reusable'] = $request->is_reusable;
            }
            if (!empty($request->is_instruction)) {
                $data['is_instruction'] = $request->is_instruction;
            }
            if (!empty($request->is_sterile)) {
                $data['is_sterile'] = $request->is_sterile;
            }
            if (!empty($request->ad_sp2)) {
                $data['ad_sp2'] = $request->ad_sp2;
            }
            if (!empty($request->mrp)) {
                $data['mrp'] = $request->mrp;
            }
            if (!empty($request->product_family_id)) {
                $data['product_family_id'] = $request->product_family_id;
            }
            if (!empty($request->product_group_id)) {
                $data['product_group_id'] = $request->product_group_id;
            }
            if (!empty($request->brand_details_id)) {
                $data['brand_details_id'] = $request->brand_details_id;
            }
            if (!empty($request->snn_description)) {
                $data['snn_description'] = $request->snn_description;
            }
            if (!empty($request->drug_license_number)) {
                $data['drug_license_number'] = $request->drug_license_number;
            }
            if (!empty($request->hierarchy_path)) {
                $data['hierarchy_path'] = $request->hierarchy_path;
            }
            if (!empty($request->hsn_code)) {
                $data['hsn_code'] = $request->hsn_code;
            }
            if (!empty($request->instruction_for_use)) {
                $data['instruction_for_use'] = $request->instruction_for_use;
            }
            if (!empty($request->is_ce_marked)) {
                $data['is_ce_marked'] = $request->is_ce_marked;
            }
            if (!empty($request->is_control_sample_applicable)) {
                $data['is_control_sample_applicable'] = $request->is_control_sample_applicable;
            }
            if (!empty($request->is_doc_applicability)) {
                $data['is_doc_applicability'] = $request->is_doc_applicability;
            }
            if (!empty($request->is_donot_reuse_logo)) {
                $data['is_donot_reuse_logo'] = $request->is_donot_reuse_logo;
            }
            if (!empty($request->is_donot_reuse_symbol)) {
                $data['is_donot_reuse_symbol'] = $request->is_donot_reuse_symbol;
            }
            if (!empty($request->is_ear_log_address)) {
                $data['is_ear_log_address'] = $request->is_ear_log_address;
            }
            if (!empty($request->is_instruction_for_reuse_symbol)) {
                $data['is_instruction_for_reuse_symbol'] = $request->is_instruction_for_reuse_symbol;
            }
            if (!empty($request->is_non_sterile_logo)) {
                $data['is_non_sterile_logo'] = $request->is_non_sterile_logo;
            }
            if (!empty($request->is_read_instruction_logo)) {
                $data['is_read_instruction_logo'] = $request->is_read_instruction_logo;
            }
            if (!empty($request->item_type)) {
                $data['item_type'] = $request->item_type;
            }
            if (!empty($request->label_format_number)) {
                $data['label_format_number'] = $request->label_format_number;
            }
            if (!empty($request->maximum_stock)) {
                $data['maximum_stock'] = $request->maximum_stock;
            }
            if (!empty($request->min_stock_set_date)) {
                $data['min_stock_set_date'] = $request->min_stock_set_date;
            }
            if (!empty($request->notified_body_number)) {
                $data['notified_body_number'] = $request->notified_body_number;
            }
            if (!empty($request->over_stock_level)) {
                $data['over_stock_level'] = $request->over_stock_level;
            }
            if (!empty($request->quantity_per_pack)) {
                $data['quantity_per_pack'] = $request->quantity_per_pack;
            }
            if (!empty($request->record_file_no)) {
                $data['record_file_no'] = $request->record_file_no;
            }
            if (!empty($request->revision_record)) {
                $data['revision_record'] = $request->revision_record;
            }
            if (!empty($request->technical_file)) {
                $data['technical_file'] = $request->technical_file;
            }
            if (!empty($request->unit_weight_kg)) {
                $data['unit_weight_kg'] = $request->unit_weight_kg;
            }
            if (!empty($request->groups)) {
                $data['groups'] = $request->groups;
            }
            if (!empty($request->family)) {
                $data['family'] = $request->family;
            }
            if (!empty($request->brand)) {
                $data['brand'] = $request->brand;
            }
            $data['is_active'] = $request->has('is_active') ? $request->is_active : 0;

           // $data['is_active'] = 1;

            if (!empty($request->process_sheet_no)) {
                $data['process_sheet_no'] = $request->process_sheet_no;
            }
            if (!empty($request->pimage)) {
                $data['label_image'] = 'label_image/' . $imageName;
            }
            if (!empty($request->pdf)) {
                $data['pdf'] = 'pdf/' . $pdfName;
            }
            // $data['process_sheet_pdf'] = $image_fileName;

            if ($request->id) {
                $data['updated'] = date('Y-m-d H:i:s');
                //$data['updated_by_id'] = config('user')['user_id']; // Fixed 'updated' to 'updated_by_id'
               // dd($data);
                $this->product->update_data(['id' => $request->id], $data);
                $request->session()->flash('success', "You have successfully updated a product !");
                return redirect("product/list");
            } else {
                // Prepare the data for insertion into the product table
                $data['created_by_id'] = config('user')['user_id'];
                $data['created'] = date('Y-m-d H:i:s');
                $data['updated'] = date('Y-m-d H:i:s');
                //dd($data);
                $this->product->insert_data($data);
                
                // If the item is either FINISHED GOODS or SEMIFINISHED GOODS, insert into fgs_item_master
                // if (in_array($data['item_type'], ['FINISHED GOODS', 'SEMI FINISHED GOODS'])) {
                //     $fgsItemData = [
                //         'sku_code' => $data['sku_code'],
                //         'sku_name' => $data['sku_name'],
                //         'item_type' => $data['item_type'],
                //        // 'minimum_stock' => $data['minimum_stock'],
                //        // 'maximum_stock' => $data['maximum_stock'],
                //         'is_sterile' => $data['is_sterile'],
                //        // 'quantity_per_pack' => $data['quantity_per_pack'],
                //         'hsn_code' => $data['hsn_code'],
                //         //'gst' => $data['gst'],
                //         'created_by_id' => config('user')['user_id'],
                //         'is_active' => $data['is_active'],
                //         'created' => now(),
                //         'updated' => now(),
                //        // 'status_type' => $data['status_type'],
                //     ];
                //     DB::table('fgs_item_master')->insert($fgsItemData); // Insert into fgs_item_master table
                // }
                
                $request->session()->flash('success', "You have successfully added a product !");
                return redirect("product/list");
            }
            
            if ($validator->errors()->all()) {
                if ($request->id) {
                    return redirect("product/Product-add" . $id)->withErrors($validator)->withInput();
                } else {
                    return redirect("product/Product-add")->withErrors($validator)->withInput();
                }
            }
            } else {
                if ($request->id) {
                    $data = product::find($request->id);
                    $materials = $this->product_input_material->getAllData(['product_input_material.product_id' => $request->product_id]);
                    $family = $this->product_productfamily->distinct('product_productfamily.family_name')->get();
                    $group = $this->product_productgroup->distinct('product_productgroup.group_name')->get();
                    $brand = $this->product_productbrand->distinct('product_productbrand.brand_name')->get();
                    return view('pages/product/Product-add', compact('data', 'materials', 'family', 'group', 'brand'));
                } else {
                    $materials = $this->product_input_material->getAllData(['product_input_material.product_id' => $request->product_id]);
                    $family = $this->product_productfamily->distinct('product_productfamily.family_name')->get();
                    $group = $this->product_productgroup->distinct('product_productgroup.group_name')->get();
                    $brand = $this->product_productbrand->distinct('product_productbrand.brand_name')->get();
                    return view('pages/product/Product-add', compact('materials', 'family', 'group', 'brand'));
                }
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
        return Redirect('fgs/product-master/list');
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
       // dd('hi');
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
                $request->session()->flash('success',  "Successfully uploaded.");
                return redirect('product/file/upload');
                 //print_r($res);exit;
                //  if($res)
                //  {
                //     $request->session()->flash('success',  "Successfully uploaded.");
                //     return redirect('product/file/upload');
                //  }
                //  else{
                //     $request->session()->flash('error',  "The data already uploaded.");
                //     return redirect('product/file/upload');
                //  }
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
            if($data=='N. A.')
            return NULL;
            else{
                $grp =  DB::table('product_productgroup')->where('group_name','=',$data)->first();
                //return $id; 
                if($grp)
                {
                    return $grp->id;
                }
                else
                {
                    return redirect('product/list')->with('error', 'The product group  "'.$data.'"  not exist on Database.So add this product group, then try to upload.');;
                } 
            }
        }
        if($type=='product brand'){
            $brand =  DB::table('product_productbrand')->where('brand_name',$data)->first();
            if($brand)
            {
                return $brand->id;
            }
            else
            {
                return redirect('product/list')->with('error', 'The product brand  "'.$data.'"  not exist on Database.So add this product brand, then try to upload.');;
            } 
        }
         if($type=='product family'){
            $family =  DB::table('product_productfamily')->where('family_name',$data)->first();
            if($family)
            {
                return $family->id;
            }
            else
            {
                return redirect('product/list')->with('error', 'The product family  "'.$data.'"  not exist on Database.So add this product family, then try to upload.');;
            }   
        }
        
    }
    public function insert_product($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 0) 
            {   
                // Initialize the $data array with default values
                $data = [
                    'minimum_stock' => $excelsheet[44] ?? null,
                    'maximum_stock' => "0",
                    'min_stock_set_date' => isset($excelsheet[47]) && preg_match('/[^NAna]/i', $excelsheet[47])
                        ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[47]))->format('Y-m-d')
                        : null,
                    'over_stock_level' => $excelsheet[46] ?? null,
                    'discription' => $excelsheet[2] ?? null,
                    'is_active' => 1,
                    'published_date' => isset($excelsheet[6]) && preg_match('/[^NAna]/i', $excelsheet[6])
                        ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')
                        : null,
                    'record_file_no' => $excelsheet[22] ?? null,
                    'technical_file' => $excelsheet[23] ?? null,
                    'instruction_for_use' => $excelsheet[24] ?? null,
                    'classification_mdd' => $excelsheet[25] ?? null,
                    'item_type' => $excelsheet[26] ?? null,
                    'family_name' => $excelsheet[28] ?? null,
                    'sku_name' => $excelsheet[1] ?? null,
                    'snn_description' => $excelsheet[29] ?? null,
                    'label_format_number' => $excelsheet[30] ?? null,
                    'is_sterile' => (isset($excelsheet[31]) && strtolower($excelsheet[31]) == 'yes') ? 1 : 0,
                    'is_non_sterile_logo' => isset($excelsheet[32]) ? ((strtolower($excelsheet[32]) == 'yes') ? 1 : 0) : null,
                    'sterilization_type' => $excelsheet[33] ?? null,
                    'is_sterile_expiry_date' => isset($excelsheet[34]) ? ((strtolower($excelsheet[34]) == 'yes') ? 1 : 0) : null,
                    'is_ce_marked' => isset($excelsheet[36]) ? ((strtolower($excelsheet[36]) == 'yes') ? 1 : 0) : null,
                    'notified_body_number' => $excelsheet[37] ?? null,
                    'is_ear_log_address' => isset($excelsheet[38]) ? ((strtolower($excelsheet[38]) == 'yes') ? 1 : 0) : null,
                    'is_read_instruction_logo' => isset($excelsheet[39]) ? ((strtolower($excelsheet[39]) == 'yes') ? 1 : 0) : null,
                    'is_instruction' => isset($excelsheet[39]) ? ((strtolower($excelsheet[39]) == 'yes') ? 1 : 0) : null,
                    'is_temperature_logo' => isset($excelsheet[40]) ? ((strtolower($excelsheet[40]) == 'yes') ? 1 : 0) : null,
                    'is_donot_reuse_logo' => isset($excelsheet[41]) ? ((strtolower($excelsheet[41]) == 'yes') ? 1 : 0) : null,
                    'drug_license_number' => $excelsheet[42] ?? null,
                    'hsn_code' => $excelsheet[43] ?? null,
                    'product_group_id' => isset($excelsheet[49]) ? $this->identify_id($excelsheet[49], "product group") : null,
                    'brand_details_id' => isset($excelsheet[27]) ? $this->identify_id($excelsheet[27], "product brand") : null,
                    'quantity_per_pack' => $excelsheet[44] ?? null,
                    'hierarchy_path' => $excelsheet[45] ?? null,
                    'unit_weight_kg' => $excelsheet[46] ?? null,
                    'revision_record' => $excelsheet[47] ?? null,
                    'is_inactive' => isset($excelsheet[48]) ? ((strtolower($excelsheet[48]) == 'y') ? 1 : 0) : null,
                    'gs1_code' => $excelsheet[50] ?? null,
                    'is_control_sample_applicable' => isset($excelsheet[51]) ? ((strtolower($excelsheet[51]) == 'yes') ? 1 : 0) : null,
                    'is_doc_applicability' => isset($excelsheet[52]) ? ((strtolower($excelsheet[52]) == 'yes') ? 1 : 0) : null,
                    'is_instruction_for_reuse_symbol' => $excelsheet[53] ?? null,
                    'is_donot_reuse_symbol' => $excelsheet[54] ?? null,
                    'ce_logo' => $excelsheet[56] ?? null,
                    'ad_sp1' => $excelsheet[57] ?? null,
                    'ad_sp2' => $excelsheet[58] ?? null,
                    'groups' => $excelsheet[49] ?? null,
                    'family' => $excelsheet[28] ?? null,
                    'brand' => $excelsheet[27] ?? null,
                    'sku_code' => $excelsheet[1] ?? null
                ];
    
                $product_product = DB::table('product_product')->where('sku_code', $excelsheet[1] ?? '')->first();
                if (!$product_product) {
                    $res[] = DB::table('product_product')->insert($data);
                } else {
                    $res[] = DB::table('product_product')->where('id', $product_product->id)->update($data);
                }
            }
        }
    
        return isset($res) && !empty($res) ? 1 : 0;
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
        //dd('group');
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

    public function upload_product_inputmaterial()
    {
        return view('pages/product/input-material-upload');
    }
    public function inputMaterialFileUpload(Request $request)
    {
        
        $file = $request->file('file');
        if ($file) 
        {
            
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
            $no_column = 10;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
            if ($sheet1_column_count == $no_column) {
                $res = $this->Excelsplitsheet_prd29($ExcelOBJ);
                if ($res) {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('product/list');
                } else {
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect('product/list');
                }
            } else {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect('product/input-material-upload');
            }
        }
    }
    public function Excelsplitsheet_prd29($ExcelOBJ)
    {
        //echo "kk";exit;
        ini_set('max_execution_time', 500);
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;
        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) {
            $ExcelOBJ->sectionName = '';
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();

            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $res = $this->insert_product_inputmaterial($ExcelOBJ);
            return $res;
        }
        // print_r($ExcelOBJ);exit;

    }
    public function insert_product_inputmaterial($ExcelOBJ)
    {
        //print_r(json_encode($ExcelOBJ->excelworksheet));exit;
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
            if ($key > 0) {
                if ($excelsheet[4] && $excelsheet[4] != 'NA' && $excelsheet[4] != 'N/A') {
                    if ($excelsheet[4] == 'Assembly') {
                        $data['item_id3'] = 0;
                    } else {
                        $item_id = DB::table('inventory_rawmaterial')->where('item_code', $excelsheet[4])->first();
                        if($item_id) {
                            $data['item_id3'] = $item_id->id;
                        }
                        else {
                            $product = DB::table('product_product')->where('sku_code',$excelsheet[4])->first();
                            if($product)
                            {
                                $info['item_code']=$product->sku_code;
                                $info['item_name']=$product->sku_code;
                                $info['item_short_name']=$product->sku_code;
                                $info['discription'] =$product->discription;
                                $info['short_description'] =$product->discription;
                                $info['is_product'] = 1;
                                $info['issue_unit_id'] = DB::table('inv_unit')->where('unit_name','Nos')->pluck('id')->first();
                                $raw_material_id3 = $this->inventory_rawmaterial->insertdata($info);
                                $data['item_id3'] =$raw_material_id3;
                            }
                            else
                            {
                                $raw['item_code']=$excelsheet[4];
                                $raw['item_name']=$excelsheet[4];
                                $raw['item_short_name']=$excelsheet[4];
                                $raw_material_id3 = $this->inventory_rawmaterial->insertdata($raw);
                                $data['item_id3'] =$raw_material_id3;
                            }
                        }

                    }
                } else {
                    $data['item_id3'] = null;
                }
                if ($excelsheet[3] && $excelsheet[3] != 'NA' && $excelsheet[3] != 'N/A') {
                    if ($excelsheet[3] == 'Assembly') {
                        $data['item_id2'] = 0;
                    } else {
                        $item_id = DB::table('inventory_rawmaterial')->where('item_code', $excelsheet[3])->first();
                        if($item_id) {
                            $data['item_id2'] = $item_id->id;
                        }
                        else
                        {
                            $product = DB::table('product_product')->where('sku_code',$excelsheet[3])->first();
                            if($product)
                            {
                                $info['item_code']=$product->sku_code;
                                $info['item_name']=$product->sku_code;
                                $info['item_short_name']=$product->sku_code;
                                $info['discription'] =$product->discription;
                                $info['short_description'] =$product->discription;
                                $info['is_product'] = 1;
                                $info['issue_unit_id'] = DB::table('inv_unit')->where('unit_name','Nos')->pluck('id')->first();
                                $raw_material_id2 = $this->inventory_rawmaterial->insertdata($info);
                                $data['item_id2'] =$raw_material_id2;
                            }
                            else
                            {
                                $raw['item_code']=$excelsheet[3];
                                $raw['item_name']=$excelsheet[3];
                                $raw['item_short_name']=$excelsheet[3];
                                $raw_material_id2 = $this->inventory_rawmaterial->insertdata($raw);
                                $data['item_id2'] =$raw_material_id2;
                            }
                        }
                        //$data['item_id2'] = $item_id->id;
                    }
                } else {
                    $data['item_id2'] = null;
                }
                if ($excelsheet[2] && $excelsheet[2] != 'NA' && $excelsheet[2] != 'NA') {
                    if ($excelsheet[2] == 'Assembly') {
                        $data['item_id1'] = 0;
                    } else {
                        $item_id = DB::table('inventory_rawmaterial')->where('item_code', $excelsheet[2])->first();
                        if($item_id)
                        {   
                            $data['item_id1'] = $item_id->id;
                        }
                        else
                        {
                            $product = DB::table('product_product')->where('sku_code',$excelsheet[2])->first();
                            if($product)
                            {
                                $info['item_code']=$product->sku_code;
                                $info['item_name']=$product->sku_code;
                                $info['item_short_name']=$product->sku_code;
                                $info['discription'] =$product->discription;
                                $info['short_description'] =$product->discription;
                                $info['is_product'] = 1;
                                $info['issue_unit_id'] = DB::table('inv_unit')->where('unit_name','Nos')->pluck('id')->first();
                                $raw_material_id = $this->inventory_rawmaterial->insertdata($info);
                                $data['item_id1'] =$raw_material_id;
                            }
                            else
                            {
                                $raw['item_code']=$excelsheet[2];
                                $raw['item_name']=$excelsheet[2];
                                $raw['item_short_name']=$excelsheet[2];
                                $raw_material_id1 = $this->inventory_rawmaterial->insertdata($raw);
                                $data['item_id1'] =$raw_material_id1;
                            }
                        }
                    }
                } else {
                    $data['item_id1'] = null;
                }

                $product_id = DB::table('product_product')->where('sku_code', $excelsheet[0])->first();
                if ($product_id) {
                    $data['product_id'] = $product_id->id;
                    $input_material = DB::table('product_input_material')->where('product_id','=',$product_id->id)->first();
                    if( $input_material)
                    $res[] = DB::table('product_input_material')->where('product_id','=',$product_id->id)->update($data); 
                    else
                    $res[] = DB::table('product_input_material')->insert($data); 
                    
                }
               
            }
        }
        
        if (!empty($res))
            return 1;
        else
            return 0;
    }
    public function get_image($id)
    {
        $product_image=product_product::where('id',$id)->first();
        return view ('pages/product/product-image',compact('product_image'));
    }
}