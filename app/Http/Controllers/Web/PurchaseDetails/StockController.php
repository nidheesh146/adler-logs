<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\User;
use App\Models\batchcard;
use App\Models\assembly_batchcards;
use App\Models\batchcard_material;
use App\Models\PurchaseDetails\inv_lot_allocation;
use App\Models\PurchaseDetails\inv_miq_item;
use App\Models\PurchaseDetails\inv_mac_item;
use App\Models\PurchaseDetails\inv_stock_to_production;
use App\Models\PurchaseDetails\inv_stock_from_production;
use App\Models\PurchaseDetails\inv_stock_transfer_order;
use App\Models\PurchaseDetails\inventory_rawmaterial;
use App\Models\PurchaseDetails\inv_purchase_req_item;
use App\Models\PurchaseDetails\inv_stock_to_production_item;
use App\Models\PurchaseDetails\inv_batchcard_qty_updation_request;

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
        $this->inv_stock_to_production_item = new inv_stock_to_production_item;
        $this->batchcard_material = new batchcard_material;
        $this->inv_batchcard_qty_updation_request = new inv_batchcard_qty_updation_request;
    }
    
    public function StockToProductionAdd(Request $request)
    {   
        return view('pages.inventory.stock.stock-issue-to-production-add');
    }

    public function findBatchCard(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Batchcard is not valid'], 500); 
        }
        $condition[] = ['batchcard_batchcard.batch_no','like','%'.strtoupper($request->q).'%'];
        $data = $this->batchcard->get_all_batchcards($condition);
       // $data  = $this->inventory_rawmaterial->getItems($condition);
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Batchcard is not valid'], 500); 
        }
    }

    public function fetchBatchCard_items(Request $request)
    {
        $batchcards = batchcard_material::select('inventory_rawmaterial.item_code','inventory_rawmaterial.id as rawmaterial_id')
                ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=', 'batchcard_materials.batchcard_id')
                ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','batchcard_materials.item_id')
                ->where('batchcard_materials.batchcard_id','=',$request->batchcard_id)
                ->get();
        return $batchcards;
        
    }
    public function fetchLotcard(Request $request)
    {
        $data =[];
        $sip_item = inv_stock_to_production_item::select('inv_stock_to_production_item.*','inv_unit.unit_name')
                                        ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_stock_to_production_item.material_id')
                                        ->leftJoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                        ->where('batchcard_id','=',$request->batchcard_id)
                                        ->where('material_id','=',$request->item_id)
                                        ->first();
        $sip_master = DB::table('inv_stock_to_production_item_rel')->where('item', $sip_item['id'])->value('master');
        $lot = inv_stock_to_production:: select('inv_lot_allocation.id as lot_id','inv_lot_allocation.lot_number','inv_mac_item.accepted_quantity','inv_mac_item.available_qty')
                                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.id','=','inv_stock_to_production.lot_id')
                                    ->leftJoin('inv_miq_item','inv_miq_item.lot_number','=','inv_lot_allocation.lot_number')
                                    ->leftJoin('inv_mac_item','inv_mac_item.miq_item_id','=','inv_miq_item.id')
                                    ->where('inv_stock_to_production.id','=',$sip_master)
                                    ->first();
        $data['batch_qty']= $sip_item['qty_to_production'];
        $data['lot_id'] = $lot['lot_id'];
        $data['lot_number'] =$lot['lot_number'];
        $data['accepted_quantity'] =$lot['accepted_quantity'];
        $data['available_qty'] =$lot['available_qty'];
        $data['unit_name'] = $sip_item['unit_name'];
         return $data;

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
        $delete = $this->inv_stock_to_production->update_data(['id' => $id],['status'=>0]);
        //$delete = $this->inv_stock_to_production->deleteData(['id' => $id]);
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
        return view('pages.inventory.stock.stock-from-production', compact('data'));
    }
    public function StockFromProductionAdd(Request $request)
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
        // $data['items'] =$this->inv_stock_to_production->getSIP_Not_In_StockFromProduction($condition);
        
        return view('pages.inventory.stock.stock-from-production-add');
    }
    

    public function StockFromProductionDelete(Request $request,$id)
    {
        //$delete = $this->inv_stock_from_production->deleteData(['id' => $id]);
        $delete = $this->inv_stock_from_production->update_data(['id' => $id],['status'=>0]);
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







    public function DirectSIP()
    {
        return view('pages.inventory.stock.direct-sip');
    }
    public function fetchBatchCards(Request $request)
    {
        $batchmaterials_in_sip_item = inv_stock_to_production_item::pluck('batchcard_material_id')->toArray();
        $arr=array_filter($batchmaterials_in_sip_item);
       // ->whereNotIn('batchcard_materials.id',$batchmaterials_in_sip_item)
       
        $batchcards = batchcard_material::select('batchcard_materials.id as batchcard_material_id','batchcard_batchcard.id as batchcard_id','batchcard_batchcard.batch_no','batchcard_materials.item_id','batchcard_materials.quantity as material_qty',
        'batchcard_batchcard.quantity as sku_quantity','product_product.sku_code','inv_unit.unit_name')
                ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=', 'batchcard_materials.batchcard_id')
                ->leftJoin('product_product','batchcard_batchcard.product_id','=', 'product_product.id')
                ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','batchcard_materials.item_id')
                ->leftJoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                ->whereNotIn('batchcard_materials.id',$arr)
                ->where('batchcard_materials.item_id','=',$request->item_id)
                ->get();
        
        $lotcards = inv_lot_allocation::select('inv_lot_allocation.id as lot_id','inv_lot_allocation.lot_number','inv_mac_item.available_qty','inv_mac_item.accepted_quantity',
        'inv_unit.unit_name','inv_mac_item.id as mac_item_id')
                            ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                            ->leftJoin('inv_miq_item','inv_miq_item.lot_number','=','inv_lot_allocation.lot_number')
                            ->leftJoin('inv_mac_item','inv_mac_item.miq_item_id','=','inv_miq_item.id')
                            ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                            ->leftJoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                            ->where('inventory_rawmaterial.id','=', $request->item_id)
                            ->where('inv_mac_item.available_qty','!=',0)
                            ->orderBy('inv_lot_allocation.id','asc')
                            ->get();
        $itemcode = inventory_rawmaterial::where('id','=',$request->item_id)->pluck('item_code')->first();
        if(count($batchcards)>0)
        {
            $data['batchcards'] = "<div style='color:#3366ff;font: size 15px;'><i class='typcn typcn-tabs-outline' style='font-size:21px;'></i>&nbsp;<strong>BatchCards</strong></div>
                                    <div class='row'>
                                            <div class='table-responsive'>
                                                <table class='table table-bordered mg-b-0' id='example1'>
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>BatchCard</th>
                                                        <th>SKU Code</th>
                                                        <th>SKU Quantity</th>
                                                        <th>Item(".$itemcode.") Quantity Required</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>";
            $i=1;
            foreach($batchcards as $card)
            {
                $data['batchcards'] .="<tr>
                                            <th><input type='checkbox' class='batchcard-checkbox'  name='batchcard[]' value='".$card->batchcard_id."' batchqty='".$card->material_qty."'></th>
                                            <th>".$card->batch_no."</th>
                                            <th>".$card->sku_code."</th>
                                            <th>".$card->sku_quantity."</th>
                                            <th class='qty'>
                                                <span>".$card->material_qty." ".$card->unit_name."</span>
                                                <span class='' style='float:right;'>
                                                    <a href='#' data-toggle='modal' data-target='#requestModal'  class='badge badge-pill badge-primary request-btn' id='request-btn' style='border:none;display:none;'batchid='".$card->batchcard_id."'  batchno='".$card->batch_no."' skucode='".$card->sku_code."' skuqty='".$card->sku_quantity."' unit='".$card->unit_name."' batchqty='".$card->material_qty."' batchmaterialId='".$card->batchcard_material_id."'>
                                                    Quantity Update Request
                                                    </a>
                                                </span>
                                            </th>
                                        </tr> ";                                               
                $i++;
            }
            $data['batchcards'] .="<tbody></table></div></div><br/>";
        }
        if(count($lotcards)>0)
        {
            $data['lotcards'] = "<div style='color:#3366ff;font: size 15px;'><i class='fas fa-address-card' style='font-size:21px;'></i>&nbsp;<strong>LotCards</strong></div>
                                    <div class='row'>
                                            <div class='table-responsive'>
                                                <table class='table table-bordered mg-b-0' id='example1' >
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>LotCard</th>
                                                        <th>Item Code</th>
                                                        <th>Quantity</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>";
            $i=1;                                       
            foreach($lotcards as $card)
            {
                $data['lotcards'] .="<tr>
                                            <td><input type='radio' required class='lot-radio' name='lot_id' value='".$card->lot_id."' lotqty='".$card->available_qty."' lotno='".$card->lot_number."' macItemid=".$card->mac_item_id."></td>
                                            <th>".$card->lot_number."</th>
                                            <th>".$itemcode."</th>
                                            <th>".$card->available_qty." ".$card->unit_name."</th>
                                        </tr> ";                                               
                $i++;
            }
            $data['lotcards'] .="<tbody></table></div></div>";
        }
                                            
        return $data;


    }

    public function addDirectSIP(Request $request)
    {
        $validation['lot_id'] = ['required'];
        $validation['batchcard'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            $lot_data= $this->inv_lot_allocation->get_single_lot1(['inv_lot_allocation.id'=>$request->lot_id]);
            $data['sip_number'] = "SIP2-".$this->po_num_gen(DB::table('inv_stock_to_production')->where('inv_stock_to_production.sip_number', 'LIKE', 'SIP2%')->count(),1); 
            $data['lot_id'] = $request->lot_id;
            //$data['qty_to_production'] = $lot_data['available_qty'];
            $data['type'] = 2;
            $data['status'] = 1;
            $data['created_at']= date('Y-m-d H:i:s');
            $data['updated_at']= date('Y-m-d H:i:s');
        
            $mac_item = inv_mac_item::leftJoin('inv_miq_item','inv_miq_item.id','=','inv_mac_item.miq_item_id')
                                 ->leftJoin('inv_lot_allocation','inv_lot_allocation.lot_number','=','inv_miq_item.lot_number')
                                 ->where('inv_lot_allocation.id','=',$request->lot_id)
                                 ->select('inv_mac_item.id','inv_mac_item.item_id','inv_mac_item.accepted_quantity')->first();
            $inv_mac_item = inv_mac_item::where('id',$mac_item['id'])->first();
            $inv_mac_item->available_qty = 0;
            $inv_mac_item->save();
            $data['pr_item_id'] = $inv_mac_item['item_id'];
            $data['qty_to_production'] = $inv_mac_item['accepted_quantity'];
            $sip_master = $this->inv_stock_to_production->insert_data($data);
            if($sip_master)
            { 
                foreach($request->batchcard as $batchcard)
                {
                    $batchdata = batchcard_material::where('batchcard_id','=',$batchcard)
                                                    ->where('item_id','=',$request->item_code)
                                                    ->first();
                    $batch['batchcard_id']=$batchcard;
                    $batch['batchcard_material_id']=$batchdata['id'];
                    $batch['material_id']=$request->item_code;
                    $batch['qty_to_production']=$batchdata['quantity'];
                    $sip_item = $this->inv_stock_to_production_item->insert_data($batch);

                    $rel['master'] = $sip_master;
                    $rel['item'] = $sip_item;
                    DB::table('inv_stock_to_production_item_rel')->insert($rel);
                }
               
                $request->session()->flash('success', "You have successfully added Stock issue to production !");
            }
            else
            {
                $request->session()->flash('error', "You have failed to add Stock issue to production !");
            }
            return redirect("inventory/Stock/ToProduction");

        }
        
    }
    public function IndirectSIP()
    {
        return view('pages.inventory.stock.indirect-sip'); 
    }
    public function addIndirectSIP(Request $request)
    {
        
        $validation['item_code'] = ['required'];
        $validation['qty_to_production'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            $data['sip_number'] = "SIP3-".$this->po_num_gen(DB::table('inv_stock_to_production')->where('inv_stock_to_production.sip_number', 'LIKE', 'SIP3%')->count(),1); 
            //$data['lot_id'] = $request->lot_id;
            //$data['qty_to_production'] = $lot_data['available_qty'];
            $data['type'] = 3;
            $data['status'] = 1;
            $data['created_by']= config('user')['user_id'];
            $data['created_at']= date('Y-m-d H:i:s');
            $data['updated_at']= date('Y-m-d H:i:s');
            $data['qty_to_production'] = $request->qty_to_production;
            $mac_item = inv_mac_item::where('id',$request->mac_item_id)->first();
            $data['pr_item_id'] = $mac_item['item_id'];
            $sip_master = $this->inv_stock_to_production->insert_data($data);
            if($sip_master)
            {
                $item['mac_item_id'] = $request->mac_item_id;
                $item['material_id']=$request->item_code;
                $item['qty_to_production'] = $request->qty_to_production;
                $sip_item = $this->inv_stock_to_production_item->insert_data($item);

                $inv_mac_item = inv_mac_item::where('id',$request->mac_item_id)->first();
                $inv_mac_item->available_qty = $inv_mac_item->available_qty - $request->qty_to_production;
                $inv_mac_item->save();

                $rel['master'] = $sip_master;
                $rel['item'] = $sip_item;
                DB::table('inv_stock_to_production_item_rel')->insert($rel);
                $request->session()->flash('success', "You have successfully added Stock issue to production !");
            }
            else
            {
                $request->session()->flash('error', "You have failed to add Stock issue to production !");
            }
            return redirect("inventory/Stock/ToProduction");
        }
        if($validator->errors()->all())
        {
            $request->session()->flash('error', "Stock transfer order updation failed!");
            return redirect("inventory/ToProduction/Indirect");
        }
    }

    public function itemMacDetails(Request $request)
    {
        $mac_details = inv_mac_item::select('inv_mac.mac_number','inv_mac_item.accepted_quantity','inv_mac_item.available_qty','inv_unit.unit_name','inv_mac_item.id as mac_item_id')
                        ->leftJoin('inv_mac_item_rel','inv_mac_item_rel.item','=','inv_mac_item.id')
                        ->leftJoin('inv_mac','inv_mac.id','=','inv_mac_item_rel.master')
                        ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_mac_item.item_id')
                        ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                        ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                        ->where('inv_purchase_req_item.Item_code','=', $request->item_id)
                        ->where('inventory_rawmaterial.item_type_id','!=',2 )
                        ->where('inv_mac_item.available_qty','!=',0 )
                        ->first();
        return $mac_details;
        
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
        foreach($data['sip'] as $sip)
        {
            $sip['items']=inv_stock_to_production_item::select('batchcard_batchcard.batch_no','inv_stock_to_production_item.qty_to_production')
                                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','inv_stock_to_production_item.batchcard_id')
                                        ->leftJoin('inv_stock_to_production_item_rel','inv_stock_to_production_item_rel.item','=','inv_stock_to_production_item.id')
                                        ->where('inv_stock_to_production_item_rel.master','=',$sip['id'])
                                        ->get();
        }
        return view('pages.inventory.stock.stock-issue-to-production',compact('data'));
    }

    public function returnFromProduction(Request $request)
    {
       // echo "kk";exit;
        $validation['batch_card'] = ['required'];
        $validation['item_id'] = ['required'];
        $validation['qty_return'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all())
        {
            // foreach($request->sip_id as $sip_id)
            // {
            //     //$lot_data = $this->inv_lot_allocation->get_single_lot1(['inv_lot_allocation.id'=>$lot_id]);
            //     $sip_data = inv_stock_to_production::select('*')->where('id','=',$sip_id)->first();
            //    // print_r(json_encode($lot_data));exit;
            //     $item_type = $this->get_item_type($sip_data['pr_item_id']);
            //     if($item_type=="Direct Items")
            //     {
            //         $data['sir_number'] = "SIR2-".$this->po_num_gen(DB::table('inv_stock_from_production')->where('inv_stock_from_production.sir_number', 'LIKE', 'SIR2%')->count(),1); 
            //     }
            //     if($item_type=="Indirect Items")
            //     {
            //         $data['sir_number'] = "SIR3-" . $this->po_num_gen(DB::table('inv_stock_from_production')->where('inv_stock_from_production.sir_number', 'LIKE', 'SIR3%')->count(),1); 
            //     }
            //     // $mac_qty = $this->get_mac_qty($mac_item_data['invoice_item_id']);
            //     // if($mac_qty)
            //     // $data['quantity']=$mac_qty;
            //     // else
            //     $data['quantity']=$sip_data['quantity'];
            //     $data['lot_id']= $sip_data['lot_id'];
            //     $data['pr_item_id']= $sip_data['pr_item_id'];
            //     $data['sip_id']=$sip_data['id'];
            //     $data['status']= 1;
            //     $data['created_at']= date('Y-m-d H:i:s');
            //     $data['updated_at']= date('Y-m-d H:i:s');
            //     $add[] = $this->inv_stock_from_production->insert_data($data);

            // }
            // if(count($add)==count($request->sip_id))
            $item_type=$this->get_item_type($request->item_id);
            if($item_type=="Direct Items")
            {
                $data['sir_number'] = "SIR2-".$this->po_num_gen(DB::table('inv_stock_from_production')->where('inv_stock_from_production.sir_number', 'LIKE', 'SIR2%')->count(),1); 
            }
            if($item_type=="Indirect Items")
            {
                $data['sir_number'] = "SIR3-" . $this->po_num_gen(DB::table('inv_stock_from_production')->where('inv_stock_from_production.sir_number', 'LIKE', 'SIR3%')->count(),1); 
            }
            $data['lot_id']= $request->lotcard_id;
            $data['item_id'] = $request->item_id;
            $data['batch_id'] = $request->batch_card;
            $data['qty_to_return']= $request->qty_return;
            $data['status']= 1;
            $data['created_at']= date('Y-m-d H:i:s');
            $data['updated_at']= date('Y-m-d H:i:s');
           
            
            $mac_item = inv_lot_allocation::select('inv_mac_item.id as mac_item_id','inv_mac_item.available_qty','inv_lot_allocation.pr_item_id','inv_lot_allocation.lot_number')
                                    ->leftJoin('inv_miq_item','inv_miq_item.lot_number','=','inv_lot_allocation.lot_number')
                                    ->leftJoin('inv_mac_item','inv_mac_item.miq_item_id','=','inv_miq_item.id')
                                    ->where('inv_lot_allocation.id','=',$request->lotcard_id)
                                    ->first();

            $data['pr_item_id'] =  $mac_item['pr_item_id'];
            $data['mac_item_id'] = $mac_item['mac_item_id'];
            $add =$this->inv_stock_from_production->insert_data($data);

            $update_qty = $mac_item['available_qty']+$request->qty_return;
            $qty_update =$this->inv_mac_item->update_data(['inv_mac_item.id'=>$mac_item['mac_item_id']],['inv_mac_item.available_qty'=>$update_qty]);

            if($add && $qty_update)
            $request->session()->flash('success', "You have successfully added Stock return from production !");
            else
            $request->session()->flash('error', "You have failed to add Stock return from production !");
            return redirect("inventory/Stock/FromProduction");
        }
        if($validator->errors()->all())
        {
            $request->session()->flash('error', "You have failed to add Stock return from production !");
            return redirect("inventory/Stock/FromProduction-add");
        }

    }

    public function quantityUpdationRequest(Request $request)
    {
        $is_exist = inv_batchcard_qty_updation_request::where('item_id','=',$request->item_id)
                                    ->where('batchcard_id','=',$request->batch_id)
                                    ->where('sku_qty_to_be_update','=', $request->request_sku_qty)
                                    ->where('material_qty_to_be_update','=', $request->request_qty)
                                    ->where('status','!=',0)
                                    ->exists();
        if($is_exist)
        {
            $request->session()->flash('error', "The request already exist !");
            return redirect()->back();
        }   
        {    
            $data['item_id'] = $request->item_id;
            $data['batchcard_id'] = $request->batch_id;
            $data['batchcard_material_id'] = $request->batchcard_material_id;
            $data['sku_qty_to_be_update']= $request->request_sku_qty;
            $data['material_qty_to_be_update']= $request->request_qty;
            $data['status']= 2;
            $data['created_at']= date('Y-m-d H:i:s');
            $data['updated_at']= date('Y-m-d H:i:s');
            $add =$this->inv_batchcard_qty_updation_request->insert_data($data);
            $request->session()->flash('success', "You have successfully send a quantity updation request !");
            return redirect()->back();
        }
    }

   

}
