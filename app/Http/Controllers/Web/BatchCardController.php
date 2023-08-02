<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use DB;
use Validator;
use PDF;
use Picqer;
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
        //$this->SetBatchcardAlloted();
        //$this->SetBatchcardInputmaterial();
        $condition=[];
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }
        if ($request->sku_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->process_sheet) {
            $condition[] = ['product_product.process_sheet_no', 'like', '%' . $request->process_sheet . '%'];
        }
        $data['batchcards'] = $this->batchcard->get_all_batchcard_list($condition);
        foreach($data['batchcards'] as $card)
        {
            $card['material'] = $this->batchcard_material->get_batchcard_material(['batchcard_materials.batchcard_id'=>$card['id']]);
        }
        return view('pages/batchcard/batchcard-list',compact('data'));
    }
    public function BatchcardPrint(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['batchcard_id'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $html = '';
                foreach($request->batchcard_id as $batchcard_id)
                {
                    $batch = $this->batchcard->get_batch_card(['batchcard_batchcard.id' => $batchcard_id]);
                    $material = product_input_material::select('product_input_material.*','material_option1.item_code as item1','material_option1.id as item1_id','material_option2.item_code as item2','material_option2.id as item2_id',
                                'material_option3.item_code as item3','material_option3.id as item3_id')
                                ->leftJoin('inventory_rawmaterial as material_option1','material_option1.id','=','product_input_material.item_id1')
                                ->leftJoin('inventory_rawmaterial as material_option2','material_option2.id','=','product_input_material.item_id2')
                                ->leftJoin('inventory_rawmaterial as material_option3','material_option3.id','=','product_input_material.item_id3')
                                ->where('product_input_material.product_id','=',$batch->product_id)
                                ->first();
                    $prdct = product::find( $batch->product_id);
                    $color =[0,0,0];
                    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                    $batchno_barcode = $generator->getBarcode($batch->batch_no, $generator::TYPE_CODE_128, 1,40, $color);
                    $sku_code_barcode = $generator->getBarcode($prdct->sku_code, $generator::TYPE_CODE_128, 1,70, $color );
                    $view = view('pages/batchcard/batchcard-list-pdf')->with(compact('batch','batchno_barcode','sku_code_barcode','material'));
                    $html .= $view->render();
                    //$pdf = PDF::loadView('pages/batchcard/batchcard-list-pdf', $data);
                    // $pdf->set_paper('A4', 'landscape');
                    // $file_name = "batchcard" . $data['batch']['start_date'] . "_" . $data['batch']['start_date'];
                    // return $pdf->stream($file_name . '.pdf');
                }
                $pdf = PDF::loadHTML($html);            
                $sheet = $pdf->setPaper('a4', 'portrait');
                return $sheet->download('batchcard_topsheets.pdf'); 
                // $pdf = PDF::loadView('pages/batchcard/batchcard-list-pdf', $data);
                // $file_name = "batchcard" . $data['batch']['start_date'] . "_" . $data['batch']['start_date'];
                // return $pdf->stream($file_name . '.pdf');
            }
            if ($validator->errors()->all()) {
                return redirect("batchcard/batchcard-list")->withErrors($validator)->withInput();
            }
        }
    }
    public function getInputMaterial(Request $request)
    {
        $input_materials = product_input_material::select('product_input_material.id','material_option1.item_code as item1','material_option1.id as item1_id','material_option2.item_code as item2','material_option2.id as item2_id',
        'material_option3.item_code as item3','material_option3.id as item3_id')
                                ->leftJoin('inventory_rawmaterial as material_option1','material_option1.id','=','product_input_material.item_id1')
                                ->leftJoin('inventory_rawmaterial as material_option2','material_option2.id','=','product_input_material.item_id2')
                                ->leftJoin('inventory_rawmaterial as material_option3','material_option3.id','=','product_input_material.item_id3')
                                ->where('product_input_material.product_id','=',$request->product_id)
                                ->get();
        //return $input_materials;
        $i=1;
        $data = '<tr>
                    <th>Sl No.</th>
                    <th>Option1</th>
                    <th>Option2</th>
                    <th>Option3
                </tr>';
        foreach($input_materials as $material)
        {
            $data .= '<tr>
                        <th>'.$i++.'
                        <input type="hidden" name="product_inputmaterial_id" value="'.$material['id'].'"></th></th>';
            if($material['item1_id']==0)
            {
                $data.='<th><input type="radio" class="item-select-radio" name="material" value="0">&nbsp; Assembly</th>';
            }
            else
            {
                $data.='<th><input type="radio" class="item-select-radio" name="material" value="'.$material['item1_id'].'">&nbsp; '.$material['item1'].'</th>';
            }
            if($material['item2_id']!=NULL ) 
            {
                if($material['item2_id']==0)
                {
                    $data.='<th><input type="radio" class="item-select-radio" name="material" value="0">&nbsp; Assembly</th>';
                }
                else
                {
                    $data.='<th><input type="radio" class="item-select-radio" name="material" value="'.$material['item2_id'].'">&nbsp; '.$material['item2'].'</th>';
                }
            }
            else
            {
                $data.='<th></th>';
            }
            if($material['item3_id']!=NULL ) 
            {
                if($material['item3_id']==0)
                {
                    $data.='<th><input type="radio" class="item-select-radio" name="material" value="0">&nbsp; Assembly</th>';
                }
                else
                {
                    $data.='<th><input type="radio" class="item-select-radio" name="material" value="'.$material['item3_id'].'">&nbsp; '.$material['item3'].'</th>';
                }
            }
            else
            {
                $data.='<th></th>';
            }

        }
        return $data;
    }
    public function addInputMaterial(Request $request)
    {
        //echo $request->material;exit;
        $data = [
            'batchcard_id'=>$request->batch_id,
            'product_inputmaterial_id'=>$request->product_inputmaterial_id,
            'item_id'=>$request->material,
            'quantity'=>0,
        ];
        $add = $this->batchcard_material->insert_data($data);
        if($add)
        $request->session()->flash('success',  "You have successfully inserted a batchcard input material !");
        else
        $request->session()->flash('error',  "You have failed insertion of batchcard input material !");
        return redirect('batchcard/batchcard-list');
    }   
    public function BatchcardAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['product'] = ['required'];
            $validation['batchcard'] = ['required'];
           // $validation['process_sheet'] = ['required'];
            $validation['sku_quantity'] = ['required'];
            $validation['start_date'] = ['required'];
            $validation['target_date'] = ['required'];
            //$validation['description'] = ['required'];
           // $validation['input_material'] = ['required'];
           // $validation['input_material_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all())
            {
                $is_exist = batchcard::where('batch_no','LIKE','%'.$request->batchcard.'%')->count();
                if($is_exist!=0)
                {
                    $request->session()->flash('error',  "This batchcard already exist.. !");
                    return redirect('batchcard/batchcard-add');
                }
                //print_r($request->all());exit;
                $datas['product_id'] = $request->product;
                $datas['process_sheet_id'] = $request->process_sheet;
                // $datas['input_material'] = $request->input_material;
                // $datas['input_material_qty']=$request->input_material_qty;
                $datas['batch_no'] = $request->batchcard;
                $datas['quantity'] = $request->sku_quantity;
                $datas['start_date'] = date('Y-m-d',strtotime($request->start_date));
                $datas['target_date'] = date('Y-m-d',strtotime($request->target_date));
               // $datas['description'] = $request->description;
                $datas['is_active'] = 1;
                $datas['created'] = date('Y-m-d H:i:s');
                $datas['updated'] = date('Y-m-d H:i:s');
                $batchcard =  $this->batchcard->insertdata($datas);
                if($batchcard)
                {
                    $product_inputmaterial_count = product_input_material::where('product_id','=',$request->product)->where('status','=',1)->count();
                    for($i=1;$i<=$product_inputmaterial_count;$i++)
                    {
                        $data['batchcard_id'] = $batchcard;
                        $data['product_inputmaterial_id'] = $_POST['material'.$i];
                        $data['item_id'] = $_POST['rawmaterial_id'.$i];
                        $data['quantity'] = $_POST['materialqty'.$i];
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
            //$validation['process_sheet'] = ['required'];
            $validation['sku_quantity'] = ['required'];
            $validation['start_date'] = ['required'];
            $validation['target_date'] = ['required'];
            $validation['description'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            //print_r( $request->primary_sku_batchcards);exit;
            if(!$validator->errors()->all())
            {
                $datas['product_id'] = $request->product1;
                //$datas['process_sheet_id'] = $request->process_sheet;
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
            $no_column = 16;
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
        ini_set('max_execution_time', 500);
        $data = [];
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 1 &&  $excelsheet[0]) 
            {
                $product = DB::table('product_product')->select(['is_sterile','id'])->where('sku_code', $excelsheet[1])->first();
                $batchcard =  DB::table('batchcard_batchcard')->select(['*'])->where('batch_no', $excelsheet[0])->first();
                if(!($batchcard) && $product)
                {   
                
                    if(strtolower($excelsheet[6]) == 'assembly')
                    $is_assemble = 1;
                    elseif(strtolower($excelsheet[5]) == 'assembly')
                    $is_assemble = 1;
                    else
                    $is_assemble = 0;

                    $data = [
                        'batch_no' =>$excelsheet[0],
                        'quantity'=>$excelsheet[10],
                        'description'=>$excelsheet[2],
                        'product_id'=>$product->id,
                        'process_sheet_id' => $excelsheet[11],
                        'is_active'=>1,
                        'is_assemble'=>$is_assemble,
                        'created'=>date('Y-m-d H:i:s'),
                        'updated'=>date('Y-m-d H:i:s'),
                        'start_date' => ($excelsheet[3]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL,
                        'target_date' => ($excelsheet[9]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[9]))->format('Y-m-d')) : NULL,

                    ];
                    $res = DB::table('batchcard_batchcard')->insertGetId($data);
                    $input_material =product_input_material::Join('product_product','product_product.id','=','product_input_material.product_id')
                                        ->where('product_product.sku_code','=', $excelsheet[1])->first();
                    // //$input_material=DB::table('product_input_material')->where('id','=',1)->first(); 
                    // print_r($input_material);exit;
                    if($excelsheet[5]!='N/A' || $excelsheet[5]!='NA' || $excelsheet[5]!='Assembly')
                    {
                        $item1 = inventory_rawmaterial::where('item_code',$excelsheet[5])->first();
                        if($item1)
                        $item_id1 = $item1['id'];
                        else
                        $item_id1 = NULL;
                    }
                    elseif($excelsheet[5]!='Assembly')
                    {
                        $item_id1 = 0;
                    }
                    else
                    {
                        $item_id1 = NULL;
                    }
                    if($excelsheet[6]!='N/A' || $excelsheet[6]!='NA')
                    {
                        $item2 = inventory_rawmaterial::where('item_code',$excelsheet[6])->first();
                        if($item2)
                        $item_id2 = $item2['id'];
                        else
                        $item_id2 = NULL;
                    }
                    else 
                    {
                        $item_id2 = NULL;
                    }
                    $item_id3 = NULL;
                    /*if($excelsheet[4]!='N/A' || $excelsheet[4]!='NA')
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
                    }*/
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
                       
                        $input_material = DB::table('product_input_material')->insert($data); 
                    }
                    if(strtolower($excelsheet[5]) != 'assembly')
                    {
                        $item_id = inventory_rawmaterial::where('item_code',$excelsheet[5])->pluck('id')->first();
                        
                        $prdct_input_material_id = product_input_material::where('product_id','=',$product->id)
                                                                        ->where('item_id1','=', $item_id)
                                                                        ->pluck('id')
                                                                        ->where('status','=',1)
                                                                        ->first();
                        $material['item_id'] = $item_id;
                        $material['batchcard_id'] =$res;
                        $material['prdct_input_material_id'] = $prdct_input_material_id;

                    }
                }
                if(($batchcard) && $product)
                {
                   
                    $data = [
                        'is_assemble'=>(strtolower($excelsheet[5]) == 'assembly') ? 1 : 0,
                        //'updated'=>date('Y-m-d H:i:s'),
                    ];
                    $res = DB::table('batchcard_batchcard')->where('id',$batchcard->id)->update($data);
                    if(strtolower($excelsheet[5]) != 'assembly')
                    {
                        $item_id = inventory_rawmaterial::where('item_code',$excelsheet[5])->pluck('id')->first();
                        
                        $prdct_input_material_id = product_input_material::where('product_id','=',$product->id)
                                                                        ->where('item_id1','=', $item_id)
                                                                        ->pluck('id')
                                                                        ->where('status','=',1)
                                                                        ->first();
                        $material['item_id'] = $item_id;
                        $material['batchcard_id'] = $batchcard->id;
                        $material['product_inputmaterial_id'] = $prdct_input_material_id;
                        $batchcard_material = DB::table('batchcard_materials')->insert($material); 

                    }
                }
                    
            }
            // if( count($data) > 0){
            // $res = DB::table('batchcard_batchcard')->insert($data);  
            // }   
        }
        return $data;  
            
    }
    // function insert_batchcard_batchcard($ExcelOBJ)
    // {
    //     $data = [];
    //     foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
    //     {
    //         if ($key > 1 &&  $excelsheet[0]) 
    //         {
    //             $product = DB::table('product_product')->select(['is_sterile','id'])->where('sku_code', $excelsheet[1])->first();
    //             $batchcard =  DB::table('batchcard_batchcard')->select(['*'])->where('batch_no', $excelsheet[0])->first();
    //             if(!($batchcard) && $product)
    //             {
    //                 $data = [
    //                     'batch_no' =>$excelsheet[0],
    //                     'quantity'=>$excelsheet[10],
    //                     'description'=>$excelsheet[2],
    //                     'product_id'=>$product->id,
    //                     'is_active'=>1,
    //                     'created'=>date('Y-m-d H:i:s'),
    //                     'updated'=>date('Y-m-d H:i:s'),
    //                     'start_date' => ($excelsheet[3]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : NULL,
    //                     'target_date' => ($excelsheet[9]!="") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[9]))->format('Y-m-d')) : NULL,

    //                 ];
    //                 $res = DB::table('batchcard_batchcard')->insert($data);
    //             }
                    
    //         }
    //         // if( count($data) > 0){
    //         // $res = DB::table('batchcard_batchcard')->insert($data);  
    //         // }   
    //     }
    //     return $data;
    
            
    // }

    public function findInputMaterials(Request $request)
    {
        $input_materials = product_input_material::select('product_input_material.id','inventory_rawmaterial.id as material_id1','material2.id as material_id2','material3.id as material_id3','inventory_rawmaterial.item_code as item_code1',
        'inventory_rawmaterial.discription as description1','material2.discription as description2','material3.discription as description3','product_input_material.quantity1','material2.item_code as item_code2','product_input_material.quantity2',
        'material3.item_code as item_code3','product_input_material.quantity3','inv_unit.unit_name as unit1','inv_unit2.unit_name as unit2','inv_unit3.unit_name as unit3','product_input_material.item_id1','product_input_material.item_id2','product_input_material.item_id3')
                                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','product_input_material.item_id1')
                                                    ->leftJoin('inventory_rawmaterial as material2','material2.id','=','product_input_material.item_id2')
                                                    ->leftJoin('inventory_rawmaterial as material3','material3.id','=','product_input_material.item_id3')
                                                    ->leftJoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                                    ->leftJoin('inv_unit as inv_unit2', 'inv_unit2.id','=', 'material2.issue_unit_id')
                                                    ->leftJoin('inv_unit as inv_unit3', 'inv_unit3.id','=', 'material3.issue_unit_id')
                                                    ->where('product_input_material.product_id','=',$request->product_id)
                                                    ->where('product_input_material.status','=',1)
                                                    ->orderBy('product_input_material.id','asc')
                                                    ->get();
        $lotcards = inv_lot_allocation::select('inv_lot_allocation.id as lot_id','inv_lot_allocation.lot_number')
                                                    ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                                                    ->where('inv_purchase_req_item.Item_code','=', $request->item_id)
                                                    //->where('inv_lot_allocation.available_qty','!=',0)
                                                    ->get();
        //echo $input_materials; exit;
        $data = '<tbody>';
        $i=1;
        foreach( $input_materials as $material)
        {

            $data .= '<tr>
                        <th>Input Material Option1</th>
                        <th>Input Material Option2</th>
                        <th>Input Material Option3</th>
                    </tr>
                    <tr>';
            if($material['item_id1']==0)
            {
                $data .= '<td><input type="radio" class="item-select-radio" checked name="material'.$i.'" value="'.$material['id'].'">Assembly<br/>
                            <input type="hidden" name="rawmaterial_id'.$i.'" value="0">
                            <input type="hidden" class="materialqty materialqty'.$i.'" name="materialqty'.$i.'" value="0">';

            }
            else
            {
                $data .= '<input type="hidden" class="input_material_qty input_material_qty'.$i.'" name="input_material_qty'.$i.'" value="">
                        <td>
                        <input type="radio" class="item-select-radio" checked name="material'.$i.'" value="'.$material['id'].'"><br/>
                        Item Code<input type="text" class="form-control"  value="'.$material['item_code1'].'" readonly>
                        <input type="hidden" name="product_inputmaterial_id'.$i.'" value="'.$material['id'].'">
                            <input type="hidden" name="rawmaterial_id'.$i.'" value="'.$material['material_id1'].'">
                            
                            Quantity
                            <div class="input-group mb-3">
                                <input type="text" class="form-control material-qty qty'.$i.'" name="qty'.$i.'" value="'.$material['quantity1'].'" required aria-describedby="unit-div1">
                                <input type="hidden" class="materialqty materialqty'.$i.'" name="materialqty'.$i.'" value="'.$material['quantity1'].'">
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div1">'.$material['unit1'].'</span>
                                </div>
                            </div>';
            }
            // $lotcard = $this->lotcard_item_availability($material['material_id1']);
            // if($lotcard)
            // {
            //     $data .='(Lot Number:'.$lotcard['lot_number'].', Quantity: '.$lotcard['accepted_quantity'].' &nbsp;'.$lotcard['unit_name'].')';
            // }
            if($material['item_id2']!=NULL ) 
            {      
                if($material['item_id2']==0)
                {
                    $data .= '<td><input type="radio" class="item-select-radio"  name="material'.$i.'" value="'.$material['id'].'">Assembly<br/>
                                        <input type="hidden" name="rawmaterial_id'.$i.'" value="0">
                                        <input type="hidden" class="materialqty materialqty'.$i.'" name="materialqty'.$i.'" value="0">';
                }   
                else
                {    
                $data .=' </td>
                            <td>
                            <input type="radio" class="item-select-radio" name="material'.$i.'" value="'.$material['id'].'"><br/>
                            Item Code<input type="text" class="form-control"  value="'.$material['item_code2'].'" readonly>
                            <input type="hidden" name="product_inputmaterial_id'.$i.'" value="'.$material['id'].'">
                            <input type="hidden" name="rawmaterial_id'.$i.'" value="'.$material['material_id2'].'">
                            Item Description<textarea value="" class="form-control" name="description" placeholder="Description" readonly>'.$material['description2'].'</textarea>
                            Quantity
                            <div class="input-group mb-3">
                                <input type="text" class="form-control material-qty qty'.$i.'" name="qty'.$i.'" value="'.$material['quantity2'].'" required aria-describedby="unit-div1" >
                                <input type="hidden" class="materialqty materialqty'.$i.'" name="materialqty'.$i.'" value="'.$material['quantity2'].'">
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div1">'.$material['unit2'].'</span>
                                </div>
                            </div>';
                }
                // $lotcard2 = $this->lotcard_item_availability($material['material_id2']);
                // if($lotcard2)
                // {
                //     $data .='(Lot Number:'.$lotcard2['lot_number'].', Quantity: '.$lotcard2['accepted_quantity'].' &nbsp;'.$lotcard2['unit_name'].')';
                // }
            }
            else
            {
                $data .='</td><td> <div style="margin-top: 35%;"><div class="alert alert-success success" style="width: 100%;"> No alternative raw material exist..</div>';
            }
            if($material['item_code3']) 
            {
                $data .=' </td>
                            <td>
                            <input type="radio" class="item-select-radio"  name="material'.$i.'" value="'.$material['id'].'"><br/>
                            Item Code<input type="text" class="form-control"  value="'.$material['item_code3'].'" readonly>
                            <input type="hidden" name="product_inputmaterial_id'.$i.'" value="'.$material['id'].'">
                            <input type="hidden" name="rawmaterial_id'.$i.'" value="'.$material['material_id3'].'">
                            Item Description<textarea value="" class="form-control" name="description" placeholder="Description" readonly>'.$material['description3'].'</textarea>
                            Quantity
                            <div class="input-group mb-3">
                                <input type="text" class="form-control material-qty qty'.$i.'" name="qty'.$i.'" value="'.$material['quantity3'].'" required aria-describedby="unit-div1" >
                                <input type="hidden" class="materialqty materialqty'.$i.'" name="materialqty'.$i.'" value="'.$material['quantity3'].'">
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div1">'.$material['unit3'].'</span>
                                </div>
                            </div>';
                // $lotcard3 = $this->lotcard_item_availability($material['material_id3']);
                // if($lotcard3)
                // {
                //     $data .='(Lot Number:'.$lotcard3['lot_number'].', Quantity: '.$lotcard3['accepted_quantity'].' &nbsp;'.$lotcard3['unit_name'].')';
                // }
            }
            else
            {
                $data .='</td><td><div style="margin-top: 35%;"><div class="alert alert-success success" style="width: 100%;"> No alternative raw material exist..</div></div>';
            }
            $data .='</td><tr/><tr><td></td><td></td><td></td></tr>';
            $i++;
        }
        $data .='</tbody>';
        return $data;

    }
    function lotcard_item_availability($material_id)
    {
        $lotcard = inv_lot_allocation::select('inv_lot_allocation.id as lot_id','inv_lot_allocation.lot_number','inv_unit.unit_name','inv_mac_item.accepted_quantity')
                    ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                    ->leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_lot_allocation.si_invoice_item_id')
                    ->leftJoin('inv_miq_item','inv_miq_item.lot_number','=','inv_lot_allocation.lot_number')
                    ->leftJoin('inv_mac_item','inv_mac_item.invoice_item_id','=','inv_lot_allocation.si_invoice_item_id')
                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftJoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->where('inv_purchase_req_item.Item_code','=', $material_id)
                    //->where('inv_lot_allocation.available_qty','!=',0)
                    ->orderBy('inv_lot_allocation.id','asc')
                    ->first();
        return $lotcard;
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
    public function BatchCardpdf($batch_id)
    { 
        $data['batch'] = $this->batchcard->get_batch_card(['batchcard_batchcard.id' => $batch_id]);
        //$data['material'] = $this->product_input_material->get_batchcard_material_product(['batchcard_materials.batchcard_id'=>$batch_id]);
        $data['material'] = product_input_material::select('product_input_material.*','material_option1.item_code as item1','material_option1.id as item1_id','material_option2.item_code as item2','material_option2.id as item2_id',
        'material_option3.item_code as item3','material_option3.id as item3_id')
                                ->leftJoin('inventory_rawmaterial as material_option1','material_option1.id','=','product_input_material.item_id1')
                                ->leftJoin('inventory_rawmaterial as material_option2','material_option2.id','=','product_input_material.item_id2')
                                ->leftJoin('inventory_rawmaterial as material_option3','material_option3.id','=','product_input_material.item_id3')
                                ->where('product_input_material.product_id','=',$data['batch']->product_id)
                                ->first();
        //print_r($data['material']);exit;
        $prdct = product::find( $data['batch']->product_id);
        $color =[0,0,0];
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $data['batchno_barcode'] = $generator->getBarcode($data['batch']->batch_no, $generator::TYPE_CODE_128, 1,40, $color);
        $data['sku_code_barcode'] = $generator->getBarcode($prdct->sku_code, $generator::TYPE_CODE_128, 1,70, $color );
        $pdf = PDF::loadView('pages/batchcard/batchcard-list-pdf', $data);
        // $pdf->set_paper('A4', 'landscape');
        $file_name = "batchcard" . $data['batch']['start_date'] . "_" . $data['batch']['start_date'];
        return $pdf->stream($file_name . '.pdf');
    }





    public function SetBatchcardAlloted()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName ='C:\xampp\htdocs\RM_status.xlsx';
        $ExcelOBJ->aircraft = 'B737-MAX';
        $ExcelOBJ->spreadsheet = new Spreadsheet();
        $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
        $ExcelOBJ->reader->setReadDataOnly(true);
        $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
        $this->SetBatchcardAllotedExcelsplitsheet($ExcelOBJ);
        exit;
    }
    public function SetBatchcardAllotedExcelsplitsheet($ExcelOBJ)
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
            $this->update_batchcard_alloted($ExcelOBJ);  
            die("done");
        }
        exit('done');
    }
    function update_batchcard_alloted($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 0 &&  $excelsheet[0]) 
             {
                $product = DB::table('product_product')->select(['is_sterile','id'])->where('sku_code', $excelsheet[1])->first();
                $batchcard =  DB::table('batchcard_batchcard')->select(['*'])->where('batch_no', $excelsheet[0])->first();
                if($product && $batchcard)
                {
                    if( $excelsheet[3]=='Yes')
                    {
                        $data = ['is_alloted'=>1];
                    DB::table('batchcard_batchcard')->where('id', $batchcard->id)->update($data);
                    }
                    else
                    {
                        $dat = ['is_alloted'=>0];
                        DB::table('batchcard_batchcard')->where('id',$batchcard->id)->update($dat);
                    }
                }
             }
        }
    }

    public function SetBatchcardInputmaterial()
    {
        $ExcelOBJ = new \stdClass();
        $ExcelOBJ->inputFileType = 'Xlsx';
        $ExcelOBJ->filename = 'SL-1-01.xlsx';
        //$ExcelOBJ->inputFileName = '/Applications/XAMPP/xamppfiles/htdocs/mel/sampleData/simple/15-09-2022/Top sheet creater_BAtch card to sheet 11SEPT (1).xlsx';
        $ExcelOBJ->inputFileName ='C:\xampp\htdocs\batchmaterial.xlsx';
        $ExcelOBJ->aircraft = 'B737-MAX';
        $ExcelOBJ->spreadsheet = new Spreadsheet();
        $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
        $ExcelOBJ->reader->setReadDataOnly(true);
        $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
        $this->SetBatchcardInputMaterialExcelsplitsheet($ExcelOBJ);
        exit;
    }
    public function SetBatchcardInputMaterialExcelsplitsheet($ExcelOBJ)
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
            $this->set_batchcard_inputmaterial($ExcelOBJ);  
            die("done");
        }
        exit('done');
    }
    public function set_batchcard_inputmaterial($ExcelOBJ)
    {
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 0 &&  $excelsheet[0]) 
             {
                $product = DB::table('product_product')->select(['is_sterile','id'])->where('sku_code', $excelsheet[1])->first();
                $batchcard =  DB::table('batchcard_batchcard')->select(['*'])->where('batch_no', $excelsheet[0])->first();
                if($product && $batchcard)
                {
                    $input_material = DB::table('inventory_rawmaterial')->where('item_code', $excelsheet[3])->first();
                    if($input_material)
                    {
                        $prdct_inputmaterial = DB::table('product_input_material')
                                                    ->where('product_id','=',$product->id)
                                                    ->orWhere('item_id1','=',$input_material->id)
                                                    ->orWhere('item_id2','=',$input_material->id)
                                                    ->orWhere('item_id3','=',$input_material->id)
                                                    ->pluck('id')
                                                    ->first();
                        $data = [
                            'batchcard_id' =>$batchcard->id,
                            'quantity'=>0,
                            'product_inputmaterial_id'=>$prdct_inputmaterial,
                            'item_id'=>$input_material->id,
                        ];
                        $res = DB::table('batchcard_materials')->insert($data);
                    }
                    
                }
             }
        }
    }

    public function Batchcardedit(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $validation['product'] = ['required'];
            $validation['batchcard'] = ['required'];
            $validation['sku_quantity'] = ['required'];
            $validation['start_date'] = ['required'];
            $validation['target_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                //print_r($request->all());exit;
                $datas['product_id'] = $request->product;
                $datas['process_sheet_id'] = $request->process_sheet;
                $datas['batch_no'] = $request->batchcard;
                $datas['quantity'] = $request->sku_quantity;
                $datas['start_date'] = date('Y-m-d',strtotime($request->start_date));
                $datas['target_date'] = date('Y-m-d',strtotime($request->target_date));
                //$datas['is_active'] = 1;
               // $datas['updated'] = date('Y-m-d H:i:s');
                $batchcard =  $this->batchcard->update_data(['batchcard_batchcard.id'=>$request->id],$datas);
                if($batchcard)
                {
                    $product_inputmaterial_count = product_input_material::where('product_id','=',$request->product)->where('status','=',1)->count();
                    for($i=1;$i<=$product_inputmaterial_count;$i++)
                    {
                        //$data['batchcard_id'] = $request->id;
                        //$data['product_inputmaterial_id'] = $_POST['material'.$i];
                        $data['item_id'] = $_POST['material'.$i];
                        $data['quantity'] = $_POST['materialqty'.$i];
                        $batchcard_material[] =  $this->batchcard_material->update_data(['batchcard_id'=>$request->id,'product_inputmaterial_id'=>$_POST['material'.$i]],$data);
                    }
                }
                //if(count($batchcard_material)==$product_inputmaterial_count)
                $request->session()->flash('success',  "You have successfully updated a batchcard !");
                // else
                //  $request->session()->flash('error',  "You have failed to insert a batchcard !");
                return redirect('batchcard/batchcard-add');
            }
            if ($validator->errors()->all()) {
                return redirect("batchcard/batchcard-add")->withErrors($validator)->withInput();
            }

        }
        else
        {
            $products = product::select('id','sku_code')->where('is_active','=',1)->get();
            $batchcard = batchcard::find($request->id);
            $input_materials = product_input_material::select('product_input_material.id','material_option1.item_code as item_code1','material_option1.id as item1_id','material_option2.item_code as item_code2','material_option2.id as item2_id',
                        'material_option3.item_code as item_code3','material_option3.id as item3_id','product_input_material.quantity1','product_input_material.quantity1','product_input_material.quantity1')
                                                ->leftJoin('inventory_rawmaterial as material_option1','material_option1.id','=','product_input_material.item_id1')
                                                ->leftJoin('inventory_rawmaterial as material_option2','material_option2.id','=','product_input_material.item_id2')
                                                ->leftJoin('inventory_rawmaterial as material_option3','material_option3.id','=','product_input_material.item_id3')
                                                ->where('product_input_material.product_id','=',$batchcard->product_id)
                                                ->get();
            return view('pages/batchcard/batchcard-edit',compact('batchcard','products','input_materials'));
        }

        
    }
  
    
}
