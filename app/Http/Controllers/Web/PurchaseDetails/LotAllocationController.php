<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\PurchaseDetails\inv_lot_allocation;

class LotAllocationController extends Controller
{
    public function __construct()
    {
        $this->inv_lot_allocation = new inv_lot_allocation;
    }

    public function lotAllocation()
    {
        $lot_data = $this->inv_lot_allocation->getData();
        //print_r($lot_data);exit;
        return view('pages.purchase-details.lot-allocation.lot-allocation-list', compact('lot_data'));
    }

    public function addLotAllocation(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $validation['lot_number'] = ['required'];
            $validation['document_no'] = ['required'];
            $validation['rev_no'] = ['required'];
            $validation['rev_date'] = ['required','date'];
            $validation['item_description'] = ['required'];
            $validation['material_code'] = ['required'];
            $validation['material_description'] = ['required'];
            $validation['invoice_no'] = ['required'];
            $validation['invoice_date'] = ['required'];
            $validation['invoice_qty'] = ['required'];
            $validation['qty_received'] = ['required'];
            $validation['qty_accepted'] = ['required'];
            $validation['qty_rejected'] = ['required'];
            $validation['unit'] = ['required'];
            $validation['po_number'] = ['required'];
            $validation['supplier'] = ['required'];
            $validation['vehicle_no'] = ['required'];
            $validation['transporter_name'] = ['required'];
            $validation['mrr_no'] = ['required'];
            $validation['mrr_date'] = ['required'];
            $validation['test_report_no'] = ['required'];
            $validation['test_report_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 
                $data['lot_number'] = $request->lot_number;
                $data['doc_number'] = $request->document_no;
                $data['rev_number'] = $request->rev_no;
                $data['rev_date'] = $request->rev_date;
                $data['item_description'] = $request->item_description;
                $data['meterial_code'] = $request->material_code;
                $data['meterial_description'] = $request->material_description;
                $data['invoice_number'] = $request->invoice_no;
                $data['invoice_date'] = $request->invoice_date;
                $data['invoice_qty'] = $request->invoice_qty;
                $data['qty_received'] = $request->qty_received;
                $data['qty_accepted'] = $request->qty_accepted;
                $data['qty_rejected'] = $request->qty_rejected;
                $data['unit'] = $request->unit;
                $data['po_id'] = $request->po_number;
                $data['supplier_id'] = $request->supplier;
                $data['vehicle_number'] = $request->vehicle_no;
                $data['transporter_name'] = $request->transporter_name;
                $data['mrr_number'] = $request->mrr_number;
                $data['mrr_date'] = $request->mrr_date;
                $data['test_report_number'] = $request->test_report_no;
                $data['test_report_date'] = $request->test_report_date;
                $lot =$this->inv_lot_allocation->insertdata($data);
                $request->session()->flash('success',  "You have successfully completed lot allocation !");
                return redirect("inventory/lot-allocation-list");
            }
            if($validator->errors()->all()) 
            { 
                return redirect("inventory/lot-allocation-add")->withErrors($validator)->withInput();

            }
        }
        return view('pages.purchase-details.lot-allocation.lot-allocation-add');
    }
}
