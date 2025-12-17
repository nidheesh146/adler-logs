<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use Validator;
use DB;
use App\Models\fgs_item_master;
use App\Exports\FGSProductExport;
use Maatwebsite\Excel\Facades\Excel; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class ProductMasterController extends Controller
{
    public function __construct()
    {
         $this->product = new product;
         $this->fgs_item_master = new fgs_item_master;

    }
    public function productList(Request $request)
    {   
        //dd('itemlist');
        // $condition[] = ['fgs_item_master.item_type','!=','SEMIFINISHED GOODS'];
        $condition[] = ['fgs_item_master.product_group1_id','!=','null'];
        $condition = [];
        if($request->sku_code)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->hsn_code)
        {
            $condition[] = ['fgs_item_master.hsn_code','like', '%' . $request->hsn_code . '%'];
        }
        if($request->group)
        {
            $condition[] = ['product_group1.group_name','like', '%' . $request->group . '%'];
        }
        if($request->brand)
        {
            $condition[] = ['product_productbrand.brand_name','like', '%' . $request->brand . '%'];
        }

        $condition[] = ['fgs_item_master.product_category_id','!=',null];
        $data['products'] = $this->fgs_item_master->get_products($condition);
    //    print_r($data);exit;
        return view('pages/FGS/product-master/product-list',compact('data'));
    }
    public function productAdd(Request $request, $id = null)
    {
      //  dd('item');
        if ($request->isMethod('post')) {
            $validation = [
                'sku_code' => ['required'],
                'description' => ['required'],
                'hsn_code' => ['required'],
                'product_group' => ['required'],
                'product_brand' => ['required'],
                'pack_size' => ['required']
            ];
            $validator = Validator::make($request->all(), $validation);
    
            if (!$validator->fails()) {
                // Check if SKU exists in product_product table
                $product = DB::table('product_product')->where('sku_code', $request->sku_code)->first();
    
                // Prepare data for insert or update
                $data = [
                    'sku_code' => $request->sku_code,
                    'short_name' => $request->short_name,
                    'discription' => $request->description,
                    'hsn_code' => $request->hsn_code,
                    'gs1_code' => $request->gs1_code,
                    'product_category_id' => $request->product_category,
                    'new_product_category_id' => $request->new_product_category,
                    'product_group1_id' => $request->product_group,
                    'product_type_id' => $request->product_type,
                    'brand_details_id' => $request->product_brand,
                    'minimum_stock' => $request->min_level,
                    'maximum_stock' => $request->max_level,
                    'is_sterile' => $request->sterile_nonsterile,
                    'quantity_per_pack' => $request->pack_size,
                    'item_type' => 'FINISHED GOODS',
                    'gst' => $request->gst,
                    'created_by_id' => config('user')['user_id'],
                    'is_active' => 1,
                    'created' => now(),
                    'updated' => now(),
                    'status_type' => $request->status_type,
                   // 'product_item_id' => $product ? $product->id : null // ✅ this is what links to product_product
                ];
                
    
                // Check if SKU already exists in fgs_item_master
                $existingProduct = $this->fgs_item_master->where('sku_code', $request->sku_code)->first();
    
                if ($existingProduct) {
                  //  dd('98');
                    // Update the existing record
                    $this->fgs_item_master->update_data(['id' => $existingProduct->id], $data);
                    $request->session()->flash('success', "You have successfully updated the product with SKU code {$request->sku_code}!");
                } else {
                    // Insert new record with same ID if SKU exists in product_product
                    if ($product) {
                        $data['id'] = $product->id;
                    }
                    //dd($data);
                    $this->fgs_item_master->insert_data($data);
                    $request->session()->flash('success', "You have successfully added a product with SKU code {$request->sku_code}!");
                }
    
                return redirect('fgs/product-master/list');
            }
    
            // Handle validation errors
            return redirect()->route('fgs/product-master/list', ['id' => $id])
                             ->withErrors($validator)
                             ->withInput();
        } else {
            // Handle GET request
            if ($request->id) {
                $datas = $this->fgs_item_master->get_single_product(['fgs_item_master.id' => $request->id]);
            } else {
                $datas = [];
            }
            $data = $this->getProductData();
            return view('pages/FGS/product-master/product-add', compact('data', 'datas'));
        }
    }
    
    
    private function getProductData()
    {
        return [
            'product_oem' => DB::table('product_oem')->get(),
            'product_type' => DB::table('product_type')->get(),
            'product_group1' => DB::table('product_group1')->get(),
            'product_productbrand' => DB::table('product_productbrand')->get(),
            'product_productfamily' => DB::table('product_productfamily')->where('is_active', '=', 1)->get(),
            'product_productgroup' => DB::table('product_productgroup')->get(),
            'product_category' => DB::table('fgs_product_category')->get(),
            'new_product_category' => DB::table('fgs_product_category_new')->get(),
        ];
    }
    public function ProductExport(Request $request)
    {
        // $condition[] = ['fgs_item_master.item_type','=','FINISHED GOODS'];
        // $condition[] = ['fgs_item_master.item_type','=','SEMIFINISHED GOODS'];
        //$condition[] = ['fgs_item_master.product_group1_id','!=','null'];
        if($request->sku_code)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->hsn_code)
        {
            $condition[] = ['fgs_item_master.hsn_code','like', '%' . $request->hsn_code . '%'];
        }
        if($request->group)
        {
            $condition[] = ['product_group.group_name','like', '%' . $request->group . '%'];
        }
        if($request->brand)
        {
            $condition[] = ['product_productbrand.brand_name','like', '%' . $request->brand . '%'];
        }
{//$condition[] = ['fgs_item_master.product_group1_id','!=','null'];
$condition[]= ['fgs_item_master.product_category_id','!=','null'];}
       // $condition = [];
        $products = $this->fgs_item_master->get_all_products($condition);//get_all_products function in fgs_item_master model is supposed to come here,but temporariry we are adding the same function(get_products) as productlist
        //dd($products);
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
            $no_column = 14;
    
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            
            if ($sheet1_column_count == $no_column) {
                $reslt = $this->productFgsExcelsplitsheet($ExcelOBJ);
                
                if ($reslt) {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('fgs/product-master/list');
                } else {
                    $request->session()->flash('error',  "The data has already been uploaded.");
                    return redirect('fgs/product-master/list');
                }
            } else {
                $request->session()->flash('error',  "Column count does not match. Please download the Excel template and check the column count.");
                return redirect('fgs/product-master/list');
            }
        }
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
        }
        return 1;
    }
    
    function insert_fgs_product_master($ExcelOBJ)
{
    foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
        if ($key > 0 && isset($excelsheet[0]) && !empty($excelsheet[0])) { // Ensure SKU code exists and is not empty
            // Fetch the product ID using the SKU code
            $product_id = DB::table('fgs_item_master')->where('sku_code', $excelsheet[0])->pluck('id')->first();

            if ($product_id) {
                // Update existing product
                $dat = [
                    'hsn_code' => $excelsheet[4] ?? null,
                    'gst' => $excelsheet[12] ?? null,
                    'item_type' => $excelsheet[13] ?? null,
                    'product_type_id' => $this->identify_id($excelsheet[3] ?? null, "PRODUCT TYPE"),
                    'product_oem_id' => $this->identify_id($excelsheet[9] ?? null, "PRODUCT OEM"),
                    'product_group1_id' => $this->identify_id($excelsheet[8] ?? null, "PRODUCT GROUP1"),
                    'product_category_id' => $this->identify_id($excelsheet[6] ?? null, "PRODUCT CATEGORY"),
                    'new_product_category_id' => $this->getNewProductCategoryId($excelsheet[7] ?? null),
                    'quantity_per_pack' => $excelsheet[10] ?? null,
                    'updated' => now(), // Ensure updated timestamp is modified
                ];

                DB::table('fgs_item_master')->where('id', $product_id)->update($dat);
            } else {
                // Insert new product
                $data = [
                    'sku_code' => $excelsheet[0],
                    'discription' => $excelsheet[1] ?? null,
                    'hsn_code' => $excelsheet[4] ?? null,
                    'gst' => $excelsheet[12] ?? null,
                    'item_type' => $excelsheet[13] ?? null,
                    'product_type_id' => $this->identify_id($excelsheet[3] ?? null, "PRODUCT TYPE"),
                    'product_oem_id' => $this->identify_id($excelsheet[9] ?? null, "PRODUCT OEM"),
                    'product_group1_id' => $this->identify_id($excelsheet[8] ?? null, "PRODUCT GROUP1"),
                    'product_category_id' => $this->identify_id($excelsheet[6] ?? null, "PRODUCT CATEGORY"),
                    'new_product_category_id' => $this->getNewProductCategoryId($excelsheet[7] ?? null),
                    'quantity_per_pack' => $excelsheet[10] ?? null,
                    'is_sterile' => (isset($excelsheet[5]) && strtolower(trim($excelsheet[5])) === 'sterile') ? 1 : 0,
                    'created' => now(),
                    'updated' => now(),
                ];

                DB::table('fgs_item_master')->insert($data);
            }
        }
    }

    return true; // Return true if there was at least one insert or update
}

    
    private function getNewProductCategoryId($categoryName)
    {
        if ($categoryName === 'ASD') {
            return 1; // ID for ASD
        } elseif ($categoryName === 'AWM') {
            return 2; // ID for AWM
        }
        return null; // Or another default value if needed
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
            // else
            // {
            //     return redirect('fgs/product-master/list')->with('error', 'The product type  "'.$data.'"  not exist on Database.So add this product type, then try to upload.');
            // }
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
        //    else
        //    {
        //        return redirect('fgs/product-master/list')->with('error', 'The product OEM  "'.$data.'"  not exist on Database.So add this product OEM, then try to upload.');
        //    }
        }
        if($type=='PRODUCT GROUP1'){
        // return   DB::table('product_group1')->where('group_name',$data)->first()->id;
            $grp = DB::table('product_group1')->where('group_name',$data)->first();
            // print_r($grp);exit;
            if($grp)
            {
                
                return $grp->id;
            }
            // else
            // {
                
            //     return redirect('fgs/product-master/list')->with('error', 'The product group1  "'.$data.'"  not exist on Database.So add this product group1, then try to upload.');
            // }
        }
    
        if($type=='PRODUCT CATEGORY')
        {
            $category = DB::table('fgs_product_category')->where('category_name',$data)->first();
            if($category)
            {
                return $category->id;
            }
            // else
            // {
            //     return redirect('fgs/product-master/list')->with('error', 'The product category  "'.$data.'"  not exist on Database.So add this product category, then try to upload.');;
            // }
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

