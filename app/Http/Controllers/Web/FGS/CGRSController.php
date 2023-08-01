<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\User;
use App\Models\product;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_grs_item_rel;
use App\Models\FGS\fgs_cgrs;
use App\Models\FGS\fgs_cgrs_item;
use App\Models\FGS\fgs_cgrs_item_rel;
use App\Models\FGS\fgs_mrn_item;
class CGRSController extends Controller
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
        $this->fgs_cgrs = new fgs_cgrs;
        $this->fgs_cgrs_item = new fgs_cgrs_item;
        $this->fgs_cgrs_item_rel = new fgs_cgrs_item_rel;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_maa_stock_management = new fgs_maa_stock_management;
        $this->production_stock_management = new production_stock_management;
        $this->User = new User;
    }
   

      public function CGRSpdf($cgrs_id)
    { 
        $data['cgrs'] = $this->fgs_cgrs->get_single_cgrs(['fgs_cgrs.id' => $cgrs_id]);
        $data['items'] = $this->fgs_cgrs_item->getItems(['fgs_cgrs_item_rel.master' => $cgrs_id]);
        $pdf = PDF::loadView('pages.FGS.CGRS.pdf-view', $data);
        // $pdf->set_paper('A4', 'landscape');
        $file_name = "CGRS" . $data['cgrs']['firm_name'] . "_" . $data['cgrs']['cgrs_date'];
        return $pdf->stream($file_name . '.pdf');
    }

    public function CGRSAdd(Request $request)
        {

           if($request->isMethod('post'))
            {
             $validation['cgrs_date'] = ['required','date'];
             $validation['stock_location1'] = ['required'];
             $validation['stock_location2'] = ['required'];
             
             $validator = Validator::make($request->all(), $validation);
                if(!$validator->errors()->all())
                {
                    if(!$request->id)
                    {
                        if(date('m')==01 || date('m')==02 || date('m')==03)
                        {
                            $years_combo = date('y', strtotime('-1 year')).date('y');
                        }
                        else
                        {
                            $years_combo = date('y').date('y', strtotime('+1 year'));
                        }
                        $data['cgrs_number'] = "CGRS-".$this->year_combo_num_gen(DB::table('fgs_cgrs')->where('fgs_cgrs.cgrs_number', 'LIKE', 'Cgrs-'.$years_combo.'%')->count()); 
                       $data['cgrs_date'] = date('Y-m-d', strtotime($request->cgrs_date));
                       $data['grs_id']= $request->grs_number;
                       $fgs_grs_data = $this->fgs_grs->get_master_data(['fgs_grs.id' => $data['grs_id']]);
                       $data['stock_location1'] = $fgs_grs_data->stock_location1;
                       $data['stock_location2'] = $fgs_grs_data->stock_location2;
                       $data['remarks']= $request->remarks;
                       $data['created_by']= config('user')['user_id'];
                       $data['status']=1;
                       $data['created_at'] =date('Y-m-d H:i:s');
                       $cgrs_id = $this->fgs_cgrs->insert_data($data);
                       $i=0;
                        $qty_to_cancel_array = $request->qty_to_cancel;
                        foreach ($request->grs_item_id as $grs_item_id) 
                        {
                            $grs_item =fgs_grs_item::find($grs_item_id);
                            $datas = [
                                "grs_item_id" => $grs_item_id,
                                "product_id" => $grs_item['product_id'],
                                "batch_quantity" => $qty_to_cancel_array[$i],
                                "created_at" => date('Y-m-d H:i:s')
                            ];
                            $this->fgs_cgrs_item->insert_data($datas,$cgrs_id);
                            if($grs_item['batch_quantity']==$qty_to_cancel_array[$i])
                            {
                                 $fgs_grs_item_qty_update = fgs_grs_item::where('fgs_grs_item.id','=',$grs_item_id)
                                                    // ->where('batchcard_id','=',$grs_item['batchcard_id'])
                                                    // ->where('product_id','=',$grs_item['product_id'])
                                                         ->update(['cgrs_status' => 1]);
                                $update_qty = $grs_item['batch_quantity']-$qty_to_cancel_array[$i];
                                $fgs_grs_item_qty_update = fgs_grs_item::where('product_id','=',$grs_item['product_id'])
                                                                ->update(['remaining_qty_after_cancel'=>$update_qty,'qty_to_invoice'=>$update_qty]);
                   
                            // $fgs_product_stock = fgs_product_stock_management::where('product_id','=',$grs_item['product_id'])
                            //             ->where('batchcard_id','=',$grs_item['batchcard_id'])
                            //             ->where('stock_location_id','=',$fgs_grs_data->stock_location1)
                            //             ->first();

                            // $update_stock = $fgs_product_stock['quantity']+$grs_item['batch_quantity'];
                            // $production_stock = $this->fgs_product_stock_management->update_data(['id'=>$fgs_product_stock['id']],['quantity'=>$update_stock]);

                            // $fgs_maa_stock = fgs_maa_stock_management::where('product_id','=',$grs_item['product_id'])
                            //                     ->where('batchcard_id','=',$grs_item['batchcard_id'])
                            //                     ->first();

                            // $update_maa_stocks = $fgs_maa_stock['quantity']-$grs_item['batch_quantity'];
                            // $maa_stock = $this->fgs_maa_stock_management->update_data(['id'=>$fgs_maa_stock['id']],['quantity'=>$update_maa_stocks]);
                            }
                            else
                            {
                                $update_qty = $grs_item['batch_quantity']-$qty_to_cancel_array[$i];
                                $fgs_grs_item_qty_update = fgs_grs_item::where('product_id','=',$grs_item['product_id'])
                                                ->update(['remaining_qty_after_cancel'=>$update_qty]);
                            }
                            $fgs_product_stock = fgs_product_stock_management::where('product_id','=',$grs_item['product_id'])
                                        ->where('batchcard_id','=',$grs_item['batchcard_id'])
                                        ->where('stock_location_id','=',$fgs_grs_data->stock_location1)
                                        ->first();

                            $update_stock = $fgs_product_stock['quantity']+$qty_to_cancel_array[$i];
                            $production_stock = $this->fgs_product_stock_management->update_data(['id'=>$fgs_product_stock['id']],['quantity'=>$update_stock]);

                            $fgs_maa_stock = fgs_maa_stock_management::where('product_id','=',$grs_item['product_id'])
                                                ->where('batchcard_id','=',$grs_item['batchcard_id'])
                                                ->first();

                            $update_maa_stocks = $fgs_maa_stock['quantity']-$qty_to_cancel_array[$i];
                            $maa_stock = $this->fgs_maa_stock_management->update_data(['id'=>$fgs_maa_stock['id']],['quantity'=>$update_maa_stocks]);
                           

                            $i++;
                
                        }
                        if($cgrs_id & $fgs_grs_item_qty_update)
                        {
                            $request->session()->flash('success', "You have successfully created a CGRS !");
                              return redirect('fgs/CGRS/CGRS-list');
                        }
                        else
                        {
                            $request->session()->flash('error', "CGRS creation is failed. Try again... !");
                            return redirect('FGS/CGRS-add');
                        }
                        
                    }
                   
                }
                if($validator->errors()->all())
                {
                    return redirect('FGS/CGRS-add')->withErrors($validator)->withInput();
                }
            }
            $condition1[] = ['user.status', '=', 1];
            $data['users'] = $this->User->get_all_users($condition1);

            if($request->id){
                $edit['grs'] = $this->fgs_grs->find_grs_datas(['fgs_grs.id' => $request->id]);

                $edit['items'] = $this->fgs_grs_item->get_items(['fgs_grs_item_rel.master' =>$request->id]);
               return view('pages.FGS.CGRS.CGRS-add',compact('edit','data'));
            }
            else
            return view('pages.FGS.CGRS.CGRS-add',compact('data'));
        }
        
         public function findGrsNumberForCGRS(Request $request){
          if ($request->q) {
            $condition[] = ['fgs_grs.grs_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_grs->find_grs_num_for_cgrs($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->grs_details($request->id, null);
            exit;
        }
       }
        public function grsInfo(Request $request)
       {
        if ($request->q) {
           $condition[] = ['fgs_grs.grs_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_grs->find_grs_num_for_cgrs($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->grs_details($request->id, null);
            exit;
        }
      }

      public function grs_details($id, $active = null)
      {
        $grs = $this->fgs_grs->get_master_data(['fgs_grs.id' => $id]);
       //return $grs;
        $grs_item = $this->fgs_grs_item->get_grs_item(['fgs_grs_item_rel.master' => $id]);
        
                    

        $data = '

          <div class="row">
         
            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               grs number (' . $grs->grs_number . ')
                   </label>
              <div class="form-devider"></div>
            </div>
           
           <table class="table table-bordered mg-b-0" style="padding-right: 15px;padding-left: 15px;">
                <thead>
                </thead>
                <tbody >
                    <tr>
                        <th>GRS Date</th>
                        <td>' . date('d-m-Y', strtotime($grs
                ->grs_date)) . '</td>
                    </tr>
                    <tr >
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($grs
                    ->created_at)) . '</td>
                            
                    </tr>
                    <tr >
                            <th>Stock Location1</th>
                            <td>' . $grs->location_name1 . '</td>
                            
                    </tr>
                    <tr>
                            <th>Stock Location2</th>
                            <td>' . $grs->location_name2 . '</td>
                            
                    </tr>
                </tbody>
           </table>
           </div>
           <br>
            <div class="row" >
             <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
                $data .= 'grs Items ';
                $data .= '</label>
                 <div class="form-devider"></div>
             </div>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered mg-b-0" id="example1"  style="padding-right: 15px;padding-left: 15px;">';
            
            $data .= '<thead>
                <tr>
                <th ></th> 
                <th>SKU CODE</th>
                <th>Description</th>
                <th>Batch NUMBER</th>
                <th> Qty</th>
                <th>Quantity To Cancel</th>
                </tr>
               </thead>
               <tbody >';
            foreach ($grs_item as $item) {
                $data .= '<tr>
                    <td ><input type="checkbox" name="grs_item_id[]" id="grs_item_id" onclick="enableTextBox(this)" value="'.$item->id.'"></td>
                       <td>'.$item->sku_code.'</td>
                       <td>'.$item->discription.'</td>
                       <td>'.$item->batch_no.'</td>
                       <td>'.$item->remaining_qty_after_cancel.'</td>
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
                       
                       
                        <input type="hidden" name="created_at" value=" '. date('d-m-Y', strtotime($grs->created_at)). ' ">
                        <input type="hidden" name="stock_location1" value="' .$grs->location_name1. '">
                        <input type="hidden" name="stock_location2" value="' .$grs->location_name2. '">
                         
                        </div>
                </div>
                <br>
                <div class="row" >
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded grs-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                Save 
                            </button>
                        </div>
                    </div>
              ';
        return $data;
    }
    public function CGRSList(Request $request)
    {
        $condition =[];
        if($request->cgrs_no)
        {
            $condition[] = ['fgs_cgrs.cgrs_number','like', '%' . $request->cgrs_no . '%'];
        }
         if($request->stock_location)
        {
            $condition[] = ['fgs_cgrs.stock_location1','like', '%' . $request->stock_location1 . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_cgrs.cgrs_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_cgrs.cgrs_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $cgrs = fgs_cgrs::select('fgs_cgrs.*','product_stock_location.location_name as location_name1',
        'stock_location.location_name as location_name2')
                   ->leftJoin('fgs_grs','fgs_grs.id','fgs_cgrs.grs_id')
                       ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
                   ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
                        ->where($condition)
                        ->orderBy('fgs_cgrs.id','DESC')
                        ->paginate(15);
        return view('pages/FGS/CGRS/CGRS-list', compact('cgrs'));
    }

    public function CGRSItemList(Request $request, $cgrs_id)
    {
        $condition = ['fgs_cgrs_item_rel.master' =>$request->cgrs_id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        
       
        // $items = fgs_grs_item::select('fgs_grs_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_grs.grs_number')
        //                 ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=','fgs_grs_item.id')
        //                 ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
        //                 ->leftjoin('product_product','product_product.id','=','fgs_grs_item.product_id')
        //                 ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
        //                 ->where($condition)
        //                 //->where('inv_mac.status','=',1)
        //                 ->orderBy('fgs_grs_item.id','DESC')
        //                 ->distinct('fgs_grs_item.id')
        //                 ->paginate(15);
        $items = $this->fgs_cgrs_item->get_items($condition);
        //print_r($items);exit; 
       // echo $grs_id;exit;
        return view('pages/FGS/CGRS/CGRS-items-list', compact('cgrs_id','items'));
    }
}   
        
 
