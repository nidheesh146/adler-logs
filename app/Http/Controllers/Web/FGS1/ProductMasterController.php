<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use Validator;
use DB;
use App\Exports\FGSProductExport;
use Maatwebsite\Excel\Facades\Excel; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class ProductMasterController extends Controller
{
    public function __construct()
    {
         $this->product = new product;
    }
    public function productList(Request $request)
    {   
        //$condition[] = ['product_product.item_type','=','FINISHED GOODS'];
       //$condition[] = ['product_product.product_group1_id','!=','null'];
        //$condition = [];
        if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->hsn_code)
        {
            $condition[] = ['product_product.hsn_code','like', '%' . $request->hsn_code . '%'];
        }
        if($request->group)
        {
            $condition[] = ['product_group.group_name','like', '%' . $request->group . '%'];
        }
        if($request->brand)
        {
            $condition[] = ['product_productbrand.brand_name','like', '%' . $request->brand . '%'];
        }

        $condition[] = ['product_product.product_group1_id','!=','null'];
        $data['products'] = $this->product->get_products($condition);
        return view('pages/FGS/product-master/product-list',compact('data'));
    }
    public function productAdd(Request $request,$id=null)
    {
        if ($request->isMethod('post'))
        {
            $validation['sku_code'] = ['required'];
            $validation['description'] = ['required'];
            $validation['hsn_code'] = ['required'];
            $validation['product_group'] = ['required'];
            $validation['product_brand'] = ['required'];
            $validation['pack_size'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $data['sku_code'] = $request->sku_code; 
                $data['short_name'] = $request->short_name;
                $data['discription'] = $request->description;
                $data['hsn_code'] = $request->hsn_code;
                $data['gs1_code'] = $request->gs1_code;
                $data['product_category_id'] = $request->product_category;
                $data['product_group1_id'] = $request->product_group;
                $data['product_type_id'] = $request->product_type;
                $data['product_oem_id'] = $request->product_oem;
                $data['brand_details_id'] = $request->product_brand;
                $data['minimum_stock'] = $request->min_level;
                $data['maximum_stock'] = $request->max_level;
                $data['is_sterile'] = $request->sterile_nonsterile;
                $data['quantity_per_pack'] = $request->pack_size;
                $data['item_type'] = 'FINISHED GOODS';
                $data['gst'] = $request->gst;
                $data['created_by_id']= config('user')['user_id'];
                $data['is_active']=1;
                $data['created'] =date('Y-m-d H:i:s');
                $data['updated'] =date('Y-m-d H:i:s');
                $data['status_type'] = $request->status_type;
                if($request->id)
                { 
                         
                        $data['updated'] = date('Y-m-d H:i:s');
                        $this->product->update_data(['id'=>$request->id],$data);
                        $request->session()->flash('success',"You have successfully updated a product !");
                        return redirect("fgs/product-master/list");
                    
                }
                else
                {
                        $data['sku_code'] = $request->sku_code; 
                        $data['short_name'] = $request->short_name;
                        $data['discription'] = $request->description;
                        $data['hsn_code'] = $request->hsn_code;
                        $data['gs1_code'] = $request->gs1_code;
                        $data['product_group1_id'] = $request->product_group;
                        $data['product_type_id'] = $request->product_type;
                        $data['product_oem_id'] = $request->product_oem;
                        $data['product_category_id'] = $request->product_category;
                        $data['brand_details_id'] = $request->product_brand;
                        $data['minimum_stock'] = $request->min_level;
                        $data['maximum_stock'] = $request->max_level;
                        $data['is_sterile'] = $request->sterile_nonsterile;
                        $data['quantity_per_pack'] = $request->pack_size;
                        $data['item_type'] = 'FINISHED GOODS';
                        $data['created_by_id']= config('user')['user_id'];
                        $data['is_active']=1;
                        $data['created'] =date('Y-m-d H:i:s');
                        $data['updated'] =date('Y-m-d H:i:s');
                        $data['status_type'] = $request->status_type;
                        $add = $this->product->insert_data($data);
              
                        $request->session()->flash('success', "You have successfully added a product !");
                        return redirect('fgs/product-master/list');
               
                }
            }
            if($validator->errors()->all()) 
            { 
                    if($request->id)
                    return redirect("fgs/product-master/add/".$id)->withErrors($validator)->withInput();
                    else
                    return redirect("fgs/product-master/add")->withErrors($validator)->withInput();
            }
        }
        else
        {
            if($request->id)
            {
                 $datas = $this->product->get_single_product(['product_product.id'=>$request->id]);
                 $data['product_oem'] = DB::table('product_oem')->get();
                $data['product_type'] = DB::table('product_type')->get();
                $data['product_group1'] = DB::table('product_group1')->get();
                $data['product_productbrand'] = DB::table('product_productbrand')->get();
                $data['product_productfamily']= DB::table('product_productfamily')->where('is_active','=',1)->get();
                $data['product_productgroup'] = DB::table('product_productgroup')->get();
                $data['product_category'] = DB::table('fgs_product_category')->get();
                return view('pages/FGS/product-master/product-add', compact('data','datas'));
            }
        
            else
            {
                $datas = [];
                // $data['category'] = fgs_product_category::get();
                $data['product_oem'] = DB::table('product_oem')->get();
                $data['product_type'] = DB::table('product_type')->get();
                $data['product_group1'] = DB::table('product_group1')->get();
                $data['product_productbrand'] = DB::table('product_productbrand')->get();
                $data['product_productfamily']= DB::table('product_productfamily')->where('is_active','=',1)->get();
                $data['product_productgroup'] = DB::table('product_productgroup')->get();
                $data['product_category'] = DB::table('fgs_product_category')->get();
                return view('pages/FGS/product-master/product-add', compact('data','datas'));
            }
        }
    }
    public function ProductExport(Request $request)
    {
        $condition[] = ['product_product.item_type','=','FINISHED GOODS'];
        $condition[] = ['product_product.product_group1_id','!=','null'];
        
        if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->hsn_code)
        {
            $condition[] = ['product_product.hsn_code','like', '%' . $request->hsn_code . '%'];
        }
        if($request->group)
        {
            $condition[] = ['product_group.group_name','like', '%' . $request->group . '%'];
        }
        if($request->brand)
        {
            $condition[] = ['product_productbrand.brand_name','like', '%' . $request->brand . '%'];
        }

        $condition[] = ['product_product.product_group1_id','!=','null'];
        $products = $this->product->get_all_products($condition);
        return Excel::download(new FGSProductExport($products), 'FGSItemMaster' . date('d-m-Y') . '.xlsx');
    }
    public function product_upload()

    {
        return view('pages/FGS/product-master/product-upload');

    }
    public function productFgsUpload(Request $request)
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
            $no_column = 12;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
            if($sheet1_column_count == $no_column)
            {
                $reslt = $this->productFgsExcelsplitsheet($ExcelOBJ);
                    //print_r($res);exit;
                if($reslt)
                {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('fgs/product-master/list');
                }
                else{
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect('fgs/product-master/list');
                }
            }
            else 
            {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect('fgs/product-master/list');
            }
        }
        // $this->productFgsExcelsplitsheet($ExcelOBJ);
        // exit;
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
        return 1;
    }
    function insert_fgs_product_master($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
            $condition[] = ['product_product.product_group1_id','!=','null'];
    
            if($key > 0 &&  $excelsheet[1])
             {
                //echo $excelsheet[17];exit;
                $not_exist =[];
                $product_id = product::where('sku_code','=',$excelsheet[0])->pluck('id')->first();
                if($product_id)
                {
                    $dat['hsn_code'] = $excelsheet[4];
                    $dat['gst'] = $excelsheet[11];
                    $dat['product_type_id'] = $this->identify_id($excelsheet[3],"PRODUCT TYPE");
                    $dat['product_oem_id'] = $this->identify_id($excelsheet[8],"PRODUCT OEM");
                    $dat['product_group1_id'] = $this->identify_id($excelsheet[7],"PRODUCT GROUP1");
                    $dat['product_category_id'] = $this->identify_id($excelsheet[6],"PRODUCT CATEGORY");
                    $dat['quantity_per_pack'] = $excelsheet[9];                    
                    $res[]=DB::table('product_product')->where('id','=',$product_id)->update($dat);
                }
                else
                {
                    // $not_exist[] = $excelsheet[0];
                    // $res[] = 1;
                    $data['sku_code'] = $excelsheet[0];
                    $data['discription'] = $excelsheet[1];
                    $data['hsn_code'] = $excelsheet[4];
                    $data['product_type_id'] = $this->identify_id($excelsheet[3],"PRODUCT TYPE");
                    $data['product_oem_id'] = $this->identify_id($excelsheet[8],"PRODUCT OEM");
                    $data['product_group1_id'] = $this->identify_id($excelsheet[7],"PRODUCT GROUP1");
                    $data['product_category_id'] = $this->identify_id($excelsheet[6],"PRODUCT CATEGORY");
                    $data['quantity_per_pack'] = $excelsheet[9];
                    if( $excelsheet[5] =='Sterile')
                    $data['is_sterile'] = 1;
                    else
                    $data['is_sterile'] = 0;
                    $data['created'] = date('Y-m-d H:i:s');
                    $data['updated'] = date('Y-m-d H:i:s');
                    $res[]=DB::table('product_product')->insert($data);
                }
                
            }
        }
        if($res)
        {
            //print_r($not_exist);
        return 1;
        }
        else
        return 0;   
    }
    function identify_id($data,$type)
    {
        if($type=='PRODUCT TYPE'){
            // if($data=='Implant')
            //     return 1;
            // else
            //     return 2;
            $product_type =  DB::table('product_type')->where('product_type_name',$data)->first();
            if($product_type)
            {
                return $product_type->id;
            }
            else
            {
                return redirect('fgs/product-master/list')->with('error', 'The product type  "'.$data.'"  not exist on Database.So add this product type, then try to upload.');;
            }
        }
        if($type=='PRODUCT OEM'){
            // if($data=='Trade Link')
            //     return 1;
            // else
            //     return 2;
           $oem = DB::table('product_oem')->where('oem_name',$data)->first();
           if($oem)
           {
               return $oem->id;
           }
           else
           {
               return redirect('fgs/product-master/list')->with('error', 'The product OEM  "'.$data.'"  not exist on Database.So add this product OEM, then try to upload.');;
           }
        }
        if($type=='PRODUCT GROUP1'){
        // return   DB::table('product_group1')->where('group_name',$data)->first()->id;
            $grp = DB::table('product_group1')->where('group_name',$data)->first();
            if($grp)
            {
                return $grp->id;
            }
            else
            {
                return redirect('fgs/product-master/list')->with('error', 'The product group1  "'.$data.'"  not exist on Database.So add this product group1, then try to upload.');;
            }
        }
    
        if($type=='PRODUCT CATEGORY')
        {
            $category = DB::table('fgs_product_category')->where('category_name',$data)->first();
            if($category)
            {
                return $category->id;
            }
            else
            {
                return redirect('fgs/product-master/list')->with('error', 'The product category  "'.$data.'"  not exist on Database.So add this product category, then try to upload.');;
            }
            // if($data=='OBM')
            // return 1;
            // else if($data=='OEM')
            // return 2;
            // else if($data=='TRADE')
            // return 3;
        }
        //exit;
       
    }
}

