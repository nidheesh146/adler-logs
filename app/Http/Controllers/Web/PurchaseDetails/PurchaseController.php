<?php 

namespace App\Http\Controllers\Web\PurchaseDetails;
use Illuminate\Validation\Rule;
use App\Exports\FinalPurchaseOrderExport;
use App\Exports\SupplierInvoiceExport;
use App\Exports\PendingPurchaseRealisationExport;
use App\Http\Controllers\Controller;
use App\Models\PurchaseDetails\inv_final_purchase_order_item;
use App\Models\PurchaseDetails\inv_final_purchase_order_rel;
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
use App\Models\PurchaseDetails\inv_supplier_invoice_rel;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\User;
use App\Models\inventory_gst;
use App\Models\currency_exchange_rate;
use App\Mail\OrderCancellation;
use App\Mail\OrderApproved;
use App\Mail\PartialOrderCancellation;
use Illuminate\Support\Facades\Mail;
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
        $this->inventory_gst = new inventory_gst;
        $this->currency_exchange_rate = new currency_exchange_rate;
    }

    public function getFinalPurchase(Request $request)
    {
        // $po_item = inv_final_purchase_order_item::get();
        // foreach($po_item as $item)
        // {
        //     $uu = inv_final_purchase_order_item::find($item['id']);
        //     $uu->qty_to_invoice = $item->order_qty;
        //     $uu->save();
        // }
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
            $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime($request->po_from))];
            //$condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-d', strtotime($this->request->po_from))];
        }
        if ($request->po_to) {
            //$condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime($this->request->po_from))];
            $condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-d', strtotime($request->po_to))];
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
                        if(date('m')==01 || date('m')==02 || date('m')==03)
                        {
                            $years_combo = date('y', strtotime('-1 year')).date('y');
                        }
                        else
                        {
                            $years_combo = date('y').date('y', strtotime('+1 year'));
                        }
                        $type = $this->check_reqisition_type($request->rq_master_id,$ByItemSupplier->supplier_id);
                        if ($type == "PR") 
                        {
                            $supplier_type = $this->check_supplier_type($ByItemSupplier->supplier_id);
                            //$supplier_type =  $this->inv_supplier->get_supplier(['id'=>$ByItemSupplier->supplier_id])->supplier_type;
                            $item_type = $this->check_item_type($request->rq_master_id,$ByItemSupplier->supplier_id);
                            if ($item_type == "Finished Goods") {
                                $data['po_number'] = "POI1-".$this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI1-'.$years_combo.'%')
                                                                                            ->Orwhere('po_number','like','%POC1-'.$years_combo.'%')->where('type', '=', 'PO')->count(),1);
                            }
                            else if ($item_type == "Direct Items") {
                                $data['po_number'] = "POI2-".$this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI2-'.$years_combo.'%')
                                                                                            ->Orwhere('po_number','like','%POC2-'.$years_combo.'%')->where('type', '=', 'PO')->count(),1);
                            } else {
                                $data['po_number'] = "POI3-".$this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI3-'.$years_combo.'ID%')
                                                                    ->Orwhere('po_number','like','%POC3-'.$years_combo.'ID%')->where('type', '=', 'PO')->count(),2);
                            }
                            $data['type'] ="PO";
                        } 
                        else 
                        {
                            $item_type = $this->check_item_type($request->rq_master_id,$ByItemSupplier->supplier_id);
                            if ($item_type == "Direct Items") {
                            $data['po_number'] = "WOI2-".$this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%WOI2-'.$years_combo.'%')
                                                            ->Orwhere('po_number','like','%WOC2-'.$years_combo.'%')->where('type', '=', 'WO')->count());
                            $data['type'] ="WO";
                            }
                            else{
                                $data['po_number'] = "WOI3-".$this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%WOI3-'.$years_combo.'%')
                                                            ->Orwhere('po_number','like','%WOC3-'.$years_combo.'%')->where('type', '=', 'WO')->count());
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
        // $condition1[] = ['user.status', '=', 1];
        // $data['users'] = $this->User->get_all_users($condition1);
        
        
        return view('pages.purchase-details.final-purchase.final-purchase-add',compact('data'));
    }

    public function insertFinalPurchase(Request $request)
    {
        $validation['quotation_id'] = ['required'];
        // $validation['create_by'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()){
            //print_r($request->quotation_id);exit;
            foreach($request->quotation_id as $quotation_id){
                $groupByItemSupplier = $this->inv_purchase_req_quotation_item_supp_rel->groupByItemSupplier(['inv_purchase_req_quotation_item_supp_rel.selected_item' => 1,'inv_purchase_req_quotation_item_supp_rel.quotation_id'=>$quotation_id]);
                    
                    foreach ($groupByItemSupplier as $ByItemSupplier) {
                        if(date('m')==01 || date('m')==02 || date('m')==03)
                        {
                            $years_combo = date('y', strtotime('-1 year')).date('y');
                        }
                        else
                        {
                            $years_combo = date('y').date('y', strtotime('+1 year'));
                        }
                        $type = $this->check_reqisition_type($quotation_id,$ByItemSupplier->supplier_id);
                        if ($type == "PR") {
                            $supplier_type = $this->check_supplier_type($ByItemSupplier->supplier_id);
                            $item_type = $this->check_item_type($quotation_id,$ByItemSupplier->supplier_id);
                            if ($item_type == "Finished Goods") {
                                $data['po_number'] = "POI1-".$this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI1-'.$years_combo.'%')
                                                            ->Orwhere('po_number','like','%POC1-'.$years_combo.'%')->where('type', '=', 'PO')->count(),1);
                            }
                            else if ($item_type == "Direct Items") {
                                $data['po_number'] = "POI2-".$this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI2-'.$years_combo.'%')
                                                            ->Orwhere('po_number','like','%POC2-'.$years_combo.'%')->where('type', '=', 'PO')->count(),1);
                                //$data['po_number'] = "POI2-" . $this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('type', '=', 'PO')->count(),1);
                            } else {
                                $data['po_number'] = "POI3-".$this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI3-'.$years_combo.'ID%')
                                                                    ->Orwhere('po_number','like','%POC3-'.$years_combo.'ID%')->where('type', '=', 'PO')->count(),2);
                                //$data['po_number'] = "POI3-" . $this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%ID%')->where('type', '=', 'PO')->count(),2);
                            }
                            $data['type'] ="PO";
                        } else {
                            $item_type = $this->check_item_type($quotation_id,$ByItemSupplier->supplier_id);
                            if ($item_type == "Direct Items") {
                                //$data['po_number'] = "WOI2-" . $this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('type', '=', 'WO')->count());
                                $data['po_number'] = "WOI2-".$this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%WOI2-'.$years_combo.'%')
                                            ->Orwhere('po_number','like','%WOC2-'.$years_combo.'%')->where('type', '=', 'WO')->count());
                            }
                            else{
                                $data['po_number'] = "WOI3-".$this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%WOI3-'.$years_combo.'%')
                                                            ->Orwhere('po_number','like','%WOC3-'.$years_combo.'%')->where('type', '=', 'WO')->count());
                                //$data['po_number'] = "WOI3-" . $this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('type', '=', 'WO')->count());
                            }
                            $data['type'] ="WO";
                        }
                        $data['created_at'] = date('Y-m-d H:i:s');
                        $data['updated_at'] = date('Y-m-d H:i:s');
                        $data['rq_master_id'] = $quotation_id;
                        $data['status'] = 4;
                        $data['supplier_id'] = $ByItemSupplier->supplier_id;
                        $data['po_date'] = date('Y-m-d');
                        $data['created_by'] = config('user')['user_id'];
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
           // $validation['gst'] = ['required'];
            // $validation['delivery_schedule'] = ['required', 'date'];
            // $validation['specification'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                $data['delivery_schedule'] = $request->delivery_schedule;
                $data['order_qty'] = $request->quantity;
                $data['rate'] = $request->rate;
                $data['discount'] = $request->discount;
                $data['gst']  = $request->gst;
                $data['Specification'] = $request->specification;
                $POitem = $this->inv_final_purchase_order_item->updatedata(['id' => $id], $data);
                $po_master =inv_final_purchase_order_rel::where('item','=', $id)->pluck('master')->first();
                $master_data['status'] = 4;
                $po_update = $this->inv_final_purchase_order_master->updatedata(['inv_final_purchase_order_master.id'=>$po_master],$master_data);
                $request->session()->flash('success', "You have successfully edited a  purchase order item!");
                return redirect("inventory/final-purchase-item-edit/" . $id);
            }
            if ($validator->errors()->all()) {
                return redirect("inventory/final-purchase-item-edit/" . $id)->withErrors($validator)->withInput();
            }
        }
        $data = $this->inv_final_purchase_order_item->get_purchase_order_single_item(['inv_final_purchase_order_item.id' => $id]);
        $gst = $this->inventory_gst->get_gst();
        return view('pages.purchase-details.final-purchase.final-purchase-item-edit', compact('data','gst'));

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
                            if($request->status == 1)
                            {
                                $excess_order_of = inv_final_purchase_order_master::where('id','=',$request->po_id)->pluck('excess_order_of')->first();
                
                                if($excess_order_of==0)
                                {
                                $done =$this->order_approved_mail_with_report($request->po_id);
                                }
                            }
                            if($request->status == 0){
                                $po_number = inv_final_purchase_order_master::where('id','=',$request->po_id)->pluck('po_number')->first();
                                $replaceWith = 'C';
                                $findStr = 'I';
                                $position = strpos($po_number, $findStr);
                                if ($position !== false) {
                                    $poc_no = substr_replace($po_number, $replaceWith, $position, strlen($findStr));
                                    $this->inv_final_purchase_order_master->updatedata(['id'=>$request->po_id],['po_number'=>$poc_no]);
                                }
                                $data['final_purchase'] = $this->inv_final_purchase_order_item->get_purchase_order_single_item_receipt(['inv_final_purchase_order_master.id' => $request->po_id]);
                                $data['items'] = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $request->po_id]);
                                //print_r( json_encode($data['items']));exit;
                                $data['terms_condition'] = DB::table('po_fpo_master_tc_rel')
                                    ->select('po_supplier_terms_conditions.terms_and_conditions')
                                    ->join('po_supplier_terms_conditions', 'po_supplier_terms_conditions.id', '=', 'po_fpo_master_tc_rel.terms_id')
                                    ->where('fpo_id', $request->po_id)
                                    ->first();
                                $data['type'] = 'cancel';

                                $pdf = PDF::loadView('pages.purchase-details.final-purchase.final-purchase-pdf', $data);
                                $pdf->set_paper('A4', 'landscape');
                                $po_master = $this->inv_final_purchase_order_master->find_po_data(['inv_final_purchase_order_master.id' => $request->po_id]);
                                $message = new OrderCancellation($po_master);
                                $message->attachData($pdf->output(), "cancellation-report.pdf");
                                Mail::to('komal.murali@gmail.com')->send($message);
                                
                            }
                            if($request->order_type=='wo')
                            $request->session()->flash('success', "You have successfully ".$status." a  Work Order ");
                            else
                            $request->session()->flash('success', "You have successfully ".$status." a  Purchase Order ");

                if(isset($request->poc))
                {
                    if($request->order_type)
                    return redirect('inventory/final-purchase/cancellation?order_type='.$request->order_type);
                    else
                    return redirect('inventory/final-purchase/cancellation');
                }
                else if(isset($request->poa))
                {
                    if($request->order_type)
                    return redirect('inventory/final-purchase/approval?order_type='.$request->order_type);
                    else
                    return redirect('inventory/final-purchase/approval');
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
            if($request->order_type=='wo')
            $request->session()->flash('success', "You have successfully deleted a  work order master !");
            else
            $request->session()->flash('success', "You have successfully deleted a  purchase order master !");
        }
        if($request->order_type)
        return redirect('inventory/final-purchase?order_type='.$request->order_type);
        else
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
    public function purchaseOderApproval(Request $request){
        $condition1 = [];
        $wherein = [4,5];
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
        return view('pages.purchase-details.final-purchase.final-purchase-approval', compact('data'));
    }

    public function Approve(Request $request)
    {
        if($request->check_approve)
        {
            foreach($request->check_approve as $po_id){
                $data = ['inv_final_purchase_order_master.status'=>1,
                'inv_final_purchase_order_master.processed_by'=>config('user')['user_id'],
                'inv_final_purchase_order_master.processed_date'=>date('Y-m-d'),
                'inv_final_purchase_order_master.updated_at'=>date('Y-m-d H:i:s')];
                $success[]=$this->inv_final_purchase_order_master->updatedata(['inv_final_purchase_order_master.id'=>$po_id],$data);
                $excess_order_of = inv_final_purchase_order_master::where('id','=',$po_id)->pluck('excess_order_of')->first();
                
                if($excess_order_of==0)
                {
                $done =$this->order_approved_mail_with_report($po_id);
                }

            }
        }
        if($request->check_hold)
        {
            foreach($request->check_hold as $po_id){
                $data = ['inv_final_purchase_order_master.status'=>5,
                'inv_final_purchase_order_master.processed_by'=>config('user')['user_id'],
                'inv_final_purchase_order_master.processed_date'=>date('Y-m-d'),
                'inv_final_purchase_order_master.updated_at'=>date('Y-m-d H:i:s')];
                $success[]=$this->inv_final_purchase_order_master->updatedata(['inv_final_purchase_order_master.id'=>$po_id],$data);
            }
        }
        if($request->check_reject)
        {
            foreach($request->check_reject as $po_id){
                $data = ['inv_final_purchase_order_master.status'=>0,
                'inv_final_purchase_order_master.processed_by'=>config('user')['user_id'],
                'inv_final_purchase_order_master.processed_date'=>date('Y-m-d'),
                'inv_final_purchase_order_master.updated_at'=>date('Y-m-d H:i:s')];
                $success[]=$this->inv_final_purchase_order_master->updatedata(['inv_final_purchase_order_master.id'=>$po_id],$data);
                $done =$this->order_cancellation_mail_with_report($po_id);
            }
        }
        if(count($success) >0)
        {
            if($request->order_type=='wo')
            $request->session()->flash('success', "You have successfully changed status of ".count($success)."  Work Order ");
            else 
            $request->session()->flash('success', "You have successfully changed status of ".count($success)."  Purchase Order ");
        }
        if($request->order_type)
        return redirect('inventory/final-purchase/approval?prsr='.$request->order_type);
        else
        return redirect('inventory/final-purchase/approval');
    }

    public function order_cancellation_mail_with_report($po_id)
    {
        $po_number = inv_final_purchase_order_master::where('id','=',$po_id)->pluck('po_number')->first();
        $replaceWith = 'C';
        $findStr = 'I';
        $position = strpos($po_number, $findStr);
        if ($position !== false) 
        {
            $poc_no = substr_replace($po_number, $replaceWith, $position, strlen($findStr));
            $this->inv_final_purchase_order_master->updatedata(['id'=>$po_id],['po_number'=>$poc_no]);
        }
        $data['final_purchase'] = $this->inv_final_purchase_order_item->get_purchase_order_single_item_receipt(['inv_final_purchase_order_master.id' => $po_id]);
        $data['items'] = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $po_id]);
                                //print_r( json_encode($data['items']));exit;
        $data['terms_condition'] = DB::table('po_fpo_master_tc_rel')
                                    ->select('po_supplier_terms_conditions.terms_and_conditions')
                                    ->join('po_supplier_terms_conditions', 'po_supplier_terms_conditions.id', '=', 'po_fpo_master_tc_rel.terms_id')
                                    ->where('fpo_id', $po_id)
                                    ->first();
        $data['type'] = 'cancel';
        $pdf = PDF::loadView('pages.purchase-details.final-purchase.final-purchase-pdf', $data);
        $pdf->set_paper('A4', 'landscape');
        $po_master = $this->inv_final_purchase_order_master->find_po_data(['inv_final_purchase_order_master.id' => $po_id]);
        $message = new OrderCancellation($po_master);
        $message->attachData($pdf->output(), "cancellation-report.pdf");
        Mail::to('shilma33@gmail.com')->send($message);
    }

    public function order_approved_mail_with_report($po_id)
    {
        
        $data['final_purchase'] = $this->inv_final_purchase_order_item->get_purchase_order_single_item_receipt(['inv_final_purchase_order_master.id' => $po_id]);
        $data['items'] = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $po_id]);
                                //print_r( json_encode($data['items']));exit;
        $data['terms_condition'] = DB::table('po_fpo_master_tc_rel')
                                    ->select('po_supplier_terms_conditions.terms_and_conditions')
                                    ->join('po_supplier_terms_conditions', 'po_supplier_terms_conditions.id', '=', 'po_fpo_master_tc_rel.terms_id')
                                    ->where('fpo_id', $po_id)
                                    ->first();
        $data['type'] = 'approval';
        $pdf = PDF::loadView('pages.purchase-details.final-purchase.final-purchase-pdf', $data);
        $pdf->set_paper('A4', 'landscape');
        $po_master = $this->inv_final_purchase_order_master->find_po_data(['inv_final_purchase_order_master.id' => $po_id]);
        $message = new OrderApproved($po_master);
        $message->attachData($pdf->output(), "order-report.pdf");
        Mail::to('shilma33@gmail.com')->cc(['shilma33@gmail.com'])->send($message);
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


/*Supplier Invoice */
    public function supplierInvoiceAdd(Request $request)
    {
        if ($request->isMethod('post'))
        {
            //$validation['invoice_number'] = ['required','unique:inv_supplier_invoice_master'];
            $validation['invoice_number'] = ['required'];
            $validation['invoice_date'] = ['required'];
            $validation['po_item_id'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $data['invoice_number'] = $request->invoice_number;
                $data['invoice_date'] = date('Y-m-d',strtotime($request->invoice_date));
                //$data['transaction_date'] =date('Y-m-d');
                $data['created_by'] = config('user')['user_id'];
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                if($request->order_type=='wo')
                $data['type'] = 'WO';
                else
                $data['type'] = 'PO';
                // echo $request->po_item_id[0];
                // exit;
                // if($request->supplier){
                //     $data['supplier_id'] = inv_supplier::where('vendor_name', 'like', '%' . $request->supplier . '%')->pluck('id')->first();
                // }
                // if($request->po_no){
                //     $data['supplier_id'] = inv_final_purchase_order_master::where('inv_final_purchase_order_master.id', '=', $request->po_item_id[0])->pluck('supplier_id')->first();
                // }
                $order_master_id = inv_final_purchase_order_rel::where('item','=', $request->po_item_id[0])->pluck('master')->first();
                $order_master = $this->inv_final_purchase_order_master->get_master_data(['inv_final_purchase_order_master.id' =>$order_master_id]);
                $data['supplier_id'] = $order_master->supplier_id;
                $SIMaster = $this->inv_supplier_invoice_master->insert_data($data,$request->po_item_id);
                if($SIMaster)
                {
                    $request->session()->flash('success', "You have successfully created a  supplier invoice master !");
                    if($request->order_type)
                    return redirect("inventory/supplier-invoice-add?order_type=".$request->order_type);
                    else
                    return redirect("inventory/supplier-invoice-add");
                }
            }
            if($validator->errors()->all())
             {
               
                // if($request->order_type)
                // return redirect("inventory/supplier-invoice-add?order_type=".$request->order_type)->withErrors($validator)->withInput();
                // else
                // return redirect("inventory/supplier-invoice-add")->withErrors($validator)->withInput();
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        else
        {
            if($request->supplier || $request->po_no)
            {
                if ($request->supplier) {
    
                    $condition1[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
                    $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
                }
                if ($request->po_no) {
                    $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $request->po_no . '%'];
                    $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
                    
                }
                if($request->order_type)
                {
                    if($request->order_type=='wo')
                    {
                        $condition1[] = ['inv_final_purchase_order_master.type', '=', 'WO'];
                    }
                    else
                    {
                        $condition1[] = ['inv_final_purchase_order_master.type', '=', 'PO'];
                    }
                }
                else{
                    $condition1[] = ['inv_final_purchase_order_master.type', '=', 'PO'];
                }
                //$po_data = $this->inv_final_purchase_order_master->get_purchase_master_list_not_in_invoice($condition1);
                $po_data = $this->inv_final_purchase_order_master->get_purchase_master_list_with_condition($condition1);
                
                //$po_data = inv_final_purchase_order_master::where('inv_final_purchase_order_master.status','=',1)->get();
                //print_r(json_encode($po_data));exit;
                $po_items=[];
                foreach($po_data as $po)
                {
                    $po_items[] = inv_final_purchase_order_rel::select('inv_final_purchase_order_rel.master','inv_final_purchase_order_rel.item','inv_final_purchase_order_item.order_qty','inv_final_purchase_order_item.qty_to_invoice','inv_final_purchase_order_item.current_invoice_qty',
                                    'inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.gst','inventory_rawmaterial.item_code','inventory_rawmaterial.short_description',
                                    'inv_unit.unit_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst','inv_item_type.type_name','inv_final_purchase_order_master.po_number','inv_supplier.vendor_name')
                                            ->leftJoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
                                            ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                                            ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                                            ->leftjoin('inv_supplier','inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                                            ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                                            ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                            ->leftjoin('inventory_gst','inventory_gst.id','=','inv_final_purchase_order_item.gst' )
                                            ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                                            ->where('inv_final_purchase_order_rel.master','=',$po['id'])
                                            ->where('inv_final_purchase_order_item.qty_to_invoice','!=',0)
                                            ->get();
                }
               //print_r(json_encode($po_items));exit;
                $data = [];
                foreach($po_items as $po_item)
                {
                    foreach($po_item as $item)
                    {
                        $data[]=['master'=>$item['master'],
                                'po_number'=>$item['po_number'],
                                'po_item'=>$item['item'],
                                'type'=>$item['type_name'],
                                'item_code'=>$item['item_code'],
                                'short_description'=>$item['short_description'],
                                'order_qty'=>$item['order_qty'],
                                'qty_to_invoice'=>$item['qty_to_invoice'],
                                'current_invoice_qty'=>$item['current_invoice_qty'],
                                'unit_name'=>$item['unit_name'],
                                'rate'=>$item['rate'],
                                'discount'=>$item['discount'],
                                'igst'=>$item['igst'],
                                'sgst'=>$item['sgst'],
                                'cgst'=>$item['cgst'],
                                'vendor'=>$item['vendor_name']
                            ];
                    }
                }
                //print_r(json_encode($data));exit;
                return view('pages.purchase-details.supplier-invoice.supplier-invoice-add',compact('data'));
            }
            return view('pages.purchase-details.supplier-invoice.supplier-invoice-add');
        }
    }

    public function PartialSupplierInvoice(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $validation['po_item_id'] = ['required'];
            $validation['partial_invoice_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $po_item = inv_final_purchase_order_item::where('id', $request->po_item_id)->first();
                $qty_update = $po_item['qty_to_invoice']-$request->partial_invoice_qty;
                $po_item_update = $this->inv_final_purchase_order_item->updatedata(['inv_final_purchase_order_item.id'=>$request->po_item_id], ['inv_final_purchase_order_item.qty_to_invoice'=>$qty_update,'inv_final_purchase_order_item.current_invoice_qty'=>$request->partial_invoice_qty]);
                $request->session()->flash('success', "You have successfully updated a  Partial invoice quantity !");
                // if($request->order_type)
                // return redirect("inventory/supplier-invoice-add?order_type=".$request->order_type);
                // else
                // return redirect("inventory/supplier-invoice-add");
                return redirect()->back();                  
            }
        }




    }

    public function supplierInvoice(Request $request)
    {
        $condition1 = [];
        
        if ($request->order_type) {
            if($request->order_type=='wo')
            $condition1[] = ['inv_supplier_invoice_master.type', '=', 'WO'];
            else
            $condition1[] = ['inv_supplier_invoice_master.type', '=', 'PO'];
        }
        if (!$request->order_type) {
            $condition1[] = ['inv_supplier_invoice_master.type', '=', 'PO'];
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


    // public function supplierInvoiceAdd1(Request $request)
    // {
    //     if ($request->isMethod('post')) {
    //         $validation['invoice_number'] = ['required'];
    //         $validation['po_id'] = ['required'];
    //         $validator = Validator::make($request->all(), $validation);

    //         if (!$validator->errors()->all()) 
    //         {
    //             $data['po_master_id'] = $request->po_id;
    //             $data['created_at'] = date('Y-m-d H:i:s');
    //             $data['updated_at'] = date('Y-m-d H:i:s');
    //             $order_master = $this->inv_final_purchase_order_master->get_master_data(['inv_final_purchase_order_master.id' => $request->po_id]);
    //             $data['supplier_id'] = $order_master->supplier_id;
        
    //             $data['invoice_number'] = $request->invoice_number;
    //             $data['invoice_date'] = date('Y-m-d');
    //             $data['created_by'] = config('user')['user_id'];
                
    //             $SIMaster = $this->inv_supplier_invoice_master->insert_data($data);
    //             $request->session()->flash('success', "You have successfully created a  supplier invoice master !");
    //             if($request->order_type)
    //             return redirect("inventory/supplier-invoice-add?order_type=".$request->order_type);
    //             else
    //             return redirect("inventory/supplier-invoice-add");
    //         }
    //         if ($validator->errors()->all()) {
    //             if($request->order_type)
    //             return redirect("inventory/supplier-invoice-add?order_type=".$request->order_type)->withErrors($validator)->withInput();
    //             else
    //             return redirect("inventory/supplier-invoice-add")->withErrors($validator)->withInput();
    //         }
    //     }
    //     else
    //     {
    //         if ($request->order_type == "wo") {
    //             $condition1[] = ['inv_final_purchase_order_master.type','=', "WO"];
    //         }else{
    //             $condition1[] = ['inv_final_purchase_order_master.type','=', "PO"];
    //         }
    //         if ($request->supplier) {
    //             $condition1[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
    //         }
    
    //         if ($request->from) {
    //             $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
    //             $condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
    //         }

    //         if ($request->po_no) {
    //             $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $request->po_no . '%'];
    //         }
    //         $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
    //         $data['po_data'] = $this->inv_final_purchase_order_master->get_purchase_master_list_not_in_invoice($condition1);
    //         $condition[] = ['user.status', '=', 1];
    //         $data['users'] = $this->User->get_all_users($condition);
    //         return view('pages.purchase-details.supplier-invoice.supplier-invoice-add1', compact('data'));
    //     }
    // }

    public function supplierInvoiceEdit1(Request $request)
    {
        //echo "ff";exit;
        if ($request->isMethod('post')) 
        {
            $validation['invoice_number'] = ['required'];
            //$validation['invoice_number'] = ['required','unique:inv_supplier_invoice_master,invoice_number,'.$this->inv_supplier_invoice_master->id];
            $validation['invoice_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) 
            {
                
                $data['invoice_number'] = $request->invoice_number;
                $data['invoice_date'] = date('Y-m-d',strtotime($request->invoice_date));
                $data['created_at'] = date('Y-m-d H:i:s',strtotime($request->transaction_date));
                $data['updated_at'] = date('Y-m-d H:i:s');

                $invoice_update= $this->inv_supplier_invoice_master->updatedata(['inv_supplier_invoice_master.id' => $request->invoice_id], $data);
                $items = $this->inv_supplier_invoice_item->get_supplier_invoice_item(['inv_supplier_invoice_rel.master' => $request->invoice_id]);
                $item_count = count($items);
                for($i=1;$i<=$item_count;$i++)
                {
                    $update[] = inv_supplier_invoice_item::where('id', '=', $_POST['item'.$i])->update(['order_qty' => $_POST['qty'.$i]]);
                    
                }
                if($invoice_update && $update)
                $request->session()->flash('success', "You have successfully updated a supplier invoice  !");
                if($request->order_type)
                return redirect("inventory/supplier-invoice?order_type=".$request->order_type);
                else
                return redirect("inventory/supplier-invoice");

            }
            if ($validator->errors()->all()) {
                if($request->order_type)
                return redirect('inventory/supplier-invoice?order_type='.$request->order_type)->withErrors($validator)->withInput();
                else
                return redirect("inventory/supplier-invoice")->withErrors($validator)->withInput();
            }
        }
    }

    public function supplier_invoice_delete(Request $request, $id)
    {
        $invoice_items = inv_supplier_invoice_rel::where('master','=',$id)->get();
        foreach($invoice_items as $item)
        {
            $invoice_item = inv_supplier_invoice_item::where('id','=',$item['item'])->first();
            $po_item = inv_final_purchase_order_item::where('id','=',$invoice_item['po_item_id'])->first();
            //print_r($po_item);exit;
            $update_qty =$po_item['qty_to_invoice']+$invoice_item['order_qty'];
            inv_final_purchase_order_item::where('id','=',$po_item['id'])->update(['qty_to_invoice'=>$update_qty]);
        }
        $this->inv_supplier_invoice_master->deleteData(['id' => $id]);
        $request->session()->flash('success', "You have successfully deleted a supplier invoice master !");
        return redirect("inventory/supplier-invoice");
    }
    public function supplierInvoiceItemEdit1(Request $request, $master, $item)
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

    public function generateFinalPurchasePdf($id, $order=null, Request $request)
    {

        $data['final_purchase'] = $this->inv_final_purchase_order_item->get_purchase_order_single_item_receipt(['inv_final_purchase_order_master.id' => $id]);
        $data['items'] = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $id]);
        //print_r( json_encode($data['items']));exit;
        $data['terms_condition'] = DB::table('po_fpo_master_tc_rel')
            ->select('po_supplier_terms_conditions.terms_and_conditions')
            ->join('po_supplier_terms_conditions', 'po_supplier_terms_conditions.id', '=', 'po_fpo_master_tc_rel.terms_id')
            ->where('fpo_id', $id)
            ->first();
        $data['type'] = $request->order;

        $pdf = PDF::loadView('pages.purchase-details.final-purchase.final-purchase-pdf', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "purchase-order_" . $data['final_purchase']['vendor_name'] . "_" . $data['final_purchase']['po_number'];
        return $pdf->stream($file_name . '.pdf');
        //return $pdf->download('final-purchase.pdf');
    }

    public function exportFinalPurchaseAll()
    {
        $status = "all";
        return Excel::download(new FinalPurchaseOrderExport($status), 'Purchase/WorkOrder' . date('d-m-Y') . '.xlsx');
    }
    public function exportFinalPurchaseOpen()
    {
        $status = 1;
        return Excel::download(new FinalPurchaseOrderExport($status), 'Purchase/WorkOrder' . date('d-m-Y') . '.xlsx');
    }

    public function exportFinalPurchase(Request $request)
    {
        if($request)
        {
            return Excel::download(new FinalPurchaseOrderExport($request), 'PurchaseWorkOrder' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new FinalPurchaseOrderExport($request), 'PurchaseWorkOrder' . date('d-m-Y') . '.xlsx');
        }
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
    public function find_currency_code($rq_id,$supplierId)
    {
        $currency = inv_purchase_req_quotation_item_supp_rel::where('quotation_id','=',$rq_id)->where('supplier_id','=',$supplierId)
        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id', '=', 'inv_purchase_req_quotation_item_supp_rel.currency')
                            ->pluck('currency_code')->first();
        return $currency;
    }

    public function getOrderItems(Request $request)
    {
        $po_id = $request->po_id;
        $items = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $po_id]);
        $data = '<table class="table table-bordered mg-b-0">';
        $i=1;
        foreach($items as $item){
            $data .= '<tr>
                    <td style="vertical-align: middle;">' . $item->item_code . '</td>
                    <td><label>Actual Order Quantity </label> <input type="text" class="order-qty orderQty'.$item->purchase_item_id.'" id="order-qty" name="qty" value="'.$item->order_qty+$item->cancelled_qty.'" disabled></td>
                    <td><label>Quantity to be accepted </label> <input type="number" step="0.01" class="accept-qty orderQtyAccept'.$item->purchase_item_id.'" oninput="quantityCheck('.$item->purchase_item_id.','."'accept'".')" id="accept-qty" name="qty'. $i.'" value="'.$item->order_qty .'" ></td>
                    <td><label>Quantity to be cancelled </label><input class="cancel-qty  orderQtyReject'.$item->purchase_item_id.'" id="cancel-qty"  oninput="quantityCheck('.$item->purchase_item_id.','."'reject'".')" type="number" step="0.01" name="cancel_qty'. $i.'"  value="'.$item->cancelled_qty.'"></td>
                    <input type="hidden" name="purchase_item_id'. $i.'" value="'.$item->purchase_item_id.'">
                    <tr>';
                    $i++;
        }
        $data .= '</table>';
        return $data; 
    }

    public function partialCancellation(Request $request){
        $items = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $request->po_id]);
        $item_count = count($items);
        //echo $item_count;exit;
        for($i=1;$i<=$item_count;$i++)
        {
            $purchase_item = $this->inv_final_purchase_order_item->get_purchase_order_single_item(['inv_final_purchase_order_item.id' => $_POST['purchase_item_id'.$i]]);
            $tot_qty = $_POST['cancel_qty'.$i]+ $_POST['qty'.$i];
            // if($tot_qty!=$purchase_item['order_qty'])
            // {
            //     $request->session()->flash('error', "Accepted and cancelled quantity is not matching.. !");
            //     if($request->order_type)
            //     return redirect('inventory/final-purchase/cancellation?prsr='.$request->order_type);
            //     else
            //     return redirect('inventory/final-purchase/cancellation');
            // }
            $datas['cancelled_qty'] = $_POST['cancel_qty'.$i];
                $success[] = $this->inv_final_purchase_order_item->updatedata(['id' => $_POST['purchase_item_id'.$i]], $datas);
                $ys[]= inv_final_purchase_order_item::where('id','=',$_POST['purchase_item_id'.$i])->decrement('order_qty',$_POST['cancel_qty'.$i]);
                $ys1[]= inv_final_purchase_order_item::where('id','=',$_POST['purchase_item_id'.$i])->decrement('qty_to_invoice',$_POST['cancel_qty'.$i]);
                $item = $this->inv_final_purchase_order_item->get_purchase_order_single_item(['inv_final_purchase_order_item.id' => $_POST['purchase_item_id'.$i]]);
                $item['cancelledQty'] = $_POST['cancel_qty'.$i];
                $items_data[] = $item;
            
        
        }
        //print_r(json_encode($items_data));exit;
        $data['items'] = $items_data;
        $data['final_purchase'] = $this->inv_final_purchase_order_item->get_purchase_order_single_item_receipt(['inv_final_purchase_order_master.id' => $request->po_id]);
        
       /* $job = (new \App\Jobs\PartialOrderCancellationMail($PartialcancelDatas))
                    ->delay(
                        now()
                            ->addSeconds(3)
                    );
                    dispatch($job);*/
        $pdf = PDF::loadView('pages.purchase-details.final-purchase.partialcancel-pdf', $data);
        $pdf->set_paper('A4', 'landscape');
        $po_master = $this->inv_final_purchase_order_master->find_po_data(['inv_final_purchase_order_master.id' => $data['final_purchase']['po_id']]);
        $message = new PartialOrderCancellation($po_master);
        $message->attachData($pdf->output(), "partial-order-cancellation-report.pdf");
        Mail::to('shilma33@gmail.com')->send($message);
        if(count( $success)==$item_count)
        {
        //$request->session()->flash('success', "");
        if($request->order_type=='wo')
            $request->session()->flash('success', "You have successfully updated Work order quantity !");
            else 
            $request->session()->flash('success', "You have successfully updated Purchase order quantity !");
        }
        if($request->order_type)
        return redirect('inventory/final-purchase/cancellation?prsr='.$request->order_type);
        else
        return redirect('inventory/final-purchase/cancellation');
    }

    public function getExcessQty(Request $request)
    {
        $condition1 = [];
            if (!$request->pr_no && !$request->rq_no && !$request->supplier && !$request->po_from && !$request->processed_from ) {
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
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

            if ($request->po_no) {
                $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $request->po_no . '%'];
            }
            

        $data['users'] = $this->User->get_all_users([]);
        $data['po_data'] = $this->inv_final_purchase_order_master->get_purchase_master_list($condition1);
        return view('pages.purchase-details.final-purchase.excess-order-qty', compact('data'));
    }

    public function viewFinalPurchaseExcess($id)
    {
        $data['master'] = $this->inv_final_purchase_order_master->get_master_details(['inv_final_purchase_order_master.id' => $id]);
        $data['items'] = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $id]);
        return view('pages.purchase-details.final-purchase.excess-order-items', compact('data'));
    }

    public function excessPurchaseOrder(Request $request)
    {
        if(date('m')==01 || date('m')==02 || date('m')==03)
        {
            $years_combo = date('y', strtotime('-1 year')).date('y');
        }
        else
        {
            $years_combo = date('y').date('y', strtotime('+1 year'));
        }

        $item_id =$request->purchase_item_id;
        $po_id = $request->po_id;
        $master = $this->inv_final_purchase_order_master->get_master_details(['inv_final_purchase_order_master.id' => $po_id]);
        $item = $this->inv_final_purchase_order_item->get_purchase_order_single_item(['inv_final_purchase_order_item.id' => $item_id]);
        $item_type = $this->check_item_type($master['rq_master_id'],$master['supplier_id']);
        if($master['type']=='PO')
        {
            if ($item_type == "Finished Goods") 
            {
                $data['po_number'] = "POI1-" .$this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI2-'.$years_combo.'%')
                                         ->Orwhere('po_number','like','%POC1-'.$years_combo.'%')->where('type', '=', 'PO')->count(),1);
            }
            else if ($item_type == "Direct Items") 
            {
                // $count = DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI2-'.$years_combo.'%')
                // ->Orwhere('po_number','like','%POC2-'.$years_combo.'%')->where('type', '=', 'PO')->count();
                // echo $count;exit;
                $data['po_number'] = "POI2-" .$this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI2-'.$years_combo.'%')
                                         ->Orwhere('po_number','like','%POC2-'.$years_combo.'%')->where('type', '=', 'PO')->count(),1);
            } else {
                $data['po_number'] = "POI3-".$this->po_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%POI3-'.$years_combo.'ID%')
                                                    ->Orwhere('po_number','like','%POC3-'.$years_combo.'ID%')->where('type', '=', 'PO')->count(),2);
            }
            $data['type'] ="PO";
        }
        else
        {
            if ($item_type == "Direct Items") 
            {
                $data['po_number'] = "WOI2-".$this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%WOI2-'.$years_combo.'%')
                                                ->Orwhere('po_number','like','%WOC2-'.$years_combo.'%')->where('type', '=', 'WO')->count());
            }
            else{
                $data['po_number'] = "WOI3-".$this->wo_num_gen(DB::table('inv_final_purchase_order_master')->where('po_number','like','%WOI3-'.$years_combo.'%')
                                                ->Orwhere('po_number','like','%WOC3-'.$years_combo.'%')->where('type', '=', 'WO')->count());
            }
            $data['type'] ="WO";
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['rq_master_id'] = $master['rq_master_id'];
        $data['status'] = 4;
        $data['supplier_id'] = $master['supplier_id'];
        $data['po_date'] = $master['po_date'];
        $data['created_by'] = config('user')['user_id'];
        $data['excess_order_of']=$po_id;
        //$inv_supplier_terms = DB::table('inv_supplier')->select('*')->where('id', $data['supplier_id'])->first();
        //$$inv_supplier_terms=[];
        $POMaster =inv_final_purchase_order_master::insertGetId($data);
        $purchase_item = inv_final_purchase_order_item::insertGetId([
            'order_qty'=>$request->excess_qty,
            'qty_to_invoice'=> $request->excess_qty,
            'rate'=>$item['rate'],
            'discount'=>$item['discount'],
            'gst'=>$item['gst'],
            'Specification'=>$item['Specification'],
            'item_id'=>$item['purchase_item_id'],
            'status'=>1,
        ]);
        $rel = inv_final_purchase_order_rel::insert([
            'master'=>$POMaster,
            'item'=>$purchase_item,
        ]);

        if($POMaster && $purchase_item && $rel)
        {
            if($master['type']=='PO')
            $request->session()->flash('success', "You have successfully created a Purchase order for the excess quantity  !");
            else 
            $request->session()->flash('success', "You have successfully created a Work order for the excess quantity  !");
            return redirect('inventory/final-purchase-view/'.$po_id.'/excess-quantity');
        }
        return redirect('inventory/final-purchase-view/'.$po_id.'/excess-quantity');

    }

    public function getPurchaseOrderItem(Request $request)
    {
        //$items = $this->inv_final_purchase_order_item->get_purchase_items(['inv_final_purchase_order_rel.master' => $request->po_id]);
        $items = $this->inv_supplier_invoice_item->get_supplier_invoice_item(['inv_supplier_invoice_rel.master' =>$request->invoice_id]);
        //print_r(json_encode($items));exit;
        $data = '<div class="table-responsive">
           <table class="table table-bordered mg-b-0" id="example1">
           <tr>
                <th rowspan="2">Item Code</th>
                <th rowspan="2">Qty</th>
                <th rowspan="2">Rate</th>
                <th rowspan="2">Value</th>
                <th colspan="2">Disc</th>
                <th colspan="2">IGST</th>
                <th colspan="2">SGST</th>
                <th colspan="2">CGST</th>
           </tr>
           <tr>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
            </tr>';
            $total = 0;
            $total_discount = 0;
            $total_igst = 0;
            $total_cgst = 0;
            $total_sgst = 0;
            $i=1;
        foreach($items as $item)
        {
            $data .='<tr>
                    <td>'.$item['item_code'].'</td>
                    <td>
                        <div class="input-group">
                            <input type="hidden" value="'.$item['id'].'" name="item'. $i.'">
                            <input type="text" class="order-qty " id="order-qty" name="qty'. $i.'" value="'.$item['order_qty'].'" aria-describedby="unit-div">
                            <div class="input-group-append">
                                <span class="input-group-text unit-div" id="unit-div">'.$item['unit_name'] .'</span>
                            </div>
                        </div>
                    </td>
                    <td>'.number_format((float)$item['rate'], 2, '.', '').'</td>
                    <td>'.number_format((float)($item['rate']* $item['order_qty']), 2, '.', '') .'</td>
                    <td>'.$item['discount'].'</td>';

                    $discount_value = ($item['rate']* $item['order_qty'])-(($item['rate']* $item['order_qty']*$item['discount'])/100);
            $data .='<td>'.number_format((float)(($item['rate']* $item['order_qty']*$item['discount'])/100), 2, '.', '').'</td>
                    <td>'.$item['igst'].'</td>
                    <td>'.number_format((float)(($discount_value*$item['igst'])/100), 2, '.', '').'</td>
                    <td>'.$item['sgst'].'</td>
                    <td>'.number_format((float)(($discount_value*$item['sgst'])/100), 2, '.', '').'</td>
                    <td>'.$item['cgst'].'</td>
                    <td>'.number_format((float)(($discount_value*$item['cgst'])/100), 2, '.', '').'</td>
                    </tr>';
                    $i++;
        } 
        $data .='</table></div>';
        return $data;

    }

    public function supplierInvoiceExport(Request $request)
    {
        if($request)
        {
            return Excel::download(new SupplierInvoiceExport($request), 'supplier_invoice' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new SupplierInvoiceExport($request), 'supplier_invoice' . date('d-m-Y') . '.xlsx');
        }
    }

    public function pendingPurchaseRealisation(Request $request)
    {
        $condition2=[];
        $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime('01-04-2023'))];
        if ($request->supplier) {
    
            $condition1[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
        }
        if ($request->po_no) {
            $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $request->po_no . '%'];
            $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
            
        }
        if ($request->item_code) {
            $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
            $condition2[] = ['inventory_rawmaterial.item_code','like','%'.$request->item_code];
        }
        if ($request->pr_no) {
            $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
            $condition2[] = ['inv_purchase_req_master.pr_no','like','%'.$request->pr_no];
        }
        if ($request->po_from) {
            $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
            $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime($request->po_from))];
            //$condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-d', strtotime($this->request->po_from))];
        }
        if ($request->po_to) {
            $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
            $condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-d', strtotime($request->po_to))];
        }
        if($request->order_type)
        {
            if($request->order_type=='wo')
            {
                $condition1[] = ['inv_final_purchase_order_master.type', '=', 'WO'];
            }
            else
            {
                $condition1[] = ['inv_final_purchase_order_master.type', '=', 'PO'];
            }
        }
        else
        {
            $condition1[] = ['inv_final_purchase_order_master.type', '=', 'PO'];
        }
        $po_data = inv_final_purchase_order_master::select(['inv_purchase_req_quotation.rq_no','inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_date',
                        'inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.status','inv_final_purchase_order_master.id as po_id','inv_final_purchase_order_master.created_at',
                        'user.f_name','user.l_name','inv_final_purchase_order_master.id'])
                            ->where($condition1)
                            ->where('inv_final_purchase_order_master.status','=',1)
                            //->join('inv_final_purchase_order_rel','inv_final_purchase_order_rel.master','=','inv_final_purchase_order_master.id')
                            ->join('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                            ->join('user','user.user_id','=','inv_final_purchase_order_master.created_by')
                            ->leftjoin('inv_supplier','inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                            ->orderby('inv_final_purchase_order_master.id','asc')
                            ->get();
        $po_items=[];
        foreach($po_data as $po)
        {
            $po_items[] = inv_final_purchase_order_rel::select('inv_final_purchase_order_rel.master','inv_final_purchase_order_rel.item','inv_final_purchase_order_item.order_qty','inv_final_purchase_order_item.qty_to_invoice','inv_final_purchase_order_item.current_invoice_qty',
                    'inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.gst','inventory_rawmaterial.item_code','inventory_rawmaterial.short_description','inventory_rawmaterial.hsn_code','inv_purchase_req_quotation.rq_no',
                    'inv_unit.unit_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst','inv_item_type.type_name','inv_final_purchase_order_master.po_number','inv_supplier.vendor_name','inv_final_purchase_order_master.po_date','inv_final_purchase_order_item.cancelled_qty',
                    'inv_final_purchase_order_master.created_at','inv_final_purchase_order_master.updated_at','user.f_name','user.l_name','inv_supplier.id as supplier_id','inv_purchase_req_quotation.quotation_id','inv_final_purchase_order_item.item_id','inv_purchase_req_master.pr_no')
                            ->leftJoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
                            ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                            ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                            ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                            ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                            ->leftjoin('inv_purchase_req_quotation', 'inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                            ->leftjoin('inv_supplier','inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                            ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                            ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
                            ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','inv_final_purchase_order_item.gst' )
                            ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_final_purchase_order_rel.master','=',$po['id'])
                            ->where('inv_final_purchase_order_item.qty_to_invoice','!=',0)
                            ->where($condition2)
                            ->get();
        }
       //print_r(json_encode($po_items));exit;
        $data = [];
        foreach($po_items as $po_item)
        {
            foreach($po_item as $item)
            {
                $data[]=['master'=>$item['master'],
                        'pr_number'=>$item['pr_no'],
                        'po_number'=>$item['po_number'],
                        'po_item'=>$item['item'],
                        'type'=>$item['type_name'],
                        'item_code'=>$item['item_code'],
                        'short_description'=>$item['short_description'],
                        'order_qty'=>$item['order_qty'],
                        'qty_to_invoice'=>$item['qty_to_invoice'],
                        'current_invoice_qty'=>$item['current_invoice_qty'],
                        'unit_name'=>$item['unit_name'],
                        'rate'=>$item['rate'],
                        'discount'=>$item['discount'],
                        'igst'=>$item['igst'],
                        'sgst'=>$item['sgst'],
                        'cgst'=>$item['cgst'],
                        'vendor'=>$item['vendor_name']
                    ];
            }
        }
        return view('pages.purchase-details.final-purchase.pending-purchase-realisation',compact('data'));
    }

    public function pendingPurchaseRealisationExport(Request $request)
    {
        if($request)
        {
            return Excel::download(new PendingPurchaseRealisationExport($request), 'R02-PendingPurchaseRealisation' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new PendingPurchaseRealisationExport($request), 'R02-PendingPurchaseRealisation' . date('d-m-Y') . '.xlsx');
        }
    }
    public function getPoNumber($invoice_id)
    {
        $po_number = inv_supplier_invoice_rel::select('inv_final_purchase_order_master.po_number') 
            ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
            ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
            ->where('inv_supplier_invoice_rel.master','=',$invoice_id)
            ->get();
            return $po_number;
    }

    public function getTermsandConditions(Request $request)
    {

        $terms = DB::table('po_fpo_master_tc_rel')
                                    ->select('po_supplier_terms_conditions.terms_and_conditions','po_supplier_terms_conditions.id')
                                    ->join('po_supplier_terms_conditions', 'po_supplier_terms_conditions.id', '=', 'po_fpo_master_tc_rel.terms_id')
                                    ->where('fpo_id', $request->po_id)
                                    ->first();
        return $terms;
    }
    public function changeTerms(Request $request)
    {
        $validation['terms'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if (!$validator->errors()->all()) {
                $data['terms_and_conditions'] = $request->terms;
                //$data['terms_id'] = $request->terms_id;
                DB::table('po_supplier_terms_conditions')->where('id','=',$request->terms_id)->update($data);
                $request->session()->flash('success', "You have successfully updated a supplier Terms and conditions !");
                return redirect()->back();
            }
            if ($validator->errors()->all()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

    }




}
