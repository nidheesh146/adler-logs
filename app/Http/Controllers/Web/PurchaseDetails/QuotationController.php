<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\PurchaseDetails\inv_purchase_req_quotation;


class QuotationController extends Controller
{
    public function __construct()
    {
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        $this->inv_purchase_req_quotation = new inv_purchase_req_quotation;
    }

    // list Quotation
    public function getQuotation(Request $request)
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
        $data['getdata'] = $this->inv_purchase_req_item->getdata($condition);
        return view('pages/purchase-details/Quotation/quotation-add', compact('data'));
    }

    // Add Quotation
    public function postQuotation(Request $request)
    {
        $validation['date'] = ['required','date'];
        $validation['Supplier'] = ['required'];
        $validation['delivery'] = ['required'];
        $validation['purchase_requisition_item'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
       
        if(!$validator->errors()->all()){
            $data = ['date'=>date('Y-m-d',strtotime($request->date)),
                     'delivery_schedule'=>date('Y-m-d',strtotime($request->delivery)),
                     'rq_no'=>'RQ-'.$this->num_gen( $this->inv_purchase_req_quotation->get_count()),
                     'created_at'=>date('Y-m-d H:i:s'),
                     'created_user'=>config('user')['user_id']];
            $this->inv_purchase_req_quotation->insert_data($data,$request);
            $request->session()->flash('success', "You have successfully created a  Request For Quotation !");
            return redirect('inventory/quotation');

        }
        if($validator->errors()->all()){
            return redirect('inventory/quotation')->withErrors($validator)->withInput();
        }
           
    }

    public function getItems(Request $request)
    {
        //echo "jj";exit;
        $data['getdata'] = $this->inv_purchase_req_item->getdata(['inv_item_type.type_name'=>$request->type]);
        return view('pages/purchase-details/Quotation/quotation-add', compact('data'));

    }

    // Edit Quotation
    public function editQuotation(Request $request)
    {
        $validation['rq_no '] = ['required'];
        $validation['date '] = ['required','date'];
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
        $validation['quotation'] = ['required'];
        $validation['item_code'] = ['required','date'];
        $validation['unit'] = ['required'];
        $validation['required_qty  '] = ['required'];
        $validation['description  '] = ['required'];
        $validation['rate'] = ['required'];
        $validation['currency  '] = ['required'];
        $validation['moq'] = ['required'];
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
        $validation['item_code '] = ['required','date'];
        $validation['unit'] = ['required'];
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
