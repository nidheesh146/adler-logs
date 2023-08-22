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
use App\Models\FGS\fgs_mrn;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_mrn_item_rel;
use App\Models\batchcard;
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

        $mrn = fgs_mrn::select('fgs_mrn.*', 'fgs_product_category.category_name', 'product_stock_location.location_name')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_mrn.product_category')
            ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_mrn.stock_location')
            ->where($condition)
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
                if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
                    $years_combo = date('y', strtotime('-1 year')) . date('y');
                } else {
                    $years_combo = date('y') . date('y', strtotime('+1 year'));
                }
                $data['mrn_number'] = "MRN-" . $this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-' . $years_combo . '%')->count());
                $data['mrn_date'] = date('Y-m-d', strtotime($request->mrn_date));
                $data['supplier_doc_number'] = $request->supplier_doc_number;
                $data['supplier_doc_date'] = date('Y-m-d', strtotime($request->supplier_doc_date));
                $data['product_category'] = $request->product_category;
                $data['stock_location'] = $request->stock_location;
                $data['created_by'] = config('user')['user_id'];
                $data['status'] = 1;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $add = $this->fgs_mrn->insert_data($data);
                if ($add) {
                    $request->session()->flash('success', "You have successfully added a MRN !");
                    return redirect('fgs/MRN/item-list/' . $add);
                } else {
                    $request->session()->flash('error', "MRN insertion is failed. Try again... !");
                    return redirect('fgs/MRN-add');
                }
            } else {
                return redirect('fgs/MRN-add')->withErrors($validator)->withInput();
            }
        } else {
            $locations = product_stock_location::get();
            
            $category = fgs_product_category::get();
           
            return view('pages/FGS/MRN/MRN-add', compact('locations', 'category'));
        }
    }

    public function MRN_delete($id)
    {
       
        fgs_mrn::where('id',$id)
        ->update([
            'status'=>0
        ]);
        fgs_mrn_item::leftjoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
        ->leftjoin('fgs_mrn','fgs_mrn.id','=','fgs_mrn_item_rel.master')
        ->where('fgs_mrn.id',$id)
        ->update([
            'fgs_mrn_item.status'=>0
        ]);
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
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->product . '%'];
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
        $mrn = fgs_mrn::select('fgs_mrn.*','fgs_product_category.category_name','product_stock_location.location_name')
                            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_mrn.product_category')
                            ->leftJoin('product_stock_location','product_stock_location.id','fgs_mrn.stock_location')
                            ->where('fgs_mrn.id','=',$request->mrn_id)
                            ->first();
        return $mrn;
    }


    // function productsearch(Request $request,$sku = null){
    //     if(!$request->q){
    //         return response()->json(['message'=>'Product code is not valid'], 500); 
    //     }
    //     $condition[] = ['product_product.sku_code','like','%'.strtoupper($request->q).'%'];
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
            ->orderBy('batchcard_batchcard.id', 'DESC')
            ->get();
        return $batchcards;
    }

    public function fetchBatchCardQty(Request $request)
    {
        $batchcard = batchcard::where('batchcard_batchcard.id', '=', $request->batch_id)->where('is_trade',0)->first();
        return $batchcard['quantity'];
    }
    public function fetchBatchCardQty_trade(Request $request)
    {
        $batchcard = batchcard::where('batchcard_batchcard.id', '=', $request->batch_id)->where('is_trade',0)->first();
        return $batchcard['quantity'];
    }

    public function MRNitemAdd(Request $request, $mrn_id)
    {
       $product_cat=DB::table('fgs_mrn')
       ->where('id',$mrn_id)
       ->first();
       
        if ($request->isMethod('post')) {
            if($product_cat->product_category==3)
            {
                $validation['moreItems.*.product'] = ['required'];
                $validation['moreItems.*.batch_no'] = ['required'];
                // $validation['batch_id.*'] = ['required'];
                // $validation['qty'] = ['required'];
                //$validation['moreItems.*.qty'] = ['required'];
                $validation['moreItems.*.manufacturing_date'] = ['required', 'date']; 
            }else{
                $validation['moreItems.*.product'] = ['required'];
                $validation['moreItems.*.batch_no'] = ['required'];
                // $validation['batch_id'] = ['required'];
                // $validation['qty'] = ['required'];
                $validation['moreItems.*.qty'] = ['required'];
                $validation['moreItems.*.manufacturing_date'] = ['required', 'date'];
            }
            // dd($request->moreItems['batch_no']);
            //$validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            //  dd($request->batch_id);
            if (!$validator->errors()->all()) {
                $mrn_info = fgs_mrn::find($request->mrn_id);
                // if(!empty($request->batch_id)){
                //     $batch_card_id= DB::table('batchcard_batchcard')
                //     ->insertGetId([
                //     "batch_no"=>$request->batch_id,
                //     "quantity"=>$request->qty,
                //     "is_trade"=>1
                //     ]);
                // }
               
                foreach ($request->moreItems as $key => $value) {
                    if($product_cat->product_category==3){
                        $batch_card_id= DB::table('batchcard_batchcard')
                        ->insertGetId([
                        "batch_no"=>$value['batch_no'],
                        "quantity"=>$value['qty'],
                        "is_trade"=>1
                        ]);
                        $qty=$value['qty'];
                    }else{
                        $batch_card_id=$value['batch_no']; 
                        $qty=$value['qty'];

                    }

                    if ($value['expiry_date'] != 'N.A')
                        $expiry_date = date('Y-m-d', strtotime($value['expiry_date']));
                    else
                        $expiry_date = '';
                        // if(empty($value['batch_no']))
                        // {
                        // $batchcard_id=$batch_card_id;
                        // $qty=$request->qty;
                        // }else{
                        //    $batchcard_id=$value['batch_no'];
                        //    $qty=$value['qty'];
                        // }
                    $data = [
                        "product_id" => $value['product'],
                       // "batchcard_id" => $value['batch_no'],moreItems[0][batch_no]
                       "batchcard_id" => $batch_card_id,
                        "quantity" => $qty,
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" => $expiry_date,
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                    $mrn_data = [
                        'remarks' => $request->remarks
                    ];
                    $stock = [
                        "product_id" => $value['product'],
                       // "batchcard_id" => $value['batch_no'],
                       "batchcard_id" =>$batch_card_id,
                        "quantity" => $qty,
                        "stock_location_id" => $mrn_info['stock_location'],
                        "quantity" => $qty,
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" => $expiry_date,
                    ];
                    $this->fgs_mrn_item->insert_data($data, $request->mrn_id);
                    $this->fgs_mrn->update_data(['id' => $request->mrn_id], $mrn_data);
                    $this->fgs_product_stock_management->insert_data($stock);
                  
                }
                $request->session()->flash('success', "You have successfully added a MRN item !");
                return redirect('fgs/MRN/item-list/' . $request->mrn_id);
            } else {
                return redirect('fgs/MRN/add-item/' . $request->mrn_id)->withErrors($validator)->withInput();
            }
        } else {
            if($product_cat->product_category==3){
                return view('pages/FGS/MRN/MRN-item-add-trade',compact('product_cat'));
            }else{
                return view('pages/FGS/MRN/MRN-item-add',compact('product_cat'));

            }
            //$batchcards = DB::table('batchcard_batchcard')->select('id',ba)->get();
        }
    }

    public function MRNpdf($mrn_id)
    {
        set_time_limit(300);
        $data['mrn'] = $this->fgs_mrn->get_single_mrn(['fgs_mrn.id' => $mrn_id]);
        $data['items'] = $this->fgs_mrn_item->getItems(['fgs_mrn_item_rel.master' => $mrn_id]);

        $pdf = PDF::loadView('pages.FGS.MRN.pdf-view', $data);
        // $pdf->set_paper('A4', 'landscape');
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
            $no_column = 6;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
            if($sheet1_column_count == $no_column)
            {
                $res = $this->Excelsplitsheet($ExcelOBJ,$request->mrn_id);
                 //print_r($res);exit;
                 if($res)
                 {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('fgs/MRN/item-list/'.$request->mrn_id);
                 }
                 else{
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect('fgs/MRN/item-list/'.$request->mrn_id);
                 }
            }
            else 
            {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect('fgs/MRN/item-list/'.$request->mrn_id);
            }
        }
    }
    public function Excelsplitsheet($ExcelOBJ,$mrn_id)
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
            $this->insert_MRN_items($ExcelOBJ,$mrn_id);
        
            //die("done");
        }
        return 1;
    }
    function insert_MRN_items($ExcelOBJ,$mrn_id)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
            $mrn = $this->fgs_mrn->get_single_mrn(['fgs_mrn.id'=>$mrn_id]);
            if ($key > 0 &&  $excelsheet[1]) 
            {
                //echo (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d'));exit;
                if($mrn->category_name != 'TRADE') 
                {
                    $product_id = product::where('sku_code', '=', $excelsheet[0])->pluck('id')->first();
                    $batchcard_id = batchcard::where('batch_no', '=', $excelsheet[1])->pluck('batchcard_batchcard.id')->first();
                    if ($product_id && $batchcard_id)
                    {
                        $item['product_id'] = $product_id;
                        $item['batchcard_id'] = $batchcard_id;
                        $item['quantity'] = $excelsheet[2];
                        $item['manufacturing_date'] = ($excelsheet[3] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL;
                        $item['expiry_date'] = ($excelsheet[4] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[4]))->format('Y-m-d')) : ' ';
                        $item['created_at'] = date('Y-m-d H:i:s');
                        $this->fgs_mrn_item->insert_data($item, $mrn_id);
                        $stock = [
                            "product_id" => $product_id,
                            "batchcard_id" => $batchcard_id,
                            "quantity" => $excelsheet[2],
                            "stock_location_id" => $mrn->location_id,
                            'manufacturing_date' => ($excelsheet[3] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL,
                            'expiry_date' => ($excelsheet[4] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[4]))->format('Y-m-d')) : ' '
                        ];
                        $res[]=$this->fgs_product_stock_management->insert_data($stock);
                        // $res[]=DB::table('production_stock_management')->insert($data);
                    } 
                    else 
                    {
                        $prdct[] = $excelsheet[0];
                        $batch_card[] = $excelsheet[1];
                        $res[] = 1;
                    }
                }
                else
                {
                    $product_id = product::where('sku_code', '=', $excelsheet[0])->pluck('id')->first();
                    if($product_id)
                    {
                        
                        $mrn_item = fgs_mrn_item::where('product_id','=',$product_id)
                                                ->where('quantity','=',$excelsheet[2])
                                                ->where('manufacturing_date','=',(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')))
                                                ->first();
                        $stock = fgs_product_stock_management::where('product_id','=',$product_id)
                                                ->where('quantity','=',$excelsheet[2])
                                                ->where('stock_location_id','=',10)
                                                ->where('manufacturing_date','=',(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')))
                                                ->first();
                        if($mrn_item)
                        {
                            $batchcard_id= DB::table('batchcard_batchcard')
                                                ->insertGetId([
                                                    "batch_no"=>$excelsheet[1],
                                                    "quantity"=>$excelsheet[2],
                                                    'product_id'=>$product_id,
                                                    "is_trade"=>1
                                                ]);
                            $mrn_item_update = fgs_mrn_item::where('id','=',$mrn_item['id'])->update([
                                'batchcard_id'=>$batchcard_id,
                                'manufacturing_date'=>($excelsheet[3] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL,
                                'expiry_date'=>($excelsheet[4] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[4]))->format('Y-m-d')) : ' ',
                            ]);
                            $res[] = fgs_product_stock_management::where('id','=',$stock['id'])->update([
                                'batchcard_id'=>$batchcard_id,
                                'manufacturing_date'=>($excelsheet[3] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL,
                                'expiry_date'=>($excelsheet[4] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[4]))->format('Y-m-d')) : ' ',
                            ]);
                        }
                        // $qty=$excelsheet[2];
                        // $item['product_id'] = $product_id;
                        // $item['batchcard_id'] = $batchcard_id;
                        // $item['quantity'] = $excelsheet[2];
                        // $item['manufacturing_date'] = ($excelsheet[3] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL;
                        // $item['expiry_date'] = ($excelsheet[4] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[4]))->format('Y-m-d')) : ' ';
                        // $item['created_at'] = date('Y-m-d H:i:s');
                        // $this->fgs_mrn_item->insert_data($item, $mrn_id);
                        // $stock = [
                        //     "product_id" => $product_id,
                        //     "batchcard_id" => $batchcard_id,
                        //     "quantity" => $excelsheet[2],
                        //     "stock_location_id" => $mrn->location_id,
                        //     'manufacturing_date' => ($excelsheet[3] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL,
                        //     'expiry_date' => ($excelsheet[4] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[4]))->format('Y-m-d')) : ' '
                        // ];
                        // $res[] = $this->fgs_product_stock_management->insert_data($stock);
                    }
                    else
                    {
                        $prdct[] = $excelsheet[0];
                        //$batch_card[] = $excelsheet[2];
                        $res[] = 1;
                    }
                }
            }
        }
        if ($res) {
            //print_r($not_exist);
            return 1;
        } else
            return 0;
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
    public function edit_mrn($id)
    {
        // $item_details=DB::table('fgs_mrn')
        // ->where('id',$id)
        // ->first();
        $item_details = DB::table('fgs_mrn_item')
            ->select('fgs_mrn_item.*', 'product_product.sku_code', 'product_product.discription', 'product_product.hsn_code', 'batchcard_batchcard.batch_no', 'fgs_mrn.mrn_number','product_product.is_sterile')
            ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_mrn_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            ->where('fgs_mrn_item.id', $id)
            ->orderBy('fgs_mrn_item.id', 'DESC')
            ->first();
        // dd($item_details);

        return view('pages/fgs/MRN/MRN-update-item', compact('item_details', 'id'));
    }
    public function update_mrn(Request $request)
    {
        // $end = date('Y-m-d', strtotime('$request->manufacturing_date1','+5 years'));
        // $expiry_date=($request->manufacturing_date1)->addYears(5);

        $product = $request->product_id;
        $batch=$request->batchcard_id;
        //dd($batch);
        $ps_mangaer=DB::table('fgs_product_stock_management')
                        ->where('product_id','=',$product)
                        ->where('batchcard_id','=',$batch)
                        ->first();
        
        $sterile = DB::table('product_product')
                    ->where('id', $product)
                    ->first();

        if ($sterile->is_sterile == 1) {
            $end = date('Y-m-d', strtotime($request->manufacturing_date1 . '+5 years'));
        } else {
            $end = NULL;
        }
        //dd($end);
        DB::table('fgs_mrn_item')
            ->where('id', $request->Itemtypehidden)
            ->update([
                'quantity' => $request->stock_qty1,
                'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date1)),
                'expiry_date' => $end
            ]);
            DB::table('fgs_product_stock_management')
            ->where('id', $ps_mangaer->id)
            ->update([
                'quantity' => $request->stock_qty1,
                'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date1)),
                'expiry_date' => $end
            ]);
        $item_details = DB::table('fgs_mrn_item')
            ->select('fgs_mrn_item.*', 'product_product.sku_code', 'product_product.discription', 'product_product.hsn_code', 'batchcard_batchcard.batch_no', 'fgs_mrn.mrn_number','product_product.is_sterile')
            ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_mrn_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
            ->where('fgs_mrn_item.id', $request->Itemtypehidden)
            ->orderBy('fgs_mrn_item.id', 'DESC')
            ->first();
        $id = $request->Itemtypehidden;
        return view('pages/fgs/MRN/MRN-update-item', compact('item_details', 'id'));
    }
    public function delete_mrn($id)
    {
        $prdct=DB::table('fgs_mrn_item')
        ->where('id',$id) 
        ->first();
        $qty=DB::table('fgs_product_stock_management')
        ->where('id',$id)
        ->first();

$fgs_qty=number_format($prdct->quantity);
$pstock_qty=number_format($qty->quantity);
//dd($fgs_qty-$pstock_qty);
// $value=$fgs_qty-$qty;

        DB::table('fgs_product_stock_management')
        ->where('id',$id)
        ->update([
            'quantity'=>$fgs_qty-$pstock_qty
        ]);
        DB::table('fgs_mrn_item')
        ->where('product_id',$prdct->product_id)
        ->where('batchcard_id',$prdct->batchcard_id)
        ->update([
            'status'=>0
        ]);
        //$mrn_id=$id;
        return redirect()->back();
        // $items = $this->MRNitemlist($mrn_id);
        // return view('pages/FGS/MRN/MRN-item-list',compact('mrn_id','items'));
    }

    public function mrn_transaction(Request $request)
    {
        $condition=[];
        if($request->mrn_no)
        {
            $condition[] = ['fgs_mrn.mrn_number','like', '%' . $request->mrn_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_mrn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        
        $items = fgs_mrn_item::select('fgs_mrn_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'batchcard_batchcard.batch_no','batchcard_batchcard.id as batch_id','fgs_mrn.mrn_number','fgs_mrn.mrn_date','fgs_mrn.created_at as mrn_wef','fgs_mrn_item.id as mrn_item_id')
                        ->leftjoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                        ->leftjoin('fgs_mrn','fgs_mrn.id','=','fgs_mrn_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_mrn_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                        ->where($condition)
                        ->where('fgs_mrn_item.status',1)
                        ->distinct('fgs_mrn_item.id')
                        ->orderBy('fgs_mrn_item.id','desc')
                        ->paginate(15);
                        
        return view('pages/fgs/MRN/MRN-transaction-list',compact('items'));
    }
    public function mrn_transaction_export(Request $request)
    {
        $condition=[];
        if($request->mrn_no)
        {
            $condition[] = ['fgs_mrn.mrn_number','like', '%' . $request->mrn_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_mrn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        
        $items = fgs_mrn_item::select('fgs_mrn_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'batchcard_batchcard.batch_no','batchcard_batchcard.id as batch_id','fgs_mrn.mrn_number','fgs_mrn.mrn_date','fgs_mrn.created_at as mrn_wef','fgs_mrn_item.id as mrn_item_id')
                        ->leftjoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                        ->leftjoin('fgs_mrn','fgs_mrn.id','=','fgs_mrn_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_mrn_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                        ->where($condition)
                        ->where('fgs_mrn_item.status',1)
                        ->distinct('fgs_mrn_item.id')
                        ->orderBy('fgs_mrn_item.id','desc')
                        ->get();

                        return Excel::download(new FGSmrntransactionExport($items), 'FGS-MRN-transaction' . date('d-m-Y') . '.xlsx');
    }
}
