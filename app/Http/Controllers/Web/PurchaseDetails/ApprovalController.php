<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\PurchaseDetails\inv_purchase_req_item;

use Validator;
class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->inv_purchase_req_item = new inv_purchase_req_item;
    }

    public function getList(Request $request) 
    {

        $data['inv_purchase'] = $this->inv_purchase_req_item->getdata_approved([]);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-approvel', compact('data'));
    }

    public function approve(Request $request) 
    {
            $validation['purchaseRequisitionItemId'] = ['required'];
            $validation['status'] = ['required'];
            $validation['reason'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) {
                if($request->status == 1  && !$request->approved_qty){
                    $validator->errors()->add('some_field', 'approved qty is empty !');
                }
            }
            if(!$validator->errors()->all()) {
                $data = ['inv_purchase_req_item_approve.approved_qty'=>$request->approved_qty,
                         'inv_purchase_req_item_approve.status'=>$request->status,
                         'inv_purchase_req_item_approve.remarks'=>$request->reason,
                         'inv_purchase_req_item_approve.created_user'=>config('user')['user_id'],
                         'inv_purchase_req_item_approve.updated_at'=>date('Y-m-d H:i:s')];

                         $this->inv_purchase_req_item->updatedata(['inv_purchase_req_item_approve.pr_item_id'=>$request->purchaseRequisitionItemId],$data);
                         $request->session()->flash('success', "You have successfully ".(($request->status == 1) ? 'approved' : 'hold')." a  requisition item ");
                         return redirect('inventory/purchase-reqisition/approval');
            }
            if($validator->errors()->all()) {
                return redirect('inventory/purchase-reqisition/approval')->withErrors($validator)->withInput();
            }
    }
}
