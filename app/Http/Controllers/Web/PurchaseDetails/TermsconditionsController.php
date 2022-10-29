<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseDetails\po_supplier_terms_conditions;

use Validator;

class TermsconditionsController extends Controller
{
    public function __construct()
    {
        $this->po_supplier_terms_conditions = new po_supplier_terms_conditions;
    }

    public function list_terms_conditions(Request $request) 
    {     
        $data['terms'] = $this->po_supplier_terms_conditions->get_data([]);
        return view('pages/purchase-details/supplier-terms-conditions/terms-conditions-list',compact('data'));
    }
    public function add_terms_conditions(Request $request,$id = null) 
    {    $datas = []; 

        if ($request->isMethod('post'))
        {
            $validation['title'] = ['required'];
            $validation['conditions'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 
                $data['title'] = $request->title;
                $data['terms_and_conditions'] = $request->conditions;
                $data['type'] = "supplier";
                if($id){
                    $request->session()->flash('success',"You have successfully updated a  terms and condition !");
                    $this->po_supplier_terms_conditions->updatedata(['id'=>$id],$data);
                }else{
                  $request->session()->flash('success',"You have successfully added a  terms and condition !");
                  $this->po_supplier_terms_conditions->insert_data($data);
                }
                return redirect("inventory/terms-and-conditions-list");
            }
            if($validator->errors()->all()) 
            { 
                return redirect("inventory/terms-and-conditions-add/".$id)->withErrors($validator)->withInput();
            }
        }
    if($id){
        $datas['terms_conditions'] = $this->po_supplier_terms_conditions->single_get_data(['id'=>$id]);
    }
        return view('pages/purchase-details/supplier-terms-conditions/terms-conditions-add',compact('datas','id'));
    }
    function get_terms_conditions($id){
        $datas['terms_conditions'] = $this->po_supplier_terms_conditions->single_get_data(['id'=>$id]);
              return response()->json(  $datas['terms_conditions'], 200); 
    
    }


}

