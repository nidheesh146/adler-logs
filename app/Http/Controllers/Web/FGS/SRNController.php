<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\FGS\fgs_dni;
use App\Models\FGS\fgs_srn;
use App\Models\FGS\fgs_srn_item;
use App\Models\FGS\fgs_srn_item_rel;
use App\Models\FGS\fgs_dni_item_rel;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_dni_item;
use PDF;

class SRNController extends Controller
{
    public function __construct()
    {
        $this->fgs_dni = new fgs_dni;
        $this->fgs_srn = new fgs_srn;
        $this->fgs_srn_item = new fgs_srn_item;
        $this->fgs_dni_item = new fgs_dni_item;
        $this->fgs_srn_item_rel = new fgs_srn_item_rel;
        $this->fgs_dni_item_rel = new fgs_dni_item_rel;
        $this->fgs_pi_item_rel = new fgs_pi_item_rel;
        
    }
    public function SRNlist(Request $request)
    {
        $condition = [];
        $condition =[];
        if($request->srn_no)
        {
            $condition[] = ['fgs_srn.srn_number','like', '%' . $request->srn_no . '%'];
        }
        if($request->dni_no)
        {
            $condition[] = ['fgs_dni.dni_number','like', '%' . $request->dni_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_srn.srn_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_srn.srn_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $srn = $this->fgs_srn->get_all_srn($condition);
        return view('pages/FGS/SRN/SRN-list',compact('srn'));
       
    }
    public function SRNAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['dni_no'] = ['required'];
            $validation['srn_date'] = ['required','date'];
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
                $data['srn_number'] = "SRN-".$this->year_combo_num_gen(DB::table('fgs_srn')->where('fgs_srn.srn_number', 'LIKE', 'SRN-'.$years_combo.'%')->count()); 
                $data['srn_date'] = date('Y-m-d', strtotime($request->srn_date));
                $data['dni_id'] = $request->dni_no;
                $data['remarks'] = $request->remarks;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $srn_id = $this->fgs_srn->insert_data($data);
                $condition[] = ['fgs_dni_item_rel.master','=',$request->dni_no];
                $dni_item = $this->fgs_dni_item->getItems($condition);
                //print_r($dni_item);exit;
                foreach($dni_item as $item)
                {
                    //$srn_item['product_id'] = $item['product_id'];
                    $srn_item['dni_item_id'] = $item['id'];
                    $srn_item['mrn_item_id'] = $item['mrn_item_id'];
                    $srn_item['created_at'] =date('Y-m-d H:i:s');
                    $srn_item_id = $this->fgs_srn_item->insert_data($srn_item,$srn_id);
                }
                if($srn_id)
                {
                    $request->session()->flash('success', "You have successfully added a SRN !");
                    return redirect('fgs/SRN/item-list/'.$srn_id);
                }
                else
                {
                    $request->session()->flash('error', "SRN insertion is failed. Try again... !");
                    return redirect('fgs/SRN-add');
                }

            }
            else
            {
                return redirect('fgs/SRN-add')->withErrors($validator)->withInput();
            }
        }
        else
        {
            return view('pages/FGS/SRN/SRN-add');
        }
       
    }
    public function findDNINumberForSRN(Request $request)
    {
        if ($request->q) {
            $condition[] = ['fgs_dni.dni_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_dni->find_dni_num_for_srn($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->dni_details($request->id);
            exit;
        }
    }

    public function findDNIInfo(Request $request)
    {
        if ($request->q) {
            $condition[] = ['fgs_dni.dni_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_dni->find_dni_num_for_srn($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->dni_details($request->id,$request->category);
            exit;
        }
    }
    function dni_details($id,$category)
    {
        $condition1[] = ['fgs_dni.id','=',$id];
        $dni = $this->fgs_dni->get_single_dni($condition1);
        //return $invoice;
        $dni_items = fgs_dni_item_rel::select('fgs_dni_item.pi_id','fgs_pi.pi_number','fgs_pi.pi_date')
                        ->leftJoin('fgs_dni_item','fgs_dni_item.id','fgs_dni_item_rel.item')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                        ->where('master','=',$id)
                        ->orderBy('fgs_dni_item.id','ASC')
                        ->distinct('fgs_dni_item.id')
                        ->distinct('fgs_dni_item.pi_id')
                        ->get();
        foreach($dni_items as $items)
        {
            $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
            'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number')
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
                    ->where('fgs_grs_item.cgrs_status','=',0)
                    ->where('fgs_pi_item.cpi_status','=',0)
                    ->orderBy('fgs_grs_item.id','ASC')
                    ->distinct('fgs_grs_item.id')
                    ->get();
            $items['pi_item'] = $pi_item;
        }
        if($dni_items && $dni)
        {
        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                MTQ (' . $dni->dni_number . ')
                   </label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
            
                </thead>
                <tbody>
                    <tr>
                        <th>DNI Date</th>
                        <td>' . date('d-m-Y', strtotime($dni->dni_date)) . '</td>
                        <th>Customer</th>
                        <td>'. $dni->firm_name.'</td>
                        
                    </tr>
                    <tr>
                        <th>Zone</th>
                        <td>'. $dni->zone_name.'</td>
                        <th>State</th>
                        <td>'.$dni->state_name.'</td>
                        
                    </tr>
                    <tr>
                        <th>Billing Address</th>
                        <td>'.$dni->billing_address.'</td>
                        <th>Shipping Address</th>
                        <td>'.$dni->shipping_address.'</td>
                    </tr>
                </tbody>
           </table>
           <br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
               foreach($dni_items as $dni_item)
               {
                    $data .='<div style="width:50%;float:left;font-size:14px;font-weight:bold;">PI Number : '.$dni_item['pi_number'].'</div>
					<div style="width:50%;float:right;font-size:14px;font-weight:bold; text-align:right;">PI Date : '.date('d-m-Y', strtotime($dni_item['pi_date'])).'</div>
					<div class="table-responsive">
                        <table class="table table-bordered mg-b-0" >
							<thead>
								<tr>
									<th></th>
									<th>GRS Number</th>
                                    <th>Product</th>
									<th>Description</th>
									<th>HSN Code</th>
									<th>Batchcard</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>Net Value</th>
								</tr>
							</thead>
							<tbody id="prbody1">';
                            $i=1;
                            foreach($dni_item['pi_item'] as $item)
                            {
                                $data .= '<tr>
                                <td>'.$i++.'</td>
                                <td>'.$item['grs_number'].'</td>
                                <td>'.$item['sku_code'].'</td>
                                <td>'.$item['discription'].'</td>	
                                <td>'.$item['hsn_code'].'</td>
                                <td>'.$item['batch_no'].'</td>
                                <td>'.$item['batch_quantity'].'Nos</td>
                                <td>'.$item['rate']. '  ' .$item['currency_code'].'</td>
                                <td>'.$item['discount'].'%</td>
                                <td>'.($item['rate']*$item['batch_quantity'])-(($item['batch_quantity']*$item['discount']*$item['rate'])/100).' '.$item['currency_code'].'</td>
                            </tr>';
                            }
                            $data .= '</tbody>
                            </table>
                            <div class="box-footer clearfix">
                            
                            </div>
                        </div>
                        <br/>';
               }
       
            
      $data.= '</div>';
        return $data;
        }
        // else
        // return 0;
    }
    public function SRNitemlist(Request $request)
    {
        $srn_id = $request->srn_id;
        //echo $mis_id;exit;
        $srn_info = fgs_srn::find($request->srn_id);
        $srn_number = $srn_info['srn_number'];
        $condition[] = ['fgs_srn_item_rel.master','=',$request->srn_id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_srn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_srn_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        //$items = $this->fgs_srn_item->getSRNItems($condition);
        $srn_items = fgs_srn_item_rel::select('fgs_srn_item.dni_item_id','fgs_srn_item.id as srn_item_id','fgs_pi.pi_number','fgs_pi.pi_date','fgs_pi.id as pi_id','fgs_dni.dni_number','fgs_dni.dni_date')
                        ->leftJoin('fgs_srn_item','fgs_srn_item_rel.item','fgs_srn_item.id')
                        ->leftJoin('fgs_dni_item','fgs_dni_item.id','fgs_srn_item.dni_item_id')
                        ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','fgs_dni_item.id')
                        ->leftJoin('fgs_dni','fgs_dni.id','fgs_dni_item_rel.master')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                        ->where('fgs_srn_item_rel.master','=',$request->srn_id)
                        ->distinct('fgs_dni_item.pi_id')
                        ->distinct('fgs_srn_item.id')
                        ->orderBy('fgs_srn_item.id','ASC')
                        ->get();
        //print_r(json_encode($srn_items));exit;
        foreach($srn_items as $items)
        {
            // $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
            // 'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number')
            //         ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
            //         ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
            //         ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
            //         ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
            //         ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
            //         ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
            //         ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
            //         ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
            //         ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
            //         ->where('fgs_pi_item_rel.master','=', $items['pi_id'])
            //         ->where('fgs_grs.status','=',1)
            //         ->where('fgs_grs_item.cgrs_status','=',0)
            //         ->where('fgs_pi_item.cpi_status','=',0)
            //         ->orderBy('fgs_grs_item.id','DESC')
            //         ->distinct('fgs_grs_item.id')
            //         ->distinct('fgs_pi.id')
            //         ->get();
            $dni_item = fgs_dni_item::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
            'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_dni_item.pi_item_id')
                            ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_dni_item.pi_item_id')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                            ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                            ->where('fgs_dni_item.id','=', $items['dni_item_id'])
                            ->where('fgs_grs.status','=',1)
                            ->where('fgs_grs_item.cgrs_status','=',0)
                            ->where('fgs_pi_item.cpi_status','=',0)
                            ->orderBy('fgs_grs_item.id','ASC')
                            ->distinct('fgs_grs_item.id')
                            ->distinct('fgs_pi.id')
                            ->get();

            $items['dni_item'] = $dni_item;
        }
        //print_r(json_encode($srn_items));exit;
        return view('pages/FGS/SRN/SRN-item-list',compact('srn_id','srn_items','srn_number'));
    }
    public function SRNpdf($srn_id)
    {
        $data['srn'] = $this->fgs_srn->get_single_srn(['fgs_dni.id' => $srn_id]);
        $data['srn_items'] = fgs_srn_item_rel::select('fgs_srn_item.dni_item_id','fgs_srn_item.id as srn_item_id','fgs_pi.pi_number','fgs_pi.pi_date','fgs_pi.id as pi_id','fgs_dni.dni_number','fgs_dni.dni_date')
                        ->leftJoin('fgs_srn_item','fgs_srn_item_rel.item','fgs_srn_item.id')
                        ->leftJoin('fgs_dni_item','fgs_dni_item.id','fgs_srn_item.dni_item_id')
                        ->leftJoin('fgs_dni_item_rel','fgs_dni_item_rel.item','fgs_dni_item.id')
                        ->leftJoin('fgs_dni','fgs_dni.id','fgs_dni_item_rel.master')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_dni_item.pi_id')
                        ->where('fgs_srn_item_rel.master','=',$srn_id)
                        ->distinct('fgs_dni_item.pi_id')
                        ->distinct('fgs_srn_item.id')
                        ->orderBy('fgs_srn_item.id','ASC')
                        ->get();
        foreach($data['srn_items'] as $items)
        {
            $dni_item= fgs_dni_item::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
            'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity as quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number',
            'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id','fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_number','fgs_oef.order_date',
            'order_fulfil.order_fulfil_type','transaction_type.transaction_name','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_dni_item.pi_item_id')
                            ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_dni_item.pi_item_id')
                            ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                            ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                            ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                            ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_grs.oef_id')
                            ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                            ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                            ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_pi_item.mrn_item_id')
                            ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                            ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                            ->where('fgs_dni_item.id','=', $items['dni_item_id'])
                            ->where('fgs_grs.status','=',1)
                            ->where('fgs_grs_item.cgrs_status','=',0)
                            ->where('fgs_pi_item.cpi_status','=',0)
                            ->orderBy('fgs_grs_item.id','DESC')
                            ->distinct('fgs_grs_item.id')
                            ->distinct('fgs_pi.id')
                            ->get();

            $items['dni_item'] = $dni_item;
        }
        //print_r(json_encode($data['dni_items']));exit;
        //$data['items'] = $this->fgs_dni_item_rel->getAllItems(['fgs_dni_item_rel.master' => $dni_id]);
        $pdf = PDF::loadView('pages.FGS.SRN.pdf-view', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "SRN" . $data['srn']['srn_number'] . "_" . $data['srn']['srn_date'];
        return $pdf->stream($file_name . '.pdf');
    }
}
