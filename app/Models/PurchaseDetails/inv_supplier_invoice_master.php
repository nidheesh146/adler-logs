<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;


class inv_supplier_invoice_master extends Model
{
    protected $table = 'inv_supplier_invoice_master';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
    protected static function booted()
    {
        static::addGlobalScope('inv_supplier_invoice_master.status', function (Builder $builder) {
            $builder->where('inv_supplier_invoice_master.status', '!=', 2);
        });
    }
    function updatedata($condition,$data){
        return $this->where($condition)->update($data);
    }
    function insert_data($data){
      $SIMaster =  $this->insertGetId($data);
      if( $SIMaster ){
       $inv_final_purchase_order_master =  DB::table('inv_final_purchase_order_rel')
                            ->leftjoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
                            ->where(['inv_final_purchase_order_rel.master'=>$data['po_master_id']])->get();

    foreach($inv_final_purchase_order_master as $items){
            $datas['item_id'] = $items->item_id;
            $datas['order_qty'] = $items->order_qty;
            $datas['discount'] =  $items->discount; 
            $datas['specification'] =  $items->Specification;
            $datas['rate'] =  $items->rate;
            $or_item_id = DB::table('inv_supplier_invoice_item')->insertGetId($datas);
                if( $or_item_id){
                    DB::table('inv_supplier_invoice_rel')->insertGetId(['master'=>$SIMaster,'item'=>$or_item_id]);
                }
        }
      }
      return $SIMaster;
    }

    function get_master_data($condition){
        return $this->select(['inv_final_purchase_order_master.id','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number',
        'inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.created_by'])
                    ->join('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_master.po_master_id')
                    ->where($condition)
                    ->first();
    }
    function deleteData($condition)
    {
         DB::table('inv_supplier_invoice_item') 
            ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
            ->where(['inv_supplier_invoice_rel.master'=>$condition['id']])
            ->delete();
     return  $this->where($condition)->delete();
    }
    function get_supplier_inv($condition){
        return $this->select(['inv_supplier_invoice_master.id','user.employee_id','user.f_name','user.l_name','inv_final_purchase_order_master.po_number',
        'inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.created_at',
        'inv_supplier.vendor_id','inv_supplier.vendor_name'])
                ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_master.po_master_id')
                ->leftjoin('user','user.user_id','=','inv_supplier_invoice_master.created_by')
                ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                ->where($condition)
                ->where($condition2)
                ->orderBy('inv_supplier_invoice_master.id','DESC')
                ->paginate(15);
    }

    function get_supplier_invoices($condition,$condition1,$condition2){
        return $this->select(['inv_supplier_invoice_master.id','user.employee_id','user.f_name','user.l_name','inv_final_purchase_order_master.po_number',
        'inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.created_at',
        'inv_supplier.vendor_id','inv_supplier.vendor_name'])
                ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_master.po_master_id')
                ->leftjoin('user','user.user_id','=','inv_supplier_invoice_master.created_by')
                ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                ->where($condition1)
                ->where($condition)
                ->Orwhere($condition2)
                ->orderBy('inv_supplier_invoice_master.id','DESC')
                ->paginate(15);
    }

    function get_invoice_nos(){
        return $this->select('id','invoice_number')->get();
    }





}
