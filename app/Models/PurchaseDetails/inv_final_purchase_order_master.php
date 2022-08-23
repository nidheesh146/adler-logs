<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;


class inv_final_purchase_order_master extends Model
{
    protected $table = 'inv_final_purchase_order_master';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
    protected static function booted()
    {
        static::addGlobalScope('inv_final_purchase_order_master.status', function (Builder $builder) {
            $builder->where('inv_final_purchase_order_master.status', '!=', 2);
        });
    }
    function updatedata($condition,$data){
        return $this->where($condition)->update($data);
    }
    function insert_data($data){
      $POMaster =  $this->insertGetId($data);
      if( $POMaster ){
       $inv_purchase_req_quotation =  DB::table('inv_purchase_req_quotation_supplier')->where(['quotation_id'=>$data['rq_master_id'],'selected_supplier'=>1])->first();
       $item =  DB::table('inv_purchase_req_quotation_item_supp_rel')->where(['quotation_id'=>$data['rq_master_id'],'status'=>1,'supplier_id'=>$inv_purchase_req_quotation->supplier_id])->get();
       
       foreach($item as $items){
            $datas['item_id'] = $items->item_id;
            $datas['order_qty'] = $items->quantity;
            $datas['discount'] =  $items->discount; 
            $datas['Specification'] =  $items->specification;
            $datas['rate'] =  $items->rate;

            $or_item_id = DB::table('inv_final_purchase_order_item')->insertGetId($datas);
                if( $or_item_id){
                    DB::table('inv_final_purchase_order_rel')->insertGetId(['master'=>$POMaster,'item'=>$or_item_id]);
                }
        }
      }
      return $POMaster;
    }
    function get_purchase_master($condition){
        return $this->select(['inv_purchase_req_quotation.rq_no','inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_date',
                              'inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.created_at','user.f_name','user.l_name','inv_final_purchase_order_master.id'])
                    ->where($condition)
                    ->join('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                    ->join('user','user.user_id','=','inv_final_purchase_order_master.created_by')
                    ->leftjoin('inv_purchase_req_quotation_supplier', function($join)
                        {
                            $join->on('inv_purchase_req_quotation_supplier.quotation_id', '=', 'inv_final_purchase_order_master.rq_master_id');
                            $join->where('inv_purchase_req_quotation_supplier.selected_supplier','=',1);
                        })
                        ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_supplier.supplier_id')
                    ->orderby('inv_final_purchase_order_master.id','desc')
                    ->paginate(15);

    }
    function get_master_data($condition){
        return $this->select(['*'])
        ->join('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                    ->where($condition)
                    ->first();
    }
    function deleteData($condition)
    {
        return $this->where($condition)->delete();
    }
    function find_po_num($condition){
        return $this->select(['po_number as text','id'])->where($condition)->get();
    }
    function find_po_data($condition){
        //
        return $this->select(['inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.id','inv_final_purchase_order_master.created_at','user.f_name','user.l_name','inv_final_purchase_order_master.po_date',
        'inv_supplier.vendor_id','inv_supplier.vendor_name'])
                    ->join('user','user.user_id','=','inv_final_purchase_order_master.created_by')
                    ->join('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                    ->join('inv_purchase_req_quotation_supplier', function($join)
                    {
                        $join->on('inv_purchase_req_quotation_supplier.quotation_id', '=', 'inv_final_purchase_order_master.rq_master_id');
                        $join->where('inv_purchase_req_quotation_supplier.selected_supplier','=',1);
                    })
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_supplier.supplier_id')
                    ->where($condition)
                    ->first();
    }

    function get_po_nos()
    {
        return $this->select('id','po_number')->get();
    }
}
