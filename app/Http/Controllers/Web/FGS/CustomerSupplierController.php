<?php

namespace App\Http\Controllers\Web\FGS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\currency_exchange_rate;
use App\Models\state;
use App\Models\PurchaseDetails\customer_supplier;
use Validator;
class CustomerSupplierController extends Controller
{
    public function __construct()
    {
        $this->currency_exchange_rate = new currency_exchange_rate;
        $this->state = new state;
        $this->customer_supplier = new customer_supplier;
    }
    public function customerSupplierList()
    {
        $customers = $this->customer_supplier->get_all([]);
        return view('pages/FGS/customer-supplier/customer-list',compact('customers'));
    }

    public function addCustomerSupplier(Request $request,$id=null)
    {
        $datas = []; 
        if ($request->isMethod('post'))
        {
            $validation['firm_name'] = ['required'];
            $validation['contact_person'] = ['required'];
            $validation['contact_person'] = ['required'];
            $validation['contact_number'] = ['required'];
            $validation['billing_address'] = ['required'];
            $validation['shipping_address'] = ['required'];
            $validation['sales_type'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 
                $data['firm_name'] = $request->firm_name;
                $data['contact_person'] = $request->contact_person;
                $data['designation'] = $request->designation;
                $data['contact_number'] = $request->contact_number;
                $data['email'] = $request->email;
                $data['billing_address'] = $request->billing_address;
                $data['shipping_address'] = $request->shipping_address;
                $data['city'] = $request->city;
                $data['state'] = $request->state;
                $data['zone'] = $request->zone;
                $data['pan_number'] = $request->pan_number;
                $data['gst_number'] = $request->gst_number;
                $data['dl_number1'] = $request->dl_number1;
                $data['dl_number2'] = $request->dl_number2;
                $data['dl_number3'] = $request->dl_number3;
                $data['currency'] = $request->currency;
                $data['payment_terms'] = $request->payment_terms;
                $data['sales_type'] = $request->sales_type;
                if($id){
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $this->customer_supplier->update_data(['id'=>$id],$data);
                    $request->session()->flash('success',"You have successfully updated a customer !");
                    return redirect("fgs/customer-supplier/add/".$id);
                }else{
                    $data['created_by'] = config('user')['user_id'];
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $this->customer_supplier->insert_data($data);
                    $request->session()->flash('success',"You have successfully added a customer !");
                    return redirect("fgs/customer-supplier");
                }
            }
            if($validator->errors()->all()) 
            { 
                if($id)
                return redirect("fgs/customer-supplier/add/".$id)->withErrors($validator)->withInput();
                else
                return redirect("fgs/customer-supplier/add")->withErrors($validator)->withInput();
            }
        }
        if($id){
            $datas['customer'] = $this->customer_supplier->get_customer(['id'=>$id]);
        }
        $currency =  $this->currency_exchange_rate->get_currency([]);
        $states = $this->state->get_states([]);
        return view('pages/FGS/customer-supplier/customer-add',compact('datas','id','currency','states'));
    }
}
