<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;


use DB;
class InventoryreportController extends Controller
{
    public function get_data(Request $request)
    {
        $condition = [];
        if ($request->item_code) {
            $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
        }
        // $item_details=DB::table('inventory_rawmaterial')
        // ->join('inv_purchase_req_item','inventory_rawmaterial.id','=','inv_purchase_req_item.requisition_item_id')
        // ->join('inv_final_purchase_order_item','inventory_rawmaterial.id','=','inv_final_purchase_order_item.item_id')
        // ->join('inv_stock_transaction','inventory_rawmaterial.id','=','inv_stock_transaction.item_id')
        // ->join('inv_stock_management','inventory_rawmaterial.id','=','inv_stock_management.item_id')
        // ->select('inventory_rawmaterial.item_name','inventory_rawmaterial.discription','inventory_rawmaterial.item_code','inv_final_purchase_order_item.order_qty',
        // 'inv_stock_transaction.lot_id','inv_stock_management.id')
        // ->get()

        $item_details=DB::table('inv_purchase_req_master_item_rel')
        ->join('inv_purchase_req_item','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
        ->join('inventory_rawmaterial','inv_purchase_req_item.Item_code','=','inventory_rawmaterial.id')
        ->join('inv_stock_to_production_item','inventory_rawmaterial.id','=','inv_stock_to_production_item.material_id')
        ->join('inv_stock_to_production_item_rel','inv_stock_to_production_item.id','=','inv_stock_to_production_item_rel.item')
        ->join('inv_stock_to_production','inv_stock_to_production_item_rel.master','=','inv_stock_to_production.id')
        ->join('inv_stock_management','inventory_rawmaterial.id','=','inv_stock_management.item_id')
        ->select('inventory_rawmaterial.*','inv_purchase_req_item.supplier','inv_stock_to_production.sip_number',
        'inv_stock_to_production.work_centre','inv_stock_to_production.qty_to_production','inv_stock_management.lot_id')
        ->where($condition)
        ->get()
        ->toArray();
        
        return view('pages/inventory/gst/inventory-trans-report',compact('item_details'));
    }

    public function get_lot_no($id)
    {
        if(!empty($id)){
        $lot_id=DB::table('inv_lot_allocation')
        ->where('id','=',$id)
        ->first();
        
        return $lot_id;
        }else{
            return 0;
        }
    }
    public function get_suplier($id)
    {
        if(!empty($id)){
            $supplier=DB::table('inv_supplier')
            ->where('id','=',$id)
            ->first();
            return $supplier;
            }else{
                return 0;
            }  
    }
   
    public function get_workcenter($id)
    {
        if(!empty($id)){
            $work_center=DB::table('work_centre')
            ->where('id','=',$id)
            ->pluck('description')[0];
            return $work_center;
            }else{
                return 0;
            }  
    }
    
}
