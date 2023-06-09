<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\FGS\fgs_pi;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_grs_item_rel;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_multiple_pi;
use App\Models\FGS\fgs_multiple_pi_item;    
use App\Models\FGS\fgs_multiple_pi_item_rel;
use App\Models\product;
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
        'customer_supplier.contact_person','customer_supplier.contact_number','fgs_oef.order_number','fgs_oef.order_date','fgs_grs.grs_number','fgs_grs.grs_date',
        'fgs_oef.oef_number','fgs_oef.oef_date')
                ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.master','=','fgs_pi.id')
                ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                ->leftJoin('fgs_grs','fgs_grs.id','fgs_pi_item.grs_id')
                ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                ->where($condition)
                ->distinct('fgs_pi.id')
                ->paginate(15);
               
        return view('pages/FGS/PI/PI-list', compact('pi'));
    }
    public function PIAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['customer'] = ['required'];
            $validation['pi_date'] = ['required'];
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
                $data['customer_id'] =$request->customer;
                $data['created_by'] = config('user')['user_id'];
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $pi_master=$this->fgs_pi->insert_data($data);
                foreach($request->grs_id as $grs_id)
                {
                    $grs = fgs_grs_item_rel::select('fgs_grs_item_rel.item','fgs_grs_item_rel.master')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_grs_item_rel.item')
                            ->where('cgrs_status','=',0)
                            ->where('master','=',$grs_id)->get();
                    
                    foreach($grs as $grs_data)
                    {
                        $grs_item = fgs_grs_item::where('id','=',$grs_data['item'])->first();
                        //print_r($grs_item);exit;
                        $item['grs_id'] = $grs_data['master'];
                        $item['grs_item_id'] = $grs_data['item'];
                        $item['mrn_item_id'] = $grs_item['mrn_item_id'];
                        $item['batchcard_id'] =$grs_item['batchcard_id'];
                        $item['product_id'] =$grs_item['product_id'];
                        $item['batch_qty'] =$grs_item['remaining_qty_after_cancel'];
                        $item['remaining_qty_after_cancel'] =$grs_item['remaining_qty_after_cancel'];
                        $item['created_at'] =  date('Y-m-d H:i:s');
                        $pi_item=$this->fgs_pi_item->insert_data($item);
                        if($pi_item && $pi_master ){
                            DB::table('fgs_pi_item_rel')->insert(['master'=>$pi_master,'item'=>$pi_item]);
                        }
                        $grs_item = fgs_grs_item::where('id','=',$grs_data['item'])->first();
                        $stock['product_id'] =$grs_item['product_id'];
                        $stock['pi_item_id'] =$pi_item;
                        $stock['batchcard_id'] =$grs_item['batchcard_id'];
                        $stock['quantity'] =$grs_item['remaining_qty_after_cancel'];
                        $stock['created_at'] =  date('Y-m-d H:i:s');
                        $maa_stock=$this->fgs_maa_stock_management->insert_data($stock);
                        
                         $fgs_grs_data = fgs_grs::where('id','=',$grs_data['master'])->first();
                        $fgs_product_stock = fgs_product_stock_management::where('product_id','=',$grs_item['product_id'])
                                        ->where('batchcard_id','=',$grs_item['batchcard_id'])
                                        ->where('stock_location_id','=',$fgs_grs_data->stock_location1)
                                        ->first();

                            $update_stock = $fgs_product_stock['quantity']-$grs_item['batch_quantity'];
                            $production_stock = $this->fgs_product_stock_management->update_data(['id'=>$fgs_product_stock['id']],['quantity'=>$update_stock]);


                    }
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
                return redirect('fgs/PI-add')->withErrors($validator)->withInput();
            }
        }
        else
        {
            return view('pages/FGS/PI/PI-add');
        }
    }

    public function PIitemlist($pi_id)
    {
        $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','product_product.sku_code','product_product.hsn_code','product_product.discription','fgs_pi_item.remaining_qty_after_cancel',
        'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_oef_item.rate','fgs_oef_item.discount','currency_exchange_rate.currency_code')
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
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->paginate(15);
        return view('pages/FGS/PI/PI-item-list',compact('pi_item'));
    }

    public function fetchGRS(Request $request)
    {
        $grs_masters =$this->fgs_grs->get_all_grs_for_pi(['customer_supplier.id'=>$request->customer_id]);
        if(count($grs_masters)>0)
        {
            $data = ' <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width:120px;">GRS Number</th>
                                <th>OEF Number</th>
                                <th>Product category</th>
                                <th>STOCK LOCATION1(DECREASE)</th>
                                <th>STOCK LOCATION2(INCREASE)</th>
                                <th>GRS Date</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">';
            foreach($grs_masters as $grs)
            {
                $data.= '<tr>
                        <td><input type="checkbox" name="grs_id[]" value='.$grs->id.' ></td>
                        <td>'.$grs->grs_number.'</td>
                        <td>'.$grs->oef_number.'</td>
                        <td>'.$grs->category_name.'</td>
                        <td>'.$grs->location_name1.'</td>
                        <td>'.$grs->location_name2.'</td>
                        <td>'.date('d-m-Y', strtotime($grs->grs_date)).'</td>
                </tr>';
            }
            $data.= ' </tbody>
            </table>';
        return $data;
        }
        else 
        return 0;
    }

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
        $pdf->set_paper('A4', 'landscape');
        $file_name = "PaymentPI" . $data['pi']['pi_number'] . "_" . $data['pi']['pi_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    public function pendingPI(Request $request)
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
                    'customer_supplier.contact_person','customer_supplier.contact_number','fgs_oef.order_number','fgs_oef.order_date','fgs_grs.grs_number','fgs_grs.grs_date',
                    'fgs_oef.oef_number','fgs_oef.oef_date')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                            ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.master','=','fgs_pi.id')
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item')
                            ->leftJoin('fgs_grs','fgs_grs.id','fgs_pi_item.grs_id')
                            ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                            ->where($condition)
                            ->whereNotIn('fgs_pi.id',function($query) {

                                $query->select('fgs_dni_item.pi_id')->from('fgs_dni_item');
                            
                            })->where('fgs_pi.status','=',1)
                            ->distinct('fgs_pi.id')
                            ->paginate(15);
        return view('pages/FGS/PI/pending-pi',compact('pi'));
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
}
