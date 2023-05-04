<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\User;
use App\Models\PurchaseDetails\inv_supplier_invoice_master;
use App\Models\PurchaseDetails\inv_miq;
use App\Models\PurchaseDetails\inv_miq_item;
use App\Models\PurchaseDetails\inv_miq_item_rel;
use App\Models\PurchaseDetails\inv_supplier_invoice_rel;
use App\Models\PurchaseDetails\inv_supplier_invoice_item;
use App\Models\PurchaseDetails\inv_final_purchase_order_master;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_item_supp_rel;
use App\Models\currency_exchange_rate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MIQExport;
use App\Exports\MIQQuarantineExport;

class MIQController extends Controller
{
    public function __construct()
    {
        $this->User = new User;
        $this->inv_miq = new inv_miq;
        $this->inv_miq_item = new inv_miq_item;
        $this->inv_miq_item_rel = new inv_miq_item_rel;
        $this->inv_final_purchase_order_master = new inv_final_purchase_order_master;
        $this->inv_supplier_invoice_rel = new inv_supplier_invoice_rel;
        $this->inv_supplier_invoice_master = new inv_supplier_invoice_master;
        $this->inv_purchase_req_quotation_item_supp_rel = new inv_purchase_req_quotation_item_supp_rel;
        $this->inv_supplier_invoice_item = new inv_supplier_invoice_item;
        $this->currency_exchange_rate = new currency_exchange_rate;
    }
    public function MIQlist(Request $request)
    {   
        $condition = [];
        if($request)
        {
            if ($request->miq_no) {
                $condition[] = ['inv_miq.miq_number','like', '%' . $request->miq_no . '%'];
            }
            if ($request->invoice_no) {
                $condition[] = ['inv_supplier_invoice_master.invoice_number','like', '%' . $request->invoice_no . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
            
            if ($request->from) {
                $condition[] = ['inv_miq.miq_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_miq.miq_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
            $data['miq']= $this->inv_miq->get_all_data($condition);
        }
        else
        $data['miq']= $this->inv_miq->get_all_data($condition=null);
        return view('pages.inventory.MIQ.MIQ-list',compact('data'));
    }

    public function MIQAdd(Request $request,$id = null)
    {
        if ($request->isMethod('post')) {
            $validation['miq_date'] = ['required','date'];
            $validation['invoice_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()){
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
                    $item_type = $this->item_type($request->invoice_number);
                    if($item_type=="Direct Items"){
                        $Data['miq_number'] = "MIQ2-".$this->year_combo_num_gen(DB::table('inv_miq')->where('inv_miq.miq_number', 'LIKE', 'MIQ2-'.$years_combo.'%')->count()); 
                    }
                    //if($item_type=="Indirect Items"){
                    else{
                        $Data['miq_number'] = "MIQ3-" . $this->year_combo_num_gen(DB::table('inv_miq')->where('inv_miq.miq_number', 'LIKE', 'MIQ3-'.$years_combo.'%')->count()); 
                    }
                        
                        $Data['miq_date'] = date('Y-m-d', strtotime($request->miq_date));
                        $Data['invoice_master_id'] = $request->invoice_number;
                        $Data['created_by']= $request->created_by;
                        $Data['status']=1;
                        $Data['created_at'] =date('Y-m-d H:i:s');
                        $Data['updated_at'] =date('Y-m-d H:i:s');
                    
                    $add_id = $this->inv_miq->insert_data($Data);
                    $invoice_items = inv_supplier_invoice_rel::select('inv_supplier_invoice_rel.item','inv_supplier_invoice_item.item_id')
                                           ->leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
                                            ->where('inv_supplier_invoice_rel.master','=',$request->invoice_number)
                                            ->where('inv_supplier_invoice_item.is_merged','=',0)
                                            ->get();
                    foreach($invoice_items as $item){
                        $dat=[
                            'invoice_item_id'=>$item->item,
                            'item_id'=>$item->item_id,
                            'status'=>1,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')

                        ];
                        $item_id = $this->inv_miq_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_miq_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a MIQ !");
                    else
                        $request->session()->flash('error', "MIQ creation is failed. Try again... !");
                    return redirect('inventory/MIQ-add/'.$add_id);
                }
                else
                {
                        $Data['miq_date'] = date('Y-m-d', strtotime($request->miq_date));
                        $Data['invoice_master_id'] = $request->invoice_number;
                        $Data['created_by']= $request->created_by;
                        $Data['updated_at'] =date('Y-m-d H:i:s');
                    
                    $update = $this->inv_miq->update_data(['inv_miq.id'=>$request->id],$Data);
                    if($update)
                        $request->session()->flash('success', "You have successfully updated a MIQ !");
                    else
                        $request->session()->flash('error', "MIQ updation is failed. Try again... !");
                    return redirect('inventory/MIQ-add/'.$request->id);

                }
                
            }
            if($validator->errors()->all()){
                if($request->id)
                return redirect('inventory/MIQ-add/'.$request->id)->withErrors($validator)->withInput();
                else
                return redirect('inventory/MIQ-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);
        if($request->id){
            $data['miq']= $this->inv_miq->get_data(['inv_miq.id'=>$request->id]);
            $data['miq_items'] = $this->inv_miq_item->get_items(['inv_miq_item_rel.master'=>$request->id]);
        }
        return view('pages.inventory.MIQ.MIQ-add',compact('data'));
    }

    function item_type($invoice_number){
        $item_type = inv_supplier_invoice_rel::leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
                            ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                            ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.requisition_item_id')
                            ->leftJoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_supplier_invoice_rel.master','=', $invoice_number)->pluck('inv_item_type.type_name')->first();
        return $item_type;
    }

    public function MIQAddItemInfo(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            //$validation['lot_number'] = ['required'];
            $validation['currency'] = ['required'];
            $validation['conversion_rate'] = ['required'];
            $validation['value_inr'] = ['required'];
            $validation['expiry_control'] = ['required'];
            $validation['expiry_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()){
                $data['lot_number'] =$request->lot_number;
                $data['currency'] = $request->currency;
                $data['conversion_rate']= $request->conversion_rate;
                $data['value_inr']= $request->value_inr;
                $data['expiry_date']=date('Y-m-d', strtotime($request->expiry_date));
                $data['expiry_control'] =$request->expiry_control;
                $update = $this->inv_miq_item->update_data(['inv_miq_item.id'=>$request->id],$data);
                $miq_id = inv_miq_item_rel::where('item','=',$request->id)->pluck('master')->first();
                if($update)
                    $request->session()->flash('success', "You have successfully updated a MIQ Item Info!");
                else
                    $request->session()->flash('error', "MIQ Item info updation is failed. Try again... !");
                return redirect('inventory/MIQ-add/'.$miq_id);
            }
            if($validator->errors()->all()){
                return redirect('inventory/MIQ/'.$request->id.'/item')->withErrors($validator)->withInput();
            }
        }
        $data = $this->inv_miq_item->get_item(['inv_miq_item.id'=>$id]);
        //print_r(json_encode($data));
        $currency = $this->currency_exchange_rate->get_currency([]);
        return view('pages.inventory.MIQ.MIQ-itemInfo-add',compact('data','currency'));
    }

    public function findInvoiceNumber(Request $request){
        if ($request->q) {
            $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_supplier_invoice_master->find_invoice_num($condition);
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
    public function miq_delete(Request $request, $id)
    {

        $this->inv_miq->update_data(['id' => $id],['status'=>0]);
        $request->session()->flash('success', "You have successfully deleted a MIQ !");
        return redirect("inventory/MIQ");
    }

    public function getCurrency($invoice_item_id)
    {
        $po_master_id = inv_supplier_invoice_item::where('id','=',$invoice_item_id)->pluck('po_master_id')->first();
        if(!$po_master_id)
        {
            $po_master_id = inv_supplier_invoice_item::where('merged_invoice_item','=',$invoice_item_id)->pluck('po_master_id')->first();
        }
        $po_master = inv_final_purchase_order_master::where('id','=',$po_master_id)->first();
        //print_r($po_master_id);exit; 
        $currency = inv_purchase_req_quotation_item_supp_rel::where('quotation_id','=',$po_master['rq_master_id'])->where('supplier_id','=',$po_master['supplier_id'])
        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id', '=', 'inv_purchase_req_quotation_item_supp_rel.currency')
        ->where('inv_purchase_req_quotation_item_supp_rel.selected_item','=',1)
                            ->pluck('currency_id')->first();
        return $currency;
    }

    public function MIQExport(Request $request)
    {
        if($request)
        {
            return Excel::download(new MIQExport($request), 'MIQ' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new MIQExport($request), 'MIQ' . date('d-m-Y') . '.xlsx');
        }
    }
    public function MIQQuarantineExport(Request $request)
    {
        if($request)
        {
            return Excel::download(new MIQQuarantineExport($request), 'QuarantineReport' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new MIQQuarantineExport($request), 'QuarantineReport' . date('d-m-Y') . '.xlsx');
        }
    }
      public function LiveQuarantineReport(Request $request)
    {   
        $condition = [];
        if($request)
        {
            if ($request->miq_no) {
                $condition[] = ['inv_miq.miq_number','like', '%' . $request->miq_no . '%'];
            }
            if ($request->item_code) {
                $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
            
            if ($request->from) {
                $condition[] = ['inv_miq.miq_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_miq.miq_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
           $data['miq']= $this->inv_miq_item->get_all_data_not_in_mac($condition);
           //$data['miq']= $this->inv_miq_item->get_items_not_in_mac($condition);
        }
        else
        $data['miq']= $this->inv_miq_item->get_all_data_not_in_mac($condition=null);
        //$data['miq']= $this->inv_miq_item->get_items_not_in_mac($condition=null);

        return view('pages.inventory.MIQ.LiveQuarantineReport',compact('data'));
    }


    
}
