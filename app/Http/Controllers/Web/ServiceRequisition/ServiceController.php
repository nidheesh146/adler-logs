<?php

namespace App\Http\Controllers\Web\ServiceRequisition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Web\WebapiController;
use App\Models\Department;
use App\Models\PurchaseDetails\inv_purchase_req_master;
use App\Models\inventory_gst;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\currency_exchange_rate;
use App\Models\PurchaseDetails\inv_purchase_req_item;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
        $this->Department = new Department;
        $this->inv_purchase_req_master = new inv_purchase_req_master;
        $this->inventory_gst = new inventory_gst;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->inv_supplier = new inv_supplier;
        $this->currency_exchange_rate = new currency_exchange_rate;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        

    }

    public function get_service_reqisition(Request $request)
    {
        if(count($_GET))
        {
            if ($request->department) {
                $condition[] = ['department.dept_name', 'like', '%'.$request->department.'%'];
            }
            if ($request->sr_no) {
                $condition[] = ['inv_purchase_req_master.pr_no',  'like', '%'.$request->sr_no.'%'];
            }
            if ($request->pr_sr) {
                $condition[] = ['inv_purchase_req_master.PR_SR', '=', $request->pr_sr];
            }
            if ($request->from) {
                $condition[] = ['inv_purchase_req_master.date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_purchase_req_master.date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
           
           // $data['po_data'] =  $this->inv_final_purchase_order_master->get_purchase_master($condition);
           $data['master']=$this->inv_purchase_req_master->get_inv_service_req_master_list($condition);
        }
        else 
        {
            $data['master']=$this->inv_purchase_req_master->get_inv_service_req_master_list($condition=null);
        }
    
        // dd($data['pr_nos']);exit;
       // $data['master']=$this->inv_purchase_req_master->get_inv_purchase_req_master_list();
        return view('pages/service-requisition/service-requisition-list', compact('data'));
    }

    // Service Reqisition item get list
    public function get_service_reqisition_item(Request $request)
    {
        if(!$request->sr_id){
            return response()->view('errors/404', [], 404);
        }
        $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->sr_id]);
        $data['item'] = $this->inv_purchase_req_item->getdata(['inv_purchase_req_master_item_rel.master'=>$request->sr_id]);
        return view('pages/service-requisition/service-requisition-item-list', compact('data'));

    }

    
         // service Reqisition item get list
      public function add_service_reqisition_item(Request $request)
      {
        if(!$request->sr_id){
            return response()->view('errors/404', [], 404);
        }

        if ($request->isMethod('post')) {

            $validation['pr_id'] = ['required'];
            $validation['Itemcode'] = ['required'];
            $validation['Supplier'] = ['required'];
            $validation['Currency'] = ['required'];
            $validation['Rate'] = ['required'];
            $validation['BasicValue'] = ['required'];
            $validation['Discount'] = ['required'];
            $validation['GST'] = ['required'];
            $validation['Netvalue'] = ['required'];
            $validation['Remarks'] = ['required'];
            $validation['ActualorderQty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
                $Request = [
                    "item_code" => $request->Itemcode,
                    "supplier"  => $request->Supplier,
                    "actual_order_qty"=> $request->ActualorderQty,
                    "basic_value"=> $request->BasicValue,
                    "rate"=> $request->Rate,
                    "discount_percent"=> $request->Discount,
                    "gst"=> $request->GST,
                    "currency"  => $request->Currency ,
                    "net_value"=>  $request->Netvalue,
                    "remarks"=> $request->Remarks,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                    "created_user" =>  config('user')['user_id']   
                ];

                $this->inv_purchase_req_item->insert_data($Request,$request->sr_id);
                $request->session()->flash('success',"You have successfully added a purchase requisition item !");
                return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->sr_id);

            }
            if($validator->errors()->all()){
                    return redirect("inventory/add-purchase-reqisition-item?pr_id=".$request->sr_id)->withErrors($validator)->withInput();
            }
        }

        $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->sr_id]);
        $data["currency"] = $this->currency_exchange_rate->get_currency([]);
        $data['gst'] = $this->inventory_gst->get_gst();
        return view('pages/service-requisition/service-requisition-item-add', compact('data'));
    }
    
}
