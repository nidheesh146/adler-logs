<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_dni;
use App\Models\FGS\fgs_srn;
use App\Models\FGS\fgs_srn_item;
use App\Models\FGS\fgs_srn_item_rel;
use App\Models\FGS\fgs_dni_item_rel;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_dni_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\inventory_gst;
use App\Models\batchcard;
use App\Models\product;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FGSsrntransactionExport;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class SRNController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_dni = new fgs_dni;
        $this->fgs_srn = new fgs_srn;
        $this->fgs_srn_item = new fgs_srn_item;
        $this->fgs_dni_item = new fgs_dni_item;
        $this->fgs_srn_item_rel = new fgs_srn_item_rel;
        $this->fgs_dni_item_rel = new fgs_dni_item_rel;
        $this->fgs_pi_item_rel = new fgs_pi_item_rel;
        $this->product = new product;
        $this->batchcard = new batchcard;
        
    }
    public function SRNlist(Request $request)
    {
        // $dni_items = fgs_dni_item::get();
        // foreach($dni_items as $dni_item)
        // {
        //     $pi_item = fgs_pi_item::where('id','=',$dni_item->pi_item_id)->first();
        //     $dniitem['product_id'] = $pi_item->product_id;
        //     $dniitem['batchcard_id'] = $pi_item->batchcard_id;
        //     $dniitem['quantity'] = $pi_item->remaining_qty_after_cancel;
        //     $dniitem['remaining_qty_after_srn'] = $pi_item->remaining_qty_after_cancel;
        //     $update = fgs_dni_item::where('id','=',$dni_item['id'])->update($dniitem);
        // }
        /*$dni_items = fgs_dni_item::get();
        {
            foreach($dni_items as $dni_item)
            {
                $pi_items = fgs_pi_item_rel::select('fgs_pi_item_rel.item','fgs_pi_item_rel.master')
                            ->leftjoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                            ->where('fgs_pi_item.status','=',1)
                            ->where('fgs_pi_item.cpi_status','=',0)
                            ->where('fgs_pi_item_rel.master','=',$dni_item['pi_id'])
                            ->get();
                //$pi_items = fgs_pi_item_rel::where('master',$dni_item['pi_id'])->get();
                foreach($pi_items as $piitem)
                {
                    $dniitem = fgs_dni_item::where('pi_item_id',$piitem['item'])->first();
                    if(!$dniitem)
                    {
                        //$dniitem = fgs_dni_item_rel::where('item',$piitem['item'])->first();
                        $pi_item = fgs_pi_item::where('id','=',$piitem['item'])->first();
                        $item['pi_id'] = $piitem['master'];
                        $item['pi_item_id'] = $pi_item['id'];
                        $item['product_id'] = $pi_item['product_id'];
                        $item['batchcard_id'] = $pi_item['batchcard_id'];
                        $item['quantity'] = $pi_item['remaining_qty_after_cancel'];
                        $item['remaining_qty_after_srn'] = $pi_item['remaining_qty_after_cancel'];
                        $item['mrn_item_id'] = $pi_item['mrn_item_id'];
                        $item['created_at'] =  date('Y-m-d H:i:s');
                        $dni_item=$this->fgs_dni_item->insert_data($item);
                        if($dni_item){
                            DB::table('fgs_dni_item_rel')->insert(['item'=>$dni_item]);
                        }
                    }

                }
            }
        }*/
        
        
           /*$fgs_dnitem= fgs_dni_item::leftjoin('fgs_dni_item_rel','fgs_dni_item_rel.item','fgs_dni_item.id')
                        ->leftJoin('fgs_dni','fgs_dni.id','fgs_dni_item_rel.master')
                        ->select('fgs_dni_item.id as dni_item_id','fgs_dni_item.pi_id','fgs_dni_item_rel.master')
                        ->where('fgs_dni_item_rel.master','!=',0)
                        ->distinct('fgs_dni_item.pi_id')
                        ->get();
            foreach($fgs_dnitem as $fgs_dni_item)
            {
                $dniItems = fgs_dni_item::where('pi_id',$fgs_dni_item['pi_id'])->get();
                foreach($dniItems as $item)
                {
                    $update = DB::table('fgs_dni_item_rel')->where('item',$item['id'])->update(['master'=>$fgs_dni_item['master']]);
                }
            }*/
        //print_r(json_encode($fgs_dnitem));exit;
        $condition = [];
        $condition =[];
        if($request->srn_no)
        {
            $condition[] = ['fgs_srn.srn_number','like', '%' . $request->srn_no . '%'];
        }
        if($request->dni_no)
        {
            $condition[] = ['fgs_dni.dni_number','like', '%' . $request->dni_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_srn.srn_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_srn.srn_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $srn = $this->fgs_srn->get_all_srn($condition);
       // print_r($srn);exit;
        return view('pages/FGS/SRN/SRN-list',compact('srn'));
       
    }
    public function SRNManualAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['dni_number'] = ['required'];
            $validation['srn_date'] = ['required','date'];
            $validation['location_increase'] = ['required'];
            $validation['dni_date'] = ['required','date'];
           // $validation['file'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $file = $request->file('srn_file');
                if ($file) 
                {
                    $ExcelOBJ = new \stdClass();

                    $path = storage_path() . '/app/' . $request->file('srn_file')->store('temp');

                    $ExcelOBJ->inputFileName = $path;
                    $ExcelOBJ->inputFileType = 'Xlsx';

                    // $ExcelOBJ->filename = 'Book1.xlsx';
                    // $ExcelOBJ->inputFileName = 'C:\xampp7.4\htdocs\mel\sampleData\Book1.xlsx';
                    $ExcelOBJ->spreadsheet = new Spreadsheet();
                    $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
                    $ExcelOBJ->reader->setReadDataOnly(true);
                    $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
                    $no_column = 8;
                    $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
                    //echo $sheet1_column_count;exit;
                    if ($sheet1_column_count == $no_column) {
                        $res = $this->Excelsplitsheet($ExcelOBJ, $request);
                        // print_r($res);exit;
                        if ($res) {
                            $request->session()->flash('success', "You have successfully added a SRN !");
                            return redirect('fgs/SRN-list');
                        } else {
                            $request->session()->flash('error',  "The data already uploaded.");
                            return redirect()->back();
                        }
                    } else {
                        $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                        return redirect()->back();
                    }
                    
                }
            }
        }
        else
        {
            $locations = product_stock_location::get();
            return view('pages/FGS/SRN/SRN-manual-add',compact('locations'));
        }
    }
    public function Excelsplitsheet($ExcelOBJ, $request)
    {
       
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
            $res = $this->insert_srn_items($ExcelOBJ, $request);
            return $res;
        }
    }
    function insert_srn_items($ExcelOBJ, $request)
    {
        $data = [];
        if(date('m')==01 || date('m')==02 || date('m')==03)
        {
            $years_combo = date('y', strtotime('-1 year')).date('y');
        }
        else
        {
            $years_combo = date('y').date('y', strtotime('+1 year'));
        }
        $data['srn_number'] = "SRN-".$this->year_combo_num_gen(DB::table('fgs_srn')->where('fgs_srn.srn_number', 'LIKE', 'SRN-'.$years_combo.'%')->count()); 
        $data['srn_date'] = date('Y-m-d', strtotime($request->srn_date));
        $data['dni_number_manual'] = $request->dni_number;
        $data['dni_date_manual'] =  date('Y-m-d', strtotime($request->dni_date));
        $data['remarks'] = $request->remarks;
        $data['location_increase'] = $request->location_increase;
        $data['created_by']= config('user')['user_id'];
        $data['customer_id'] = $request->customer;
        $data['status']=1;
        $data['created_at'] =date('Y-m-d H:i:s');
        $data['updated_at'] =date('Y-m-d H:i:s');
        $srn_id = $this->fgs_srn->insert_data($data);
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 0 &&  $excelsheet[0]) 
            {
                
                $product_id = DB::table('product_product')->where('sku_code', $excelsheet[0])->pluck('id')->first();
                $batchcard_id = batchcard::where('batch_no', '=', $excelsheet[1])->pluck('batchcard_batchcard.id')->first();
                if ($product_id && $batchcard_id) {
                    $customer = DB::table('customer_supplier')->select('customer_supplier.firm_name', 'zone.zone_name', 'state.state_name')
                                            ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                                            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                                            ->where('customer_supplier.id', '=', $request->customer)->first();
                    if($customer->zone_name!='Export')
                    {
                        if($customer->state_name=='Maharashtra')
                        {
                            $gst = $excelsheet['7'] / 2;
                            $gst_data = inventory_gst::select('inventory_gst.*')->where('inventory_gst.sgst', '=', $gst)->where('inventory_gst.cgst', '=', $gst)->first();
                        }
                        else
                        {
                            $gst = $excelsheet['7'];
                            $gst_data = inventory_gst::select('inventory_gst.*')->where('inventory_gst.igst', '=', $gst)->first();
                        }

                    }
                    //print_r($gst_data);exit;
                   
                    $item = [
                        'product_id' => $product_id,
                        'batchcard_id' => $batchcard_id,
                        'quantity' => $excelsheet[4],
                        'status' => 1,
                        'manufacturing_date' => ($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL,
                        'expiry_date' => ($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ' ',
                        'rate'=>$excelsheet[5],
                        'discount'=>$excelsheet['6'],
                        'gst_id'=>$gst_data->id,
                    ];
                    $item_id = DB::table('fgs_manual_srn_item')
                        ->insertGetId($item);

                    DB::table('fgs_manual_srn_item_rel')
                        ->insert([
                            'master' => $srn_id,
                            'item' => $item_id
                        ]);

                    $product_stock = fgs_product_stock_management::where('product_id',$product_id)->where('stock_location_id',$request->location_increase)->where('batchcard_id',$batchcard_id)->first();
                    if(!empty($product_stock))
                        {
                            $new_stock = $product_stock->quantity+ $excelsheet[4];
                            $res[] = $this->fgs_product_stock_management->update_data(['id'=>$product_stock->id],['quantity'=>$new_stock]);
                        }
                    else
                        {
                            $stock = [
                                "product_id" => $product_id,
                            // "batchcard_id" => $value['batch_no'],
                                "batchcard_id" =>$batchcard_id,
                                "quantity" =>$excelsheet[4],
                                "stock_location_id" => $request->location_increase,
                                'manufacturing_date' => ($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL,
                                'expiry_date' => ($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ' ',
                            ];
                            $this->fgs_product_stock_management->insert_data($stock);
                        }
                }
                else if($product_id && !$batchcard_id)
                {
                    $customer = DB::table('customer_supplier')->select('customer_supplier.firm_name', 'zone.zone_name', 'state.state_name')
                                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                                ->where('customer_supplier.id', '=', $request->customer)->first();
                    if($customer->zone_name!='Export')
                    {
                        if($customer->state_name=='Maharashtra')
                        {
                            $gst = $excelsheet['7'] / 2;
                            $gst_data = inventory_gst::select('inventory_gst.*')->where('inventory_gst.sgst', '=', $gst)->where('inventory_gst.cgst', '=', $gst)->first();
                        }
                        else
                        {
                            $gst = $excelsheet['7'];
                            $gst_data = inventory_gst::select('inventory_gst.*')->where('inventory_gst.igst', '=', $gst)->first();
                        }

                    }
                    $batchcard_id= DB::table('batchcard_batchcard')
                                            ->insertGetId([
                                                "batch_no"=>$excelsheet[1],
                                                "quantity"=>$excelsheet[4],
                                                'product_id'=>$product_id,
                                                "is_trade"=>1
                                            ]);

                    $item = [
                        'product_id' => $product_id,
                        'batchcard_id' => $batchcard_id,
                        'quantity' => $excelsheet[4],
                        'status' => 1,
                        'manufacturing_date' => ($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL,
                        'expiry_date' => ($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ' ',
                        'rate'=>$excelsheet[5],
                        'discount'=>$excelsheet['6'],
                        'gst_id'=>$gst_data->id,
                    ];
                    $item_id = DB::table('fgs_manual_srn_item')
                                        ->insertGetId($item);

                    DB::table('fgs_manual_srn_item_rel')
                    ->insert([
                        'master' => $srn_id,
                        'item' => $item_id
                    ]);

                    $product_stock = fgs_product_stock_management::where('product_id',$product_id)->where('stock_location_id',$request->location_increase)->where('batchcard_id',$batchcard_id)->first();
                    if(!empty($product_stock))
                    {
                        $new_stock = $product_stock->quantity+ $excelsheet[4];
                        $res[] = $this->fgs_product_stock_management->update_data(['id'=>$product_stock->id],['quantity'=>$new_stock]);
                    }
                    else
                    {
                        $stock = [
                            "product_id" => $product_id,
                        // "batchcard_id" => $value['batch_no'],
                            "batchcard_id" =>$batchcard_id,
                            "quantity" =>$excelsheet[4],
                            "stock_location_id" => $request->location_increase,
                            'manufacturing_date' => ($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL,
                            'expiry_date' => ($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ' ',
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                    }
                }
            }
        }
        return $item_id;
    }
    public function SRNAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['dni_number'] = ['required'];
            $validation['srn_date'] = ['required','date'];
            $validation['location_increase'] = ['required'];
            $validation['dni_item_id'] = ['required'];
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
                $dni = fgs_dni::find($request->dni_number);
                $data['srn_number'] = "SRN-".$this->year_combo_num_gen(DB::table('fgs_srn')->where('fgs_srn.srn_number', 'LIKE', 'SRN-'.$years_combo.'%')->count()); 
                $data['srn_date'] = date('Y-m-d', strtotime($request->srn_date));
                $data['dni_id'] = $request->dni_number;
                $data['remarks'] = $request->remarks;
                $data['location_increase'] = $request->location_increase;
                $data['created_by']= config('user')['user_id'];
                $data['customer_id'] = $request->customer;
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $srn_id = $this->fgs_srn->insert_data($data);
                //print_r($dni_item);exit;
                //print_r($request->dni_item_id);
                //print_r($request->srn_qty);
                $i=0;
                foreach ($request->dni_item_id as $dni_item_id) 
                {
                    $dni_item = fgs_dni_item::find($dni_item_id);
                    $srn_item['product_id'] = $dni_item['product_id'];
                    $srn_item['batchcard_id'] = $dni_item['batchcard_id'];
                    $srn_item['dni_item_id'] = $dni_item['id'];
                    $srn_item['mrn_item_id'] = $dni_item['mrn_item_id'];
                    $srn_item['quantity'] = $request->srn_qty[$i];
                    $srn_item['created_at'] =date('Y-m-d H:i:s');
                    $srn_item_id = $this->fgs_srn_item->insert_data($srn_item,$srn_id);
                    /*$maa_stock =  fgs_maa_stock_management::where('product_id',$dni_item['product_id'])->where('batchcard_id',$dni_item['batch_id'])->first();
                    $stock['quantity'] =$maa_stock['quantity']+$request->srn_qty[$i];
                    $maa_stock_management = $this->fgs_maa_stock_management->update_data(['id'=>$maa_stock['id']],$stock);*/
                    $qty_update = $dni_item['remaining_qty_after_srn']-$request->srn_qty[$i];
                    $dni_item_update = fgs_dni_item::where('id',$dni_item['id'])->update(['remaining_qty_after_srn'=>$qty_update]);
                    $product_stock = fgs_product_stock_management::where('product_id',$dni_item['product_id'])->where('stock_location_id',$request->location_increase)->where('batchcard_id',$dni_item['batchcard_id'])->first();
                    if(!empty($product_stock))
                    {
                        $new_stock = $product_stock->quantity+ $request->srn_qty[$i];
                        $res[] = $this->fgs_product_stock_management->update_data(['id'=>$product_stock->id],['quantity'=>$new_stock]);
                    }
                    else
                    {
                        $mrn_item = fgs_mrn_item::where('id',$dni_item['mrn_item_id'])->first();
                        $stock = [
                            "product_id" => $dni_item['product_id'],
                        // "batchcard_id" => $value['batch_no'],
                            "batchcard_id" =>$dni_item['batchcard_id'],
                            "quantity" =>$request->srn_qty[$i],
                            "stock_location_id" => $request->location_increase,
                            "manufacturing_date" =>$mrn_item['manufacturing_date'],
                            "expiry_date" => $mrn_item['expiry_date'],
                        ];
                        $this->fgs_product_stock_management->insert_data($stock);
                    }
                    $i++;
                }
                if($srn_id)
                {
                    $request->session()->flash('success', "You have successfully added a SRN !");
                    return redirect('fgs/SRN-list');
                }
                else
                {
                    $request->session()->flash('error', "SRN insertion is failed. Try again... !");
                    return redirect('fgs/SRN-add');
                }

            }
            else
            {
                return redirect('fgs/SRN-add')->withErrors($validator)->withInput();
            }
        }
        else
        {
            $locations = product_stock_location::get();
            return view('pages/FGS/SRN/SRN-add',compact('locations'));
        }
       
    }
    public function findDNINumberForSRN(Request $request)
    {
        if($request->customer_id)
        {
            $condition[] = ['fgs_dni.customer_id','=', $request->customer_id];
            $data = $this->fgs_dni->find_dni_num_for_srn($condition);
            if($data)
            return $data;
            else
            return 0;
        }
    }

   
    function findDNIInfo(Request $request)
    {
        $condition1[] = ['fgs_dni.id','=',$request->id];
        $dni = $this->fgs_dni->get_single_dni($condition1);
        //return $invoice;
        $dni_items = fgs_dni_item_rel::select('fgs_dni_item.pi_id','fgs_pi.pi_number','fgs_pi.pi_date')
                            ->leftJoin('fgs_dni_item','fgs_dni_item.id','fgs_dni_item_rel.item')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                            ->where('fgs_dni_item_rel.master','=',$request->id)
                            ->distinct('fgs_dni_item_rel.id')
                            ->get();
        foreach($dni_items as $items)
        {
            $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
            'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity as quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number',
            'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id','fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_number','fgs_oef.order_date',
            'order_fulfil.order_fulfil_type','transaction_type.transaction_name','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_product_category.category_name',
            'fgs_pi_item.remaining_qty_after_cancel','fgs_pi_item.id as pi_item_id','product_price_master.mrp','fgs_dni_item.id as dni_item_id','fgs_dni_item.remaining_qty_after_srn')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                            ->leftJoin('fgs_dni_item','fgs_dni_item.pi_item_id','fgs_pi_item.id')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                            ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_grs.oef_id')
                            ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                            ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                            ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                            ->leftjoin('product_price_master','product_price_master.product_id','=','product_product.id')
                            ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_pi_item.mrn_item_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                            ->where('fgs_pi_item_rel.master','=', $items['pi_id'])
                            ->where('fgs_grs.status','=',1)
                            ->where('fgs_pi_item.status','=',1)
                            ->where('fgs_pi_item.remaining_qty_after_cancel','!=',0)
                            ->where('fgs_dni_item.remaining_qty_after_srn','!=',0)
                            ->where('fgs_pi_item.cpi_status','=',0)
                            ->orderBy('fgs_grs_item.id','DESC')
                            ->distinct('fgs_dni_item.id')
                            ->get();
            $items['pi_item'] = $pi_item;
        }
        if($dni_items && $dni)
        {
        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                DNI Number (' . $dni->dni_number . ')
                   </label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
            
                </thead>
                <tbody>
                    <tr>
                        <th>DNI Date</th>
                        <td>' . date('d-m-Y', strtotime($dni->dni_date)) . '</td>
                        <th>Customer</th>
                        <td>'. $dni->firm_name.'</td>
                        
                    </tr>
                    <tr>
                        <th>Zone</th>
                        <td>'. $dni->zone_name.'</td>
                        <th>State</th>
                        <td>'.$dni->state_name.'</td>
                        
                    </tr>
                    <tr>
                        <th>Billing Address</th>
                        <td>'.$dni->billing_address.'</td>
                        <th>Shipping Address</th>
                        <td>'.$dni->shipping_address.'</td>
                    </tr>
                </tbody>
           </table>
           <br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
            //    foreach($dni_items as $dni_item)
            //    {
                    $data .='<div class="table-responsive">
                        <table class="table table-bordered mg-b-0" >
							<thead>
								<tr>
									<th><input type="checkbox" class="item-select-radio  checkall" id="checkall" ></th>
									<th>GRS Number</th>
                                    <th>Product</th>
									<th>Description</th>
									<th>HSN Code</th>
									<th>Batchcard</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <!--th>Discount</th-->
                                    <th>Net Value</th>
                                    <th>Quantity Return</th>
								</tr>
							</thead>
							<tbody id="prbody1">';
                            $i=1;
                            foreach($dni_items as $dni_item)
                            {
                                foreach($dni_item['pi_item'] as $item)
                                {
                                    $data .= '<tr>
                                    <td><input type="checkbox" class="check-dni" name="dni_item_id[]" onclick="enableTextBox(this)" id="dni_item_id" value="'.$item['dni_item_id'].'"></td>
                                    <td>'.$item['grs_number'].'</td>
                                    <td>'.$item['sku_code'].'</td>
                                    <td>'.$item['discription'].'</td>	
                                    <td>'.$item['hsn_code'].'</td>
                                    <td>'.$item['batch_no'].'</td>
                                    <td class="qty">'.$item['remaining_qty_after_srn'].' Nos</td>
                                    <td>'.$item['rate']. '  ' .$item['currency_code'].'</td>
                                    <!--td>'.$item['discount'].'%</td-->
                                    <td>'.($item['rate']*$item['remaining_qty_after_srn'])-(($item['remaining_qty_after_srn']*$item['discount']*$item['rate'])/100).' '.$item['currency_code'].'</td>
                                    <td><input type="number" class="srn_qty" id="srn_qty" name="srn_qty[]" min="1" max="'.$item['remaining_qty_after_srn'].'" value="'.$item['remaining_qty_after_srn'].'" disabled> Nos</td>
                                </tr>';
                                }
                            }
                            $data .= '</tbody>
                            </table>
                            <div class="box-footer clearfix">
                            
                            </div>
                        </div>
                        <br/>';
            //    }
       
            
      $data.= '</div>';
        return $data;
        }
        // else
        // return 0;
    }
    public function SRNitemlist(Request $request)
    {
        $srn_id = $request->srn_id;
        //echo $mis_id;exit;
        $srn_info = fgs_srn::find($request->srn_id);
        $srn_number = $srn_info['srn_number'];
        $condition[] = ['fgs_srn_item_rel.master','=',$request->srn_id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_srn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_srn_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        if($srn_info->dni_number_manual!=NULL)
        {
            $srn_items = DB::table('fgs_manual_srn_item_rel')->select('fgs_manual_srn_item.id as srn_item_id','fgs_manual_srn_item.quantity','product_product.sku_code','product_product.hsn_code','product_product.discription','batchcard_batchcard.batch_no',
            'fgs_manual_srn_item.manufacturing_date','fgs_manual_srn_item.expiry_date','fgs_manual_srn_item.rate','fgs_manual_srn_item.discount','inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst')
                            ->leftJoin('fgs_manual_srn_item','fgs_manual_srn_item_rel.item','fgs_manual_srn_item.id')
                            ->leftjoin('product_product','product_product.id','=','fgs_manual_srn_item.product_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_manual_srn_item.batchcard_id')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_manual_srn_item.gst_id')
                            ->where('fgs_manual_srn_item_rel.master','=',$srn_id)
                            ->distinct('fgs_manual_srn_item.id')
                            ->orderBy('fgs_manual_srn_item.id','ASC')
                            ->paginate(15);
            //print_r(json_encode($srn_items));exit;
            return view('pages/FGS/SRN/SRN-item-list-manual',compact('srn_id','srn_items','srn_number'));
        }
        else
        {
            $srn_items = fgs_srn_item_rel::select('fgs_srn_item.dni_item_id','fgs_srn_item.id as srn_item_id','fgs_pi.pi_number','fgs_pi.pi_date','fgs_pi.id as pi_id','fgs_dni.dni_number','fgs_dni.dni_date',
            'fgs_srn_item.quantity','product_product.sku_code','product_product.hsn_code','product_product.discription','batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date',
            'fgs_oef_item.rate','fgs_oef_item.discount')
                            ->leftJoin('fgs_srn_item','fgs_srn_item_rel.item','fgs_srn_item.id')
                            ->leftJoin('fgs_dni_item','fgs_dni_item.id','fgs_srn_item.dni_item_id')
                            ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','fgs_dni_item.id')
                            ->leftJoin('fgs_dni','fgs_dni.id','fgs_dni_item_rel.master')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                            ->leftjoin('product_product','product_product.id','=','fgs_srn_item.product_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_srn_item.batchcard_id')
                            ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_srn_item.mrn_item_id')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_dni_item.pi_item_id')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                            ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->where('fgs_srn_item_rel.master','=',$request->srn_id)
                            ->distinct('fgs_dni_item.pi_id')
                            ->distinct('fgs_srn_item.id')
                            ->orderBy('fgs_srn_item.id','ASC')
                            ->paginate(15);
            return view('pages/FGS/SRN/SRN-item-list',compact('srn_id','srn_items','srn_number'));
        }
        
      
    }
    public function SRNpdf($srn_id)
    {
        $data['srn'] = $this->fgs_srn->get_single_srn(['fgs_srn.id' => $srn_id]);
        //print_r($data['srn']);exit;
        if($data['srn']->dni_number_manual!=NULL)
        {
            $data['srn_items'] = DB::table('fgs_manual_srn_item_rel')->select('fgs_manual_srn_item.id as srn_item_id','fgs_manual_srn_item.quantity','product_product.sku_code','product_product.hsn_code','product_product.discription','batchcard_batchcard.batch_no',
            'fgs_manual_srn_item.manufacturing_date','fgs_manual_srn_item.expiry_date','fgs_manual_srn_item.rate','fgs_manual_srn_item.discount','inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst')
                            ->leftJoin('fgs_manual_srn_item','fgs_manual_srn_item_rel.item','fgs_manual_srn_item.id')
                            ->leftjoin('product_product','product_product.id','=','fgs_manual_srn_item.product_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_manual_srn_item.batchcard_id')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_manual_srn_item.gst_id')
                            ->where('fgs_manual_srn_item_rel.master','=',$srn_id)
                            ->distinct('fgs_manual_srn_item.id')
                            ->orderBy('fgs_manual_srn_item.id','ASC')
                            ->get();
            //print_r(json_encode($srn_items));exit;
            $pdf = PDF::loadView('pages.FGS.SRN.SRN-manual-pdf-view', $data);
            $pdf->set_paper('A4', 'landscape');
            $pdf->setOptions(['isPhpEnabled' => true]);       

            $file_name = "SRN" . $data['srn']['srn_number'] . "_" . $data['srn']['srn_date'];
            return $pdf->stream($file_name . '.pdf');
        }
        else
        {
            $data['srn_items'] = fgs_srn_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
            'batchcard_batchcard.batch_no','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number','fgs_pi.pi_date',
            'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id','fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_number','fgs_oef.order_date',
            'order_fulfil.order_fulfil_type','transaction_type.transaction_name','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_srn_item.quantity')
                            ->leftJoin('fgs_srn_item','fgs_srn_item_rel.item','fgs_srn_item.id')
                            ->leftJoin('fgs_dni_item','fgs_dni_item.id','fgs_srn_item.dni_item_id')
                            ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','fgs_dni_item.id')
                            ->leftJoin('fgs_dni','fgs_dni.id','fgs_dni_item_rel.master')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                            ->leftjoin('product_product','product_product.id','=','fgs_srn_item.product_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_srn_item.batchcard_id')
                            ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_srn_item.mrn_item_id')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_dni_item.pi_item_id')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                            ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                            ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_grs.oef_id')
                            ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                            ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                            ->where('fgs_srn_item_rel.master','=',$srn_id)
                            ->distinct('fgs_dni_item.pi_id')
                            ->distinct('fgs_srn_item.id')
                            ->orderBy('fgs_srn_item.id','ASC')
                            ->get();
            $pdf = PDF::loadView('pages.FGS.SRN.pdf-view', $data);
            $pdf->set_paper('A4', 'landscape');
            $pdf->setOptions(['isPhpEnabled' => true]);       
                    
            $file_name = "SRN" . $data['srn']['srn_number'] . "_" . $data['srn']['srn_date'];
            return $pdf->stream($file_name . '.pdf');
        }
        
    }

    public function srn_transaction(Request $request)
    {
        $condition=[];
        if($request->srn_no)
        {
            $condition[] = ['fgs_srn.srn_number','like', '%' . $request->srn_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_srn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        $items = fgs_srn_item::select('fgs_srn.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'fgs_srn.srn_number','fgs_srn.srn_date','fgs_srn.created_at as srn_wef','fgs_srn_item.id as srn_item_id')
            ->leftJoin('fgs_srn_item_rel', 'fgs_srn_item_rel.item', '=', 'fgs_srn_item.id')
            ->leftJoin('fgs_srn', 'fgs_srn.id', '=', 'fgs_srn_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_srn_item.product_id')
            //->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_srn_item.batchcard_id')
            //->where('fgs_srn_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_srn_item.status',1)
            //->distinct('fgs_srn_item.id')
            ->orderBy('fgs_srn_item.id','desc')
            ->paginate(15);
            
        return view('pages/fgs/SRN/SRN-transaction-list',compact('items'));
    }
    public function srn_transaction_export(Request $request)
    {
        $condition=[];
        if($request->srn_no)
        {
            $condition[] = ['fgs_srn.srn_number','like', '%' . $request->srn_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_srn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        $items = fgs_srn_item::select('fgs_srn.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'fgs_srn.srn_number','fgs_srn.srn_date','fgs_srn.created_at as srn_wef','fgs_srn_item.id as srn_item_id')
            ->leftJoin('fgs_srn_item_rel', 'fgs_srn_item_rel.item', '=', 'fgs_srn_item.id')
            ->leftJoin('fgs_srn', 'fgs_srn.id', '=', 'fgs_srn_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_srn_item.product_id')
           // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_srn_item.batchcard_id')
            //->where('fgs_srn_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_srn_item.status',1)
            //->distinct('fgs_srn_item.id')
            ->orderBy('fgs_srn_item.id','desc')
            ->get();
            return Excel::download(new FGSsrntransactionExport($items), 'FGS-SRN-transaction' . date('d-m-Y') . '.xlsx');

    }
}
