<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\FGS\fgs_pi;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_grs_item_rel;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_dni_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_multiple_pi;
use App\Models\FGS\fgs_multiple_pi_item;    
use App\Models\FGS\fgs_multiple_pi_item_rel;
use App\Models\product;
use App\Models\PurchaseDetails\customer_supplier;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendingPIExport;
use App\Exports\FGSpitransactionExport;
use App\Exports\FGScpitransactionExport;
class PIController extends Controller
{
    public function __construct()
    {
        $this->fgs_pi = new fgs_pi;
        $this->fgs_grs = new fgs_grs;
        $this->fgs_grs_item_rel = new fgs_grs_item_rel;
        $this->fgs_grs_item = new fgs_grs_item;
        $this->fgs_pi_item = new fgs_pi_item;
        $this->fgs_pi_item_rel = new fgs_pi_item_rel;
        $this->fgs_maa_stock_management = new fgs_maa_stock_management;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_multiple_pi = new fgs_multiple_pi;
        $this->fgs_multiple_pi_item = new fgs_multiple_pi_item;
        $this->fgs_multiple_pi_item_rel = new fgs_multiple_pi_item_rel;
        $this->product = new product;
    }
    public function PIList(Request $request)
    {
        $condition =[];
        if($request->pi_number)
        {
            $condition[] = ['fgs_pi.pi_number','like', '%' . $request->pi_number . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_pi.pi_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_pi.pi_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $pi = fgs_pi::select('fgs_pi.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address',
        'customer_supplier.contact_person','customer_supplier.contact_number')
                ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                //->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.master','=','fgs_pi.id')
                //->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                //->leftJoin('fgs_grs','fgs_grs.id','fgs_pi_item.grs_id')
                //->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                ->where($condition)
                ->distinct('fgs_pi.id')
                ->orderBy('fgs_pi.id','DESC')
                ->where('fgs_pi.status','=',1)
                ->paginate(15);
               
        return view('pages/FGS/PI/PI-list', compact('pi'));
    }
    public function getGRSInfo($pi_id)
    {
        $grs_info = fgs_grs::select('fgs_grs.grs_number','fgs_grs.grs_date')
                         ->leftJoin('fgs_pi_item','fgs_pi_item.grs_id','=','fgs_grs.id')
                        ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->where('fgs_pi.id','=',$pi_id)
                        ->where('fgs_grs.status','=',1)
                        ->distinct('fgs_grs.id')
                        ->get();
        return $grs_info;

    }
    public function getOEFInfo($pi_id)
    {
        $oef_info = fgs_oef::select('fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_date','fgs_oef.order_number')
                        ->leftJoin('fgs_grs','fgs_grs.oef_id','fgs_oef.id')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.grs_id','=','fgs_grs.id')
                        ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->where('fgs_pi.id','=',$pi_id)
                        ->where('fgs_oef.status','=',1)
                        ->distinct('fgs_oef.id')
                        ->get();
        return $oef_info;

    }
    public function PIAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['customer_id'] = ['required'];
            $validation['pi_date'] = ['required'];
            $validation['grs_item_id'] = ['required'];
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
                $data['pi_number'] = "PI-".$this->year_combo_num_gen(DB::table('fgs_pi')->where('fgs_pi.pi_number', 'LIKE', 'PI-'.$years_combo.'%')->count()); 
                $data['pi_date'] = date('Y-m-d',strtotime($request->pi_date));
                $data['customer_id'] =$request->customer_id;
                $data['created_by'] = config('user')['user_id'];
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $pi_master=$this->fgs_pi->insert_data($data);
                foreach($request->grs_item_id as $grs_item_id)
                {
                    //print_r($request->grs_item_id);exit;
                    $grs_item = fgs_grs_item::where('id','=',$grs_item_id)->first();
                    $grs = fgs_grs_item_rel::select('fgs_grs_item_rel.item','fgs_grs_item_rel.master')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_grs_item_rel.item')
                            // ->where('cgrs_status','=',0)
                            ->where('fgs_grs_item.id','=',$grs_item['id'])->first();
                    $item['grs_id'] = $grs['master'];
                    $item['grs_item_id'] = $grs_item['id'];
                    $item['mrn_item_id'] = $grs_item['mrn_item_id'];
                    $item['batchcard_id'] =$grs_item['batchcard_id'];
                    $item['product_id'] =$grs_item['product_id'];
                    $item['batch_qty'] =$grs_item['remaining_qty_after_cancel'];
                    //$item['remaining_qty_after_cancel'] =$grs_item['remaining_qty_after_cancel'];
                    $item['created_at'] =  date('Y-m-d H:i:s');
                    if($grs_item['current_invoice_qty']==0)
                    {
                        $item['batch_qty']= $grs_item['remaining_qty_after_cancel'];
                        $item['remaining_qty_after_cancel'] =$grs_item['qty_to_invoice'];
                    }
                    else
                    {
                        $item['batch_qty']= $grs_item['current_invoice_qty'];
                        $item['remaining_qty_after_cancel'] =$grs_item['current_invoice_qty'];
                    }
                    $pi_item=$this->fgs_pi_item->insert_data($item);
                    if($pi_item && $pi_master ){
                        DB::table('fgs_pi_item_rel')->insert(['master'=>$pi_master,'item'=>$pi_item]);
                    }
                    //$grs_item = fgs_grs_item::where('id','=',$grs_item['id'])->first();
                    $stock['product_id'] =$grs_item['product_id'];
                    $stock['pi_item_id'] =$pi_item;
                    $stock['batchcard_id'] =$grs_item['batchcard_id'];
                   /* if($grs_item['current_invoice_qty']==0)
                    {
                        $stock['quantity'] =$grs_item['qty_to_invoice'];
                    }
                    else
                    {
                        $stock['quantity'] =$grs_item['current_invoice_qty'];
                    }
                    $stock['created_at'] =  date('Y-m-d H:i:s');
                    $maa_stock=$this->fgs_maa_stock_management->insert_data($stock);*/
                    $fgs_grs_data = fgs_grs::where('id','=',$grs['master'])->first();
                    /*$fgs_product_stock = fgs_product_stock_management::where('product_id','=',$grs_item['product_id'])
                                        ->where('batchcard_id','=',$grs_item['batchcard_id'])
                                        ->where('stock_location_id','=',$fgs_grs_data->stock_location1)
                                        ->first();*/
                    if($grs_item['current_invoice_qty']==0)
                    {
                       // $update_stock = $fgs_product_stock['quantity']-$grs_item['qty_to_invoice'];
                        $grs_item_update = $this->fgs_grs_item->update_data(['fgs_grs_item.id'=>$grs_item->id], ['fgs_grs_item.current_invoice_qty'=>0,'fgs_grs_item.qty_to_invoice'=>0,'fgs_grs_item.remaining_qty_after_cancel'=>0]);
                    }
                    else
                    {
                        //$update_stock = $fgs_product_stock['quantity']-$grs_item['current_invoice_qty'];
                        $grs_stock = $grs_item['qty_to_invoice'] - $grs_item['current_invoice_qty'];
                        $grs_item_update = $this->fgs_grs_item->update_data(['fgs_grs_item.id'=>$grs_item->id], ['fgs_grs_item.current_invoice_qty'=>0,'fgs_grs_item.qty_to_invoice'=>$grs_stock,'fgs_grs_item.remaining_qty_after_cancel'=>$grs_stock]);
                    }
                    //$production_stock = $this->fgs_product_stock_management->update_data(['id'=>$fgs_product_stock['id']],['quantity'=>$update_stock]);
                    //$grs_item_update = $this->fgs_grs_item->update_data(['fgs_grs_item.id'=>$request->grs_item_id], ['fgs_grs_item.current_invoice_qty'=>0]);

                   
                }
                if($pi_master)
                {
                    $request->session()->flash('success', "You have successfully added a PI !");
                    return redirect('fgs/PI-list');
                }
                else
                {
                    $request->session()->flash('error', "PI insertion is failed. Try again... !");
                    return redirect('fgs/PI-add');
                }

            }
            else
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        else
        {
            if($request->customer)
            {
                $condition = ['customer_supplier.id'=>$request->customer];
                $customer =customer_supplier::find($request->customer);
                $grs_items = $this->fgs_grs_item->get_all_grs_item_for_pi(['customer_supplier.id'=>$request->customer]);
                //echo $grs_items;exit;
                return view('pages/FGS/PI/PI-add',compact('grs_items','customer'));
            }
            else
            {
                return view('pages/FGS/PI/PI-add');
            } 
        }
    }

    public function PIitemlist($pi_id)
    {
        $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','product_product.sku_code','product_product.hsn_code','product_product.discription','fgs_pi_item.remaining_qty_after_cancel',
        'batchcard_batchcard.batch_no','fgs_pi_item.batch_qty','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi_item.id as pi_item_id')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                        ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                        ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_pi_item.mrn_item_id')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_mrn_item.batchcard_id')
                        ->where('fgs_pi_item_rel.master','=', $pi_id)
                        ->where('fgs_grs.status','=',1)
                        ->where('fgs_pi.status','=',1)
                        ->where('fgs_pi_item.status','=',1)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->paginate(15);
        return view('pages/FGS/PI/PI-item-list',compact('pi_item'));
    }

    public function fetchGRS(Request $request)
    {
        $grs_items = $this->fgs_grs_item->get_all_grs_item_for_pi(['customer_supplier.id'=>$request->customer_id]);
        if(count($grs_items)>0)
        {
            $data = ' <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="item-select-radio  check-all"></th>
                                <th style="width:120px;">SKU Code</th>
                                <th>Description</th>
                                <th>HSN Code</th>
                                <th>GRS NUMBER</th>
                                <th>GRS Date</th>
                                <th>Customer</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">';
            foreach($grs_items as $items)
            {
                $data.= '<tr>
                        <td><input type="checkbox" class="check_pi" name="grs_id[]" value='.$items->id.' ></td>
                        <td>'.$items->sku_code.'</td>
                        <td>'.$items->discription.'</td>
                        <td>'.$items->hsn_code.'</td>
                        <td>'.$items->grs_number.'</td>
                        <td>'.date('d-m-Y', strtotime($items->grs_date)).'</td>
                        <td>'.$items->firm_name.'</td>
                        <td>'.$items->remaining_qty_after_cancel.'Nos</td>
                        
                </tr>';
            }
            $data.= ' </tbody>
            </table>';
        return $data;
        }
        else 
        return 0;
    }
    // public function fetchGRS(Request $request)
    // {
    //     $grs_masters =$this->fgs_grs->get_all_grs_for_pi(['customer_supplier.id'=>$request->customer_id]);
    //     if(count($grs_masters)>0)
    //     {
    //         $data = ' <table class="table table-bordered mg-b-0" id="example1">
    //                     <thead>
    //                         <tr>
    //                             <th></th>
    //                             <th style="width:120px;">GRS Number</th>
    //                             <th>OEF Number</th>
    //                             <th>Product category</th>
    //                             <th>STOCK LOCATION1(DECREASE)</th>
    //                             <th>STOCK LOCATION2(INCREASE)</th>
    //                             <th>GRS Date</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody id="table-body">';
    //         foreach($grs_masters as $grs)
    //         {
    //             $data.= '<tr>
    //                     <td><input type="checkbox" name="grs_id[]" value='.$grs->id.' ></td>
    //                     <td>'.$grs->grs_number.'</td>
    //                     <td>'.$grs->oef_number.'</td>
    //                     <td>'.$grs->category_name.'</td>
    //                     <td>'.$grs->location_name1.'</td>
    //                     <td>'.$grs->location_name2.'</td>
    //                     <td>'.date('d-m-Y', strtotime($grs->grs_date)).'</td>
    //             </tr>';
    //         }
    //         $data.= ' </tbody>
    //         </table>';
    //     return $data;
    //     }
    //     else 
    //     return 0;
    // }

    public function PIpdf($pi_id)
    { 
        $data['pi'] = $this->fgs_pi->get_single_pi(['fgs_pi.id' => $pi_id]);
        $data['items'] = $this->fgs_pi_item_rel->getAllItems(['fgs_pi_item_rel.master' => $pi_id]);
        $pdf = PDF::loadView('pages.FGS.PI.pdf-view', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "PI" . $data['pi']['pi_number'] . "_" . $data['pi']['pi_date'];
        return $pdf->stream($file_name . '.pdf');
    }
     public function PIPaymentpdf($pi_id)
    {
        $data['pi'] = $this->fgs_pi->get_single_pi(['fgs_pi.id' => $pi_id]);
        $data['items'] = $this->fgs_pi_item_rel->getAllItems(['fgs_pi_item_rel.master' => $pi_id]);
        
        $pdf = PDF::loadView('pages.FGS.PI.payment-pdf-view', $data);
        $pdf->set_paper('A4', 'portrait');
        $file_name = "PaymentPI" . $data['pi']['pi_number'] . "_" . $data['pi']['pi_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    public function pendingPI(Request $request)
    {
        $condition = [];
        if($request->order_no)
        {
            $condition[] = ['fgs_oef.order_number','like', '%' . $request->order_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_pi.pi_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_pi.pi_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        if($request->pi_no)
        {
            $condition[] = ['fgs_pi.pi_number','like', '%' . $request->pi_no . '%'];
        }
        $pi_items = fgs_pi_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
        'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number','fgs_pi.pi_date',
        'fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_date','fgs_oef.order_number','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_pi_item.batch_qty',
        'fgs_pi_item.remaining_qty_after_cancel','fgs_pi.created_at as pi_created_at','customer_supplier.firm_name','fgs_product_category.category_name')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                        ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                        ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                        ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
                        ->where($condition)
                        ->whereNotIn('fgs_pi.id',function($query) {

                            $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
                        
                        })->where('fgs_grs.status','=',1)
                        ->where('fgs_pi.status','=',1)
                        ->where('fgs_pi_item.status','=',1)
                        ->where('fgs_pi_item.cpi_status','=',0)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->paginate(15);
        return view('pages/FGS/PI/pending-pi',compact('pi_items'));
    }

    public function getIndianCurrencyInt(int $number)
    {
        
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        // $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
       // $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . '' : '';
        //return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
        return ($Rupees);
    }

    public function mergedPIList(Request $request)
    {
        $condition =[];
        if($request->merged_pi_number)
        {
            $condition[] = ['fgs_multiple_pi.merged_pi_name','like', '%' . $request->merged_pi_number . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_multiple_pi.created_at', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_multiple_pi.created_at', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $mergedpi = fgs_multiple_pi::select('fgs_multiple_pi.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address',
        'customer_supplier.contact_person','customer_supplier.contact_number','user.f_name','user.l_name')
                ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_multiple_pi.customer_id')
                ->leftJoin('user','user.user_id','=','fgs_multiple_pi.created_by')
                ->where($condition)
                ->distinct('fgs_multiple_pi.id')
                ->paginate(15);
               
        return view('pages/FGS/PI/multiple-pi-list', compact('mergedpi'));

    }
    public function mergeMutiplePI(Request $request)
    {
        $condition = [];
        if ($request->pi_no) {
            $condition[] = ['fgs_pi.pi_no', 'like', '%'.$request->pi_no.'%'];
        }
        if ($request->customer) {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        $data['pi'] = fgs_pi::select('fgs_pi.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address',
        'customer_supplier.contact_person','customer_supplier.contact_number','fgs_oef.order_number','fgs_oef.order_date','fgs_grs.grs_number','fgs_grs.grs_date',
        'fgs_oef.oef_number','fgs_oef.oef_date')
                ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.master','=','fgs_pi.id')
                ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                ->leftJoin('fgs_grs','fgs_grs.id','fgs_pi_item.grs_id')
                ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                ->where($condition)
                ->whereNotIn('fgs_pi.id',function($query) {

                    $query->select('fgs_multiple_pi_item.pi_id')->from('fgs_multiple_pi_item');
                
                })
                //  ->whereNotIn('fgs_pi.id',function($query) {

                //     $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
                
                // })
                ->distinct('fgs_pi.id')
                ->paginate(15);
            
        
        //if($request->customer )
        return view('pages/FGS/PI/multiple-pi-add',compact('data'));
       // else
       // return view('pages/FGS/PI/multiple-pi-add');
    }
    public function mergePIInsert(Request $request)
    {
        $validation['pi_id'] = ['required'];
        $validator = Validator::make($request->all(), $validation);
        if(!$validator->errors()->all()){
           // $request->pi_id[0];
            $pi = fgs_pi::find($request->pi_id[0]); 
            if(date('m')==01 || date('m')==02 || date('m')==03)
            {
                $years_combo = date('y', strtotime('-1 year')).date('y');
            }
            else
            {
                $years_combo = date('y').date('y', strtotime('+1 year'));
            }
            $data['merged_pi_name'] = "MPI-".$this->year_combo_num_gen(DB::table('fgs_multiple_pi')->where('merged_pi_name','like','%MPI-'.$years_combo.'%')
                                                ->count(),1);
                
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['status'] = 1;
           $data['customer_id'] = $pi->customer_id;
            $data['created_by'] = config('user')['user_id'];
            $fgs_multiple_pi_master = $this->fgs_multiple_pi->insert_data($data);

            foreach($request->pi_id as $pi_id)
            {
                $item['pi_id']= $pi_id;
                $item['status'] = 1;
                $item['created_at'] = date('Y-m-d H:i:s');
                $fgs_multiple_pi_item = $this->fgs_multiple_pi_item->insert_data($item);
                $fgs_multiple_pi_item_rel = fgs_multiple_pi_item_rel::create([
                    'master'=>$fgs_multiple_pi_master,
                    'item' => $fgs_multiple_pi_item,
                ]);
            }
            $request->session()->flash('success', "You have successfully merged a multiple PI !");
            return redirect('fgs/merged-PI-list')->withErrors($validator)->withInput();
        }   
        if($validator->errors()->all()){
            return redirect('fgs/merge-multiple-PI')->withErrors($validator)->withInput();
        }

    }

    public function MergedPIPaymentpdf($mpi_id)
    {
        //$data['pi'] = $this->fgs_pi->get_single_pi(['fgs_pi.id' => $pi_id]);
        //$data['items'] = $this->fgs_pi_item_rel->getAllItems(['fgs_pi_item_rel.master' => $pi_id]);
        $data['mpi'] = fgs_multiple_pi::select('fgs_multiple_pi.*','customer_supplier.firm_name','customer_supplier.billing_address',
        'zone.zone_name','state.state_name','customer_supplier.city')
                         ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_multiple_pi.customer_id')
                         ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                        ->leftJoin('state','state.state_id','=','customer_supplier.state')
                         ->where('fgs_multiple_pi.id','=',$mpi_id)
                         ->first();
        $data['pis'] = fgs_pi::select('fgs_pi.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address',
                         'customer_supplier.contact_person','customer_supplier.contact_number','fgs_oef.order_number','fgs_oef.order_date','fgs_grs.grs_number','fgs_grs.grs_date',
                         'fgs_oef.oef_number','fgs_oef.oef_date')
                                 ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                                 ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.master','=','fgs_pi.id')
                                 ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                                 ->leftJoin('fgs_grs','fgs_grs.id','fgs_pi_item.grs_id')
                                 ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                                 ->distinct('fgs_pi.id')
                                 ->where('fgs_pi.status','=',1)
                                 ->get();
        $pdf = PDF::loadView('pages.FGS.PI.multiple-pi-payment-pdf', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "PaymentPI" . $data['mpi']['merged_pi_name'] . "_" . $data['mpi']['firm_name'];
        return $pdf->stream($file_name . '.pdf');
    }

    public function getPIItems($pi_id)
    {
        $items = $this->fgs_pi_item_rel->getAllItems(['fgs_pi_item_rel.master' => $pi_id]);
        return $items;
    }
    public function pendingPIExport(Request $request)
    {
        $condition = [];
        if($request->order_no)
        {
            $condition[] = ['fgs_oef.order_number','like', '%' . $request->order_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_pi.pi_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_pi.pi_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        if($request->pi_no)
        {
            $condition[] = ['fgs_pi.pi_number','like', '%' . $request->pi_no . '%'];
        }
        // $pi = fgs_pi::select('fgs_pi.*','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.billing_address',
        //             'customer_supplier.contact_person','customer_supplier.contact_number','fgs_oef.order_number','fgs_oef.order_date','fgs_grs.grs_number','fgs_grs.grs_date',
        //             'fgs_oef.oef_number','fgs_oef.oef_date')
        //                     ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
        //                     ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.master','=','fgs_pi.id')
        //                     ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
        //                     ->leftJoin('fgs_grs','fgs_grs.id','fgs_pi_item.grs_id')
        //                     ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
        //                     ->where($condition)
        //                     ->whereNotIn('fgs_pi.id',function($query) {

        //                         $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
                            
        //                     })->where('fgs_pi.status','=',1)
        //                     ->distinct('fgs_pi.id')
        //                     ->paginate(15);
        $pi_data = fgs_pi_item_rel::select('fgs_grs.grs_number','fgs_grs.grs_date','product_product.sku_code','product_product.hsn_code','product_product.discription',
        'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code','fgs_pi.pi_number',
        'fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_date','fgs_oef.order_number','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_pi_item.batch_qty',
        'fgs_pi_item.remaining_qty_after_cancel','fgs_pi.created_at as pi_created_at','customer_supplier.firm_name')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                        ->leftJoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                        ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                        ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                        ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                        //->where('fgs_pi_item_rel.master','=', $items['pi_id'])
                        ->where($condition)
                        ->whereNotIn('fgs_pi.id',function($query) {

                            $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
                        
                        })->where('fgs_grs.status','=',1)
                        ->where('fgs_pi.status','=',1)
                        ->where('fgs_pi_item.status','=',1)
                        ->where('fgs_pi_item.cpi_status','=',0)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->get();
        return Excel::download(new PendingPIExport($pi_data), 'PIBackOrderReport' . date('d-m-Y') . '.xlsx');
        
    }
    public function PartialPI(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $validation['grs_item_id'] = ['required'];
            $validation['partial_invoice_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $grs_item = fgs_grs_item::where('id', $request->grs_item_id)->first();
                $qty_update = $grs_item['qty_to_invoice']-$request->partial_invoice_qty;
                $grs_item_update = $this->fgs_grs_item->update_data(['fgs_grs_item.id'=>$request->grs_item_id], ['fgs_grs_item.current_invoice_qty'=>$request->partial_invoice_qty]);
                $request->session()->flash('success', "You have successfully updated a  Partial PI quantity !");
                // if($request->order_type)
                // return redirect("inventory/supplier-invoice-add?order_type=".$request->order_type);
                // else
                // return redirect("inventory/supplier-invoice-add");
                return redirect()->back();                  
            }
        }




    }
    public function pi_transaction(Request $request)
    {
        $condition = [];
        if ($request->pi_no) {
            $condition[] = ['fgs_pi.pi_number', 'like', '%' . $request->pi_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_pi_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }
        $items = fgs_pi_item::select(
            'fgs_pi.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_pi.pi_number',
            'fgs_pi.pi_date',
            'fgs_pi.created_at as min_wef',
            'fgs_pi_item.id as pi_item_id'
        )
            ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
            ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_pi_item.product_id')
            // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
            //->where('fgs_min_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_pi_item.status',1)
            //->distinct('fgs_min_item.id')
            ->orderBy('fgs_pi_item.id', 'desc')
            ->paginate(15);
        return view('pages/FGS/PI/PI-transaction-list', compact('items'));
    }
    public function pi_transaction_export(Request $request)
    {
        $condition = [];
        if ($request->pi_no) {
            $condition[] = ['fgs_pi.pi_number', 'like', '%' . $request->pi_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_pi_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }
        $items = fgs_pi_item::select(
            'fgs_pi.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_pi.pi_number',
            'fgs_pi.pi_date',
            'fgs_pi.created_at as min_wef',
            'fgs_pi_item.id as pi_item_id'
        )
            ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
            ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_pi_item.product_id')
            // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
            //->where('fgs_min_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_pi_item.status',1)
            //->distinct('fgs_min_item.id')
            ->orderBy('fgs_pi_item.id', 'desc')
            ->get();
        return Excel::download(new FGSpitransactionExport($items), 'FGS-PI-transaction' . date('d-m-Y') . '.xlsx');
    }

    public function piExistInDNI($pi_id)
    {
        $dni_item = fgs_dni_item::where('pi_id','=',$pi_id)->get();
        if(count($dni_item)>0)
        return 1;
        else
        return 0;
    }
    public function piItemExistInDNI($pi_item_id)
    {
        $dni_item = fgs_dni_item::where('pi_item_id','=',$pi_item_id)->get();
        if(count($dni_item)>0)
        return 1;
        else
        return 0;
    }
    public function PIItemDelete($pi_item_id,Request $request)
    {
        $pi_item = fgs_pi_item::where('id','=',$pi_item_id)->first();
        $grs_item = fgs_grs_item::find($pi_item->grs_item_id);
        $update_qty = $grs_item->qty_to_invoice+$pi_item->remaining_qty_after_cancel;
        $update_grs_item = fgs_grs_item::where('id','=',$pi_item->grs_item_id)->update(['qty_to_invoice'=>$update_qty,'remaining_qty_after_cancel'=>$update_qty]);
        $delete = fgs_pi_item::where('id','=',$pi_item_id)->delete();
        fgs_pi_item_rel::where('item','=',$pi_item_id)->delete();
        if($delete)
        $request->session()->flash('success', "You have successfully deleted a PI Item !");
        else
        $request->session()->flash('error', "You have failed to delete PI Item !");
        //return redirect('fgs/PI-list');
        return redirect()->back();
    }

    public function PIDelete($pi_id,Request $request)
    {
        $pi = fgs_pi::where('id','=',$pi_id)->first();
        $dni_items = fgs_pi_item_rel::where('master','=',$pi_id)->get();
        if(count($dni_items)>0)
        {
            $request->session()->flash('error', "You can't deleted this PI(".$pi->pi_number.").It have items !");
        }
        else
        {
            $update = $this->fgs_pi->update_data(['id'=>$pi_id],['status'=>0]);
            $request->session()->flash('success', "You have successfully deleted a PI(".$pi->pi_number.") !");
        }
        return redirect('fgs/PI-list');
    }

    public function PIEdit($pi_id,Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['pi_id'] = ['required'];
            $validation['pi_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $pi = fgs_pi::find($pi_id);
                $update = $this->fgs_pi->update_data(['id'=>$pi_id],['pi_date'=>date('Y-m-d',strtotime($request->pi_date))]);
                if($update)
                $request->session()->flash('success', "You have successfully updated a PI(".$pi->pi_number.") !");
                else
                $request->session()->flash('error', "You have failed updated a PI(".$pi->pi_number.") !");
                return redirect('fgs/PI-list');
            }
            else
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        else
        {
            $pi = $this->fgs_pi->get_single_pi(['fgs_pi.id'=>$pi_id]);
            return view('pages/FGS/PI/PI-update',compact('pi'));

        }
    }
}
