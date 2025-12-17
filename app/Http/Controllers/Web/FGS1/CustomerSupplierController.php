<?php

namespace App\Http\Controllers\Web\FGS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\currency_exchange_rate;
use App\Models\state;
use App\Models\zone;
use App\Models\PurchaseDetails\customer_supplier;
use Validator;
use App\Exports\CustomerSupplierExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerSupplierController extends Controller
{  
    public function __construct()
    {
        $this->currency_exchange_rate = new currency_exchange_rate;
        $this->state = new state;
        $this->zone = new zone;
        $this->customer_supplier = new customer_supplier;
    }
    public function customerSupplierList(Request $request)
    {
        $condition =[];
        if($request->firm_name)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->firm_name . '%'];
        }
        if($request->contact_person)
        {
            $condition[] = ['customer_supplier.contact_person','like', '%' . $request->contact_person . '%'];
        }
        if($request->sales_type)
        {
            $condition[] = ['customer_supplier.sales_type','like', '%' . $request->sales_type . '%'];
        }
        if($request->state)
        {
            $condition[] = ['customer_supplier.state','like', '%' . $request->state . '%'];
        }
        $states = $this->state->get_states([]);
        $customers = $this->customer_supplier->get_all($condition);
        return view('pages/FGS/customer-supplier/customer-list',compact('customers','states'));
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
                $validation['pan_number'] = ['required'];
                $validation['status_type'] = ['required'];
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
                    $data['country'] = $request->country;
                    $data['pan_number'] = $request->pan_number;
                    $data['gst_number'] = $request->gst_number;
                    $data['dl_number1'] = $request->dl_number1;
                    $data['dl_number2'] = $request->dl_number2;
                    $data['dl_number3'] = $request->dl_number3;
                    $data['currency'] = $request->currency;
                    $data['payment_terms'] = $request->payment_terms;
                    $data['sales_type'] = $request->sales_type;
                    // $data['sales_person_name'] = $request->sales_person_name;
                    // $data['sales_person_email'] = $request->sales_person_email;
                    $data['me_name'] = $request->me_name;
                    $data['me_email'] = $request->me_email;
                    $data['asm_name'] = $request->asm_name;
                    $data['asm_email'] = $request->asm_email;
                    $data['zm_name'] = $request->zm_name;
                    $data['zm_email'] = $request->zm_email;
                    $data['rm_name'] = $request->rm_name;
                    $data['rm_email'] = $request->rm_email;
                    $data['whatsapp_number'] = $request->whatsapp_number;
                    $data['status_type'] = $request->status_type;
                    $data['master_type'] = $request->master_type;
                    $data['dl_expiry_date'] =date('Y-m-d',strtotime($request->dl_expiry_date));

                    if($request->id)
                    { 
                         
                    $data['updated_at'] = date('Y-m-d H:i:s');
                        $this->customer_supplier->update_data(['id'=>$request->id],$data);

                        $request->session()->flash('success',"You have successfully updated a customer !");
                        return redirect("fgs/customer-supplier");
                    
                    }
                    else
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
                    $data['payment_terms'] = nl2br($request->payment_terms);
                    $data['sales_type'] = $request->sales_type;
                    // $data['sales_person_name'] = $request->sales_person_name;
                    // $data['sales_person_email'] = $request->sales_person_email;
                    $data['me_name'] = $request->me_name;
                    $data['me_email'] = $request->me_email;
                    $data['asm_name'] = $request->asm_name;
                    $data['asm_email'] = $request->asm_email;
                    $data['zm_name'] = $request->zm_name;
                    $data['zm_email'] = $request->zm_email;
                    $data['rm_name'] = $request->rm_name;
                    $data['rm_email'] = $request->rm_email;
                    $data['whatsapp_number'] = $request->whatsapp_number;
                    $data['dl_expiry_date'] =date('Y-m-d',strtotime($request->dl_expiry_date));
                    $data['status_type'] = $request->status_type;
                    $data['master_type'] = $request->master_type;

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
                    if($request->id)
                    return redirect("fgs/customer-supplier/add/".$id)->withErrors($validator)->withInput();
                    else
                    return redirect("fgs/customer-supplier/add")->withErrors($validator)->withInput();
                }
            }
            else
            {
            if($request->id)
            {
                $datas = $this->customer_supplier->get_single_customer_supplier(['customer_supplier.id'=>$request->id]);
                 
                  $currency =  $this->currency_exchange_rate->get_currency([]);
            $states = $this->state->get_states([]);
           
            $zones = $this->zone->get_zones([]);

                 return view('pages/FGS/customer-supplier/customer-add',compact('datas','id','currency','states','zones'));
                  
            
            }
         
            else
            {
                $datas = [];
                 $currency =  $this->currency_exchange_rate->get_currency([]);
            $states = $this->state->get_states([]);

            $zones = $this->zone->get_zones([]);

             return view('pages/FGS/customer-supplier/customer-add',compact('currency','states','zones','datas'));
            }
    }
    }

    
    public function customersearch(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Customer is not valid'], 500); 
        }
        $data =  $this->customer_supplier->get_customer_data(strtoupper($request->q));
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Customer is not valid'], 500); 
        }
    }
    public function domesticCustomer(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Customer is not valid'], 500); 
        }
        $data =  $this->customer_supplier->get_domestic_customer_data(strtoupper($request->q));
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Customer is not valid'], 500); 
        }
    }
    public function exportCustomer(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Customer is not valid'], 500); 
        }
        $data =  $this->customer_supplier->get_export_customer_data(strtoupper($request->q));
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Customer is not valid'], 500); 
        }
    }
     public function CustomerSupplierExport(Request $request)
    {
        if($request)
        {
            return Excel::download(new CustomerSupplierExport($request), 'CustomerSupplier' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new CustomerSupplierExport($request), 'CustomerSupplier' . date('d-m-Y') . '.xlsx');
        }
    }

    
}
