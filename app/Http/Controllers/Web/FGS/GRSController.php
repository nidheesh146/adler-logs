<?php

namespace App\Http\Controllers\Web\fgs;
use Validator;
use DB;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_grs_item_rel;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_pi_item;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendingGRSExport;
use App\Exports\FGSgrstransactionExport;

class GRSController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->product = new product;
        $this->fgs_oef = new fgs_oef;
        $this->fgs_oef_item = new fgs_oef_item;
        $this->fgs_mrn_item = new fgs_mrn_item;
        $this->fgs_grs = new fgs_grs;
        $this->fgs_grs_item = new fgs_grs_item;
        $this->fgs_grs_item_rel = new fgs_grs_item_rel;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_maa_stock_management = new fgs_maa_stock_management;
        $this->production_stock_management = new production_stock_management;
    }

    public function GRSList(Request $request)
    {
        $condition = [];
        if($request->grs_no)
        {
            $condition[] = ['fgs_grs.grs_number','like', '%' . $request->grs_no . '%'];
        }
        if($request->oef_no)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_grs.grs_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $grs = $this->fgs_grs->get_all_grs($condition);
        return view('pages/FGS/GRS/GRS-list',compact('grs'));
    }
    public function GRSAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['oef_number'] = ['required'];
            $validation['customer'] = ['required'];
            $validation['grs_date'] = ['required','date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location1'] = ['required'];
            $validation['stock_location2'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $exist = $this->product_exist_current_location($request->oef_number, $request->stock_location1);
                if($exist==0)
                {
                    $request->session()->flash('error', "OEF items not exist on current location. Try again with another location... !");
                    return redirect('fgs/GRS-add');
                }
                if(date('m')==01 || date('m')==02 || date('m')==03)
                {
                    $years_combo = date('y', strtotime('-1 year')).date('y');
                }
                else
                {
                    $years_combo = date('y').date('y', strtotime('+1 year'));
                }
                $data['grs_number'] = "GRS-".$this->year_combo_num_gen(DB::table('fgs_grs')->where('fgs_grs.grs_number', 'LIKE', 'GRS-'. $years_combo.'%')->count()+772); 
                $data['grs_date'] = date('Y-m-d', strtotime($request->grs_date));
                $data['oef_id']=$request->oef_number;
                $data['customer_id'] = $request->customer;
                $data['product_category'] = $request->product_category;
                $data['stock_location1'] = $request->stock_location1;
                $data['stock_location2'] = $request->stock_location2;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $add = $this->fgs_grs->insert_data($data);
                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a GRS !");
                    return redirect('fgs/GRS/item-list/'.$add);
                }
                else
                {
                    $request->session()->flash('error', "GRS insertion is failed. Try again... !");
                    return redirect('fgs/GRS-add');
                }
            }
            if($validator->errors()->all())
            {
                return redirect('fgs/GRS-add')->withErrors($validator)->withInput();
            }
        }
        else
        {
            $data['locations'] = product_stock_location::get();
            $data['category']= fgs_product_category::get();
            return view('pages/FGS/GRS/GRS-add', compact('data'));
        }
    }
    function product_exist_current_location($oef_id, $stock_location)
    {
        $condition[] = ['fgs_oef_item_rel.master','=', $oef_id];
        $condition[] = ['fgs_oef_item.quantity_to_allocate','!=',0];
        $condition[] = ['fgs_oef_item.coef_status','=',0]; 
        $oef_items = $this->fgs_oef_item->getItems($condition);
        $i=0;
        foreach($oef_items as $item)
        {
            $is_exist= fgs_product_stock_management::where('product_id','=',$item['product_id'])
                                ->where('stock_location_id','=',$stock_location)
                                ->where('quantity','!=',0)
                                ->exists();
            if($is_exist)
            $i++;
        }
        if($i==0)
        return 0;
        else 
        return 1;

    }
    public function findOEFforGRS(Request $request)
    {
        // if ($request->q) {
        //     $condition[] = ['fgs_oef.oef_number', 'like', '%' . strtoupper($request->q) . '%'];
           
        //     $data = $this->fgs_oef->find_oef_num_for_grs($condition);
        //     if (!empty($data[0])) {
        //         return response()->json($data, 200);
        //     } else {
        //         return response()->json(['message' => 'item code is not valid'], 500);
        //     }
        // } else {
        //     echo $this->OEF_details($request->id);
        //     exit;
        // }
        if($request->customer_id)
        {
            $condition[] = ['fgs_oef.customer_id','=', $request->customer_id];
            $data = $this->fgs_oef->find_oef_num_for_grs($condition);
            if($data)
            return $data;
            else
            return 0;
        }
    }

    public function findOEFInfo(Request $request)
    {
        if ($request->q) {
            $condition[] = ['fgs_oef.oef_number', 'like', '%' . strtoupper($request->q) . '%'];    
            $data = $this->fgs_oef->find_oef_num_for_grs($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->OEF_details($request->id);
            exit;
        }
    }
    public function OEF_details($id)
    {
        $oef = $this->fgs_oef->get_single_oef(['fgs_oef.id'=>$id]);
        $condition[] = ['fgs_oef_item_rel.master','=', $id];
        $condition[] = ['fgs_oef_item.quantity_to_allocate','!=',0];  
        $condition[] = ['fgs_oef_item.coef_status','=',0];          
        $oef_items = $this->fgs_oef_item->getAllItems($condition);
        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               Order Execution Form(OEF) (' . $oef->oef_number . ')
                   </label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
            
                </thead>
                <tbody>
                    <tr>
                        <th>OEF Date</th>
                        <td>' . date('d-m-Y', strtotime($oef->oef_date)) . '</td>
                        <th>Due Date</th>
                        <td>' . date('d-m-Y', strtotime($oef->due_date)) . '</td>
                    </tr>
                    <tr>
                        <th>Customer </th>
                        <td>'.$oef->firm_name.'</td>
                        <th>Contact Person & Designation</th>
                        <td>'.$oef->contact_person.'<br/>
                        '.$oef->designation.'</td>
                    </tr>
                    <tr>
                        <th>Contact Number </th>
                        <td>'.$oef->contact_number.'</td>
                        <th>Email ID</th>
                        <td>'.$oef->email.'</td>
                    </tr>
                    <tr>
                        <th>Shipping Address</th>
                        <td>'.$oef->shipping_address.'</td>
                        <th>Billing Address</th>
                        <td>'.$oef->billing_address.'</td>
                    </tr>
                    <tr>
                        <th>Order Number</th>
                        <td>'.$oef->order_number.'</td>
                        <th>Order Date</th>
                        <td>'.date('d-m-Y', strtotime($oef->order_date)).'</td>
                    </tr>
                    <tr>
                    <tr>
                        <th>Sales Type</th>
                        <td>'.$oef->sales_type.'</td>
                        <th>Transaction Type</th>
                        <td>'.$oef->transaction_name.'</td>
                    </tr>
                    <tr>
                        <th>Order FulFil</th>
                        <td>'.$oef->order_fulfil_type.'</td>
                        <th>Remarks</th>
                        <td>'.$oef->remarks.'</td>
                    </tr>
                    </tr>
                </tbody>
           </table>
           <br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
            $data .= 'OEF Items ';
            $data .= '</label>
               <div class="form-devider"></div>
                </div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1">';

        
            $data .= '<thead>
                   <tr>
                   <th>Product</th>
                   <th>Descriptio</th>
                   <th>Quantity</th>
                   <th>Rate</th>
                   <th>Discount</th>
                   <th>GST </th>           
                   </tr>
               </thead>
               <tbody >';
            foreach ($oef_items as $item) {
                $data .= '<tr>
                        <td>'.$item->sku_code.'</td>
                       <td>'.$item->discription.'</td>
                       <td>'.$item->quantity_to_allocate.'Nos</td>
                       <td>'.$item->rate.'  '.$oef->currency_code. '</td>
                       <td>'.$item->discount.'%</td>
                       <td>IGST:'.$item->igst.'% ,
                            SGST:'.$item->sgst.'%,
                            CGST:'.$item->cgst.'%<br/>
                       </td>
                       
                   </tr>';
            }
            $data .= '</tbody>';
        

        $data .= '</table>
       </div>';
        return $data;
    }

    public function GRSitemlist(Request $request, $grs_id)
    {
        $grs_master = fgs_grs::find($grs_id);
        $condition[] = ['fgs_oef_item_rel.master','=', $grs_master->oef_id];
        $condition[] = ['fgs_oef_item.quantity_to_allocate','!=',0];
        $condition[] = ['fgs_oef_item.coef_status','=',0];             
        $oef_items = $this->fgs_oef_item->getItems($condition);
        foreach($oef_items as $item)
        {
            $product_batchcards = fgs_product_stock_management::select('fgs_product_stock_management.batchcard_id','batchcard_batchcard.batch_no','fgs_product_stock_management.quantity as batchcard_available_qty')
                                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=', 'fgs_product_stock_management.batchcard_id')
                                    ->where('fgs_product_stock_management.stock_location_id','=',$grs_master->stock_location1)
                                    ->where('fgs_product_stock_management.product_id','=',$item['product_id'])
                                    ->where('fgs_product_stock_management.quantity','!=',0)
                                    ->get();
            if(count($product_batchcards)>0)
            {
                $item['batchcards'] = $product_batchcards;
            }
           
        }
        $condition1[] = ['fgs_grs_item_rel.master','=', $grs_id];
        $grs_items = $this->fgs_grs_item->getItems($condition1);
        return view('pages/FGS/GRS/GRS-item-list', compact('grs_id','oef_items','grs_items'));
    }
    public function GRSitemAdd(Request $request, $grs_id, $oef_item_id)
    {
        if($request->isMethod('post'))
        {
            $validation['grs_id'] = ['required'];
            $validation['batchcard'] = ['required'];
            $validation['oef_item_id'] = ['required'];
            $validation['batch_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $oef_item = fgs_oef_item::find($request->oef_item_id);
                $data['product_id'] = $oef_item['product_id'];
                $data['oef_item_id']=$request->oef_item_id;
                $data['mrn_item_id']=$request->mrn_item_id;
                $data['batchcard_id'] = $request->batchcard;
                $data['batch_quantity'] = $request->batch_qty;
                $data['remaining_qty_after_cancel'] = $request->batch_qty;
                $data['qty_to_invoice'] = $request->batch_qty;
                $data['created_at'] =date('Y-m-d H:i:s');
                $add = $this->fgs_grs_item->insert_data($data,$request->grs_id);
                $grs_master = fgs_grs::find($request->grs_id);
                $mrn_item = fgs_mrn_item::find($request->mrn_item_id);
                $fgs_stock = fgs_product_stock_management::select('id as fgs_stock_id','quantity')
                                                        ->where('product_id','=',$oef_item['product_id'])
                                                        ->where('stock_location_id','=',$grs_master['stock_location1'])
                                                        ->where('batchcard_id','=',$request->batchcard)
                                                        ->first();
                $oef_qty_updation = $oef_item['quantity_to_allocate']- $request->batch_qty;
                $oef_item['quantity_to_allocate'] = $oef_qty_updation;
                $oef_item['remaining_qty_after_cancel'] = $oef_qty_updation;
                $oef_item->save();
            
                $stock_updation = $fgs_stock['quantity']-$request->batch_qty;
                $stock_mngment= fgs_product_stock_management::find($fgs_stock['fgs_stock_id']);
                $stock_mngment->quantity = $stock_updation;
                $stock_mngment->save();
                $maa_stock = fgs_maa_stock_management::select('id as maa_stock_id','quantity')
                                        ->where('product_id','=',$oef_item['product_id'])
                                        ->where('batchcard_id','=',$request->batchcard)
                                        ->first();
                if($maa_stock)
                {
                    $maa_stock_updation = $maa_stock['quantity']+$request->batch_qty;
                    $update = $this->fgs_maa_stock_management->update_data(['id'=>$maa_stock['id']],['quantity'=>$maa_stock_updation]);
                }
                else
                {
                    $stock['product_id']= $oef_item['product_id'];
                    $stock['batchcard_id']= $request->batchcard;
                    $stock['quantity']= $request->batch_qty;
                    $data['created_at'] =date('Y-m-d H:i:s');
                    $stock_add = $this->fgs_maa_stock_management->insert_data($stock);
                }

                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a GRS Item!");
                    return redirect('fgs/GRS/item-list/'.$request->grs_id);
                }
                else
                {
                    $request->session()->flash('error', "GRS Item insertion is failed. Try again... !");
                    return redirect('fgs/GRS/'.$request->grs_id.'/add-item/'.$request->oef_item_id);
                }
            }
            else
            {
                return redirect('fgs/GRS/'.$request->grs_id.'/add-item/'.$request->oef_item_id)->withErrors($validator)->withInput();
            }
        }
        else
        {
            $grs_master = fgs_grs::find($grs_id);
            $oef_item = $this->fgs_oef_item->getSingleItem(['fgs_oef_item.id'=>$oef_item_id]);
            if($oef_item)
            {
                $product_batchcards = fgs_product_stock_management::select('fgs_product_stock_management.batchcard_id','batchcard_batchcard.batch_no','fgs_mrn_item.id as mrn_item_id',
                                            'fgs_product_stock_management.quantity as batchcard_available_qty','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=', 'fgs_product_stock_management.batchcard_id')
                                            ->leftJoin('fgs_mrn_item','fgs_mrn_item.batchcard_id','=','batchcard_batchcard.id')
                                            ->leftJoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                                            ->leftJoin('fgs_mrn','fgs_mrn.id','=','fgs_mrn_item_rel.master')
                                            ->where('fgs_product_stock_management.stock_location_id','=',$grs_master['stock_location1'])
                                            ->where('fgs_product_stock_management.product_id','=',$oef_item['product_id'])
                                            ->where('fgs_mrn_item.product_id','=',$oef_item['product_id'])
                                            ->where('fgs_mrn.stock_location','=', $grs_master['stock_location1'])
                                            ->where('fgs_mrn.product_category','=',$grs_master['product_category'])
                                            ->where('fgs_product_stock_management.quantity','>',0)
                                            ->orderBy('batchcard_batchcard.id','ASC')
                                            ->groupBy('fgs_product_stock_management.id')
                                            ->get();
                if(count($product_batchcards)>0)
                {
                    $oef_item['batchcards'] = $product_batchcards;
                }
               // print_r(json_encode($product_batchcards));exit;
                
            }
            return view('pages/FGS/GRS/GRS-item-add', compact('grs_id','oef_item'));
        }
        
        // if($request->isMethod('post'))
        // {
        //     $validation['grs_id'] = ['required'];
        //     $validation['oef_item_id'] = ['required'];
        //     $validator = Validator::make($request->all(), $validation);
        //     if(!$validator->errors()->all())
        //     {
        //         foreach($request->oef_item_id as $oef_item_id)
        //         {

        //         }
        //     }
        //     else
        //     {
        //         return redirect('fgs/GRS/add-item/'.$request->grs_id)->withErrors($validator)->withInput();
        //     }
        // }
        // else
        // {
        //     $grs_master = fgs_grs::find($grs_id);
        //     $condition[] = ['fgs_oef_item_rel.master','=', $grs_master['oef_id']];
        //     $condition[] = ['fgs_oef_item.quantity_to_allocate','!=',0];            
        //     $oef_items = $this->fgs_oef_item->getItems($condition);
        //     foreach($oef_items as $item)
        //     {
        //         $product_batchcards = fgs_product_stock_management::select('fgs_product_stock_management.batchcard_id','batchcard_batchcard.batch_no','fgs_product_stock_management.quantity as batchcard_available_qty')
        //                             ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=', 'fgs_product_stock_management.batchcard_id')
        //                             ->where('fgs_product_stock_management.stock_location_id','=',$grs_master['stock_location1'])
        //                             ->where('fgs_product_stock_management.product_id','=',$item['product_id'])
        //                             ->where('fgs_product_stock_management.quantity','!=',0)
        //                             ->get();
        //         if(count($product_batchcards)>0)
        //         {
        //             $item['batchcards'] = $product_batchcards;
        //         }
        //     }
        //     //print_r(json_encode($oef_items));exit;
        //     return view('pages/FGS/GRS/GRS-item-add', compact('grs_id','oef_items'));
        // }
    }

    public function GRSpdf($grs_id)
    {
        $data['grs'] = $this->fgs_grs->get_single_grs(['fgs_grs.id' => $grs_id]);
        $data['items'] = $this->fgs_grs_item->getAllItems(['fgs_grs_item_rel.master' => $grs_id]);
        $pdf = PDF::loadView('pages.FGS.GRS.pdf-view', $data);
        //$pdf->set_paper('A4', 'landscape');
        $file_name = "GRS" . $data['grs']['firm_name'] . "_" . $data['grs']['grs_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    public function pendingGRS(Request $request)
    {
        $condition = [];
        if($request->grs_no)
        {
            $condition[] = ['fgs_grs.grs_number','like', '%' . $request->grs_no . '%'];
        }
        if($request->oef_no)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_grs.grs_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        
        $grs = fgs_grs::select('fgs_grs.*','fgs_product_category.category_name','product_stock_location.location_name as location_name1',
                        'stock_location.location_name as location_name2','fgs_oef.oef_number','customer_supplier.firm_name', 'fgs_oef.order_number','fgs_oef.order_date')
                            ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
                            ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
                            ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
                            ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                            ->whereNotIn('fgs_grs.id',function($query) {

                                $query->select('fgs_pi_item.grs_id')->from('fgs_pi_item');
                            
                            })->where('fgs_grs.status','=',1)
                            ->where($condition)
                            ->orderBy('fgs_grs.id','DESC')
                            ->distinct('fgs_grs.id')
                            ->paginate(15);
        return view('pages/FGS/GRS/pending-grs',compact('grs'));
    }
    public function pendingGRSExport(Request $request)
    {
        $condition = [];
        if($request->grs_no)
        {
            $condition[] = ['fgs_grs.grs_number','like', '%' . $request->grs_no . '%'];
        }
        if($request->oef_no)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_grs.grs_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $grs_data =fgs_grs_item::select('fgs_grs_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','fgs_grs.grs_number','fgs_grs.grs_date',
        'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_product_category.category_name','product_stock_location.location_name as location_name1',
        'stock_location.location_name as location_name2','fgs_oef.oef_number','fgs_oef.oef_date','customer_supplier.firm_name', 'fgs_oef.order_number','fgs_oef.order_date','fgs_grs.created_at as grs_created_at',
        'fgs_oef_item.rate','fgs_oef_item.discount','inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst')
                    ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                    ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                    ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
                    ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                    ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
                    ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
                    ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
                    ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                    ->leftjoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                    ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                    ->whereNotIn('fgs_grs.id',function($query) {

                        $query->select('fgs_pi_item.grs_id')->from('fgs_pi_item');
                    
                    })
                    ->where($condition)
                    ->where('fgs_grs_item.cgrs_status','=',0)
                    ->where('fgs_grs.status','=',1)
                    ->where('fgs_grs_item.status','=',1)
                    ->orderBy('fgs_grs_item.id','DESC')
                    ->distinct('fgs_grs_item.id')
                    ->get();

        return Excel::download(new PendingGRSExport($grs_data), 'GRSBackOrderReport' . date('d-m-Y') . '.xlsx');
    
    }
    public function grs_transaction(Request $request)
    {
        $condition = [];
        if ($request->mrn_no) {
            $condition[] = ['fgs_grs.grs_number', 'like', '%' . $request->grs_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_grs_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }
        $items = fgs_grs_item::select(
            'fgs_grs.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_grs.grs_number',
            'fgs_grs.grs_date',
            'fgs_grs.created_at as min_wef',
            'fgs_grs_item.id as grs_item_id'
        )
            ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_grs_item.product_id')
            // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            //->where('fgs_min_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_grs_item.status',1)
            //->distinct('fgs_min_item.id')
            ->orderBy('fgs_grs_item.id', 'desc')
            ->paginate(15);

        return view('pages/FGS/GRS/GRS-transaction-list', compact('items'));
    }

    public function grs_transaction_export(Request $request)
    {
        $condition = [];
        if ($request->mrn_no) {
            $condition[] = ['fgs_grs.grs_number', 'like', '%' . $request->grs_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_grs_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }
        $items = fgs_grs_item::select(
            'fgs_grs.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_grs.grs_number',
            'fgs_grs.grs_date',
            'fgs_grs.created_at as min_wef',
            'fgs_grs_item.id as grs_item_id'
        )
            ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_grs_item.product_id')
            // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
            //->where('fgs_min_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_grs_item.status',1)
            //->distinct('fgs_min_item.id')
            ->orderBy('fgs_grs_item.id', 'desc')
            ->get();

        return Excel::download(new FGSgrstransactionExport($items), 'FGS-GRS-transaction' . date('d-m-Y') . '.xlsx');
    }

    public function grsItemExistInPI($grs_item_id)
    {
        $pi_item = fgs_pi_item::where('grs_item_id','=',$grs_item_id)->where('status','=',1)->get();
        if(count($pi_item)>0)
        return 1;
        else
        return 0;

    }
    public function grsExistInPI($grs_id)
    {
        $pi_item = fgs_pi_item::where('grs_id','=',$grs_id)->where('status','=',1)->get();
        if(count($pi_item)>0)
        return 1;
        else
        return 0;
    }
    public function GRSItemDelete($grs_item_id,Request $request)
    {
        $grs_item = fgs_grs_item::where('id','=',$grs_item_id)->first();
        $grs_id = fgs_grs_item_rel::where('item','=',$grs_item_id)->first();
        $grs_data = fgs_grs::find($grs_id->master);
        $mma_stock_update = fgs_maa_stock_management::where('product_id','=',$grs_item->product_id)
                                            ->where('batchcard_id','=',$grs_item->batchcard_id)
                                            ->decrement('quantity',$grs_item->qty_to_invoice);
        $prduct_stock_update = fgs_product_stock_management::where('product_id','=',$grs_item->product_id)
                                                    ->where('batchcard_id','=',$grs_item->batchcard_id)
                                                    //->where('stock_location_id','=',$grs_data->stock_location1)
                                                    ->increment('quantity',$grs_item->qty_to_invoice);
        $oef_item = fgs_oef_item::where('id','=',$grs_item->oef_item_id)->first();
        $new_qty_to_allocate = $oef_item->quantity_to_allocate+$grs_item->qty_to_invoice;
        $oef_item_update = fgs_oef_item::where('id','=',$grs_item->oef_item_id)->update(['quantity_to_allocate'=>$new_qty_to_allocate,'remaining_qty_after_cancel'=>$new_qty_to_allocate]);
        $grs_item_update = fgs_grs_item::where('id','=',$grs_item_id)->update(['status'=>0]);

        if($mma_stock_update &&  $prduct_stock_update && $grs_item_update)
        {
            $request->session()->flash('success', "You have successfully deleted a GRS Item !");
            return redirect('fgs/GRS/item-list/'.$grs_id->master);
        }


    }

    public function GRSDelete($grs_id,Request $request)
    {
       // echo $grs_id;exit;
        $grs = fgs_grs::where('id','=',$grs_id)->first();
        $grs_items = fgs_grs_item_rel::leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_grs_item_rel.item')
                        ->where('fgs_grs_item.status','=',1)
                        ->where('fgs_grs_item_rel.master','=',$grs_id)->get();
        if(count($grs_items)>0)
        {
            $request->session()->flash('error', "You can't deleted this GRS(".$grs->grs_number.").It have items !");
        }
        else
        {
            $update = $this->fgs_grs->update_data(['id'=>$grs_id],['status'=>0]);
            $request->session()->flash('success', "You have successfully deleted a GRS(".$grs->grs_number.") !");
        }
        return redirect('fgs/GRS-list');

    }

    public function GRSEdit($grs_id,Request $request,)
    {
        if($request->isMethod('post'))
        {
            $validation['grs_id'] = ['required'];
            $validation['grs_number'] = ['required'];
            $validation['grs_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $data['grs_number']=$request->grs_number;
                $data['grs_date']=date('Y-m-d', strtotime($request->grs_date));
                $update = $this->fgs_grs->update_data(['id'=>$grs_id],$data);
                if($update)
                $request->session()->flash('success', "You have successfully update a GRS.");
                else
                $request->session()->flash('error', "You have failed to update a GRS.");
                return redirect('fgs/GRS-list');
            }
            if($validator->errors()->all())
            {
                return redirect('fgs/GRS-list')->withErrors($validator)->withInput();
            }
        }
        else
        {
            $grs= $this->fgs_grs->get_single_grs(['fgs_grs.id'=>$grs_id]);
            //print_r($grs);exit;
            return view('pages/FGS/GRS/GRS-add', compact('grs'));
        }
    }

    public function GRSItemEdit($grs_item_id,Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['grs_id'] = ['required'];
            $validation['batchcard'] = ['required'];
            $validation['grs_item_id'] = ['required'];
            $validation['batch_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                
                $grs_master = fgs_grs::find($request->grs_id);
                $grs_item = fgs_grs_item::find($request->grs_item_id);
                $fgs_stock_old_batch = fgs_product_stock_management::select('id as fgs_stock_id','quantity')
                                                        ->where('product_id','=',$grs_item['product_id'])
                                                        ->where('stock_location_id','=',$grs_master['stock_location1'])
                                                        ->where('batchcard_id','=',$grs_item['batchcard_id'])
                                                        ->first();
                /*$oef_qty_updation = $oef_item['quantity_to_allocate']- $request->batch_qty;
                $oef_item['quantity_to_allocate'] = $oef_qty_updation;
                $oef_item['remaining_qty_after_cancel'] = $oef_qty_updation;
                $oef_item->save();*/
            
                $old_batch_stock_updation = $fgs_stock_old_batch['quantity']+$grs_item->qty_to_invoice;
                $old_stock_mngment= fgs_product_stock_management::find($fgs_stock_old_batch['fgs_stock_id']);
                $old_stock_mngment->quantity = $old_batch_stock_updation;
                $old_stock_mngment->save();

                $maa_stock_old_batch = fgs_maa_stock_management::select('id as maa_stock_id','quantity')
                                        ->where('product_id','=',$grs_item['product_id'])
                                        ->where('batchcard_id','=',$grs_item['batchcard_id'])
                                        ->first();
                $old_batch_maa_stock_updation = $maa_stock_old_batch['quantity']-$grs_item->qty_to_invoice;
                $old_batch_update = $this->fgs_maa_stock_management->update_data(['id'=>$maa_stock_old_batch['id']],['quantity'=>$old_batch_maa_stock_updation]);

                $fgs_stock_new_batch = fgs_product_stock_management::select('id as fgs_stock_id','quantity')
                                    ->where('product_id','=',$grs_item['product_id'])
                                    ->where('stock_location_id','=',$grs_master['stock_location1'])
                                    ->where('batchcard_id','=',$request->batchcard)
                                    ->first();

                $new_batch_stock_updation = $fgs_stock_new_batch['quantity']-$request->batch_qty;
                $new_stock_mngment= fgs_product_stock_management::find($fgs_stock_new_batch['fgs_stock_id']);
                $new_stock_mngment->quantity = $new_batch_stock_updation;
                $new_stock_mngment->save();

                $maa_stock_new_batch = fgs_maa_stock_management::select('id as maa_stock_id','quantity')
                                        ->where('product_id','=',$grs_item['product_id'])
                                        ->where('batchcard_id','=',$request->batchcard)
                                        ->first();

                if($maa_stock_new_batch)
                {
                    $maa_new_stock_updation = $maa_stock_new_batch['quantity']+$request->batch_qty;
                    $update = $this->fgs_maa_stock_management->update_data(['id'=>$maa_stock_new_batch['id']],['quantity'=>$maa_new_stock_updation]);
                }
                else
                {
                    $stock['product_id']= $grs_item['product_id'];
                    $stock['batchcard_id']= $request->batchcard;
                    $stock['quantity']= $request->batch_qty;
                    $data['created_at'] =date('Y-m-d H:i:s');
                    $stock_add = $this->fgs_maa_stock_management->insert_data($stock);
                }

                $data['batchcard_id'] = $request->batchcard;
                $data['batch_quantity'] = $request->batch_qty;
                $data['remaining_qty_after_cancel'] = $request->batch_qty;
                $data['qty_to_invoice'] = $request->batch_qty;
                //$data['created_at'] =date('Y-m-d H:i:s');
                $update = $this->fgs_grs_item->update_data(['fgs_grs_item.id'=>$request->grs_item_id],$data);

                if($update)
                {
                    $request->session()->flash('success', "You have successfully updated a GRS Item!");
                    return redirect('fgs/GRS/item-list/'.$request->grs_id);
                }
                else
                {
                    $request->session()->flash('error', "GRS Item updation is failed. Try again... !");
                    return redirect('fgs/GRS/item-list/'.$request->grs_id);
                }
            }
            if($validator->errors()->all())
            {
                return redirect('fgs/GRS/item-list/'.$request->grs_id)->withErrors($validator)->withInput();
            }
        }
        else
        {
            $grs_item = $this->fgs_grs_item->getSingleItem(['fgs_grs_item.id'=>$grs_item_id]);
            $grs_id = fgs_grs_item_rel::where('item','=',$grs_item_id)->first();
            $grs_master = fgs_grs::find($grs_id->master);
            $grs_id = $grs_id->master;

            //$oef_item = $this->fgs_oef_item->getSingleItem(['fgs_oef_item.id'=>$oef_item_id]);
            if($grs_item)
            {
                $product_batchcards = fgs_product_stock_management::select('fgs_product_stock_management.batchcard_id','batchcard_batchcard.batch_no','fgs_mrn_item.id as mrn_item_id',
                                                'fgs_product_stock_management.quantity as batchcard_available_qty','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                                                ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=', 'fgs_product_stock_management.batchcard_id')
                                                ->leftJoin('fgs_mrn_item','fgs_mrn_item.batchcard_id','=','batchcard_batchcard.id')
                                                ->leftJoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                                                ->leftJoin('fgs_mrn','fgs_mrn.id','=','fgs_mrn_item_rel.master')
                                                ->where('fgs_product_stock_management.stock_location_id','=',$grs_master['stock_location1'])
                                                ->where('fgs_product_stock_management.product_id','=',$grs_item['product_id'])
                                                ->where('fgs_mrn_item.product_id','=',$grs_item['product_id'])
                                                ->where('fgs_mrn.stock_location','=', $grs_master['stock_location1'])
                                                ->where('fgs_mrn.product_category','=',$grs_master['product_category'])
                                                ->where('fgs_product_stock_management.quantity','>',0)
                                                ->orderBy('batchcard_batchcard.id','ASC')
                                                ->groupBy('fgs_product_stock_management.id')
                                                ->get();
                    if(count($product_batchcards)>0)
                    {
                        $grs_item['batchcards'] = $product_batchcards;
                    }
                    //print_r(json_encode($oef_item));exit;
                    
            }
            return view('pages/FGS/GRS/GRS-item-add', compact('grs_id','grs_item'));
        }
    }
}
