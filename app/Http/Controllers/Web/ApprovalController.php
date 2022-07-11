<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;
class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
    }

    public function getList() 
    {
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/purchase-requisition-item-list-add-edit-delete/';
        $data = $this->HttpRequest->HttpClient($Request);
        $requisition_items = ($data['response']['purchase_requisition']);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-approvel', compact('requisition_items'));
    }

    public function approve(Request $request) 
    {
            $validation['purchaseRequisitionMasterId'] = ['required'];
            $validation['purchaseRequisitionItemId'] = ['required'];
            $validation['status'] = ['required'];
            $validation['approved_qty'] = ['required'];

            $validator = Validator::make($request->all(), $validation);
            $data = [];
            if(!$validator->errors()->all()) {

                $Request['Method'] = 'POST';
                $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-approval-list-add-edit-delete";
                $Request['param'] = json_encode([
                        'action_type' =>'AddPurchaseRequititionApproval',
                        'purchase_reqisition ' => $request->purchaseRequisitionMasterId,
                        'purchase_reqisition_list' => $request->purchaseRequisitionItemId,
                        'is_approved'=> 1,
                        'quantity '=>$request->approved_qty,
                        'reason'=>$request->reason
                        ]);
                $data = $this->HttpRequest->HttpClient($Request);
                // print_r($data);
                // exit;
                if(!empty($data['response']['success'])){
                    $request->session()->flash('success',  $data['response']['message']);
                    return redirect('inventory/purchase-reqisition/approval');
                 }
                 else 
                 {
                    $request->session()->flash('error',  $data['error']);
                    return redirect('inventory/purchase-reqisition/approval');
                 }
                
            }
    }
}
