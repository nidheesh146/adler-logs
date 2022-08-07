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
        $data['quotation'] = $this->inv_purchase_req_quotation->get_quotation([]);
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
            return redirect('inventory/edit-supplier-quotation-item/'.$rq_no.'/'.$supp_id.'/'.$item_id);
            }
            if($validator->errors()->all()){
                return redirect('inventory/edit-supplier-quotation-item/'.$rq_no.'/'.$supp_id.'/'.$item_id)->withErrors($validator)->withInput();

            }
        }
        $data['get_item_single'] = $this->inv_purchase_req_quotation_item_supp_rel->get_item_single(['inv_purchase_req_quotation_item_supp_rel.supplier_id'=>$supp_id,'inv_purchase_req_quotation_item_supp_rel.item_id'=>$item_id,'inv_purchase_req_quotation_item_supp_rel.quotation_id'=>$rq_no]);
        return view('pages/purchase-details/supplier-quotation/supplier-quotation-edit-item',compact('data'));
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

    public function comparisonOfQuotation($rq_no) {
        // $Request['Method'] = 'GET';
        // $Request['URL'] = config('app.ApiURL') . "/inventory/supplier-quotation-new-add-edit-delete/";
        // $Request['param'] = ['quotation' => $rq_no];
        // $data = $this->HttpRequest->HttpClient($Request);
        $Res['error'] = "";
        $Res['response'] = [];
        try {
            $response = Http::pool(fn (Pool $pool) => [
                $pool->withHeaders([
                    'Authorization' => 'Token ' . session('user')['token'],
                ])->get(config('app.ApiURL') . '/inventory/supplier-quotation-new-add-edit-delete/',['quotation'=>$rq_no]),
                $pool->withHeaders([
                    'Authorization' => 'Token ' . session('user')['token'],
                ])->get(config('app.ApiURL') . '/inventory/quotation-new-add-edit-delete/',['rq_no' => $rq_no]),
            ]);
            if ($response[0]->status() == 200 && $response[1]->status() == 200){
                if ($response[0]->json()['status'] == 'success' && $response[1]->json()['status'] == 'success') {
                    $Res['response'] =['response0'=>$response[0]->json(),'response1'=>$response[1]->json()];
                    
                }else{
                    $Res['error'] = " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
                }
            }else{
                $Res['error'] = " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
            }
        }catch (\Exception $e) {
            $Res['error'] = " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
        }
            foreach($Res['response']['response0']['supplier_quotation'] as $item)
            {   
                $supplier = $item['supplier']['vendor_id'];
                $newdata =  [
                            'item_id' => $item['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['id'],
                            'item_name' => $item['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['item_name'],
                            'item_code' => $item['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['item_code'],
                             'hsn'=>$item['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['hsn_code'],
                        ];
                $supplier_price = [
                            'supplier_rate'=>$item['supplier_rate'],
                            'quantity'=>$item['quantity'],
                            'total'=>$item['supplier_rate']*$item['quantity']
                    ];
                $supplier_Itemprice []= $supplier_price;
                $supplier_item[] = $newdata;
            }
            $supplier_count = count($Res['response']['response1']['quotation'][0]['supplier']);
            $supplier_values = $this->arrange_Itemprice_list($supplier_item, $supplier_Itemprice, $supplier_count);

        return view('pages/supplier-quotation/comparison-quotation',compact('rq_no', 'Res', 'supplier_values'));
    }

    public function arrange_Itemprice_list($supplier_item, $supplier_Itemprice, $supplier_count)
    {
        $size = ceil(count($supplier_item)/$supplier_count);
        $array1 = array_chunk($supplier_item, $size);
        $item_by_supplier = $array1[0];
        $item_count = count($item_by_supplier);
        $array = array_chunk($supplier_Itemprice, $item_count);
        $length = sizeof($array[0]);
        //print_r(json_encode($array));exit;
        foreach($array as $ar)
        {
            $total =0;
            foreach($ar as $a){
                $total = $total+$a['total'];
            }
            $grant_total_supplier[] = $total;
        }
        //print_r(json_encode($grant_total));exit;
        for($i=0;$i<$item_count;$i++)
        {
             foreach($array as $arr) 
            {
            $supplier_items[] = $arr[$i];
            }
        }
    
        $supplier_item_prices= array_chunk($supplier_items,$supplier_count );
        $i=0;
        foreach($item_by_supplier as $item)
        {
            $data = [
                    'price_data' =>array_reverse( $supplier_item_prices[$i])
                ];
            $final[] = array_merge($item, $data);
            $i++;

        }
        $return_values = ['supplier_items' => $final,
                            'grant_total_supplier'=> array_reverse($grant_total_supplier)];
        return $return_values;
    }


}

