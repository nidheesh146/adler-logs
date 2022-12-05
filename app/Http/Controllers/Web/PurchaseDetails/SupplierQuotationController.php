<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseDetails\inv_purchase_req_quotation;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_supplier;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_item_supp_rel;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\PurchaseDetails\inv_purchase_req_master_item_rel;
use App\Models\PurchaseDetails\inv_purchase_req_master;
use App\Models\PurchaseDetails\inv_supplier_itemrate;
use App\Models\currency_exchange_rate;
use App\Models\inventory_gst;
use Validator;

USE DB;

class SupplierQuotationController extends Controller
{
    public function __construct()
    {
        $this->inv_purchase_req_quotation = new inv_purchase_req_quotation;
        $this->inv_supplier = new inv_supplier;
        $this->inv_supplier_itemrate = new inv_supplier_itemrate;
        $this->inv_purchase_req_quotation_supplier = new inv_purchase_req_quotation_supplier;
        $this->inv_purchase_req_quotation_item_supp_rel = new inv_purchase_req_quotation_item_supp_rel;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        $this->inv_purchase_req_master_item_rel = new inv_purchase_req_master_item_rel;
        $this->inv_purchase_req_master = new inv_purchase_req_master;
        $this->currency_exchange_rate = new currency_exchange_rate;
        $this->inventory_gst = new inventory_gst;
    }

    public function getSupplierQuotation(Request $request) 
    {     
           $condition = [];
            if ($request->rq_no) {
                $condition[] = ['inv_purchase_req_quotation.rq_no', 'like', '%'.$request->rq_no.'%'];
            }
            if ($request->prsr) {
                $condition[] = ['inv_purchase_req_quotation.type', '=', strtolower($request->prsr)];
            }
            if (!$request->prsr) {
                $condition[] = ['inv_purchase_req_quotation.type', '=', 'PR'];
            }
            if ($request->from) {
                $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
           
            if ($request->supplier) {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
       
           // $data['po_data'] =  $this->inv_final_purchase_order_master->get_purchase_master($condition);
           //  $this->inv_purchase_req_quotation->get_quotation($condition);

        $data['quotation'] =  $this->inv_purchase_req_quotation_supplier->get_quotation_all( $condition);
      // print_r( $data['quotation']);die;
        $data['suppliers'] = $this->inv_supplier->get_all_suppliers();
        $data['rq_nos'] = $this->inv_purchase_req_quotation->get_rq_nos();
        //$data['quotation'] = $this->inv_purchase_req_quotation->get_quotation([]);
        //print_r(json_encode($data['quotation']));exit;
        return view('pages/purchase-details/supplier-quotation/supplier-quotation', compact('data'));
    }
    function get_supplier($id){
      $suppliers ="";
      $supplier_id ="";

      $quotation_supplier =  $this->inv_purchase_req_quotation_supplier->getItem(['quotation_id'=>$id]);
      $count =  count($quotation_supplier);
      foreach($quotation_supplier as $key => $quotation_supplier){
         $supplier =  $this->inv_supplier->get_supplier(['id'=>$quotation_supplier->supplier_id]);
        if(!$supplier_id){
            $supplier_id = $supplier->id;
        }
         if($supplier){
            $suppliers .="<span>".$supplier->vendor_id."</span> - <span>".$supplier->vendor_name."</span>" ;
            if(  $key !=  ( $count - 1)){
                $suppliers .= " <br> ";
            }   
        }

      }
        return ["supplier" => $suppliers,'supplier_id' => $supplier_id];
    }

    function check_reqisition_type($id)
    {
        $item_id = inv_purchase_req_quotation_item_supp_rel::where('quotation_id','=',$id)->pluck('item_id')->first();
        $requisition_item_id = inv_purchase_req_item::where('requisition_item_id','=',$item_id)->pluck('requisition_item_id')->first();
        $requisition_master_id = inv_purchase_req_master_item_rel::where('item','=',$requisition_item_id)->pluck('master')->first();
        $reqisition_type = inv_purchase_req_master::where('master_id','=',$requisition_master_id)->pluck('PR_SR')->first();
        return $reqisition_type;

    }
   

    public function delete_supplier_quotation(Request $request) {
        if($request->qr_id)
        {
            $Request['Method'] = 'POST';
            $Request['URL'] = config('app.ApiURL') . "/inventory/supplier-quotation-master-add-edit-delete/";
            $Request['param'] = json_encode([
                "action_type" => "DeleteSupplierQuotationMaster",
                "supplier_quotation_id " => $request->qr_id
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
            if(!empty($data['response']['message']) && $data['response']['success']){
                $request->session()->flash('success',  $data['response']['message']);
            }
        }
        return redirect('inventory/supplier-quotation');
    }

    public function edit_supplier_quotation(Request $request)
    {
        

        $Request['Method'] = 'POST';
        $Request['URL'] = config('app.ApiURL') . "/inventory/supplier-quotation-master-add-edit-delete/";

        if ($request->isMethod('post')) {
            $Request['param'] = json_encode([
                "action_type" => "EditSupplierQuotationMaster",
                "quotation_no" => $request->quotation_no,
                 // "pr_no" => "PR-" . date('y') . date('m') . sprintf("%03d", date('d')),
                "rq_no" =>  $request->rq_no,
                "supplier"  => $request->supplier,
                "date"=>$request->date,
                "requestor" => (session('user')['employee_id'] ? session('user')['employee_id'] : 'Requestor 1'),
                "pr_sr" => $request->prsr,
                "deliver_schedule"=>$request->delivery,
                "supplier_quotation_id"=> $request->supplier_quotation_id,
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
          //  print_r($data );die;
            if(!empty( $data['response']['success'])){
                $request->session()->flash('success',  $data['response']['message']);
                return redirect('inventory/edit-supplier-quotation?qr_id='.$request->supplier_quotation_id);
             }
        }
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/supplier-quotation-master-add-edit-delete/';
        $Request['param'] = ['qr_id' => $request->supplier_quotation_id];
        $data = $this->HttpRequest->HttpClient($Request);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-add', compact('data'));

    }

    
    public function getSupplierQuotationEditItem(Request $request,$rq_no,$supp_id,$item_id)
    {
        if ($request->isMethod('post')) {
            $validation['quantity'] = ['required'];
            $validation['rate'] = ['required'];
            $validation['discount'] = ['required'];
           // $validation['Specification'] = ['required'];
            $validation['committed_delivery_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
             $data =  [
                "specification" =>  $request->Specification,
                "committed_delivery_date"=>date('Y-m-d',strtotime($request->committed_delivery_date)),
                "rate"  => $request->rate,
                "discount"=>$request->discount,
                "quantity" =>$request->quantity,
                "currency"  => $request->currency ,
                "gst"  => $request->gst ,
                "remarks" =>$request->Remarks,
            ];
            $request->session()->flash('success',"You have successfully updated a Supplier Quotation Item !");
            $this->inv_purchase_req_quotation_item_supp_rel->updatedata(['quotation_id'=>$rq_no,'item_id'=>$item_id,'supplier_id'=>$supp_id],$data);
            return redirect('inventory/view-supplier-quotation-items/'.$rq_no.'/'.$supp_id);
            }
            if($validator->errors()->all()){
                return redirect('inventory/edit-supplier-quotation-item/'.$rq_no.'/'.$supp_id.'/'.$item_id)->withErrors($validator)->withInput();
            }
        }
        $data['get_item_single'] = $this->inv_purchase_req_quotation_item_supp_rel->get_item_single(['inv_purchase_req_quotation_item_supp_rel.supplier_id'=>$supp_id,'inv_purchase_req_quotation_item_supp_rel.item_id'=>$item_id,'inv_purchase_req_quotation_item_supp_rel.quotation_id'=>$rq_no]);
        $data["currency"] = $this->currency_exchange_rate->get_currency([]);
        $data['gst'] = $this->inventory_gst->get_gst();
        return view('pages/purchase-details/supplier-quotation/supplier-quotation-edit-item',compact('data','rq_no','supp_id'));
    }

    public function supplierQuotationUpdate(Request $request, $rq_no,$supp_id)
    {

        if(!$rq_no || !$supp_id){
            return response()->view('errors/404', [], 404);
        }
        $validation['supplier_quotation_no'] = ['required'];
        //$validation['commited_delivery_date'] = ['required','date'];
         $validation['quotation_date'] = ['required','date'];
         //$validation['contact'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()){
            $data = ['supplier_quotation_num'=>$request->supplier_quotation_no,
                    //'commited_delivery_date'=>date('Y-m-d',strtotime($request->commited_delivery_date)),
                    'quotation_date'=>date('Y-m-d',strtotime($request->quotation_date)),
                    //'contact_number'=>$request->contact,
                    'freight_charge'=>$request->freight_charge];
            $this->inv_purchase_req_quotation_supplier->updatedata(['supplier_id'=>$supp_id,'quotation_id'=>$rq_no],$data);
            $request->session()->flash('success',"You have successfully updated a Supplier Quotation master !");
            return redirect('inventory/view-supplier-quotation-items/'.$rq_no.'/'.$supp_id);
        }
        if($validator->errors()->all()){
            return redirect('inventory/view-supplier-quotation-items/'.$rq_no.'/'.$supp_id)->withErrors($validator)->withInput();
        }
    }
    
    public function viewSupplierQuotationItems(Request $request,$rq_no,$supp_id)
    {
        if(!$rq_no || !$supp_id){
            return response()->view('errors/404', [], 404);
        }
        if($request->Supplier){
            $supp_id  = $request->Supplier;
        }
  
        $data["currency"] = $this->currency_exchange_rate->get_currency([]);
        $data['supplier'] = $this->inv_purchase_req_quotation_supplier->get_Item(['quotation_id'=>$request->rq_no]);
        $data['quotation']   = $this->inv_purchase_req_quotation->get_quotation_single(['quotation_id'=>$request->rq_no]);
        $data['supplier_single']= $this->inv_purchase_req_quotation_supplier->get_single_item(['inv_purchase_req_quotation_supplier.supplier_id'=>$supp_id,'inv_purchase_req_quotation_supplier.quotation_id'=>$rq_no]);
        $data['inv_purchase_req'] = $this->inv_purchase_req_quotation_item_supp_rel->get_Item(['inv_purchase_req_quotation_item_supp_rel.supplier_id'=>$supp_id,'inv_purchase_req_quotation_item_supp_rel.quotation_id'=>$rq_no]);
        return view('pages/purchase-details/supplier-quotation/supplier-quotation-items', compact('data','rq_no','supp_id'));
    }

    public function comparisonOfQuotation($rq_no) 
    {
        $rq_number = $this->inv_purchase_req_quotation->get_quotation_number(['quotation_id'=> $rq_no]);
        $suppliers = $this->inv_purchase_req_quotation_supplier->get_suppliers(['inv_purchase_req_quotation_supplier.quotation_id'=>$rq_no]);
        $item_details = $this->inv_purchase_req_quotation_item_supp_rel->get_quotation_items_details(['inv_purchase_req_quotation_item_supp_rel.quotation_id'=> $rq_no]);
       //print_r(json_encode($item_details));exit;
       foreach($item_details as $item){
        $fixed_item[] = $this->check_fixed_item($item['itemId'], $item['supplier_id']);
       }
        $fixedItem =array_values(array_filter($fixed_item));
        if(count($fixedItem)>0)
        {
            $items = $this->inv_purchase_req_quotation_item_supp_rel->get_quotation_items_without_fixed_item(['inv_purchase_req_quotation_item_supp_rel.quotation_id'=> $rq_no],$fixedItem);
            $items_info = $this->inv_purchase_req_quotation_item_supp_rel->get_quotation_items_details_without_fixed_item(['inv_purchase_req_quotation_item_supp_rel.quotation_id'=> $rq_no], $fixedItem);
            $supplier_data = $this->arrage_items($items, $items_info);
        }
        else
        {
            $items_info = $this->inv_purchase_req_quotation_item_supp_rel->get_quotation_items_details(['inv_purchase_req_quotation_item_supp_rel.quotation_id'=> $rq_no]);
            $items = $this->inv_purchase_req_quotation_item_supp_rel->get_quotation_items(['inv_purchase_req_quotation_item_supp_rel.quotation_id'=> $rq_no]);
            $supplier_data = $this->arrage_items($items, $items_info);
        }
        //print_r($supplier_data);exit;
        return view('pages/purchase-details/supplier-quotation/comparison-quotation',compact('suppliers', 'rq_number', 'supplier_data', 'rq_no'));
    }
    
    public function check_fixed_item($itemId, $supplier_id)
    {
        $fixed_item = inv_supplier_itemrate::where('item_id','=',$itemId)->where('supplier_id','=',$supplier_id)->pluck('item_id')->first();
        if($fixed_item)
        return $fixed_item;
        else
        return 0;
    }

    public function arrage_items($items,$item_details)
    {
        $newdata = [];
        $item1=[];
        $i=1;
        foreach($items as $item)
        {
            $newdata = [];
           
            foreach($item_details as $details)
            {
                if($item['itemid']==$details['itemid'])
                {
                    $newdata[] = [
                        'radio_name'=> 'radio'.$i,
                        'supplier_id'=>$details['supplier_id'],
                        'quantity' => $details['quantity'],
                        'rate' => $details['rate'],
                        'discount' => $details['discount'],
                        'itemId'=>$details['itemid'],
                        'remarks'=>$details['remarks'],
                        'selected_item'=>$details['selected_item'],
                        'total'=>$details['rate']*$details['quantity']-$details['rate']*$details['quantity']*$details['discount']/100
                    ];
                }
                $price_data['price_data'] = $newdata; 
            }
            $item1[] = array_merge($item, $price_data);
            $i++;
        }
        //print_r(json_encode($item1));exit;
        return $item1;

    }

    public function selectQuotationItems(Request $request)
    {
        $quotation_id = $request->quotation_id;
        $supplier = $request->supplier;
        $item_id = $request->item_id;
        $un_select = $this->inv_purchase_req_quotation_item_supp_rel->updatedata(['quotation_id'=>$quotation_id,'item_id'=>$item_id,'selected_item'=>1],['selected_item'=>0]);
        $select = $this->inv_purchase_req_quotation_item_supp_rel->updatedata(['quotation_id'=>$quotation_id,'item_id'=>$item_id,'supplier_id'=>$supplier],['selected_item'=>1]);
        if($un_select && $select) {
            return 1;
        }
        else
        {
           return 0;
        } 
    }

    public function selectQuotation(Request $request)
    {
        $quotation_id = $request->quotation_id;
        $supplier = $request->supplier;
        $un_select = $this->inv_purchase_req_quotation_supplier->updatedata(['quotation_id'=>$request->quotation_id,'selected_supplier'=>1],['selected_supplier'=>0]);
        $select = $this->inv_purchase_req_quotation_supplier->updatedata(['quotation_id'=>$request->quotation_id,'supplier_id'=>$request->supplier],['selected_supplier'=>1]);
        if($un_select && $select) {
            return 1;
        }
        else
        {
           return 0;
        } 
    }

    function checkSelectedQuotation($rq_no,$supplier)
    {
        $check = inv_purchase_req_quotation_supplier::where('quotation_id','=',$rq_no)
                                                    ->where('supplier_id','=',$supplier)
                                                    ->pluck('selected_supplier')
                                                    ->first();
          return $check;
    }
    function getRemarks($rq_no,$supplier)
    {
        $check = inv_purchase_req_quotation_item_supp_rel::where('quotation_id','=',$rq_no)
                                                    ->where('supplier_id','=',$supplier)
                                                    ->pluck('remarks')
                                                    ->first();
          return $check;
    }

    function get_rate($supplier_id,$item_id)
    {
        $now = date('Y-m-d');
        $data = inv_supplier_itemrate::select('*')
                                    ->where('supplier_id','=',$supplier_id)
                                    ->where('item_id','=',$item_id)
                                    ->first();
        if($data){
            if($data['rate_expiry_startdate']<=$now && $data['rate_expiry_enddate']>=$now)
            return $data['rate'];
            else 
            return 0;
        }
        else
        return 0;

    }

    public function checkFixedItem($requisition_item_id,$quotation_id)
    {
        
        $row_material_id = inv_purchase_req_item::where('requisition_item_id','=', $requisition_item_id)->pluck('Item_code')->first();
        $suppliers = inv_purchase_req_quotation_item_supp_rel::select('inv_purchase_req_quotation_item_supp_rel.supplier_id')
                                                         ->where('inv_purchase_req_quotation_item_supp_rel.item_id','=',$requisition_item_id)
                                                         ->where('inv_purchase_req_quotation_item_supp_rel.quotation_id','=',$quotation_id)
                                                         ->get();
        foreach($suppliers as $supplier)
        {
            $fixed_item[] = inv_supplier_itemrate::where('supplier_id','=',$supplier['supplier_id'])
                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_supplier_itemrate.item_id')
                                    ->where('inv_supplier_itemrate.item_id','=',$row_material_id)
                                    ->select('inventory_rawmaterial.item_code','inv_supplier_itemrate.supplier_id')
                                    //->pluck('inventory_rawmaterial.item_code')
                                    ->first();
        }
        return $fixed_item;
    }    
    
    public function get_Fixeditem($fixed_item)
    {  
        $Trimed= rtrim(ltrim($fixed_item,"{"),"}");
        $Trimed_array=explode(",",$Trimed); 
        $item = explode(':',$Trimed_array[0]);
        return rtrim(ltrim($item[1],'"'),'"');
    }
    public function get_fixed_supplier($fixed_item)
    {
        $Trimed= rtrim(ltrim($fixed_item,"{"),"}");
        $Trimed_array=explode(",",$Trimed); 
        $supplier = explode(':',$Trimed_array[1]);
        return $supplier[1];
    }
    public function getFixedRateItems($quotation_id, $requisition_item_id){
        $items = inv_purchase_req_quotation_item_supp_rel::select('inv_purchase_req_quotation_item_supp_rel.supplier_id','inventory_rawmaterial.id as row_material_id')
                                                                    ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_quotation_item_supp_rel.item_id')
                                                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                                                                    ->where('quotation_id','=',$quotation_id)
                                                                    ->get();
        foreach($items as $item){
            $fixed_item[] = inv_supplier_itemrate::where('supplier_id','=',$item['supplier_id'])
                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_supplier_itemrate.item_id')
                                    ->where('inv_supplier_itemrate.item_id','=',$item['row_material_id'])
                                    //->select('inventory_rawmaterial.item_code')
                                    ->whereDate('rate_expiry_startdate', '<=', date("Y-m-d"))
                                    ->whereDate('rate_expiry_enddate', '>=', date("Y-m-d"))
                                    ->pluck('inventory_rawmaterial.id')
                                    ->first();
        }
        $row_material_id = inv_purchase_req_item::where('requisition_item_id','=', $requisition_item_id)->pluck('Item_code')->first();
        if (in_array($row_material_id,array_values(array_filter($fixed_item))))
        return 1;
        else
        return 0;
       // return array_values(array_filter($fixed_item));
        
    }

    public function getFixedItemSupplier($quotation_id, $requisition_item_id,$supplier_id){
        $items = inv_purchase_req_quotation_item_supp_rel::select('inv_purchase_req_quotation_item_supp_rel.supplier_id','inventory_rawmaterial.id as row_material_id')
                                                                    ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_quotation_item_supp_rel.item_id')
                                                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                                                                    ->where('quotation_id','=',$quotation_id)
                                                                    ->get();
        foreach($items as $item){
            $fixed_item[] = inv_supplier_itemrate::where('supplier_id','=',$item['supplier_id'])
                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_supplier_itemrate.item_id')
                                    ->where('inv_supplier_itemrate.item_id','=',$item['row_material_id'])
                                    //->select('inventory_rawmaterial.item_code')
                                    ->whereDate('rate_expiry_startdate', '<=', date("Y-m-d"))
                                    ->whereDate('rate_expiry_enddate', '>=', date("Y-m-d"))
                                    ->pluck('inv_supplier_itemrate.supplier_id')
                                    ->first();
        }
        //$row_material_id = inv_purchase_req_item::where('requisition_item_id','=', $requisition_item_id)->pluck('Item_code')->first();
        if (in_array($supplier_id,array_values(array_filter($fixed_item))))
        return 1;
        else
        return 0;
       // return array_values(array_filter($fixed_item));
        
    }



}

