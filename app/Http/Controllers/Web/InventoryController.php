<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
    }

    // Purchase Reqisition Master get list
    public function get_purchase_reqisition(Request $request)
    {
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/purchase-requisition-master-list-add-edit-delete/';
        $data = $this->HttpRequest->HttpClient($Request);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-list', compact('data'));
    }

    // Purchase Reqisition Master Add
    public function add_purchase_reqisition(Request $request)
    {
        $data = [];
        if ($request->isMethod('post')) {
            $Request['Method'] = 'POST';
            $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-master-list-add-edit-delete/";
            $Request['param'] = json_encode([
                "action_type" => "AddPurchaseRequititionMaster",
               // "pr_no" => "PR-" . date('y') . date('m') . sprintf("%03d", date('d')),
                "requestor" => $request->Requestor,//session('user')['employee_id'] ? session('user')['employee_id'] : 'Requestor 1',
                "date" => $request->Date,
                "department" => $request->Department,
                "prcsr" => $request->PRSR,
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
            if(!empty( $data['response']['purchase_requisition_id'])){
               return redirect('inventory/add-purchase-reqisition-item?pr_id='.$data['response']['purchase_requisition_id']);
            }
        }
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-add', compact('data'));

    }

    // Purchase Reqisition Master edit
    public function edit_purchase_reqisition(Request $request)
    {
        $Request['Method'] = 'POST';
        $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-master-list-add-edit-delete/";

        if ($request->isMethod('post')) {
            $Request['param'] = json_encode([
                "action_type" => "EditPurchaseRequititionMaster",
                "purchase_requitition_id" => $request->pr_id,
                 // "pr_no" => "PR-" . date('y') . date('m') . sprintf("%03d", date('d')),
                "requestor" =>  $request->Requestor,
                "date" =>$request->Date,
                "department" => $request->Department,
                "prcsr" => $request->PRSR,
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
          //  print_r($data );die;
            if(!empty( $data['response']['success'])){
                $request->session()->flash('success',  $data['response']['message']);
                return redirect('inventory/edit-purchase-reqisition?pr_id='.$request->pr_id);
             }
        }
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/purchase-requisition-master-list-add-edit-delete/';
        $Request['param'] = ['pr_id' => $request->pr_id];
        $data = $this->HttpRequest->HttpClient($Request);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-add', compact('data'));


    }
    // Purchase Reqisition Master delete
    public function delete_purchase_reqisition(Request $request)
    {
        if($request->pr_id){
        $Request['Method'] = 'POST';
        $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-master-list-add-edit-delete/";
        $Request['param'] = json_encode([
            "action_type" => "DeletePurchaseRequititionMaster",
            "purchase_requitition_id" => $request->pr_id
        ]);
        $data = $this->HttpRequest->HttpClient($Request);
        if(!empty($data['response']['message']) && $data['response']['success']){
            $request->session()->flash('success',  $data['response']['message']);
        }
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
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/gst-add-edit-delete/';
        $Request['param'] = [];
        $data = $this->HttpRequest->HttpClient($Request);
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

    function itemcodesearch($itemcode = null){
        if(!$itemcode){
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/rawmaterial-list-add-edit-delete/';
        $Request['param'] = ['item_code' => $itemcode];
        $data = $this->HttpRequest->HttpClient($Request);
        if(!empty($data['response']['raw_materials'][0])){
            return response()->json($data['response']['raw_materials'][0], 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }

    }

    function suppliersearch(Request $request){

        if(!$request->q){
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
    
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/supplier-add-edit-delete/';
        $Request['param'] = ['supplier' => $request->q];
        $data = $this->HttpRequest->HttpClient($Request);
        if(!empty($data['response']['suppliers'][0])){
            foreach($data['response']['suppliers']  as $itemcode){
                $string[] = ['id'=>$itemcode['id'],'text'=>$itemcode['vendor_name']];
            }
            return response()->json($string, 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }

    }


}
