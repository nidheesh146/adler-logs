<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseDetails\inv_purchase_req_quotation;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_supplier;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_item_supp_rel;

use Validator;

class SupplierQuotationController extends Controller
{
    public function __construct()
    {
        $this->inv_purchase_req_quotation = new inv_purchase_req_quotation;
        $this->inv_supplier = new inv_supplier;
        $this->inv_purchase_req_quotation_supplier = new inv_purchase_req_quotation_supplier;
        $this->inv_purchase_req_quotation_item_supp_rel = new inv_purchase_req_quotation_item_supp_rel;
   
    }

    public function getSupplierQuotation(Request $request) 
    {
        if(count($_GET))
        {
            if ($request->rq_no) {
                $condition[] = ['inv_purchase_req_quotation.rq_no', 'like', '%'.$request->rq_no.'%'];
            }
            // if ($request->supplier) {
            //     $condition[] = ['inv_purchase_req_quotation_supplier.supplier_id', '=', $request->supplier];
            // }
            if ($request->from) {
                $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
           
           // $data['po_data'] =  $this->inv_final_purchase_order_master->get_purchase_master($condition);
            $data['quotation'] = $this->inv_purchase_req_quotation->get_quotation($condition);
        }
        else 
        {
            $data['quotation'] = $this->inv_purchase_req_quotation->get_quotation([]);
        }
        $data['suppliers'] = $this->inv_supplier->get_all_suppliers();
        $data['rq_nos'] = $this->inv_purchase_req_quotation->get_rq_nos();
        //$data['quotation'] = $this->inv_purchase_req_quotation->get_quotation([]);
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
            $suppliers .="<span>".$supplier->vendor_id."</span>" ;
            if(  $key !=  ( $count - 1)){
                $suppliers .= " , ";
            }   
        }

      }
        return ["supplier" => $suppliers,'supplier_id' => $supplier_id];
    }
    public function getSupplierQuotationAdd(Request $request)
    {

     
        if ($request->isMethod('post')) {
            $Request['Method'] = 'POST';
            $Request['URL'] = config('app.ApiURL') . "/inventory/purchase-requisition-item-list-add-edit-delete/";
            $Request['param'] = json_encode([
                "action_type"=>"AddSupplierQuotationMaster",
                'quotation_no'=>"",
                "rq_no"=>$request->rq_no,
                "supplier"  => $request->supplier,
                "date"=>$request->date,
                "requestor" => (session('user')['employee_id'] ? session('user')['employee_id'] : 'Requestor 1'),
                "pr_sr" => $request->prsr,
                "deliver_schedule"=>$request->delivery
                
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
            if(!empty($data['response']['message'] && $data['response']['success']))
            {
                $request->session()->flash('success',  $data['response']['message']);
                return redirect('inventory/supplier-quotation');
             }
        }
        return view('pages/supplier-quotation/supplier-quotation-add');
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
            $validation['Specification'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
             $data =  [
                "specification" =>  $request->Specification,
                "rate"  => $request->rate,
                "discount"=>$request->discount,
                "quantity" =>$request->quantity
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
        return view('pages/purchase-details/supplier-quotation/supplier-quotation-edit-item',compact('data','rq_no','supp_id'));
    }

    public function supplierQuotationUpdate(Request $request, $rq_no,$supp_id)
    {

        if(!$rq_no || !$supp_id){
            return response()->view('errors/404', [], 404);
        }
        $validation['supplier_quotation_no'] = ['required'];
        $validation['commited_delivery_date'] = ['required','date'];
         $validation['quotation_date'] = ['required','date'];
         $validation['contact'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()){
            $data = ['supplier_quotation_num'=>$request->supplier_quotation_no,'commited_delivery_date'=>date('Y-m-d',strtotime($request->commited_delivery_date)),
            'quotation_date'=>date('Y-m-d',strtotime($request->quotation_date)),'contact_number'=>$request->contact];
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
        $items = $this->inv_purchase_req_quotation_item_supp_rel->get_quotation_items(['inv_purchase_req_quotation_item_supp_rel.quotation_id'=> $rq_no]);
        $item_details = $this->inv_purchase_req_quotation_item_supp_rel->get_quotation_items_details(['inv_purchase_req_quotation_item_supp_rel.quotation_id'=> $rq_no]);
        $supplier_data = $this->arrage_items($items, $item_details);
        return view('pages/purchase-details/supplier-quotation/comparison-quotation',compact('suppliers', 'rq_number', 'supplier_data', 'rq_no'));
    }

    public function arrage_items($items,$item_details)
    {
        $newdata = [];
        foreach($items as $item)
        {
            $newdata = [];
            foreach($item_details as $details)
            {
                if($item['itemId']==$details['itemId'])
                {
                    $newdata[] = [
                        'quantity' => $details['quantity'],
                        'rate' => $details['rate'],
                        'discount' => $details['discount'],
                        'total'=>$details['rate']*$details['quantity']-$details['rate']*$details['quantity']*$details['discount']/100
                    ];
                }
                $price_data['price_data'] = $newdata; 
            }
            $item1[] = array_merge($item, $price_data);
        }
        return $item1;

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


}

