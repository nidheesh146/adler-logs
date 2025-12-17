<?php
namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\fgs_item_master;
use App\Models\batchcard;
use App\Models\PurchaseDetails\product_price_master;
use App\Models\PurchaseDetails\product_productgroup;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Validator;
use Illuminate\Support\Facades\DB; // This line is correct for importing DB

use App\Models\PurchaseDetails\inventory_rawmaterial;

use App\Exports\PriceMasterExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel; 

// Remove this line:
// use Illuminate\Support\Facades\DB as FacadesDB;

class PriceController extends Controller
{ 
    public function __construct()
    {
         $this->product = new product;
         $this->fgs_item_master = new fgs_item_master;
         $this->product_price_master = new product_price_master;
         $this->product_productgroup = new product_productgroup;
    }

    public function priceList(Request $request)
    {
        ///$this->priceMasterUpload();
    //$this->productFgsUpload();
       //$this->fgsStockUpload();
    //    $this->item_InputmaterialUpload();
    //  $this->fgsprdUpload();
        //  $this->fgsUploadquarentine();

        $condition =[];
        if($request->sku_code)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->hsn_code)
        {
            $condition[] = ['fgs_item_master.hsn_code','like', '%' . $request->hsn_code . '%'];
        }
        if($request->group_name)
        {
            $condition[] = ['product_group1.group_name','like', '%' . $request->group_name . '%'];
        }
        $price = DB::table('product_group1')->distinct('product_group1.group_name')->get();
        $prices = $this->product_price_master->get_all($condition);
        //dd($prices);
        /*foreach($prices as $price)
        {
            $t=product_price_master::find($price['id']);
            $t->with_effective_from = $price['created_at'];
            $t->save();
            //$update =  $this->product_price_master->update_data(['id'=>$price['id']],['with_effective_from'=>$price['created_at']]);
        }*/
        //exit;
        return view('pages/FGS/price-master/price-master-list',compact('prices','price'));
    }
    // public function productAdd(Request $request,$id=null)
    // {
    //     if ($request->isMethod('post'))
    //     {
    //         $validation['sku_code'] = ['required'];
    //         $validation['description'] = ['required'];
    //         $validation['hsn_code'] = ['required'];
    //         $validation['product_group'] = ['required'];
    //         $validation['product_brand'] = ['required'];
    //         $validation['pack_size'] = ['required'];
    //         $validator = Validator::make($request->all(), $validation);
    //         if(!$validator->errors()->all())
    //         {
    //             $data['sku_code'] = $request->sku_code; 
    //             $data['short_name'] = $request->short_name;
    //             $data['discription'] = $request->description;
    //             $data['hsn_code'] = $request->hsn_code;
    //             $data['gs1_code'] = $request->gs1_code;
    //             $data['product_category_id'] = $request->product_category;
    //             $data['product_group1_id'] = $request->product_group;
    //             $data['product_type_id'] = $request->product_type;
    //             // $data['product_oem_id'] = $request->product_oem;
    //             $data['brand_details_id'] = $request->product_brand;
    //             $data['minimum_stock'] = $request->min_level;
    //             $data['maximum_stock'] = $request->max_level;
    //             $data['is_sterile'] = $request->sterile_nonsterile;
    //             $data['quantity_per_pack'] = $request->pack_size;
    //             $data['item_type'] = 'FINISHED GOODS';
    //             $data['gst'] = $request->gst;
    //             $data['created_by_id']= config('user')['user_id'];
    //             $data['is_active']=1;
    //             $data['created'] =date('Y-m-d H:i:s');
    //             $data['updated'] =date('Y-m-d H:i:s');
    //             $data['status_type'] = $request->status_type;
    //             if($request->id)
    //             { 
                         
    //                     $data['updated'] = date('Y-m-d H:i:s');
    //                     $this->product->update_data(['id'=>$request->id],$data);
    //                     $request->session()->flash('success',"You have successfully updated a product !");
    //                     return redirect("fgs/product-master/list");
    //             }
    //             else
    //             {
    //                     $data['sku_code'] = $request->sku_code; 
    //                     $data['short_name'] = $request->short_name;
    //                     $data['discription'] = $request->description;
    //                     $data['hsn_code'] = $request->hsn_code;
    //                     $data['gs1_code'] = $request->gs1_code;
    //                     $data['product_group1_id'] = $request->product_group;
    //                     $data['product_type_id'] = $request->product_type;
    //                     // $data['product_oem_id'] = $request->product_oem;
    //                     $data['product_category_id'] = $request->product_category;
    //                     $data['brand_details_id'] = $request->product_brand;
    //                     $data['minimum_stock'] = $request->min_level;
    //                     $data['maximum_stock'] = $request->max_level;
    //                     $data['is_sterile'] = $request->sterile_nonsterile;
    //                     $data['quantity_per_pack'] = $request->pack_size;
    //                     $data['item_type'] = 'FINISHED GOODS';
    //                     $data['created_by_id']= config('user')['user_id'];
    //                     $data['is_active']=1;
    //                     $data['created'] =date('Y-m-d H:i:s');
    //                     $data['updated'] =date('Y-m-d H:i:s');
    //                     $data['status_type'] = $request->status_type;
    //                     $add = $this->product->insert_data($data);
              
    //                     $request->session()->flash('success', "You have successfully added a product !");
    //                     return redirect('fgs/product-master/list');
               
    //             }
    //         }
    //         if($validator->errors()->all()) 
    //         { 
    //                 if($request->id)
    //                 return redirect("fgs/product-master/add/".$id)->withErrors($validator)->withInput();
    //                 else
    //                 return redirect("fgs/product-master/add")->withErrors($validator)->withInput();
    //         }
    //     }
    //     else
    //     {
    //         if($request->id)
    //         {
    //              $datas = $this->product->get_single_product(['fgs_item_master.id'=>$request->id]);
    //              $data['product_oem'] = DB::table('product_oem')->get();
    //             $data['product_type'] = DB::table('product_type')->get();
    //             $data['product_group1'] = DB::table('product_group1')->get();
    //             $data['product_productbrand'] = DB::table('product_productbrand')->get();
    //             $data['product_productfamily']= DB::table('product_productfamily')->where('is_active','=',1)->get();
    //             $data['product_productgroup'] = DB::table('product_productgroup')->get();
    //             $data['product_category'] = DB::table('fgs_product_category')->get();
    //             return view('pages/FGS/product-master/product-add', compact('data','datas'));
    //         }
        
    //         else
    //         {
    //             $datas = [];
    //             // $data['category'] = fgs_product_category::get();
    //             $data['product_oem'] = DB::table('product_oem')->get();
    //             $data['product_type'] = DB::table('product_type')->get();
    //             $data['product_group1'] = DB::table('product_group1')->get();
    //             $data['product_productbrand'] = DB::table('product_productbrand')->get();
    //             $data['product_productfamily']= DB::table('product_productfamily')->where('is_active','=',1)->get();
    //             $data['product_productgroup'] = DB::table('product_productgroup')->get();
    //             $data['product_category'] = DB::table('fgs_product_category')->get();
    //             return view('pages/FGS/product-master/product-add', compact('data','datas'));
    //         }
    //     }
    // }
    public function priceAdd(Request $request, $id = null)
{
    if ($request->isMethod('post')) 
    {
        // Validation rules
        $validation = [
            'product' => ['required'],
            'purchase_price' => ['required'],
            'sales_price' => ['required'],
            'transfer_price' => ['required'],
            'mrp' => ['required'],
            'with_effective_from' => ['required', 'date'],
            'with_effective_to' => ['required', 'date'],
            'status_type' => ['required']
        ];

        // Apply validation rules
        $validator = Validator::make($request->all(), $validation);

        if (!$validator->fails()) 
        {
            DB::beginTransaction();

            try {
                // Check if product_id exists safely
                $product_id = $request->input('product');
                $existingData = $this->product_price_master->get_single_product_price(['product_id' => $product_id]);

                if ($existingData) {
                    // If product_id exists, update the existing record
                    DB::table('product_price_master')
                        ->where('product_id', $product_id)
                        ->update([
                            'old_purchase' => $existingData->purchase,
                            'old_sales' => $existingData->sales,
                            'old_transfer' => $existingData->transfer,
                            'old_mrp' => $existingData->mrp,
                            'purchase' => $request->purchase_price,
                            'sales' => $request->sales_price,
                            'transfer' => $request->transfer_price,
                            'mrp' => $request->mrp,
                            'status_type' => $request->status_type,
                            'with_effective_from' => date('Y-m-d', strtotime($request->with_effective_from)),
                            'with_effective_to' => date('Y-m-d', strtotime($request->with_effective_to)),
                            'updated_at' => now(),
                            'updated_by' => config('user')['user_id'],
                        ]);

                    DB::commit();
                    $request->session()->flash('success', "Product price updated successfully!");
                    return redirect("fgs/price-master/list/" . $id);
                } 
                else 
                {
                    // Insert new product price record
                    $data = [
                        'product_id' => $product_id,
                        'purchase' => $request->purchase_price,
                        'sales' => $request->sales_price,
                        'transfer' => $request->transfer_price,
                        'mrp' => $request->mrp,
                        'status_type' => $request->status_type,
                        'with_effective_from' => date('Y-m-d', strtotime($request->with_effective_from)),
                        'with_effective_to' => date('Y-m-d', strtotime($request->with_effective_to)),
                        'created_at' => now(),
                        'updated_at' => now(),
                        'created_by' => config('user')['user_id'],
                    ];

                    $this->product_price_master->insert_data($data);
                    DB::commit();

                    $request->session()->flash('success', "New price master added successfully!");
                    return redirect("fgs/price-master/list");
                }
            } 
            catch (\Exception $e) 
            {
                DB::rollBack();
                $request->session()->flash('error', "An error occurred: " . $e->getMessage());
                return redirect("fgs/price-master/add/" . $id);
            }
        }

        return redirect()->back()->withErrors($validator)->withInput();
    } 
    else 
    {
        return view('pages/FGS/price-master/price-master-add');
    }
}

    
public function priceEdit(Request $request, $id)
{
    if ($request->isMethod('post')) 
    {
        // Validation rules
        $validation = [
            'purchase_price' => ['required'],
            'sales_price' => ['required'],
            'transfer_price' => ['required'],
            'mrp' => ['required'],
            'with_effective_from' => ['required', 'date'],
            'with_effective_to' => ['required', 'date'],
            'status_type' => ['required']
        ];

        // Apply validation rules
        $validator = Validator::make($request->all(), $validation);

        if (!$validator->fails()) 
        {
            DB::beginTransaction();

            try {
                // Fetch the existing record by ID
                $existingData = $this->product_price_master->get_single_product_price(['id' => $id]);

                if ($existingData) {
                    // Update the existing record and store old prices
                    DB::table('product_price_master')
                        ->where('id', $id)
                        ->update([
                            'old_purchase' => $existingData->purchase,
                            'old_sales' => $existingData->sales,
                            'old_transfer' => $existingData->transfer,
                            'old_mrp' => $existingData->mrp,
                            'purchase' => $request->purchase_price,
                            'sales' => $request->sales_price,
                            'transfer' => $request->transfer_price,
                            'mrp' => $request->mrp,
                            'status_type' => $request->status_type,
                            'with_effective_from' => date('Y-m-d', strtotime($request->with_effective_from)),
                            'with_effective_to' => date('Y-m-d', strtotime($request->with_effective_to)),
                            'updated_at' => now(),
                            'updated_by' => config('user')['user_id'],
                        ]);

                    DB::commit();
                    $request->session()->flash('success', "Product price updated successfully!");
                    return redirect("fgs/price-master/edit/" . $id);
                } 
                else 
                {
                    $request->session()->flash('error', "Product not found!");
                    return redirect()->back()->withInput();
                }
            } 
            catch (\Exception $e) 
            {
                DB::rollBack();
                $request->session()->flash('error', "An error occurred: " . $e->getMessage());
                return redirect("fgs/price-master/edit/" . $id);
            }
        }

        return redirect()->back()->withErrors($validator)->withInput();
    } 
    else 
    {
        $priceData = $this->product_price_master->get_single_product_price(['id' => $id]);
        
        if (!$priceData) {
            return redirect('fgs/price-master/list')->with('error', 'Product not found.');
        }

        return view('pages/FGS/price-master/price-master-edit', compact('priceData'));
    }
}

    
    public function productsearch(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Product is not valid'], 500); 
        }
      
            $data =  $this->fgs_item_master->get_product_info(strtoupper($request->q));   
        
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
          $data= DB::table('fgs_item_master')
           ->select(['fgs_item_master.id','fgs_item_master.sku_code as text','fgs_item_master.discription','product_productgroup.group_name','fgs_item_master.hsn_code',
           'fgs_item_master.is_sterile','fgs_item_master.process_sheet_no','inv_stock_management.stock_qty as qty'])
                           ->leftjoin('product_productgroup','product_productgroup.id','=','fgs_item_master.product_group_id')
                           ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.item_code','=','fgs_item_master.sku_code')
                           ->leftjoin('inv_stock_management','inv_stock_management.item_id','=','inventory_rawmaterial.id')
                           ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.Item_code','=','inventory_rawmaterial.id')
                           ->leftjoin('inv_mac_item','inv_mac_item.pr_item_id','=','inv_purchase_req_item.requisition_item_id')
                           ->leftjoin('fgs_transfer','fgs_transfer.pr_item_id','=','inv_mac_item.pr_item_id')
                           ->where('fgs_item_master.sku_code','like','%'.$request->q.'%')
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
        if ($file) {
            $ExcelOBJ = new \stdClass();
            $path = storage_path() . '/app/' . $request->file('file')->store('temp');
            $ExcelOBJ->inputFileName = $path;
            $ExcelOBJ->inputFileType = 'Xlsx';
            $ExcelOBJ->spreadsheet = new Spreadsheet();
            $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
            $ExcelOBJ->reader->setReadDataOnly(true);
            $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
            $no_column = 7;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
            if ($sheet1_column_count == $no_column) {
                $reslt = $this->priceMasterExcelsplitsheet($ExcelOBJ);
                //  print_r($reslt);exit;
                if ($reslt) {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('fgs/price-master/upload-excel');
                } else {
                    $request->session()->flash('error',  "The data already uploaded or no item found in item master");
                    return redirect('fgs/price-master/upload-excel');
                }
            } else {
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
           $add= $this->insert_price_master($ExcelOBJ);
            //die("done");
        }
        //exit('done');
        if ($add)
            return 1;
        else
            return 0;
    }
    function insert_price_master($ExcelOBJ)
{
    $errors = [];
    $res = [];
    $finalData = [];  // Store last occurrence of each product_id
    $missingProducts = [];  // Track missing SKU codes

    // Iterate through the Excel data in reverse to keep the last occurrence
    for ($i = count($ExcelOBJ->excelworksheet) - 1; $i > 0; $i--) {
        $excelsheet = $ExcelOBJ->excelworksheet[$i];

        if (empty($excelsheet[0])) {
            continue; // Skip empty rows
        }

        $product_id = fgs_item_master::where('sku_code', '=', $excelsheet[0])->pluck('id')->first();

        if (!$product_id) {
            $missingProducts[] = $excelsheet[0]; // Collect missing SKU codes
            continue; // Skip row if product_id not found
        }

        // Store the last occurrence and avoid duplicate processing
        if (!isset($finalData[$product_id])) {
            $finalData[$product_id] = $excelsheet;
        }
    }

    foreach ($finalData as $product_id => $excelsheet) {
        $price_master = product_price_master::where('product_id', '=', $product_id)->first();

        if ($price_master) {
            $existingData = $price_master;
            $data['old_purchase'] = $existingData->purchase;
            $data['old_sales'] = $existingData->sales;
            $data['old_transfer'] = $existingData->transfer;
            $data['old_mrp'] = $existingData->mrp;
        }

        if (!empty($excelsheet[2])) $data['purchase'] = $excelsheet[2];
        if (!empty($excelsheet[3])) $data['sales'] = $excelsheet[3];
        if (!empty($excelsheet[4])) $data['mrp'] = $excelsheet[4];
        if (!empty($excelsheet[5])) $data['transfer'] = $excelsheet[5];
        if (!empty($excelsheet[6])) {
            $data['with_effective_from'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelsheet[6])->format('Y-m-d');
        }
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($price_master) {
            $res[] = DB::table('product_price_master')->where('id', '=', $price_master->id)->update($data);
        } else {
            $data['product_id'] = $product_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $res[] = DB::table('product_price_master')->insert($data);
        }
    }

    // Display error message if any SKU codes were not found
    if (!empty($missingProducts)) {
        $errors[] = 'The following SKU codes were not found in item master: ' . implode(', ', $missingProducts);
    }

    if (!empty($errors)) {
        session()->flash('error', implode('<br>', $errors));
    }

    return !empty($res) ? 1 : 0;
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
    public function fgsprdUpload()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName = 'C:\xampp\htdocs\prd_upload.xlsx';
        $ExcelOBJ->aircraft = 'B737-MAX';
        $ExcelOBJ->spreadsheet = new Spreadsheet();
        $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
        $ExcelOBJ->reader->setReadDataOnly(true);
        $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
        $this->fgsprdExcelsplitsheet($ExcelOBJ);
        exit;
    }
    public function fgsprdExcelsplitsheet($ExcelOBJ)
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
            $this->insert_fgs_prd($ExcelOBJ);
            //die("done");
        }
        exit('done');
    }
    function insert_fgs_prd($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {

            if ($key > 0 &&  $excelsheet[0]) {
                //echo $excelsheet[17];exit;
                $product_id = product::where('sku_code', '=', $excelsheet[0])->pluck('id')->first();
                if ($product_id) {
                    $res[] = product::where('sku_code', '=', $excelsheet[0])
                        ->update([
                            'is_sterile' => 0
                        ]);
                    //  $batchcard=batchcard::where('batch_no','=',$excelsheet[2])->first();
                    // if ($product_id) {
                    //     // $data['product_id'] = $product_id;
                    //     // $data['batchcard_id'] = $batchcard['id'];
                    //     $data['is_sterile'] = 1;
                    //     // $data['is_sterile_expiry_date'] = date('Y-m-d', strtotime($excelsheet[11]));
                    //     $excelDateSerial = intval($excelsheet[11]);
                    //     $unixTimestamp = ($excelDateSerial - 25569) * 86400; // Adjust for Excel's epoch (January 1, 1900)
                    //     $dateString = date('Y-m-d', $unixTimestamp);
                    //     $data['is_sterile_expiry_date'] = $dateString;

                    //     // print_r($dateString);
                    //     //  dd($dateString);
                    //     $res[] = product::where('sku_code', '=', $excelsheet[0])
                    //         ->update($data);
                } else {
                    $not_exist[] = $excelsheet[0];
                    $res[] = 1;
                }
            if ($res) {
                // print_r($not_exist);
                return 1;
            } else
                return 0;
            }
        }
    }
    public function fgsUploadquarentine()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName = 'C:\xampp\htdocs\quarentine.xlsx';
        $ExcelOBJ->aircraft = 'B737-MAX';
        $ExcelOBJ->spreadsheet = new Spreadsheet();
        $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
        $ExcelOBJ->reader->setReadDataOnly(true);
        $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
        $this->fgsprdExcelsplitsheetmiq($ExcelOBJ);
        exit;
    }
    public function fgsprdExcelsplitsheetmiq($ExcelOBJ)
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
            $this->insert_miq_prd($ExcelOBJ);
            //die("done");
        }
        exit('done');
    }
    function insert_miq_prd($ExcelOBJ)
    {
        // dd($ExcelOBJ->excelworksheet);
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {

            if ($key > 0 &&  $excelsheet[1]) {
                //echo $excelsheet[17];exit;
                //  dd($excelsheet[5]);
                $miq = DB::table('inv_miq_item')
                ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
                ->leftjoin('inv_miq','inv_miq_item_rel.master','=','inv_miq.id')
                ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_miq_item.item_id')
                ->leftjoin('inventory_rawmaterial','inv_purchase_req_item.Item_code','=','inventory_rawmaterial.id')
                ->where('inv_miq.miq_number', '=', $excelsheet[1])
                 ->where('inventory_rawmaterial.item_code', '=', $excelsheet[5])
                 ->pluck('inv_miq_item.id')
                ->first();
                // dd($miq);
                if ($miq) {
                    $res[] = DB::table('inv_miq_item')
                        ->where('id', '=', $miq)
                        ->update([
                            'status' => 0
                        ]);
                }
                
            }
                // if ($res) {
                    //  print_r($not_exist);
                    // return 1;
                // } else
                //     return 0;
            
        }
    }
    public function priceDelete($id)
    {
        product_price_master::where('id', $id)
            ->update([
                'is_active' => 0
            ]);
        session()->flash('success', "You have  deleted Price  !");
            return redirect()->back();
    }
}