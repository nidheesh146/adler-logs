<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\PurchaseDetails\inv_lot_allocation;
use App\Models\PurchaseDetails\inv_supplier_invoice_item;
use DB;
class LotAllocationController extends Controller
{
    public function __construct()
    {
        $this->inv_lot_allocation = new inv_lot_allocation;
        $this->inv_supplier_invoice_item = new inv_supplier_invoice_item;
        $this->User = new User;
    }

    public function lotAllocation()
    {
        $lot_data = $this->inv_lot_allocation->getData();
        // print_r($lot_data);exit;
        return view('pages.purchase-details.lot-allocation.lot-allocation-list', compact('lot_data'));
    }

    public function addLotAllocation(Request $request)
    {
        if ($request->isMethod('post'))
        {

            $validation['document_no'] = ['required'];
            $validation['rev_no'] = ['required'];
            $validation['rev_date'] = ['required','date'];
            $validation['invoice_id'] = ['required'];
            $validation['qty_received'] = ['required'];
            $validation['qty_accepted'] = ['required'];
            $validation['qty_rejected'] = ['required'];
            $validation['vehicle_no'] = ['required'];
            $validation['transporter_name'] = ['required'];
            $validation['mrr_no'] = ['required'];
            $validation['mrr_date'] = ['required'];
            $validation['test_report_no'] = ['required'];
            $validation['test_report_date'] = ['required'];
            $validation['prepared_by'] = ['required'];
            $validation['si_id'] = ['required'];

            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 

                $data['lot_number'] = "LT-".$this->num_gen(DB::table('inv_lot_allocation')->count()); //$request->lot_number;
                $data['doc_number'] = $request->document_no;
                $data['rev_number'] = $request->rev_no;
                $data['rev_date'] = $request->rev_date;
                $data['qty_received'] = $request->qty_received;
                $data['qty_accepted'] = $request->qty_accepted;
                $data['qty_rejected'] = $request->qty_rejected;
                $data['vehicle_number'] = $request->vehicle_no;
                $data['transporter_name'] = $request->transporter_name;
                $data['mrr_number'] = $request->mrr_no;
                $data['mrr_date'] = $request->mrr_date;
                $data['test_report_number'] = $request->test_report_no;
                $data['test_report_date'] = $request->test_report_date;
                $data['prepared_by'] = $request->prepared_by;
                $data['approved_by'] = $request->approved_by;
             
            if(!$request->lot_id){
                $invoice_item = $this->inv_supplier_invoice_item->get_single_supplier_invoice_item_id(['inv_supplier_invoice_item.id'=>$request->si_id]);
                $data['pr_item_id'] = $invoice_item->requisition_item_id;
                $data['si_invoice_item_id'] = $invoice_item->invoice_item_id;
                $data['supplier_id'] = $invoice_item->supp_id;
                $data['po_id'] = $invoice_item->po_master_id;
              }

                if($request->lot_id){
                    $lot =$this->inv_lot_allocation->updatedata(['inv_lot_allocation.id'=>$request->lot_id],$data);
                    $request->session()->flash('success',  "You have successfully updated lot allocation !");
                } 
                else{
                    $lot =$this->inv_lot_allocation->insertdata($data);
                    $request->session()->flash('success',  "You have successfully completed lot allocation !");
                }
                
                return redirect("inventory/lot-allocation-list");
            }
            if($validator->errors()->all()) 
            { 
                return redirect("inventory/lot-allocation-add")->withErrors($validator)->withInput();

            }
        }
        $data['items'] = $this->inv_supplier_invoice_item->get_supplier_invoice_item1(['inv_supplier_invoice_item.status'=>1,'inv_supplier_invoice_master.status'=>1]);
        $data['users'] = $this->User->get_all_users([['user.status','=',1]]);
        
      // print_r( $data['items']);exit;
        return view('pages.purchase-details.lot-allocation.lot-allocation-add', compact('data'));
    }

    public function getInvoiceItem($invoice_item_id)
    {
        $invoice_item = $this->inv_supplier_invoice_item->get_single_supplier_invoice_item(['inv_supplier_invoice_item.id'=>$invoice_item_id]);
        return $invoice_item;
    }

    public function getsingleLot($lot_allocation_id)
    {
        //echo $lot_allocation_id;
        //exit;
        $lot_data= $this->inv_lot_allocation->get_single_lot1(['inv_lot_allocation.id'=>$lot_allocation_id]);
        return $lot_data;
    }
}
