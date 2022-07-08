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
        print_r($data);die;
    }

    // Purchase Reqisition Master Add
    public function add_purchase_reqisition(Request $request)
    {
        if ($request->isMethod('post')) {

            $Request['Method'] = 'POST';
            $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-master-list-add-edit-delete/";
            $Request['Request'] = json_encode([
                "action_type" => "AddPurchaseRequititionMaster",
                "pr_no" => "PR-" . date('y') . date('m') . sprintf("%03d", date('d')),
                "requestor" => session('user')['employee_id'] ? session('user')['employee_id'] : 'Requestor 1',
                "date" => date("d-m-Y"),
                "department" => "production",
                "prcsr" => "pr",
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
            print_r($data);die;
        }
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
                "requestor" => session('user')['employee_id'] ? session('user')['employee_id'] : 'Requestor 1',
                "date" => date("d-m-Y"),
                "department" => "Production",
                "prcsr" => "sr",
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
            print_r($data);die;
        }

        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/purchase-requisition-master-list-add-edit-delete/';
        $Request['param'] = ['pr_id' => $request->pr_id];
        $data = $this->HttpRequest->HttpClient($Request);
        print_r($data);die;

    }
        // Purchase Reqisition Master delete
        public function delete_purchase_reqisition(Request $request)
        {
            $Request['Method'] = 'POST';
            $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-master-list-add-edit-delete/";
            $Request['param'] = json_encode([
                "action_type" => "DeletePurchaseRequititionMaster",
                "purchase_requitition_id" => $request->pr_id
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
            print_r($data);die;
          
    
        }

}
