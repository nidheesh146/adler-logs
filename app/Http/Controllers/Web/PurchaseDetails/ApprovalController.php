<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\PurchaseDetails\inv_purchase_req_master;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\PurchaseDetails\inventory_rawmaterial;

use Validator;
class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        $this->inv_purchase_req_master = new inv_purchase_req_master;
        $this->inv_supplier = new inv_supplier;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
    }

    public function getList(Request $request) 
    {
        if(count($_GET))
        {
            if ($request->pr_no) {
                $condition[] = ['inv_purchase_req_master.pr_no', 'like', '%'.$request->pr_no.'%'];
            }
            if ($request->supplier) {
                $condition[] = ['inv_supplier.vendor_id',  'like', '%'.$request->supplier.'%'];
                //$condition2[] = ['inv_supplier.vendor_name',  'like', '%'.$request->supplier.'%'];
            }
            if ($request->item_code) {
                $condition[] = ['inventory_rawmaterial.Item_code','like', '%'.$request->item_code.'%'];
            }
            if ($request->status) {
                $condition[] = ['inv_purchase_req_item_approve.status', '=', $request->status];
            }
            if ($request->from) {
                $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
           
           // $data['po_data'] =  $this->inv_final_purchase_order_master->get_purchase_master($condition);
           $data['inv_purchase'] = $this->inv_purchase_req_item->getdata_approved($condition);
        }
        else 
        {
            $data['inv_purchase'] = $this->inv_purchase_req_item->getdata_approved([]);
        }
        $data['pr_nos'] = $this->inv_purchase_req_master->get_pr_nos();
        $data['suppliers'] = $this->inv_supplier->get_all_suppliers();
        $data['items'] = $this->inventory_rawmaterial->get_items();
        //$data['inv_purchase'] = $this->inv_purchase_req_item->getdata_approved([]);
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
