<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;

class SupplierQuotationController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
    }

    public function getSupplierQuotation() 
    {
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/supplier-quotation-new-add-edit-delete/';
        $data = $this->HttpRequest->HttpClient($Request);
        //  print_r(json_encode($data['response']['supplier_quotation']));
        //  exit;
        $items = $data['response']['supplier_quotation'];
        return view('pages/supplier-quotation/supplier-quotation', compact('items'));
    }

    function quotationsearch($rq_no = null){
        if(!$rq_no){
            return response()->json(['message'=>'RQ No is not valid'], 500); 
        }
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/quotation-item-list-add-edit-delete/';
        $Request['param'] = ['rq_no' => $rq_no];
        $data = $this->HttpRequest->HttpClient($Request);
        if(!empty($data['response']['quotation'][0])){
            return response()->json($data['response']['quotation'], 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }

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
    
    public function getSupplierQuotationEditItem($supplierquotationid)
    {
        return view('pages/supplier-quotation/supplier-quotation-edit-item');
    }
    
    public function viewSupplierQuotationItems($supplierquotationid) {
        return view('pages/supplier-quotation/supplier-quotation-items');
    }

    public function comparisonOfQuotation($supplierquotationid) {
        return view('pages/supplier-quotation/comparison-quotation');
    }
}

