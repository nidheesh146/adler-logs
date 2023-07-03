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
         if($request->remarks)
        {
            $condition[] = ['fgs_coef.remarks','like', '%' . $request->remarks . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_coef.coef_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_coef.coef_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $coef = fgs_coef::select('fgs_coef.*')
                 ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_coef.oef_id')
                        ->where($condition)
                        ->distinct('fgs_coef.id')
                        ->paginate(15);
          return view('pages/FGS/COEF/COEF-list', compact('coef'));
    }
    public function COEFAdd(Request $request)
    { 
         
        if($request->isMethod('post'))
        { 
        //     print_r($request->oef_item_id);
        //  print_r($request->qty_to_cancel);exit;
           $validation['coef_date'] = ['required'];
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
                $data['coef_date'] = date('Y-m-d', strtotime($request->coef_date));
                $data['oef_id'] = $request->oef_number;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $data['remarks'] = $request->remarks;
                        

                $coef_id = $this->fgs_coef->insert_data($data);
                $i=0;
                $qty_to_cancel_array = $request->qty_to_cancel;
                foreach ($request->oef_item_id as $oef_item_id)
                {
                        $oef_item =fgs_oef_item::find($oef_item_id);
                        $datas = [
                            "coef_item_id" => $oef_item_id,
                            "product_id" => $oef_item['product_id'],
                            //"quantity" => $oef_item['quantity'],
                            "quantity" =>$qty_to_cancel_array[$i],
                            "created_at" => date('Y-m-d H:i:s')
                        ];

                         $this->fgs_coef_item->insert_data($datas,$coef_id);
                         if($oef_item['quantity_to_allocate']==$qty_to_cancel_array[$i])
                         {
                            $fgs_oef_item = fgs_oef_item::where('product_id','=',$oef_item['product_id'])
                                            ->update(['coef_status' => 1,'remaining_qty_after_cancel'=>0]);
                         }
                         else
                         {
                            $update_qty = $oef_item['quantity_to_allocate']-$qty_to_cancel_array[$i];
                            $fgs_oef_item = fgs_oef_item::where('product_id','=',$oef_item['product_id'])
                                            ->update(['remaining_qty_after_cancel'=>$update_qty,'quantity_to_allocate'=>$update_qty]);
                         }
                         $i++;
                }
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
              return view('pages.FGS.COEF.COEF-add',compact('edit','data','transaction_type'));
         }
        else
        {
            $transaction_type = transaction_type::get();    
            return view('pages.FGS.COEF.COEF-add',compact('data','transaction_type'));
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

        <div class="row" >
         
            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               OEF number (' . $oef->oef_number . ')
                   </label>
                <div class="form-devider"></div>
            </div>
          
            <table class="table table-bordered mg-b-0" style="padding-right: 15px;padding-left: 15px;">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <th>OEF Date</th>
                        <td>' . date('d-m-Y', strtotime($oef->oef_date)) . '</td>
                    </tr>
                    <tr >
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($oef->created_at)) . '</td>
                            
                    </tr>
                    
                     
                  
                </tbody>
           </table>
        </div>
        <br>
        <div class="row" >
             <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
                $data .= 'OEF Items ';
                $data .= '</label>
                <div class="form-devider"></div>
             </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mg-b-0" id="example1" style="padding-right: 15px;padding-left: 15px;">';
            
            $data .= '<thead>
                <tr>
                <th ></th> 
               <th>PRODUCT</th>
                <th>HSN CODE</th>
                <th>DESCRIPTION</th>
                <th> QUANTITY</th>
                <th>QUANTITY TO CANCEL</th>
                </tr>
               </thead>
               <tbody >';
               foreach ($oef_item as $item) {
                $data .= '<tr>
                    <td ><input type="checkbox" name="oef_item_id[]" id="oef_item_id" onclick="enableTextBox(this)" value="'.$item->id.'"></td>
                       <td>'.$item->sku_code.'</td>
                       <td>'.$item->hsn_code.'</td>
                        <td>'.$item->discription.'</td>
                       <td class="qty" data-qty='.$item->remaining_qty_after_cancel.'>'.$item->remaining_qty_after_cancel.'</td>
                       <td><input type="number" class="qty_to_cancel" id="qty_to_cancel" name="qty_to_cancel[]" min="1" max="'.$item->remaining_qty_after_cancel.'" disabled></td>
                      </tr>';
                }
                $data .= '</tbody>';
                $data .= '</table>
        </div>
        <div class="row">
                <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1" style="margin-top: 6px; ">
                   <label>Remarks:</label>
                </div>
        </div>
        <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <textarea type="text"  name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks" rows= "4">     </textarea>     
                       
                        
                        <input type="hidden" name="created_at" value=" '. date('d-m-Y', strtotime($oef->created_at)). ' ">
                        <input type="hidden" name="order_number" value="' .$oef->order_number. '">
                        <input type="hidden" name="customer_id" value="' .$oef->customer_id. '">
                        <input type="hidden" name="transaction_type" value="' .$oef->transaction_type. '">
                </div>
        </div>
                <br>
        <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded invoice-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                Save 
                            </button>
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
        $items = $this->fgs_coef_item->get_items($condition);
        //print_r($items);exit; 
       // echo $min_id;exit;
        return view('pages/FGS/COEF/COEF-item-list', compact('coef_id','items'));
    }
    public function COEFpdf($oef_id)
    {
        $data['coef'] = $this->fgs_coef->get_single_oef(['fgs_coef.id' => $oef_id]);
        $data['items'] = $this->fgs_coef_item->getAllItems(['fgs_coef_item_rel.master' => $oef_id]);
        $pdf = PDF::loadView('pages.FGS.COEF.pdf-view', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "COEF" . $data['coef']['firm_name'] . "_" . $data['coef']['coef_date'];
        return $pdf->stream($file_name . '.pdf');
    }

}   
        
 
