<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\PurchaseDetails\inv_miq;
use App\Models\PurchaseDetails\inv_miq_item;
use App\Models\PurchaseDetails\inv_miq_item_rel;
use App\Models\PurchaseDetails\inv_mrd;
use App\Models\PurchaseDetails\inv_mrd_item;
use App\Models\PurchaseDetails\inv_mrd_item_rel;
use App\Models\User;
use App\Models\currency_exchange_rate;

class MRDController extends Controller
{
    public function __construct()
    {
        $this->inv_miq = new inv_miq;
        $this->inv_miq_item = new inv_miq_item;
        $this->inv_miq_item_rel = new inv_miq_item_rel;
        $this->inv_mrd = new inv_mrd;
        $this->inv_mrd_item = new inv_mrd_item;
        $this->inv_mrd_item_rel = new inv_mrd_item_rel;
        $this->User = new User;
        $this->currency_exchange_rate = new currency_exchange_rate;
    }

    public function MRDlist(Request $request)
    {
        $condition=[];
        if($request)
        {
            if ($request->miq_no) {
                $condition[] = ['inv_miq.miq_number','like', '%' . $request->miq_no . '%'];
            }
            if ($request->mrd_no) {
                $condition[] = ['inv_mrd.mrd_number','like', '%' . $request->mrd_no . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
            
            if ($request->from) {
                $condition[] = ['inv_mrd.mrd_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
                $condition[] = ['inv_mrd.mrd_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
            }
            $data= $this->inv_mrd->get_all_data($condition);
        }
        else
        $data = $this->inv_mrd->get_all_data($condition=null);
        //$data = $this->inv_mrd->get_all_data([]);
        return view('pages.inventory.MRD.MRD-list',compact('data'));
    }

    public function MRDAdd(Request $request,$id=null)
    {
        if($request->isMethod('post'))
        {
            $validation['mrd_date'] = ['required','date'];
            $validation['miq_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(!$request->id)
                {
                    $item_type = $this->get_item_type($request->miq_number);
                    if($item_type=="Direct Items"){
                        $Data['mrd_number'] = "MRD2-".$this->po_num_gen(DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'MAC2%')->count(),1); 
                    }
                    if($item_type=="Indirect Items"){
                        $Data['mrd_number'] = "MRD3-" . $this->po_num_gen(DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'MAC3%')->count(),1); 
                    }
                    $Data['mrd_date'] = date('Y-m-d', strtotime($request->mrd_date));
                    $Data['miq_id'] = $request->miq_number;
                    $Data['created_by']= $request->created_by;
                    $Data['status']=1;
                    $Data['created_at'] =date('Y-m-d H:i:s');
                    $Data['updated_at'] =date('Y-m-d H:i:s');
                    $add_id = $this->inv_mrd->insert_data($Data);
                    $miq_items = inv_miq_item_rel::select('inv_miq_item_rel.item','inv_miq_item.item_id')
                                ->leftJoin('inv_miq_item','inv_miq_item.id','=','inv_miq_item_rel.item')
                                ->where('master','=',$request->miq_number)->get();
                    foreach($miq_items as $item){
                        $dat=[
                            'miq_item_id'=>$item->item,
                            'item_id'=>$item->item_id,
                            'status'=>1,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')

                        ];
                        $item_id = $this->inv_mrd_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_mrd_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a MRD !");
                    else
                        $request->session()->flash('error', "MRD creation is failed. Try again... !");
                    return redirect('inventory/MRD-add/'.$add_id);
                }
                else
                {
                    //echo $request->created_by;exit;
                        $data['mrd_date'] = date('Y-m-d', strtotime($request->mrd_date));
                        $data['created_by']= $request->created_by;
                        $data['updated_at'] =date('Y-m-d H:i:s');
                        $update = $this->inv_mrd->update_data(['id'=>$request->id],$data);
                    if($update)
                        $request->session()->flash('success', "You have successfully updated a MRD !");
                    else
                        $request->session()->flash('error', "MRD updation is failed. Try again... !");
                    return redirect('inventory/MRD-add/'.$request->id);
                }
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/MRD-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);

        if($request->id){
            $edit['mrd'] = $this->inv_mrd->find_mrd_data(['inv_mrd.id' => $request->id]);

            $edit['items'] = $this->inv_mrd_item->get_items(['inv_mrd_item_rel.master' =>$request->id]);
            return view('pages.inventory.MRD.MRD-Add',compact('edit','data'));
        }
        else
        return view('pages.inventory.MRD.MRD-Add',compact('data'));
    }

    public function get_item_type($miq_number)
    {
        $item_type = inv_miq_item_rel::leftJoin('inv_miq_item','inv_miq_item.id','=','inv_miq_item_rel.item')
                            ->leftJoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                            ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                            ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.requisition_item_id')
                            ->leftJoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_miq_item_rel.master','=', $miq_number)->pluck('inv_item_type.type_name')->first();
        return $item_type;
    }

    public function MRDAddItemInfo(Request $request,$id)
    {
        if ($request->isMethod('post')) {
            $validation['rejected_quantity'] = ['required'];
            $validation['currency'] = ['required'];
            $validation['conversion_rate'] = ['required'];
            $validation['value_inr'] = ['required'];
            $validation['remarks'] = ['required'];

            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()){
                $data['rejected_quantity'] =$request->rejected_quantity;
                $data['remarks'] =$request->remarks;
                $data['currency'] =$request->currency;
                $data['value_inr'] =$request->value_inr;
                $data['conversion_rate'] =$request->conversion_rate;
                $update = $this->inv_mrd_item->update_data(['inv_mrd_item.id'=>$request->id],$data);
                $mrd_id = inv_mrd_item_rel::where('item','=',$request->id)->pluck('master')->first();
                if($update)
                    $request->session()->flash('success', "You have successfully updated a MRD Item Info!");
                else
                    $request->session()->flash('error', "MRD Item info updation is failed. Try again... !");
                return redirect('inventory/MRD-add/'.$mrd_id);
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/MRD/'.$id.'/item')->withErrors($validator)->withInput();
            }
        }
        $data = $this->inv_mrd_item->get_item(['inv_mrd_item.id'=>$id]);
        $currency = $this->currency_exchange_rate->get_currency([]);
        return view('pages.inventory.MRD.MRD-itemInfo', compact('data','currency'));

    }
    public function mrd_delete(Request $request, $id)
    {
        $this->inv_mrd->deleteData(['id' => $id]);
        $request->session()->flash('success', "You have successfully deleted a MRD !");
        return redirect("inventory/MRD");
    }

    public function findMiqNumberForMRD(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_miq.miq_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_miq->find_miq_num_for_mrd($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->invoice_details($request->id, null);
            exit;
        }
    }
}
