<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;
use DB;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\PurchaseDetails\inv_purchase_req_quotation;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_item_supp_rel;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_supplier;
use App\Models\PurchaseDetails\inv_final_purchase_order_rel;



class QuotationController extends Controller
{
    public function __construct()
    {
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        $this->inv_purchase_req_quotation = new inv_purchase_req_quotation;
        $this->inv_supplier = new inv_supplier;
        $this->inv_purchase_req_quotation_item_supp_rel = new inv_purchase_req_quotation_item_supp_rel;
        $this->inv_purchase_req_quotation_supplier = new inv_purchase_req_quotation_supplier;
        $this->inv_final_purchase_order_rel = new inv_final_purchase_order_rel;
    }

    // list Quotation
    public function getQuotation(Request $request,$id=null)
    { 

        if ($request->prsr) {
            if($request->type)
            {
            $condition[] = ['inv_purchase_req_master.PR_SR', '=', strtolower($request->prsr)];
            $condition[] = ['inventory_rawmaterial.item_type_id','=',$request->type];
            }
            else
            $condition[] = ['inv_purchase_req_master.PR_SR', '=', strtolower($request->prsr)];
        }
        if (!$request->prsr) {
            if($request->type)
            {
            $condition[] = ['inv_purchase_req_master.PR_SR', '=','PR'];
            $condition[] = ['inventory_rawmaterial.item_type_id','=',$request->type];
            }
            $condition[] = ['inv_purchase_req_master.PR_SR', '=', 'PR'];
        }



      //  $data['reopen_supplier'] = $this->inv_purchase_req_quotation_supplier->inv_purchase_req_quotation_data(['inv_purchase_req_quotation_supplier.quotation_id'=>$id]);
        if($id){
            if ($request->prsr=='sr') 
            {
                $data['reopen_data_single'] = $this->inv_final_purchase_order_rel->singleGetData(['inv_final_purchase_order_rel.master'=>$id,'inv_final_purchase_order_master.type'=>'WO']);
                $data['reopen_data'] = $this->inv_final_purchase_order_rel->getData(['inv_final_purchase_order_rel.master'=>$id, 'inv_final_purchase_order_master.type'=>'WO']);
            }
            else if($request->prsr=='pr') {
                $data['reopen_data_single'] = $this->inv_final_purchase_order_rel->singleGetData(['inv_final_purchase_order_rel.master'=>$id, 'inv_final_purchase_order_master.type'=>'PO']);
                $data['reopen_data'] = $this->inv_final_purchase_order_rel->getData(['inv_final_purchase_order_rel.master'=>$id,'inv_final_purchase_order_master.type'=>'PO']);
            }
            else
            {
                $data['reopen_data_single'] = $this->inv_final_purchase_order_rel->singleGetData(['inv_final_purchase_order_rel.master'=>$id, 'inv_final_purchase_order_master.type'=>'PO']);
                $data['reopen_data'] = $this->inv_final_purchase_order_rel->getData(['inv_final_purchase_order_rel.master'=>$id,'inv_final_purchase_order_master.type'=>'PO']);
            }
            //print_r(json_encode($data['reopen_data']));exit;
        }
      
        $data['getdata'] = $this->inv_purchase_req_item->getqdata($condition);
      
        // print_r($data['getdata']);exit;
        return view('pages/purchase-details/Quotation/quotation-add', compact('data','id'));
    }

    public function postQuotation(Request $request)
    {
        $validation = [
            'date' => ['required', 'date'],
            'Supplier' => ['required'],
            'delivery' => ['required'],
            'purchase_requisition_item' => ['required'], // Ensure it's an array of item IDs
        ];
    
        $validator = Validator::make($request->all(), $validation);
    
        if (!$validator->errors()->all()) {
            $supplierIds = [ // List of all supplier IDs
                237, 83, 88, 87, 86, 100, 102, 81, 129, 76, 75, 58, 70, 68, 
                66, 63, 103, 59, 57, 121, 106, 147, 145, 94, 108, 47, 44119, 
                127, 120, 117, 32, 118, 153, 27, 26, 93, 125, 101, 96, 11, 
                151, 7, 148, 4
            ];
    
            $suppliers = $request->input('Supplier', []);
    
            // Check if any supplier in the request matches the given supplier list
            if (!empty(array_intersect($suppliers, $supplierIds))) {
                $expiredItems = [];
    
                foreach ($request->purchase_requisition_item as $item_id) {
                    $item = DB::table('inv_purchase_req_item')
                        ->where('requisition_item_id', $item_id)
                        ->first();
    
                    if ($item) {
                        $supplierItem = DB::table('inv_supplier_itemrate')
                            ->where('item_id', $item->Item_code)
                            ->whereIn('supplier_id', $supplierIds) // Using all supplier IDs
                            ->first();
    
                        if ($supplierItem && strtotime($supplierItem->rate_expiry_enddate) < strtotime(now())) {
                            $expiredItems[] = $item->Item_code;
                        }
                    }
                }
    
                if (!empty($expiredItems)) {
                    return redirect('inventory/quotation')
                        ->withErrors(['msg' => 'The following items have expired: ' . implode(', ', $expiredItems)])
                        ->withInput();
                }
            }
    
            // Generate the quotation number
            $yearMonth = date('y') . date('m');
            $rq_number = "RQ-" . $this->pr_num_gen(
                DB::table('inv_purchase_req_quotation')
                    ->where('rq_no', 'LIKE', '%RQ-' . $yearMonth . '%')
                    ->count()
            );
    
            $data = [
                'date' => date('Y-m-d', strtotime($request->date)),
                'delivery_schedule' => date('Y-m-d', strtotime($request->delivery)),
                'rq_no' => $rq_number,
                'created_at' => now(),
                'created_user' => config('user')['user_id'],
                'type' => ($request->prsr == 'sr') ? 'SR' : 'PR',
            ];
    
            // Insert quotation data
            $this->inv_purchase_req_quotation->insert_data($data, $request);
    
            // Update the status in `inv_purchase_req_item_approve` to 8
            DB::table('inv_purchase_req_item_approve')
                ->whereIn('pr_item_id', $request->purchase_requisition_item) 
                ->update(['status' => 8]);
    
            $request->session()->flash('success', "You have successfully created a Request For Quotation!");
    
            return redirect($request->prsr ? 'inventory/quotation?prsr=' . $request->prsr : 'inventory/quotation');
        }
    
        return redirect($request->prsr ? 'inventory/quotation?prsr=' . $request->prsr : 'inventory/quotation')
            ->withErrors($validator)
            ->withInput();
    }


    public function getItems(Request $request)
    {
        $data['getdata'] = $this->inv_purchase_req_item->getdata(['inv_item_type.type_name'=>$request->type]);
        return view('pages/purchase-details/Quotation/quotation-add', compact('data'));

    }
    function request_quotation($q_id,$s_id){
        // if((!$this->decrypt($q_id)) ||  (!$this->decrypt($s_id))){
        //     return response()->view('errors/404', [], 404);
        // }
        // $q_id = $this->decrypt($q_id);
        // $s_id = $this->decrypt($s_id);
        $data['inv_purchase_req_quotation'] = $this->inv_purchase_req_quotation->get_quotation_single(['quotation_id'=>$q_id]);
        $data['inv_supplier'] = $this->inv_supplier->get_supplier(['id'=>$s_id]);
        $data['inv_purchase_req_quotation_item_supp_rel'] = $this->inv_purchase_req_quotation_item_supp_rel->open_get_quotation(['inv_purchase_req_quotation_item_supp_rel.quotation_id'=>$q_id,'inv_purchase_req_quotation_item_supp_rel.supplier_id'=>$s_id]);
        return view('pages/purchase-details/supplier-quotation/quotation-open-view', compact('data'));
    }

    public function directPurchase(Request $request)
    {
        if ($request->prsr) {
            if($request->type)
            {
            $condition[] = ['inv_purchase_req_master.PR_SR', '=', strtolower($request->prsr)];
            $condition[] = ['inventory_rawmaterial.item_type_id','=',$request->type];
            }
            if($request->Supplier)
            {
                $condition[] = ['inv_supplier_itemrate.supplier_id','=',$request->Supplier];
            }
            else
            $condition[] = ['inv_purchase_req_master.PR_SR', '=', strtolower($request->prsr)];
        }
        if (!$request->prsr) {
            if($request->type)
            {
            $condition[] = ['inv_purchase_req_master.PR_SR', '=','PR'];
            $condition[] = ['inventory_rawmaterial.item_type_id','=',$request->type];
            }
            if($request->Supplier)
            {
                $condition[] = ['inv_supplier_itemrate.supplier_id','=',$request->Supplier];
            }
            $condition[] = ['inv_purchase_req_master.PR_SR', '=', 'PR'];
        }
        $data['getdata'] = $this->inv_purchase_req_item->getdataFixedItems($condition);
        return view('pages/purchase-details/Quotation/direct-purchase', compact('data'));
    }

    public function directPurchaseQuotation(Request $request)
    {
        
        $validation = [
            'date' => ['required', 'date'],
            'Supplier' => ['required'],
            'delivery' => ['required'],
            'purchase_requisition_item' => ['required']
        ];
    
        $validator = Validator::make($request->all(), $validation);
    
        if (!$validator->errors()->all()) {
            $supplierIds = [ // List of all supplier IDs
                237, 83, 88, 87, 86, 100, 102, 81, 129, 76, 75, 58, 70, 68, 
                66, 63, 103, 59, 57, 121, 106, 147, 145, 94, 108, 47, 44119, 
                127, 120, 117, 32, 118, 153, 27, 26, 93, 125, 101, 96, 11, 
                151, 7, 148, 4
            ];
    
            $suppliers = $request->input('Supplier');

            if (!is_array($suppliers)) {
                $suppliers = $suppliers ? [$suppliers] : [];
            }
                
            // Check if any supplier in the request matches the given supplier list
            if (!empty(array_intersect($suppliers, $supplierIds))) {
                $expiredItems = [];
    
                foreach ($request->purchase_requisition_item as $item_id) {
                    $item = DB::table('inv_purchase_req_item')
                        ->where('requisition_item_id', $item_id)
                        ->first();
    
                    if ($item) {
                        $supplierItem = DB::table('inv_supplier_itemrate')
                            ->where('item_id', $item->Item_code)
                            ->whereIn('supplier_id', $supplierIds) // Using all supplier IDs
                            ->first();
    
                        if ($supplierItem && strtotime($supplierItem->rate_expiry_enddate) < strtotime(now())) {
                            $expiredItems[] = $item->Item_code;
                        }
                    }
                }
    
                if (!empty($expiredItems)) {
                    return redirect('inventory/direct/purchase')
                        ->withErrors(['msg' => 'The following items have expired: ' . implode(', ', $expiredItems)])
                        ->withInput();
                }
            }
    
            $data = [
                'date' => date('Y-m-d', strtotime($request->date)),
                'delivery_schedule' => date('Y-m-d', strtotime($request->delivery)),
                'rq_no' => 'RQ-' . $this->num_gen($this->inv_purchase_req_quotation->get_count()),
                'created_at' => now(),
                'created_user' => config('user')['user_id'],
                'type' => ($request->prsr == 'sr') ? 'SR' : 'PR'
            ];
    
            // Insert the quotation data
            $this->inv_purchase_req_quotation->insert_fixed_item_data($data, $request);
    
            // Update the status of the corresponding `pr_item_id` to 8
            $prItemIds = $request->input('purchase_requisition_item'); // Assume this contains an array of `pr_item_id`
            DB::table('inv_purchase_req_item_approve')
                ->whereIn('pr_item_id', $prItemIds)
                ->update(['status' => 8]);
    
            $request->session()->flash('success', "You have successfully created a Request For Quotation!");
            return redirect($request->prsr ? 'inventory/direct/purchase?prsr=' . $request->prsr : 'inventory/direct/purchase');
        }
    
        return redirect($request->prsr ? 'inventory/direct/purchase?prsr=' . $request->prsr : 'inventory/direct/purchase')
            ->withErrors($validator)
            ->withInput();
    }
    
    
    public function get_supplier($id){
        $supplier = inv_supplier::where('id','=',$id)->pluck('vendor_name')->first();
        return $supplier;
    }

    public function checkFixedItem($item_id)
    {
        $supplier = inv_purchase_req_item::where('inv_purchase_req_item.requisition_item_id','=',$item_id)
                        ->leftjoin('inv_supplier_itemrate','inv_supplier_itemrate.item_id','=','inv_purchase_req_item.Item_code')
                        ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_itemrate.supplier_id')
                        ->whereIn('inv_purchase_req_item.Item_code',function($query){
                            $query->select('inv_supplier_itemrate.item_id')->from('inv_supplier_itemrate');
                        })
                        ->pluck('inv_supplier.vendor_name')->first();
        if($supplier)
        return $supplier;
        else 
        return 0;
    }

}
