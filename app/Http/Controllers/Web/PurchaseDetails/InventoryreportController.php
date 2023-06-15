<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryTransactionExport;

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

        // $item_details=DB::table('inv_purchase_req_master_item_rel')
        // ->join('inv_purchase_req_item','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
        // ->join('inventory_rawmaterial','inv_purchase_req_item.Item_code','=','inventory_rawmaterial.id')
        // ->join('inv_stock_to_production_item','inventory_rawmaterial.id','=','inv_stock_to_production_item.material_id')
        // ->join('inv_stock_to_production_item_rel','inv_stock_to_production_item.id','=','inv_stock_to_production_item_rel.item')
        // ->join('inv_stock_to_production','inv_stock_to_production_item_rel.master','=','inv_stock_to_production.id')
        // ->join('inv_stock_management','inventory_rawmaterial.id','=','inv_stock_management.item_id')
        // ->select('inventory_rawmaterial.*','inv_purchase_req_item.supplier','inv_stock_to_production.sip_number',
        // 'inv_stock_to_production.work_centre','inv_stock_to_production.qty_to_production','inv_stock_management.lot_id')
        // ->where($condition)
        // ->get()
        // ->toArray();

        $item_details=DB::table('inv_stock_to_production_item')
                                ->select('inv_lot_allocation.lot_number','inv_stock_to_production.sip_number','work_centre.centre_code','work_centre.description as workcentre_description',
                                'inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inventory_rawmaterial.discription','inv_stock_to_production.qty_to_production',
                                'inv_unit.unit_name','inv_supplier.vendor_name','inv_supplier.vendor_id')
                                ->leftJoin('inv_stock_to_production_item_rel','inv_stock_to_production_item_rel.item','=','inv_stock_to_production_item.id')
                                ->leftJoin('inv_stock_to_production','inv_stock_to_production.id','=','inv_stock_to_production_item_rel.master')
                                ->leftJoin('inv_lot_allocation','inv_lot_allocation.id','=','inv_stock_to_production.lot_id')
                                ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_stock_to_production_item.material_id')
                                ->leftJoin('work_centre','work_centre.id','=','inv_stock_to_production.work_centre')
                                ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                ->leftJoin('inv_supplier','inv_supplier.id','=','inv_lot_allocation.supplier_id')
                                ->where($condition)
                                ->distinct('inv_stock_to_production_item.id')
                                ->orderBy('inv_stock_to_production_item.id')
                                ->get();

        return view('pages/inventory/gst/inventory-trans-report',compact('item_details'));
    }

    public function Transactionexport(Request $request)
    {
        $condition = [];
        if ($request->item_code) {
            $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
        }
        $item_details=DB::table('inv_stock_to_production_item')
                                ->select('inv_lot_allocation.lot_number','inv_stock_to_production.sip_number','work_centre.centre_code','work_centre.description as workcentre_description',
                                'inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inventory_rawmaterial.discription','inv_stock_to_production.qty_to_production',
                                'inv_unit.unit_name','inv_supplier.vendor_name','inv_supplier.vendor_id')
                                ->leftJoin('inv_stock_to_production_item_rel','inv_stock_to_production_item_rel.item','=','inv_stock_to_production_item.id')
                                ->leftJoin('inv_stock_to_production','inv_stock_to_production.id','=','inv_stock_to_production_item_rel.master')
                                ->leftJoin('inv_lot_allocation','inv_lot_allocation.id','=','inv_stock_to_production.lot_id')
                                ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_stock_to_production_item.material_id')
                                ->leftJoin('work_centre','work_centre.id','=','inv_stock_to_production.work_centre')
                                ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                ->leftJoin('inv_supplier','inv_supplier.id','=','inv_lot_allocation.supplier_id')
                                ->where($condition)
                                ->distinct('inv_stock_to_production_item.id')
                                ->orderBy('inv_stock_to_production_item.id')
                                ->get();
        return Excel::download(new InventoryTransactionExport($item_details), 'InventoryTransactionReport' . date('d-m-Y') . '.xlsx');
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
