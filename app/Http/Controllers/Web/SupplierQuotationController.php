<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class SupplierQuotationController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
    }

    public function getSupplierQuotation(Request $request) 
    {
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/quotation-new-add-edit-delete/';
        $Request['param'] = ["no_of_entries"=>10,'page'=>$request->page ? $request->page  : 1];
        $data = $this->HttpRequest->HttpClient($Request);
         // print_r(json_encode($data['response']['quotation']));
          //exit;
        //$items = $data['response']['quotation'];
        return view('pages/supplier-quotation/supplier-quotation', compact('data'));
    }

    // function quotationsearch($rq_no = null){
    //     if(!$rq_no){
    //         return response()->json(['message'=>'RQ No is not valid'], 500); 
    //     }
    //     $Request['Method'] = 'GET';
    //     $Request['URL'] = config('app.ApiURL') . '/inventory/quotation-item-list-add-edit-delete/';
    //     $Request['param'] = ['rq_no' => $rq_no];
    //     $data = $this->HttpRequest->HttpClient($Request);
    //     if(!empty($data['response']['quotation'][0])){
    //         return response()->json($data['response']['quotation'], 200); 
    //     }else{
    //         return response()->json(['message'=>'item code is not valid'], 500); 
    //     }

    // }
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
            $Request['Method'] = 'POST';
            $Request['URL'] = config('app.ApiURL') . "/inventory/supplier-quotation-new-add-edit-delete/";    
            $Request['param'] = json_encode([
                "action_type" => "EditSupplierQuotationNew",
                "quotation_id" => $request->supplier_quotation,
                "specifications" =>  $request->Specification,
                "supplier_rate"  => $request->rate,
                "supplier_discount"=>$request->discount,
                "quantity" =>$request->quantity
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
            if(!empty($data['response']['success'])){
                $request->session()->flash('success',  $data['response']['message']);
            }else{
                $request->session()->flash('error',  $data['error']);
                return redirect('inventory/edit-supplier-quotation-item/'.$rq_no.'/'.$supp_id.'/'.$item_id.'?name='.$request->name);
            }
            return redirect('inventory/view-supplier-quotation-items/'.$rq_no.'/'.$supp_id);
        }
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . "/inventory/supplier-quotation-new-add-edit-delete/";
        $Request['param'] = ['supplier' => $supp_id,'quotation'=>$rq_no,"no_of_entries"=>25,'page'=>$request->page ? $request->page  : 1];
        $data = $this->HttpRequest->HttpClient($Request);
        if(!empty($data['response']['supplier_quotation'][0])){
            $supplier_quotation = [];
            foreach($data['response']['supplier_quotation'] as $key => $value){
                if($value['id'] == $item_id){
                    $supplier_quotation[] = $value;
                }
            unset($data['response']['supplier_quotation'][$key]);
            }
            $data['response']['supplier_quotation']=$supplier_quotation; 
        }
        return view('pages/supplier-quotation/supplier-quotation-edit-item',compact('data'));
    }

    public function supplierQuotationUpdate(Request $request, $rq_no,$supp_id)
    {
        if ($request->isMethod('post')) {
            $Request['Method'] = 'POST';
            $Request['URL'] = config('app.ApiURL') . "/inventory/supplier-quotation-new-add-edit-delete/";    
            $Request['param'] = json_encode([
                "action_type" => "EditSupplierQuotationNew",
                "quotation_id"=>$request->quotation_id,
                "supplier_quotation_no" => $request->supplier_quotation_no,
                "quotation_date" =>  date('d-m-Y', strtotime($request->quotation_date)),
                "contact"  => $request->contact,
                "deliver_date"=> date('d-m-Y', strtotime($request->commited_delivery_date))
            ]);
            //print_r($Request);exit;
            $data = $this->HttpRequest->HttpClient($Request);
            if(!empty($data['response']['success'])){
                $request->session()->flash('success',  $data['response']['message']);
            }else{
                $request->session()->flash('error',  $data['error']);
                return redirect('inventory/view-supplier-quotation-items/'.$rq_no.'/'.$supp_id);
            }
            return redirect('inventory/view-supplier-quotation-items/'.$rq_no.'/'.$supp_id);
        } 

    }
    
    public function viewSupplierQuotationItems(Request $request,$rq_no,$supp_id)
    {
        $Res['error'] = "";
        $Res['response'] = [];
        $supp_id = $request->supplier ? $request->supplier : $supp_id;
    try {
        $response = Http::pool(fn (Pool $pool) => [
            $pool->withHeaders([
                'Authorization' => 'Token ' . session('user')['token'],
            ])->get(config('app.ApiURL') . '/inventory/supplier-quotation-new-add-edit-delete/',['supplier' => $supp_id,'quotation'=>$rq_no,"no_of_entries"=>25,'page'=>$request->page ? $request->page  : 1]),
            $pool->withHeaders([
                'Authorization' => 'Token ' . session('user')['token'],
            ])->get(config('app.ApiURL') . '/inventory/quotation-new-add-edit-delete/',['rq_no' => $rq_no]),
        ]);
        if ($response[0]->status() == 200 && $response[1]->status() == 200){
            if ($response[0]->json()['status'] == 'success' && $response[1]->json()['status'] == 'success') {
               //print_r(json_encode($response[0]->json()));exit;
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
       return view('pages/supplier-quotation/supplier-quotation-items', compact('Res','rq_no','supp_id'));
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

