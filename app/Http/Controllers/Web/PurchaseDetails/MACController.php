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
use App\Models\PurchaseDetails\inv_supplier_invoice_master;
use App\Models\PurchaseDetails\inv_supplier_invoice_item;
use App\Models\PurchaseDetails\inv_supplier_invoice_rel;
use App\Models\PurchaseDetails\inv_lot_allocation;
use App\Models\PurchaseDetails\inv_stock_management;
use App\Models\PurchaseDetails\inv_stock_transaction;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\User;
use App\Models\currency_exchange_rate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MACExport;
class MACController extends Controller
{
    public function __construct()
    {
        $this->inv_miq = new inv_miq;
        $this->inv_miq_item = new inv_miq_item;
        $this->inv_miq_item_rel = new inv_miq_item_rel;
        $this->inv_mac = new inv_mac;
        $this->inv_mac_item = new inv_mac_item;
        $this->inv_mac_item_rel = new inv_mac_item_rel;
        $this->inv_supplier_invoice_master = new inv_supplier_invoice_master;
        $this->inv_supplier_invoice_item =new inv_supplier_invoice_item;
        $this->inv_supplier_invoice_rel =new inv_supplier_invoice_rel;
        $this->User = new User;
        $this->currency_exchange_rate = new currency_exchange_rate;
        $this->inv_lot_allocation = new inv_lot_allocation;
        $this->inv_stock_management = new inv_stock_management;
        $this->inv_stock_transaction = new inv_stock_transaction;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
    }

    public function MAClist(Request $request)
    {
        /*
        // $this->directmacstockImport();
        // $this->indirectmacstockImport();
        // $this->indirectwoastockImport();
        */
        
        $condition = [];
        if($request)
        {
            if ($request->invoice_no) {
                $condition[] = ['inv_supplier_invoice_master.invoice_number','like', '%' . $request->invoice_no . '%'];
            }
            if ($request->mac_no) {
                $condition[] = ['inv_mac.mac_number','like', '%' . $request->mac_no . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
            
            if ($request->from) {
                $condition[] = ['inv_mac.mac_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_mac.mac_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
            if($request->order_type)
            {  
                $condition[] = ['inv_supplier_invoice_master.type','=',  strtoupper($request->order_type)];
            }
            if(!$request->order_type)
            {  
                $condition[] = ['inv_supplier_invoice_master.type','=', 'PO'];
            }
            $data= $this->inv_mac->get_all_data($condition);
        }
        else
        {
        $condition[] = ['inv_supplier_invoice_master.type','=', 'PO'];
        $data = $this->inv_mac->get_all_data($condition);
        }
        //$data = $this->inv_mac->get_all_data([]);
        return view('pages.inventory.MAC.MAC-list', compact('data'));
    }
    
    public function findInvoiceNumberForMAC(Request $request){
        if ($request->q) {
            $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_supplier_invoice_master->find_invoice_num_for_mac($condition);
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
    public function findInvoiceNumberForWOA(Request $request){
        if ($request->q) {
            $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_supplier_invoice_master->find_invoice_num_for_woa($condition);
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

    public function invoiceInfo(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_supplier_invoice_master->find_invoice_num_for_mac($condition);
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
        $invoice = $this->inv_supplier_invoice_master->get_master_data(['inv_supplier_invoice_master.id' => $id]);
        //return $invoice;
        $invoice_item = $this->inv_supplier_invoice_item->get_supplier_invoice_item(['inv_supplier_invoice_rel.master' => $id]);
        // if ($active) {
        //     $inv_mac_item = $this->inv_mac_item->get_mac_items(['inv_mac_rel.master' => $active]);
        // }

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
                       
                   </tr>';
            }
            $data .= '</tbody>';
        

        $data .= '</table>
       </div>';
        return $data;
    }

    public function MACAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['mac_date'] = ['required','date'];
            $validation['invoice_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(!$request->id)
                {
                    $item_type = $this->get_item_type($request->invoice_number);
                    if($item_type=="Direct Items"){
                        $Data['mac_number'] = "MAC2-".$this->po_num_gen(DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'MAC2%')->count(),1); 
                    }
                    //if($item_type=="Indirect Items"){
                    else
                    {
                        $Data['mac_number'] = "MAC3-" . $this->po_num_gen(DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'MAC3%')->count(),1); 
                    }
                    $Data['mac_date'] = date('Y-m-d', strtotime($request->mac_date));
                    $Data['invoice_id'] = $request->invoice_number;
                    $Data['created_by']= $request->created_by;
                    $Data['status']=1;
                    $Data['created_at'] =date('Y-m-d H:i:s');
                    $Data['updated_at'] =date('Y-m-d H:i:s');
                    $add_id = $this->inv_mac->insert_data($Data);
                    $invoice_items = inv_supplier_invoice_rel::select('inv_supplier_invoice_rel.item','inv_supplier_invoice_item.item_id')
                                ->leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
                                ->where('inv_supplier_invoice_item.is_merged','=',0)
                                ->where('master','=',$request->invoice_number)->get();
                    foreach($invoice_items as $item){
                        $dat=[
                            'invoice_item_id'=>$item->item,
                            'pr_item_id'=>$item->item_id,
                            'status'=>1,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')

                        ];
                        $item_id = $this->inv_mac_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_mac_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a MAC !");
                    else
                        $request->session()->flash('error', "MAC creation is failed. Try again... !");
                    return redirect('inventory/MAC-add/'.$add_id);
                }
                else
                {
                    $data['mac_date'] = date('Y-m-d', strtotime($request->mac_date));
                    $data['created_by']= $request->created_by;
                    $data['updated_at'] =date('Y-m-d H:i:s');
                    $update = $this->inv_mac->update_data(['id'=>$request->id],$data);
                    if($update)
                        $request->session()->flash('success', "You have successfully updated a MAC !");
                    else
                        $request->session()->flash('error', "MAC updation is failed. Try again... !");
                    return redirect('inventory/MAC-add/'.$request->id);
                }
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/MAC-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);

        if($request->id){
            $edit['mac'] = $this->inv_mac->find_mac_data(['inv_mac.id' => $request->id]);

            $edit['items'] = $this->inv_mac_item->get_items(['inv_mac_item_rel.master' =>$request->id]);
            return view('pages.inventory.MAC.MAC-add',compact('edit','data'));
        }
        else
        return view('pages.inventory.MAC.MAC-add',compact('data'));
    }
    public function get_item_type($invoice_number)
    {
        $item_type = inv_supplier_invoice_rel::leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
                           // ->leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                            ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                            ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                            ->leftJoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_supplier_invoice_rel.master','=', $invoice_number)->pluck('inv_item_type.type_name')->first();
        return $item_type;
    }
    public function MACAddItemInfo(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validation['accepted_quantity'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()){
                $data['accepted_quantity'] =$request->accepted_quantity;
                $data['available_qty'] = $request->accepted_quantity;
                $update = $this->inv_mac_item->update_data(['inv_mac_item.id'=>$request->id],$data);
                $mac_id = inv_mac_item_rel::where('item','=',$request->id)->pluck('master')->first();
                $stock_update = $this->stock_management($request->id);
                if($update)
                    $request->session()->flash('success', "You have successfully updated a MAC Item Info!");
                else
                    $request->session()->flash('error', "MAC Item info updation is failed. Try again... !");
                $mac_number = inv_mac::where('id','=',$mac_id)->first()->pluck('mac_number');
                if(str_starts_with($mac_number , 'MAC') )
                return redirect('inventory/MAC-add/'.$mac_id);
                else
                return redirect('inventory/WOA-add/'.$mac_id);
                //redirect()->back();
            }
        }
        $data = $this->inv_mac_item->get_item(['inv_mac_item.id'=>$id]);
        $mac_number = inv_mac_item_rel::leftJoin('inv_mac','inv_mac.id','=','inv_mac_item_rel.master')
                        ->where('item','=',$id)->pluck('inv_mac.mac_number')->first();
        $currency = $this->currency_exchange_rate->get_currency([]);
        return view('pages.inventory.MAC.MAC-itemInfo', compact('data','currency','mac_number'));
    }
    public function WOAAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['mac_date'] = ['required','date'];
            $validation['invoice_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(!$request->id)
                {
                    $item_type = $this->get_item_type($request->invoice_number);
                    if($item_type=="Direct Items"){
                        $Data['mac_number'] = "WOA2-".$this->po_num_gen(DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'WOA2%')->count(),1); 
                    }
                    //if($item_type=="Indirect Items"){
                    else {
                        $Data['mac_number'] = "WOA3-" . $this->po_num_gen(DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'WOA3%')->count(),1); 
                    }
                    $Data['mac_date'] = date('Y-m-d', strtotime($request->mac_date));
                    $Data['invoice_id'] = $request->invoice_number;
                    $Data['created_by']= $request->created_by;
                    $Data['status']=1;
                    $Data['created_at'] =date('Y-m-d H:i:s');
                    $Data['updated_at'] =date('Y-m-d H:i:s');
                    $add_id = $this->inv_mac->insert_data($Data);
                    $invoice_items = inv_supplier_invoice_rel::select('inv_supplier_invoice_rel.item','inv_supplier_invoice_item.item_id')
                                ->leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
                                ->where('inv_supplier_invoice_item.is_merged','=',0)
                                ->where('master','=',$request->invoice_number)->get();
                    foreach($invoice_items as $item){
                        $dat=[
                            'invoice_item_id'=>$item->item,
                            'pr_item_id'=>$item->item_id,
                            'status'=>1,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')

                        ];
                        $item_id = $this->inv_mac_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_mac_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a WOA !");
                    else
                        $request->session()->flash('error', "WOA creation is failed. Try again... !");
                    return redirect('inventory/WOA-add/'.$add_id);
                }
                else
                {
                    $data['mac_date'] = date('Y-m-d', strtotime($request->mac_date));
                    $data['created_by']= $request->created_by;
                    $data['updated_at'] =date('Y-m-d H:i:s');
                    $update = $this->inv_mac->update_data(['id'=>$request->id],$data);
                    if($update)
                        $request->session()->flash('success', "You have successfully updated a WOA !");
                    else
                        $request->session()->flash('error', "WOA updation is failed. Try again... !");
                    return redirect('inventory/WOA-add/'.$request->id);
                }
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/WOA-add')->withErrors($validator)->withInput();
            }
        }
       
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);

        if($request->id){
            $edit['mac'] = $this->inv_mac->find_mac_data(['inv_mac.id' => $request->id]);

            $edit['items'] = $this->inv_mac_item->get_items(['inv_mac_item_rel.master' =>$request->id]);
    
            return view('pages.inventory.MAC.WOA-add',compact('edit','data'));
        }
        
        return view('pages.inventory.MAC.WOA-add',compact('data'));
    }
    public function mac_delete(Request $request, $id)
    {
        $this->inv_mac->update_data(['id' => $id],['status'=>0]);
        // $mac_item_ids = inv_mac_item_rel::where('master','=',$id)->select('item')->get();
        // foreach($mac_item_ids as $mac_item)
        // {
        //     $stock = inv_stock_management::where('transaction_id','=',$mac_item['item'])->where('transaction_type',2)->first();
        //     if($stock)
        //     $stock_update = $this->inv_stock_management->update_data(['id'=>$stock['id']],['status'=>0]);
        // }
        $request->session()->flash('success', "You have successfully deleted a MAC !");
        return redirect("inventory/MAC");
    }

    





































    public function MACAdd1(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['mac_date'] = ['required','date'];
            $validation['miq_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(!$request->id)
                {
                    $item_type = $this->get_item_type($request->miq_number);
                    if($item_type=="Direct Items"){
                        $Data['mac_number'] = "MAC2-".$this->po_num_gen(DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'MAC2%')->count(),1); 
                    }
                    //if($item_type=="Indirect Items")
                    else{
                        $Data['mac_number'] = "MAC3-" . $this->po_num_gen(DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'MAC3%')->count(),1); 
                    }
                    $Data['mac_date'] = date('Y-m-d', strtotime($request->mac_date));
                    $Data['miq_id'] = $request->miq_number;
                    $Data['created_by']= $request->created_by;
                    $Data['status']=1;
                    $Data['created_at'] =date('Y-m-d H:i:s');
                    $Data['updated_at'] =date('Y-m-d H:i:s');
                    $add_id = $this->inv_mac->insert_data($Data);
                    $miq_items = inv_miq_item_rel::select('inv_miq_item_rel.item','inv_miq_item.item_id')
                                ->leftJoin('inv_miq_item','inv_miq_item.id','=','inv_miq_item_rel.item')
                                ->where('master','=',$request->miq_number)->get();
                    foreach($miq_items as $item){
                        $dat=[
                            'miq_item_id'=>$item->item,
                            'item_id'=>$item->item_id,
                            'status'=>1,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')

                        ];
                        $item_id = $this->inv_mac_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_mac_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a MAC !");
                    else
                        $request->session()->flash('error', "MAC creation is failed. Try again... !");
                    return redirect('inventory/MAC-add/'.$add_id);
                }
                else
                {
                    $data['mac_date'] = date('Y-m-d', strtotime($request->mac_date));
                    $data['created_by']= $request->created_by;
                    $data['updated_at'] =date('Y-m-d H:i:s');
                    $update = $this->inv_mac->update_data(['id'=>$request->id],$data);
                    if($update)
                        $request->session()->flash('success', "You have successfully updated a MAC !");
                    else
                        $request->session()->flash('error', "MAC updation is failed. Try again... !");
                    return redirect('inventory/MAC-add/'.$request->id);
                }
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/MAC-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);

        if($request->id){
            $edit['mac'] = $this->inv_mac->find_mac_data(['inv_mac.id' => $request->id]);

            $edit['items'] = $this->inv_mac_item->get_items(['inv_mac_item_rel.master' =>$request->id]);
            return view('pages.inventory.MAC.MAC-add',compact('edit','data'));
        }
        else
        return view('pages.inventory.MAC.MAC-add',compact('data'));
    }

    public function get_item_type1($invoice_number)
    {
        $item_type = inv_miq_item_rel::leftJoin('inv_miq_item','inv_miq_item.id','=','inv_miq_item_rel.item')
                            ->leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                            ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                            ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.requisition_item_id')
                            ->leftJoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_miq_item_rel.master','=', $miq_number)->pluck('inv_item_type.type_name')->first();
        return $item_type;
    }

    public function MACAddItemInfo1(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validation['accepted_quantity'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()){
                $data['accepted_quantity'] =$request->accepted_quantity;
                $data['available_qty'] = $request->accepted_quantity;
                $update = $this->inv_mac_item->update_data(['inv_mac_item.id'=>$request->id],$data);
                $mac_id = inv_mac_item_rel::where('item','=',$request->id)->pluck('master')->first();
                if($update)
                    $request->session()->flash('success', "You have successfully updated a MAC Item Info!");
                else
                    $request->session()->flash('error', "MAC Item info updation is failed. Try again... !");
                return redirect('inventory/MAC-add/'.$mac_id);
            }
        }
        $data = $this->inv_mac_item->get_item(['inv_mac_item.id'=>$id]);
        $currency = $this->currency_exchange_rate->get_currency([]);
        return view('pages.inventory.MAC.MAC-itemInfo', compact('data','currency'));
    }

    public function findMiqNumber(Request $request)
    {
        if ($request->q) {
            //echo $request->type;exit;
            $condition[] = ['inv_miq.miq_number', 'like', '%' . strtoupper($request->q) . '%'];
            $condition[] = ['inv_supplier_invoice_master.type','=',strtoupper($request->type)];
            $data = $this->inv_miq->find_miq_num($condition);
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

    public function find_miq_info(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_miq.miq_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_miq->find_miq_num($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->miq_details($request->id, null);
            exit;
        }
    }
    public function miq_details($id, $active = null)
    {
        $miq = $this->inv_miq->find_miq_data(['inv_miq.id' => $id]);

        $inv_miq_item = $this->inv_miq_item->get_items(['inv_miq_item_rel.master' => $id]);
        // if ($active) {
        //     $inv_mac_item = $this->inv_mac_item->get_mac_items(['inv_mac_rel.master' => $active]);
        // }

        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                Material Inwards To Quarantine (' . $miq->miq_number . ')
                   </label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
            
                </thead>
                <tbody>
                    <tr>
                        <th>MIQ Date</th>
                        <td>' . date('d-m-Y', strtotime($miq->miq_date)) . '</td>
                    </tr>
                    <tr>
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($miq->created_at)) . '</td>
                    </tr>
                    <tr>
                        <th>Supplier ID</th>
                        <td>'.$miq->vendor_id.'</td>
                        
                    </tr>
                    <tr>
                        <th>Supplier Name</th>
                        <td>'.$miq->vendor_name.'</td>
                    </tr>
                </tbody>
           </table>
           <br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
        if ($active) {
            $data .= 'MAC Items ';
        } else {
            $data .= 'MIQ Items ';
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
            foreach ($inv_miq_item as $item) {
                $data .= '<tr>
                       <td>lll</td>
                       <td>jjj</td>
                       <td>jjj</td>
                       <td>uuuu</td>
                       <td>yuyt</td>
                       <td>ouii</td>
                       <td><a class="badge badge-info" style="font-size: 13px;" href="' . url("inventory/supplier-invoice-item-edit/" . $active . '/' . $item->id) . '"><i class="fas fa-edit"></i> Edit</a></td>
                   </tr>';
            }
            $data .= '</tbody>';
        }

        if (!$active) {
            $data .= '<thead>
                   <tr>
                   <th>Item Code:</th>
                   <th>Lot No</th>
                   <th>Invoice Qty</th>
                   <th>Rate</th>
                   <th>Discount </th>
                   <th>Price </th>
                   <th>Price In Inr </th>
                   </tr>
               </thead>
               <tbody >';
            foreach ($inv_miq_item as $item) {
                $data .= '<tr>
                       <td>'.$item->item_code.'</td>
                       <td>'.$item->lot_number.'</td>
                       <td>'.$item->order_qty. $item->unit_name.'</td>
                       <td>'.$item->rate.'</td>
                       <td>'.$item->discount.'</td>
                       <td>'.(($item->rate*$item->order_qty*$item->discount)/100) . $item->currency_code.'</td>
                       <td>'.$item->value_inr.'</td>
                   </tr>';
            }
            $data .= '</tbody>';
        }

        $data .= '</table>
       </div>';
        return $data;
    }

    
   public function WOApdf($id)
   {
    $data['mac'] = $this->inv_mac->find_mac_data(['inv_mac.id' => $id]);

    $data['items'] = $this->inv_mac_item->get_items(['inv_mac_item_rel.master' =>$id]);
    $pdf = PDF::loadView('pages.inventory.MAC.WOA-pdf', $data);
    //$pdf->set_paper('A4', 'portiait');
    $file_name = $data['mac']['mac_number'];
    return $pdf->stream($file_name . '.pdf');
   }

   function getMergedPoinfo($invoice_item_id)
   {
        $data =inv_supplier_invoice_item::select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_item.order_qty')
                                            ->leftjoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_supplier_invoice_item.po_item_id')
                                            ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
                                            ->where('inv_supplier_invoice_item.merged_invoice_item','=',$invoice_item_id)
                                            ->get();
        return $data;
   }

   public function MACExport(Request $request)
    {
        if($request)
        {
            return Excel::download(new MACExport($request), 'MAC' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new MACExport($request), 'MAC' . date('d-m-Y') . '.xlsx');
        }
    }


    public function stock_management($mac_item_id)
    {
        $mac_item = inv_mac_item::where('id','=',$mac_item_id)->first();
        $lot_id = inv_lot_allocation::where('inv_lot_allocation.si_invoice_item_id','=',$mac_item['invoice_item_id'])->pluck('id')->first();
        $row_material_id = inv_purchase_req_item::where('requisition_item_id','=',$mac_item['pr_item_id'])->pluck('inv_purchase_req_item.item_code')->first();
       









        
        $mac_item_exist = inv_stock_transaction::where('transaction_id','=',$mac_item_id)->where('transaction_type',2)->first();
        if( $mac_item_exist)
        {
            $stock_transaction_update = $this->inv_stock_transaction->update_data(['id'=>$mac_item_exist['id']],['transaction_qty'=>$mac_item['accepted_quantity']]);
            if($lot_id)
            {
                $stock_update = $this->inv_stock_management->update_data(['lot_id'=>$lot_id],['stock_qty'=>$mac_item['accepted_quantity']]);
            }
            else
            {
                $current_material_stock = inv_stock_management::where('item_id','=',$row_material_id)->pluck('stock_qty')->first();
                $new_stock = $current_material_stock - $mac_item_exist['transaction_qty'] + $mac_item['accepted_quantity'];
                $stock_update = $this->inv_stock_management->update_data(['item_id'=>$row_material_id],['stock_qty'=>$new_stock]);
            }
            
        }
        else
        {
                $data['item_id'] = $row_material_id;
                $data['lot_id'] = $lot_id;
                $data['transaction_type'] = 2;
                $data['transaction_id'] = $mac_item['id'];
                $data['transaction_qty'] = $mac_item['accepted_quantity'];
                //$data['current_stock_qty'] = $last_stock_info['current_stock_qty']+ $mac_item['accepted_quantity'];
                $data['created_at'] = date('Y-m-d H:i:s');
                //$data['updated_at'] = date('Y-m-d H:i:s');
                $stock_transaction_add = $this->inv_stock_transaction->insert_data($data);

                
                if(!$lot_id)
                {
                    
                    $material_exist = inv_stock_management::where('item_id','=',$row_material_id)->first();
                    if($material_exist)
                    {
                        $new_stock_qty =$material_exist['stock_qty']+$mac_item['accepted_quantity'];
                        $stock_update = $this->inv_stock_management->update_data(['item_id'=>$row_material_id],['stock_qty'=>$new_stock_qty]);
                    }
                    else
                    {
                        $inf['item_id'] = $row_material_id;
                        $inf['stock_qty'] = $mac_item['accepted_quantity'];
                        $stock_add = $this->inv_stock_management->insert_data($inf);
                    }
                }
                else
                {
                    $info['item_id'] = $row_material_id;
                    $info['lot_id'] = $lot_id;
                    $info['stock_qty'] = $mac_item['accepted_quantity'];
                    $stock_add = $this->inv_stock_management->insert_data($info);
                }
                
        }
        return 1;
        
    }

    public function directmacstockImport()
    {
        $direct_mac = DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'MAC2%')->where('status','=',1)->get();
        foreach($direct_mac as $mac)
        {
            $mac_items = inv_mac_item_rel::where('master','=',$mac->id)->select('item')->get();
            foreach($mac_items as $macitem)
            {
                $mac_item = inv_mac_item::select('inv_mac_item.id as mac_item_id','inv_mac_item.accepted_quantity','inv_mac_item.created_at','inv_lot_allocation.id as lot_id','inv_purchase_req_item.Item_code')
                                ->leftJoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_mac_item.invoice_item_id')
                                ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_mac_item.pr_item_id')
                                ->where('inv_mac_item.id','=',$macitem->item)->where('accepted_quantity','!=',0)
                                ->first();
                $stock['item_id'] = $mac_item->Item_code;
                $stock['lot_id'] = $mac_item->lot_id;
                $stock['stock_qty'] = $mac_item->accepted_quantity;              
                DB::table('inv_stock_management')->insert($stock);

                $data['item_id'] = $mac_item->Item_code;
                $data['lot_id'] = $mac_item->lot_id;
                $data['transaction_type'] = 2;
                $data['transaction_id'] = $mac_item->mac_item_id;
                $data['transaction_qty'] = $mac_item->accepted_quantity;
                $data['created_at'] = $mac_item->created_at;
                DB::table('inv_stock_transaction')->insert($data);
                

            }
        }
        
    }
    function indirectmacstockImport()
    {
        $direct_mac = DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'MAC3%')->where('status','=',1)->get();
        foreach($direct_mac as $mac)
        {
            $mac_items = inv_mac_item_rel::where('master','=',$mac->id)->select('item')->get();
            foreach($mac_items as $macitem)
            {
                $mac_item = inv_mac_item::select('inv_mac_item.id as mac_item_id','inv_mac_item.accepted_quantity','inv_mac_item.created_at','inv_purchase_req_item.Item_code')
                                //->leftJoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_mac_item.invoice_item_id')
                                ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_mac_item.pr_item_id')
                                ->where('inv_mac_item.id','=',$macitem->item)->where('accepted_quantity','!=',0)
                                ->first();
                if($mac_item){
                $is_exist_stock = DB::table('inv_stock_management')->where('item_id','=',$mac_item['Item_code'])->first();
                if($is_exist_stock)
                {
                    $stock = inv_stock_management::where('id','=',$is_exist_stock->id)->first();
                    $stock_qty = $stock->stock_qty + $mac_item->accepted_quantity;
                    $stock->stock_qty = $stock_qty;
                    $stock->save();

                    $data['item_id'] = $mac_item->Item_code;
                    $data['transaction_type'] = 2;
                    $data['transaction_id'] = $mac_item->mac_item_id;
                    $data['transaction_qty'] = $mac_item->accepted_quantity;
                    $data['created_at'] = $mac_item->created_at;
                    DB::table('inv_stock_transaction')->insert($data);
                }
                else
                {
                    $stok['item_id'] = $mac_item->Item_code;
                    $stok['stock_qty'] = $mac_item->accepted_quantity;
                    DB::table('inv_stock_management')->insert($stok);
                    $data['item_id'] = $mac_item->Item_code;
                    $data['transaction_type'] = 2;
                    $data['transaction_id'] = $mac_item->mac_item_id;
                    $data['transaction_qty'] = $mac_item->accepted_quantity;
                    $data['created_at'] = $mac_item->created_at;
                    DB::table('inv_stock_transaction')->insert($data);
                }
            }
            }
        }
    }
    function indirectwoastockImport()
    {
        $direct_mac = DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'WOA3%')->where('status','=',1)->get();
        foreach($direct_mac as $mac)
        {
            $mac_items = inv_mac_item_rel::where('master','=',$mac->id)->select('item')->get();
            foreach($mac_items as $macitem)
            {
                $mac_item = inv_mac_item::select('inv_mac_item.id as mac_item_id','inv_mac_item.accepted_quantity','inv_mac_item.created_at','inv_purchase_req_item.Item_code')
                                //->leftJoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_mac_item.invoice_item_id')
                                ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_mac_item.pr_item_id')
                                ->where('inv_mac_item.id','=',$macitem->item)->where('accepted_quantity','!=',0)
                                ->first();
                if($mac_item)
                {               
                    $is_exist_stock = DB::table('inv_stock_management')->where('item_id','=',$mac_item['Item_code'])->first();
                    if($is_exist_stock)
                    {
                        $stock = inv_stock_management::where('id','=',$is_exist_stock->id)->first();
                        $stock_qty = $stock->stock_qty + $mac_item->accepted_quantity;
                        $stock->stock_qty = $stock_qty;
                        $stock->save();

                        $data['item_id'] = $mac_item->Item_code;
                        $data['transaction_type'] = 2;
                        $data['transaction_id'] = $mac_item->mac_item_id;
                        $data['transaction_qty'] = $mac_item->accepted_quantity;
                        $data['created_at'] = $mac_item->created_at;
                        DB::table('inv_stock_transaction')->insert($data);
                    }
                    else
                    {
                        $stock['item_id'] = $mac_item->Item_code;
                        $stock['stock_qty'] = $mac_item->accepted_quantity;
                        $stock = [
                            'item_id'=>$mac_item->Item_code,
                            'stock_qty'=> $mac_item->accepted_quantity
                        ];
                        DB::table('inv_stock_management')->insert($stock);
                        $data['item_id'] = $mac_item->Item_code;
                        $data['transaction_type'] = 2;
                        $data['transaction_id'] = $mac_item->mac_item_id;
                        $data['transaction_qty'] = $mac_item->accepted_quantity;
                        $data['created_at'] = $mac_item->created_at;
                        DB::table('inv_stock_transaction')->insert($data);
                    }
                }
            }
        }
    }
}
