<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class inv_miq_item extends Model
{
    protected $table = 'inv_miq_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_items($condition){
        return $this->select('inv_miq_item.id as item_id','inv_miq_item.expiry_control','inv_miq_item.expiry_date','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
                    'inventory_rawmaterial.item_code','inv_item_type.type_name','inv_unit.unit_name','inv_lot_allocation.lot_number','inv_miq_item.value_inr','currency_exchange_rate.currency_code')
                    ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
                    ->where($condition)
                    ->orderBy('inv_miq_item.id','DESC')
                    ->distinct('inv_miq_item.id')
                    ->get();
    }
    function get_item($condition){
        return $this->select('inv_miq_item.id as item_id','inv_miq_item.currency','inv_miq_item.conversion_rate','inv_miq_item.value_inr','inv_miq_item.expiry_control',
        'inv_miq_item.expiry_date','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_purchase_req_item.requisition_item_id','inv_miq_item.invoice_item_id',
                    'inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inv_item_type.type_name','inv_unit.unit_name','inv_lot_allocation.lot_number','inv_supplier_invoice_item.discount')
                    ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->where($condition)
                    ->orderBy('inv_miq_item.id','DESC')
                    ->first();
                    
    }
    function get_all_data_not_in_mac($condition)
    {
       // $specialMiqNumber = 'MIQ3-2526-267';
       // $specialItemCode = 'RSS11RS1000';
    
        return $this->select(
            'inv_miq_item.id as item_id',
            'inv_miq_item.expiry_control',
            'inv_miq_item.expiry_date',
            'inv_supplier_invoice_item.order_qty',
            'inv_supplier_invoice_item.rate',
            'inv_supplier_invoice_item.discount',
            'inventory_rawmaterial.item_code',
            'inventory_rawmaterial.discription',
            'inventory_rawmaterial.hsn_code',
            'inv_item_type.type_name',
            'inv_unit.unit_name',
            'inv_lot_allocation.lot_number',
            'inv_miq_item.value_inr',
            'currency_exchange_rate.currency_code',
            'inv_miq_item.conversion_rate',
            'inv_miq.miq_number',
            'inv_miq.miq_date',
            'inv_miq.created_at',
            'user.f_name',
            'user.l_name',
            'inv_supplier.vendor_id',
            'inv_supplier.vendor_name',
            'inv_supplier_invoice_master.invoice_number',
            'inv_supplier_invoice_master.invoice_date',
            'inv_final_purchase_order_master.po_number'
        )
        ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
        ->leftjoin('inv_miq','inv_miq.id','=','inv_miq_item_rel.master')
        ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
        ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
        ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
        ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
        ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
        ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
        ->leftjoin('user','user.user_id','inv_miq.created_by')
        ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
        ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','inv_supplier_invoice_item.id')
        ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_supplier_invoice_rel.master')
        ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
        ->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
    
        // Group your normal filters here:
        ->where(function($query) use ($condition) {
            $query->where($condition)
                  ->whereNotIn('inv_supplier_invoice_master.id', function($q) {
                      $q->select('inv_mac.invoice_id')->from('inv_mac')->where('inv_mac.status', '=', 1);
                  })
                  ->whereNotIn('inv_miq_item.invoice_item_id', function($q) {
                      $q->select('inv_mrd_item.invoice_item_id')->from('inv_mrd_item');
                  })
                  ->where('inv_miq.status', '=', 1);
        })
    
        // Now OR with your special record condition:
        // ->orWhere(function($query) use ($specialMiqNumber, $specialItemCode) {
        //     $query->where('inv_miq.miq_number', '=', $specialMiqNumber)
        //           ->where('inventory_rawmaterial.item_code', '=', $specialItemCode);
        // })
    
        ->distinct('inv_miq_item.id')
        ->orderBy('inv_miq_item.id','DESC')
        ->paginate(15);
    }
    

    // function get_all_data_not_in_mac($condition)
    // {
    //     return $this->select('inv_miq_item.id as item_id','inv_miq_item.expiry_control','inv_miq_item.expiry_date','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
    //                 'inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_item_type.type_name','inv_unit.unit_name','inv_lot_allocation.lot_number','inv_miq_item.value_inr','currency_exchange_rate.currency_code',
    //                 'inv_miq_item.conversion_rate','inv_miq.miq_number','inv_miq.miq_date','inv_miq.created_at','user.f_name','user.l_name','inv_supplier.vendor_id','inv_supplier.vendor_name','inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_final_purchase_order_master.po_number')
    //                 ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
    //                 ->leftjoin('inv_miq','inv_miq.id','=','inv_miq_item_rel.master')
    //                 ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
    //                 ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
    //                 ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
    //                 ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
    //                 ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
    //                 ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
    //                 ->leftjoin('user','user.user_id','inv_miq.created_by')
    //                 ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
    //                 ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','inv_supplier_invoice_item.id')
    //                 ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_supplier_invoice_rel.master')
    //                 ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
    //                 ->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
    //                 ->where($condition)
    //                 // ->whereNotIn('inv_miq_item.invoice_item_id',$invoice_item_id)
    //                 // ->whereNotIn('inv_miq_item.invoice_item_id',function($query) {
    //                 //     $query->select('inv_mac_item.invoice_item_id')->from('inv_mac_item');
                  
    //                 // })
    //                 // ->whereExists(function($query) {
    //                 //     $query->select(DB::raw(1))
    //                 //         ->from('inv_supplier_invoice_rel as sir')
    //                 //         ->join('inv_supplier_invoice_item as sii', 'sii.id', '=', 'sir.item')
    //                 //         ->whereColumn('sir.master', 'inv_supplier_invoice_master.id')
    //                 //         ->whereNotExists(function($sub) {
    //                 //             $sub->select(DB::raw(1))
    //                 //                 ->from('inv_mac_item_rel as mir')
    //                 //                 ->join('inv_mac_item as mi', 'mi.id', '=', 'mir.item')
    //                 //                 ->join('inv_mac', 'inv_mac.id', '=', 'mir.master')
    //                 //                 ->whereColumn('mi.invoice_item_id', 'sii.id');
    //                 //         });
    //                 // })
                    
    //                 ->whereNotIn('inv_miq_item.invoice_item_id',function($query) {
    //                     $query->select('inv_mrd_item.invoice_item_id')->from('inv_mrd_item');
                  
    //                 })
    //                 ->where('inv_miq.status','=',1)
    //                 ->distinct('inv_miq_item.id')
    //                 ->orderBy('inv_miq_item.id','DESC')
    //                 ->paginate(15);

    // }

      
}
