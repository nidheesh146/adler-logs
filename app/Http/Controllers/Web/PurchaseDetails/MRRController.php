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
use App\Models\PurchaseDetails\inv_mac;
use App\Models\PurchaseDetails\inv_mac_item;
use App\Models\PurchaseDetails\inv_mac_item_rel;
use App\Models\PurchaseDetails\inv_mrr;
use App\Models\PurchaseDetails\inv_mrr_item;
use App\Models\PurchaseDetails\inv_mrr_item_rel;
use App\Models\User;
use App\Models\currency_exchange_rate;
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
        return view('pages.inventory.MRR.mrr-list',compact('data'));
    }
    public function addMRR(Request $request,$id=Null)
    {
        if($request->isMethod('post'))
        {
            $validation['mrr_date'] = ['required','date'];
            $validation['mac_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(!$request->id)
                {
                    $item_type = $this->get_item_type($request->mac_number);
                    if($item_type=="Direct Items"){
                        $Data['mrr_number'] = "MRR2-".$this->po_num_gen(DB::table('inv_mrr')->where('inv_mrr.mrr_number', 'LIKE', 'MRR2%')->count(),1); 
                    }
                    if($item_type=="Indirect Items"){
                        $Data['mrr_number'] = "MRR3-" . $this->po_num_gen(DB::table('inv_mrr')->where('inv_mrr.mrr_number', 'LIKE', 'MRR3%')->count(),1); 
                    }
                    $Data['mrr_date'] = date('Y-m-d', strtotime($request->mrr_date));
                    $Data['mac_id'] = $request->mac_number;
                    $Data['created_by']= $request->created_by;
                    $Data['status']=1;
                    $Data['created_at'] =date('Y-m-d H:i:s');
                    $Data['updated_at'] =date('Y-m-d H:i:s');
                    $add_id = $this->inv_mrr->insert_data($Data);
                    $inv_mac_items = $this->inv_mac_item->get_items_for_mrr(['inv_mac_item_rel.master' => $request->mac_number]);
                    foreach($inv_mac_items as $item){
                        $dat=[
                            'mac_item_id'=>$item->id,
                            'item_id'=>$item->pr_item_id,
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
    public function get_item_type($mac_number)
    {
        $item_type = inv_mac_item_rel::leftJoin('inv_mac_item','inv_mac_item.id','=','inv_mac_item_rel.item')
                            ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_mac_item.item_id')
                            ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.requisition_item_id')
                            ->leftJoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_mac_item_rel.master','=', $mac_number)->pluck('inv_item_type.type_name')->first();
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
                       <td>'.$item->accepted_quantity. $item->unit_name.'</td>
                       <td>'.date('d-m-Y', strtotime($item['expiry_date'])).'</td>
                   </tr>';
            }
            $data .= '</tbody>';
        

        $data .= '</table>
       </div>';
        return $data;
    }
    public function mrr_delete(Request $request, $id)
    {
        $this->inv_mrr->deleteData(['id' => $id]);
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
        $pdf = PDF::loadView('pages.inventory.MRR.pdf-view', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "MRR" . $data['mrr']['vendor_name'] . "_" . $data['mrr']['mrr_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    
}
