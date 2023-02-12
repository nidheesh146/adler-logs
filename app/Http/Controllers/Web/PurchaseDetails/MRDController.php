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
use App\Models\PurchaseDetails\inv_mrd_item;
use App\Models\PurchaseDetails\inv_mrd_item_rel;
use App\Models\PurchaseDetails\inv_rmrn;
use App\Models\PurchaseDetails\inv_rmrn_item;
use App\Models\PurchaseDetails\inv_rmrn_item_rel;
use App\Models\PurchaseDetails\inv_final_purchase_order_master;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_item_supp_rel;
use App\Models\PurchaseDetails\inv_supplier_invoice_master;
use App\Models\PurchaseDetails\inv_supplier_invoice_item;
use App\Models\PurchaseDetails\inv_supplier_invoice_rel;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\User;
use App\Models\currency_exchange_rate;

class MRDController extends Controller
{
    public function __construct()
    {
        $this->inv_miq = new inv_miq;
        $this->inv_miq_item = new inv_miq_item;
        $this->inv_miq_item_rel = new inv_miq_item_rel;
        $this->inv_mrd = new inv_mrd;
        $this->inv_mrd_item = new inv_mrd_item;
        $this->inv_mrd_item_rel = new inv_mrd_item_rel;
        $this->inv_rmrn = new inv_rmrn;
        $this->inv_rmrn_item = new inv_rmrn_item;
        $this->inv_rmrn_item_rel = new inv_rmrn_item_rel;
        $this->User = new User;
        $this->inv_final_purchase_order_master = new inv_final_purchase_order_master;
        $this->inv_purchase_req_quotation_item_supp_rel = new inv_purchase_req_quotation_item_supp_rel;
        $this->currency_exchange_rate = new currency_exchange_rate;
        $this->inv_supplier_invoice_master = new inv_supplier_invoice_master;
        $this->inv_supplier_invoice_item =new inv_supplier_invoice_item;
        $this->inv_supplier_invoice_rel =new inv_supplier_invoice_rel;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
    }

    public function MRDlist(Request $request)
    {
        $condition=[];
        if($request)
        {
            if ($request->miq_no) {
                $condition[] = ['inv_supplier_invoice_master.invoice_number','like', '%' . $request->invoice_no . '%'];
            }
            if ($request->mrd_no) {
                $condition[] = ['inv_mrd.mrd_number','like', '%' . $request->mrd_no . '%'];
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
                $condition[] = ['inv_mrd.mrd_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_mrd.mrd_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
            $data= $this->inv_mrd->get_all_data($condition);
        }
        else
        {
            $condition[] = ['inv_supplier_invoice_master.type','=', 'PO'];
            $data = $this->inv_mrd->get_all_data($condition);
        }
        //$data = $this->inv_mrd->get_all_data([]);
        return view('pages.inventory.MRD.MRD-list',compact('data'));
    }
    public function findInvoiceNumberForMRD(Request $request){
        if ($request->q) {
            $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_supplier_invoice_master->find_invoice_num_for_mrd($condition);
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
    public function findInvoiceNumberForWOR(Request $request){
        if ($request->q) {
            $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_supplier_invoice_master->find_invoice_num_for_wor($condition);
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
           
            $data = $this->inv_supplier_invoice_master->find_invoice_num_for_mrd($condition);
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
        $invoice_item = $this->inv_supplier_invoice_item->get_supplier_invoice_item_mac(['inv_supplier_invoice_rel.master' => $id]);
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
                   <th>Accepted Qty</th>
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
                       <td>'.$item->accepted_quantity. $item->unit_name.'</td>
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

    public function MRDAdd(Request $request,$id=null)
    {
        if($request->isMethod('post'))
        {
            $validation['mrd_date'] = ['required','date'];
            $validation['invoice_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(!$request->id)
                {
                    $item_type = $this->get_item_type($request->invoice_number);
                    if($item_type=="Direct Items"){
                        $Data['mrd_number'] = "MRD2-".$this->po_num_gen(DB::table('inv_mrd')->where('inv_mrd.mrd_number', 'LIKE', 'MRD2%')->count(),1); 
                    }
                    if($item_type=="Indirect Items"){
                        $Data['mrd_number'] = "MRD3-" . $this->po_num_gen(DB::table('inv_mrd')->where('inv_mrd.mrd_number', 'LIKE', 'MRD3%')->count(),1); 
                    }
                    $Data['mrd_date'] = date('Y-m-d', strtotime($request->mrd_date));
                    $Data['invoice_id'] = $request->invoice_number;
                    $Data['created_by']= $request->created_by;
                    $Data['status']=1;
                    $Data['created_at'] =date('Y-m-d H:i:s');
                    $Data['updated_at'] =date('Y-m-d H:i:s');
                    $add_id = $this->inv_mrd->insert_data($Data);
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
                        $item_id = $this->inv_mrd_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_mrd_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a MRD !");
                    else
                        $request->session()->flash('error', "MRD creation is failed. Try again... !");
                    return redirect('inventory/MRD-add/'.$add_id);
                }
                else
                {
                    //echo $request->created_by;exit;
                        $data['mrd_date'] = date('Y-m-d', strtotime($request->mrd_date));
                        $data['created_by']= $request->created_by;
                        $data['updated_at'] =date('Y-m-d H:i:s');
                        $update = $this->inv_mrd->update_data(['id'=>$request->id],$data);
                    if($update)
                        $request->session()->flash('success', "You have successfully updated a MRD !");
                    else
                        $request->session()->flash('error', "MRD updation is failed. Try again... !");
                    return redirect('inventory/MRD-add/'.$request->id);
                }
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/MRD-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);

        if($request->id){
            $edit['mrd'] = $this->inv_mrd->find_mrd_data(['inv_mrd.id' => $request->id]);

            $edit['items'] = $this->inv_mrd_item->get_items(['inv_mrd_item_rel.master' =>$request->id]);
            return view('pages.inventory.MRD.MRD-add',compact('edit','data'));
        }
        else
        return view('pages.inventory.MRD.MRD-add',compact('data'));
    }
    public function get_item_type($invoice_number)
    {
        $item_type = inv_supplier_invoice_rel::Join('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                            ->Join('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
                            //->leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                            ->Join('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                            ->Join('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                            ->Join('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_supplier_invoice_rel.master','=', $invoice_number)->pluck('inv_item_type.type_name')->first();
        return $item_type;
    }
    public function MRDAddItemInfo(Request $request,$id)
    {
        if ($request->isMethod('post')) {
            $validation['rejected_quantity'] = ['required'];
            $validation['currency'] = ['required'];
            $validation['conversion_rate'] = ['required'];
            $validation['value_inr'] = ['required'];
            $validation['remarks'] = ['required'];

            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()){
                $data['rejected_quantity'] =$request->rejected_quantity;
                $data['remarks'] =$request->remarks;
                $data['currency'] =$request->currency;
                $data['value_inr'] =$request->value_inr;
                $data['conversion_rate'] =$request->conversion_rate;
                $update = $this->inv_mrd_item->update_data(['inv_mrd_item.id'=>$request->id],$data);
                $mrd_id = inv_mrd_item_rel::where('item','=',$request->id)->pluck('master')->first();
                if($update)
                    $request->session()->flash('success', "You have successfully updated a MRD Item Info!");
                else
                    $request->session()->flash('error', "MRD Item info updation is failed. Try again... !");
                return redirect('inventory/MRD-add/'.$mrd_id);
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/MRD/'.$id.'/item')->withErrors($validator)->withInput();
            }
        }
        $data = $this->inv_mrd_item->get_item(['inv_mrd_item.id'=>$id]);
        $currency = $this->currency_exchange_rate->get_currency([]);
        return view('pages.inventory.MRD.MRD-itemInfo', compact('data','currency'));

    }
    public function getCurrency($invoice_item_id)
    {
        $invoice_item = inv_supplier_invoice_item::where('id','=',$invoice_item_id)->first();
        $po_master_id = inv_supplier_invoice_item::where('id','=',$invoice_item_id)->pluck('po_master_id')->first();
        if(!$po_master_id)
        {
            $po_master_id = inv_supplier_invoice_item::where('merged_invoice_item','=',$invoice_item_id)->pluck('po_master_id')->first();
        }
        $po_master = inv_final_purchase_order_master::where('id','=',$po_master_id)->first();
        $currency = inv_purchase_req_quotation_item_supp_rel::leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id', '=', 'inv_purchase_req_quotation_item_supp_rel.currency')
                            ->where('quotation_id','=',$po_master['rq_master_id'])
                            ->where('supplier_id','=',$po_master['supplier_id'])
                            ->where('item_id','=',$invoice_item['item_id'])
                            ->where('inv_purchase_req_quotation_item_supp_rel.selected_item','=',1)
                            ->pluck('currency_id')->first();
       return $currency;
    }

    public function WORAdd(Request $request, $id=null)
    {
        if($request->isMethod('post'))
        {
            $validation['mrd_date'] = ['required','date'];
            $validation['invoice_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(!$request->id)
                {
                    
                    $item_type = $this->get_item_type($request->invoice_number);
                    if($item_type=="Direct Items"){
                        $Data['mrd_number'] = "WOR2-".$this->po_num_gen(DB::table('inv_mrd')->where('inv_mrd.mrd_number', 'LIKE', 'WOR2%')->count(),1); 
                    }
                    if($item_type=="Indirect Items"){
                        $Data['mrd_number'] = "WOR3-" . $this->po_num_gen(DB::table('inv_mrd')->where('inv_mrd.mrd_number', 'LIKE', 'WOR3%')->count(),1); 
                    }
                    $Data['mrd_date'] = date('Y-m-d', strtotime($request->mrd_date));
                    $Data['invoice_id'] = $request->invoice_number;
                    $Data['created_by']= $request->created_by;
                    $Data['status']=1;
                    $Data['created_at'] =date('Y-m-d H:i:s');
                    $Data['updated_at'] =date('Y-m-d H:i:s');
                    $add_id = $this->inv_mrd->insert_data($Data);
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
                        $item_id = $this->inv_mrd_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_mrd_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a MRD !");
                    else
                        $request->session()->flash('error', "MRD creation is failed. Try again... !");
                    return redirect('inventory/WOR-add/'.$add_id);
                }
                else
                {
                    //echo $request->created_by;exit;
                        $data['mrd_date'] = date('Y-m-d', strtotime($request->mrd_date));
                        $data['created_by']= $request->created_by;
                        $data['updated_at'] =date('Y-m-d H:i:s');
                        $update = $this->inv_mrd->update_data(['id'=>$request->id],$data);
                    if($update)
                        $request->session()->flash('success', "You have successfully updated a MRD !");
                    else
                        $request->session()->flash('error', "MRD updation is failed. Try again... !");
                    return redirect('inventory/WOR-add/'.$request->id);
                }
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/WOR-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);
        if($request->id)
        {
           // $edit['mrd'] = $this->inv_mrd->find_mrd_data(['inv_mrd.id' => $request->id]);
            $edit['mrd'] = inv_mrd::select('inv_mrd.mrd_number','inv_mrd.id','inv_mrd.created_at','inv_mrd.created_by','user.f_name','user.l_name','inv_mrd.mrd_date',
        'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.id as invoice_id')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_mrd.invoice_id')
                    ->leftjoin('inv_miq','inv_miq.invoice_master_id','=','inv_supplier_invoice_master.id')
                    ->leftjoin('user','user.user_id','=','inv_miq.created_by')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->where('inv_mrd.id',$request->id)->first();
            //echo $edit['mrd']['mrd_number'];exit;
            $edit['items'] = $this->inv_mrd_item->get_items(['inv_mrd_item_rel.master' =>$request->id]);
            return view('pages.inventory.MRD.WOR-add',compact('edit','data'));
        }
        else
        return view('pages.inventory.MRD.WOR-add',compact('data'));
    }
    public function mrd_delete(Request $request, $id)
    {
        $this->inv_mrd->update_data(['id' => $id],['status'=>0]);
        $request->session()->flash('success', "You have successfully deleted a MRD !");
        return redirect("inventory/MRD");
    }























    


    

    public function findMiqNumberForMRD(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_miq.miq_number', 'like', '%' . strtoupper($request->q) . '%'];
            $condition[] = ['inv_supplier_invoice_master.type','=',strtoupper($request->type)];
            $data = $this->inv_miq->find_miq_num_for_mrd($condition);
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
    

   

    
    public function RMRNlist(Request $request)
    {
        $condition=[];
        if($request)
        {
            if ($request->rmrn_no) {
                $condition[] = ['inv_rmrn.rmrn_number','like', '%' . $request->rmrn_no . '%'];
            }
            if ($request->mrd_no) {
                $condition[] = ['inv_mrd.mrd_number','like', '%' . $request->mrd_no . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
            
            if ($request->from) {
                $condition[] = ['inv_rmrn.rmrn_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_rmrn.rmrn_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
            $data= $this->inv_rmrn->get_all_data($condition);
        }
        else
        {
            $data = $this->inv_rmrn->get_all_data($condition=null);
        }
        
        return view('pages.inventory.RMRN.RMRN-list',compact('data'));
    }
    public function RMRNAdd(Request $request,$id=null)
    {
        if($request->isMethod('post'))
        {
            $validation['rmrn_date'] = ['required','date'];
            $validation['mrd_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(!$request->id)
                {
                    $item_type = $this->get_item_type($request->mrd_number);
                    if($item_type=="Direct Items"){
                        $Data['rmrn_number'] = "RMRN2-".$this->po_num_gen(DB::table('inv_rmrn')->where('inv_rmrn.rmrn_number', 'LIKE', 'RMRN2%')->count(),1); 
                    }
                    if($item_type=="Indirect Items"){
                        $Data['rmrn_number'] = "RMRN3-" . $this->po_num_gen(DB::table('inv_rmrn')->where('inv_rmrn.rmrn_number', 'LIKE', 'RMRN3%')->count(),1); 
                    }
                    $Data['rmrn_date'] = date('Y-m-d', strtotime($request->rmrn_date));
                    $Data['mrd_id'] = $request->mrd_number;
                    $Data['created_by']= $request->created_by;
                    $Data['status']=1;
                    $Data['created_at'] =date('Y-m-d H:i:s');
                    $Data['updated_at'] =date('Y-m-d H:i:s');
                    $add_id = $this->inv_rmrn->insert_data($Data);
                    $mrd_items = inv_mrd_item_rel::select('inv_mrd_item_rel.item','inv_mrd_item.pr_item_id')
                                ->leftJoin('inv_mrd_item','inv_mrd_item.id','=','inv_mrd_item_rel.item')
                                ->where('master','=',$request->mrd_number)
                                ->where('inv_mrd_item.rejected_quantity','!=',NULL)
                                ->get();
                    foreach($mrd_items as $item){
                        $dat=[
                            'mrd_item_id'=>$item->item,
                            'pr_item_id'=>$item->pr_item_id,
                            //'status'=>1,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')

                        ];
                        $item_id = $this->inv_rmrn_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_rmrn_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a RMRN !");
                    else
                        $request->session()->flash('error', "RMRN creation is failed. Try again... !");
                    return redirect('inventory/RMRN-add/'.$add_id);
                }
                else
                {
                    //echo $request->created_by;exit;
                        $data['rmrn_date'] = date('Y-m-d', strtotime($request->rmrn_date));
                        $data['created_by']= $request->created_by;
                        $data['updated_at'] =date('Y-m-d H:i:s');
                        $update = $this->inv_rmrn->update_data(['id'=>$request->id],$data);
                    if($update)
                        $request->session()->flash('success', "You have successfully updated a RMRN !");
                    else
                        $request->session()->flash('error', "RMRN updation is failed. Try again... !");
                    return redirect('inventory/RMRN-add/'.$request->id);
                }
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/RMRN-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);

        if($request->id){
            $edit['rmrn'] = $this->inv_rmrn->find_rmrn_data(['inv_rmrn.id' => $request->id]);

            $edit['items'] = $this->inv_rmrn_item->get_items(['inv_rmrn_item_rel.master' =>$request->id]);
            return view('pages.inventory.RMRN.RMRN-add',compact('edit','data'));
        }
        else
        return view('pages.inventory.RMRN.RMRN-add',compact('data'));
    }
    public function find_mrd(Request $request)
    {
        if ($request->q) {
            //echo $request->type;exit;
            $condition[] = ['inv_mrd.mrd_number', 'like', '%' . strtoupper($request->q) . '%'];
            
            $data = $this->inv_mrd->find_mrd_not_in_rmrn($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mrd_details($request->id, null);
            exit;
        }
    }

    public function find_mrd_info(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_mrd.mrd_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_mrd->find_mrd_not_in_rmrn($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mrd_details($request->id, null);
            exit;
        }
    }
    public function mrd_details($id, $active = null)
    {
        $mrd = $this->inv_mrd->find_mrd_data(['inv_mrd.id' => $id]);

        $inv_mrd_items = $this->inv_mrd_item->get_items_for_rmrn(['inv_mrd_item_rel.master' => $id]);
        // if ($active) {
        //     $inv_mac_item = $this->inv_mac_item->get_mac_items(['inv_mac_rel.master' => $active]);
        // }

        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                Material Inwards To Quarantine (' . $mrd->mrd_number . ')
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
                        <td>' . date('d-m-Y', strtotime($mrd->mrd_date)) . '</td>
                    </tr>
                    <tr>
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($mrd->created_at)) . '</td>
                    </tr>
                    <tr>
                        <th>Supplier ID</th>
                        <td>'.$mrd->vendor_id.'</td>
                        
                    </tr>
                    <tr>
                        <th>Supplier Name</th>
                        <td>'.$mrd->vendor_name.'</td>
                    </tr>
                </tbody>
           </table>
           <br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
            $data .= 'MRD Items ';
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
                   <th>Rejected Qty</th>
                   <th>Currency</th>
                   <th>conversion rate </th>
                   <th>Price In Inr </th>
                   <th>Reason</th>
                   </tr>
               </thead>
               <tbody >';
            foreach ($inv_mrd_items as $item) {
                $data .= '<tr>
                       <td>'.$item->item_code.'</td>
                       <td>'.$item->type_name.'</td>
                       <td>'.$item->rejected_quantity. $item->unit_name.'</td>
                       <td>'.$item->currency_code.'</td>
                       <td>'.$item->mrd_conversion_rate.'</td>
                       <td>'. $item->value_inr.'</td>
                       <td>'.$item->remarks.'</td>
                   </tr>';
            }
            $data .= '</tbody>';
        

        $data .= '</table>
       </div>';
        return $data;
    }

    public function RMRNDelete(Request $request, $id)
    {
        $this->inv_rmrn->deleteData(['id' => $id]);
        $request->session()->flash('success', "You have successfully deleted a RMRN !");
        return redirect("inventory/RMRN");
    }
    public function RMRNAddItemInfo(Request $request,$id)
    {
        if ($request->isMethod('post')) {
            $validation['courier_transport_name'] = ['required'];
            $validation['receipt_lr_number'] = ['required'];
            $validation['dispatched_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()){
                if($request->file('receipt_file')){
                    $file= $request->file('receipt_file');
                    $filename= date('YmdHi').$file->getClientOriginalName();
                    $file-> move(public_path('public/receipt/Image'), $filename);
                    //$data['image']= $filename;
                }
                else {
                    $filename= "";
                }
                $data['receipt_path']=$filename;
                $data['dispatched_date']=$request->dispatched_date;
                $data['courier_transport_name'] =$request->courier_transport_name;
                $data['receipt_lr_number'] =$request->receipt_lr_number;
                $update = $this->inv_rmrn_item->update_data(['inv_rmrn_item.id'=>$request->id],$data);
                $mrd_id = inv_rmrn_item_rel::where('item','=',$request->id)->pluck('master')->first();
                if($update)
                    $request->session()->flash('success', "You have successfully updated a RMRN Item Info!");
                else
                    $request->session()->flash('error', "RMRN Item info updation is failed. Try again... !");
                return redirect('inventory/RMRN-add/'.$mrd_id);
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/RMRN/'.$id.'/item')->withErrors($validator)->withInput();
            }
        }
        $data = $this->inv_rmrn_item->get_item(['inv_rmrn_item.id'=>$id]);
        return view('pages.inventory.RMRN.RMRN-itemInfo', compact('data'));

    }
    public function RMRNpdf($id)
    {
        $data['rmrn'] = $this->inv_rmrn->find_rmrn_data(['inv_rmrn.id' => $id]);
        $data['items'] = $this->inv_rmrn_item->get_items(['inv_rmrn_item_rel.master' => $id]);
        $pdf = PDF::loadView('pages.inventory.RMRN.RMRN-pdf', $data);
        //$pdf->set_paper('A4', 'portait');
        $file_name = "RMRN_" . $data['rmrn']['vendor_name'] . "_" . $data['rmrn']['rmr_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    
   

    
}
