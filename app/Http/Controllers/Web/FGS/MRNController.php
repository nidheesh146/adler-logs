<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\product;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_product_category_new;
use App\Models\FGS\fgs_mrn;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_mrn_item_rel;
use App\Models\batchcard;
use App\Models\fgs_item_master;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MRDExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Exports\FGSmrntransactionExport;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class MRNController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->fgs_mrn = new fgs_mrn;
        $this->fgs_mrn_item = new fgs_mrn_item;
        $this->fgs_mrn_item_rel = new fgs_mrn_item_rel;
        $this->product = new product;
        $this->fgs_item_master = new fgs_item_master;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->production_stock_management = new production_stock_management;
    }

    public function MRNList(request $request)
    {
        //$this->fgsMRNStockUpload();
        $condition = [];
        if ($request->mrn_no) {
            $condition[] = ['fgs_mrn.mrn_number', 'like', '%' . $request->mrn_no . '%'];
        }
        if ($request->supplier_doc_number) {
            $condition[] = ['fgs_mrn.supplier_doc_number', 'like', '%' . $request->supplier_doc_number . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_mrn.mrn_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_mrn.mrn_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }

        $mrn = fgs_mrn::select('fgs_mrn.*', 'fgs_product_category.category_name','fgs_product_category_new.category_name as new_category_name','product_stock_location.location_name')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_mrn.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', 'fgs_mrn.new_product_category')
            ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_mrn.stock_location')
            ->where($condition)
            ->where('fgs_mrn.status',1)
            ->orderBy('fgs_mrn.id', 'DESC')
            ->paginate(15);
        return view('pages/FGS/MRN/MRN-list', compact('mrn'));
    }

    public function MRNAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['supplier_doc_number'] = ['required'];
            $validation['supplier_doc_date'] = ['required', 'date'];
            $validation['mrn_date'] = ['required', 'date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
    
            if (!$validator->errors()->all()) {
                // Generate year combo
                if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
                    $years_combo = date('y', strtotime('-1 year')) . date('y');
                } else {
                    $years_combo = date('y') . date('y', strtotime('+1 year'));
                }
    
                // Fetch the latest MRN number for the current year combo
                $latestMRN = DB::table('fgs_mrn')
                    ->where('mrn_number', 'LIKE', 'MRN-' . $years_combo . '-%')
                    ->orderBy('mrn_number', 'desc')
                    ->value('mrn_number');
    
                // Generate new 4-digit running number
                if ($latestMRN) {
                    $lastNumber = (int) substr($latestMRN, -4);
                    $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '0001';
                }
    
                // Final MRN Number
                $data['mrn_number'] = "MRN-" . $years_combo . '-' . $newNumber;
    
                // Assign form values
                $data['mrn_date'] = date('Y-m-d', strtotime($request->mrn_date));
                $data['supplier_doc_number'] = $request->supplier_doc_number;
                $data['supplier_doc_date'] = date('Y-m-d', strtotime($request->supplier_doc_date));
                $data['product_category'] = $request->product_category;
                $data['new_product_category'] = $request->new_product_category;
                $data['stock_location'] = $request->stock_location;
                $data['supplier'] = $request->supplier;
                $data['created_by'] = config('user')['user_id'];
                $data['status'] = 1;
                $data['remarks'] = $request->remarks;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
    
                // Insert MRN
               // dd($data);
                $add = $this->fgs_mrn->insert_data($data);
                if ($add) {
                    $request->session()->flash('success', "You have successfully added a MRN !");
                    return redirect('fgs/MRN/item-list/' . $add);
                } else {
                    $request->session()->flash('error', "MRN insertion failed. Try again... !");
                    return redirect('fgs/MRN-add');
                }
            } else {
                return redirect('fgs/MRN-add')->withErrors($validator)->withInput();
            }
        } else {
            $locations = product_stock_location::where('status', 1)
                ->whereNotIn('id', [12, 13, 14])->get();
            $category = fgs_product_category::get();
            $product_category = fgs_product_category_new::get();
            return view('pages/FGS/MRN/MRN-add', compact('locations', 'category', 'product_category'));
        }
    }
    
    public function MRN_edit($id,Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $validation['supplier_doc_number'] = ['required'];
            $validation['supplier_doc_date'] = ['required', 'date'];
            $validation['mrn_date'] = ['required', 'date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) 
            {
                $data['mrn_date'] = date('Y-m-d', strtotime($request->mrn_date));
                $data['supplier_doc_number'] = $request->supplier_doc_number;
                $data['supplier_doc_date'] = date('Y-m-d', strtotime($request->supplier_doc_date));
                $data['product_category'] = $request->product_category;
                $data['new_product_category'] = $request->new_product_category;
                $data['stock_location'] = $request->stock_location;
                $data['supplier']=$request->supplier;
                //$data['created_by'] = config('user')['user_id'];
                //$data['status'] = 1;
                //$data['created_at'] = date('Y-m-d H:i:s');
                $data['remarks']=$request->remarks;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $update = $this->fgs_mrn->update_data(['fgs_mrn.id'=>$request->mrn_id],$data);
                if ($update) {
                    $request->session()->flash('success', "You have successfully updated a MRN !");
                    return redirect('fgs/MRN-list');
                } else {
                    $request->session()->flash('error', "MRN updation is failed. Try again... !");
                    return redirect('fgs/MRN-edit/'.$request->mrn_id);
                }

            }
            else 
            {
                return redirect('fgs/MRN-edit/'.$request->mrn_id)->withErrors($validator)->withInput();
            }
        }
        else
        {
            $mrn = fgs_mrn::find($id);
            $locations = product_stock_location::get();
            $category = fgs_product_category::get();
            $product_category = fgs_product_category_new::get();
            return view('pages/FGS/MRN/MRN-add', compact('locations', 'category','mrn','product_category'));
        }
    }
     public function MRN_delete($id)
    {
//dd('hi');
         fgs_mrn::where('id', $id)->delete();

        // fgs_mrn_item::leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
        //     ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
        //     ->where('fgs_mrn.id', $id)
        //     ->update([
        //         'fgs_mrn_item.status' => 0
        //     ]);
        session()->flash('success', "You have  deleted MRN  !");
        return redirect()->back();
    }
    public function MRNitemlist(Request $request, $mrn_id)
    {
        $product_cat=DB::table('fgs_mrn')
       ->where('id',$mrn_id)
       ->first();
        $condition = ['fgs_mrn_item_rel.master' => $request->mrn_id];
        if ($request->product) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->product . '%'];
        }
        if ($request->batchnumber) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batchnumber . '%'];
        }
        if ($request->manufaturing_from) {
            $condition[] = ['fgs_mrn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->manufaturing_from))];
            $condition[] = ['fgs_mrn_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->manufaturing_from))];
        }
        $items = $this->fgs_mrn_item->getMRNItems($condition);
        return view('pages/FGS/MRN/MRN-item-list', compact('mrn_id', 'items','product_cat'));
    }

    public function fetchMRNInfo(Request $request)
    {
        $mrn = fgs_mrn::select('fgs_mrn.*','fgs_product_category.category_name','product_stock_location.location_name','fgs_product_category_new.category_name as new_category_name')
                            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_mrn.product_category')
                            ->leftJoin('fgs_product_category_new','fgs_product_category_new.id','fgs_mrn.new_product_category')
                            ->leftJoin('product_stock_location','product_stock_location.id','fgs_mrn.stock_location')
                            ->where('fgs_mrn.id','=',$request->mrn_id)
                            ->first();
        return $mrn;
    }
    public function search(Request $request, $sku = null)
    {
        if (!$request->q) {
            return response()->json(['message' => 'Product code is not valid'], 500); 
        }
    
        // Initialize the conditions array with SKU code search
        $condition = [
            ['fgs_item_master.sku_code', 'like', '%' . strtoupper($request->q) . '%'],
            ['fgs_item_master.status_type', '=', 1] // Filter for active products
        ];
    
        // Fetch product data based on conditions
        $data = $this->fgs_item_master->get_product_mrn($condition);
    
        if (!empty($data)) {
            return response()->json($data, 200); 
        } else {
            return response()->json(['message' => 'Product code is not valid'], 500); 
        }
    }

    // function productsearch(Request $request,$sku = null){
    //     if(!$request->q){
    //         return response()->json(['message'=>'Product code is not valid'], 500); 
    //     }
    //     $condition[] = ['fgs_item_master.sku_code','like','%'.strtoupper($request->q).'%'];
    //     $data  = $this->product->get_product_mrn($condition);
    //     if(!empty( $data)){
    //         return response()->json( $data, 200); 
    //     }else{
    //         return response()->json(['message'=>'Product code is not valid'], 500); 
    //     }

    // }
    public function fetchProductBatchCards(Request $request)
    {
        // $batchcards = production_stock_management::select('batchcard_batchcard.batch_no','production_stock_management.stock_qty','batchcard_batchcard.id as batch_id')
        //                                 ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','production_stock_management.batchcard_id')
        //                                 ->where('production_stock_management.product_id','=',$request->product_id)
        //                                 ->where('production_stock_management.stock_qty','!=',0)
        //                                 ->get();
        $batchcards = batchcard::select('batchcard_batchcard.batch_no', 'batchcard_batchcard.id as batch_id', 'batchcard_batchcard.start_date', 'batchcard_batchcard.target_date', 'batchcard_batchcard.quantity')
            ->where('batchcard_batchcard.product_id', '=', $request->product_id)
            ->where('is_trade',0)
            ->orderBy('batchcard_batchcard.id', 'asc')
            ->get();
        return $batchcards;
    }

    public function fetchBatchCardQty(Request $request)
    {
        $batchcard = batchcard::where('batchcard_batchcard.id', '=', $request->batch_id)->where('is_trade',0)->first();
        return $batchcard['quantity'];
    }

    public function MRNitemAdd(Request $request, $mrn_id)
    {
        $product_cat = DB::table('fgs_mrn')
            ->where('id', $mrn_id)
            ->first();
    
        if ($request->isMethod('post')) {
            // Validation based on product category
            if ($product_cat->product_category == 3) {
                $validation['moreItems.*.product'] = ['required'];
                $validation['moreItems.*.batch_no'] = ['required'];
                $validation['moreItems.*.manufacturing_date'] = ['required', 'date'];
            } else {
                $validation['moreItems.*.product'] = ['required'];
                $validation['moreItems.*.batch_no'] = ['required'];
                $validation['moreItems.*.qty'] = ['required'];
                $validation['moreItems.*.manufacturing_date'] = ['required', 'date'];
            }
    
            $validator = Validator::make($request->all(), $validation);
    
            if (!$validator->errors()->all()) {
                $mrn_info = fgs_mrn::find($request->mrn_id);
    
                foreach ($request->moreItems as $key => $value) {
                    // Get product information for sterility check
                    $product_info = DB::table('fgs_item_master')->where('id', $value['product'])->first();
    
                    // Set expiry_date based on product sterility
                    if ($product_info && $product_info->is_sterile == 0) {
                        // Not sterile, set expiry_date to '0000-00-00'
                        $expiry_date = '0000-00-00';
                    } else {
                        // If product is sterile, use actual expiry date or empty string if 'N.A'
                        if ($value['expiry_date'] != 'N.A') {
                            $expiry_date = date('Y-m-d', strtotime($value['expiry_date']));
                        } else {
                            $expiry_date = '';
                        }
                    }
    
                    // Handle batchcard existence and quantity adjustments
                    if ($product_cat->product_category == 3) {
                        // When product category is 3
                        $batchcard_exist = DB::table('batchcard_batchcard')->where('batch_no', '=', $value['batch_no'])->where('is_trade', '=', 1)->first();
    
                        if (!empty($batchcard_exist)) {
                            $batch_card_id = $batchcard_exist->id;
                            $qty = $batchcard_exist->quantity - $value['qty'];
                            DB::table('batchcard_batchcard')
                                ->where('id', '=', $value['batch_no'])
                                ->update(["quantity" => $qty]);
                        } else {
                            $batch_card_id = DB::table('batchcard_batchcard')
                                ->insertGetId([
                                    "batch_no" => $value['batch_no'],
                                    "quantity" => $value['qty'],
                                    "is_trade" => 1
                                ]);
                        }
                    } else {
                        // When product category is not 3
                        $batchcard_exist = DB::table('batchcard_batchcard')->where('id', '=', $value['batch_no'])->where('is_trade', '=', 0)->first();
                        $batch_card_id = $value['batch_no'];
                        $qty = $batchcard_exist->quantity - $value['qty'];
                        DB::table('batchcard_batchcard')
                            ->where('id', '=', $value['batch_no'])
                            ->update(["quantity" => $qty]);
                    }
    
                    // Prepare data to be inserted into MRN items and stock management
                    $data = [
                        "product_id" => $value['product'],
                        "batchcard_id" => $value['batch_no'],
                        "quantity" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" => $expiry_date,
                        "created_at" => date('Y-m-d H:i:s')
                    ];
    
                    $mrn_data = [
                        'remarks' => $request->remarks
                    ];
    
                    // Check if product stock already exists
                    $prdct_stock = fgs_product_stock_management::where('product_id', $value['product'])
                        ->where('batchcard_id', $batch_card_id)
                        ->where('stock_location_id', $mrn_info->stock_location)
                        ->first();
    
                    if (!empty($prdct_stock)) {
                        $new_stock = $prdct_stock->quantity + $value['qty'];
                        $res[] = $this->fgs_product_stock_management->update_data(['id' => $prdct_stock->id], ['quantity' => $new_stock]);
                    } else {
                        $stock = [
                            "product_id" => $value['product'],
                            "batchcard_id" => $value['batch_no'],
                            "quantity" => $value['qty'],
                            "stock_location_id" => $mrn_info['stock_location'],
                            "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                            "expiry_date" => $expiry_date,
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                    }
    
                    // Insert MRN item data
                    $this->fgs_mrn_item->insert_data($data, $request->mrn_id);
                    // Update MRN data with remarks
                    $this->fgs_mrn->update_data(['id' => $request->mrn_id], $mrn_data);
                }
    
                // Success message and redirect
                $request->session()->flash('success', "You have successfully added an MRN item!");
                return redirect('fgs/MRN/item-list/' . $request->mrn_id);
            } else {
                // Validation errors, return back with errors
                return redirect('fgs/MRN/add-item/' . $request->mrn_id)->withErrors($validator)->withInput();
            }
        } else {
            // When request method is not post, return the view for adding MRN items
            return view('pages/FGS/MRN/MRN-item-add', compact('product_cat'));
        }
    }

    public function MRNpdf($mrn_id)
    {
        set_time_limit(300);
        $data['mrn'] = $this->fgs_mrn->get_single_mrn(['fgs_mrn.id' => $mrn_id]);
        $data['items'] = $this->fgs_mrn_item->getItems(['fgs_mrn_item_rel.master' => $mrn_id]);

        $pdf = PDF::loadView('pages.FGS.MRN.pdf-view', $data);
        // $pdf->set_paper('A4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);       

        $file_name = "MRN" . $data['mrn']['firm_name'] . "_" . $data['mrn']['mrn_date'];
        return $pdf->stream($file_name . '.pdf');
    }

    public function MRNUpload(Request $request)
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
            $no_column = 5;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
    
            if($sheet1_column_count == $no_column)
            {
                $reslt = $this->Excelsplitsheet($ExcelOBJ, $request->mrn_id);
    
                if($reslt)
                {
                    $request->session()->flash('success', "Successfully uploaded.");
                    return redirect('fgs/MRN/item-list/'.$request->mrn_id);
                }
                else
                {
                    $request->session()->flash('error', "The data already uploaded.");
                    return redirect('fgs/MRN/item-list/'.$request->mrn_id);
                }
            }
            else 
            {
                $request->session()->flash('error', "Column not matching. Please download the excel template and check the column count.");
                return redirect('fgs/MRN/item-list/'.$request->mrn_id);
            }
        }
    }
    
    public function Excelsplitsheet($ExcelOBJ, $mrn_id)
    {
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;
    
        foreach ($ExcelOBJ->worksheetData as $worksheet) 
        {
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            
            $this->insert_MRN_items($ExcelOBJ, $mrn_id);
        }
        return 1;
    }
    
    function insert_MRN_items($ExcelOBJ, $mrn_id)
{
    $res = [];

    foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
    {
        if ($key > 0 && $excelsheet[1]) 
        {
            $mrn = $this->fgs_mrn->get_single_mrn(['fgs_mrn.id' => $mrn_id]);

            // Check if product exists and is not deactivated (status_type != 0)
            $product = fgs_item_master::where('sku_code', '=', $excelsheet[0])->first();

            if ($product && $product->status_type != 0) 
            {
                // Check if batch number exists in batchcard_batchcard table
                $batchcard_id = batchcard::where('batch_no', '=', $excelsheet[1])->pluck('batchcard_batchcard.id')->first();

                // If location_id is 10 and batch number doesn't exist, insert it
               
                if ($mrn->location_id == 10 && !$batchcard_id) {
                    // Get the latest ID once before inserting multiple records
                    $latestId = batchcard::selectRaw('MAX(batchcard_batchcard.id) as max_id')->value('max_id') ?? 0;
                    
                    // Assign the next ID
                    $latestId++;
                
                    $batchcard_id = batchcard::insertGetId([
                        'id' => $latestId, // Use the incremented ID
                        'batch_no' => $excelsheet[1],
                        'product_id' => $product->id, 
                        'created' => now()
                    ]);
                
                    // Increase the latest ID for the next iteration
                    $latestId++;
                }
                                  
                
                if ($batchcard_id) 
                {
                    $item['product_id'] = $product->id;
                    $item['batchcard_id'] = $batchcard_id;
                    $item['quantity'] = $excelsheet[2];
                    $item['manufacturing_date'] = ($excelsheet[3] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL;
                    $item['expiry_date'] = ($excelsheet[4] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[4]))->format('Y-m-d')) : ' ';
                    $item['created_at'] = date('Y-m-d H:i:s');
                    
                    $this->fgs_mrn_item->insert_data($item, $mrn_id);

                    $prdct_stock = fgs_product_stock_management::where('product_id', $product->id)
                                    ->where('batchcard_id', $batchcard_id)
                                    ->where('stock_location_id', $mrn->stock_location)
                                    ->first();

                    if (!empty($prdct_stock)) 
                    {
                        $new_stock = $prdct_stock->quantity + $excelsheet[2];
                        $res[] = $this->fgs_product_stock_management->update_data(['id' => $prdct_stock->id], ['quantity' => $new_stock]);
                    } 
                    else 
                    {
                        $stock = [
                            "product_id" => $product->id,
                            "batchcard_id" => $batchcard_id,
                            "quantity" => $excelsheet[2],
                            "stock_location_id" => $mrn->location_id,
                            'manufacturing_date' => ($excelsheet[3] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL,
                            'expiry_date' => ($excelsheet[4] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[4]))->format('Y-m-d')) : ' '
                        ];
                        $res[] = $this->fgs_product_stock_management->insert_data($stock);
                    }
                }
            }
        }
    }

    return !empty($res) ? 1 : 0;
}

    
    public function fgsMRNStockUpload()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName = 'C:\xampp\htdocs\FGS_Stk5.xlsx';
        $ExcelOBJ->aircraft = 'B737-MAX';
        $ExcelOBJ->spreadsheet = new Spreadsheet();
        $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
        $ExcelOBJ->reader->setReadDataOnly(true);
        $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
        $this->fgsMRNStockExcelsplitsheet($ExcelOBJ);
        exit;
    }
    public function fgsMRNStockExcelsplitsheet($ExcelOBJ)
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
            //$this->insert_MRN_stock_location1($ExcelOBJ);  
            $this->insert_MRN_stock_location2($ExcelOBJ);
            // $this->insert_MRN_stock_SNN_Mktd($ExcelOBJ); 
            //$this->insert_MRN_stock_AHPL_Mktd($ExcelOBJ); 
            die("done");
        }
        exit('done');
    }
    function insert_MRN_stock_SNN_Mktd($ExcelOBJ)
    {
        if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
            $years_combo = date('y', strtotime('-1 year')) . date('y');
        } else {
            $years_combo = date('y') . date('y', strtotime('+1 year'));
        }
        $data['mrn_number'] = "MRN-" . $this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-' . $years_combo . '%')->count());
        $data['mrn_date'] = date('Y-m-d');
        $data['supplier_doc_number'] = 'Open Stock1 SNN Mktd May 18-31';
        $data['supplier_doc_date'] = date('Y-m-d');
        $data['product_category'] = 1; //ASD
        $data['stock_location'] = 6; // SNN_Mktd
        $data['created_by'] = config('user')['user_id'];
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $mrn_master = $this->fgs_mrn->insert_data($data);
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {

            if ($key > 0 &&  $excelsheet[1]) {
                if ($excelsheet[4] == 'SNN Mktd.') {
                    $product_id = product::where('sku_code', '=', $excelsheet[0])->pluck('id')->first();
                    $batchcard_id = batchcard::where('batch_no', '=', $excelsheet[2])->pluck('batchcard_batchcard.id')->first();
                    if ($product_id && $batchcard_id) {
                        $item['product_id'] = $product_id;
                        $item['batchcard_id'] = $batchcard_id;
                        $item['quantity'] = $excelsheet[3];
                        $item['manufacturing_date'] = ($excelsheet[5] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL;
                        $item['expiry_date'] = ($excelsheet[6] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' ';
                        $item['created_at'] = date('Y-m-d H:i:s');
                        $this->fgs_mrn_item->insert_data($item, $mrn_master);
                        $stock = [
                            "product_id" => $product_id,
                            "batchcard_id" => $batchcard_id,
                            "quantity" => $excelsheet[3],
                            "stock_location_id" => 6,
                            'manufacturing_date' => ($excelsheet[5] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL,
                            'expiry_date' => ($excelsheet[6] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' '
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                        // $res[]=DB::table('production_stock_management')->insert($data);
                    } else {
                        $prdct[] = $excelsheet[0];
                        $batch_card[] = $excelsheet[2];
                        $res[] = 1;
                    }
                }
            }
        }
        // print_r($prdct);
        // print_r($batch_card);
        // exit;
        if ($res) {
            //print_r($not_exist);
            return 1;
        } else
            return 0;
    }
    function insert_MRN_stock_AHPL_Mktd($ExcelOBJ)
    {
        if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
            $years_combo = date('y', strtotime('-1 year')) . date('y');
        } else {
            $years_combo = date('y') . date('y', strtotime('+1 year'));
        }
        $data['mrn_number'] = "MRN-" . $this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-' . $years_combo . '%')->count());
        $data['mrn_date'] = date('Y-m-d');
        $data['supplier_doc_number'] = 'Open Stock1 AHPL Mktd May 18-31';
        $data['supplier_doc_date'] = date('Y-m-d');
        $data['product_category'] = 1; //ASD
        $data['stock_location'] = 7; // AHPL_Mktd
        $data['created_by'] = config('user')['user_id'];
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $mrn_master = $this->fgs_mrn->insert_data($data);
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {

            if ($key > 0 &&  $excelsheet[1]) {
                if ($excelsheet[4] == 'AHPL Mktd.') {
                    $product_id = product::where('sku_code', '=', $excelsheet[0])->pluck('id')->first();
                    $batchcard_id = batchcard::where('batch_no', '=', $excelsheet[2])->pluck('batchcard_batchcard.id')->first();
                    if ($product_id && $batchcard_id) {
                        $item['product_id'] = $product_id;
                        $item['batchcard_id'] = $batchcard_id;
                        $item['quantity'] = $excelsheet[3];
                        $item['manufacturing_date'] = ($excelsheet[5] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL;
                        $item['expiry_date'] = ($excelsheet[6] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' ';
                        $item['created_at'] = date('Y-m-d H:i:s');
                        $this->fgs_mrn_item->insert_data($item, $mrn_master);
                        $stock = [
                            "product_id" => $product_id,
                            "batchcard_id" => $batchcard_id,
                            "quantity" => $excelsheet[3],
                            "stock_location_id" => 7,
                            'manufacturing_date' => ($excelsheet[5] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL,
                            'expiry_date' => ($excelsheet[6] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' '
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                        // $res[]=DB::table('production_stock_management')->insert($data);
                    } else {
                        $not_exist[] = $excelsheet[0];
                        $res[] = 1;
                    }
                }
            }
        }
        if ($res) {
            print_r($not_exist);
            return 1;
        } else
            return 0;
    }
    function insert_MRN_stock_location2($ExcelOBJ)
    {
        if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
            $years_combo = date('y', strtotime('-1 year')) . date('y');
        } else {
            $years_combo = date('y') . date('y', strtotime('+1 year'));
        }
        $data['mrn_number'] = "MRN-" . $this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-' . $years_combo . '%')->count());
        $data['mrn_date'] = date('Y-m-d');
        $data['supplier_doc_number'] = 'Open Stock1 Location2 April 29-30';
        $data['supplier_doc_date'] = date('Y-m-d');
        $data['product_category'] = 1; //ASD
        $data['stock_location'] = 2; //location2
        $data['created_by'] = config('user')['user_id'];
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $mrn_master = $this->fgs_mrn->insert_data($data);
        
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {

            if ($key > 0 &&  $excelsheet[1]) {
                if ($excelsheet[4] == 'Location-2 (Non-Std.)') {
                    $product_id = product::where('sku_code', '=', $excelsheet[0])->pluck('id')->first();
                    $batchcard_id = batchcard::where('batch_no', '=', $excelsheet[2])->pluck('batchcard_batchcard.id')->first();
                    if ($product_id && $batchcard_id) {
                        $item['product_id'] = $product_id;
                        $item['batchcard_id'] = $batchcard_id;
                        $item['quantity'] = $excelsheet[3];
                        $item['manufacturing_date'] = ($excelsheet[5] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL;
                        $item['expiry_date'] = ($excelsheet[6] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' ';
                        $item['created_at'] = date('Y-m-d H:i:s');
                        $this->fgs_mrn_item->insert_data($item, $mrn_master);
                        $stock = [
                            "product_id" => $product_id,
                            "batchcard_id" => $batchcard_id,
                            "quantity" => $excelsheet[3],
                            "stock_location_id" => 2,
                            'manufacturing_date' => ($excelsheet[5] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL,
                            'expiry_date' => ($excelsheet[6] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' '
                        ];
                        $res[] = $this->fgs_product_stock_management->insert_data($stock);
                        // $res[]=DB::table('production_stock_management')->insert($data);
                    } else {
                        $not_exist[] = $excelsheet[0];
                        $res[] = 1;
                    }
                }
            }
        }
        if ($res) {
            print_r($not_exist);
            return 1;
        } else
            return 0;
    }
    function insert_MRN_stock_location1($ExcelOBJ)
    {
        $not_exist = [];
        if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
            $years_combo = date('y', strtotime('-1 year')) . date('y');
        } else {
            $years_combo = date('y') . date('y', strtotime('+1 year'));
        }
        $data['mrn_number'] = "MRN-" . $this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-' . $years_combo . '%')->count());
        $data['mrn_date'] = date('Y-m-d');
        $data['supplier_doc_number'] = 'Open Stock1 Location1 April 29-30';
        $data['supplier_doc_date'] = date('Y-m-d');
        $data['product_category'] = 1; //ASD
        $data['stock_location'] = 1; //location1
        $data['created_by'] = config('user')['user_id'];
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $mrn_master = $this->fgs_mrn->insert_data($data);
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {

            if ($key > 0 &&  $excelsheet[1]) {
                if ($excelsheet[4] == 'Location-1 (Std.)') {
                    $product_id = product::where('sku_code', '=', $excelsheet[0])->pluck('id')->first();
                    $batchcard_id = batchcard::where('batch_no', '=', $excelsheet[2])->pluck('batchcard_batchcard.id')->first();
                    if ($batchcard_id && $product_id) {
                        $item['product_id'] = $product_id;
                        $item['batchcard_id'] = $batchcard_id;
                        $item['quantity'] = $excelsheet[3];
                        $item['manufacturing_date'] = ($excelsheet[5] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL;
                        $item['expiry_date'] = ($excelsheet[6] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' ';
                        $item['created_at'] = date('Y-m-d H:i:s');
                        $this->fgs_mrn_item->insert_data($item, $mrn_master);
                        $stock = [
                            "product_id" => $product_id,
                            "batchcard_id" => $batchcard_id,
                            "quantity" => $excelsheet[3],
                            "stock_location_id" => 1,
                            'manufacturing_date' => ($excelsheet[5] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL,
                            'expiry_date' => ($excelsheet[6] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' '
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                        //$res[] = 1;
                    } else {
                        $not_exist[] = $excelsheet[2];
                        $res[] = 1;
                    }
                }
            }
        }
        if ($res) {
            print_r($not_exist);
            exit;
            return 1;
        } else
            return 0;
    }
    public function check_item($id)
    {
        $mrn_check = fgs_mrn_item_rel::leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_mrn_item_rel.item')
                            ->where('fgs_mrn_item.status','=',1)
                            ->where('fgs_mrn_item_rel.master', $id)->get();
        return $mrn_check;
    }
    public function edit_mrn_item($id,Request $request)
    {
    
        $grs_item = DB::table('fgs_grs_item')->where('mrn_item_id',$id)->where('status','=',1)->get();
        if(count($grs_item)>0)
        {
            // $request->session()->flash('error', "You can't update this MRN Item.This moved to Next step !");
            return redirect()->back();
        }
        else
        {
            $mrn_id = DB::table('fgs_mrn_item_rel')->where('item','=',$id)->first();
            $mrn = fgs_mrn::select('product_stock_location.location_name')
                        ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_mrn.stock_location')
                        ->where('fgs_mrn.id',$mrn_id->master)
                        ->first();
            if($mrn->location_name!='SNN Trade')
            {
                    $item_details = DB::table('fgs_mrn_item')
                        ->select('fgs_mrn_item.*', 'fgs_item_master.sku_code', 'fgs_item_master.id as product_id','fgs_item_master.discription', 'fgs_item_master.hsn_code', 'batchcard_batchcard.batch_no', 'fgs_mrn.mrn_number','fgs_item_master.is_sterile')
                        ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                        ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                        ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
                        ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
                        ->where('fgs_mrn_item.id', $id)
                        ->orderBy('fgs_mrn_item.id', 'DESC')
                        ->first();
                    $batchcards = batchcard::select('batchcard_batchcard.batch_no', 'batchcard_batchcard.id as batch_id', 'batchcard_batchcard.start_date', 'batchcard_batchcard.target_date', 'batchcard_batchcard.quantity')
                        ->where('batchcard_batchcard.product_id', '=', $item_details->product_id)
                        ->where('is_trade',0)
                        ->orderBy('batchcard_batchcard.id', 'asc')
                        ->get();
            }
            else
            {
                $item_details = DB::table('fgs_mrn_item')
                        ->select('fgs_mrn_item.*', 'fgs_item_master.sku_code', 'fgs_item_master.id as product_id','fgs_item_master.discription', 'fgs_item_master.hsn_code', 'batchcard_batchcard.batch_no', 'fgs_mrn.mrn_number','fgs_item_master.is_sterile')
                        ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                        ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                        ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
                        ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
                        ->where('fgs_mrn_item.id', $id)
                        ->orderBy('fgs_mrn_item.id', 'DESC')
                        ->first();
                $batchcards = batchcard::select('batchcard_batchcard.batch_no', 'batchcard_batchcard.id as batch_id', 'batchcard_batchcard.start_date', 'batchcard_batchcard.target_date', 'batchcard_batchcard.quantity')
                        ->where('batchcard_batchcard.id', '=', $item_details->batchcard_id)
                        //->where('is_trade',0)
                        ->orderBy('batchcard_batchcard.id', 'asc')
                        ->get();
               // print_r($batchcards);exit;
            }
            //$batchcards = fgs_product_stock_management
            // dd($item_details);

            return view('pages/fgs/MRN/MRN-update-item', compact('item_details', 'id','batchcards'));
        }
    }
    // public function update_mrn_item(Request $request)
    // {
    //     $validation['batch_no'] = ['required'];
    //     $validation['stock_qty'] = ['required'];
    //     $validation['manufacturing_date'] = ['required', 'date'];
    //     $validator = Validator::make($request->all(), $validation);
    //     if(!$validator->errors()->all())
    //     {
    //         $mrn_item = fgs_mrn_item::find($request->mrn_item_id);
    //         $mrn_rel = DB::table('fgs_mrn_item_rel')->where('item',$request->mrn_item_id)->first();
    //         $mrn = fgs_mrn::find($mrn_rel->master);
    //         $old_batch = $mrn_item->batchcard_id;
    //         $old_qty = $mrn_item->quantity;
    //         $product = $request->product_id;

    //         //old batch Stock updation 
    //         $old_stock = DB::table('fgs_product_stock_management')
    //                                 ->where('product_id','=',$product)
    //                                 ->where('batchcard_id','=',$old_batch)
    //                                 ->first();
    //         $old_stock_update = $old_stock->quantity - $mrn_item->quantity; 
    //         $old_stock_updation = DB::table('fgs_product_stock_management')
    //                                         ->where('id',$old_stock->id)
    //                                         ->update(['quantity'=>$old_stock_update]);
            
            
    //         //new batch stock updation
    //         $new_stock = DB::table('fgs_product_stock_management')
    //                             ->where('product_id','=',$product)
    //                             ->where('batchcard_id','=',$request->batch_no)
    //                             ->first();
    //         if(!empty($new_stock))
    //         {
    //             if ($request->expiry_date != 'N.A')
    //                 $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
    //             else
    //                 $expiry_date = '';
    //             $new_stock_update = $new_stock->quantity + $request->stock_qty; 
    //             $new_stock_updation = DB::table('fgs_product_stock_management')
    //                                     ->where('id',$new_stock->id)
    //                                     ->update([
    //                                         'quantity'=>$new_stock_update,
    //                                         'manufacturing_date'=>date('Y-m-d', strtotime($request->manufacturing_date)),
    //                                         'expiry_date'=>$expiry_date]);
    //         }
    //         else
    //         {
    //             if ($request->expiry_date != 'N.A')
    //                 $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
    //             else
    //                 $expiry_date = '';
    //             $stock = [
    //                 "product_id" => $product,
    //                 "batchcard_id" => $request->batch_no,
    //                 "quantity" => $request->stock_qty,
    //                 "stock_location_id" =>$mrn->stock_location,
    //                 'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
    //                 'expiry_date' => $expiry_date,
    //                 //'created_at'=>date('Y-m-d'),
    //             ];
    //             $res = $this->fgs_product_stock_management->insert_data($stock);
    //         }
    //         if ($request->expiry_date != 'N.A')
    //         $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
    //         else
    //         $expiry_date = '';
    //         $data['batchcard_id']=$request->batch_no;
    //         $data['quantity'] = $request->stock_qty;
    //         $data['manufacturing_date'] = date('Y-m-d', strtotime($request->manufacturing_date));
    //         $data['expiry_date'] = $expiry_date;
    //         $update_mrn = $this->fgs_mrn_item->update_data(['id'=>$mrn_item->id],$data);
    //         if($update_mrn)
    //         $request->session()->flash('success', "You have successfully updated a MRN Item !");
    //         else
    //         $request->session()->flash('error', "You have failed to update a MRN Item !");
    //         return redirect('fgs/MRN/item-list/' . $mrn->id);
    //     }
    //     else
    //     {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
    // }
    public function update_mrn_item(Request $request)
    {
        $validation['batch_no'] = ['required'];
        $validation['stock_qty'] = ['required'];
        $validation['manufacturing_date'] = ['required', 'date'];
        $validator = Validator::make($request->all(), $validation);
        if (!$validator->errors()->all()) {
            $mrn_item = fgs_mrn_item::find($request->mrn_item_id);
            $mrn_rel = DB::table('fgs_mrn_item_rel')->where('item', $request->mrn_item_id)->first();
            $mrn = fgs_mrn::find($mrn_rel->master);
            $old_batch = $mrn_item->batchcard_id;
            $old_qty = $mrn_item->quantity;
            $product = $request->product_id;

            //old batch Stock updation 
            $old_stock = DB::table('fgs_product_stock_management')
                ->where('product_id', '=', $product)
                ->where('batchcard_id', '=', $old_batch)
                ->first();
            $old_stock_update = $old_stock->quantity - $mrn_item->quantity;
            $old_stock_updation = DB::table('fgs_product_stock_management')
                ->where('id', $old_stock->id)
                ->update(['quantity' => $old_stock_update]);


            //new batch stock updation
            $new_stock = DB::table('fgs_product_stock_management')
                ->where('product_id', '=', $product)
                ->where('batchcard_id', '=', $request->batch_no)
                ->first();
            if (!empty($new_stock)) {
                if ($request->expiry_date != 'N.A')
                    $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
                else
                    $expiry_date = '';
                $new_stock_update = $new_stock->quantity + $request->stock_qty;
                $new_stock_updation = DB::table('fgs_product_stock_management')
                    ->where('id', $new_stock->id)
                    ->update([
                        'quantity' => $new_stock_update,
                        'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
                        'expiry_date' => $expiry_date
                    ]);
                $data['batchcard_id'] = $request->batch_no;
                $data['quantity'] = $request->stock_qty;
                $data['manufacturing_date'] = date('Y-m-d', strtotime($request->manufacturing_date));
                $data['expiry_date'] = $expiry_date;
                $update_mrn = $this->fgs_mrn_item->update_data(['id' => $mrn_item->id], $data);
                if ($update_mrn)
                    $request->session()->flash('success', "You have successfully updated a MRN Item !");
                else
                    $request->session()->flash('error', "You have failed to update a MRN Item !");
                return redirect('fgs/MRN/item-list/' . $mrn->id);
            } else {
                if ($request->expiry_date != 'N.A')
                    $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
                else
                    $expiry_date = '';
                $stock = [
                    "product_id" => $product,
                    "batchcard_id" => $request->batch_no,
                    "quantity" => $request->stock_qty,
                    "stock_location_id" => $mrn->stock_location,
                    'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
                    'expiry_date' => $expiry_date,
                    //'created_at'=>date('Y-m-d'),
                ];
                $res = $this->fgs_product_stock_management->insert_data($stock);
                $data['batchcard_id'] = $request->batch_no;
                $data['quantity'] = $request->stock_qty;
                $data['manufacturing_date'] = date('Y-m-d', strtotime($request->manufacturing_date));
                $data['expiry_date'] = $expiry_date;
                $update_mrn = $this->fgs_mrn_item->update_data(['id' => $mrn_item->id], $data);
                if ($update_mrn)
                    $request->session()->flash('success', "You have successfully updated a MRN Item !");
                else
                    $request->session()->flash('error', "You have failed to update a MRN Item !");
                return redirect('fgs/MRN/item-list/' . $mrn->id);
            }
            if ($request->expiry_date != 'N.A')
                $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
            else
                $expiry_date = '';
            $data['batchcard_id'] = $request->batch_no;
            $data['quantity'] = $request->stock_qty;
            $data['manufacturing_date'] = date('Y-m-d', strtotime($request->manufacturing_date));
            $data['expiry_date'] = $expiry_date;
            $update_mrn = $this->fgs_mrn_item->update_data(['id' => $mrn_item->id], $data);
            if ($update_mrn)
                $request->session()->flash('success', "You have successfully updated a MRN Item !");
            else
                $request->session()->flash('error', "You have failed to update a MRN Item !");
            return redirect('fgs/MRN/item-list/' . $mrn->id);
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
    public function delete_mrn_item($mrn_item_id,Request $request)
    {
        $grs_item = DB::table('fgs_grs_item')->where('mrn_item_id','=',$mrn_item_id)->where('status',1)->get();
        if(count($grs_item)>0)
        {
           // dd('if');
            $request->session()->flash('error', "You can't delete this MRN Item,It moved to next step !");
            return redirect()->back();
        }
        else
        {
           // dd('else');
            $mrn_item_rel = DB::table('fgs_mrn_item_rel')->where('item',$mrn_item_id)->first();
            $mrn_master = DB::table('fgs_mrn')->where('id',$mrn_item_rel->master)->first();
            $mrn_item=DB::table('fgs_mrn_item')
                        ->where('id',$mrn_item_id) 
                        ->first();
            $stock=DB::table('fgs_product_stock_management')
                            ->where('product_id',$mrn_item->product_id)
                            ->where('batchcard_id',$mrn_item->batchcard_id)
                            ->where('stock_location_id',$mrn_master->stock_location)
                            ->where('quantity','!=',0)
                            ->first();
            //print_r($mrn_item);exit;
            //  dd($stock);
            $fgs_qty = (float) $mrn_item->quantity;
            if ($stock) {
                $pstock_qty = (float) $stock->quantity;
               // dd($pstock_qty);
            $update_stock =  DB::table('fgs_product_stock_management')
                                ->where('id',$stock->id)
                                ->update([
                                    'quantity'=>$pstock_qty-$fgs_qty
                                ]);
                }
            $mrn_item_update =  DB::table('fgs_mrn_item')
                                ->where('id',$mrn_item->id)
                                ->update([
                                    'status'=>0
                                ]);
            $batch = DB::table('batchcard_batchcard')->where('batchcard_batchcard.id', $mrn_item->batchcard_id)->first();
            $batchqty_new = $fgs_qty + $batch->quantity;
            DB::table('batchcard_batchcard')->where('id', $mrn_item->batchcard_id)
                ->update([
                'quantity' => $batchqty_new,
                        ]);                   
            if($mrn_item_update)
            $request->session()->flash('success', "You have successfully deleted a MRN Item !");
            else
            $request->session()->flash('error', "You have failed to delete a MRN Item !");
            return redirect()->back();
        }
        
    }
    public function mrn_transaction(Request $request)
    {
        $condition = [];
        if ($request->mrn_no) {
            $condition[] = ['fgs_mrn.mrn_number', 'like', '%' . $request->mrn_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_mrn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }

        $items = fgs_mrn_item::select(
            'fgs_mrn_item.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no',
            'batchcard_batchcard.id as batch_id',
            'fgs_mrn.mrn_number',
            'fgs_mrn.mrn_date',
            'fgs_mrn.created_at as mrn_wef',
            'fgs_mrn_item.id as mrn_item_id'
        )
            ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            ->where($condition)
            ->where('fgs_mrn_item.status', 1)
            ->distinct('fgs_mrn_item.id')
            ->orderBy('fgs_mrn_item.id', 'desc')
            ->paginate(15);

        return view('pages/fgs/MRN/MRN-transaction-list', compact('items'));
    }
    public function mrn_transaction_export(Request $request)
    {
        $condition = [];
        if ($request->mrn_no) {
            $condition[] = ['fgs_mrn.mrn_number', 'like', '%' . $request->mrn_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_mrn.mrn_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }

        $items = fgs_mrn_item::select(
            'fgs_mrn_item.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'batchcard_batchcard.batch_no',
            'batchcard_batchcard.id as batch_id',
            'fgs_mrn.mrn_number',
            'fgs_mrn.mrn_date',
            'fgs_mrn.created_at as mrn_wef',
            'fgs_mrn_item.id as mrn_item_id',
            'customer_supplier.firm_name',
            'customer_supplier.city',
            'state.state_name',
            'zone.zone_name',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            // 'transaction_type.transaction_name',
            'customer_supplier.sales_type',
        )
            ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_mrn_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_mrn.supplier')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_mrn.product_category')
                ->leftJoin('fgs_product_category_new','fgs_product_category_new.id','fgs_mrn.new_product_category')
                // ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
            ->where($condition)
            ->where('fgs_mrn_item.status','=', 1)
            ->where('fgs_mrn.status','=', 1)
            //->distinct('fgs_mrn_item.id')
            ->orderBy('fgs_mrn.id', 'desc')
            ->get();

        return Excel::download(new FGSmrntransactionExport($items), 'FGS-MRN-transaction' . date('d-m-Y') . '.xlsx');
    }
   
}
