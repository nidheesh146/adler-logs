<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_miq extends Model
{
    protected $table = 'inv_miq';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_all_data($condition){
        return $this->select('inv_miq.id as miq_id','inv_miq.miq_number','inv_miq.miq_date','inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.created_by','inv_supplier.vendor_name','user.f_name','user.l_name')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_miq.invoice_master_id')
                    ->leftjoin('user','user.user_id','inv_miq.created_by')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')

                    ->where($condition)
                    ->where('inv_miq.status','=',1)
                    ->orderBy('inv_supplier_invoice_master.id','DESC')
                    ->paginate(15);
    }
    function get_data($condition)
    {
        return $this->select('inv_miq.id as miq_id','inv_miq.miq_number','inv_miq.miq_date','inv_supplier_invoice_master.invoice_number','inv_miq.invoice_master_id',
                            'inv_miq.created_by','inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_name','inv_supplier.vendor_id','user.f_name','user.l_name')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_miq.invoice_master_id')
                    ->leftjoin('user','user.user_id','inv_miq.created_by')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->where($condition)
                    ->where('inv_miq.status','=',1)
                    ->first();
    }
}

