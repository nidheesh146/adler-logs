<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\FGS\fgs_pi;
use App\Models\FGS\fgs_dni;
use App\Models\FGS\fgs_dni_item_rel;
use App\Models\FGS\fgs_dni_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_grs_item;
use App\Models\product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NetBillingExport;
use App\Exports\FGSdnitransactionExport;

class DNIController extends Controller
{
    public function __construct()
    {
        $this->fgs_grs_item= new fgs_grs_item;
        $this->fgs_pi = new fgs_pi;
        $this->fgs_dni = new fgs_dni;
        $this->fgs_dni_item_rel = new fgs_dni_item_rel;
        $this->fgs_dni_item = new fgs_dni_item;
        $this->fgs_pi_item = new fgs_pi_item;
        $this->fgs_pi_item_rel = new fgs_pi_item_rel;
        $this->fgs_maa_stock_management = new fgs_maa_stock_management;
        $this->product = new product;
    }
    public function DNIList(Request $request)
    {
        $condition =['fgs_dni.dni_exi'=>'DNI'];
        if($request->dni_number)
        {
            $condition[] = ['fgs_dni.dni_number','like', '%' . $request->dni_number . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_dni.dni_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_dni.dni_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $dni = $this->fgs_dni->get_all_dni($condition);
        

        return view('pages/FGS/DNI/DNI-list', compact('dni'));
    }

    public function DNIAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['customer'] = ['required'];
            //$validation['dni_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                if(date('m')==01 || date('m')==02 || date('m')==03)
                {
                    $years_combo = date('y', strtotime('-1 year')).date('y');
                }
                else
                {
                    $years_combo = date('y').date('y', strtotime('+1 year'));
                }
                $data['dni_number'] = "DNI-".$this->year_combo_num_gen(DB::table('fgs_dni')->where('fgs_dni.dni_number', 'LIKE', 'DNI-'.$years_combo.'%')->count()+704); 
                $data['dni_date'] = date('Y-m-d',strtotime($request->dni_date));
                $data['customer_id'] =$request->customer;
                $data['dni_exi']= 'DNI';
                $data['created_by'] = config('user')['user_id'];
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $dni_master=$this->fgs_dni->insert_data($data);
                foreach($request->pi_id as $pi_id)
                {
                    $pi = fgs_pi_item_rel::where('master','=',$pi_id)->get();
                    foreach($pi as $pi_data)
                    {
                        $pi_item = fgs_pi_item::where('id','=',$pi_data['item'])->first();
                        $item['pi_id'] = $pi_data['master'];
                        $item['pi_item_id'] = $pi_data['item'];
                        $item['mrn_item_id'] = $pi_item['mrn_item_id'];
                        $item['created_at'] =  date('Y-m-d H:i:s');
                        $dni_item=$this->fgs_dni_item->insert_data($item);
                        if($dni_item && $dni_master ){
                            DB::table('fgs_dni_item_rel')->insert(['master'=>$dni_master,'item'=>$dni_item]);
                        }
                        $pi_item = fgs_pi_item::where('id','=',$pi_data['item'])->first();
                        $grs_item = fgs_grs_item::where('id','=',$pi_item['grs_item_id'])->first();
                        $maa_stock =  fgs_maa_stock_management::where('pi_item_id',$pi_item['id'])->first();
                        $stock['quantity'] =0;
                        $maa_stock_management = $this->fgs_maa_stock_management->update_data(['pi_item_id'=>$pi_item['id']],$stock);
                        //$maa_stock=$this->fgs_maa_stock_management->insert_data($stock);

                    }
                }
                if($dni_master)
                {
                    $request->session()->flash('success', "You have successfully added a DMI !");
                    return redirect('fgs/DNI-list');
                }
                else
                {
                    $request->session()->flash('error', "DMI insertion is failed. Try again... !");
                    return redirect('fgs/DNI-add');
                }
            }
            if($validator->errors()->all())
            {
                return redirect('fgs/DNI-add')->withErrors($validator)->withInput();
            }
        }
        else
        {
            return view('pages/FGS/DNI/DNI-add');
        }
    }


    public function fetchPI(Request $request)
    {
        $pi_masters =$this->fgs_pi->get_all_pi_for_dni(['customer_supplier.id'=>$request->customer_id]);
        if(count($pi_masters)>0)
        {
            $data = ' <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width:120px;">PI Number</th>
                                <th>GRS Numbers</th>
                                <th>PI Date</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">';
            foreach($pi_masters as $pi)
            {
                $grs_numbers = $this->get_grs_numbers($pi->id);
                $data.= '<tr>
                        <td><input type="checkbox" name="pi_id[]" value='.$pi->id.' ></td>
                        <td>'.$pi->pi_number.'</td>
                        <td>';
                foreach($grs_numbers as $grs)
                {
                    $data .= $grs->grs_number.'<br>';
                }
                $data.='</td>
                        <td>'.date('d-m-Y', strtotime($pi->pi_date)).'</td>
                        <td>'.$pi->firm_name.'</td>
                </tr>';
            }
            $data.= ' </tbody>
            </table>';
        return $data;
        }
        else 
        return 0;
    }

    public function get_grs_numbers($pi_id)
    {
        $grs_numbers = fgs_pi_item_rel::leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                                        ->leftJoin('fgs_grs','fgs_grs.id','=', 'fgs_pi_item.grs_id')
                                        ->select('fgs_grs.grs_number')
                                        ->where('fgs_pi_item_rel.master','=',$pi_id)
                                        ->get();
        return $grs_numbers;                                
    }

    public function DNIitemlist($dni_id)
    {
        $dni_items = fgs_dni_item_rel::select('fgs_dni_item.pi_id','fgs_pi.pi_number','fgs_pi.pi_date')
                            ->leftJoin('fgs_dni_item','fgs_dni_item.id','fgs_dni_item_rel.item')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                            //->distinct('fgs_dni_item.pi_id')
                            ->where('master','=',$dni_id)->get();
        foreach($dni_items as $items)
        {
            $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
            'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number',
            'fgs_pi_item.remaining_qty_after_cancel')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                            ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                            ->where('fgs_pi_item_rel.master','=', $items['pi_id'])
                            ->where('fgs_grs.status','=',1)
                            ->orderBy('fgs_grs_item.id','DESC')
                            ->distinct('fgs_grs_item.id')
                            ->get();
            $items['pi_item'] = $pi_item;
        }
       // print_r(json_encode($dni_items));exit;
        return view('pages/FGS/DNI/DNI-item-list',compact('dni_items'));
    }
    public function DNIpdf($dni_id)
    {
        $data['dni'] = $this->fgs_dni->get_single_dni(['fgs_dni.id' => $dni_id]);
        $data['dni_items'] = fgs_dni_item_rel::select('fgs_dni_item.pi_id','fgs_pi.pi_number','fgs_pi.pi_date')
                            ->leftJoin('fgs_dni_item','fgs_dni_item.id','fgs_dni_item_rel.item')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                            ->where('master','=',$dni_id)
                            ->distinct('fgs_dni_item_rel.id')
                            ->get();
        foreach($data['dni_items'] as $items)
        {
            $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
            'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity as quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number',
            'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id','fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_number','fgs_oef.order_date',
            'order_fulfil.order_fulfil_type','transaction_type.transaction_name','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_product_category.category_name',
            'fgs_pi_item.remaining_qty_after_cancel','product_price_master.mrp')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                            ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_grs.oef_id')
                            ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                            ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                            ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                            ->leftjoin('product_price_master','product_price_master.product_id','=','product_product.id')
                            ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_pi_item.mrn_item_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                            ->where('fgs_pi_item_rel.master','=', $items['pi_id'])
                            ->where('fgs_grs.status','=',1)
                            ->orderBy('fgs_grs_item.id','DESC')
                            //->distinct('fgs_grs_item.id')
                            ->get();
            $items['pi_item'] = $pi_item;
        }
        //print_r(json_encode($data['dni_items']));exit;
        //$data['items'] = $this->fgs_dni_item_rel->getAllItems(['fgs_dni_item_rel.master' => $dni_id]);
        $pdf = PDF::loadView('pages.FGS.DNI.pdf-view', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "DNI" . $data['dni']['dni_number'] . "_" . $data['dni']['dni_date'];
        return $pdf->stream($file_name . '.pdf');
    }

    public function netBillingReport(Request $request)
    {
        $condition = [];
        if($request->type=='EXI')
        {
            $condition = ['fgs_dni.dni_exi'=>'EXI'];
        }
        if($request->type=='DNI')
        {
            $condition = ['fgs_dni.dni_exi'=>'DNI'];
        }
        if($request->dni_number)
        {
            $condition[] = ['fgs_dni.dni_number','like', '%' . $request->dni_number . '%'];
        }
        if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        $dni_items = fgs_dni_item::select('fgs_dni_item.*','fgs_dni.dni_number','fgs_dni.dni_date','fgs_pi.pi_number','fgs_pi.pi_date','fgs_grs.grs_number','fgs_grs.grs_date',
        'fgs_pi_item.remaining_qty_after_cancel','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date',
        'fgs_mrn_item.expiry_date','fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef_item.rate','fgs_oef_item.discount', 'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id',
        'fgs_oef.order_number','fgs_oef.order_date','order_fulfil.order_fulfil_type','transaction_type.transaction_name','fgs_product_category.category_name','customer_supplier.firm_name','customer_supplier.shipping_address',
        'customer_supplier.billing_address','zone.zone_name')
                                    ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','=','fgs_dni_item.id')
                                    ->leftJoin('fgs_dni','fgs_dni.id','=','fgs_dni_item_rel.master')
                                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_dni.customer_id')
                                    ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                                    ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_dni_item.pi_item_id')
                                    ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                                    ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                                    ->leftjoin('product_product','product_product.id','=','fgs_pi_item.product_id')
                                    ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_pi_item.mrn_item_id')
                                    ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_pi_item.batchcard_id')
                                    ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                                    ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_grs.oef_id')
                                    ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                                    ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                                    ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
                                    ->leftJoin('zone','zone.id','customer_supplier.zone')
                                    ->where($condition)
                                    ->where('fgs_pi_item.cpi_status','=',0)
                                    ->orderBy('fgs_dni_item.id','DESC')
                                    ->distinct('fgs_dni_item.id')
                                    ->paginate(15);
        return view('pages/FGS/DNI/net-billing-report',compact('dni_items'));
    }
     
    public function netBillingExport(Request $request)
    {
        $condition = [];
        if($request->type=='EXI')
        {
            $condition = ['fgs_dni.dni_exi'=>'EXI'];
        }
        if($request->type=='DNI')
        {
            $condition = ['fgs_dni.dni_exi'=>'DNI'];
        }
        if($request->dni_number)
        {
            $condition[] = ['fgs_dni.dni_number','like', '%' . $request->dni_number . '%'];
        }
        if($request->sku_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->sku_code . '%'];
        }
        $dni_items = fgs_dni_item::select('fgs_dni_item.*','fgs_dni.dni_number','fgs_dni.dni_date','fgs_pi.pi_number','fgs_pi.pi_date','fgs_grs.grs_number','fgs_grs.grs_date',
        'fgs_pi_item.remaining_qty_after_cancel','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date',
        'fgs_mrn_item.expiry_date','fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef_item.rate','fgs_oef_item.discount', 'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id',
        'fgs_oef.order_number','fgs_oef.order_date','order_fulfil.order_fulfil_type','transaction_type.transaction_name','fgs_product_category.category_name','customer_supplier.firm_name','customer_supplier.shipping_address',
        'customer_supplier.billing_address','zone.zone_name')
                                    ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','=','fgs_dni_item.id')
                                    ->leftJoin('fgs_dni','fgs_dni.id','=','fgs_dni_item_rel.master')
                                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_dni.customer_id')
                                    ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                                    ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_dni_item.pi_item_id')
                                    ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                                    ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                                    ->leftjoin('product_product','product_product.id','=','fgs_pi_item.product_id')
                                    ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_pi_item.mrn_item_id')
                                    ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_pi_item.batchcard_id')
                                    ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                                    ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_grs.oef_id')
                                    ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                                    ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                                    ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
                                    ->leftJoin('zone','zone.id','customer_supplier.zone')
                                    ->where($condition)
                                    ->where('fgs_pi_item.cpi_status','=',0)
                                    ->orderBy('fgs_dni_item.id','DESC')
                                    ->distinct('fgs_dni_item.id')
                                    ->get();
        return Excel::download(new NetBillingExport($dni_items), 'NetBillingReport' . date('d-m-Y') . '.xlsx');
    }
    public function dni_transaction(Request $request)
    {
        $condition=[];
        if($request->dni_no)
        {
            $condition[] = ['fgs_dni.dni_number','like', '%' . $request->dni_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_dni_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        $items=fgs_dni_item::select('fgs_dni.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'fgs_dni.dni_number','fgs_dni.dni_date','fgs_dni.created_at as min_wef','fgs_dni_item.id as dni_item_id')
            ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
            ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_dni_item.mrn_item_id')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_mrn_item.product_id')
           // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_dni_item.batchcard_id')
            //->where('fgs_min_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            ->where('fgs_dni.dni_exi','DNI')
            //->distinct('fgs_min_item.id')
            ->orderBy('fgs_dni_item.id','desc')
            ->paginate(15);
            
        return view('pages/FGS/DNI/DNI-transaction-list',compact('items'));

    }
    public function dni_transaction_export(Request $request)
    {
        $condition=[];
        if($request->dni_no)
        {
            $condition[] = ['fgs_dni.dni_number','like', '%' . $request->dni_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_dni_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        $items=fgs_dni_item::select('fgs_dni.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'fgs_dni.dni_number','fgs_dni.dni_date','fgs_dni.created_at as min_wef','fgs_dni_item.id as dni_item_id')
            ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
            ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
            ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_dni_item.mrn_item_id')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_mrn_item.product_id')
           // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_dni_item.batchcard_id')
            //->where('fgs_min_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            ->where('fgs_dni.dni_exi','DNI')
            //->distinct('fgs_min_item.id')
            ->orderBy('fgs_dni_item.id','desc')
            ->get();
            
            return Excel::download(new FGSdnitransactionExport($items), 'FGS-DNI-transaction' . date('d-m-Y') . '.xlsx');

    }
    public function get_oef_details($id){
        $oef=fgs_dni::select('fgs_dni.id as dniid','fgs_oef.*')
        ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.master', '=', 'fgs_dni.id')
        ->leftJoin('fgs_dni_item', 'fgs_dni_item.id', '=', 'fgs_dni_item_rel.item')
        ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
        ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
        ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
        ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
        ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
        ->where('fgs_dni.id',$id)
        ->distinct('fgs_oef.oef_number')
        ->get();

        return $oef;

    }
}
