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
        ini_set('max_execution_time', 400); 
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

        // $item_details=DB::table('inv_stock_to_production_item')
        //                         ->select('inv_lot_allocation.lot_number','inv_stock_to_production.sip_number','work_centre.centre_code','work_centre.description as workcentre_description',
        //                         'inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inventory_rawmaterial.discription','inv_stock_to_production.qty_to_production',
        //                         'inv_unit.unit_name','inv_supplier.vendor_name','inv_supplier.vendor_id')
        //                         ->leftJoin('inv_stock_to_production_item_rel','inv_stock_to_production_item_rel.item','=','inv_stock_to_production_item.id')
        //                         ->leftJoin('inv_stock_to_production','inv_stock_to_production.id','=','inv_stock_to_production_item_rel.master')
        //                         ->leftJoin('inv_lot_allocation','inv_lot_allocation.id','=','inv_stock_to_production.lot_id')
        //                         ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_stock_to_production_item.material_id')
        //                         ->leftJoin('work_centre','work_centre.id','=','inv_stock_to_production.work_centre')
        //                         ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
        //                         ->leftJoin('inv_supplier','inv_supplier.id','=','inv_lot_allocation.supplier_id')
        //                         ->where($condition)
        //                         ->distinct('inv_stock_to_production_item.id')
        //                         ->orderBy('inv_stock_to_production_item.id')
        //                         ->get();

        $item_details = DB::table('inv_supplier_invoice_item')
                            ->select('inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inventory_rawmaterial.discription','inv_unit.unit_name','inv_final_purchase_order_master.po_number','inv_lot_allocation.id as lot_id',
                            'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.created_at as transaction_date','inv_supplier_invoice_item.rate as basic_rate','inv_stock_to_production.sip_number',
                            'inv_item_type_2.type_name','inv_lot_allocation.lot_number','inv_lot_allocation.prepared_by as lot_created_by','inv_miq.miq_number','inv_miq.miq_date','inv_mac.mac_number','inv_mac.mac_date','inv_mrd.mrd_number','inv_mrd.mrd_date','inv_supplier_invoice_master.invoice_number',
                            'inv_supplier_invoice_item.order_qty as invoice_qty','inv_supplier_invoice_master.created_by as invoice_created_by','inv_miq_item.value_inr','inv_miq_item.expiry_control','inv_miq.created_by as miq_created_by','inv_mac_item.accepted_quantity',
                            'inv_mac.created_by as mac_created_by','inv_mrd.created_by as mrd_created_by','inv_mrd_item.rejected_quantity','inv_mrd_item.remarks','inv_mrr.mrr_number','inv_mrr.mrr_date','inv_mrr.created_by as mrr_created_by','inv_stock_to_production.qty_to_production',
                            'inv_stock_to_production.created_by as sip_created_by','inv_stock_to_production.created_at as sip_date','inv_stock_from_production.sir_number','inv_stock_from_production.qty_to_return','inv_stock_from_production.created_at as sir_date','inv_stock_transfer_order_item.transfer_qty',
                            'inv_stock_transfer_order.sto_number','inv_stock_transfer_order.created_at as sto_date','inv_stock_transfer_order.created_user as sto_created_by','work_centre.centre_code')
                            ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                            ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                            ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                            ->leftjoin('inv_item_type_2','inv_item_type_2.id','=','inventory_rawmaterial.item_type_id_2')
                            ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                            ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_item.po_master_id')
                            ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                            ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_miq_item','inv_miq_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
                            ->leftjoin('inv_miq','inv_miq.id','=','inv_miq_item_rel.master')
                            ->leftjoin('inv_mac_item','inv_mac_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_mac_item_rel','inv_mac_item_rel.item','=','inv_mac_item.id')
                            ->leftjoin('inv_mac','inv_mac.id','=','inv_mac_item_rel.master')
                            ->leftjoin('inv_mrd_item','inv_mrd_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_mrd_item_rel','inv_mrd_item_rel.item','=','inv_mrd_item.id')
                            ->leftjoin('inv_mrd','inv_mrd.id','=','inv_mrd_item_rel.master')
                            ->leftjoin('inv_mrr_item','inv_mrr_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_mrr_item_rel','inv_mrr_item_rel.item','=','inv_mrr_item.id')
                            ->leftjoin('inv_mrr','inv_mrr.id','=','inv_mrr_item_rel.master')
                            ->leftjoin('inv_stock_to_production','inv_stock_to_production.mac_item_id','=','inv_mac_item.id')
                            ->leftjoin('inv_stock_from_production','inv_stock_from_production.sip_id','=','inv_stock_to_production.id')
                            ->leftJoin('work_centre','work_centre.id','=','inv_stock_to_production.work_centre')
                            ->leftjoin('inv_stock_transfer_order_item','inv_stock_transfer_order_item.sip_id','=','inv_stock_to_production.id')
                            ->leftjoin('inv_stock_transfer_order_item_rel','inv_stock_transfer_order_item_rel.item','=','inv_stock_transfer_order_item.id')
                            ->leftjoin('inv_stock_transfer_order','inv_stock_transfer_order.id','=','inv_stock_transfer_order_item_rel.master')
                            ->where('inv_supplier_invoice_item.is_merged','=',0)
                            ->where($condition)
                            ->orWhere('inv_miq.status','=',1)
                            ->orWhere('inv_mac.status','=',1)
                            ->orWhere('inv_mrd.status','=',1)
                            ->orWhere('inv_mrr.status','=',1)
                            ->groupBy('inv_supplier_invoice_item.id')
                            ->distinct('inv_supplier_invoice_item.id')
                            ->orderBy('inv_supplier_invoice_item.id','desc')
                            ->paginate(15);
        return view('pages/inventory/gst/inventory-trans-report',compact('item_details'));
    }

    public function Transactionexport(Request $request)
    {
        $condition = [];
        if ($request->item_code) {
            $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $request->item_code . '%'];
        }
        // $item_details=DB::table('inv_stock_to_production_item')
        //                         ->select('inv_lot_allocation.lot_number','inv_stock_to_production.sip_number','work_centre.centre_code','work_centre.description as workcentre_description',
        //                         'inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inventory_rawmaterial.discription','inv_stock_to_production.qty_to_production',
        //                         'inv_unit.unit_name','inv_supplier.vendor_name','inv_supplier.vendor_id')
        //                         ->leftJoin('inv_stock_to_production_item_rel','inv_stock_to_production_item_rel.item','=','inv_stock_to_production_item.id')
        //                         ->leftJoin('inv_stock_to_production','inv_stock_to_production.id','=','inv_stock_to_production_item_rel.master')
        //                         ->leftJoin('inv_lot_allocation','inv_lot_allocation.id','=','inv_stock_to_production.lot_id')
        //                         ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_stock_to_production_item.material_id')
        //                         ->leftJoin('work_centre','work_centre.id','=','inv_stock_to_production.work_centre')
        //                         ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
        //                         ->leftJoin('inv_supplier','inv_supplier.id','=','inv_lot_allocation.supplier_id')
        //                         ->where($condition)
        //                         ->distinct('inv_stock_to_production_item.id')
        //                         ->orderBy('inv_stock_to_production_item.id')
        //   
        $item_details = DB::table('inv_supplier_invoice_item')
                            ->select('inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inventory_rawmaterial.discription','inv_unit.unit_name','inv_final_purchase_order_master.po_number','inv_lot_allocation.id as lot_id',
                            'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.created_at as transaction_date','inv_supplier_invoice_item.rate as basic_rate','inv_stock_to_production.sip_number',
                            'inv_item_type_2.type_name','inv_lot_allocation.lot_number','inv_lot_allocation.prepared_by as lot_created_by','inv_miq.miq_number','inv_miq.miq_date','inv_mac.mac_number','inv_mac.mac_date','inv_mrd.mrd_number','inv_mrd.mrd_date','inv_supplier_invoice_master.invoice_number',
                            'inv_supplier_invoice_item.order_qty as invoice_qty','inv_supplier_invoice_master.created_by as invoice_created_by','inv_miq_item.value_inr','inv_miq_item.expiry_control','inv_miq.created_by as miq_created_by','inv_mac_item.accepted_quantity',
                            'inv_mac.created_by as mac_created_by','inv_mrd.created_by as mrd_created_by','inv_mrd_item.rejected_quantity','inv_mrd_item.remarks','inv_mrr.mrr_number','inv_mrr.mrr_date','inv_mrr.created_by as mrr_created_by','inv_stock_to_production.qty_to_production',
                            'inv_stock_to_production.created_by as sip_created_by','inv_stock_to_production.created_at as sip_date','inv_stock_from_production.sir_number','inv_stock_from_production.qty_to_return','inv_stock_from_production.created_at as sir_date','inv_stock_transfer_order_item.transfer_qty',
                            'inv_stock_transfer_order.sto_number','inv_stock_transfer_order.created_at as sto_date','inv_stock_transfer_order.created_user as sto_created_by','work_centre.centre_code')
                            ->leftJoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                            ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                            ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                            ->leftjoin('inv_item_type_2','inv_item_type_2.id','=','inventory_rawmaterial.item_type_id_2')
                            ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                            ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_item.po_master_id')
                            ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                            ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_miq_item','inv_miq_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
                            ->leftjoin('inv_miq','inv_miq.id','=','inv_miq_item_rel.master')
                            ->leftjoin('inv_mac_item','inv_mac_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_mac_item_rel','inv_mac_item_rel.item','=','inv_mac_item.id')
                            ->leftjoin('inv_mac','inv_mac.id','=','inv_mac_item_rel.master')
                            ->leftjoin('inv_mrd_item','inv_mrd_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_mrd_item_rel','inv_mrd_item_rel.item','=','inv_mrd_item.id')
                            ->leftjoin('inv_mrd','inv_mrd.id','=','inv_mrd_item_rel.master')
                            ->leftjoin('inv_mrr_item','inv_mrr_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                            ->leftjoin('inv_mrr_item_rel','inv_mrr_item_rel.item','=','inv_mrr_item.id')
                            ->leftjoin('inv_mrr','inv_mrr.id','=','inv_mrr_item_rel.master')
                            ->leftjoin('inv_stock_to_production','inv_stock_to_production.mac_item_id','=','inv_mac_item.id')
                            ->leftjoin('inv_stock_from_production','inv_stock_from_production.sip_id','=','inv_stock_to_production.id')
                            ->leftJoin('work_centre','work_centre.id','=','inv_stock_to_production.work_centre')
                            ->leftjoin('inv_stock_transfer_order_item','inv_stock_transfer_order_item.sip_id','=','inv_stock_to_production.id')
                            ->leftjoin('inv_stock_transfer_order_item_rel','inv_stock_transfer_order_item_rel.item','=','inv_stock_transfer_order_item.id')
                            ->leftjoin('inv_stock_transfer_order','inv_stock_transfer_order.id','=','inv_stock_transfer_order_item_rel.master')
                            ->where('inv_supplier_invoice_item.is_merged','=',0)
                            ->where($condition)
                            ->orWhere('inv_miq.status','=',1)
                            ->orWhere('inv_mac.status','=',1)
                            ->orWhere('inv_mrd.status','=',1)
                            ->orWhere('inv_mrr.status','=',1)
                            ->groupBy('inv_supplier_invoice_item.id')
                            ->distinct('inv_supplier_invoice_item.id')
                            ->orderBy('inv_supplier_invoice_item.id','desc')
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
    public function get_user($id)
    {
        if(!empty($id))
        {
            $user=DB::table('user')
                            ->where('user_id','=',$id)
                            ->first();
            return $user->f_name.''.$user->l_name;
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
