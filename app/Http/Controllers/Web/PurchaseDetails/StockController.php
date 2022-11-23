<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\User;
use App\Models\PurchaseDetails\inv_lot_allocation;
use App\Models\PurchaseDetails\inv_miq_item;
use App\Models\PurchaseDetails\inv_stock_to_production;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_purchase_req_item;

class StockController extends Controller
{
    public function __construct()
    {
        $this->User = new User;
        $this->inv_miq_item = new inv_miq_item;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->inv_lot_allocation = new inv_lot_allocation;
        $this->inv_stock_to_production = new inv_stock_to_production;
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
        $data['lot'] =$this->inv_lot_allocation->getLot_Not_In_StockToProduction($condition);
        
        return view('pages.inventory.stock.stock-issue-to-production-add',compact('data'));
    }

    public function issueToProduction(Request $request)
    {
        $validation['lot_id'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            foreach($request->lot_id as $lot_id)
            {
                $lot_data = $this->inv_lot_allocation->get_single_lot1(['inv_lot_allocation.id'=>$lot_id]);
               // print_r(json_encode($lot_data));exit;
                $item_type = $this->get_item_type($lot_data['pr_item_id']);
                if($item_type=="Direct Items")
                {
                    $data['sip_number'] = "SIP2-".$this->po_num_gen(DB::table('inv_stock_to_production')->where('inv_stock_to_production.sip_number', 'LIKE', 'SIP2%')->count(),1); 
                }
                if($item_type=="Indirect Items")
                {
                    $data['sip_number'] = "SIP3-" . $this->po_num_gen(DB::table('inv_stock_to_production')->where('inv_stock_to_production.sip_number', 'LIKE', 'SIP3%')->count(),1); 
                }
                $mac_qty = $this->get_mac_qty($lot_data['si_invoice_item_id']);
                if($mac_qty)
                $data['quantity']=$mac_qty;
                else
                $data['quantity']=$lot_data['qty_accepted'];
                $data['lot_id']= $lot_data['id'];
                $data['pr_item_id']= $lot_data['pr_item_id'];
                $data['status']= 1;
                $data['created_at']= date('Y-m-d H:i:s');
                $data['updated_at']= date('Y-m-d H:i:s');
                $add[] = $this->inv_stock_to_production->insert_data($data);

            }
            if(count($add)==count($request->lot_id))
            $request->session()->flash('success', "You have successfully added Stock issue to production !");
            else
            $request->session()->flash('error', "You have failed to add Stock issue to production !");
            return redirect("inventory/Stock/ToProduction");
        }

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

    public function StockToProductionDelete(Request $request,$id)
    {
        $this->inv_stock_to_production->deleteData(['id' => $id]);
        $request->session()->flash('success', "You have successfully deleted Stock issue to production !");
        return redirect("inventory/Stock/ToProduction");
    }
    

    public function StockFromProduction()
    {
        return view('pages.inventory.stock.stock-from-production');
    }
    public function StockFromProductionAdd(Request $request)
    {
        if($request->id){
            $edit=1;
            return view('pages.inventory.stock.stock-from-production-add',compact('edit'));
        }
        else
        return view('pages.inventory.stock.stock-from-production-add');
    }
    public function StockFromProductionAddItem()
    {
        return view('pages.inventory.stock.stock-from-production-item');
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
