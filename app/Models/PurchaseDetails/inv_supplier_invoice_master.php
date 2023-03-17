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
   
        $SIMaster =  $this->insertGetId($data);
        $datas =[];
        $repeated_pr_si_item =[];
        $repeat_raw_item_id =[];
       
        foreach($po_item_id as $POitem_id)
        {
            $item = DB::table('inv_final_purchase_order_item')
                            ->select('inv_final_purchase_order_item.*','inv_purchase_req_item.Item_code as rawmaterial_id')
                            ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                            ->where('inv_final_purchase_order_item.id','=',$POitem_id)->first();
            $count=0;
            $qty_sum =0;
            foreach($po_item_id as $POitem_id)
            {
                $fpo_item = DB::table('inv_final_purchase_order_item')
                                    ->select('inv_final_purchase_order_item.*','inv_purchase_req_item.Item_code as rawmaterial_id')
                                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                                    ->where('inv_final_purchase_order_item.id','=',$POitem_id)->first();
                if($item->rawmaterial_id==$fpo_item->rawmaterial_id && $item->gst==$fpo_item->gst && $item->rate==$fpo_item->rate && $item->discount==$fpo_item->discount)
                {
                    $count++;
                }
            }
            if($count>=2)
            {
            //$repeated_pr_poitem[] = $item;
            $repeat_raw_item_id[] = $item->rawmaterial_id;
            }

        }
        $repeat_raw_item_id = (array_unique($repeat_raw_item_id));


        foreach($po_item_id as $POitem_id){
              $item = DB::table('inv_final_purchase_order_item')->where('id','=',$POitem_id)->first();
              
              //$item_data['data']= $item
              //print_r($item);exit;
              $po_master = DB::table('inv_final_purchase_order_rel')->where('item','=',$POitem_id)->pluck('master')->first();
              $datas['rate'] = $item->rate;
              if($item->current_invoice_qty==0)
              {
              $datas['order_qty']= $item->qty_to_invoice;
              }
              else
              {
              $datas['order_qty']= $item->current_invoice_qty;
              }
              $datas['discount']= $item->discount;
              $datas['gst']= $item->gst;
              $datas['specification']= $item->Specification;
              $datas['item_id']= $item->item_id;
              $datas['po_item_id']= $POitem_id;
              $datas['status']= 1;
              $datas['po_master_id']= $po_master;
              $or_item_id = DB::table('inv_supplier_invoice_item')->insertGetId($datas);
              if($item->current_invoice_qty==0)
              {
                $update = DB::table('inv_final_purchase_order_item')->where('id','=',$POitem_id)->update(['qty_to_invoice'=>0]);
              }
              else
              {
                $update = DB::table('inv_final_purchase_order_item')->where('id','=',$POitem_id)->update(['current_invoice_qty'=>0]);
              }
              if($or_item_id)
              {
                DB::table('inv_supplier_invoice_rel')->insertGetId(['master'=>$SIMaster,'item'=>$or_item_id]);
              }
        }
        $si_items = DB::table('inv_supplier_invoice_rel')
                        ->select('inv_supplier_invoice_rel.item')
                        ->where('master','=',$SIMaster)->get();
        foreach($si_items as $sitem)
        {
            $item = DB::table('inv_supplier_invoice_item')
                            ->select('inv_supplier_invoice_item.*','inv_purchase_req_item.Item_code as rawmaterial_id')
                            ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                            ->where('inv_supplier_invoice_item.id','=',$sitem->item)
                            ->first();
                            //echo $rawmaterial_id;
            $count=0;
            $qty_sum =0;
            foreach($si_items as $si_item)
            {
                $si_item = DB::table('inv_supplier_invoice_item')
                                ->select('inv_supplier_invoice_item.*','inv_purchase_req_item.Item_code as rawmaterial_id')
                                ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                                ->where('inv_supplier_invoice_item.id','=',$si_item->item)->first();
                if($item->rawmaterial_id==$si_item->rawmaterial_id && $item->gst==$si_item->gst && $item->rate==$si_item->rate && $item->discount==$si_item->discount)
                {
                    $count++;
                    $qty_sum = $qty_sum + $si_item->order_qty;
                    $item->qty_sum = $qty_sum;
                    //$pr_id[]=$item->item_id;
                }
                
            }
            if($count>=2)
            {
                $repeated_siitem[] = $item;
            }
                
        }
        //print_r(json_encode($repeated_siitem));
        //exit;

        foreach($repeat_raw_item_id as $raw_item)
        {
            $item = DB::table('inv_supplier_invoice_rel')
                        ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
                        ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                        ->select('inv_supplier_invoice_item.*','inv_purchase_req_item.Item_code')
                        ->where('inv_purchase_req_item.Item_code','=',$raw_item)
                        ->where('inv_supplier_invoice_rel.master','=',$SIMaster)->first();
            $items = DB::table('inv_supplier_invoice_rel')
                        ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_supplier_invoice_rel.item')
                        ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                        ->select('inv_supplier_invoice_item.*','inv_purchase_req_item.Item_code')
                        ->where('inv_purchase_req_item.Item_code','=',$raw_item)
                        ->where('inv_supplier_invoice_rel.master','=',$SIMaster)->get();

            $dat['order_qty'] = $item->order_qty;
            $dat['rate'] =  $item->rate;
            $dat['discount'] =$item->discount;
            $dat['gst'] =$item->gst;
            $dat['specification']=$item->specification;
            $dat['item_id'] =$item->item_id;
            $dat['status'] =1;
            $merged_si_item_id = DB::table('inv_supplier_invoice_item')->insertGetId($dat);
            if( $merged_si_item_id)
            {
                DB::table('inv_supplier_invoice_rel')->insertGetId(['master'=>$SIMaster,'item'=>$merged_si_item_id]);
            }
            $order_qty =0;
            foreach($items as $si_item)
            {
                $order_qty = $order_qty+ $si_item->order_qty;
                DB::table('inv_supplier_invoice_item')->where('id','=', $merged_si_item_id)->update(['order_qty'=>$order_qty]);
                DB::table('inv_supplier_invoice_item')->where('id','=', $si_item->id)->update(['is_merged'=>1, 'merged_invoice_item'=>$merged_si_item_id]);
            }

        }
        return $SIMaster;
    }

    function get_master_data($condition){
        return $this->select(['inv_final_purchase_order_master.id','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number',
        'inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.created_by','inv_supplier_invoice_master.created_at as invoice_created','inv_supplier.vendor_name','inv_supplier.vendor_id'])
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_master.po_master_id')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
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

            $query->select('inv_miq.invoice_master_id')->from('inv_miq')>where('inv_miq.status','=',1);
        
        })->where('inv_supplier_invoice_master.status','=',1)
        ->where('inv_supplier_invoice_master.type','=','PO')
        ->get();
    }

    function find_invoice_num_for_mac($condition)
    {
        return $this->select(['inv_supplier_invoice_master.invoice_number as text','inv_supplier_invoice_master.id'])->where($condition)
        ->whereNotIn('inv_supplier_invoice_master.id',function($query) {

            $query->select('inv_mac.invoice_id')->from('inv_mac')->where('inv_mac.status','=',1);
        
        })->where('inv_supplier_invoice_master.status','=',1)
        ->where($condition)
        ->where('inv_supplier_invoice_master.type','=','PO')
        ->get();
    }
    function find_invoice_num_for_woa($condition)
    {
        return $this->select(['inv_supplier_invoice_master.invoice_number as text','inv_supplier_invoice_master.id'])->where($condition)
        ->whereNotIn('inv_supplier_invoice_master.id',function($query) {

            $query->select('inv_mac.invoice_id')->from('inv_mac')->where('inv_mac.status','=',1);
        
        })->where('inv_supplier_invoice_master.status','=',1)
        ->where($condition)
        ->where('inv_supplier_invoice_master.type','=','WO')
        ->get();
    }
    function find_invoice_num_for_mrd($condition)
    {
        return $this->select(['inv_supplier_invoice_master.invoice_number as text','inv_supplier_invoice_master.id'])->where($condition)
        ->whereNotIn('inv_supplier_invoice_master.id',function($query) {

            $query->select('inv_mrd.invoice_id')->from('inv_mrd')->where('inv_mrd.status','=',1);
        
        })->where('inv_supplier_invoice_master.status','=',1)
        ->where($condition)
        ->where('inv_supplier_invoice_master.type','=','PO')
        ->get();
    }
    function find_invoice_num_for_wor($condition)
    {
        return $this->select(['inv_supplier_invoice_master.invoice_number as text','inv_supplier_invoice_master.id'])->where($condition)
        ->whereNotIn('inv_supplier_invoice_master.id',function($query) {

            $query->select('inv_mrd.invoice_id')->from('inv_mrd')->where('inv_mrd.status','=',1);
        
        })->where('inv_supplier_invoice_master.status','=',1)
        ->where($condition)
        ->where('inv_supplier_invoice_master.type','=','WO')
        ->get();
    }





}
