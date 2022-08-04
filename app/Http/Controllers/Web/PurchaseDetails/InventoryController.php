<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;

use Validator;
use DB;

use App\Models\Department;
use App\Models\PurchaseDetails\inv_purchase_req_master;
use App\Models\inventory_gst;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_supplier;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
        $this->Department = new Department;
        $this->inv_purchase_req_master = new inv_purchase_req_master;
        $this->inventory_gst = new inventory_gst;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->inv_supplier = new inv_supplier;

    }

    // Purchase Reqisition Master get list
    public function get_purchase_reqisition(Request $request)
    {
        $data['master']=$this->inv_purchase_req_master->get_inv_purchase_req_master_list();
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-list', compact('data'));
    }

    // Purchase Reqisition Master Add
    public function add_purchase_reqisition(Request $request)
    {

        if ($request->isMethod('post')) {

            $validation['Date'] = ['required'];
            $validation['Department'] = ['required'];
            $validation['PRSR'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
                $datas['requestor_id'] = config('user')['user_id'];
                $datas['pr_no'] = "PR-".$this->num_gen(DB::table('inv_purchase_req_master')->count());
                $datas['department'] =  $request->Department;
                $datas['date'] =  date('Y-m-d',strtotime($request->Date));
                $datas['PR_SR'] =  $request->PRSR;
                $datas['created_at'] =  date('Y-m-d h:i:s');
                $datas['updated_at'] =  date('Y-m-d h:i:s');
                $inv_purchase_num =  $this->inv_purchase_req_master->insertdata($datas);
                return redirect('inventory/add-purchase-reqisition-item?pr_id='.$inv_purchase_num);
            }
            if ($validator->errors()->all()) {
                return redirect("inventory/add-purchase-reqisition/")->withErrors($validator)->withInput();
            }
        }
        $data['Department'] = $this->Department->get_dept(['status'=>1]);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-add', compact('data'));

    }

    // Purchase Reqisition Master edit
    public function edit_purchase_reqisition(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['Date'] = ['required'];
            $validation['Department'] = ['required'];
            $validation['PRSR'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
                $datas['requestor_id'] = config('user')['user_id'];
                $datas['department'] =  $request->Department;
                $datas['date'] =  date('Y-m-d',strtotime($request->Date));
                $datas['PR_SR'] =  $request->PRSR;
                $datas['updated_at'] =  date('Y-m-d h:i:s');
                $this->inv_purchase_req_master->updatedata(['master_id'=>$request->pr_id],$datas);
                return redirect('inventory/edit-purchase-reqisition?pr_id='.$request->pr_id);
            }
            if ($validator->errors()->all()) {
                return redirect("inventory/edit-purchase-reqisition/?pr_id=".$request->pr_id)->withErrors($validator)->withInput();
            }
        }
        $data['Department'] = $this->Department->get_dept(['status'=>1]);
        $data['inv_purchase_req_master'] = $this->inv_purchase_req_master->get_data(['inv_purchase_req_master.status'=>1,'master_id'=>$request->pr_id]);
    
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-add', compact('data'));
    }
    // Purchase Reqisition Master delete
    public function delete_purchase_reqisition(Request $request)
    {
        if($request->pr_id){
            $this->inv_purchase_req_master->updatedata(['master_id'=>$request->pr_id],['status'=>2]);
            $request->session()->flash('success',  "You have successfully deleted a  purchase requisition master !");
        }
       return redirect('inventory/get-purchase-reqisition');
    }

    // Purchase Reqisition item get list
    public function get_purchase_reqisition_item(Request $request)
    {
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/purchase-requisition-item-list-add-edit-delete/';
        $Request['param'] = ['pr_no_master' => $request->pr_id,
        "no_of_entries"=>15,
        'page'=>$request->page ? $request->page  : 1];


        $data = $this->HttpRequest->HttpClient($Request);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-list', compact('data'));

    }
      // Purchase Reqisition item get list
      public function add_purchase_reqisition_item(Request $request)
      {



        if(!$request->pr_id){
            return redirect('inventory/get-purchase-reqisition');
        }
     
        if ($request->isMethod('post')) {
            $Request['Method'] = 'POST';
            $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-item-list-add-edit-delete/";
    
            $Request['param'] = json_encode([
                "action_type"=>"AddPurchaseRequititionItem",
                "purchase_reqisition"=>$request->pr_id,
                "item_code" => $request->Itemcodehidden,
                "supplier"  => $request->Supplier,
                "hfn_sac"=>"hsn-sac",
                "date" => date('d-m-Y'),
                "requestor" => (session('user')['employee_id'] ? session('user')['employee_id'] : 'Requestor 1'),
                "department" =>  "production",
                "currency"  => $request->Currency ,
                "rate"=> $request->Rate,
                "basic_value"=> $request->BasicValue,
                "discount_percent"=> $request->Discount,
                "discount_value"=> $request->Discount,
                "gst"=> $request->GST,
                "net_value"=>  $request->Netvalue,
                "currency"=>$request->Currency,
                "remarks"=> $request->Remarks,
                "actual_order_qty"=> $request->ActualorderQty
            ]);
            $data = $this->HttpRequest->HttpClient($Request);

            
            if(!empty($data['response']['success'])){
                $request->session()->flash('success',  $data['response']['message']);
                return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
             }
        }

    
        $data['gst'] = $this->inventory_gst->get_gst();
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-add', compact('data'));
  
      }
  
        // Purchase Reqisition item get list
        public function edit_purchase_reqisition_item(Request $request)
        {

            if(!$request->pr_id || !$request->item){
                return redirect('inventory/get-purchase-reqisition');
            } 


            $datas=[];

            if ($request->isMethod('post')) {
                $Request['Method'] = 'POST';
                $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-item-list-add-edit-delete/";
        
                $Request['param'] = json_encode([
                    "action_type"=>"EditPurchaseRequititionItem",
                    "purchase_reqisition_id" =>  $request->pr_id,
                    "purchase_requitition_id"=>$request->item,
                    "hfn_sac"=>"hsn-sac",
                    "item_code" => $request->Itemcodehidden,
                    "supplier"  => $request->Supplier,
                    "date" => date('d-m-Y'),
                    "requestor" => (session('user')['employee_id'] ? session('user')['employee_id'] : 'Requestor 1'),
                    "department" =>  "production",
                    "currency"  => $request->Currency ,
                    "rate"=> $request->Rate,
                    "basic_value"=> $request->BasicValue,
                    "discount_percent"=> $request->Discount,
                    "discount_value"=> $request->Discount,
                    "gst"=> $request->GST,
                    "net_value"=>  $request->Netvalue,
                    "currency"=>$request->Currency,
                    "remarks"=> $request->Remarks,
                    "actual_order_qty"=> $request->ActualorderQty
                ]);
                $data = $this->HttpRequest->HttpClient($Request);
                if(!empty($data['response']['success'])){
                    $request->session()->flash('success',  $data['response']['message']);
                    return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
                 }
            }



            if($request->item){
                $Request['Method'] = 'GET';
                $Request['URL'] = config('app.ApiURL') . '/inventory/purchase-requisition-item-list-add-edit-delete/';
                $Request['param'] = ['pr_id' => $request->item];
                $data = $this->HttpRequest->HttpClient($Request);
// print_r(  $data);die;
                if(!empty($data['response']['purchase_requisition'][0])){
                    $datas = $data['response']['purchase_requisition'][0];
                }else{
                    return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
                }
                // print_r($datas);die;
            }


            $Request['Method'] = 'GET';
            $Request['URL'] = config('app.ApiURL') . '/inventory/gst-add-edit-delete/';
            $Request['param'] = [];
            $data = $this->HttpRequest->HttpClient($Request);


            return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-add', compact('data','datas'));
      
    
        }
     

    // Purchase Reqisition item delete 
    public function delete_purchase_reqisition_item(Request $request)
    {
        if($request->item_id){
        $Request['Method'] = 'POST';
        $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-item-list-add-edit-delete/";
        $Request['param'] = json_encode([
            "action_type" => "DeletePurchaseRequititionItem",
            "purchase_requitition_id" => $request->item_id
        ]);
        $data = $this->HttpRequest->HttpClient($Request);
        if(!empty($data['response']['message']) && $data['response']['success']){
            $request->session()->flash('success',  $data['response']['message']);
        }
      }
        return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
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


}
