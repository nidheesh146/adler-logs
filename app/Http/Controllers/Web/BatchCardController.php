<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use DB;
use Validator;

use App\Models\batchcard;
use App\Models\product;
use App\Models\product_input_material;
use App\Models\batchcard_material;
use App\Models\PurchaseDetails\inv_lot_allocation;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_batchcard_qty_updation_request;

class BatchCardController extends Controller
{
    public function __construct()
    {
        $this->batchcard = new batchcard;
        $this->product = new product;
        $this->product_input_material = new product_input_material;
        $this->batchcard_material = new batchcard_material;
        $this->inv_lot_allocation= new inv_lot_allocation;
        $this->inv_batchcard_qty_updation_request = new inv_batchcard_qty_updation_request;
    }
    public function productsearch(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Product is not valid'], 500); 
        }
        $data =  $this->product->get_product_data(strtoupper($request->q));
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Product is not exist'], 500); 
        }
    }
    public function BatchcardList(Request $request)
    {
        $condition=[];
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->process_sheet) {
            $condition[] = ['batchcard_batchcard.process_sheet_id', 'like', '%' . $request->process_sheet . '%'];
        }
        $data['batchcards'] = $this->batchcard->get_all_batchcard_list($condition);
        foreach($data['batchcards'] as $card)
        {
            $card['material'] = $this->batchcard_material->get_batchcard_material(['batchcard_materials.batchcard_id'=>$card['id']]);
        }
        return view('pages/batchcard/batchcard-list',compact('data'));
    }
    public function BatchcardAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['product'] = ['required'];
            $validation['batchcard'] = ['required'];
            $validation['process_sheet'] = ['required'];
            $validation['sku_quantity'] = ['required'];
            $validation['start_date'] = ['required'];
            $validation['target_date'] = ['required'];
            $validation['description'] = ['required'];
           // $validation['input_material'] = ['required'];
           // $validation['input_material_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all())
            {

                $datas['product_id'] = $request->product;
                $datas['process_sheet_id'] = $request->process_sheet;
                // $datas['input_material'] = $request->input_material;
                // $datas['input_material_qty']=$request->input_material_qty;
                $datas['batch_no'] = $request->batchcard;
                $datas['quantity'] = $request->sku_quantity;
                $datas['start_date'] = date('Y-m-d',strtotime($request->start_date));
                $datas['target_date'] = date('Y-m-d',strtotime($request->target_date));
                $datas['description'] = $request->description;
                $datas['is_active'] = 1;
                $datas['created'] = date('Y-m-d H:i:s');
                $datas['updated'] = date('Y-m-d H:i:s');
                $batchcard =  $this->batchcard->insertdata($datas);
                if($batchcard)
                {
                    $product_inputmaterial_count = product_input_material::where('product_id','=',$request->product)->count();
                    for($i=1;$i<=$product_inputmaterial_count;$i++)
                    {
                        $data['batchcard_id'] = $batchcard;
                        $data['product_inputmaterial_id'] = $_POST['product_inputmaterial_id'.$i];
                        $data['item_id'] = $_POST['rawmaterial_id'.$i];
                        $data['quantity'] = $_POST['qty'.$i];
                        $batchcard_material[] =  $this->batchcard_material->insert_data($data);
                    }
                }
                if(count($batchcard_material)==$product_inputmaterial_count)
                $request->session()->flash('success',  "You have successfully inserted a batchcard !");
                else
                $request->session()->flash('error',  "You have failed to insert a batchcard !");
                return redirect('batchcard/batchcard-add');
            }
            if ($validator->errors()->all()) {
                return redirect("batchcard/batchcard-add")->withErrors($validator)->withInput();
            }
        }
        $batch_no = $this->batchcardNumberGeneration();
        //echo  $batch_no;
        return view('pages/batchcard/batchcard-add',compact('batch_no'));
    }

    public function assemblebatchcardAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['product1'] = ['required'];
            $validation['batchcard'] = ['required'];
            $validation['primary_sku_batchcards'] = ['required'];
            $validation['process_sheet'] = ['required'];
            $validation['sku_quantity'] = ['required'];
            $validation['start_date'] = ['required'];
            $validation['target_date'] = ['required'];
            $validation['description'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            //print_r( $request->primary_sku_batchcards);exit;
            if(!$validator->errors()->all())
            {
                $datas['product_id'] = $request->product1;
                $datas['process_sheet_id'] = $request->process_sheet;
                // $datas['input_material'] = $request->input_material;
                // $datas['input_material_qty']=$request->input_material_qty;
                $datas['batch_no'] = $request->batchcard;
                $datas['quantity'] = $request->sku_quantity;
                $datas['start_date'] = date('Y-m-d',strtotime($request->start_date));
                $datas['target_date'] = date('Y-m-d',strtotime($request->target_date));
                $datas['description'] = $request->description;
                $datas['is_active'] = 1;
                $datas['is_assemble'] = 1;
                $datas['created'] = date('Y-m-d H:i:s');
                $datas['updated'] = date('Y-m-d H:i:s');
                $batchcard =  $this->batchcard->insertdata($datas);
                    foreach($request->primary_sku_batchcards as $card)
                    {
                        $batch =$this->batchcard->get_batchcard(['batchcard_batchcard.id'=> $card]); 
                        $dat2 =[
                            'main_batchcard_id'=>$batchcard,
                            'primary_sku_batchcard_id'=>$card,
                            'quantity'=>$batch->quantity,
                        ];
                        $rel =DB::table('assembly_batchcards')->insert($dat2);
                    }
                
                if(count($dat2))
                $request->session()->flash('success',  "You have successfully inserted a batchcard !");
                else
                $request->session()->flash('error',  "You have failed to insert a batchcard !");
                return redirect('batchcard/batchcard-add');
            }
            if ($validator->errors()->all()) {
                return redirect("batchcard/batchcard-add")->withErrors($validator)->withInput();
            }
        }
    }
    public function getBatchcardUpload()
    {
        return view('pages/batchcard/batchcard-upload');
    }

    public function batchcardUpload(Request $request) 
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
            $no_column = 15;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            if($sheet1_column_count == $no_column)
            {
                 $res = $this->Excelsplitsheet($ExcelOBJ);
                 //print_r($res);exit;
                 if($res)
                 {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect('batchcard/batchcard-upload');
                 }
                 else{
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect('batchcard/batchcard-upload');
                 }
            }
            else 
            {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect('batchcard/batchcard-upload');
            }
            
            //dd($ExcelOBJ->worksheetData);
            //exit;
        }
    }

    public function Excelsplitsheet($ExcelOBJ)
    {
        $ExcelOBJ->SQLdata = [];
        $ExcelOBJ->arrayinc = 0;

        foreach ($ExcelOBJ->worksheetData as $key => $worksheet) 
        {
            $ExcelOBJ->sectionName = '';
            $ExcelOBJ->sheetName = $worksheet['worksheetName'];
            $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
            $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
            $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
           // print_r(json_encode($ExcelOBJ->worksheet));exit;
            $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
            $ExcelOBJ->date_created = date('Y-m-d H:i:s');
            $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
            $res = $this->insert_batchcard_batchcard($ExcelOBJ);
            return $res;
        }
         //print_r($res);exit;

       
    }

    function insert_batchcard_batchcard($ExcelOBJ)
    {
        $data = [];
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 1 &&  $excelsheet[0]) 
            {
                $product = DB::table('product_product')->select(['is_sterile','id'])->where('sku_code', $excelsheet[1])->first();
                $batchcard =  DB::table('batchcard_batchcard')->select(['*'])->where('batch_no', $excelsheet[0])->first();
                if(!($batchcard) && $product)
                {
                    $data = [
                        'batch_no' =>$excelsheet[0],
                        'quantity'=>$excelsheet[10],
                        'description'=>$excelsheet[2],
                        'product_id'=>$product->id,
                        'is_active'=>1,
                        'created'=>date('Y-m-d H:i:s'),
                        'updated'=>date('Y-m-d H:i:s'),
                        'start_date' => ($excelsheet[3]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL,
                        'target_date' => ($excelsheet[9]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[9]))->format('Y-m-d')) : NULL,

                    ];
                    $res = DB::table('batchcard_batchcard')->insert($data);
                }
                    
            }
            // if( count($data) > 0){
            // $res = DB::table('batchcard_batchcard')->insert($data);  
            // }   
        }
        return $data;  
            
    }

    public function findInputMaterials(Request $request)
    {
        $input_materials = product_input_material::select('product_input_material.id','inventory_rawmaterial.id as rawmaterial_id','inventory_rawmaterial.item_code',
        'inventory_rawmaterial.discription','inv_unit.unit_name','product_input_material.quantity')
                                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','product_input_material.item_id')
                                                    ->leftJoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                                    ->where('product_input_material.product_id','=',$request->product_id)
                                                    ->get();
        $lotcards = inv_lot_allocation::select('inv_lot_allocation.id as lot_id','inv_lot_allocation.lot_number')
                                                    ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                                                    ->where('inv_purchase_req_item.Item_code','=', $request->item_id)
                                                    //->where('inv_lot_allocation.available_qty','!=',0)
                                                    ->get();
       // echo $input_materials; exit;
        $data = '<tbody>';
        $i=1;
        foreach( $input_materials as $material)
        {
            $lotcard = inv_lot_allocation::select('inv_lot_allocation.id as lot_id','inv_lot_allocation.lot_number','inv_unit.unit_name','inv_mac_item.accepted_quantity')
                        ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                        ->leftJoin('inv_miq_item','inv_miq_item.lot_number','=','inv_lot_allocation.lot_number')
                        ->leftJoin('inv_mac_item','inv_mac_item.miq_item_id','=','inv_miq_item.id')
                        ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                        ->leftJoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                        ->where('inv_purchase_req_item.Item_code','=', $material->rawmaterial_id)
                        //->where('inv_lot_allocation.available_qty','!=',0)
                        ->orderBy('inv_lot_allocation.id','asc')
                        ->first();
            $data .= '<tr>
                        <td>Item Code<input type="text" class="form-control"  value="'.$material['item_code'].'" readonly>
                            <input type="hidden" name="product_inputmaterial_id'.$i.'" value="'.$material['id'].'">
                            <input type="hidden" name="rawmaterial_id'.$i.'" value="'.$material['rawmaterial_id'].'">';
            if($lotcard)
            {
                $data .='(Lot Number:'.$lotcard['lot_number'].', Quantity: '.$lotcard['accepted_quantity'].' &nbsp;'.$lotcard['unit_name'].')';
            }
                            
            $data .=' </td>
                        <td width="40%">
                            Description<textarea value="" class="form-control" name="description" placeholder="Description">'.$material['discription'].'</textarea>
                        </td>
                        <td>
                            Quantity
                            <div class="input-group mb-3">
                                <input type="text" class="form-control material-qty qty'.$i.'" name="qty'.$i.'" value="'.$material['quantity'].'" required aria-describedby="unit-div1">
                                <input type="hidden" class="materialqty'.$i.'" name="materialqty'.$i++.'" value="'.$material['quantity'].'">
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div1">'.$material['unit_name'].'</span>
                                </div>
                            </div>
                        </td>
                        </tr>';
        }
        $data .='</tbody>';
        return $data;

    }
    function batchcardNumberGeneration()
    {
        $i=1993;
        for($x = 'A'; $x <='Z' & $x !== 'AAA'; $x++)
        {
            $timestamp = strtotime(date('Y'));
            $current_year = idate('Y', $timestamp);
            if(date('m')==01 || date('m')==02 || date('m')==03)
            {
                if($i==((int)$current_year-1))
                {
                    $year_char =$x;
                    
                }
            }else
            {
                if($i==(int)$current_year)
                {
                    $year_char = $x;
                } 
            }
            $i++;
        }
        $m=date('m');
        switch ($m) {
            case 1:        
                $mnth_char = "A";
                break;
            case 2:        
                $mnth_char = "B";
                break;
            case 3:        
                $mnth_char = "C";
                break;
            case 4:        
                $mnth_char = "D";
                break;
            case 5:        
                $mnth_char = "E";
                break;
            case 6:        
                $mnth_char = "F";
                break;
            case 7:        
                $mnth_char = "G";
                break;
            case 8:        
                $mnth_char = "H";
                break;
            case 9:        
                $mnth_char = "I";
                break;
            case 10:        
                $mnth_char = "J";
                break;
            case 11:        
                $mnth_char = "K";
                break;
            case 12:        
                $mnth_char = "L";
                break;        
        }
        
        $structure = $year_char.$mnth_char;
        $count = DB::table('batchcard_batchcard')->where('batchcard_batchcard.batch_no', 'LIKE', $structure.'%')->count();
        //echo $structure;
        if ($count+1 <= 9999)
        {
            $numZero = 4 - strlen($count+1);
            $serial_no=str_repeat('0', $numZero).$count+1;
            $count++;
        }
       $batch_no = $structure.$serial_no;
        return $batch_no;
    }

    public function requestList(Request $request)
    {
        $condition = [];
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if($request->item_code) {
            $condition[] = ['inventory_rawmaterial.item_code','like','%' . $request->item_code . '%'];
        }
        if($request->status)
        {
            if($request->status=='reject')
            $condition[] = ['inv_batchcard_qty_updation_request.status','=',0];
            else
            $condition[] = ['inv_batchcard_qty_updation_request.status','=',$request->status];

        }
        if(!$request->status)
        {
            $condition[] = ['inv_batchcard_qty_updation_request.status','=',2];
        }
        $data['requests'] = inv_batchcard_qty_updation_request::select('inv_batchcard_qty_updation_request.id as request_id','batchcard_batchcard.batch_no','product_product.sku_code',
                                'inventory_rawmaterial.item_code','inv_batchcard_qty_updation_request.sku_qty_to_be_update','inv_batchcard_qty_updation_request.material_qty_to_be_update',
                                'batchcard_batchcard.quantity as sku_qty','batchcard_materials.quantity as material_qty','inv_unit.unit_name','inv_batchcard_qty_updation_request.status','inv_batchcard_qty_updation_request.created_at')
                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','inv_batchcard_qty_updation_request.batchcard_id')
                            ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_batchcard_qty_updation_request.item_id')
                            ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                            ->leftJoin('batchcard_materials','batchcard_materials.id','=','inv_batchcard_qty_updation_request.batchcard_material_id')
                            ->leftJoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                            //->where('inv_batchcard_qty_updation_request.status','=',2)
                            ->where($condition)
                            ->orderBy('inv_batchcard_qty_updation_request.id','desc')
                            ->paginate(15);
        return view('pages/batchcard/request-list',compact('data'));
    }

    public function rejectRequest(Request $request)
    {
        $success = inv_batchcard_qty_updation_request::where('id','=',$request->id)->update(['status'=>0]);
        if($success)
        $request->session()->flash('success', "Request Rejected !");
        else
        $request->session()->flash('error', "Request Rejection Failed !");
        return redirect("batchcard/request-list");
    }

    public function approveRequest(Request $request)
    {
        $updation_request = inv_batchcard_qty_updation_request::where('id','=',$request->id)->first();
        $request_id = $request->id;
        DB::transaction(function() use ($updation_request, $request_id)
        {
            inv_batchcard_qty_updation_request::where('id','=',$request_id)->update(['status'=>1]);
            batchcard::where('batchcard_batchcard.id','=',$updation_request['batchcard_id'])->update(['quantity'=>$updation_request['sku_qty_to_be_update']]);
            batchcard_material::where('id','=',$updation_request['batchcard_material_id'])->update(['quantity'=>$updation_request['material_qty_to_be_update']]);
            $materials = batchcard_material::select('id','item_id','quantity','product_inputmaterial_id')
                            ->where('batchcard_id','=',$updation_request['batchcard_id'])
                            ->where('id','!=',$updation_request['batchcard_material_id'])
                            ->where('quantity','!=',0)
                            ->get();
            foreach( $materials as $item)
            {
                $per_material_qty = product_input_material::where('id','=',$item['product_inputmaterial_id'])->pluck('quantity')->first();
                $batch_material_qty = ($per_material_qty * $updation_request['sku_qty_to_be_update']);
                $update = batchcard_material::where('id','=',$item['id'])->update(['quantity'=>$batch_material_qty]);
            }

        });
        $request->session()->flash('success', "Request Approved !");
        return redirect("batchcard/request-list");

    }
    
}
