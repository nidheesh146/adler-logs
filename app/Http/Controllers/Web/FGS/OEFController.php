<?php

namespace App\Http\Controllers\Web\fgs;
use Validator;
use DB;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_oef_item_rel;
use App\Models\FGS\transaction_type;
use App\Models\FGS\order_fulfil;
use App\Models\inventory_gst;
use App\Models\FGS\fgs_product_category;
use App\Models\PurchaseDetails\customer_supplier;
use App\Models\product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendingOEFExport;
use NumberFormatter;
class OEFController extends Controller
{
    public function __construct()
    {
        $this->fgs_oef = new fgs_oef;
        $this->fgs_oef_item = new fgs_oef_item;
        $this->fgs_product_category = new fgs_product_category;
        $this->fgs_oef_item_rel = new fgs_oef_item_rel;
        $this->transaction_type = new transaction_type;
        $this->order_fulfil = new order_fulfil;
        $this->inventory_gst = new inventory_gst;
        $this->product = new product;
        $this->customer_supplier = new customer_supplier;
    }
    public function OEFList(Request $request)
    {
        // $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        // echo $f->format(123456);
        //echo $this->getIndianCurrency('123456.78');
        //exit;
        $condition =[];
        if($request->oef_number)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_number . '%'];
        }
        if($request->order_number)
        {
            $condition[] = ['fgs_oef.order_number','like', '%' . $request->order_number . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_oef.oef_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_oef.oef_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $oef = fgs_oef::select('fgs_oef.*','order_fulfil.order_fulfil_type','transaction_type.transaction_name',
        'customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.contact_person',
        'customer_supplier.contact_number','fgs_product_category.category_name')
                        ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                        ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_oef.product_category')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                        ->where($condition)
                        ->distinct('fgs_oef.id')
                        ->orderBy('fgs_oef.id','DESC')
                        ->paginate(15);
        return view('pages/FGS/OEF/OEF-list', compact('oef'));
    }
    function getIndianCurrencyInt(int $number)
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
    function getIndianCurrency(float $number)
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
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . '' : '';
        //return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
        return ($Rupees .''.$paise);
    }


    public function OEFAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['customer'] = ['required'];
            $validation['order_number'] = ['required'];
            $validation['order_date'] = ['required','date'];
            $validation['oef_date'] = ['required','date'];
            $validation['due_date'] = ['required','date'];
            $validation['order_fulfil'] = ['required'];
            $validation['product_category'] = ['required'];
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
                $data['oef_number'] = "OEF-".$this->year_combo_num_gen(DB::table('fgs_oef')->where('fgs_oef.oef_number', 'LIKE', 'OEF-'.$years_combo.'%')->count()); 
                $data['customer_id'] = $request->customer;
                $data['oef_date'] = date('Y-m-d', strtotime($request->oef_date));
                $data['order_number'] = $request->order_number;
                $data['order_date'] = date('Y-m-d', strtotime($request->order_date));
                $data['due_date'] = date('Y-m-d', strtotime($request->due_date));
                $data['order_fulfil'] = $request->order_fulfil;
                $data['transaction_type'] = $request->transaction_type;
                $data['product_category'] = $request->product_category;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $add = $this->fgs_oef->insert_data($data);
                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a OEF !");
                    return redirect('fgs/OEF/item-list/'.$add);
                }
                else
                {
                    $request->session()->flash('error', "OEF insertion is failed. Try again... !");
                    return redirect('fgs/OEF-add');
                }

            }
            else
            {
                return redirect('fgs/OEF-add')->withErrors($validator)->withInput();
            }
        }
        else
        {        
            $transaction_type = transaction_type::get();
            $order_fulfil = order_fulfil::get();
            $category = fgs_product_category::get();
            return view('pages/FGS/OEF/OEF-add', compact('transaction_type','order_fulfil','category'));
        }
       
    }
    public function OEFproductsearch(Request $request,$oef_id)
    {
        $condition=[];
        if(!$request->q){
            return response()->json(['message'=>'Product is not valid'], 500); 
        }
        $oef = fgs_oef::find($oef_id);
        $customer = customer_supplier::select('customer_supplier.firm_name','zone.zone_name','state.state_name')
                        ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                        ->leftJoin('state','state.state_id','=','customer_supplier.state')
                        ->where('customer_supplier.id','=',$oef['customer_id'])->first();
        $condition[] = ['product_product.product_category_id','=',$oef['product_category']];
        $data =  $this->product->get_product_info_for_oef(strtoupper($request->q),$condition);
        foreach($data as $dat)
        {
            if($dat['gst']!='')
            {
                if($customer['state_name']=='Maharashtra')
                {
                    $gst = $dat['gst']/2;
                    $gst_data = inventory_gst::select('inventory_gst.*')->where('inventory_gst.sgst','=',$gst)->first();
                }
                else
                {
                    $gst = $dat['gst'];
                    $gst_data = inventory_gst::select('inventory_gst.*')->where('inventory_gst.igst','=',$gst)->first();
                   
                }
                $prdct[] = array(
                    'id'=>$dat['id'],
                    'text'=>$dat['text'],
                    'discription'=>$dat['discription'],
                    'group_name'=>$dat['group_name'],
                    'hsn_code'=>$dat['hsn_code'],
                    'sales'=>$dat['sales'],
                    'gst_id'=> $gst_data['id'],
                    'igst'=> $gst_data['igst'],
                    'sgst'=> $gst_data['sgst'],
                    'cgst'=> $gst_data['cgst'],
                );
                // $dat['gst_id'] = $gst_data['id'];
                // $dat['igst'] = $gst_data['igst'];
                // $dat['sgst'] = $gst_data['sgst'];
                // $dat['cgst'] = $gst_data['cgst'];
            }
        }
       // print_r( $data);exit;
        if(!empty( $data)){
            return response()->json( $prdct, 200); 
        }else{
            return response()->json(['message'=>'Product is not exist'], 500); 
        }
    }

    public function OEFitemlist(Request $request, $oef_id)
    {
        $condition = ['fgs_oef_item_rel.master' =>$request->oef_id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        $items = $this->fgs_oef_item->getItems($condition);
        return view('pages/FGS/OEF/OEF-item-list', compact('oef_id','items'));
    }

    public function OEFitemAdd(Request $request, $oef_id)
    {
        if($request->isMethod('post'))
        {
            $validation['moreItems.*.product'] = ['required'];
            $validation['moreItems.*.quantity'] = ['required'];
            $validation['moreItems.*.discount'] = ['required'];
            $validation['moreItems.*.rate'] = ['required'];
            //$validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $mrn_info = fgs_oef::find($request->oef_id);
                //print_r($request->moreItems);exit;
                foreach ($request->moreItems as $key => $value) 
                {
                    $data = [
                        "product_id" => $value['product'],
                        "quantity" => $value['quantity'],
                        "quantity_to_allocate"=>$value['quantity'],
                        "remaining_qty_after_cancel"=>$value['quantity'],
                        "rate"=>$value['rate'],
                        "gst" => $value['gst'],
                        "discount"=>$value['discount'],
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                    $oef_data =[
                        'remarks' => $request->remarks
                    ];
                    
                    $this->fgs_oef_item->insert_data($data,$request->oef_id);
                    $this->fgs_oef->update_data(['id'=>$request->oef_id],$oef_data);
                }
                $request->session()->flash('success',"You have successfully added a OEF item !");
                return redirect('fgs/OEF/item-list/'.$request->oef_id);
            } 
            else
            {
                return redirect('fgs/OEF/add-item/'.$request->oef_id)->withErrors($validator)->withInput();
            }
        }
        else{
            $data['gst'] = $this->inventory_gst->get_gst();
            $oef_id = $request->oef_id;
            return view('pages/FGS/OEF/OEF-item-add',compact('data','oef_id'));
        }
    }

    public function OEFpdf($oef_id)
    {
        $data['oef'] = $this->fgs_oef->get_single_oef(['fgs_oef.id' => $oef_id]);
        $data['items'] = $this->fgs_oef_item->getAllItems(['fgs_oef_item_rel.master' => $oef_id]);
        $pdf = PDF::loadView('pages.FGS.OEF.pdf-view', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "OEF" . $data['oef']['firm_name'] . "_" . $data['oef']['oef_date'];
        return $pdf->stream($file_name . '.pdf');
    }

    public function pendingOEF(Request $request)
    {
        $condition =[];
        if($request->oef_number)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_number . '%'];
        }
        if($request->order_number)
        {
            $condition[] = ['fgs_oef.order_number','like', '%' . $request->order_number . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_oef.oef_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_oef.oef_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $oef = fgs_oef::select('fgs_oef.*','order_fulfil.order_fulfil_type','transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.contact_person','customer_supplier.contact_number')
                        ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                        ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                        ->whereNotIn('fgs_oef.id',function($query) {

                            $query->select('fgs_grs.oef_id')->from('fgs_grs')->where('fgs_grs.status','=',1);
                        
                        })->where('fgs_oef.status','=',1)
                        ->where($condition)
                        ->distinct('fgs_oef.id')
                        ->paginate(15);
        return view('pages/FGS/OEF/pending-oef',compact('oef'));
    }
    public function pendingOEFExport(Request $request)
    {
        if($request)
        {
            return Excel::download(new PendingOEFExport($request), 'OEFBackOrderReport' . date('d-m-Y') . '.xlsx');
        }
        else
        {
            $request =null;
            return Excel::download(new PendingOEFExport($request), 'OEFBackOrderReport' . date('d-m-Y') . '.xlsx');
        }
    }
     public function OEFackpdf($oef_id)
    {
        $data['oef'] = $this->fgs_oef->get_single_oef(['fgs_oef.id' => $oef_id]);
        $data['items'] = $this->fgs_oef_item->getAllItems(['fgs_oef_item_rel.master' => $oef_id]);
        $pdf = PDF::loadView('pages.FGS.OEF.ack-pdf-view', $data);
       // $pdf->set_paper('A4', 'landscape');
        $file_name = "ORDER ACKNOWLEDGMENT" . $data['oef']['firm_name'] . "_" . $data['oef']['oef_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    
}
