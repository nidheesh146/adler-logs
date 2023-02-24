<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\PurchaseDetails\inv_purchase_req_item_approve;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\PurchaseDetails\inv_purchase_req_master;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use DB;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AllRequisitionItemsExport;
class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->User = new User;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        $this->inv_purchase_req_master = new inv_purchase_req_master;
        $this->inv_supplier = new inv_supplier;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        //$this->inv_purchase_req_item_approve = new inv_purchase_req_item_approve;
    }

    public function getList(Request $request) 
    {
            $condition = []; 
            $wherein = [4,5];

            if ($request->pr_no) {
                $condition[] = ['inv_purchase_req_master.pr_no', 'like', '%'.$request->pr_no.'%'];
            }
            if ($request->prsr == 'sr') {
                $condition[] = ['inv_purchase_req_master.PR_SR', '=', "SR"];
            }else{
                $condition[] = ['inv_purchase_req_master.PR_SR', '=', "PR"];
            }
            // if ($request->supplier) {
            //     $condition[] = ['inv_supplier.vendor_id',  'like', '%'.$request->supplier.'%'];
            // }
            if ($request->item_code) {
                $condition[] = ['inventory_rawmaterial.Item_code','like', '%'.$request->item_code.'%'];
            }
            if ($request->requestor) {
                //$condition[] = [DB::raw("CONCAT(user.f_name,user.l_name)"), 'like', '%' . $request->requestor . '%'];
                //$condition[] = ['user.l_name','like', '%'.$request->requestor.'%'];
                $condition[] = ['user.f_name','like', '%'.$request->requestor.'%'];
            }
            if ($request->status || $request->status == '0') {
                $wherein = [$request->status];
            }
            // if ($request->from) {
            //     $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            //     $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            // }
        


        $data['inv_purchase'] = $this->inv_purchase_req_item->getdata_approved($condition,$wherein);
        $data['pr_nos'] = $this->inv_purchase_req_master->get_pr_nos();
        $data['suppliers'] = $this->inv_supplier->get_all_suppliers();
        $data['items'] = $this->inventory_rawmaterial->get_items();
        $data['users'] = $this->User->get_all_users([]);
        //$data['inv_purchase'] = $this->inv_purchase_req_item->getdata_approved([]);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-approvel', compact('data'));
    }

    public function approve(Request $request) 
    {
        if($request->check_approve)
        {
            foreach($request->check_approve as $item_id){
                $qty = inv_purchase_req_item::where('requisition_item_id','=',$item_id)->pluck('actual_order_qty')->first();
                $data = ['inv_purchase_req_item_approve.approved_qty'=>$qty,
                             'inv_purchase_req_item_approve.status'=>1,
                             'inv_purchase_req_item_approve.remarks'=>"Approved",
                             'inv_purchase_req_item_approve.created_user'=>config('user')['user_id'],
                             'inv_purchase_req_item_approve.updated_at'=>date('Y-m-d H:i:s')
                        ];
                 $success[]=$this->inv_purchase_req_item->updatedata(['inv_purchase_req_item_approve.pr_item_id'=>$item_id],$data);
            }
        }
        if($request->check_hold)
        {
            foreach($request->check_hold as $item_id){
                $qty = inv_purchase_req_item::where('requisition_item_id','=',$item_id)->pluck('actual_order_qty')->first();
                $data = ['inv_purchase_req_item_approve.approved_qty'=>$qty,
                             'inv_purchase_req_item_approve.status'=>5,
                             'inv_purchase_req_item_approve.remarks'=>"On Hold",
                             'inv_purchase_req_item_approve.created_user'=>config('user')['user_id'],
                             'inv_purchase_req_item_approve.updated_at'=>date('Y-m-d H:i:s')
                        ];
                $success[]=$this->inv_purchase_req_item->updatedata(['inv_purchase_req_item_approve.pr_item_id'=>$item_id],$data);
            }
        }
        if($request->check_reject)
        {
            foreach($request->check_reject as $item_id){
                $qty = inv_purchase_req_item::where('requisition_item_id','=',$item_id)->pluck('actual_order_qty')->first();
                $data = ['inv_purchase_req_item_approve.approved_qty'=>$qty,
                             'inv_purchase_req_item_approve.status'=>0,
                             'inv_purchase_req_item_approve.remarks'=>"On Hold",
                             'inv_purchase_req_item_approve.created_user'=>config('user')['user_id'],
                             'inv_purchase_req_item_approve.updated_at'=>date('Y-m-d H:i:s')
                        ];
                $success[]=$this->inv_purchase_req_item->updatedata(['inv_purchase_req_item_approve.pr_item_id'=>$item_id],$data);
            }
        }
        if(count($success) >0)
        {
            $request->session()->flash('success', "You have successfully changed status of ".count($success)."  requisition item ");
        }
        if($request->prsr)
        return redirect('inventory/purchase-reqisition/approval?prsr='.$request->prsr);
        else
        return redirect('inventory/purchase-reqisition/approval');
            //return redirect('inventory/purchase-reqisition/approval?prsr='.$request->prsr);
        
            // $validation['purchaseRequisitionItemId'] = ['required'];
            // $validation['status'] = ['required'];
            // //$validation['reason'] = ['required'];
            // $validation['approved_by'] = ['required'];
            // $validator = Validator::make($request->all(), $validation);
            // if(!$validator->errors()->all()) {
            //     if($request->status == 1  && !$request->approved_qty){
            //         $validator->errors()->add('some_field', 'approved qty is empty !');
            //     }
            // }
            // if(!$validator->errors()->all()) {
            //     $data = ['inv_purchase_req_item_approve.approved_qty'=>$request->approved_qty,
            //              'inv_purchase_req_item_approve.status'=>$request->status,
            //              'inv_purchase_req_item_approve.remarks'=>$request->reason,
            //              'inv_purchase_req_item_approve.created_user'=>$request->approved_by,
            //              'inv_purchase_req_item_approve.updated_at'=>date('Y-m-d H:i:s')];
            //             if($request->status == 1)
            //             $status="Approved";
            //             if($request->status == 5)
            //             $status="Hold";
            //             if($request->status == 0)
            //             $status="Rejected";
            //              $this->inv_purchase_req_item->updatedata(['inv_purchase_req_item_approve.pr_item_id'=>$request->purchaseRequisitionItemId],$data);
            //              $request->session()->flash('success', "You have successfully ".$status." a  requisition item ");
            //              return redirect('inventory/purchase-reqisition/approval?prsr='.$request->prsr);
            // }
            // if($validator->errors()->all()) {
            //     return redirect('inventory/purchase-reqisition/approval?prsr='.$request->prsr)->withErrors($validator)->withInput();
            // }
    }

    public function get_updated_by($pr_item_id){
        $updated_by = inv_purchase_req_item::select('user.f_name','user.l_name')
        ->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                            ->leftjoin('user','user.user_id','=', 'inv_purchase_req_item_approve.created_user')
                            ->where('inv_purchase_req_item_approve.pr_item_id','=',$pr_item_id)
                            ->first();
        return $updated_by;
    }

    public function AllrequisitionItemExport(Request $request)
    {
        
        if($request)
        {
            return Excel::download(new AllRequisitionItemsExport($request), 'requisition_list' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new AllRequisitionItemsExport($request), 'requisition_list' . date('d-m-Y') . '.xlsx');
        }
    }
}
