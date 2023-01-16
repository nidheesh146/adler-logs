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

class BatchCardController extends Controller
{
    public function __construct()
    {
        $this->batchcard = new batchcard;
        $this->product = new product;
        $this->product_input_material = new product_input_material;
        $this->batchcard_material = new batchcard_material;
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
                $datas['input_material'] = $request->input_material;
                $datas['input_material_qty']=$request->input_material_qty;
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
        return view('pages/batchcard/batchcard-add');
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
                $datas['input_material'] = $request->input_material;
                $datas['input_material_qty']=$request->input_material_qty;
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
        $input_materials = product_input_material::select('product_input_material.id','inventory_rawmaterial.id as rawmaterial_id','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inv_unit.unit_name')
                                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','product_input_material.item_id')
                                                    ->leftJoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                                    ->where('product_input_material.product_id','=',$request->product_id)
                                                    ->get();
       // echo $input_materials; exit;
        $data = '<tbody>';
        $i=1;
        foreach( $input_materials as $material)
        {
            $data .= '<tr>
                        <td>Item Code<input type="text" class="form-control"  value="'.$material['item_code'].'" readonly><input type="hidden" name="product_inputmaterial_id'.$i.'" value="'.$material['id'].'">
                            <input type="hidden" name="rawmaterial_id'.$i.'" value="'.$material['rawmaterial_id'].'">
                        </td>
                        <td width="50%">
                            Description<textarea value="" class="form-control" name="description" placeholder="Description">'.$material['discription'].'</textarea>
                        </td>
                        <td>
                            Quantity
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="qty'.$i++.'" required aria-describedby="unit-div1">
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
    

}
