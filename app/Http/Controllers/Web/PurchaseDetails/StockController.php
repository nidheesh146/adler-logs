<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\User;
use App\Models\batchcard;
use App\Models\assembly_batchcards;
use App\Models\PurchaseDetails\inv_lot_allocation;
use App\Models\PurchaseDetails\inv_miq_item;
use App\Models\PurchaseDetails\inv_mac_item;
use App\Models\PurchaseDetails\inv_stock_to_production;
use App\Models\PurchaseDetails\inv_stock_from_production;
use App\Models\PurchaseDetails\inv_stock_transfer_order;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_purchase_req_item;

class StockController extends Controller
{
    public function __construct()
    {
        $this->User = new User;
        $this->batchcard = new batchcard;
        $this->assembly_batchcards = new assembly_batchcards;
        $this->inv_miq_item = new inv_miq_item;
        $this->inv_mac_item = new inv_mac_item;
        $this->inv_purchase_req_item = new inv_purchase_req_item;
        $this->inventory_rawmaterial = new inventory_rawmaterial;
        $this->inv_lot_allocation = new inv_lot_allocation;
        $this->inv_stock_to_production = new inv_stock_to_production;
        $this->inv_stock_from_production = new inv_stock_from_production;
        $this->inv_stock_transfer_order = new inv_stock_transfer_order;
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
        // $condition = [];
        // if($request)
        // {
        //     if ($request->lot_number) {
        //         $condition[] = ['inv_lot_allocation.lot_number','like', '%' . $request->lot_number . '%'];
        //     }
        //     if ($request->item_code) {
        //         $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
        //     }
        //     if($request->supplier)
        //     {
        //         $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $request->supplier . '%'];
        //     }
           
        // }
        //$data['items'] =$this->inv_mac_item->getMAC_items_Not_In_StockToProduction($condition);
        
        return view('pages.inventory.stock.stock-issue-to-production-add');
    }

    public function findBatchCard(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Batchcard is not valid'], 500); 
        }
        $condition[] = ['batchcard_batchcard.batch_no','like','%'.strtoupper($request->q).'%'];
        $data = $this->batchcard->get_batchcard_not_in_sip($condition);
       // $data  = $this->inventory_rawmaterial->getItems($condition);
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Batchcard is not valid'], 500); 
        }
    }

    public function fetchBatchCard_info(Request $request)
    {
        $batchcard_id = $request->batchcard_id;
        $data['batchcard'] = $this->batchcard->get_batchcard(['batchcard_batchcard.id'=>$request->batchcard_id]);
        if($data['batchcard']['is_assemble']==1)
        {
            $primary_sku_batchcards = assembly_batchcards::select('assembly_batchcards.id','batchcard_batchcard.id as batch_id','batchcard_batchcard.batch_no','assembly_batchcards.quantity','inventory_rawmaterial.item_code')
                                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','assembly_batchcards.primary_sku_batchcard_id')
                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','batchcard_batchcard.input_material')
                                    ->where('assembly_batchcards.main_batchcard_id','=',$request->batchcard_id)
                                    ->get();
            //return $primary_sku_batchcards;
            $data['batch'] = '&nbsp;
                            <label>BatchCard</label>
                            <table  class="table table-bordered mg-b-0" id="example1">
                            <tr>
                            <th>#</th>
                                <th>Batch Number</th>
                                <th>Qty</th>
                            </tr>
                            <tbody class="data-bindings1">';
            foreach($primary_sku_batchcards as $batch)
            {
                $data['batch'] .= '<tr>
                        <td><input type="radio" id="batch_radio" name="assemble_item_id"  value="'.$batch['batch_id'].'" data-batchno="'.$batch['batch_no'].'" data-qty="'.$batch['quantity'].'" ></td>
                        <td>'.$batch['batch_no'].'</td>
                    
                        <td>'.$batch['quantity'].'</td>
                        </tr>';
            }
            $data['batch'] .='<tbody>
            </table>';
        
            
        }
        else
        {
            $lotcard = $this->inv_lot_allocation->getLots_sip(['inv_purchase_req_item.item_code'=>$data['batchcard']['input_material']]);
            $data['lot'] = '&nbsp;
                            <label>Lotcard</label>
                            <table  class="table table-bordered mg-b-0" id="example1">
                            <tr>
                            <th>#</th>
                                <th>Lot Number</th>
                                <th>Item</th>
                                <th>Qty</th>
                            </tr>
                            <tbody class="data-bindings1">';
            foreach($lotcard as $lot)
            {
                $data['lot'] .= '<tr>
                        <td><input type="radio" id="lot_radio" name="lot_id" lot="'.$lot['lot_number'].'" qty="'.$lot['available_qty'].'" value="'.$lot['id'].'"></td>
                        <td>'.$lot['lot_number'].'</td>
                        <td>'.$lot['item_code'].'</td>
                        <td>'.$lot['available_qty'].'</td>
                        </tr>';
            }
            $data['lot'] .='<tbody>
            </table>';
        }
        return $data;
    }

    public function fetchPrimaryBatchCard_info(Request $request)
    {
        echo "hh";
        $primary_sku_batchcards = assembly_batchcards::select('assembly_batchcards.id','batchcard_batchcard.batch_no','assembly_batchcards.quantity','inventory_rawmaterial.item_code','inventory_rawmaterial.discription')
                                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','assembly_batchcards.primary_sku_batchcard_id')
                                    ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','batchcard_batchcard.input_material')
                                    ->where('assembly_batchcards.primary_sku_batchcard_id','=',$request->batchcard_id)
                                    ->get();
        return $primary_sku_batchcards;
    }
    public function issueToProduction(Request $request)
    {
        $validation['batch_card'] = ['required'];
        //$validation['lot_number'] = ['required'];
        $validation['quantity'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            if($request->raw_material_id)
            {
                $item_type = $this->get_item_type($request->raw_material_id);
            }
            else
            {
                if($request->primary_batch_id)
                {
                    $item_type = batchcard::leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','batchcard_batchcard.input_material')
                                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                                    ->pluck('inv_item_type.type_name')
                                    ->first();
                }
            }
            //echo $item_type;exit;
                $lotcard = inv_lot_allocation::where('id','=',$request->lot_number)->select('pr_item_id','available_qty')->first();
                if($item_type=="Direct Items")
                {
                    $data['sip_number'] = "SIP2-".$this->po_num_gen(DB::table('inv_stock_to_production')->where('inv_stock_to_production.sip_number', 'LIKE', 'SIP2%')->count(),1); 
                }
                if($item_type=="Indirect Items")
                {
                    $data['sip_number'] = "SIP3-" . $this->po_num_gen(DB::table('inv_stock_to_production')->where('inv_stock_to_production.sip_number', 'LIKE', 'SIP3%')->count(),1); 
                }
                $data['batch_no_id']=$request->batch_card;
                $data['qty_to_production']=$request->quantity;
                $data['lot_id']= $request->lot_number;
               
                if(!$request->primary_batch_id)
                {
                    $data['pr_item_id']= $lotcard->pr_item_id;
                }
                else
                {
                    $data['primary_sku_batch_id']= $request->primary_batch_id;
                }
                //$data['lot_qty_received']= $lotcard->qty_accepted;
               // $data['mac_item_id']=$mac_item_data['id'];
                $data['status']= 1;
                $data['created_at']= date('Y-m-d H:i:s');
                $data['updated_at']= date('Y-m-d H:i:s');
                $add= $this->inv_stock_to_production->insert_data($data);
                if(!$request->primary_batch_id)
                {
                $lot_qty['available_qty'] = $lotcard['available_qty']-$request->quantity;
                $update_lot = $this->inv_lot_allocation->updatedata(['inv_lot_allocation.id'=>$request->lot_number],$lot_qty);
                }

            if($add)
            $request->session()->flash('success', "You have successfully added Stock issue to production !");
            else
            $request->session()->flash('error', "You have failed to add Stock issue to production !");
            return redirect("inventory/Stock/ToProduction");
        }
        if($validator->errors()->all())
        {
            $request->session()->flash('error', "Stock issue to production updation failed!");
            return redirect("inventory/Stock/ToProduction-add");
        }

    }
    public function get_primary_batch($primary_sku_batch_id)
    {
        $batch_no = batchcard::where('batchcard_batchcard.id','=',$primary_sku_batch_id)->pluck('batch_no')->first();
        return $batch_no;
    }
    public function get_primary_batch_item($primary_sku_batch_id)
    {
        $item_code = batchcard::leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','batchcard_batchcard.input_material')
                    ->where('batchcard_batchcard.id','=',$primary_sku_batch_id)
                    ->pluck('inventory_rawmaterial.item_code')->first();
        return $item_code;
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
    public function get_item_type($raw_material_id)
    {
        $item_type = inventory_rawmaterial::leftJoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                                            ->where('inventory_rawmaterial.id','=',$raw_material_id)
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
            $data['qty_to_production'] = $request->quantity;
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
        $delete = $this->inv_stock_to_production->deleteData(['id' => $id]);
        if($delete)
        $request->session()->flash('success', "You have successfully deleted Stock issue to production !");
        else
        $request->session()->flash('error', "You have failed to delete Stock issue to production !");
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
                    $data['sir_number'] = "SIR2-".$this->po_num_gen(DB::table('inv_stock_from_production')->where('inv_stock_from_production.sir_number', 'LIKE', 'SIR2%')->count(),1); 
                }
                if($item_type=="Indirect Items")
                {
                    $data['sir_number'] = "SIR3-" . $this->po_num_gen(DB::table('inv_stock_from_production')->where('inv_stock_from_production.sir_number', 'LIKE', 'SIR3%')->count(),1); 
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

    public function StockFromProductionDelete(Request $request,$id)
    {
        $delete = $this->inv_stock_from_production->deleteData(['id' => $id]);
        if($delete)
        $request->session()->flash('success', "You have successfully deleted Stock return from production !");
        else
        $request->session()->flash('error', "You have failed to delete Stock return from production !");
        return redirect("inventory/Stock/FromProduction");
    }
    public function getSingleSIR(Request $request)
    {
        $sir = inv_stock_from_production::select('inv_stock_from_production.*','inventory_rawmaterial.item_code','inv_unit.unit_name')
                                ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_stock_from_production.pr_item_id')
                                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                                ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                ->where('inv_stock_from_production.id','=', $request->sir_id)->first();
        return $sir;
    }
    public function StockFromProductionEdit(Request $request)
    {
        $validation['quantity'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            $data['quantity'] = $request->quantity;
            $data['updated_at'] = date('Y-m-d H:i:s');
            $update = $this->inv_stock_from_production->update_data(['id' => $request->sirId],$data);
            if($update)
            $request->session()->flash('success', "You have successfully updated Stock return from production !");
            else
            $request->session()->flash('error', "Stock return from production updation failed!");
            return redirect("inventory/Stock/FromProduction");
        }
        if($validator->errors()->all())
        {
            $request->session()->flash('error', "Stock return from production updation failed!");
            return redirect("inventory/Stock/FromProduction");
        }
    }

    public function StockTransfer(Request $request)
    {
        $condition = [];
        if($request)
        {
            if ($request->sto_number) {
                $condition[] = ['inv_stock_transfer_order.sto_number','like', '%' . $request->sto_number . '%'];
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
        $data['sto'] =$this->inv_stock_transfer_order->get_all_data($condition);
        return view('pages.inventory.stock.stock-transfer',compact('data'));
    }
    public function StockTransferAdd(Request $request)
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
        $data['items'] =$this->inv_stock_from_production->getSIR_Not_In_StockTransferOrder($condition);
        return view('pages.inventory.stock.stock-transfer-add',compact('data'));
    }
    public function transferOrder(Request $request)
    {
        $validation['sir_id'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            foreach($request->sir_id as $sir_id)
            {
                //$lot_data = $this->inv_lot_allocation->get_single_lot1(['inv_lot_allocation.id'=>$lot_id]);
                $sir_data = inv_stock_from_production::select('*')->where('id','=',$sir_id)->first();
               // print_r(json_encode($lot_data));exit;
                $item_type = $this->get_item_type($sir_data['pr_item_id']);
                if($item_type=="Direct Items")
                {
                    $data['sto_number'] = "STO2-".$this->po_num_gen(DB::table('inv_stock_transfer_order')->where('inv_stock_transfer_order.sto_number', 'LIKE', 'STO2%')->count(),1); 
                }
                if($item_type=="Indirect Items")
                {
                    $data['sto_number'] = "STO3-" . $this->po_num_gen(DB::table('inv_stock_transfer_order')->where('inv_stock_transfer_order.sto_number', 'LIKE', 'STO3%')->count(),1); 
                }
                // $mac_qty = $this->get_mac_qty($mac_item_data['invoice_item_id']);
                // if($mac_qty)
                // $data['quantity']=$mac_qty;
                // else
                $data['quantity']=$sir_data['quantity'];
                $data['lot_id']= $sir_data['lot_id'];
                $data['pr_item_id']= $sir_data['pr_item_id'];
                $data['sir_id']=$sir_data['id'];
                $data['status']= 1;
                $data['created_at']= date('Y-m-d H:i:s');
                $data['updated_at']= date('Y-m-d H:i:s');
                $add[] = $this->inv_stock_transfer_order->insert_data($data);

            }
            if(count($add)==count($request->sir_id))
            $request->session()->flash('success', "You have successfully added Stock Transfer Order !");
            else
            $request->session()->flash('error', "You have failed to add  Stock Transfer Order !");
            return redirect("inventory/Stock/transfer");
        }
        if($validator->errors()->all())
        {
            $request->session()->flash('error', "You have failed to add Stock Transfer Order !");
            return redirect("inventory/Stock/transfer-add");
        }
    }
    public function getSingleSTO(Request $request)
    {
        $sto = inv_stock_transfer_order::select('inv_stock_transfer_order.*','inventory_rawmaterial.item_code','inv_unit.unit_name')
                                ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_stock_transfer_order.pr_item_id')
                                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                                ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                ->where('inv_stock_transfer_order.id','=', $request->sto_id)->first();
        return $sto;
    }
    public function StockTransferEdit(Request $request)
    {
        $validation['quantity'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            $data['quantity'] = $request->quantity;
            $data['updated_at'] = date('Y-m-d H:i:s');
            $update = $this->inv_stock_transfer_order->update_data(['id' => $request->stoId],$data);
            if($update)
            $request->session()->flash('success', "You have successfully updated Stock transfer order !");
            else
            $request->session()->flash('error', "Stock transfer Order updation failed!");
            return redirect("inventory/Stock/transfer");
        }
        if($validator->errors()->all())
        {
            $request->session()->flash('error', "Stock transfer order updation failed!");
            return redirect("inventory/Stock/transfer");
        }
    }
    public function StockTransferDelete(Request $request,$id)
    {
        $delete = $this->inv_stock_transfer_order->deleteData(['id' => $id]);
        if($delete)
        $request->session()->flash('success', "You have successfully deleted Stock transfer Order!");
        else
        $request->session()->flash('error', "You have failed to delete Stock transfer Order !");
        return redirect("inventory/Stock/transfer");
    }

    public function item_batch()
    {
        return view('pages.inventory.stock.item-batch-master');
    }

}
