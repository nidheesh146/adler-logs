<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\User;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_oef_item_rel;
use App\Models\FGS\transaction_type;
use App\Models\FGS\order_fulfil;
use App\Models\inventory_gst;
use App\Models\product;
use App\Models\FGS\fgs_coef;
use App\Models\FGS\fgs_coef_item;
use App\Models\FGS\fgs_coef_item_rel;

class COEFController extends Controller
{
    public function __construct()
    { 
        $this->fgs_oef = new fgs_oef;
        $this->fgs_oef_item = new fgs_oef_item;
        $this->fgs_oef_item_rel = new fgs_oef_item_rel;
         $this->fgs_coef = new fgs_coef;
        $this->fgs_coef_item = new fgs_coef_item;
        $this->fgs_coef_item_rel = new fgs_coef_item_rel;
        $this->transaction_type = new transaction_type;
        $this->order_fulfil = new order_fulfil;
        $this->inventory_gst = new inventory_gst;
        $this->product = new product;
        $this->User = new User;
    }
   
 public function COEFList(Request $request)
    {
        $condition =[];
        if($request->coef_number)
        {
            $condition[] = ['fgs_coef.coef_number','like', '%' . $request->coef_number . '%'];
        }
        if($request->order_number)
        {
            $condition[] = ['fgs_coef.order_number','like', '%' . $request->order_number . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_coef.coef_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_coef.coef_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $coef = fgs_coef::select('fgs_coef.*','order_fulfil.*','transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.shipping_address','customer_supplier.contact_person','customer_supplier.contact_number','fgs_oef.*')
                 ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_coef.oef_id')
                        ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_coef.order_fulfil')
                        ->leftJoin('transaction_type','transaction_type.id','=','fgs_coef.transaction_type')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_coef.customer_id')
                        ->where($condition)
                        ->distinct('fgs_coef.id')
                        ->paginate(15);
        return view('pages/FGS/COEF/COEF-list', compact('coef'));
    }
    public function COEFAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['oef_item.*.order_number'] = ['required'];
            $validation['oef_item.*.order_date'] = ['required','date'];
            $validation['oef_item.*.order_fulfil'] = ['required'];
            $validation['oef_item.*.customer_id'] = ['required'];
            $validation['oef_item.*.transaction_type'] = ['required'];
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
                $data['coef_number'] = "COEF-".$this->year_combo_num_gen(DB::table('fgs_coef')->where('fgs_coef.coef_number', 'LIKE', 'COEF-'.$years_combo.'%')->count()); 
                $data['customer_id'] = $request->customer_id;
                $data['coef_date'] = date('Y-m-d', strtotime($request->coef_date));
                $data['oef_id'] = $request->oef_number;
                $data['order_number'] = $request->order_number;
                $data['order_date'] = date('Y-m-d', strtotime($request->order_date));
                $data['due_date'] = date('Y-m-d', strtotime($request->due_date));
                $data['order_fulfil'] = $request->order_fulfil;
                $data['transaction_type'] = $request->transaction_type;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $data['remarks'] = $request->remarks;
                        

                $coef_id = $this->fgs_coef->insert_data($data);

                foreach ($request->oef_item_id as $oef_item_id) {
                        $oef_item =fgs_oef_item::find($oef_item_id);
                        $datas = [
                            "coef_item_id" => $oef_item_id,
                            "product_id" => $oef_item['product_id'],
                            "quantity" => $oef_item['quantity'],
                            "quantity_to_allocate" => $oef_item['quantity_to_allocate'],
                            "rate" => $oef_item['rate'],
                            "discount" => $oef_item['discount'],
                            "gst" => $oef_item['gst'],
                            "created_at" => date('Y-m-d H:i:s')
                        ];

                         $this->fgs_coef_item->insert_data($datas,$coef_id);
                         $fgs_oef_item = fgs_oef_item::
                                        where('product_id','=',$oef_item['product_id'])
                                        ->update(['coef_status' => 1]);
                   
                if($coef_id)
                {
                    $request->session()->flash('success', "You have successfully added a COEF !");
                    return redirect('fgs/COEF/COEF-list');
                }
                else
                {
                    $request->session()->flash('error', "COEF insertion is failed. Try again... !");
                    return redirect('fgs/COEF-add');
                }

            }
        }
            if($validator->errors()->all())
                {
                    return redirect('FGS/COEF-add')->withErrors($validator)->withInput();
                }
            }
            $condition1[] = ['user.status', '=', 1];
            $data['users'] = $this->User->get_all_users($condition1);

            if($request->id){
                $edit['oef'] = $this->fgs_oef->find_oef_datas(['fgs_oef.id' => $request->id]);
                $edit['items'] = $this->fgs_oef_item->get_items(['fgs_oef_item_rel.master' =>$request->id]);
                $transaction_type = transaction_type::get();
            $order_fulfil = order_fulfil::get();
               return view('pages.FGS.COEF.COEF-add',compact('edit','data','transaction_type','order_fulfil'));
            }

            else
            {
                  $transaction_type = transaction_type::get();
            
            $order_fulfil = order_fulfil::get();
            return view('pages.FGS.COEF.COEF-add',compact('data','transaction_type','order_fulfil'));
       
    }
}
    public function findOefNumberForCOEF(Request $request){
        if ($request->q) {
            $condition[] = ['fgs_oef.oef_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_oef->find_oef_num_for_coef($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->oef_details($request->id, null);
            exit;
        }
       }
        public function oefInfo(Request $request)
       {
        if ($request->q) {
           $condition[] = ['fgs_oef.oef_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_oef->find_oef_num_for_coef($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->oef_details($request->id, null);
            exit;
        }
      }

      public function oef_details($id, $active = null)
      {
        $oef = $this->fgs_oef->get_master_data(['fgs_oef.id' => $id]);
       //return $invoice;
        $oef_item = $this->fgs_oef_item->get_oef_item(['fgs_oef_item_rel.master' => $id]);
        $data = '

          <div class="row" style=" width:100%; ">
         
            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               MIN number (' . $oef->oef_number . ')
                   </label>
              <div class="form-devider"></div>
            </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
                </thead>
                <tbody style=" width:100%;">
                    <tr style=" width:100%;">
                        <th>OEF Date</th>
                        <td>' . date('d-m-Y', strtotime($oef->oef_date)) . '</td>
                    </tr>
                    <tr style=" width:100%;">
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($oef->created_at)) . '</td>
                            
                    </tr>
                     <tr style=" width:100%;">
                            <th>Order Number</th>
                            <td>' .$oef->order_number. '</td>
                            
                    </tr>
                      <tr style=" width:100%;">
                            <th>Order Date</th>
                            <td>' . date('d-m-Y', strtotime($oef->order_date)) . '</td>
                            
                    </tr>
                    <tr style=" width:100%;">
                            <th>Order Fulfil</th>
                            <td>' .$oef->order_fulfil_type. '</td>
                            
                    </tr>
                  
             </tbody>
           </table>
           <br>
            <div class="row" style=" width:100%; ">
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
                <th ></th> 
               <th>PRODUCT</th>
                <th>HSN CODE</th>
                <th>DESCRIPTION</th>
                <th> QUANTITY</th>
                </tr>
               </thead>
               <tbody >';
            foreach ($oef_item as $item) {
                $data .= '<tr>
                    <td ><input type="checkbox" name="oef_item_id[]" id="oef_item_id" value="'.$item->id.'"></td>
                       <td>'.$item->sku_code.'</td>
                       <td>'.$item->hsn_code.'</td>
                        <td>'.$item->discription.'</td>
                       <td>'.$item->quantity.'</td>
                      
                      
                      </tr>';
            }
            $data .= '</tbody>';
        $data .= '</table>
       </div>
       <div class="row" style=" width:100%; ">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                   
                    <div class="form-devider"></div>
                    <div class="row" style=" width:100%; ">
                        <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1" style="margin-top: 6px;padding:0px; margin-left : 15px;">
                            <label>Remarks :</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <textarea type="text"  name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks" rows= "4">     </textarea>                       
                        </div>
                       
                        
                        <input type="hidden" name="created_at" value=" '. date('d-m-Y', strtotime($oef->created_at)). ' ">
                        <input type="hidden" name="order_number" value="' .$oef->order_number. '">
                        <input type="hidden" name="order_date" value="' .date('d-m-Y', strtotime($oef->order_date)). '">
                        <input type="hidden" name="due_date" value="' .date('d-m-Y', strtotime($oef->due_date)). '">
                        <input type="hidden" name="order_fulfil" value="' .$oef->order_fulfil. '">
                        <input type="hidden" name="customer_id" value="' .$oef->customer_id. '">
                        <input type="hidden" name="transaction_type" value="' .$oef->transaction_type. '">
                        </div>
                </div>
                <div class="form-devider"></div>
                <br>
                <div class="row" style=" width:60%; ">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded invoice-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                Save 
                            </button>
                        </div>
                    </div>
              </div>
             ';
        return $data;
    }
   
    public function COEFItemList(Request $request, $coef_id)
    {
        $condition = ['fgs_coef_item_rel.master' =>$request->coef_id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        if($request->batchnumber)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batchnumber . '%'];
        }
        if($request->manufaturing_from)
        {
            $condition[] = ['fgs_cmin_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->manufaturing_from))];
            $condition[] = ['fgs_cmin_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->manufaturing_from))];
        }
        // $items = fgs_min_item::select('fgs_min_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_min.min_number')
        //                 ->leftjoin('fgs_min_item_rel','fgs_min_item_rel.item','=','fgs_min_item.id')
        //                 ->leftjoin('fgs_min','fgs_min.id','=','fgs_min_item_rel.master')
        //                 ->leftjoin('product_product','product_product.id','=','fgs_min_item.product_id')
        //                 ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_min_item.batchcard_id')
        //                 ->where($condition)
        //                 //->where('inv_mac.status','=',1)
        //                 ->orderBy('fgs_min_item.id','DESC')
        //                 ->distinct('fgs_min_item.id')
        //                 ->paginate(15);
        $items = $this->fgs_cmin_item->get_items($condition);
        //print_r($items);exit; 
       // echo $min_id;exit;
        return view('pages/FGS/CMIN/CMIN-items-list', compact('cmin_id','items'));
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

}   
        
 
