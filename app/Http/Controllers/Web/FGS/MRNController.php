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
        $condition =[];
        if($request->mrn_no)
        {
            $condition[] = ['fgs_mrn.mrn_number','like', '%' . $request->mrn_no . '%'];
        }
        if($request->supplier_doc_number)
        {
            $condition[] = ['fgs_mrn.supplier_doc_number','like', '%' . $request->supplier_doc_number . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_mrn.mrn_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_mrn.mrn_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }

        $mrn = fgs_mrn::select('fgs_mrn.*','fgs_product_category.category_name','product_stock_location.location_name')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_mrn.product_category')
                        ->leftJoin('product_stock_location','product_stock_location.id','fgs_mrn.stock_location')
                        ->where($condition)
                        ->paginate(15);
        return view('pages/FGS/MRN/MRN-list', compact('mrn'));
    }

    public function MRNAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['supplier_doc_number'] = ['required'];
            $validation['supplier_doc_date'] = ['required','date'];
            $validation['mrn_date'] = ['required','date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(date('m')==01 || date('m')==02 || date('m')==03)
                {
                    $years_combo = date('y', strtotime('-1 year')).date('y');
                }
                else
                {
                    $years_combo = date('y').date('y', strtotime('+1 year'));
                }
                $data['mrn_number'] = "MRN-".$this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-'.$years_combo.'%')->count()); 
                $data['mrn_date'] = date('Y-m-d', strtotime($request->mrn_date));
                $data['supplier_doc_number'] = $request->supplier_doc_number;
                $data['supplier_doc_date'] = date('Y-m-d', strtotime($request->supplier_doc_date));
                $data['product_category'] = $request->product_category;
                $data['stock_location'] = $request->stock_location;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $add = $this->fgs_mrn->insert_data($data);
                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a MRN !");
                    return redirect('fgs/MRN/item-list/'.$add);
                }
                else
                {
                    $request->session()->flash('error', "MRN insertion is failed. Try again... !");
                    return redirect('fgs/MRN-add');
                }

            }
            else
            {
                return redirect('fgs/MRN-add')->withErrors($validator)->withInput();
            }
        }
        else
        {        
            $locations = product_stock_location::get();
            $category = fgs_product_category::get();
            return view('pages/FGS/MRN/MRN-add', compact('locations','category'));
        }
       
    }
    public function MRNitemlist(Request $request,$mrn_id)
    {
        $condition = ['fgs_mrn_item_rel.master' =>$request->mrn_id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        if($request->batchnumber)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batchnumber . '%'];
        }
        if($request->manufaturing_from)
        {
            $condition[] = ['fgs_mrn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->manufaturing_from))];
            $condition[] = ['fgs_mrn_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->manufaturing_from))];
        }
        $items = $this->fgs_mrn_item->getMRNItems($condition);
        return view('pages/FGS/MRN/MRN-item-list', compact('mrn_id','items'));
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
        $batchcards = batchcard::select('batchcard_batchcard.batch_no','batchcard_batchcard.id as batch_id')
                                        ->where('batchcard_batchcard.product_id','=',$request->product_id)
                                        ->get();
        return $batchcards;
    }

    public function MRNitemAdd(Request $request, $mrn_id)
    {
        if($request->isMethod('post'))
        {
            $validation['moreItems.*.product'] = ['required'];
            $validation['moreItems.*.batch_no'] = ['required'];
            $validation['moreItems.*.qty'] = ['required'];
            $validation['moreItems.*.manufacturing_date'] = ['required','date'];
            //$validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $mrn_info = fgs_mrn::find($request->mrn_id);
                foreach ($request->moreItems as $key => $value) 
                {
                    if($value['expiry_date']!='N.A')
                    $expiry_date = date('Y-m-d', strtotime($value['expiry_date']));
                    else
                    $expiry_date = '';
                    $data = [
                        "product_id" => $value['product'],
                        "batchcard_id"=> $value['batch_no'],
                        "quantity" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" => $expiry_date ,
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                    $mrn_data =[
                        'remarks' => $request->remarks
                    ];
                    $stock = [
                        "product_id" => $value['product'],
                        "batchcard_id"=> $value['batch_no'],
                        "quantity" => $value['qty'],
                        "stock_location_id"=>$mrn_info['stock_location'],
                        "quantity" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" => $expiry_date ,
                    ];
                    $this->fgs_mrn_item->insert_data($data,$request->mrn_id);
                    $this->fgs_mrn->update_data(['id'=>$request->mrn_id],$mrn_data);
                    $this->fgs_product_stock_management->insert_data($stock);
                    // $production_stock = production_stock_management::where('product_id','=',$value['product'])
                    //                             ->where('batchcard_id','=',$value['batch_no'])
                    //                             ->first();
                    // $update_stock = $production_stock['stock_qty']-$value['qty'];
                    // $production_stock = $this->production_stock_management->update_data(['id'=>$production_stock['id']],['stock_qty'=>$update_stock]);              
                }
                $request->session()->flash('success',"You have successfully added a MRN item !");
                return redirect('fgs/MRN/item-list/'.$request->mrn_id);
            } 
            else
            {
                return redirect('fgs/MRN/add-item/'.$request->mrn_id)->withErrors($validator)->withInput();
            }
        }
        else{
            //$batchcards = DB::table('batchcard_batchcard')->select('id',ba)->get();
            return view('pages/FGS/MRN/MRN-item-add');
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
    public function fgsMRNStockUpload()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName ='C:\xampp\htdocs\new_stock.xlsx';
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
           // $this->insert_MRN_stock_location1($ExcelOBJ);  
            //$this->insert_MRN_stock_location2($ExcelOBJ);
           //$this->insert_MRN_stock_SNN_Mktd($ExcelOBJ); 
           //$this->insert_MRN_stock_AHPL_Mktd($ExcelOBJ); 
            die("done");
        }
        exit('done');
    }
    function insert_MRN_stock_SNN_Mktd($ExcelOBJ)
    {
        $prdct = [];
        $batch_card = [];
        if(date('m')==01 || date('m')==02 || date('m')==03)
        {
            $years_combo = date('y', strtotime('-1 year')).date('y');
        }
        else
        {
            $years_combo = date('y').date('y', strtotime('+1 year'));
        }
        $data['mrn_number'] = "MRN-".$this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-'.$years_combo.'%')->count()); 
        $data['mrn_date'] = date('Y-m-d');
        $data['supplier_doc_number'] = 'Open Stock1 SNN Mktd April 15-18';
        $data['supplier_doc_date'] = date('Y-m-d');
        $data['product_category'] = 1;//ASD
        $data['stock_location'] = 6; // SNN_Mktd
        $data['created_by']= config('user')['user_id'];
        $data['status']=1;
        $data['created_at'] =date('Y-m-d H:i:s');
        $data['updated_at'] =date('Y-m-d H:i:s');
        $mrn_master = $this->fgs_mrn->insert_data($data);
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
    
            if ($key > 0 &&  $excelsheet[1]) 
             {
                if($excelsheet[4]=='SNN Mktd.')
                {
                    $product_id = product::where('sku_code','=',$excelsheet[0])->pluck('id')->first();
                    $batchcard_id=batchcard::where('batch_no','=',$excelsheet[2])->pluck('batchcard_batchcard.id')->first();
                    if($product_id && $batchcard_id )
                    {
                        $item['product_id'] = $product_id;
                        $item['batchcard_id'] = $batchcard_id;
                        $item['quantity'] = $excelsheet[3];
                        $item['manufacturing_date']=($excelsheet[5]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL;
                        $item['expiry_date']=($excelsheet[6]!="NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' ';
                        $item['created_at'] = date('Y-m-d H:i:s');
                        $this->fgs_mrn_item->insert_data($item,$mrn_master);
                        $stock = [
                            "product_id" => $product_id,
                            "batchcard_id"=> $batchcard_id,
                            "quantity" => $excelsheet[3],
                            "stock_location_id"=>6,
                            'manufacturing_date'=>($excelsheet[5]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL,
                            'expiry_date'=>($excelsheet[6]!="NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' '
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                       // $res[]=DB::table('production_stock_management')->insert($data);
                    }
                    else
                    {
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
        if($res)
        {
        //print_r($not_exist);
        return 1;
       
        }
        else
        return 0;
    }
    function insert_MRN_stock_AHPL_Mktd($ExcelOBJ)
    {
        if(date('m')==01 || date('m')==02 || date('m')==03)
        {
            $years_combo = date('y', strtotime('-1 year')).date('y');
        }
        else
        {
            $years_combo = date('y').date('y', strtotime('+1 year'));
        }
        $data['mrn_number'] = "MRN-".$this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-'.$years_combo.'%')->count()); 
        $data['mrn_date'] = date('Y-m-d');
        $data['supplier_doc_number'] = 'Open Stock1 AHPL Mktd April 19-28';
        $data['supplier_doc_date'] = date('Y-m-d');
        $data['product_category'] = 1;//ASD
        $data['stock_location'] = 7; // AHPL_Mktd
        $data['created_by']= config('user')['user_id'];
        $data['status']=1;
        $data['created_at'] =date('Y-m-d H:i:s');
        $data['updated_at'] =date('Y-m-d H:i:s');
        $mrn_master = $this->fgs_mrn->insert_data($data);
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
    
            if ($key > 0 &&  $excelsheet[1]) 
             {
                if($excelsheet[4]=='AHPL Mktd.')
                {
                    $product_id = product::where('sku_code','=',$excelsheet[0])->pluck('id')->first();
                    $batchcard_id=batchcard::where('batch_no','=',$excelsheet[2])->pluck('batchcard_batchcard.id')->first();
                    if($product_id && $batchcard_id )
                    {
                        $item['product_id'] = $product_id;
                        $item['batchcard_id'] = $batchcard_id;
                        $item['quantity'] = $excelsheet[3];
                        $item['manufacturing_date']=($excelsheet[5]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL;
                        $item['expiry_date']=($excelsheet[6]!="NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' ';
                        $item['created_at'] = date('Y-m-d H:i:s');
                        $this->fgs_mrn_item->insert_data($item,$mrn_master);
                        $stock = [
                            "product_id" => $product_id,
                            "batchcard_id"=> $batchcard_id,
                            "quantity" => $excelsheet[3],
                            "stock_location_id"=>7,
                            'manufacturing_date'=>($excelsheet[5]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL,
                            'expiry_date'=>($excelsheet[6]!="NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' '
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                       // $res[]=DB::table('production_stock_management')->insert($data);
                    }
                    else
                    {
                        $not_exist[] = $excelsheet[0];
                        $res[] = 1;
                    }
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
    function insert_MRN_stock_location2($ExcelOBJ)
    {
        if(date('m')==01 || date('m')==02 || date('m')==03)
        {
            $years_combo = date('y', strtotime('-1 year')).date('y');
        }
        else
        {
            $years_combo = date('y').date('y', strtotime('+1 year'));
        }
        $data['mrn_number'] = "MRN-".$this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-'.$years_combo.'%')->count()); 
        $data['mrn_date'] = date('Y-m-d');
        $data['supplier_doc_number'] = 'Open Stock1 Location2 April 15-18';
        $data['supplier_doc_date'] = date('Y-m-d');
        $data['product_category'] = 1;//ASD
        $data['stock_location'] = 2;//location2
        $data['created_by']= config('user')['user_id'];
        $data['status']=1;
        $data['created_at'] =date('Y-m-d H:i:s');
        $data['updated_at'] =date('Y-m-d H:i:s');
        $mrn_master = $this->fgs_mrn->insert_data($data);
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
    
            if ($key > 0 &&  $excelsheet[1]) 
             {
                if($excelsheet[4]=='Location-2 (Non-Std.)')
                {
                    $product_id = product::where('sku_code','=',$excelsheet[0])->pluck('id')->first();
                    $batchcard_id=batchcard::where('batch_no','=',$excelsheet[2])->pluck('batchcard_batchcard.id')->first();
                    if($product_id && $batchcard_id )
                    {
                        $item['product_id'] = $product_id;
                        $item['batchcard_id'] = $batchcard_id;
                        $item['quantity'] = $excelsheet[3];
                        $item['manufacturing_date']=($excelsheet[5]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL;
                        $item['expiry_date']=($excelsheet[6]!="NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' ';
                        $item['created_at'] = date('Y-m-d H:i:s');
                        $this->fgs_mrn_item->insert_data($item,$mrn_master);
                        $stock = [
                            "product_id" => $product_id,
                            "batchcard_id"=> $batchcard_id,
                            "quantity" => $excelsheet[3],
                            "stock_location_id"=>2,
                            'manufacturing_date'=>($excelsheet[5]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL,
                            'expiry_date'=>($excelsheet[6]!="NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' '
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                       // $res[]=DB::table('production_stock_management')->insert($data);
                    }
                    else
                    {
                        $not_exist[] = $excelsheet[0];
                        $res[] = 1;
                    }
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
    function insert_MRN_stock_location1($ExcelOBJ)
    {
        $not_exist=[];
        if(date('m')==01 || date('m')==02 || date('m')==03)
        {
            $years_combo = date('y', strtotime('-1 year')).date('y');
        }
        else
        {
            $years_combo = date('y').date('y', strtotime('+1 year'));
        }
        $data['mrn_number'] = "MRN-".$this->year_combo_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN-'.$years_combo.'%')->count()); 
        $data['mrn_date'] = date('Y-m-d');
        $data['supplier_doc_number'] = 'Open Stock1 Location1 April 15-18';
        $data['supplier_doc_date'] = date('Y-m-d');
        $data['product_category'] = 1;//ASD
        $data['stock_location'] = 1;//location1
        $data['created_by']= config('user')['user_id'];
        $data['status']=1;
        $data['created_at'] =date('Y-m-d H:i:s');
        $data['updated_at'] =date('Y-m-d H:i:s');
        $mrn_master = $this->fgs_mrn->insert_data($data);
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
    
            if ($key > 0 &&  $excelsheet[1]) 
             {
                if($excelsheet[4]=='Location-1 (Std.)')
                {
                    $product_id = product::where('sku_code','=',$excelsheet[0])->pluck('id')->first();
                    $batchcard_id=batchcard::where('batch_no','=',$excelsheet[2])->pluck('batchcard_batchcard.id')->first();
                    if($batchcard_id && $product_id)
                    {
                        $item['product_id'] = $product_id;
                        $item['batchcard_id'] = $batchcard_id;
                        $item['quantity'] = $excelsheet[3];
                        $item['manufacturing_date']=($excelsheet[5]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL;
                        $item['expiry_date']=($excelsheet[6]!="NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' ';
                        $item['created_at'] = date('Y-m-d H:i:s');
                        $this->fgs_mrn_item->insert_data($item,$mrn_master);
                        $stock = [
                            "product_id" => $product_id,
                            "batchcard_id"=> $batchcard_id,
                            "quantity" => $excelsheet[3],
                            "stock_location_id"=>1,
                            'manufacturing_date'=>($excelsheet[5]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[5]))->format('Y-m-d')) : NULL,
                            'expiry_date'=>($excelsheet[6]!="NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[6]))->format('Y-m-d')) : ' '
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                       //$res[] = 1;
                    }
                    else
                    {
                        $not_exist[] = $excelsheet[2];
                        $res[] = 1;
                    }
                }
            }
        }
        if($res)
        {
        print_r($not_exist);
        exit;
        return 1;
        }
        else
        return 0;
    }
}
