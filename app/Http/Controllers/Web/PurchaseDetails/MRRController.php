<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\PurchaseDetails\inv_miq;
use App\Models\PurchaseDetails\inv_miq_item;
use App\Models\PurchaseDetails\inv_miq_item_rel;
use App\Models\PurchaseDetails\inv_mrd;
use App\Models\PurchaseDetails\inv_mac;
use App\Models\PurchaseDetails\inv_mac_item;
use App\Models\PurchaseDetails\inv_mac_item_rel;
use App\Models\PurchaseDetails\inv_mrr;
use App\Models\PurchaseDetails\inv_mrr_item;
use App\Models\PurchaseDetails\inv_mrr_item_rel;
use App\Models\User;
use App\Models\currency_exchange_rate;
use App\Models\PurchaseDetails\inv_supplier_invoice_rel;
use App\Models\PurchaseDetails\inv_supplier_invoice_item;
use App\Models\PurchaseDetails\inv_supplier_invoice_master;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MRRExport;
class MRRController extends Controller
{ 
    public function __construct()
    {
        $this->inv_miq = new inv_miq;
        $this->inv_miq_item = new inv_miq_item;
        $this->inv_miq_item_rel = new inv_miq_item_rel;
        $this->inv_mac = new inv_mac;
        $this->inv_mac_item = new inv_mac_item;
        $this->inv_mac_item_rel = new inv_mac_item_rel;
        $this->inv_mrr = new inv_mrr;
        $this->inv_mrr_item = new inv_mrr_item;
        $this->inv_mrr_item_rel = new inv_mrr_item_rel;
        $this->User = new User;
        $this->currency_exchange_rate = new currency_exchange_rate;
        $this->inv_supplier_invoice_item = new inv_supplier_invoice_item;
        $this->inv_supplier_invoice_master = new inv_supplier_invoice_master;
    }
    public function receiptReport(Request $request)
    {
        $condition=[];
        if($request)
        {
            if ($request->mac_no) {
                $condition[] = ['inv_mac.mac_number','like', '%' . $request->mac_no . '%'];
            }
            if ($request->mrr_no) {
                $condition[] = ['inv_mrr.mrr_number','like', '%' . $request->mrr_no . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
            if($request->order_type)
            {  
                $condition[] = ['inv_supplier_invoice_master.type','=',  strtoupper($request->order_type)];
            }
            if(!$request->order_type)
            {  
                $condition[] = ['inv_supplier_invoice_master.type','=', 'PO'];
            }
            
            if ($request->from) {
                $condition[] = ['inv_mrr.mrr_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_mrr.mrr_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
            $data= $this->inv_mrr->get_all_data($condition);
        }
        else
        {
            $condition[] = ['inv_supplier_invoice_master.type','=', 'PO'];
            $data = $this->inv_mrr->get_all_data($condition);
        }
        $mrr = inv_mrr::select('inv_mac.invoice_id','inv_mrr.id')
                        ->leftJoin('inv_mac','inv_mac.id','=','inv_mrr.mac_id')
                        ->get();
        foreach($mrr as $mr)
        {
            
            $mrd_id= inv_mrd::where('invoice_id','=',$mr['invoice_id'])->pluck('id')->first();
            $master = inv_mrr::where('id','=',$mr['id'])->update(['invoice_id'=>$mr['invoice_id'],'mrd_id'=>$mrd_id]);
        }
        $mrr_item = inv_mrr_item::select('inv_mac_item.invoice_item_id','inv_mrr_item.id')
                        ->leftJoin('inv_mac_item','inv_mac_item.id','=','inv_mrr_item.mac_item_id')
                        ->get();
        foreach($mrr_item as $mr_item)
        {
            //$mrd_id= inv_mrd::where('invoice_id','=',$mr['invoice_id'])->pluck('id')->first();
            $item = inv_mrr_item::where('id','=',$mr_item['id'])->update(['invoice_item_id'=>$mr_item['invoice_item_id']]);
        }
        
        return view('pages.inventory.MRR.mrr-list',compact('data'));
    }
    public function addMRR(Request $request,$id=Null)
    {
        if($request->isMethod('post'))
        {
            $validation['mrr_date'] = ['required','date'];
            $validation['invoice_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(!$request->id)
                {
                    if(date('m')==01 || date('m')==02 || date('m')==03)
                    {
                        $years_combo = date('y', strtotime('-1 year')).date('y');
                    }
                    else
                    {
                        $years_combo = date('y').date('y', strtotime('+1 year'));
                    }
                    $item_type = $this->get_item_type($request->invoice_number);
                    //echo $item_type;exit;
                    if($request->order_type=='po')
                    {
                        if($item_type=="Direct Items"){
                            $Data['mrr_number'] = "MRR2-".$this->year_combo_num_gen(DB::table('inv_mrr')->where('inv_mrr.mrr_number', 'LIKE', 'MRR2-'.$years_combo.'%')->count()); 
                        }
                        //if($item_type=="Indirect Items"){
                        else{
                            $Data['mrr_number'] = "MRR3-" . $this->year_combo_num_gen(DB::table('inv_mrr')->where('inv_mrr.mrr_number', 'LIKE', 'MRR3-'.$years_combo.'%')->count()); 
                        }
                    }
                    else
                    {
                        if($item_type=="Direct Items"){
                            $Data['mrr_number'] = "SRR2-".$this->year_combo_num_gen(DB::table('inv_mrr')->where('inv_mrr.mrr_number', 'LIKE', 'SRR2-'.$years_combo.'%')->count()); 
                        }
                       else{
                            $Data['mrr_number'] = "SRR3-" . $this->year_combo_num_gen(DB::table('inv_mrr')->where('inv_mrr.mrr_number', 'LIKE', 'SRR3-'.$years_combo.'%')->count()); 
                        }
                    }
                    // $miq_number = inv_mac::leftJoin('inv_miq','inv_miq.id','=','inv_mac.miq_id')
                    //                     ->where('inv_mac.id','=',$request->mac_number)->pluck('inv_miq.miq_number')->first();
                    // if($request->order_type=='po')
                    // $Data['mrr_number'] = str_replace("MIQ", "MRR", $miq_number);  
                    // else
                    // $Data['mrr_number'] = str_replace("MIQ", "SRR", $miq_number);

                    $Data['mrr_date'] = date('Y-m-d', strtotime($request->mrr_date));
                    $Data['invoice_id'] = $request->invoice_number;
                    $mac_id = inv_mac::where('invoice_id','=',$request->invoice_number)->pluck('id')->first();
                    $mrd_id = inv_mrd::where('invoice_id','=',$request->invoice_number)->pluck('id')->first();
                    $Data['mac_id'] = $mac_id;
                    $Data['mrd_id'] = $mrd_id;
                    $Data['created_by']= $request->created_by;
                    $Data['status']=1;
                    $Data['created_at'] =date('Y-m-d H:i:s');
                    $Data['updated_at'] =date('Y-m-d H:i:s');
                    $add_id = $this->inv_mrr->insert_data($Data);
                    
                    
                    
                    //$inv_mac_items = $this->inv_mac_item->get_items_for_mrr(['inv_mac_item_rel.master' => $mac_id]);
                    $supplier_invoice_items =  $this->inv_supplier_invoice_item->get_supplier_invoice_item(['inv_supplier_invoice_rel.master'=>$request->invoice_number]);
                    /*foreach($inv_mac_items as $item){
                        $dat=[
                            'mac_item_id'=>$item->id,
                            'pr_item_id'=>$item->pr_item_id,
                            //'status'=>1,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')

                        ];
                        $item_id = $this->inv_mrr_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_mrr_item_rel')->insert($dat2);
                    }*/
                    $supplier_invoice_items =  $this->inv_supplier_invoice_item->get_supplier_invoice_item(['inv_supplier_invoice_rel.master'=>$request->invoice_number]);
                    foreach($supplier_invoice_items as $item)
                    {
                        $mac_item_id = inv_mac_item::where('invoice_item_id','=',$item->id)->pluck('id')->first();
                        $dat = [
                            'invoice_item_id'=>$item->id,
                            'mac_item_id'=>$mac_item_id,
                            'pr_item_id'=>$item->pr_item_id,
                            //'status'=>1,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')
                        ];
                        $item_id = $this->inv_mrr_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_mrr_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a MRR !");
                    else
                        $request->session()->flash('error', "MRR creation is failed. Try again... !");
                    return redirect('inventory/MRR-add/'.$add_id);
                }
                else
                {
                    //echo $request->created_by;exit;
                        $data['mrr_date'] = date('Y-m-d', strtotime($request->mrr_date));
                        $data['created_by']= $request->created_by;
                        $data['updated_at'] =date('Y-m-d H:i:s');
                        $update = $this->inv_mrr->update_data(['id'=>$request->id],$data);
                    if($update)
                        $request->session()->flash('success', "You have successfully updated a MRR !");
                    else
                        $request->session()->flash('error', "MRR updation is failed. Try again... !");
                    return redirect('inventory/MRR-add/'.$request->id);
                }
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/MRR-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);

        if($request->id){
            $edit['mrr'] = $this->inv_mrr->find_mrr_data(['inv_mrr.id' => $request->id]);
            $edit['items'] = $this->inv_mrr_item->get_items(['inv_mrr_item_rel.master' =>$request->id]);
            return view('pages.inventory.MRR.MRR-add',compact('edit','data'));
        }
        else
        return view('pages.inventory.MRR.MRR-add',compact('data'));
    }
    public function get_item_type($invoice_number)
    {
        $item_type = inv_supplier_invoice_rel::leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
                            ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                            ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                            ->leftJoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_supplier_invoice_rel.master','=', $invoice_number)->pluck('inv_item_type.type_name')->first();
        return $item_type;
    }

    public function find_mac_for_mrr(Request $request)
    {
        if ($request->q) {
            //echo $request->type;exit;
            $condition[] = ['inv_mac.mac_number', 'like', '%' . strtoupper($request->q) . '%'];
            
            $data = $this->inv_mac->find_mac_not_in_mrr($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mac_details($request->id, null);
            exit;
        }
    }
    public function find_woa_for_mrr(Request $request)
    {
        if ($request->q) {
            //echo $request->type;exit;
            $condition[] = ['inv_mac.mac_number', 'like', '%' . strtoupper($request->q) . '%'];
            
            $data = $this->inv_mac->find_woa_not_in_mrr($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mac_details($request->id, null);
            exit;
        }
    }
    public function find_mac_info(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_mac.mac_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_mac->find_mac_not_in_mrr($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mac_details($request->id, null);
            exit;
        }
    }
    public function find_woa_info(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_mac.mac_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_mrd->find_woa_not_in_mrr($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mac_details($request->id, null);
            exit;
        }
    }
    public function mac_details($id, $active = null)
    {
        $mac = $this->inv_mac->find_mac_data(['inv_mac.id' => $id]);

        $inv_mac_items = $this->inv_mac_item->get_items_for_mrr(['inv_mac_item_rel.master' => $id]);
        // if ($active) {
        //     $inv_mac_item = $this->inv_mac_item->get_mac_items(['inv_mac_rel.master' => $active]);
        // }

        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
        if(str_starts_with($mac->mac_number , 'MAC') )
        $data .='Material Acceptance (' . $mac->mac_number . ')';
        else
        $data .='Work Order Acceptance (' . $mac->mac_number . ')';

        $data .= '</label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
            
                </thead>
                <tbody>
                    <tr>
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($mac->created_at)) . '</td>
                    </tr>
                    <tr>
                        <th>Supplier ID</th>
                        <td>'.$mac->vendor_id.'</td>
                        
                    </tr>
                    <tr>
                        <th>Supplier Name</th>
                        <td>'.$mac->vendor_name.'</td>
                    </tr>
                </tbody>
           </table>
           <br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
            
            if(str_starts_with($mac->mac_number , 'MAC') )
            $data .= 'MAC Items ';
            else
            $data .= 'SRR Items ';

        $data .= '</label>
               <div class="form-devider"></div>
           </div>
           </div>
           <div class="table-responsive">
           <table class="table table-bordered mg-b-0" id="example1">';
            $data .= '<thead>
                   <tr>
                   <th>Item Code:</th>
                   <th>Item Type</th>
                   <th>Accepted Qty</th>
                   <th>Expiry Date</th>
                   </tr>
               </thead>
               <tbody >';
            foreach ($inv_mac_items as $item) {
                $data .= '<tr>
                       <td>'.$item->item_code.'</td>
                       <td>'.$item->type_name.'</td>
                       <td>'.$item->accepted_quantity. $item->unit_name.'</td>';
                if($item['expiry_date']!=NULL)          
                $data .='<td>'.date('d-m-Y', strtotime($item['expiry_date'])).'</td></tr>';
                else
                $data .='<td> </td></tr>';
                   
            }
            $data .= '</tbody>';
        

        $data .= '</table>
       </div>';
        return $data;
    }
    public function mrr_delete(Request $request, $id)
    {
        //$this->inv_mrr->deleteData(['id' => $id]);
        $this->inv_mrr->update_data(['id' => $id],['status'=>0]);
        $request->session()->flash('success', "You have successfully deleted a MRR !");
        if($request->order_type)
        return redirect('inventory/receipt-report?order_type='.$request->order_type);
        else
        return redirect('inventory/receipt-report');
    }
    public function receiptReportPDF(Request $request,$id)
    {
        $data['type'] = $request->order_type;
        $data['mrr'] = $this->inv_mrr->find_mrr_data(['inv_mrr.id' => $id]);
        //print_r($data['mrr']);exit;
        $data['items'] = $this->inv_mrr_item->get_items(['inv_mrr_item_rel.master' => $id]);
        // print_r(json_encode($data['items']));exit;
        $pdf = PDF::loadView('pages.inventory.MRR.pdf-view', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "MRR" . $data['mrr']['vendor_name'] . "_" . $data['mrr']['mrr_date'];
        return $pdf->stream($file_name . '.pdf');
    }

    public function getPO_for_merged_si_item($supplier_invoice_item_id)
    {
        $po_nos = inv_supplier_invoice_item::leftJoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
                    ->leftJoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_supplier_invoice_item.po_item_id')
                    ->where('inv_supplier_invoice_item.merged_invoice_item','=',$supplier_invoice_item_id)
                    ->select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_item.order_qty')
                    ->get();
        return $po_nos;
    }
    
    public function find_invoice_for_mrr(Request $request)
    {
        if ($request->q) {
            //echo $request->type;exit;
            $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . strtoupper($request->q) . '%'];
            
            $data = $this->inv_supplier_invoice_master->find_invoice_number_not_in_mrr($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->invoice_details($request->id, null);
            exit;
        }
    }
    public function find_invoice_for_srr(Request $request)
    {
        if ($request->q) {
            //echo $request->type;exit;
            $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . strtoupper($request->q) . '%'];
            
            $data = $this->inv_supplier_invoice_master->find_invoice_number_not_in_srr($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->invoice_details($request->id, null);
            exit;
        }
    }
    public function find_invoice_info(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_supplier_invoice_master->find_invoice_number_not_in_mrr($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->invoice_details($request->id, null);
            exit;
        }
    }
    public function invoice_details($id, $active = null)
    {
        $invoice = $this->inv_supplier_invoice_master->get_master_data_mrr(['inv_supplier_invoice_master.id' => $id]);
        //return $invoice;
        $invoice_item = $this->inv_supplier_invoice_item->get_supplier_invoice_item(['inv_supplier_invoice_rel.master' => $id]);
        

        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                Supplier Invoice (' . $invoice->invoice_number . ')
                   </label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
            
                </thead>
                <tbody>
                    <tr>
                        <th>Invoice Date</th>
                        <td>' . date('d-m-Y', strtotime($invoice->invoice_date)) . '</td>
                    </tr>
                    <tr>
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($invoice->invoice_created)) . '</td>
                    </tr>
                    <tr>
                        <th>Supplier ID</th>
                        <td>'.$invoice->vendor_id.'</td>
                        
                    </tr>
                    <tr>
                        <th>Supplier Name</th>
                        <td>'.$invoice->vendor_name.'</td>
                    </tr>
                    <tr>
                        <th>MAC/WOA Number</th>
                        <td>'.$invoice->mac_number.'</td>
                    </tr>
                    <tr>
                        <th>MRD/WOR Number</th>
                        <td>'.$invoice->mrd_number.'</td>
                    </tr>
                </tbody>
           </table>
           <br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
            $data .= 'Invoice Items ';
            $data .= '</label>
               <div class="form-devider"></div>
                </div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1">';

        
            $data .= '<thead>
                   <tr>
                   <th>PR No</th>
                   <th>Item Code:</th>
                   <th>Lot No</th>
                   <th>Invoice Qty</th>
                   <th>Rate</th>
                   <th>Discount </th>
                   <th>GST </th>
                   <th>Accepted Qty</th>
                   <th>Rejected Qty</th>
                   </tr>
               </thead>
               <tbody >';
            foreach ($invoice_item as $item) {
                $data .= '<tr>
                        <td>'.$item->pr_no.'</td>
                       <td>'.$item->item_code.'</td>
                       <td>'.$item->lot_number.'</td>
                       <td>'.$item->order_qty. $item->unit_name.'</td>
                       <td>'.$item->rate.'</td>
                       <td>'.$item->discount.'</td>
                       <td>IGST:'.$item->igst.'% ,
                            SGST:'.$item->sgst.'%,
                            CGST:'.$item->cgst.'%<br/>
                       </td>
                       <td>'.$item->accepted_quantity. $item->unit_name.'</td>
                       <td>'.$item->rejected_quantity. $item->unit_name.'</td>
                       
                   </tr>';
            }
            $data .= '</tbody>';
        

        $data .= '</table>
       </div>';
        return $data;
    }
    public function MRRExport(Request $request)
    {
        if($request)
        {
            return Excel::download(new MRRExport($request), 'MRR' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new MRRExport($request), 'MRR' . date('d-m-Y') . '.xlsx');
        }
    }
}
