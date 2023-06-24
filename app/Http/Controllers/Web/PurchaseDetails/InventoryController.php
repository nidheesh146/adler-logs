<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Validator;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RequisitionItems;
use App\Models\Department;
use App\Models\User;
use App\Models\PurchaseDetails\inv_purchase_req_master;
use App\Models\inventory_gst;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\currency_exchange_rate;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\PurchaseDetails\inv_purchase_req_item_approve;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
        $this->Department = new Department;
        $this->User = new User;
        $this->inv_purchase_req_master = new inv_purchase_req_master;
        $this->inventory_gst = new inventory_gst;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->inv_supplier = new inv_supplier;
        $this->currency_exchange_rate = new currency_exchange_rate;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        

    }
    function getSingleItem(Request $request){
        $getFilter = $this->inventory_rawmaterial->getSingleDescription(['inventory_rawmaterial.id'=>$request->id]);
        echo json_encode( $getFilter);die;
    }
    function get_description(Request $request){

        $data['draw'] = $request->draw;
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $conditions = [];
        $data['data'] = [];

        if($request->value){
            $conditions[] = ['inventory_rawmaterial.discription','like','%'.$request->value.'%'];
            $getFilterDescription = $this->inventory_rawmaterial->getFilterDescription($conditions,$request->length,$request->start);
            $getFilterDescription1 = $this->inventory_rawmaterial->getFilterDescription1($conditions);

            $data['recordsTotal'] = $getFilterDescription1;
            $data['recordsFiltered'] =  $getFilterDescription1;
            $data['data'] = $getFilterDescription;
        }
       echo json_encode($data);die;
    }

    // Purchase Reqisition Master get list
    public function get_purchase_reqisition(Request $request)
    {
            $condition = []; 
            if ($request->department) {
                $condition[] = ['department.dept_name', 'like', '%'.$request->department.'%'];
            }
            if ($request->pr_no) {
                $condition[] = ['inv_purchase_req_master.pr_no',  'like', '%'.$request->pr_no.'%'];
            }

            if ($request->prsr) {
                $condition[] = ['inv_purchase_req_master.PR_SR', '=', strtolower($request->prsr)];
            }
            if (!$request->prsr) {
                $condition[] = ['inv_purchase_req_master.PR_SR', '=', 'PR'];
            }

            if ($request->from) {
                $condition[] = ['inv_purchase_req_master.date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_purchase_req_master.date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
           
        $data['master']=$this->inv_purchase_req_master->get_inv_purchase_req_master_list($condition);
       
       
        $data['department']= $this->Department->get_dept([]);
        $data['pr_nos'] = $this->inv_purchase_req_master->get_pr_nos();
        // print_r(json_encode($data['master']));
        // exit;
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-list', compact('data'));
       // $data['master']=$this->inv_purchase_req_master->get_inv_purchase_req_master_list();
        //return view('pages/purchase-details/purchase-requisition/purchase-requisition-list', compact('data'));
    }

    // Purchase Reqisition Master Add
    public function add_purchase_reqisition(Request $request)
    {

        if ($request->isMethod('post')) {

            $validation['Date'] = ['required'];
            $validation['Department'] = ['required'];
            //$validation['Type'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
                $datas['requestor_id'] = $request->Requestor;
                if(date('m')==01 || date('m')==02 || date('m')==03)
                {
                    $yearMonth = date('y',strtotime("-1 year")).date('m');
                }
                else
                {
                    $yearMonth = date('y').date('m');
                }
                if($request->prsr=="pr"){
                $datas['pr_no'] = "PR-".$this->num_gen(DB::table('inv_purchase_req_master')->where('pr_no','LIKE', '%PR-'.$yearMonth.'%')->count());
                }
                if($request->prsr=="sr"){
                    $datas['pr_no'] = "SR-".$this->num_gen(DB::table('inv_purchase_req_master')->where('pr_no','LIKE', '%SR-'.$yearMonth.'%')->count());
                }
                $datas['department'] =  $request->Department;
                $datas['date'] =  date('Y-m-d',strtotime($request->Date));
                $datas['PR_SR'] =  $request->prsr;
                $datas['created_at'] =  date('Y-m-d h:i:s');
                $datas['updated_at'] =  date('Y-m-d h:i:s');
                $inv_purchase_num =  $this->inv_purchase_req_master->insertdata($datas);
                if($request->prsr=='pr'){
                    return redirect('inventory/add-purchase-reqisition-item?pr_id='.$inv_purchase_num);
                }
                else
                {
                    return redirect('inventory/add-purchase-reqisition-item?sr_id='.$inv_purchase_num);
                }
            }
            if ($validator->errors()->all()) {
                return redirect("inventory/add-purchase-reqisition/")->withErrors($validator)->withInput();
            }
        }
        $data['users'] = $this->User->get_all_users([]);
        $data['Department'] = $this->Department->get_dept(['status'=>1]);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-add', compact('data'));

    }

    // Purchase Reqisition Master edit
    public function edit_purchase_reqisition(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['Date'] = ['required'];
            $validation['Department'] = ['required'];
            // $validation['PRSR'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
                $datas['requestor_id'] = $request->Requestor;
                $datas['department'] =  $request->Department;
                $datas['date'] =  date('Y-m-d',strtotime($request->Date));
                //$datas['PR_SR'] =  $request->prsr;
                $datas['updated_at'] =  date('Y-m-d h:i:s');
                if($request->pr_id) 
                {
                    $request->session()->flash('success',  "You have successfully updated a  purchase requisition master !");
                    $this->inv_purchase_req_master->updatedata(['master_id'=>$request->pr_id],$datas);
                    return redirect('inventory/get-purchase-reqisition?prsr=pr');
                }
                else 
                {
                    $request->session()->flash('success',  "You have successfully updated a  service requisition master !");
                    $this->inv_purchase_req_master->updatedata(['master_id'=>$request->sr_id],$datas);
                    return redirect('inventory/get-purchase-reqisition?prsr=sr');
                }
            }
            if ($validator->errors()->all()) {
                if($request->pr_id) 
                return redirect("inventory/edit-purchase-reqisition/?pr_id=".$request->pr_id)->withErrors($validator)->withInput();
                else
                return redirect("inventory/edit-service-reqisition/?sr_id=".$request->sr_id)->withErrors($validator)->withInput();
            }
        }
        $data['Department'] = $this->Department->get_dept(['status'=>1]);
        $data['users'] = $this->User->get_all_users([]);
        if($request->pr_id) 
            $data['inv_purchase_req_master'] = $this->inv_purchase_req_master->get_data(['inv_purchase_req_master.status'=>1,'master_id'=>$request->pr_id]);
        else
            $data['inv_purchase_req_master'] = $this->inv_purchase_req_master->get_data(['inv_purchase_req_master.status'=>1,'master_id'=>$request->sr_id]);

        return view('pages/purchase-details/purchase-requisition/purchase-requisition-add', compact('data'));
    }
    // Purchase Reqisition Master delete
    public function delete_purchase_reqisition(Request $request)
    {
        if($request->pr_id)
        {
            $this->inv_purchase_req_master->updatedata(['master_id'=>$request->pr_id],['status'=>2]);
            $request->session()->flash('success',  "You have successfully deleted a  purchase requisition master !");
        }
        
        
       return redirect('inventory/get-purchase-reqisition?prsr=pr');
    }
    // service Reqisition Master delete
    public function delete_service_reqisition(Request $request)
    {
        if($request->sr_id)
        {
            $this->inv_purchase_req_master->updatedata(['master_id'=>$request->sr_id],['status'=>2]);
            $request->session()->flash('success',  "You have successfully deleted a  service requisition master !");
        }
        
        
       return redirect('inventory/get-purchase-reqisition?prsr=sr');
    }

    // Purchase Reqisition item get list
    public function get_purchase_reqisition_item(Request $request)
    {
        if((!$request->pr_id) AND (!$request->sr_id)){
            return response()->view('errors/404', [], 404);
        }
        if($request->pr_id)
        {
            $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->pr_id]);
            $data['item'] = $this->inv_purchase_req_item->getItemdata(['inv_purchase_req_master_item_rel.master'=>$request->pr_id]);
        }
        else 
        {
            $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->sr_id]);
            $data['item'] = $this->inv_purchase_req_item->getItemdata(['inv_purchase_req_master_item_rel.master'=>$request->sr_id]);
        }
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-list', compact('data'));
        
    }
    public function addItems(Request $request){
        $number = count($_POST["Itemcode"]);   
        echo $number;
    }
      // Purchase Reqisition add item 
      public function add_purchase_reqisition_item(Request $request)
      {
        if((!$request->pr_id) && (!$request->sr_id)){
            return response()->view('errors/404', [], 404);
        }
        if ($request->isMethod('post'))
        {
            // $number = count($_POST["Itemcode"]);   
            // echo $number;
            // exit;
            if($request->pr_id)
            {
                $validation['pr_id'] = ['required'];
            }
            else{
                $validation['sr_id'] = ['required'];
            }
           
            $validation['moreItems.*.Itemcode'] = ['required'];
            $validation['moreItems.*.ActualorderQty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                foreach ($request->moreItems as $key => $value) {
                    $Request = [
                                    "item_code" => $value['Itemcode'],
                                    "actual_order_qty"=> $value['ActualorderQty'],
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                    "created_user" =>  config('user')['user_id']   
                                ];
                    if($request->pr_id)
                        $this->inv_purchase_req_item->insert_data($Request,$request->pr_id);
                    else
                    $this->inv_purchase_req_item->insert_data($Request,$request->sr_id);
                }
                // $count = count($_POST["Itemcode"]);
                // for($i=0;$i<$count;$i++)
                // {
                //     if(!empty($_POST['Itemcode'][$i]) && !empty($_POST['ActualorderQty'][$i]))
                //     {
                //         $Request = [
                //             "item_code" => $_POST['Itemcode'][$i],
                //             "actual_order_qty"=> $_POST['ActualorderQty'][$i],
                //             "created_at" => date('Y-m-d H:i:s'),
                //             "updated_at" => date('Y-m-d H:i:s'),
                //             "created_user" =>  config('user')['user_id']   
                //         ];
                //         if($request->pr_id)
                //         $this->inv_purchase_req_item->insert_data($Request,$request->pr_id);
                //         else
                //         $this->inv_purchase_req_item->insert_data($Request,$request->sr_id);
                //     }
                // }
                if($request->pr_id)
                {
                    $request->session()->flash('success',"You have successfully added a purchase requisition item !");
                    return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
                }
                else {
                   
                    $request->session()->flash('success',"You have successfully added a service requisition item !");
                    return redirect('inventory/get-purchase-reqisition-item?sr_id='.$request->sr_id);
                }
            }
            if($validator->errors()->all())
            {
                if($request->pr_id)
                    return redirect("inventory/add-purchase-reqisition-item?pr_id=".$request->pr_id)->withErrors($validator)->withInput();
                else
                    return redirect("inventory/add-purchase-reqisition-item?sr_id=".$request->sr_id)->withErrors($validator)->withInput();
            }
        }

        // if ($request->isMethod('post')) {
        //     if($request->pr_id){
        //     $validation['pr_id'] = ['required'];
        //     }
        //     else{
        //         $validation['sr_id'] = ['required'];
        //     }
        //     $validation['Itemcode'] = ['required'];
        //     $validation['ActualorderQty'] = ['required'];
        //     $validator = Validator::make($request->all(), $validation);

        //     if(!$validator->errors()->all()){
        //         $Request = [
        //             "item_code" => $request->Itemcode,
        //             "actual_order_qty"=> $request->ActualorderQty,
        //             "created_at" => date('Y-m-d H:i:s'),
        //             "updated_at" => date('Y-m-d H:i:s'),
        //             "created_user" =>  config('user')['user_id']   
        //         ];
        //         if($request->pr_id){
        //         $this->inv_purchase_req_item->insert_data($Request,$request->pr_id);
        //         $request->session()->flash('success',"You have successfully added a purchase requisition item !");
        //         return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
        //         }
        //         else {
        //             $this->inv_purchase_req_item->insert_data($Request,$request->sr_id);
        //             $request->session()->flash('success',"You have successfully added a service requisition item !");
        //             return redirect('inventory/get-purchase-reqisition-item?sr_id='.$request->sr_id);
        //         }

        //     }
        //     if($validator->errors()->all()){
        //         if($request->pr_id)
        //             return redirect("inventory/add-purchase-reqisition-item?pr_id=".$request->pr_id)->withErrors($validator)->withInput();
        //         else
        //         return redirect("inventory/add-purchase-reqisition-item?sr_id=".$request->sr_id)->withErrors($validator)->withInput();
        //     }
        // }
        if($request->pr_id)
            $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->pr_id]);
        else
        $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->sr_id]);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-add', compact('data'));
      }

      public function getSGSTandCGST(Request $request)
      {
        $id = $request->id;
        $gst = $this->inventory_gst->get_single_gst(['id'=>$id]);
        return $gst;
     }
  
        //edit  Purchase Reqisition item 
        public function edit_purchase_reqisition_item(Request $request)
        {
            if(!$request->pr_id && !$request->item && !$request->sr_id){
                return redirect('inventory/get-purchase-reqisition');
            } 

            if ($request->isMethod('post')) 
            {
                if($request->pr_id){
                    $validation['pr_id'] = ['required'];
                    }
                    else{
                        $validation['sr_id'] = ['required'];
                    }
                $validation['Itemcode'] = ['required'];
                //$validation['Supplier'] = ['required'];
                // $validation['Currency'] = ['required'];
                // $validation['Rate'] = ['required'];
                // $validation['BasicValue'] = ['required'];
                // $validation['Discount'] = ['required'];
                //$validation['gst'] = ['required'];
                //$validation['Netvalue'] = ['required'];
                //$validation['Remarks'] = ['required'];
                $validation['ActualorderQty'] = ['required'];
                $validator = Validator::make($request->all(), $validation);

                if(!$validator->errors()->all()){
                    $Request = [
                        "item_code" => $request->Itemcode,
                        "actual_order_qty"=> $request->ActualorderQty,
                        "inv_purchase_req_item.updated_at" => date('Y-m-d H:i:s'),
                        "inv_purchase_req_item.created_user" =>  config('user')['user_id']   
                    ];
                    if($request->pr_id)
                    {
                        $this->inv_purchase_req_item->updatedata(['inv_purchase_req_item.requisition_item_id'=>$request->item],$Request);
                        $request->session()->flash('success',"You have successfully edited a purchase requisition item !");
                        return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
                    }
                    else
                    {
                        $this->inv_purchase_req_item->updatedata(['inv_purchase_req_item.requisition_item_id'=>$request->item],$Request);
                        $request->session()->flash('success',"You have successfully edited a service requisition item !");
                        return redirect('inventory/get-purchase-reqisition-item?sr_id='.$request->sr_id);
                    }
                }
                
                if($validator->errors()->all()){
                    if($request->pr_id)
                    return redirect("inventory/edit-purchase-reqisition-item?pr_id=".$request->pr_id)->withErrors($validator)->withInput();
                    else
                    return redirect("inventory/add-purchase-reqisition-item?sr_id=".$request->sr_id)->withErrors($validator)->withInput();                    
                }
            }
            //echo $request->item;exit;
            $datas["item"] = $this->inv_purchase_req_item->getItem(['inv_purchase_req_item.requisition_item_id'=>$request->item]);
            if($request->pr_id)
            $data['master'] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->pr_id]);
            else
            $data['master'] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->sr_id]);
            // $data["currency"] = $this->currency_exchange_rate->get_currency([]);
            // $data['gst'] = $this->inventory_gst->get_gst();
          
          
            return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-edit', compact('data', 'datas'));
            
        }


    // Purchase Reqisition item delete 
    public function delete_purchase_reqisition_item(Request $request)
    {
        if($request->item_id){
            $this->inv_purchase_req_item->updatedata(['requisition_item_id'=>$request->item_id],['status'=>2]);
            $request->session()->flash('success',  "You have successfully deleted a  purchase requisition item !");
        }
        if($request->pr_id)
        return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
        else
        return redirect('inventory/get-purchase-reqisition-item?sr_id='.$request->sr_id);
    }

    function itemcodesearch(Request $request,$itemcode = null){
        if(!$request->q){
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
        $condition[] = ['inventory_rawmaterial.item_code','like','%'.strtoupper($request->q).'%'];
        $data  = $this->inventory_rawmaterial->get_inv_raw_data($condition);
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }

    }

    function suppliersearch(Request $request){
        if(!$request->q){
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
        $data =  $this->inv_supplier->get_supplier_data(strtoupper($request->q));
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }

        


        }

    public function getStatus($pr_item_id)
    {
        $status = inv_purchase_req_item::leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                            ->where('inv_purchase_req_item_approve.pr_item_id','=',$pr_item_id)
                            ->pluck('inv_purchase_req_item_approve.status')
                            ->first();
        return $status;
    }
    public function requisitionItemExport(Request $request)
    {
        $pr_id = $request->pr_id;
        $pr = inv_purchase_req_master::where('master_id','=',$pr_id)->first();
       // $data['item'] = $this->inv_purchase_req_item->getItemdata(['inv_purchase_req_master_item_rel.master'=>$pr_id]);
        return Excel::download(new RequisitionItems($pr_id), 'Requisition-Items-' . $pr['pr_number'] . '.xlsx');
    }

    public function upload_purchas_requesition_item(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            $pr_id = $request->pr_id;
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
            $no_column = 6;
            $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
            if($sheet1_column_count == $no_column)
            {
                 $res = $this->Excelsplitsheet($ExcelOBJ,$pr_id);
                 //print_r($res);exit;
                 if($res)
                 {
                    $request->session()->flash('success',  "Successfully uploaded.");
                    return redirect()->back();
                 }
                 else{
                    $request->session()->flash('error',  "The data already uploaded.");
                    return redirect()->back();
                 }
            }
            else 
            {
                $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                return redirect()->back();
            }
            
            //dd($ExcelOBJ->worksheetData);
            //exit;
        }
    }
    public function Excelsplitsheet($ExcelOBJ, $pr_id)
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
            $res = $this->insert_requisition_items($ExcelOBJ, $pr_id);
            return $res;
        }
    }
    function insert_requisition_items($ExcelOBJ, $pr_id)
    {
        //echo $pr_id;exit;
        $data = [];
        foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) 
        {
            if ($key > 1 &&  $excelsheet[1]) 
            {
                $item = DB::table('inventory_rawmaterial')->where('item_code', $excelsheet[1])->first();
                if($item)
                {
                    $data = [
                        'item_code' =>$item['id'],
                        'actual_order_qty'=>$excelsheet[4],
                        'created'=>date('Y-m-d H:i:s'),
                        'updated'=>date('Y-m-d H:i:s'),
                        

                    ];
                    $this->inv_purchase_req_item->insert_data($data,$pr_id);
                    //$res = DB::table('batchcard_batchcard')->insert($data);
                }
                    
            }
            // if( count($data) > 0){
            // $res = DB::table('batchcard_batchcard')->insert($data);  
            // }   
        }
        return $data;
    
            
    }

}