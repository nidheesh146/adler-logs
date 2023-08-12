<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class FGSTransferController extends Controller
{
    public function fgsTransfer(Request $request)
    {
        $condition=[];
        if($request)
        {
            if ($request->supplier) {
                $condition[] = ['inv_supplier.vendor_name','like', '%' . $request->supplier . '%'];
            }
            if ($request->mac_no) {
                $condition[] = ['inv_mac.mac_no','like', '%' . $request->mac_no . '%'];
            }
           
            if($request->mac_date)
            {  
                $condition[] = ['inv_mac.mac_date', '=', date('Y-m-d', strtotime('01-' . $request->mac_date))];
            }
        $items = DB::table('inv_mac_item')
            ->select(
                'inv_mac_item.*',
                'inv_purchase_req_item.requisition_item_id',
                'inventory_rawmaterial.item_code',
                'inv_mac.mac_number',
                'inv_mac.mac_date',
                'inv_supplier.vendor_name'
            )
            ->leftjoin('inv_mac_item_rel', 'inv_mac_item_rel.item', '=', 'inv_mac_item.id')
                ->leftjoin('inv_mac', 'inv_mac_item_rel.master', '=', 'inv_mac.id')
            ->leftjoin('inv_purchase_req_item', 'inv_mac_item.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
            ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id', '=', 'inv_mac_item.invoice_item_id')
                ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
            ->leftjoin('inventory_rawmaterial', 'inv_purchase_req_item.Item_code', '=', 'inventory_rawmaterial.id')
            ->leftjoin('inv_item_type_2', 'inventory_rawmaterial.item_type_id_2', '=', 'inv_item_type_2.id')
            ->where('inv_item_type_2.id','=', 18)
            ->where('inv_mac_item.fgs_transfer_status','=',1)
            ->where('inv_mac_item.fgs_transfer_status','!=','fgs_transfer.pr_item_id')
            ->where($condition)
            ->get();
            

}
        return view('pages/inventory/FGS-Transfer/fgs-transfer-add', compact('items'));
    }
    public function fgsTransferAdd(Request $request)
    {
        //dd($request->item_id);
        
        foreach ($request->item_id as $item_id) {
            
            $items = DB::table('inv_mac_item')
                ->select(
                    'inv_mac_item.*',
                    'inv_purchase_req_item.requisition_item_id',
                    'inv_mac.id as mac_id',
                    'inv_mac.mac_date',
                    'inv_supplier.vendor_name',
                    'inv_supplier.vendor_id',
                    'inv_supplier.id as ve_id',
                    'inventory_rawmaterial.id as inv_row_id'
                    
                )
                ->leftjoin('inv_mac_item_rel', 'inv_mac_item_rel.item', '=', 'inv_mac_item.id')
                ->leftjoin('inv_mac', 'inv_mac_item_rel.master', '=', 'inv_mac.id')
                ->leftjoin('inv_purchase_req_item', 'inv_mac_item.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id', '=', 'inv_mac_item.invoice_item_id')
                ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                ->leftjoin('inventory_rawmaterial', 'inv_purchase_req_item.Item_code', '=', 'inventory_rawmaterial.id')
                ->leftjoin('inv_item_type_2', 'inventory_rawmaterial.item_type_id_2', '=', 'inv_item_type_2.id')
                ->where('inv_item_type_2.id', '=',18)
                ->where('inv_mac_item.id','=', $item_id)
                ->first();
               
                $stock=DB::table('inv_stock_management')
                ->where('item_id',$items->inv_row_id)
                ->first();
                $r_qty=$stock->stock_qty-$items->available_qty;
                DB::table('inv_stock_management')
                ->where('item_id',$items->inv_row_id)
                ->update([
                    'stock_qty'=>$r_qty
                ]);
            DB::table('fgs_transfer')
                ->insert([
                    'pr_item_id' => $items->id,
                    'mac_item_id' => $items->mac_id,
                    'supplier_id'=>$items->ve_id,
                    // 'mac_item_id' => 1,
                    // 'supplier_id'=>5,
                    'quantity' => $items->available_qty,
                     'reamining_qty'=>r_qty,
                ]);
                DB::table('inv_mac_item')
                ->where('id',$items->id)
                ->update([
                    'fgs_transfer_status'=>2
                ]);
        }
        $request->session()->flash('success',  "You have successfully Added to FGS Transfer!");
        return redirect('inventory/fgs-transfer-list');
    }
    public function fgsTransferList(Request $request)
    {
        $condition=[];
        if($request)
        {
            if ($request->supplier) {
                $condition[] = ['inv_supplier.vendor_name','like', '%' . $request->supplier . '%'];
            }
            if ($request->mac_no) {
                $condition[] = ['inv_mac.mac_no','like', '%' . $request->mac_no . '%'];
            }
           
            if($request->mac_date)
            {  
                $condition[] = ['inv_mac.mac_date', '=', date('Y-m-d', strtotime('01-' . $request->mac_date))];
            }
            $data=DB::table('fgs_transfer')
            ->select(
                'inv_mac_item.*',
                'inv_purchase_req_item.requisition_item_id',
                'inv_mac.id as mac_id',
                'inventory_rawmaterial.item_code',
                
                'inv_mac.mac_number',
                
                'inv_mac.mac_date',
                'inv_supplier.vendor_name',
                'inv_supplier.vendor_id',
                'inv_supplier.id as ve_id',
                'inventory_rawmaterial.id as inv_row '
                
            )
             ->leftjoin('inv_supplier','inv_supplier.id','=','fgs_transfer.supplier_id')
             ->leftjoin('inv_mac','inv_mac.id','=','fgs_transfer.mac_item_id')
            ->leftjoin('inv_mac_item','inv_mac_item.id','=','fgs_transfer.pr_item_id')
            ->leftjoin('inv_mac_item_rel', 'inv_mac_item_rel.item', '=', 'inv_mac_item.id')
            ->leftjoin('inv_purchase_req_item', 'inv_mac_item.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
             ->leftjoin('inventory_rawmaterial', 'inv_purchase_req_item.Item_code', '=', 'inventory_rawmaterial.id')
            // ->where('inv_mac', 'inv_mac_item_rel.master', '=', 'inv_mac_item.id')
             //->where('inv_supplier', 'inv_supplier.id', '=', 'fgs_transfer.supplier_id')
            ->where($condition)
            ->get();
           
        }
        return view('pages/inventory/FGS-Transfer/fgs-transfer-list',compact('data'));
    }
}
