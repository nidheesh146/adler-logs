<?php

namespace App\Http\Controllers\Web\FGS;
use Validator;
use DB;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\User;
use App\Models\batchcard;
use App\Models\FGS\fgs_sad;
use App\Models\FGS\fgs_sad_item;
use App\Models\FGS\fgs_sad_item_rel;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class SADController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_maa_stock_management = new fgs_maa_stock_management;
        $this->User = new User;
        $this->fgs_sad = new fgs_sad;
        $this->fgs_sad_item = new fgs_sad_item;
        $this->fgs_sad_item_rel = new fgs_sad_item_rel;
    }
    public function SADlist(Request $request)
    {
        $condition = [];
        if ($request->sad_no) {
            $condition[] = ['fgs_sad.sad_number', 'like', '%' . $request->sad_no . '%'];
        }
        if ($request->location) {
            $condition[] = ['product_stock_location.location_name', 'like', '%' . $request->location . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_sad.sad_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_sad.sad_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $sad_datas =  fgs_sad::select('fgs_sad.*','product_stock_location.location_name','user.f_name','user.l_name')
                            ->leftjoin('user','user.user_id','=','fgs_sad.created_by')
                            ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_sad.location_id')
                            ->where('fgs_sad.status',1)
                            ->where($condition)
                            ->orderBy('fgs_sad.id','DESC')
                            ->paginate(15);
        return view('pages/FGS/SAD/SAD-list',compact('sad_datas'));
    }
    public function SADAdd(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $validation['location'] = ['required'];
            $validation['created_by'] = ['required'];
            $validation['sad_date'] = ['required', 'date'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) 
            {
                if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
                    $years_combo = date('y', strtotime('-1 year')) . date('y');
                } else {
                    $years_combo = date('y') . date('y', strtotime('+1 year'));
                }
                $data['sad_number'] = "SAD-" . $this->year_combo_num_gen(DB::table('fgs_sad')->where('fgs_sad.sad_number', 'LIKE', 'SAD-' . $years_combo . '%')->count());
                $data['sad_date'] = date('Y-m-d', strtotime($request->sad_date));
                $data['location_id'] = $request->location;
                $data['remarks'] = $request->remarks;
                $data['created_by'] = config('user')['user_id'];
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $sad_master = $this->fgs_sad->insert_data($data);
                if ($sad_master) {
                    $request->session()->flash('success', "You have successfully added a SAD !");
                    return redirect('fgs/SAD-list');
                } else {
                    $request->session()->flash('error', "SAD insertion is failed. Try again... !");
                    return redirect('fgs/SAD-add');
                }
            }
            else {
                return redirect()->back()->withErrors($validator)->withInput();
            }

        }
        else
        {
            $condition1[] = ['user.status', '=', 1];
            $users = $this->User->get_all_users($condition1);
            $locations = product_stock_location::get();
            return view('pages/FGS/SAD/SAD-add',compact('users','locations'));
        }
    }
    public function SADItemList($sad_id)
    {
        $sad_data =  fgs_sad::select('fgs_sad.*','product_stock_location.location_name','user.f_name','user.l_name')
                        ->leftjoin('user','user.user_id','=','fgs_sad.created_by')
                        ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_sad.location_id')
                        ->where('fgs_sad.id',$sad_id)
                        ->first();
        $sad_items =fgs_sad_item::select('fgs_sad_item.*','fgs_item_master.sku_code','fgs_item_master.hsn_code','fgs_item_master.discription','batchcard_batchcard.batch_no')
                                ->leftJoin('fgs_sad_item_rel','fgs_sad_item_rel.item','=','fgs_sad_item.id')
                                ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_sad_item.product_id')
                                ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_sad_item.batchcard_id')
                                ->where('fgs_sad_item_rel.master','=',$sad_id)
                                ->paginate(15); 
        return view('pages/FGS/SAD/SAD-item-list',compact('sad_id','sad_data','sad_items'));
    }
    public function SADItemUpload(Request $request, $sad_id)
    {
        $file = $request->file('file');
        if ($file) {
            $sad_id = $request->sad_id;
            $ExcelOBJ = new \stdClass();

            $path = storage_path() . '/app/' . $request->file('file')->store('temp');

            $ExcelOBJ->inputFileName = $path;
            $ExcelOBJ->inputFileType = 'Xlsx';

            // $ExcelOBJ->filename = 'Book1.xlsx';
            // $ExcelOBJ->inputFileName = 'C:\xampp7.4\htdocs\mel\sampleData\Book1.xlsx';
            $ExcelOBJ->spreadsheet = new Spreadsheet();
            $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
            $ExcelOBJ->reader->setReadDataOnly(true);
            $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
            $no_column = 7;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            //echo $sheet1_column_count;exit;
           // echo $sai_id;exit;
            if ($sheet1_column_count == $no_column) {
                $res = $this->Excelsplitsheet($ExcelOBJ, $sad_id);
                // print_r($res);exit;
                if ($res) {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect()->back();
                } else {
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect()->back();
                }
            } else {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect()->back();
            }

            //dd($ExcelOBJ->worksheetData);
            //exit;
        }
    }
    public function Excelsplitsheet($ExcelOBJ, $sad_id)
    {
        //echo $pr_id;exit;
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;

        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) {
            $ExcelOBJ->sectionName = '';
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
            // print_r(json_encode($ExcelOBJ->worksheet));exit;
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
            $res = $this->insert_sad_items($ExcelOBJ, $sad_id);

            return $res;
        }
    }
    function insert_sad_items($ExcelOBJ, $sad_id)
    {
        
        $data = [];
        $sad_master =  fgs_sad::select('fgs_sad.*','product_stock_location.location_name','user.f_name','user.l_name')
                                                ->leftjoin('user','user.user_id','=','fgs_sad.created_by')
                                                ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_sad.location_id')
                                                ->where('fgs_sad.id',$sad_id)
                                                ->first();
        //print_r($sai_master);exit;
       
        if($sad_master->location_name=='MAA (Material Allocation Area)')
        {
            foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
            {
                if ($key > 1 &&  $excelsheet[0]) 
                {

                    $product = DB::table('fgs_item_master')->where('sku_code', $excelsheet[0])->first();
                    $batchcard = DB::table('batchcard_batchcard')->where('batch_no', $excelsheet[4])->first();
                    
                    if($product && $batchcard)
                    {
                        $product_stock = fgs_maa_stock_management::where('product_id',$product->id)
                                                ->where('batchcard_id',$batchcard->id)
                                                ->first();
                        if($product_stock)
                        {
                            $update_stock = $product_stock->quantity - $excelsheet[5];
                            $res[] = fgs_maa_stock_management::where('id',$product_stock->id)->update(['quantity'=>$update_stock]);
                        }
                        else
                        {
                            $newstock = [
                                'product_id' => $product->id,
                                'batchcard_id' => $batchcard->id,
                                'quantity' =>0,
                                //'manufacturing_date' =>($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL,,
                                //'expiry_date' =>($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[4]))->format('Y-m-d')) : ' ',
                                ];
                            $res[] = $this->fgs_product_stock_management->insert_data($newstock);

                        }
                        $item = [
                            'product_id' => $product->id,
                            'batchcard_id' => $batchcard->id,
                            'quantity' => $excelsheet[5],
                            'rate' => $excelsheet[6],
                            // 'manufacturing_date' =>($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL,
                            // 'expiry_date' =>($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ' ',
                            'manufacturing_date' => ($excelsheet[2] != "") ? (date('Y-m-d', strtotime($excelsheet[2]))) : NULL,
                            'expiry_date' =>($excelsheet[3] != "NA") ? (date('Y-m-d', strtotime($excelsheet[3]))) : ' ',
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        $item_id = DB::table('fgs_sad_item')
                            ->insertGetId($item);
    
                        DB::table('fgs_sad_item_rel')
                            ->insert([
                                'master' => $sad_id,
                                'item' => $item_id
                            ]);
                    }
   
                }
            }
        }
        //if($sai_master->location_name=='Location-1(Std.)' && $sai_master->location_name=='Location-2(Non-Std.)' && $sai_master->location_name=='Location-3(CSL)' && $sai_master->location_name=='SNN Mktd' && $sai_master->location_name=='AHPL Mktd' && $sai_master->location_name=='SNN Trade' && $sai_master->location_name=='SNN OEM')
        else {
            foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
            {
                if ($key > 0 &&  $excelsheet[0]) 
                {

                    $product = DB::table('fgs_item_master')->where('sku_code', $excelsheet[1])->first();
                    $batchcard = DB::table('batchcard_batchcard')->where('batch_no', $excelsheet[4])->first();
                    //print_r( $product); exit;
                    if($product && $batchcard)
                    {
                        
                        $product_stock = fgs_product_stock_management::where('product_id',$product->id)
                                                ->where('batchcard_id',$batchcard->id)
                                                ->where('stock_location_id',$sad_master->location_id)
                                                ->first();
                        if($product_stock)
                        {
                            $update_stock = $product_stock->quantity - $excelsheet[5];
                            $res[] = fgs_product_stock_management::where('id',$product_stock->id)->update(['quantity'=>$update_stock]);
                        }
                        else
                        {
                            $newstock = [
                                'product_id' => $product->id,
                                'batchcard_id' => $batchcard->id,
                                'quantity' => $excelsheet[5],
                                'stock_location_id'=>$sad_master->location_id,
                                'manufacturing_date' => ($excelsheet[2] != "") ? (date('Y-m-d', strtotime($excelsheet[2]))) : NULL,
                                'expiry_date' =>($excelsheet[3] != "NA") ? (date('Y-m-d', strtotime($excelsheet[3]))) : ' ',
                                ];
                            $res[] = $this->fgs_product_stock_management->insert_data($newstock);

                        }
                        $item = [
                            'product_id' => $product->id,
                            'batchcard_id' => $batchcard->id,
                            'quantity' => $excelsheet[5],
                            'rate' => $excelsheet[6],
                            // 'manufacturing_date' =>($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL,
                            // 'expiry_date' =>($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ' ',
                            'manufacturing_date' => ($excelsheet[2] != "") ? (date('Y-m-d', strtotime($excelsheet[2]))) : NULL,
                            'expiry_date' =>($excelsheet[3] != "NA") ? (date('Y-m-d', strtotime($excelsheet[3]))) : ' ',
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        $item_id = DB::table('fgs_sad_item')
                            ->insertGetId($item);
    
                        DB::table('fgs_sad_item_rel')
                            ->insert([
                                'master' => $sad_id,
                                'item' => $item_id
                            ]);
                    }
                }
            }
        }
        return $res;
            
    }

    public function SADpdf($sad_id)
    {
        $data['sad'] =  fgs_sad::select('fgs_sad.*','product_stock_location.location_name','user.f_name','user.l_name')
                            ->leftjoin('user','user.user_id','=','fgs_sad.created_by')
                            ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_sad.location_id')
                            ->where('fgs_sad.id',$sad_id)
                            ->first();
        $data['items'] =fgs_sad_item::select('fgs_sad_item.*','fgs_item_master.sku_code','fgs_item_master.hsn_code','fgs_item_master.discription','batchcard_batchcard.batch_no')
                            ->leftJoin('fgs_sad_item_rel','fgs_sad_item_rel.item','=','fgs_sad_item.id')
                            ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_sad_item.product_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_sad_item.batchcard_id')
                            ->where('fgs_sad_item_rel.master','=',$sad_id)
                            ->get(); 
        $pdf = PDF::loadView('pages.FGS.SAD.SAD-pdf', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = $data['sad']['sad_number'] . "_" . $data['sad']['sad_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    public function SADItemAdd(Request $request, $sad_id)
    {
        $product_cat = DB::table('fgs_item_master')
            ->where('id', $request->product)
            ->first();
        $sad_info = fgs_sad::find($sad_id);
        $loc = $sad_info->location_id;

        if ($request->isMethod('post')) {
            // if($product_cat->product_category_id==3)
            // {
            //     $validation['moreItems.*.product'] = ['required'];
            //     $validation['moreItems.*.batch_no'] = ['required'];
            //     // $validation['batch_id.*'] = ['required'];
            //     // $validation['qty'] = ['required'];
            //     //$validation['moreItems.*.qty'] = ['required'];
            //     $validation['moreItems.*.manufacturing_date'] = ['required', 'date']; 
            // }else{
            $validation['moreItems.*.product'] = ['required'];
            $validation['moreItems.*.batch_no'] = ['required'];
            // $validation['batch_id'] = ['required'];
            // $validation['qty'] = ['required'];
            $validation['moreItems.*.quantity'] = ['required'];
            $validation['moreItems.*.manufacturing_date'] = ['required', 'date'];

            //dd($request->moreItems);
            //$validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            //  dd($request->batch_id);
            if (!$validator->errors()->all()) {
                // if(!empty($request->batch_id)){
                //     $batch_card_id= DB::table('batchcard_batchcard')
                //     ->insertGetId([
                //     "batch_no"=>$request->batch_id,
                //     "quantity"=>$request->qty,
                //     "is_trade"=>1
                //     ]);
                // }


                foreach ($request->moreItems as $key => $value) {
                    //dd( $value['product']);
                    $prdct_stock = fgs_product_stock_management::where('product_id', $value['product'])->where('batchcard_id', $value['batch_no'])->where('stock_location_id', $sad_info->location_id)->first();
                    // dd($prdct_stock);
                    if ($prdct_stock) {
                        $new_stock = $prdct_stock->quantity - $value['quantity'];
                        $res[] = $this->fgs_product_stock_management->update_data(['id' => $prdct_stock->id], ['quantity' => $new_stock]);
                        //dd($new_stock);
                        if (empty($value['expiry_date']))
                            $exp = "NA";
                        else
                            $exp = $value['expiry_date'];
                        $stock = [
                            "product_id" => $value['product'],
                            "batchcard_id" => $value['batch_no'],
                            // "batchcard_id" =>$batch_card_id,
                            "quantity" => $value['quantity'],
                            "rate" => $value['rate'],
                            // "quantity" => $qty,
                            "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                            "expiry_date" => $exp,
                        ];
                        $this->fgs_sad_item->insert_data($stock, $sad_id);
                    } else {
                        if (empty($value['expiry_date']))
                            $exp = "NA";
                        else
                            $exp = $value['expiry_date'];
                        $newstock = [
                            'product_id' => $value['product'],
                            'batchcard_id' => $value['batch_no'],
                            'quantity' => $value['quantity'],
                            "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                            "expiry_date" => $exp,
                        ];
                        $res[] = $this->fgs_product_stock_management->insert_data($newstock);
                    }
                    // $this->fgs_mrn_item->insert_data($data, $request->mrn_id);
                    //$this->fgs_mrn->update_data(['id' => $request->mrn_id], $mrn_data);


                }

                $request->session()->flash('success', "You have successfully added a SAI item !");
                return redirect('fgs/SAD/item-list/' . $sad_id);
            } else {
                return redirect('fgs/SAD/add-item/' . $sad_id)->withErrors($validator)->withInput();
            }
        } else {
            //$batchcards = DB::table('batchcard_batchcard')->select('id',ba)->get();
            return view('pages/FGS/SAD/SAD-item-add', compact('sad_id', 'loc'));
        }
    }
}



