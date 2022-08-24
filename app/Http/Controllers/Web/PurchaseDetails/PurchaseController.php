<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PurchaseDetails\inv_purchase_req_quotation;
use App\Models\User;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_supplier;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_item_supp_rel;
use App\Models\PurchaseDetails\inv_final_purchase_order_master;
use App\Models\PurchaseDetails\inv_final_purchase_order_item;
use App\Models\PurchaseDetails\inv_supplier_invoice_master;
use App\Models\PurchaseDetails\inv_supplier_invoice_item;
use App\Models\PurchaseDetails\inv_supplier;


use Validator;
use DB;

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
    }

    public function getFinalPurchase()
    {

        $data['po_data'] =  $this->inv_final_purchase_order_master->get_purchase_master([]);
        return view('pages.purchase-details.final-purchase.final-purchase-list',compact('data'));
    }
    public function addFinalPurchase(Request $request,$id = null)
    {  
    if ($request->isMethod('post')) 
    {

        $validation['date'] = ['required','date'];
        $validation['create_by'] = ['required'];
        $validation['rq_master_id'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()) 
        { 

         if(!$id){
            $data['po_number'] = "PO-".$this->num_gen(DB::table('inv_final_purchase_order_master')->count());
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['rq_master_id'] = $request->rq_master_id;
            $quotation_supplier = $this->inv_purchase_req_quotation_supplier->get_suppliers_single(['inv_purchase_req_quotation_supplier.quotation_id'=>$request->rq_master_id,'inv_purchase_req_quotation_supplier.selected_supplier'=>1]);
            $data['supplier_id'] = $quotation_supplier->supplier_id;
         }
            $data['po_date'] = date('Y-m-d',strtotime($request->date));
            $data['created_by'] = $request->create_by;

            if(!$id){
                $POMaster =   $this->inv_final_purchase_order_master->insert_data($data);
                $request->session()->flash('success',  "You have successfully added a  purchase order master !");
            }else{
                 $this->inv_final_purchase_order_master->updatedata(['inv_final_purchase_order_master.id'=>$id],$data);
                $request->session()->flash('success',  "You have successfully updated a  purchase order master !");
            }
            return redirect("inventory/final-purchase-add/".$id);
        }
        if($validator->errors()->all()) 
        { 
            return redirect("inventory/final-purchase-add/".$id)->withErrors($validator)->withInput();

        }
        
    }
        $condition[] = ['user.status','=',1];
        $data['users'] = $this->User->get_all_users($condition);
        if($id){
            $data['master_data'] = $this->inv_final_purchase_order_master->get_master_data(['inv_final_purchase_order_master.id'=>$id]);
            $data['master_list'] = $this->rq_details($data['master_data']->rq_master_id,$id);

        }
        return view('pages.purchase-details.final-purchase.final-purchase-add',compact('data'));
    }

    function Edit_PO_item(Request $request,$id){

        if ($request->isMethod('post'))
        {
            $validation['quantity'] = ['required'];
            $validation['rate'] = ['required'];
            $validation['discount'] = ['required'];
            $validation['delivery_schedule'] = ['required','date'];
            $validation['specification'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 
                $data['delivery_schedule'] = $request->delivery_schedule;
                $data['order_qty'] = $request->quantity;
                $data['rate'] = $request->rate;
                $data['discount'] = $request->discount;
                $data['Specification'] = $request->specification;
                $POitem=   $this->inv_final_purchase_order_item->updatedata(['id'=>$id],$data);
                $request->session()->flash('success',  "You have successfully edited a  purchase order item!");
                return redirect("inventory/final-purchase-item-edit/".$id);
            }
            if($validator->errors()->all()) 
            { 
                return redirect("inventory/final-purchase-item-edit/".$id)->withErrors($validator)->withInput();
            }
        }
        $data = $this->inv_final_purchase_order_item->get_purchase_order_single_item(['inv_final_purchase_order_item.id'=>$id]);
        return view('pages.purchase-details.final-purchase.final-purchase-item-edit',compact('data'));
        
    }

    public function deleteFinalPurchase(Request $request,$id)
    {
        if($id){
            $this->inv_final_purchase_order_master->deleteData(['id'=>$id]);
            $request->session()->flash('success',  "You have successfully deleted a final purchase order master !");
        }
       return redirect('inventory/final-purchase');
    }
    function find_rq_number(Request $request){
        if($request->q){     
            $condition1[] = ['inv_purchase_req_quotation.rq_no','like','%'.strtoupper($request->q).'%'];
            $condition1[] = ['inv_purchase_req_quotation_supplier.selected_supplier','=',1];

            $condition2[] = ['inv_purchase_req_quotation.rq_no','like','%'.strtoupper($request->q).'%'];
            $data = $this->inv_purchase_req_quotation->get_master_filter($condition1,$condition2);
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
    }else{
        echo $this->rq_details($request->id,null);
        exit;
    }


    }

function rq_details($id,$active=null){
 $quotation   = $this->inv_purchase_req_quotation_supplier->inv_purchase_req_quotation_data(['inv_purchase_req_quotation_supplier.quotation_id'=>$id,'inv_purchase_req_quotation_supplier.selected_supplier'=>1]);
 $quotation_item   = $this->inv_purchase_req_quotation_item_supp_rel->inv_purchase_req_quotation_item_data(['inv_purchase_req_quotation_item_supp_rel.supplier_id'=>$quotation->supplier_id,'inv_purchase_req_quotation_item_supp_rel.quotation_id'=>$quotation->quotation_id]);
  
 if($active){
  $purchase_order_item =  $this->inv_final_purchase_order_item->get_purchase_order_item(['inv_final_purchase_order_rel.master'=>$active]);
  }
 
 
 $data = '<div class="row">
    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
        Supplier Quotation ('.$quotation->rq_no.')
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
        <td>'.$quotation->supplier_quotation_num.'</td>
        <td>'.date('d-m-Y',strtotime($quotation->commited_delivery_date)).'</td>
        <td>'.date('d-m-Y',strtotime($quotation->quotation_date)).'</td>
        <td>'.$quotation->contact_number.'</td>
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
        <td>'.$quotation->vendor_id.'</td>
        <td>'.$quotation->vendor_name.'</td>
    </tr>
    </tbody>

    </table><br>
    <div class="row">
    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
        if($active){
            $data .= 'Final purchase order items';
        }else{
            $data .= 'Supplier Quotation Items';
        }                             
        $data .='</label>
        <div class="form-devider"></div>
    </div>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered mg-b-0" id="example1">';
        
    if($active){
        $data .='<thead>
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
    foreach($purchase_order_item as $item){
        $data .='<tr>
                <td>'.$item->pr_no.'</td>
                <td>'.$item->item_code.'</td>
                <td>'.$item->hsn_code.'</td>
                <td>'.($item->delivery_schedule ? date('d-m-Y',strtotime($item->delivery_schedule )) : '-').'</td>
                <td>'.$item->order_qty.'</td>
                <td>'.$item->rate.'</td>
                <td>'.$item->discount.'</td>
                <td><a class="badge badge-info" style="font-size: 13px;" href="'.url("inventory/final-purchase-item-edit/".$item->id).'"><i class="fas fa-edit"></i> Edit</a></td>
            </tr>';      
        } 
     $data .='</tbody>';
    }


    if(!$active){
        $data .='<thead>
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
    foreach($quotation_item as $item){
        $data .='<tr>
                <td>'.$item->pr_no.'</td>
                <td>'.$item->item_code.'</td>
                <td>'.$item->hsn_code.'</td>
                <td>'.$item->quantity.'</td>
                <td>'.$item->rate.'</td>
                <td>'.$item->discount.'</td>
            </tr>';      
        } 
     $data .='</tbody>';
    }

    $data .=  '</table>
</div>';
return  $data;
}


    function find_po_number(Request $request){
        if($request->q){     
            $condition[] = ['inv_final_purchase_order_master.po_number','like','%'.strtoupper($request->q).'%'];
            $data =  $this->inv_final_purchase_order_master->find_po_num($condition);
        if(!empty( $data[0])){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
    }else{
        echo $this->rq_details_po($request->id,null);
        exit;
    }
    }
    function rq_details_po($id,$active=null){
         $po_master =   $this->inv_final_purchase_order_master->find_po_data(['inv_final_purchase_order_master.id'=>$id]);
         $purchase_order_item =  $this->inv_final_purchase_order_item->get_purchase_order_item(['inv_final_purchase_order_rel.master'=>$id]);
         if($active){
             $purchase_order_item =  $this->inv_supplier_invoice_item->get_supplier_invoice_item(['inv_supplier_invoice_rel.master'=>$active]);
         }

        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               Final Purchase Order  master ('.$po_master->po_number.')
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
               <td>'.date('d-m-Y',strtotime($po_master->po_date)).'</td>
               <td>'.date('d-m-Y',strtotime($po_master->created_at)).'</td>
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
               <td>'.$po_master->vendor_id.'</td>
               <td>'.$po_master->vendor_name.'</td>
           </tr>
           </tbody>
       
           </table><br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
               if($active){
                   $data .= 'Supplier Invoice items ';
               }else{
                   $data .= 'Final purchase order items ';
               }                             
               $data .='</label>
               <div class="form-devider"></div>
           </div>
           </div>
           <div class="table-responsive">
           <table class="table table-bordered mg-b-0" id="example1">';
               
           if($active){
               $data .='<thead>
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
           foreach($purchase_order_item as $item){
               $data .='<tr>
                       <td>'.$item->pr_no.'</td>
                       <td>'.$item->item_code.'</td>
                       <td>'.$item->hsn_code.'</td>
                       <td>'.$item->order_qty.'</td>
                       <td>'.$item->rate.'</td>
                       <td>'.$item->discount.'</td>
                       <td><a class="badge badge-info" style="font-size: 13px;" href="'.url("inventory/supplier-invoice-item-edit/".$active.'/'.$item->id).'"><i class="fas fa-edit"></i> Edit</a></td>
                   </tr>';      
               } 
            $data .='</tbody>';
           }
       
       
           if(!$active){
               $data .='<thead>
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
           foreach($purchase_order_item as $item){
               $data .='<tr>
                       <td>'.$item->pr_no.'</td>
                       <td>'.$item->item_code.'</td>
                       <td>'.$item->hsn_code.'</td>
                       <td>'.$item->order_qty.'</td>
                       <td>'.$item->rate.'</td>
                       <td>'.$item->discount.'</td>
                   </tr>';      
               } 
            $data .='</tbody>';
           }
       
           $data .=  '</table>
       </div>';
       return  $data;
       }

    public function supplierInvoice(Request $request)
    {
        if(count($_GET))
        {
            if ($request->po_no) {
                $condition2[] = ['inv_final_purchase_order_master.id', '=', $request->po_no];
            }
            if ($request->invoice_no) {
                $condition2[] = ['inv_supplier_invoice_master.id', '=', $request->invoice_no];
            }
            if ($request->supplier) {
                $condition2[] = ['inv_supplier.id', '=', $request->supplier];
            }
            if ($request->from) {
                $condition2[] = ['inv_supplier_invoice_master.invoice_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition2[] = ['inv_supplier_invoice_master.invoice_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
           
            $data['Requisition'] = $this->inv_supplier_invoice_master->get_supplier_inv(['inv_supplier_invoice_master.status'=>1],$condition2);
        }
        else 
        {
            $data['Requisition'] = $this->inv_supplier_invoice_master->get_supplier_inv(['inv_supplier_invoice_master.status'=>1],$condition2=null);

        }
        $data['suppliers'] = $this->inv_supplier->get_all_suppliers();
        $data['po_nos'] = $this->inv_final_purchase_order_master->get_po_nos();
        $data['invoice_nos'] = $this->inv_supplier_invoice_master->get_invoice_nos();
        //$data['Requisition'] = $this->inv_supplier_invoice_master->get_supplier_inv(['inv_supplier_invoice_master.status'=>1]);
        return view('pages.purchase-details.supplier-invoice.supplier-invoice-list',compact('data'));
    }

    public function supplierInvoiceAdd(Request $request,$id = null)
    {

        if ($request->isMethod('post')) 
        {
            $validation['invoice_number'] = ['required'];
            $validation['po_number'] = ['required'];
            $validation['date'] = ['required','date'];
            $validation['create_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()) 
            { 
                if(!$id){
                    $data['po_master_id'] = $request->po_number;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $order_master =   $this->inv_final_purchase_order_master->get_master_data(['inv_final_purchase_order_master.id'=>$request->po_number]);
                    $data['supplier_id'] = $order_master->supplier_id;
                }
                $data['invoice_number'] = $request->invoice_number;
                $data['invoice_date'] = date('Y-m-d',strtotime($request->date));
                $data['created_by'] = $request->create_by;
                if(!$id){
                    $SIMaster =    $this->inv_supplier_invoice_master->insert_data($data);
                    $request->session()->flash('success',  "You have successfully created a  supplier invoice master !");
                    return redirect("inventory/supplier-invoice-add/".$SIMaster);
                }else{
                    $this->inv_supplier_invoice_master->updatedata(['inv_supplier_invoice_master.id'=>$id],$data);
                    $request->session()->flash('success',  "You have successfully updated a supplier invoice master !");
                    return redirect("inventory/supplier-invoice-add/".$id);
                }
            }
            if($validator->errors()->all()) 
            { 
                return redirect("inventory/supplier-invoice-add/".$id)->withErrors($validator)->withInput();
            }
        }
        $condition[] = ['user.status','=',1];
        $data['users'] = $this->User->get_all_users($condition);
        if($id){
            $data['simaster'] = $this->inv_supplier_invoice_master->get_master_data(['inv_supplier_invoice_master.id'=>$id]);
            $data['master_list'] = $this->rq_details_po($data['simaster']->id,$id);
        }
        return view('pages.purchase-details.supplier-invoice.supplier-invoice-add',compact('data','id'));
    }
    function supplier_invoice_delete(Request $request,$id){
        $this->inv_supplier_invoice_master->deleteData(['id'=>$id]);
        $request->session()->flash('success',  "You have successfully deleted a supplier invoice master !");
        return redirect("inventory/supplier-invoice");
    }
    public function supplierInvoiceItemEdit(Request $request,$master,$item)
    {
        if ($request->isMethod('post')) 
        {
            $validation['quantity'] = ['required'];
            $validation['rate'] = ['required'];
            $validation['discount'] = ['required'];
            $validation['specification'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 
                $data['order_qty'] = $request->quantity;
                $data['rate'] = $request->rate;
                $data['discount'] = $request->discount;
                $data['specification'] = $request->specification;
                $this->inv_supplier_invoice_item->updatedata(['id'=>$item],$data);
                $request->session()->flash('success',  "You have successfully updated a supplier invoice item !");
                return redirect("inventory/supplier-invoice-item-edit/".$master.'/'.$item)->withErrors($validator)->withInput();
            }
            if($validator->errors()->all()) 
            { 
                return redirect("inventory/supplier-invoice-item-edit/".$master.'/'.$item)->withErrors($validator)->withInput();
            }
        }


         $data['item'] = $this->inv_supplier_invoice_item->get_si_item(['inv_supplier_invoice_item.id'=>$item]);
        return view('pages.purchase-details.supplier-invoice.supplier-invoice-list-edit',compact('data','master','item'));
    }







    public function lotAllocation()
    {
        return view('pages.purchase-details.supplier-invoice.supplier-invoice-list');
        return view('pages.purchase-details.purchase.lot-allocation');
    }
}
