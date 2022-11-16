<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Models\PurchaseDetails\inv_miq;
use App\Models\PurchaseDetails\inv_miq_item;
use App\Models\PurchaseDetails\inv_miq_item_rel;
use App\Models\PurchaseDetails\inv_mac;
use App\Models\User;
class MACController extends Controller
{
    public function __construct()
    {
        $this->inv_miq = new inv_miq;
        $this->inv_miq_item = new inv_miq_item;
        $this->inv_miq_item_rel = new inv_miq_item_rel;
        $this->inv_mac = new inv_mac;
        $this->User = new User;
     
    }

    public function MAClist()
    {
        return view('pages.inventory.MAC.MAC-list');
    }

    public function MACAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['mac_date'] = ['required','date'];
            $validation['miq_number'] = ['required'];
            $validation['created_by'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $item_type = $this->get_item_type($request->miq_number);
                if($item_type=="Direct Items"){
                    $Data['mac_number'] = "MAC2-".$this->po_num_gen(DB::table('inv_mac')->where('inv_mac.mac_number', 'LIKE', 'MAC2%')->count(),1); 
                }
                if($item_type=="Indirect Items"){
                    $Data['mac_number'] = "MAC3-" . $this->po_num_gen(DB::table('inv_mac')->where('inv_miq.mac_number', 'LIKE', 'MAC3%')->count(),1); 
                }
                $Data['mac_date'] = date('Y-m-d', strtotime($request->miq_date));
                $Data['miq_id'] = $request->invoice_number;
                $Data['created_by']= $request->created_by;
                $Data['status']=1;
                $Data['created_at'] =date('Y-m-d H:i:s');
                $Data['updated_at'] =date('Y-m-d H:i:s');
                $add_id = $this->inv_mac->insert_data($Data);
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
                        $item_id = $this->inv_mac_item->insert_data($dat);
                        $dat2 =[
                            'master'=>$add_id,
                            'item'=>$item_id,
                        ];
                        $rel =DB::table('inv_mac_item_rel')->insert($dat2);
                    }
                    
                    if($add_id && $item_id && $rel)
                        $request->session()->flash('success', "You have successfully created a MAC !");
                    else
                        $request->session()->flash('error', "MAC creation is failed. Try again... !");
                    return redirect('inventory/MIQ-add/'.$add_id);
            }
            if($validator->errors()->all())
            {
                return redirect('inventory/MAC-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);

        if($request->id){
            $edit=1;
            return view('pages.inventory.MAC.MAC-Add',compact('edit','data'));
        }
        else
        return view('pages.inventory.MAC.MAC-Add',compact('data'));
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

    public function MACAddItemInfo()
    {
        
        return view('pages.inventory.MAC.MAC-itemInfo');
    }

    public function findMiqNumber(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_miq.miq_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_miq->find_miq_num($condition);
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

    public function find_miq_info(Request $request)
    {
        if ($request->q) {
            $condition[] = ['inv_miq.miq_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->inv_miq->find_miq_num($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->miq_details($request->id, null);
            exit;
        }
    }
    public function miq_details($id, $active = null)
    {
        $miq = $this->inv_miq->find_miq_data(['inv_miq.id' => $id]);

        $inv_miq_item = $this->inv_miq_item->get_items(['inv_miq_item_rel.master' => $id]);
        // if ($active) {
        //     $inv_mac_item = $this->inv_mac_item->get_mac_items(['inv_mac_rel.master' => $active]);
        // }

        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                Material Inwards To Quarantine (' . $miq->miq_number . ')
                   </label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
           <thead>
           <tr>
               <th>MIQ Date</th>
               <th>Created Date</th>
           </tr>
          </thead>
           <tbody>
           <tr>
               <td>' . date('d-m-Y', strtotime($miq->miq_date)) . '</td>
               <td>' . date('d-m-Y', strtotime($miq->created_at)) . '</td>
           </tr>
           </tbody>
           </table>

           <table class="table table-bordered mg-b-0">
           <thead>
           <tr>
               <th>Supplier ID</th>
               <th>Supplier Name</th>
           </tr>
          </thead>
           <tbody>
           <tr>
               <td>'.$miq->vendor_id.'</td>
               <td>'.$miq->vendor_name.'</td>
           </tr>
           </tbody>

           </table><br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
        if ($active) {
            $data .= 'MAC Items ';
        } else {
            $data .= 'MIQ Items ';
        }
        $data .= '</label>
               <div class="form-devider"></div>
           </div>
           </div>
           <div class="table-responsive">
           <table class="table table-bordered mg-b-0" id="example1">';

        if ($active) {
            $data .= '<thead>
                   <tr>
                       <th>PR NO.</th>
                       <th>Item Code:</th>
                       <th>HSN</th>
                       <th>Quantity</th>
                       <th>rate</th>
                       <th>Discount </th>
                       <th>Action</th>
                   </tr>
               </thead>
               <tbody >';
            foreach ($inv_miq_item as $item) {
                $data .= '<tr>
                       <td>lll</td>
                       <td>jjj</td>
                       <td>jjj</td>
                       <td>uuuu</td>
                       <td>yuyt</td>
                       <td>ouii</td>
                       <td><a class="badge badge-info" style="font-size: 13px;" href="' . url("inventory/supplier-invoice-item-edit/" . $active . '/' . $item->id) . '"><i class="fas fa-edit"></i> Edit</a></td>
                   </tr>';
            }
            $data .= '</tbody>';
        }

        if (!$active) {
            $data .= '<thead>
                   <tr>
                   <th>Item Code:</th>
                   <th>Lot No</th>
                   <th>Invoice Qty</th>
                   <th>Rate</th>
                   <th>Discount </th>
                   <th>Price </th>
                   <th>Price In Inr </th>
                   <th>Accepted Quantity</th>
                   <th>Work Center</th>
                   <th>Reason</th>
                   <th></th>
                   </tr>
               </thead>
               <tbody >';
            foreach ($inv_miq_item as $item) {
                $data .= '<tr>
                       <td>'.$item->item_code.'</td>
                       <td>'.$item->lot_number.'</td>
                       <td>'.$item->order_qty. $item->unit_name.'</td>
                       <td>'.$item->rate.'</td>
                       <td>'.$item->discount.'</td>
                       <td>'.(($item->rate*$item->order_qty*$item->discount)/100) . $item->currency_code.'</td>
                       <td>'.$item->value_inr.'</td>
                       <td>'.$item->value_inr.'</td>
                       <td>'.$item->value_inr.'</td>
                       <td>'.$item->value_inr.'</td>
                       <td>'.$item->value_inr.'</td>
                   </tr>';
            }
            $data .= '</tbody>';
        }

        $data .= '</table>
       </div>';
        return $data;
    }
}
