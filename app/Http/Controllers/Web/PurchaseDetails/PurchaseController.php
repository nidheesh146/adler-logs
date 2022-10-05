<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Exports\FinalPurchaseOrderExport;
use App\Http\Controllers\Controller;
use App\Models\PurchaseDetails\inv_final_purchase_order_item;
use App\Models\PurchaseDetails\inv_final_purchase_order_master;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\PurchaseDetails\inv_purchase_req_master;
use App\Models\PurchaseDetails\inv_purchase_req_master_item_rel;
use App\Models\PurchaseDetails\inv_purchase_req_quotation;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_item_supp_rel;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_supplier;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\PurchaseDetails\inv_supplier_invoice_item;
use App\Models\PurchaseDetails\inv_supplier_invoice_master;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Validator;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->inv_purchase_req_quotation = new inv_purchase_req_quotation;
        $this->User = new User;
        $this->inv_purchase_req_quotation_supplier = new inv_purchase_req_quotation_supplier;
        $this->inv_purchase_req_quotation_item_supp_rel = new inv_purchase_req_quotation_item_supp_rel;
        $this->inv_final_purchase_order_master = new inv_final_purchase_order_master;
        $this->inv_final_purchase_order_item = new inv_final_purchase_order_item;
        $this->inv_supplier_invoice_master = new inv_supplier_invoice_master;
        $this->inv_supplier_invoice_item = new inv_supplier_invoice_item;
        $this->inv_supplier = new inv_supplier;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
    }

    public function getFinalPurchase(Request $request)
    {//echo $request->order_type;exit;
            $condition1 = [];
            if (!$request->pr_no && !$request->rq_no && !$request->supplier && !$request->po_from && !$request->processed_from && !$request->status) {
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 4];
                $condition1[] = ['inv_final_purchase_order_master.type','=', "PO"];
            }
            if ($request->order_type=="wo") {
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 4];
                $condition1[] = ['inv_final_purchase_order_master.type','=', 'WO'];
            }else{
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 4];
                $condition1[] = ['inv_final_purchase_order_master.type','=', "PO"];
            }
            if ($request->rq_no) {
                $condition1[] = ['inv_purchase_req_quotation.rq_no', 'like', '%' . $request->rq_no . '%'];
            }
            if ($request->supplier) {
                $condition1[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
       
            if ($request->po_from) {
                $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime('01-' . $request->po_from))];
                $condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-t', strtotime('01-' . $request->po_from))];
            }

            if ($request->status) {
                if ($request->status == "reject") {
                    $condition1[] = ['inv_final_purchase_order_master.status', '=', 0];
                }
                $condition1[] = ['inv_final_purchase_order_master.status', '=', $request->status];
            }
            if ($request->po_no) {
                $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $request->po_no . '%'];
            }


        $data['users'] = $this->User->get_all_users([]);
        $data['po_data'] = $this->inv_final_purchase_order_master->get_purchase_master_list($condition1);
       // print_r(json_encode($data['po_data']));exit;
        return view('pages.purchase-details.final-purchase.final-purchase-list', compact('data'));
    }
    public function editFinalPurchase(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            $validation['date'] = ['required', 'date'];
            $validation['create_by'] = ['required'];
            $validation['rq_master_id'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                if (!$id) {
                    $groupByItemSupplier = $this->inv_purchase_req_quotation_item_supp_rel->groupByItemSupplier(['inv_purchase_req_quotation_item_supp_rel.selected_item' => 1,'inv_purchase_req_quotation_item_supp_rel.quotation_id'=>$request->rq_master_id]);
                    
                    foreach ($groupByItemSupplier as $ByItemSupplier) {
                        $type = $this->check_reqisition_type($request->rq_master_id,$ByItemSupplier->supplier_id);
                        if ($type == "PR") {
                            $supplier_type = $this->check_supplier_type($ByItemSupplier->supplier_id);
                            //$supplier_type =  $this->inv_supplier->get_supplier(['id'=>$ByItemSupplier->supplier_id])->supplier_type;
                            $item_type = $this->check_item_type($request->rq_master_id,$ByItemSupplier->supplier_id);
                           
                            if ($item_type == "Direct Items") {
                                $data['po_number'] = "POI2-" . $this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('type', '=', 'PO')->count(),1);
                            } else {
                                $data['po_number'] = "POI3" . $this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%ID%')->where('type', '=', 'PO')->count(),2);
                            }
                            $data['type'] ="PO";
                        } else {
                            $item_type = $this->check_item_type($request->rq_master_id,$ByItemSupplier->supplier_id);
                            if ($item_type == "Direct Items") {
                            $data['po_number'] = "WOI2-" . $this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('type', '=', 'WO')->count());
                            $data['type'] ="WO";
                            }
                            else{
                                $data['po_number'] = "WOI3-" . $this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('type', '=', 'WO')->count());
                            }
                        }
                        $data['created_at'] = date('Y-m-d H:i:s');
                        $data['updated_at'] = date('Y-m-d H:i:s');
                        $data['rq_master_id'] = $request->rq_master_id;
                        $data['status'] = 4;
                        $data['supplier_id'] = $ByItemSupplier->supplier_id;
                        $data['po_date'] = date('Y-m-d', strtotime($request->date));
                        $data['created_by'] = $request->create_by;
                
                        $inv_supplier_terms = DB::table('inv_supplier')->select('*')->where('id', $data['supplier_id'])->first();
                        $POMaster = $this->inv_final_purchase_order_master->insert_data($data, $inv_supplier_terms->terms_and_conditions);

  
                    }
                    $request->session()->flash('success', "You have successfully added a  purchase order master !");

                }
                if ($id) {
                    $data['po_date'] = date('Y-m-d', strtotime($request->date));
                    $data['created_by'] = $request->create_by;
                    $this->inv_final_purchase_order_master->updatedata(['inv_final_purchase_order_master.id' => $id], $data);
                    $request->session()->flash('success', "You have successfully updated a  purchase order master !");
                }
                return redirect("inventory/final-purchase-edit/" . $id);
            }
            if ($validator->errors()->all()) {
                return redirect("inventory/final-purchase-edit/" . $id)->withErrors($validator)->withInput();

            }

        }
        $condition[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition);
        if ($id) {
            $data['master_data'] = $this->inv_final_purchase_order_master->get_master_data(['inv_final_purchase_order_master.id' => $id]);
            $data['master_list'] = $this->rq_details($data['master_data']->rq_master_id, $id);

        }

        return view('pages.purchase-details.final-purchase.final-purchase-edit', compact('data'));
    }

    public function add1FinalPurchase(Request $request){
        $condition = [];
        if($request->order_type){
            if($request->order_type=='wo')
            $cond = 'SR';
            else
            $cond = 'PR';
            $condition[] = ['inv_purchase_req_quotation.type', '=', $cond];
            
        }
        if ($request->rq_no) {
            $condition[] = ['inv_purchase_req_quotation.rq_no', 'like', '%'.$request->rq_no.'%'];
        }
        if ($request->supplier) {
            $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
        }
        $data['quotation'] =  $this->inv_purchase_req_quotation->get_rq_final_purchase( $condition);
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);
        return view('pages.purchase-details.final-purchase.final-purchase-add1',compact('data'));
    }

    public function insertFinalPurchase(Request $request){
        $validation['date'] = ['required','date'];
        $validation['quotation_id'] = ['required'];
        $validation['create_by'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()){
            //print_r($request->quotation_id);exit;
            foreach($request->quotation_id as $quotation_id){
                $groupByItemSupplier = $this->inv_purchase_req_quotation_item_supp_rel->groupByItemSupplier(['inv_purchase_req_quotation_item_supp_rel.selected_item' => 1,'inv_purchase_req_quotation_item_supp_rel.quotation_id'=>$quotation_id]);
                    
                    foreach ($groupByItemSupplier as $ByItemSupplier) {
                        $type = $this->check_reqisition_type($quotation_id,$ByItemSupplier->supplier_id);
                        if ($type == "PR") {
                            $supplier_type = $this->check_supplier_type($ByItemSupplier->supplier_id);
                            $item_type = $this->check_item_type($quotation_id,$ByItemSupplier->supplier_id);
                           
                            if ($item_type == "Direct Items") {
                                $data['po_number'] = "POI2-" . $this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('type', '=', 'PO')->count(),1);
                            } else {
                                $data['po_number'] = "POI3" . $this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%ID%')->where('type', '=', 'PO')->count(),2);
                            }
                            $data['type'] ="PO";
                        } else {
                            $item_type = $this->check_item_type($quotation_id,$ByItemSupplier->supplier_id);
                            if ($item_type == "Direct Items") {
                            $data['po_number'] = "WOI2-" . $this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('type', '=', 'WO')->count());
                            $data['type'] ="WO";
                            }
                            else{
                                $data['po_number'] = "WOI3-" . $this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('type', '=', 'WO')->count());
                            }
                        }
                        $data['created_at'] = date('Y-m-d H:i:s');
                        $data['updated_at'] = date('Y-m-d H:i:s');
                        $data['rq_master_id'] = $quotation_id;
                        $data['status'] = 4;
                        $data['supplier_id'] = $ByItemSupplier->supplier_id;
                        $data['po_date'] = date('Y-m-d', strtotime($request->date));
                        $data['created_by'] = $request->create_by;
                
                        $inv_supplier_terms = DB::table('inv_supplier')->select('*')->where('id', $data['supplier_id'])->first();
                        $POMaster = $this->inv_final_purchase_order_master->insert_data($data, $inv_supplier_terms->terms_and_conditions);

  
                    }
            }
            $request->session()->flash('success', "You have successfully added a  purchase order master !");
            return redirect('inventory/final-purchase?order_type='.$request->order_type)->withErrors($validator)->withInput();
        }   
        if($validator->errors()->all()){
            return redirect('inventory/final-purchase-add?order_type='.$request->order_type)->withErrors($validator)->withInput();
        }

    }

    public function check_item_type($rq_master_id, $supplier_id){
        $item_id = inv_purchase_req_quotation_item_supp_rel::where('inv_purchase_req_quotation_item_supp_rel.quotation_id','=',$rq_master_id)
                                                    ->where('inv_purchase_req_quotation_item_supp_rel.supplier_id','=',$supplier_id)
                                                    ->where('selected_item','=',1)
                                                    ->pluck('item_id')
                                                    ->first();
        $item_code = inv_purchase_req_item::where('requisition_item_id','=',$item_id)->pluck('item_code')->first();
        $type = inventory_rawmaterial::leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                                    ->where('inventory_rawmaterial.id','=',$item_code)->pluck('type_name')->first();
        return $type;

    }

    public function Edit_PO_item(Request $request, $id)
    {

        if ($request->isMethod('post')) {
            $validation['quantity'] = ['required'];
            $validation['rate'] = ['required'];
            $validation['discount'] = ['required'];
            $validation['delivery_schedule'] = ['required', 'date'];
            $validation['specification'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                $data['delivery_schedule'] = $request->delivery_schedule;
                $data['order_qty'] = $request->quantity;
                $data['rate'] = $request->rate;
                $data['discount'] = $request->discount;
                $data['Specification'] = $request->specification;
                $POitem = $this->inv_final_purchase_order_item->updatedata(['id' => $id], $data);
                $request->session()->flash('success', "You have successfully edited a  purchase order item!");
                return redirect("inventory/final-purchase-item-edit/" . $id);
            }
            if ($validator->errors()->all()) {
                return redirect("inventory/final-purchase-item-edit/" . $id)->withErrors($validator)->withInput();
            }
        }
        $data = $this->inv_final_purchase_order_item->get_purchase_order_single_item(['inv_final_purchase_order_item.id' => $id]);
       
        return view('pages.purchase-details.final-purchase.final-purchase-item-edit', compact('data'));

    }

    function get_supplier($quotation_id){
        //return "jj";
        $suppliers ="";
        $supplier_id ="";
        $suppliers_list = inv_purchase_req_quotation_item_supp_rel::select('inv_supplier.vendor_name','inv_supplier.vendor_id')
                        ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_item_supp_rel.supplier_id')
                        ->where('inv_purchase_req_quotation_item_supp_rel.quotation_id','=', $quotation_id)
                        ->where('inv_purchase_req_quotation_item_supp_rel.selected_item','=',1)
                        ->get();
        foreach($suppliers_list as $supplier){
            $suppliers .="<span>".$supplier->vendor_id."</span> - <span>".$supplier->vendor_name."</span>" ;
            if(count($suppliers_list)>1)
            {
                $suppliers .= " <br> ";
            }
        }
        return ["supplier" => $suppliers];
        
    }

    public function changeStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['po_id'] = ['required'];
            $validation['status'] = ['required'];
            //$validation['remarks'] = ['required'];
            $validation['date'] = ['required'];
            $validation['approved_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            {
                $data = ['inv_final_purchase_order_master.status'=>$request->status,
                        'inv_final_purchase_order_master.remarks'=>$request->remarks,
                        'inv_final_purchase_order_master.processed_by'=>$request->approved_by,
                        'inv_final_purchase_order_master.processed_date'=>date('Y-m-d',strtotime($request->date)),
                        'inv_final_purchase_order_master.updated_at'=>date('Y-m-d H:i:s')];
                            if($request->status == 1)
                            $status="Approved";
                            if($request->status == 5)
                            $status="Hold";
                            if($request->status == 0)
                            $status="Cancelled";

                            $this->inv_final_purchase_order_master->updatedata(['inv_final_purchase_order_master.id'=>$request->po_id],$data);
                            $request->session()->flash('success', "You have successfully ".$status." a  Purchase/Work Order ");
                if(isset($request->poc))
                {
                    if($request->order_type)
                    return redirect('inventory/final-purchase/cancellation?order_type='.$request->order_type);
                    else
                    return redirect('inventory/final-purchase/cancellation');
                }
                else
                {
                    if($request->order_type)
                    return redirect('inventory/final-purchase?order_type='.$request->order_type);
                    else
                    return redirect('inventory/final-purchase');
                }
            }
            if ($validator->errors()->all()) {
                return redirect('inventory/final-purchase')->withErrors($validator)->withInput();
            }
        }
    }
    

    public function viewFinalPurchase($id)
    {
        $data['master'] = $this->inv_final_purchase_order_master->get_master_details(['inv_final_purchase_order_master.id' => $id]);
        $data['items'] = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $id]);
        return view('pages.purchase-details.final-purchase.final-purchase-view', compact('data'));
    }

    public function deleteFinalPurchase(Request $request, $id)
    {
        if ($id) {
            $this->inv_final_purchase_order_master->deleteData(['id' => $id]);
            $request->session()->flash('success', "You have successfully deleted a final purchase order master !");
        }
        return redirect('inventory/final-purchase');
    }

    public function purchaseOderCancellation(Request $request){
        $condition1 = [];
            if (!$request->pr_no && !$request->rq_no && !$request->supplier && !$request->po_from && !$request->processed_from && !$request->status) {
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 4];
            }
            if ($request->order_type == "wo") {
                $condition1[] = ['inv_final_purchase_order_master.type','=', "WO"];
            }else{
                $condition1[] = ['inv_final_purchase_order_master.type','=', "PO"];
            }
            if ($request->rq_no) {
                $condition1[] = ['inv_purchase_req_quotation.rq_no', 'like', '%' . $request->rq_no . '%'];
            }
            if ($request->supplier) {
                $condition1[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
       
            if ($request->po_from) {
                $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime('01-' . $request->po_from))];
                $condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-t', strtotime('01-' . $request->po_from))];
            }

            if ($request->status) {
                if ($request->status == "reject") {
                    $condition1[] = ['inv_final_purchase_order_master.status', '=', 0];
                }
                $condition1[] = ['inv_final_purchase_order_master.status', '=', $request->status];
            }
            if ($request->po_no) {
                $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $request->po_no . '%'];
            }
            

        $data['users'] = $this->User->get_all_users([]);
        $data['po_data'] = $this->inv_final_purchase_order_master->get_purchase_master_list($condition1);
        return view('pages.purchase-details.final-purchase.final-purchase-cancellation', compact('data'));
    }
    public function find_rq_number(Request $request)
    {
        if ($request->q) {
            $condition1[] = ['inv_purchase_req_quotation.rq_no', 'like', '%' . strtoupper($request->q) . '%'];
            //$condition1[] = ['inv_purchase_req_quotation_supplier.selected_supplier','=',1];
            // $condition2[] = ['inv_purchase_req_quotation.rq_no','like','%'.strtoupper($request->q).'%'];
            $data = $this->inv_purchase_req_quotation->get_master_filter($condition1);
            if (!empty($data)) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->rq_details($request->id, null);
            exit;
        }

    }
    public function check_supplier_type($rq_id)
    {
        $supplier_type = inv_purchase_req_quotation_supplier::where('quotation_id', '=', $rq_id)
            ->leftjoin('inv_supplier', 'inv_supplier.id', '=', 'inv_purchase_req_quotation_supplier.supplier_id')
            ->where('selected_supplier', '=', 1)
            ->pluck('inv_supplier.supplier_type')->first();
        return $supplier_type;

    }

    public function check_reqisition_type($id)
    {
        $item_id = inv_purchase_req_quotation_item_supp_rel::where('quotation_id', '=', $id)->pluck('item_id')->first();
        $requisition_item_id = inv_purchase_req_item::where('requisition_item_id', '=', $item_id)->pluck('requisition_item_id')->first();
        $requisition_master_id = inv_purchase_req_master_item_rel::where('item', '=', $requisition_item_id)->pluck('master')->first();
        $reqisition_type = inv_purchase_req_master::where('master_id', '=', $requisition_master_id)->pluck('PR_SR')->first();
        return $reqisition_type;

    }

    public function rq_details($id, $active = null)
    {

        if ($active) {
            $inv_final_purchase_order_master_id = $this->inv_final_purchase_order_master->get_master_data(['inv_final_purchase_order_master.id' => $active]);
            $qn = $this->inv_purchase_req_quotation_supplier->inv_purchase_req_quotation_data(['inv_purchase_req_quotation_supplier.quotation_id' => $id, 'inv_purchase_req_quotation_supplier.supplier_id' => $inv_final_purchase_order_master_id->supplier_id]);
        } else {
            $qn = $this->inv_purchase_req_quotation_supplier->inv_purchase_req_quotation_data(['inv_purchase_req_quotation_supplier.quotation_id' => $id]);
        }

        $data = "";
        foreach ($qn as $key => $quotation) {
            $quotation_item = $this->inv_purchase_req_quotation_item_supp_rel->inv_purchase_req_quotation_item_data(['inv_purchase_req_quotation_item_supp_rel.supplier_id' => $quotation->supplier_id, 'inv_purchase_req_quotation_item_supp_rel.quotation_id' => $quotation->quotation_id, 'inv_purchase_req_quotation_item_supp_rel.selected_item' => 1]);
            if ($active) {
                $purchase_order_item = $this->inv_final_purchase_order_item->get_purchase_order_item(['inv_final_purchase_order_rel.master' => $active]);
            }
            if ($quotation_item) {
                if ($key == 0) {
                    $data .= '<div class="row">
        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
            Supplier Quotation (' . $quotation->rq_no . ')
                </label>
            <div class="form-devider"></div>
          </div>
        </div>';
                }
                $data .= '<div style="border: 1px solid #cdd4e0;padding: 8px;margin-bottom: 27px;" >
     <div class="row">
        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
           ' . $quotation->vendor_id . ' - ' . $quotation->vendor_name . '
                </label>
            <div class="form-devider"></div>
          </div>
        </div>
    <table class="table table-bordered mg-b-0">
    <thead>
    <tr>
        <th>Supplier quotation NO</th>
        <th>Commited delivery date</th>
        <th>Quotation date</th>
        <th>Contact</th>
    </tr>
   </thead>
    <tbody>
    <tr>
        <td>' . $quotation->supplier_quotation_num . '</td>
        <td>' . ($quotation->commited_delivery_date ? date('d-m-Y', strtotime($quotation->commited_delivery_date)) : '-') . '</td>
        <td>' . ($quotation->quotation_date ? date('d-m-Y', strtotime($quotation->quotation_date)) : '-' ). '</td>
        <td>' . $quotation->contact_number . '</td>
    </tr>
    </tbody>
    </table><br>

    <div class="table-responsive">
    <table class="table table-bordered mg-b-0" id="example1">';

                if ($active) {
                    $data .= '<thead>
            <tr>
                <th>PR NO.</th>
                <th>Item Code:</th>
                <th>HSN</th>
                <th>Delivery schedule</th>
                <th>Quantity</th>
                <th>rate</th>
                <th>Discount </th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody >';
                    foreach ($purchase_order_item as $item) {
                        $data .= '<tr>
                <td>' . $item->pr_no . '</td>
                <td>' . $item->item_code . '</td>
                <td>' . $item->hsn_code . '</td>
                <td>' . ($item->delivery_schedule ? date('d-m-Y', strtotime($item->delivery_schedule)) : '-') . '</td>
                <td>' . $item->order_qty . '</td>
                <td>' . $item->rate . '</td>
                <td>' . $item->discount . '</td>
                <td><a class="badge badge-info" style="font-size: 13px;" href="' . url("inventory/final-purchase-item-edit/" . $item->id) . '"><i class="fas fa-edit"></i> Edit</a></td>
            </tr>';
                    }
                    $data .= '</tbody>';
                }

                if (!$active) {
                    $data .= '<thead>
            <tr>
                <th>PR NO.</th>
                <th>Item Code:</th>
                <th>HSN</th>
                <th>Supplier Qty</th>
                <th>Supplier Rate</th>
                <th>Supplier Discount %</th>
            </tr>
        </thead>
        <tbody >';
                    foreach ($quotation_item as $item) {
                        $data .= '<tr>
                <td>' . $item->pr_no . '</td>
                <td>' . $item->item_code . '</td>
                <td>' . $item->hsn_code . '</td>
                <td>' . $item->quantity . '</td>
                <td>' . $item->rate . '</td>
                <td>' . $item->discount . '</td>
            </tr>';
                    }
                    $data .= '</tbody>';
                }

                $data .= '</table>
</div></div>';

            }

        }

        return $data;
    }

    public function find_po_number(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_final_purchase_order_master->find_po_num($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->rq_details_po($request->id, null);
            exit;
        }
    }
    public function rq_details_po($id, $active = null)
    {
        $po_master = $this->inv_final_purchase_order_master->find_po_data(['inv_final_purchase_order_master.id' => $id]);

        $purchase_order_item = $this->inv_final_purchase_order_item->get_purchase_order_item(['inv_final_purchase_order_rel.master' => $id]);
        if ($active) {
            $purchase_order_item = $this->inv_supplier_invoice_item->get_supplier_invoice_item(['inv_supplier_invoice_rel.master' => $active]);
        }

        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               Final Purchase Order  master (' . $po_master->po_number . ')
                   </label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
           <thead>
           <tr>
               <th>Purchase order date</th>
               <th>created date</th>
           </tr>
          </thead>
           <tbody>
           <tr>
               <td>' . date('d-m-Y', strtotime($po_master->po_date)) . '</td>
               <td>' . date('d-m-Y', strtotime($po_master->created_at)) . '</td>
           </tr>
           </tbody>
           </table>

           <table class="table table-bordered mg-b-0">
           <thead>
           <tr>
               <th>Supplier ID</th>
               <th>Supplier Name</th>
           </tr>
          </thead>
           <tbody>
           <tr>
               <td>' . $po_master->vendor_id . '</td>
               <td>' . $po_master->vendor_name . '</td>
           </tr>
           </tbody>

           </table><br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
        if ($active) {
            $data .= 'Supplier Invoice items ';
        } else {
            $data .= 'Final purchase order items ';
        }
        $data .= '</label>
               <div class="form-devider"></div>
           </div>
           </div>
           <div class="table-responsive">
           <table class="table table-bordered mg-b-0" id="example1">';

        if ($active) {
            $data .= '<thead>
                   <tr>
                       <th>PR NO.</th>
                       <th>Item Code:</th>
                       <th>HSN</th>
                       <th>Quantity</th>
                       <th>rate</th>
                       <th>Discount </th>
                       <th>Action</th>
                   </tr>
               </thead>
               <tbody >';
            foreach ($purchase_order_item as $item) {
                $data .= '<tr>
                       <td>' . $item->pr_no . '</td>
                       <td>' . $item->item_code . '</td>
                       <td>' . $item->hsn_code . '</td>
                       <td>' . $item->order_qty . '</td>
                       <td>' . $item->rate . '</td>
                       <td>' . $item->discount . '</td>
                       <td><a class="badge badge-info" style="font-size: 13px;" href="' . url("inventory/supplier-invoice-item-edit/" . $active . '/' . $item->id) . '"><i class="fas fa-edit"></i> Edit</a></td>
                   </tr>';
            }
            $data .= '</tbody>';
        }

        if (!$active) {
            $data .= '<thead>
                   <tr>
                   <th>PR NO.</th>
                   <th>Item Code:</th>
                   <th>HSN</th>
                   <th>Quantity</th>
                   <th>rate</th>
                   <th>Discount </th>
                   </tr>
               </thead>
               <tbody >';
            foreach ($purchase_order_item as $item) {
                $data .= '<tr>
                       <td>' . $item->pr_no . '</td>
                       <td>' . $item->item_code . '</td>
                       <td>' . $item->hsn_code . '</td>
                       <td>' . $item->order_qty . '</td>
                       <td>' . $item->rate . '</td>
                       <td>' . $item->discount . '</td>
                   </tr>';
            }
            $data .= '</tbody>';
        }

        $data .= '</table>
       </div>';
        return $data;
    }

    public function supplierInvoice(Request $request)
    {
        $condition1 = [];
        
        if ($request->order_type) {
            $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', $request->order_type . '%'];
        }

        if ($request->po_no) {
            $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $request->po_no . '%'];
        }
        if ($request->invoice_no) {
            $condition1[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . $request->invoice_no . '%'];
        }
        if ($request->supplier) {
            // $condition2[] = ['inv_supplier.id', '=', $request->supplier];
            $condition1[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            // $condition2[] = ['inv_supplier.vendor_name', 'like', '%'.$request->supplier.'%'];
        }
        if ($request->from) {
            $condition1[] = ['inv_supplier_invoice_master.invoice_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition1[] = ['inv_supplier_invoice_master.invoice_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }

        $data['Requisition'] = $this->inv_supplier_invoice_master->get_supplier_invoices($condition1);

        // $data['suppliers'] = $this->inv_supplier->get_all_suppliers();
        // $data['po_nos'] = $this->inv_final_purchase_order_master->get_po_nos();
        // $data['invoice_nos'] = $this->inv_supplier_invoice_master->get_invoice_nos();
        //$data['Requisition'] = $this->inv_supplier_invoice_master->get_supplier_inv(['inv_supplier_invoice_master.status'=>1]);
        return view('pages.purchase-details.supplier-invoice.supplier-invoice-list', compact('data'));
    }

    public function supplierInvoiceAdd(Request $request, $id = null)
    {

        if ($request->isMethod('post')) {
            $validation['invoice_number'] = ['required'];
            $validation['po_number'] = ['required'];
            $validation['date'] = ['required', 'date'];
            $validation['create_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if (!$validator->errors()->all()) {
                if (!$id) {
                    $data['po_master_id'] = $request->po_number;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $order_master = $this->inv_final_purchase_order_master->get_master_data(['inv_final_purchase_order_master.id' => $request->po_number]);
                    $data['supplier_id'] = $order_master->supplier_id;
                }
                $data['invoice_number'] = $request->invoice_number;
                $data['invoice_date'] = date('Y-m-d', strtotime($request->date));
                $data['created_by'] = $request->create_by;
                if (!$id) {
                    $SIMaster = $this->inv_supplier_invoice_master->insert_data($data);
                    $request->session()->flash('success', "You have successfully created a  supplier invoice master !");
                    return redirect("inventory/supplier-invoice-add/" . $SIMaster);
                } else {
                    $this->inv_supplier_invoice_master->updatedata(['inv_supplier_invoice_master.id' => $id], $data);
                    $request->session()->flash('success', "You have successfully updated a supplier invoice master !");
                    return redirect("inventory/supplier-invoice-add/" . $id);
                }
            }
            if ($validator->errors()->all()) {
                return redirect("inventory/supplier-invoice-add/" . $id)->withErrors($validator)->withInput();
            }
        }
        $condition[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition);
        if ($id) {
            $data['simaster'] = $this->inv_supplier_invoice_master->get_master_data(['inv_supplier_invoice_master.id' => $id]);
            $data['master_list'] = $this->rq_details_po($data['simaster']->id, $id);
        }

        return view('pages.purchase-details.supplier-invoice.supplier-invoice-add', compact('data', 'id'));
    }
    public function supplier_invoice_delete(Request $request, $id)
    {
        $this->inv_supplier_invoice_master->deleteData(['id' => $id]);
        $request->session()->flash('success', "You have successfully deleted a supplier invoice master !");
        return redirect("inventory/supplier-invoice");
    }
    public function supplierInvoiceItemEdit(Request $request, $master, $item)
    {
        if ($request->isMethod('post')) {
            $validation['quantity'] = ['required'];
            $validation['rate'] = ['required'];
            $validation['discount'] = ['required'];
            $validation['specification'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                $data['order_qty'] = $request->quantity;
                $data['rate'] = $request->rate;
                $data['discount'] = $request->discount;
                $data['specification'] = $request->specification;
                $this->inv_supplier_invoice_item->updatedata(['id' => $item], $data);
                $request->session()->flash('success', "You have successfully updated a supplier invoice item !");
                return redirect("inventory/supplier-invoice-item-edit/" . $master . '/' . $item)->withErrors($validator)->withInput();
            }
            if ($validator->errors()->all()) {
                return redirect("inventory/supplier-invoice-item-edit/" . $master . '/' . $item)->withErrors($validator)->withInput();
            }
        }

        $data['item'] = $this->inv_supplier_invoice_item->get_si_item(['inv_supplier_invoice_item.id' => $item]);
        return view('pages.purchase-details.supplier-invoice.supplier-invoice-list-edit', compact('data', 'master', 'item'));
    }

    public function generateFinalPurchasePdf($id)
    {

        $data['final_purchase'] = $this->inv_final_purchase_order_item->get_purchase_order_single_item_receipt(['inv_final_purchase_order_master.id' => $id]);
        $data['items'] = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $id]);
        //print_r( json_encode($data['items']));exit;
        $data['terms_condition'] = DB::table('po_fpo_master_tc_rel')
            ->select('po_supplier_terms_conditions.terms_and_conditions')
            ->join('po_supplier_terms_conditions', 'po_supplier_terms_conditions.id', '=', 'po_fpo_master_tc_rel.terms_id')
            ->where('fpo_id', $id)
            ->first();

        $pdf = PDF::loadView('pages.purchase-details.final-purchase.final-purchase-pdf', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "final-purchase-order_" . $data['final_purchase']['vendor_name'] . "_" . $data['final_purchase']['po_number'];
        return $pdf->stream($file_name . '.pdf');
        //return $pdf->download('final-purchase.pdf');
    }

    public function exportFinalPurchaseAll()
    {
        $status = "all";
        return Excel::download(new FinalPurchaseOrderExport($status), 'FinalPurchaseOrder' . date('d-m-Y') . '.xlsx');
    }
    public function exportFinalPurchaseOpen()
    {
        $status = 1;
        return Excel::download(new FinalPurchaseOrderExport($status), 'FinalPurchaseOrder' . date('d-m-Y') . '.xlsx');
    }

    public function find_user($user_id)
    {
        $user = $this->User->get_user(['user_id' => $user_id]);
        return $user;
    }

    public function find_freight_charge($rq_id,$supplierId)
    {
        $freight_charge = inv_purchase_req_quotation_supplier::where('quotation_id','=',$rq_id)->where('supplier_id','=',$supplierId)
                            ->pluck('freight_charge')->first();
         return $freight_charge;                   
    }

}
