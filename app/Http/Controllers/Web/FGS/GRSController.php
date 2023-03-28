<?php

namespace App\Http\Controllers\Web\fgs;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_grs;
class GRSController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->product = new product;
        $this->fgs_oef = new fgs_oef;
        $this->fgs_oef_item = new fgs_oef_item;
        $this->fgs_grs = new fgs_grs;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
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
                $data['grs_number'] = "GRS-".$this->po_num_gen(DB::table('fgs_grs')->where('fgs_grs.grs_number', 'LIKE', 'GRS%')->count(),1); 
                $data['grs_date'] = date('Y-m-d', strtotime($request->grs_date));
                $data['oef_id']=$request->oef_number;
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
        $oef_items = $this->fgs_oef_item->getItems($condition);
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
        // $condition = ['fgs_grs_item_rel.master' =>$request->grs_id];
        // if($request->product)
        // {
        //     $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        // }
        // $items = $this->fgs_grs_item->getItems($condition);
        return view('pages/FGS/GRS/GRS-item-list', compact('grs_id'));
    }
    public function GRSitemAdd(Request $request, $grs_id)
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

            }
            else
            {
                return redirect('fgs/GRS/add-item/'.$request->grs_id)->withErrors($validator)->withInput();
            }
        }
        else
        {
            return view('pages/FGS/GRS/GRS-item-add', compact('grs_id'));
        }
    }

}
