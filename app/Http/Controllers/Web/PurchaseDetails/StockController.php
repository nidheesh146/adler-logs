<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\User;
use App\Models\PurchaseDetails\inv_lot_allocation;
use App\Models\PurchaseDetails\inv_miq_item;
use App\Models\PurchaseDetails\inv_mac_item;
use App\Models\PurchaseDetails\inv_stock_to_production;
use App\Models\PurchaseDetails\inv_stock_from_production;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_purchase_req_item;

class StockController extends Controller
{
    public function __construct()
    {
        $this->User = new User;
        $this->inv_miq_item = new inv_miq_item;
        $this->inv_mac_item = new inv_mac_item;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->inv_lot_allocation = new inv_lot_allocation;
        $this->inv_stock_to_production = new inv_stock_to_production;
        $this->inv_stock_from_production = new inv_stock_from_production;
    }
    public function StockToProduction(Request $request)
    {
        $condition = [];
        if($request)
        {
            if ($request->sip_number) {
                $condition[] = ['inv_stock_to_production.sip_number','like', '%' . $request->sip_number . '%'];
            }
            if ($request->lot_number) {
                $condition[] = ['inv_lot_allocation.lot_number','like', '%' . $request->lot_number . '%'];
            }
            if ($request->item_code) {
                $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
           
        }
        $data['sip'] =$this->inv_stock_to_production->get_all_data($condition);
        return view('pages.inventory.stock.stock-issue-to-production',compact('data'));
    }
    public function StockToProductionAdd(Request $request)
    {
        $condition = [];
        if($request)
        {
            if ($request->lot_number) {
                $condition[] = ['inv_lot_allocation.lot_number','like', '%' . $request->lot_number . '%'];
            }
            if ($request->item_code) {
                $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
           
        }
        $data['items'] =$this->inv_mac_item->getMAC_items_Not_In_StockToProduction($condition);
        
        return view('pages.inventory.stock.stock-issue-to-production-add',compact('data'));
    }

    public function issueToProduction(Request $request)
    {
        $validation['mac_item_id'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            foreach($request->mac_item_id as $mac_item_id)
            {
                //$lot_data = $this->inv_lot_allocation->get_single_lot1(['inv_lot_allocation.id'=>$lot_id]);
                $mac_item_data = $this->inv_mac_item->get_item(['inv_mac_item.id'=>$mac_item_id]);
               // print_r(json_encode($lot_data));exit;
                $item_type = $this->get_item_type($mac_item_data['requisition_item_id']);
                if($item_type=="Direct Items")
                {
                    $data['sip_number'] = "SIP2-".$this->po_num_gen(DB::table('inv_stock_to_production')->where('inv_stock_to_production.sip_number', 'LIKE', 'SIP2%')->count(),1); 
                }
                if($item_type=="Indirect Items")
                {
                    $data['sip_number'] = "SIP3-" . $this->po_num_gen(DB::table('inv_stock_to_production')->where('inv_stock_to_production.sip_number', 'LIKE', 'SIP3%')->count(),1); 
                }
                $mac_qty = $this->get_mac_qty($mac_item_data['invoice_item_id']);
                if($mac_qty)
                $data['quantity']=$mac_qty;
                else
                $data['quantity']=$mac_item_data['accepted_quantity'];
                $data['lot_id']= $mac_item_data['lot_id'];
                $data['pr_item_id']= $mac_item_data['requisition_item_id'];
                $data['mac_item_id']=$mac_item_data['id'];
                $data['status']= 1;
                $data['created_at']= date('Y-m-d H:i:s');
                $data['updated_at']= date('Y-m-d H:i:s');
                $add[] = $this->inv_stock_to_production->insert_data($data);

            }
            if(count($add)==count($request->mac_item_id))
            $request->session()->flash('success', "You have successfully added Stock issue to production !");
            else
            $request->session()->flash('error', "You have failed to add Stock issue to production !");
            return redirect("inventory/Stock/ToProduction");
        }
        if($validator->errors()->all())
        {
            $request->session()->flash('error', "Stock issue to production updation failed!");
            return redirect("inventory/Stock/FromProduction");
        }

    }
    public function getSingleSIP(Request $request)
    {
        $sip = inv_stock_to_production::select('inv_stock_to_production.*','inventory_rawmaterial.item_code','inv_unit.unit_name')
                                ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_stock_to_production.pr_item_id')
                                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                                ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                ->where('inv_stock_to_production.id','=', $request->sip_id)->first();
        return $sip;
    }

    public function get_mac_qty($invoice_item_id)
    {
        $mac_qty = inv_miq_item::leftJoin('inv_mac_item','inv_mac_item.miq_item_id','=','inv_miq_item.id')
                                ->where('inv_miq_item.invoice_item_id','=',$invoice_item_id)
                                ->pluck('inv_mac_item.accepted_quantity')
                                ->first();
        //if($mac_qty)
        return $mac_qty;
        
    }
    public function get_item_type($pr_item_id)
    {
        $item_type = inv_purchase_req_item::leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                                            ->leftJoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                                            ->where('inv_purchase_req_item.requisition_item_id','=',$pr_item_id)
                                            ->pluck('inv_item_type.type_name')
                                            ->first();
        return $item_type;
    }     
    public function StockToProductionEdit(Request $request)
    {
        $validation['quantity'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            $data['quantity'] = $request->quantity;
            $data['updated_at'] = date('Y-m-d H:i:s');
            $update = $this->inv_stock_to_production->update_data(['id' => $request->sipId],$data);
            if($update)
            $request->session()->flash('success', "You have successfully updated Stock issue to production !");
            else
            $request->session()->flash('error', "Stock issue to production updation failed!");
            return redirect("inventory/Stock/ToProduction");
        }
        if($validator->errors()->all())
        {
            $request->session()->flash('error', "Stock issue to production updation failed!");
            return redirect("inventory/Stock/ToProduction");
        }
    }                  

    public function StockToProductionDelete(Request $request,$id)
    {
        $this->inv_stock_to_production->deleteData(['id' => $id]);
        $request->session()->flash('success', "You have successfully deleted Stock issue to production !");
        return redirect("inventory/Stock/ToProduction");
    }
    

    public function StockFromProduction(Request $request)
    {
       
        $condition = [];
        if($request)
        {
            if ($request->sir_number) {
                $condition[] = ['inv_stock_from_production.sir_number','like', '%' . $request->sir_number . '%'];
            }
            if ($request->lot_number) {
                $condition[] = ['inv_lot_allocation.lot_number','like', '%' . $request->lot_number . '%'];
            }
            if ($request->item_code) {
                $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
           
        }
        $data['sir'] =$this->inv_stock_from_production->get_all_data($condition);
        return view('pages.inventory.stock.stock-from-production',compact('data'));
    }
    public function StockFromProductionAdd(Request $request)
    {
        $condition = [];
        if($request)
        {
            if ($request->lot_number) {
                $condition[] = ['inv_lot_allocation.lot_number','like', '%' . $request->lot_number . '%'];
            }
            if ($request->item_code) {
                $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
            }
            if($request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
            }
           
        }
        $data['items'] =$this->inv_stock_to_production->getSIP_Not_In_StockFromProduction($condition);
        
        return view('pages.inventory.stock.stock-from-production-add',compact('data'));
    }
    public function returnFromProduction(Request $request)
    {
        $validation['sip_id'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            foreach($request->sip_id as $sip_id)
            {
                //$lot_data = $this->inv_lot_allocation->get_single_lot1(['inv_lot_allocation.id'=>$lot_id]);
                $sip_data = inv_stock_to_production::select('*')->where('id','=',$sip_id)->first();
               // print_r(json_encode($lot_data));exit;
                $item_type = $this->get_item_type($sip_data['pr_item_id']);
                if($item_type=="Direct Items")
                {
                    $data['sir_number'] = "SIR2-".$this->po_num_gen(DB::table('inv_stock_from_production')->where('inv_stock_from_production.sir_number', 'LIKE', 'SRP2%')->count(),1); 
                }
                if($item_type=="Indirect Items")
                {
                    $data['sir_number'] = "SIR3-" . $this->po_num_gen(DB::table('inv_stock_from_production')->where('inv_stock_from_production.sir_number', 'LIKE', 'SRP3%')->count(),1); 
                }
                // $mac_qty = $this->get_mac_qty($mac_item_data['invoice_item_id']);
                // if($mac_qty)
                // $data['quantity']=$mac_qty;
                // else
                $data['quantity']=$sip_data['quantity'];
                $data['lot_id']= $sip_data['lot_id'];
                $data['pr_item_id']= $sip_data['pr_item_id'];
                $data['sip_id']=$sip_data['id'];
                $data['status']= 1;
                $data['created_at']= date('Y-m-d H:i:s');
                $data['updated_at']= date('Y-m-d H:i:s');
                $add[] = $this->inv_stock_from_production->insert_data($data);

            }
            if(count($add)==count($request->sip_id))
            $request->session()->flash('success', "You have successfully added Stock return from production !");
            else
            $request->session()->flash('error', "You have failed to add Stock return from production !");
            return redirect("inventory/Stock/FromProduction");
        }
        if($validator->errors()->all())
        {
            $request->session()->flash('error', "You have failed to add Stock return from production !");
            return redirect("inventory/Stock/FromProduction");
        }

    }

    public function StockTransfer()
    {
        return view('pages.inventory.stock.stock-transfer');
    }
    public function StockTransferAdd(Request $request)
    {
        if($request->id){
            $edit=1;
            return view('pages.inventory.stock.stock-transfer-add',compact('edit'));
        }
        else
        return view('pages.inventory.stock.stock-transfer-add');
    }
    public function StockTransferAddItem()
    {
        return view('pages.inventory.stock.stock-transfer-item');
    }
}
