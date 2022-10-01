<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;

use Validator;
use DB;

use App\Models\Department;
use App\Models\User;
use App\Models\PurchaseDetails\inv_purchase_req_master;
use App\Models\inventory_gst;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_supplier;
use App\Models\currency_exchange_rate;
use App\Models\PurchaseDetails\inv_purchase_req_item;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
        $this->Department = new Department;
        $this->User = new User;
        $this->inv_purchase_req_master = new inv_purchase_req_master;
        $this->inventory_gst = new inventory_gst;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->inv_supplier = new inv_supplier;
        $this->currency_exchange_rate = new currency_exchange_rate;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        

    }

    // Purchase Reqisition Master get list
    public function get_purchase_reqisition(Request $request)
    {
            $condition = []; 
            if ($request->department) {
                $condition[] = ['department.dept_name', 'like', '%'.$request->department.'%'];
            }
            if ($request->pr_no) {
                $condition[] = ['inv_purchase_req_master.pr_no',  'like', '%'.$request->pr_no.'%'];
            }

            if ($request->prsr) {
                $condition[] = ['inv_purchase_req_master.PR_SR', '=', strtolower($request->prsr)];
            }
            if (!$request->prsr) {
                $condition[] = ['inv_purchase_req_master.PR_SR', '=', 'PR'];
            }

            if ($request->from) {
                $condition[] = ['inv_purchase_req_master.date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_purchase_req_master.date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
           
        $data['master']=$this->inv_purchase_req_master->get_inv_purchase_req_master_list($condition);
       
       
        $data['department']= $this->Department->get_dept([]);
        $data['pr_nos'] = $this->inv_purchase_req_master->get_pr_nos();
        // print_r(json_encode($data['master']));
        // exit;
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-list', compact('data'));
       // $data['master']=$this->inv_purchase_req_master->get_inv_purchase_req_master_list();
        //return view('pages/purchase-details/purchase-requisition/purchase-requisition-list', compact('data'));
    }

    // Purchase Reqisition Master Add
    public function add_purchase_reqisition(Request $request)
    {

        if ($request->isMethod('post')) {

            $validation['Date'] = ['required'];
            $validation['Department'] = ['required'];
            //$validation['Type'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
                $datas['requestor_id'] = $request->Requestor;
                if($request->prsr=="pr"){
                $datas['pr_no'] = "PR-".$this->num_gen(DB::table('inv_purchase_req_master')->count());
                }
                if($request->prsr=="sr"){
                    $datas['pr_no'] = "SR-".$this->num_gen(DB::table('inv_purchase_req_master')->count());
                }
                $datas['department'] =  $request->Department;
                $datas['date'] =  date('Y-m-d',strtotime($request->Date));
                $datas['PR_SR'] =  $request->prsr;
                $datas['created_at'] =  date('Y-m-d h:i:s');
                $datas['updated_at'] =  date('Y-m-d h:i:s');
                $inv_purchase_num =  $this->inv_purchase_req_master->insertdata($datas);
                if($request->prsr=='pr'){
                    return redirect('inventory/add-purchase-reqisition-item?pr_id='.$inv_purchase_num);
                }
                else
                {
                    return redirect('inventory/add-purchase-reqisition-item?sr_id='.$inv_purchase_num);
                }
            }
            if ($validator->errors()->all()) {
                return redirect("inventory/add-purchase-reqisition/")->withErrors($validator)->withInput();
            }
        }
        $data['users'] = $this->User->get_all_users([]);
        $data['Department'] = $this->Department->get_dept(['status'=>1]);
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-add', compact('data'));

    }

    // Purchase Reqisition Master edit
    public function edit_purchase_reqisition(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['Date'] = ['required'];
            $validation['Department'] = ['required'];
            // $validation['PRSR'] = ['required'];
            $validator = Validator::make($request->all(), $validation);

            if(!$validator->errors()->all()){
                $datas['requestor_id'] = $request->Requestor;
                $datas['department'] =  $request->Department;
                $datas['date'] =  date('Y-m-d',strtotime($request->Date));
                //$datas['PR_SR'] =  $request->prsr;
                $datas['updated_at'] =  date('Y-m-d h:i:s');
                if($request->pr_id) 
                {
                    $this->inv_purchase_req_master->updatedata(['master_id'=>$request->pr_id],$datas);
                    return redirect('inventory/edit-service-reqisition?pr_id='.$request->pr_id);
                }
                else 
                {
                    $this->inv_purchase_req_master->updatedata(['master_id'=>$request->sr_id],$datas);
                    return redirect('inventory/edit-service-reqisition?sr_id='.$request->sr_id);
                }
            }
            if ($validator->errors()->all()) {
                if($request->pr_id) 
                return redirect("inventory/edit-purchase-reqisition/?pr_id=".$request->pr_id)->withErrors($validator)->withInput();
                else
                return redirect("inventory/edit-service-reqisition/?sr_id=".$request->sr_id)->withErrors($validator)->withInput();
            }
        }
        $data['Department'] = $this->Department->get_dept(['status'=>1]);
        $data['users'] = $this->User->get_all_users([]);
        if($request->pr_id) 
            $data['inv_purchase_req_master'] = $this->inv_purchase_req_master->get_data(['inv_purchase_req_master.status'=>1,'master_id'=>$request->pr_id]);
        else
            $data['inv_purchase_req_master'] = $this->inv_purchase_req_master->get_data(['inv_purchase_req_master.status'=>1,'master_id'=>$request->sr_id]);

        return view('pages/purchase-details/purchase-requisition/purchase-requisition-add', compact('data'));
    }
    // Purchase Reqisition Master delete
    public function delete_purchase_reqisition(Request $request)
    {
        if($request->pr_id)
        {
            $this->inv_purchase_req_master->updatedata(['master_id'=>$request->pr_id],['status'=>2]);
            $request->session()->flash('success',  "You have successfully deleted a  purchase requisition master !");
        }
        
        
       return redirect('inventory/get-purchase-reqisition');
    }
    // service Reqisition Master delete
    public function delete_service_reqisition(Request $request)
    {
        if($request->sr_id)
        {
            $this->inv_purchase_req_master->updatedata(['master_id'=>$request->sr_id],['status'=>2]);
            $request->session()->flash('success',  "You have successfully deleted a  service requisition master !");
        }
        
        
       return redirect('inventory/get-purchase-reqisition');
    }

    // Purchase Reqisition item get list
    public function get_purchase_reqisition_item(Request $request)
    {
        if((!$request->pr_id) AND (!$request->sr_id)){
            return response()->view('errors/404', [], 404);
        }
        if($request->pr_id)
        {
            $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->pr_id]);
            $data['item'] = $this->inv_purchase_req_item->getItemdata(['inv_purchase_req_master_item_rel.master'=>$request->pr_id]);
        }
        else 
        {
            $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->sr_id]);
            $data['item'] = $this->inv_purchase_req_item->getItemdata(['inv_purchase_req_master_item_rel.master'=>$request->sr_id]);
        }
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-list', compact('data'));
        
    }
      // Purchase Reqisition add item 
      public function add_purchase_reqisition_item(Request $request)
      {
        if((!$request->pr_id) && (!$request->sr_id)){
            return response()->view('errors/404', [], 404);
        }

        if ($request->isMethod('post')) {
            if($request->pr_id){
            $validation['pr_id'] = ['required'];
            }
            else{
                $validation['sr_id'] = ['required'];
            }
            $validation['Itemcode'] = ['required'];
            //$validation['Supplier'] = ['required'];
            $validation['Currency'] = ['required'];
            $validation['Rate'] = ['required'];
            $validation['BasicValue'] = ['required'];
            $validation['Discount'] = ['required'];
           // $validation['gst'] = ['required'];
            $validation['Netvalue'] = ['required'];
           // $validation['Remarks'] = ['required'];
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
                    "gst"=> $request->gst,
                    "currency"  => $request->Currency ,
                    "net_value"=>  $request->Netvalue,
                    "remarks"=> $request->Remarks,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                    "created_user" =>  config('user')['user_id']   
                ];
                if($request->pr_id){
                $this->inv_purchase_req_item->insert_data($Request,$request->pr_id);
                $request->session()->flash('success',"You have successfully added a purchase requisition item !");
                return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
                }
                else {
                    $this->inv_purchase_req_item->insert_data($Request,$request->sr_id);
                    $request->session()->flash('success',"You have successfully added a service requisition item !");
                    return redirect('inventory/get-purchase-reqisition-item?sr_id='.$request->sr_id);
                }

            }
            if($validator->errors()->all()){
                if($request->pr_id)
                    return redirect("inventory/add-purchase-reqisition-item?pr_id=".$request->pr_id)->withErrors($validator)->withInput();
                else
                return redirect("inventory/add-purchase-reqisition-item?sr_id=".$request->sr_id)->withErrors($validator)->withInput();
            }
        }
        if($request->pr_id)
            $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->pr_id]);
        else
        $data["master"] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->sr_id]);
        $data["currency"] = $this->currency_exchange_rate->get_currency([]);
        $data['gst'] = $this->inventory_gst->get_gst();
        return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-add', compact('data'));
      }

      public function getSGSTandCGST(Request $request)
      {
        $id = $request->id;
        $gst = $this->inventory_gst->get_single_gst(['id'=>$id]);
        return $gst;
     }
  
        //edit  Purchase Reqisition item 
        public function edit_purchase_reqisition_item(Request $request)
        {
            if(!$request->pr_id && !$request->item && !$request->sr_id){
                return redirect('inventory/get-purchase-reqisition');
            } 

            if ($request->isMethod('post')) 
            {
                if($request->pr_id){
                    $validation['pr_id'] = ['required'];
                    }
                    else{
                        $validation['sr_id'] = ['required'];
                    }
                $validation['Itemcode'] = ['required'];
                //$validation['Supplier'] = ['required'];
                $validation['Currency'] = ['required'];
                $validation['Rate'] = ['required'];
                $validation['BasicValue'] = ['required'];
                $validation['Discount'] = ['required'];
                //$validation['gst'] = ['required'];
                $validation['Netvalue'] = ['required'];
                //$validation['Remarks'] = ['required'];
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
                        "gst"=> $request->gst,
                        "currency"  => $request->Currency ,
                        "net_value"=>  $request->Netvalue,
                        "inv_purchase_req_item.remarks"=> $request->Remarks,
                        "inv_purchase_req_item.updated_at" => date('Y-m-d H:i:s'),
                        "inv_purchase_req_item.created_user" =>  config('user')['user_id']   
                    ];
                    if($request->pr_id)
                    {
                        $this->inv_purchase_req_item->updatedata(['inv_purchase_req_item.requisition_item_id'=>$request->item],$Request);
                        $request->session()->flash('success',"You have successfully edited a purchase requisition item !");
                        return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
                    }
                    else
                    {
                        $this->inv_purchase_req_item->updatedata(['inv_purchase_req_item.requisition_item_id'=>$request->item],$Request);
                        $request->session()->flash('success',"You have successfully edited a service requisition item !");
                        return redirect('inventory/get-purchase-reqisition-item?sr_id='.$request->sr_id);
                    }
                }
                
                if($validator->errors()->all()){
                    if($request->pr_id)
                    return redirect("inventory/edit-purchase-reqisition-item?pr_id=".$request->pr_id)->withErrors($validator)->withInput();
                    else
                    return redirect("inventory/add-purchase-reqisition-item?sr_id=".$request->sr_id)->withErrors($validator)->withInput();                    
                }
            }
            //echo $request->item;exit;
            $datas["item"] = $this->inv_purchase_req_item->getItem(['inv_purchase_req_item.requisition_item_id'=>$request->item]);
            if($request->pr_id)
            $data['master'] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->pr_id]);
            else
            $data['master'] = $this->inv_purchase_req_master->get_data(['master_id'=>$request->sr_id]);
            $data["currency"] = $this->currency_exchange_rate->get_currency([]);
            $data['gst'] = $this->inventory_gst->get_gst();
          
          
            return view('pages/purchase-details/purchase-requisition/purchase-requisition-item-add', compact('data', 'datas'));
            
        }


    // Purchase Reqisition item delete 
    public function delete_purchase_reqisition_item(Request $request)
    {
        if($request->item_id){
            $this->inv_purchase_req_item->updatedata(['requisition_item_id'=>$request->item_id],['status'=>2]);
            $request->session()->flash('success',  "You have successfully deleted a  purchase requisition item !");
        }
        if($request->pr_id)
        return redirect('inventory/get-purchase-reqisition-item?pr_id='.$request->pr_id);
        else
        return redirect('inventory/get-purchase-reqisition-item?sr_id='.$request->sr_id);
    }

    function itemcodesearch(Request $request,$itemcode = null){
        if(!$request->q){
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
        $condition[] = ['inventory_rawmaterial.item_code','like','%'.strtoupper($request->q).'%'];
        $data  = $this->inventory_rawmaterial->get_inv_raw_data($condition);
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }

    }

    function suppliersearch(Request $request){
        if(!$request->q){
            return response()->json(['message'=>'item code is not valid'], 500); 
        }
        $data =  $this->inv_supplier->get_supplier_data(strtoupper($request->q));
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'item code is not valid'], 500); 
        }

        


        }
}