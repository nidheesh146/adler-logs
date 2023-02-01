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
    function insert_data($data,$po_item_id){
    //   $SIMaster =  $this->insertGetId($data);
    //   if( $SIMaster ){
    //    $inv_final_purchase_order_master =  DB::table('inv_final_purchase_order_rel')
    //                         ->leftjoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
    //                         ->where(['inv_final_purchase_order_rel.master'=>$data['po_master_id']])->get();

    // foreach($inv_final_purchase_order_master as $items){
    //         $datas['item_id'] = $items->item_id;
    //         $datas['order_qty'] = $items->order_qty;
    //         $datas['discount'] =  $items->discount; 
    //         $datas['specification'] =  $items->Specification;
    //         $datas['rate'] =  $items->rate;
    //         $or_item_id = DB::table('inv_supplier_invoice_item')->insertGetId($datas);
    //             if( $or_item_id){
    //                 DB::table('inv_supplier_invoice_rel')->insertGetId(['master'=>$SIMaster,'item'=>$or_item_id]);
    //             }
    //     }
    //   }
    //   return $SIMaster;
        $SIMaster =  $this->insertGetId($data);
        $datas =[];
        foreach($po_item_id as $POitem_id){
              $item = DB::table('inv_final_purchase_order_item')->where('id','=',$POitem_id)->first();
              
              //$item_data['data']= $item
              //print_r($item);exit;
              $po_master = DB::table('inv_final_purchase_order_rel')->where('item','=',$POitem_id)->pluck('master')->first();
              $datas['rate'] = $item->rate;
              if($item->current_invoice_qty==0)
              $datas['order_qty']= $item->qty_to_invoice;
              else
              $datas['order_qty']= $item->current_invoice_qty;
              $datas['discount']= $item->discount;
              $datas['gst']= $item->gst;
              $datas['specification']= $item->Specification;
              $datas['item_id']= $item->item_id;
              $datas['po_item_id']= $POitem_id;
              $datas['status']= 1;
              $datas['po_master_id']= $po_master;
              $or_item_id = DB::table('inv_supplier_invoice_item')->insertGetId($datas);
              $update = DB::table('inv_final_purchase_order_item')->where('id','=',$POitem_id)->update(['current_invoice_qty'=>0]);
              if( $or_item_id)
              {
                DB::table('inv_supplier_invoice_rel')->insertGetId(['master'=>$SIMaster,'item'=>$or_item_id]);
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

    function get_supplier_invoices($condition1){
        return $this->select(['inv_supplier_invoice_master.id','user.employee_id','user.f_name','user.l_name','inv_final_purchase_order_master.po_number',
        'inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.created_at',
        'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_supplier_invoice_master.po_master_id'])
                ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_master.po_master_id')
                ->leftjoin('user','user.user_id','=','inv_supplier_invoice_master.created_by')
                ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                ->where($condition1)
                //->where('')
                ->orderBy('inv_supplier_invoice_master.id','DESC')
                ->paginate(15);
    }

    function get_invoice_nos(){
        return $this->select('id','invoice_number')->get();
    }

    function find_invoice_num($condition)
    {
        return $this->select(['inv_supplier_invoice_master.invoice_number as text','inv_supplier_invoice_master.id'])->where($condition)
        ->whereNotIn('inv_supplier_invoice_master.id',function($query) {

            $query->select('inv_miq.invoice_master_id')->from('inv_miq');
        
        })->where('inv_supplier_invoice_master.status','=',1)
        ->get();
    }





}
