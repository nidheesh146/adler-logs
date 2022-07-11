<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Support\Facades\Http;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
    }

    function suppliersearch(Request $request){

        if(!$request->q){
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
    
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/supplier-add-edit-delete/';
        $Request['param'] = ['supplier' => $request->q];
        $data = $this->HttpRequest->HttpClient($Request);
        if(!empty($data['response']['suppliers'][0])){
            foreach($data['response']['suppliers']  as $itemcode){
                $string[] = ['id'=>$itemcode['id'],'text'=>$itemcode['vendor_name']];
            }
            return response()->json($string, 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
    }

    // list Quotation
    public function getQuotation()
    {
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL').'/inventory/purchase-requisition-approval-list-add-edit-delete/?status=1';
        $data = $this->HttpRequest->HttpClient($Request); 
        return view('pages/Quotation/quotation-add', compact('data'));
    }

    // Add Quotation
    public function postQuotation(Request $request)
    {
        $validation['rq_no '] = ['required'];
        $validation['date '] = ['required'|'date'];
        $validation['requestor  '] = ['required'];
        $validation['supplier  '] = ['required'];
        $validation['deliver_schedule  '] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()) 
        { 
            $Request['Method'] = 'POST';
            $Request['URL'] = config('app.ApiURL').'/inventory/quotation-master-list-add-edit-delete';
            
            $Request['param'] = json_encode([
                'action_type '=>'AddQuotationMaster',
                'rq_no' => '11',//$request->rq_no,
                'date' => '08-07-2022',//$request->date,
                'requestor' =>3, //$request->requestor,
                'supplier' => 1,//$request->supplier,
                'deliver_schedule' => '10-07-2022'//$request->deliver_schedule,
            ]);
            $data = $this->HttpRequest->HttpClient($Request);
            if(!empty($data['response']['success'])){
                $request->session()->flash('success',  $data['response']['message']);
                return redirect('inventory/quotation');
             }
             else 
             {
                $request->session()->flash('error',  $data['error']);
                return redirect('inventory/quotation');
             }
            
        }

    }

    // Edit Quotation
    public function editQuotation(Request $request)
    {
        $validation['rq_no '] = ['required'];
        $validation['date '] = ['required'|'date'];
        $validation['requestor  '] = ['required'];
        $validation['supplier  '] = ['required'];
        $validation['deliver_schedule  '] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()) 
        { 
            $response = Http::post(config('app.ApiURL') . '/inventory/quotation-master-list-add-edit-delete
            ', [
                'action_type '=>'EditQuotationMaster',
                'rq_no' => '11',//$request->rq_no,
                'date' => '08-07-2022',//$request->date,
                'requestor' =>3, //$request->requestor,
                'supplier' => 1,//$request->supplier,
                'deliver_schedule' => '10-07-2022',//$request->deliver_schedule,
                'quotation_id'=>8
            ]);

            if ($response->status() == 200) {
                if (!empty($response->json()['success'])) {

                    //return redirect("inventory/get-purchase-reqisition");
                } else {
                    $error =  $response->json()['message'];
                }
            } else {
                $error =  " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
            }
        }
    }

    // Delete Quotation
    public function deleteQuotation(Request $request)
    {
        $response = Http::post(config('app.ApiURL') . '/inventory/quotation-master-list-add-edit-delete
        ', [
            'action_type '=>'DeleteQuotationMaster',
            'quotation_id'=>9
        ]);

        if ($response->status() == 200) {
            if (!empty($response->json()['success'])) {

                //return redirect("inventory/get-purchase-reqisition");
            } else {
                $error =  $response->json()['message'];
            }
        } else {
            $error =  " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
        }
    }

    // Search Quotation

    // Add Quotation item
    public function AddQuotationItem(Request $request)
    {
        $validation['quotation '] = ['required'];
        $validation['item_code '] = ['required'|'date'];
        $validation['unit  '] = ['required'];
        $validation['required_qty  '] = ['required'];
        $validation['description  '] = ['required'];
        $validation['rate  '] = ['required'];
        $validation['currency  '] = ['required'];
        $validation['moq  '] = ['required'];
        $validation['exstock_availability  '] = ['required'];
        $validation['deliver_schedule  '] = ['required'];

        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()) 
        { 
            $response = Http::post(config('app.ApiURL').'/inventory/quotation-item-list-add-edit-delete/
            ', [
                'action_type '=>'AddQuotationItem',
                'quotation ' => 8,
                'item_code ' => '3',
                'unit ' =>3, 
                'required_qty' => 10,
                'description '=>'nice product',
                'rate '=> '4star',
                'currency '=> 'INR',
                'moq '=>'moqqq',
                'exstock_availability '=> '10',
                'deliver_schedule' => '10-07-2022',
            ]);

            if ($response->status() == 200) {
                if (!empty($response->json()['success'])) {

                    //return redirect("inventory/get-purchase-reqisition");
                } else {
                    $error =  $response->json()['message'];
                }
            } else {
                $error =  " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
            }
        }
    }

    // edit Quotation item
    public function editQuotationItem(Request $request)
    {
        $validation['quotation '] = ['required'];
        $validation['item_code '] = ['required'|'date'];
        $validation['unit  '] = ['required'];
        $validation['required_qty  '] = ['required'];
        $validation['description  '] = ['required'];
        $validation['rate  '] = ['required'];
        $validation['currency  '] = ['required'];
        $validation['moq  '] = ['required'];
        $validation['exstock_availability  '] = ['required'];
        $validation['deliver_schedule  '] = ['required'];

        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()) 
        { 
            $response = Http::post(config('app.ApiURL') . '/inventory/inventory/quotation-item-list-add-edit-delete/

            ', [
                'action_type '=>'EditQuotationItem',
                'quotation ' => 8,
                'item_code ' => '3',
                'unit ' =>3, 
                'required_qty' => 10,
                'description '=>'nice product',
                'rate '=> '4star',
                'currency '=> 'INR',
                'moq '=>'moqqq',
                'exstock_availability '=> '10',
                'deliver_schedule' => '10-07-2022',
                'quotation_id' => 11,
            ]);

            if ($response->status() == 200) {
                if (!empty($response->json()['success'])) {

                    //return redirect("inventory/get-purchase-reqisition");
                } else {
                    $error =  $response->json()['message'];
                }
            } else {
                $error =  " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
            }
        }
    }

        // Delete Quotation item
    public function deleteQuotationItem(Request $request)
    {
        $response = Http::post(config('app.ApiURL') . '/inventory/quotation-item-list-add-edit-delete/

        ', [
            'action_type '=>'DeleteQuotationItem',
            'quotation_id'=>9
        ]);

        if ($response->status() == 200) {
            if (!empty($response->json()['success'])) {

                //return redirect("inventory/get-purchase-reqisition");
            } else {
                $error =  $response->json()['message'];
            }
        } else {
            $error =  " Networking Error: Server is not responding. Please contact System Administrator for assistance.";
        }
    }
    


}
