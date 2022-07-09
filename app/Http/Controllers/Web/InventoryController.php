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
                "pr_no" => "PR-" . date('y') . date('m') . sprintf("%03d", date('d')),
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
                "pr_no" => "PR-" . date('y') . date('m') . sprintf("%03d", date('d')),
                "requestor" =>  $request->Requestor,
                "date" =>$request->Date,
                "department" => $request->Department,
                "prcsr" => $request->PRSR,
            ]);
            
            $data = $this->HttpRequest->HttpClient($Request);
            if(!empty( $data['response']['success'])){
                $request->session()->flash('success',  $data['response']['message']);
                return redirect('inventory/edit-purchase-reqisition?pr_id='.$request->pr_id);
             }

            print_r($data);die;
          
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
        $Request['param'] = ['pr_id' => $request->pr_id];
        $data = $this->HttpRequest->HttpClient($Request);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-list', compact([]));

    }
      // Purchase Reqisition item get list
      public function add_purchase_reqisition_item(Request $request)
      {

          return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-add', compact([]));
  
      }
  
        // Purchase Reqisition item get list
        public function edit_purchase_reqisition_item(Request $request)
        {

            return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-add', compact([]));
    
        }
     

    // Purchase Reqisition item delete 
    public function delete_purchase_reqisition_item(Request $request)
    {
        $Request['Method'] = 'POST';
        $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-item-list-add-edit-delete/";
        $Request['param'] = json_encode([
            "action_type" => "DeletePurchaseRequititionItem",
            "purchase_requitition_id" => $request->pr_id
        ]);
        $data = $this->HttpRequest->HttpClient($Request);
        print_r($data);die;
    }



}
