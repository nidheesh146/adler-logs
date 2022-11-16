<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\PurchaseDetails\inv_purchase_req_quotation;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_item_supp_rel;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_supplier;
use App\Models\PurchaseDetails\inv_final_purchase_order_rel;



class QuotationController extends Controller
{
    public function __construct()
    {
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        $this->inv_purchase_req_quotation = new inv_purchase_req_quotation;
        $this->inv_supplier = new inv_supplier;
        $this->inv_purchase_req_quotation_item_supp_rel = new inv_purchase_req_quotation_item_supp_rel;
        $this->inv_purchase_req_quotation_supplier = new inv_purchase_req_quotation_supplier;
        $this->inv_final_purchase_order_rel = new inv_final_purchase_order_rel;
    }

    // list Quotation
    public function getQuotation(Request $request,$id=null)
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



      //  $data['reopen_supplier'] = $this->inv_purchase_req_quotation_supplier->inv_purchase_req_quotation_data(['inv_purchase_req_quotation_supplier.quotation_id'=>$id]);
        if($id){
        $data['reopen_data_single'] = $this->inv_final_purchase_order_rel->singleGetData(['inv_final_purchase_order_rel.master'=>$id]);
        $data['reopen_data'] = $this->inv_final_purchase_order_rel->getData(['inv_final_purchase_order_rel.master'=>$id]);
        }
      
        $data['getdata'] = $this->inv_purchase_req_item->getdata($condition);
      
      
        return view('pages/purchase-details/Quotation/quotation-add', compact('data','id'));
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
                     'created_user'=>config('user')['user_id'],
                     'type'=> ($request->prsr == 'sr') ? 'SR' : 'PR'
                    ];
            $this->inv_purchase_req_quotation->insert_data($data,$request);

            $request->session()->flash('success', "You have successfully created a  Request For Quotation !");
            if($request->prsr)
            return redirect('inventory/quotation?prsr='.$request->prsr);
            else
            return redirect('inventory/quotation');

        }
        if($validator->errors()->all()){
            if($request->prsr)
            return redirect('inventory/quotation?prsr='.$request->prsr)->withErrors($validator)->withInput();
            else
            return redirect('inventory/quotation')->withErrors($validator)->withInput();

            //return redirect('inventory/quotation');
        }
           
    }

    public function getItems(Request $request)
    {
        $data['getdata'] = $this->inv_purchase_req_item->getdata(['inv_item_type.type_name'=>$request->type]);
        return view('pages/purchase-details/Quotation/quotation-add', compact('data'));

    }
    function request_quotation(Request $request,$q_id,$s_id){
        if((!$this->decrypt($q_id)) ||  (!$this->decrypt($s_id))){
            return response()->view('errors/404', [], 404);
        }
        $q_id = $this->decrypt($q_id);
        $s_id = $this->decrypt($s_id);
        $data['inv_purchase_req_quotation'] = $this->inv_purchase_req_quotation->get_quotation_single(['quotation_id'=>$q_id]);
        $data['inv_supplier'] = $this->inv_supplier->get_supplier(['id'=>$s_id]);
        $data['inv_purchase_req_quotation_item_supp_rel'] = $this->inv_purchase_req_quotation_item_supp_rel->open_get_quotation(['inv_purchase_req_quotation_item_supp_rel.quotation_id'=>$q_id,'inv_purchase_req_quotation_item_supp_rel.supplier_id'=>$s_id]);
//print_r( $data['inv_purchase_req_quotation_item_supp_rel']);die;
        return view('pages/purchase-details/supplier-quotation/quotation-open-view', compact('data'));
    }

    public function directPurchase(Request $request)
    {
        if ($request->prsr) {
            if($request->type)
            {
            $condition[] = ['inv_purchase_req_master.PR_SR', '=', strtolower($request->prsr)];
            $condition[] = ['inventory_rawmaterial.item_type_id','=',$request->type];
            }
            if($request->Supplier)
            {
                $condition[] = ['inv_supplier_itemrate.supplier_id','=',$request->Supplier];
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
            if($request->Supplier)
            {
                $condition[] = ['inv_supplier_itemrate.supplier_id','=',$request->Supplier];
            }
            $condition[] = ['inv_purchase_req_master.PR_SR', '=', 'PR'];
        }
        $data['getdata'] = $this->inv_purchase_req_item->getdataFixedItems($condition);
        return view('pages/purchase-details/Quotation/direct-purchase', compact('data'));
    }

    public function directPurchaseQuotation(Request $request)
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
                     'created_user'=>config('user')['user_id'],
                     'type'=> ($request->prsr == 'sr') ? 'SR' : 'PR'
                    ];
            $this->inv_purchase_req_quotation->insert_fixed_item_data($data,$request);

            $request->session()->flash('success', "You have successfully created a  Request For Quotation !");
            if($request->prsr)
            return redirect('inventory/direct/purchase?prsr='.$request->prsr);
            else
            return redirect('inventory/direct/purchase');

        }
        if($validator->errors()->all()){
            if($request->prsr)
            return redirect('inventory/direct/purchase?prsr='.$request->prsr)->withErrors($validator)->withInput();
            else
            return redirect('inventory/direct/purchase')->withErrors($validator)->withInput();

            //return redirect('inventory/quotation');
        }
    }

    public function get_supplier($id){
        $supplier = inv_supplier::where('id','=',$id)->pluck('vendor_name')->first();
        return $supplier;
    }

    public function checkFixedItem($item_id)
    {
        $supplier = inv_purchase_req_item::where('inv_purchase_req_item.requisition_item_id','=',$item_id)
                        ->leftjoin('inv_supplier_itemrate','inv_supplier_itemrate.item_id','=','inv_purchase_req_item.Item_code')
                        ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_itemrate.supplier_id')
                        ->whereIn('inv_purchase_req_item.Item_code',function($query){
                            $query->select('inv_supplier_itemrate.item_id')->from('inv_supplier_itemrate');
                        })
                        ->pluck('inv_supplier.vendor_name')->first();
        if($supplier)
        return $supplier;
        else 
        return 0;
    }

}
