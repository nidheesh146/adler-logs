<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\PurchaseDetails\inv_lot_allocation;
use App\Models\PurchaseDetails\inv_supplier_invoice_item;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\PurchaseDetails\inv_final_purchase_order_master;
use App\Models\PurchaseDetails\inv_supplier_invoice_master;
use App\Models\PurchaseDetails\inv_supplier_invoice_rel;
use App\Models\currency_exchange_rate;
use PDF;

use DB;
class LotAllocationController extends Controller
{
    public function __construct()
    {
        $this->inv_lot_allocation = new inv_lot_allocation;
        $this->inv_supplier_invoice_item = new inv_supplier_invoice_item;
        $this->User = new User;
        $this->inv_supplier_invoice_rel = new inv_supplier_invoice_rel;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->inv_supplier = new inv_supplier;
        $this->inv_final_purchase_order_master = new inv_final_purchase_order_master;
        $this->inv_supplier_invoice_master = new inv_supplier_invoice_master;
        $this->currency_exchange_rate = new currency_exchange_rate;
        
    }

    public function lotAllocation(Request $request)
    {
            $condition = [];
            if ($request->lot_no) {
                $condition[] = ['inv_lot_allocation.lot_number', 'like', '%'.$request->lot_no.'%'];
            }
            if ($request->po_no) {
                $condition[] = ['inv_final_purchase_order_master.po_number', 'like', '%'.$request->po_no.'%'];
            }
            if ($request->invoice_no) {
                $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%'.$request->invoice_no.'%'];
            }
            if ($request->item_code) {
                $condition[] = ['inventory_rawmaterial.item_code', 'like', '%'.$request->item_code.'%'];
            }
            if ($request->supplier) {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"),'like','%'.$request->supplier.'%'];
            }
        
        $data['lot_data'] = $this->inv_lot_allocation->getData($condition);
        $users = $this->User->get_all_users([['user.status','=',1]]);
        $data['items'] = $this->inventory_rawmaterial->get_items();
        $data['suppliers'] = $this->inv_supplier->get_all_suppliers();
        $data['lot_nos'] = $this->inv_lot_allocation->get_lots();
        $data['po_nos'] = $this->inv_final_purchase_order_master->get_po_nos();
        $data['invoice_nos'] = $this->inv_supplier_invoice_master->get_invoice_nos();
        $data["currency"] = $this->currency_exchange_rate->get_currency([]);
        return view('pages.purchase-details.lot-allocation.lot-allocation-list', compact('users','data'));
    }

    public function addLotAllocation(Request $request)
    {
        if ($request->isMethod('post'))
        {
            // $validation['document_no'] = ['required'];
            // $validation['rev_no'] = ['required'];
            // $validation['rev_date'] = ['required','date'];
            // $validation['qty_received'] = ['required'];
            // $validation['qty_accepted'] = ['required'];
            // $validation['qty_rejected'] = ['required'];
            // $validation['test_report_no'] = ['required'];
            // $validation['test_report_date'] = ['required'];
            // $validation['currency'] = ['required'];
            // $validation['conversion_rate'] = ['required'];
            //$validation['prepared_by'] = ['required'];

            // $validator = Validator::make($request->all(), $validation);
            // if(!$validator->errors()->all()) 
            // { 
                $mnthYearcombo= date('m').date('y');
                $data['lot_number'] = $this->lot_num_gen(DB::table('inv_lot_allocation')->where('lot_number','LIKE', '%'.$mnthYearcombo)->count()); //$request->lot_number;
                $data['doc_number'] = $request->document_no;
                $data['rev_number'] = $request->rev_no;
                $data['rev_date'] = $request->rev_date;
                $data['qty_received'] = $request->qty_received;
                $data['qty_accepted'] = $request->qty_received;
                //$data['available_qty'] = $request->qty_received;
                $data['qty_rejected'] = $request->qty_rejected;
                $data['vehicle_number'] = $request->vehicle_no;
                $data['transporter_name'] = $request->transporter_name;
                $data['mrr_number'] = $request->mrr_no;
                $data['mrr_date'] = $request->mrr_date;
                $data['test_report_number'] = $request->test_report_no;
                $data['test_report_date'] = $request->test_report_date;
                $data['prepared_by'] = config('user')['user_id'];
                $data['approved_by'] =config('user')['user_id'];
             
            if(!$request->lot_id){
                $invoice_item = $this->inv_supplier_invoice_item->get_single_supplier_invoice_item_id(['inv_supplier_invoice_item.id'=>$request->si_id]);
                //print_r($invoice_item);exit;
                $invoice_master_supplier = inv_supplier_invoice_rel::leftJoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                                       ->where('inv_supplier_invoice_rel.item','=', $invoice_item['invoice_item_id'])->pluck('inv_supplier_invoice_master.supplier_id')->first();

                $data['pr_item_id'] = $invoice_item->requisition_item_id;
                $data['si_invoice_item_id'] = $invoice_item->invoice_item_id;
                $data['supplier_id'] = $invoice_master_supplier;
                $data['po_id'] = $invoice_item->po_master_id;
              }

              $data['qty_rej_reason'] = $request->qty_rej_reason;
              $data['rejected_user'] = config('user')['user_id'];
              $data['invoice_rate'] = $request->invoice_rate;
            //   $data['currency'] = $request->currency;
            //   $data['conversion_rate'] = $request->conversion_rate;
            //   $data['value_inr'] = $request->value_inr;

                if($request->lot_id){
                    $lot =$this->inv_lot_allocation->updatedata(['inv_lot_allocation.id'=>$request->lot_id],$data);
                    $request->session()->flash('success',  "You have successfully updated a LOT allocation !");
                } 
                else{
                    $lot =$this->inv_lot_allocation->insertdata($data);
                    $request->session()->flash('success',  "You have successfully created a LOT allocation !");
                }
                
                return redirect("inventory/lot-allocation-list");
            //}
            // if($validator->errors()->all()) 
            // { 
            //     return redirect("inventory/lot-allocation-add")->withErrors($validator)->withInput();

            // }
        }
        // $all_lot_invoice_number = $this->inv_lot_allocation->all_lot_invoice_number();
        // $items = $this->inv_supplier_invoice_item->get_non_lot_alloted_supplier_invoice_items(['inv_supplier_invoice_item.status'=>1],$all_lot_invoice_number);
        // // print_r(json_encode($items));exit;
        // return view('pages.purchase-details.lot-allocation.lot-allocation-add', compact('items'));

        $data['items'] = $this->inv_supplier_invoice_item->get_supplier_invoice_item1(['inv_supplier_invoice_item.status'=>1,'inv_supplier_invoice_master.status'=>1]);
        $data['users'] = $this->User->get_all_users([['user.status','=',1]]);
        $data["currency"] = $this->currency_exchange_rate->get_currency([]);
        return view('pages.purchase-details.lot-allocation.lot-allocation-add', compact('data'));
    }

    public function getInvoiceItem($invoice_item_id)
    {
        $invoice_item = $this->inv_supplier_invoice_item->get_single_supplier_invoice_item(['inv_supplier_invoice_item.id'=>$invoice_item_id]);
        if($invoice_item->po_number!=null)
         {
             $invoice_item->po_Number = $invoice_item->po_number;
         }
         else
         {
             $po_nos = inv_supplier_invoice_item::leftJoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
                     ->where('inv_supplier_invoice_item.merged_invoice_item','=',$invoice_item_id)
                     ->select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date')->get();
             $po = '';
             foreach($po_nos as $no)
             {
                 $po = $no['po_number'].' , '.$po; 
             }
             $invoice_item->po_Number = $po;
        
         }
        $invoice_item->total_rate = (float) sprintf('%.2f',($invoice_item->rate - ($invoice_item->discount/100)*$invoice_item->rate));
        return $invoice_item;
    }

    public function getsingleLot($lot_allocation_id)
    {
        //echo $lot_allocation_id;
        //exit;
         //exit;
         $lot_data= $this->inv_lot_allocation->get_single_lot1(['inv_lot_allocation.id'=>$lot_allocation_id]);
         if($lot_data->po_number!=null)
         {
             $lot_data->po_Number = $lot_data->po_number;
         }
         else
         {
             $po_nos = inv_supplier_invoice_item::leftJoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
                     ->where('inv_supplier_invoice_item.merged_invoice_item','=',$lot_data->invoice_item_id)
                     ->select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date')->get();
             $po = '';
             foreach($po_nos as $no)
             {
                 $po = $no['po_number'].' , '.$po; 
             }
             $lot_data->po_Number = $po;
        
         }
         $lot_data->total_rate = (float) sprintf('%.2f',($lot_data->rate - ($lot_data->discount/100)*$lot_data->rate));
         return $lot_data;
    }

    public function generatePdf($id){
       // echo "jj";exit;
        $data['lot']= $this->inv_lot_allocation->get_single_lot1(['inv_lot_allocation.id'=>$id]);
        $pdf = PDF::loadView('pages.purchase-details.lot-allocation.lot-allocation-pdf', $data);
        $file_name = "lotallocation_".$data['lot']['lot_number'];
        return $pdf->stream($file_name . '.pdf');
    }
}
