<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseDetails\inv_supplier;

use Validator;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->inv_supplier = new inv_supplier;
    }

    public function list_supplier(Request $request) 
    {   $condition =[];

        if ($request->supplier_id) {
            $condition[] = ['inv_supplier.vendor_id', 'like','%'.$request->supplier_id.'%'];
        }
        if ($request->supplier_name) {
            $condition[] = ['inv_supplier.vendor_name', 'like','%'.$request->supplier_name.'%'];
        }
        if ($request->contact_persion) {
            $condition[] = ['inv_supplier.contact_person', 'like','%'.$request->contact_persion.'%'];
        }
        if ($request->type) {
            $condition[] = ['inv_supplier.supplier_type', '=', $request->type];
        }

        $data['suppliers'] = $this->inv_supplier->get_suppliers($condition);
        return view('pages/purchase-details/suppliers/suppliers-list',compact('data'));
    }
    public function add_supplier(Request $request,$id = null) 
    {   
        $datas = []; 
        if ($request->isMethod('post'))
        {
            $validation['Vendor_id'] = ['required'];
            $validation['Vendor_name'] = ['required'];
            $validation['contact_person'] = ['required'];
            $validation['Address'] = ['required'];
            $validation['conditions'] = ['required'];
            $validation['Supplier_type'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 
                $data['vendor_id'] = $request->Vendor_id;
                $data['vendor_name'] = $request->Vendor_name;
                $data['indian_overseas'] = $request->Indian_Overseas;
                $data['justification_company_classification'] = $request->justification_company_classification;
                $data['company_classification'] = $request->company_classification;
                $data['general_product_service'] = $request->general_product_service;
                $data['contact_person'] = $request->contact_person;
                $data['contact_number'] = json_encode(explode(",",$request->contact_number));
                $data['email'] = json_encode(explode(",",$request->email));
                $data['supplier_type'] = $request->Supplier_type;
                $data['address'] = $request->Address;
                $data['remarks'] = $request->Remarks;
                $data['terms_and_conditions'] = $request->conditions;
                if($id){
                    $data['updated'] = date('Y-m-d H:i:s');
                    $request->session()->flash('success',"You have successfully updated a supplier !");
                    $this->inv_supplier->updatedata(['id'=>$id],$data);
                    return redirect("inventory/suppliers-add/".$id);
                }else{
                    $data['created'] = date('Y-m-d H:i:s');
                    $data['updated'] = date('Y-m-d H:i:s');
                  $request->session()->flash('success',"You have successfully added a supplier !");
                  $this->inv_supplier->insert_data($data);
                  return redirect("inventory/suppliers-list");
                }
            }
            if($validator->errors()->all()) 
            { 
                return redirect("inventory/suppliers-add/".$id)->withErrors($validator)->withInput();
            }
        }
    if($id){
        $datas['supplier'] = $this->inv_supplier->get_supplier(['id'=>$id]);
    }
        return view('pages/purchase-details/suppliers/suppliers-add',compact('datas','id'));
    }
    function get_supplier($id){
        $datas['terms_conditions'] = $this->po_supplier_terms_conditions->single_get_data(['id'=>$id]);
              return response()->json(  $datas['terms_conditions'], 200); 
    
    }

    function delete_suppliers(Request $request,$id){
        $this->inv_supplier->updatedata(['id'=>$id],['status'=>2]);
        $request->session()->flash('success',"You have successfully deleted a supplier !");
        return redirect("inventory/suppliers-list");
    }
}

