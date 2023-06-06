<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;

use DB;
class InventoryreportController extends Controller
{
    public function get_data()
    {
        $item_details=DB::table('inventory_rawmaterial')
        ->join('inv_purchase_req_item','inventory_rawmaterial.id','=','inv_purchase_req_item.requisition_item_id')
        ->join('inv_final_purchase_order_item','inventory_rawmaterial.id','=','inv_final_purchase_order_item.item_id')
        ->join('inv_stock_transaction','inventory_rawmaterial.id','=','inv_stock_transaction.item_id')
        ->join('inv_stock_management','inventory_rawmaterial.id','=','inv_stock_management.item_id')
        ->select('inventory_rawmaterial.item_name','inventory_rawmaterial.discription','inventory_rawmaterial.item_code','inv_final_purchase_order_item.order_qty',
        'inv_stock_transaction.lot_id','inv_stock_management.id')
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
            ->pluck('vendor_name')[0];
            return $supplier;
            }else{
                return 0;
            }  
    }
    public function get_sip($id)
    {
        if(!empty($id)){
            $sip_no=DB::table('inv_stock_to_production')
            ->where('stock_id','=',$id)
            ->first();
            return $sip_no;
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
