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
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_min;
use App\Models\FGS\fgs_min_item;
use App\Models\FGS\fgs_min_item_rel;
use App\Models\FGS\fgs_cmin;
use App\Models\FGS\fgs_cmin_item;
use App\Models\FGS\fgs_cmin_item_rel;
class CMINController extends Controller
{
    public function __construct()
    { 
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->fgs_min = new fgs_min;
        $this->fgs_min_item = new fgs_min_item;
        $this->fgs_min_item_rel = new fgs_min_item_rel;
        $this->fgs_cmin = new fgs_cmin;
        $this->fgs_cmin_item = new fgs_cmin_item;
        $this->fgs_cmin_item_rel = new fgs_cmin_item_rel;
        $this->product = new product;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->production_stock_management = new production_stock_management;
        $this->User = new User;
    }
   

      public function MINpdf($min_id)
    { 
        $data['min'] = $this->fgs_min->get_single_min(['fgs_min.id' => $min_id]);
        $data['items'] = $this->fgs_min_item->getItems(['fgs_min_item_rel.master' => $min_id]);
        $pdf = PDF::loadView('pages.FGS.MIN.pdf-view', $data);
        // $pdf->set_paper('A4', 'landscape');
        $file_name = "MIN" . $data['min']['firm_name'] . "_" . $data['min']['min_date'];
        return $pdf->stream($file_name . '.pdf');
    }

    public function CMINAdd(Request $request)
        {

           if($request->isMethod('post'))
            {
                $validation['cmin_date'] = ['required','date'];
                $validation['min_number'] = ['required'];
                $validation['created_by'] = ['required'];
                $validation['remarks'] = ['required'];
                $validation['invoice_item.*.sku_code'] = ['required'];
                $validation['invoice_item.*.batch_no'] = ['required'];
                $validation['invoice_item.*.qty'] = ['required'];
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
                        $Data['cmin_number'] = "CMIN-".$this->year_combo_num_gen(DB::table('fgs_cmin')->where('fgs_cmin.cmin_number', 'LIKE', 'CMIN-'.$years_combo.'%')->count()); 
                        $Data['cmin_date']=date('Y-m-d', strtotime($request->cmin_date));
                        $Data['created_by']= $request->created_by;
                        $Data['created_at'] =date('Y-m-d H:i:s');
                        $Data['updated_at'] =date('Y-m-d H:i:s');
                        $Data['min_id']= $request->min_number;
                        $fgs_min_data = $this->fgs_min->get_master_data(['fgs_min.id' => $Data['min_id']]);
                        
                        $Data['stock_location']= $loc->stock_location;
                        $Data['remarks']= $request->remarks;
                        $cmin_id = $this->fgs_cmin->insert_data($Data);

                        foreach ($request->min_item_id as $min_item_id) 
                        {
                            $min_item =fgs_min_item::find($min_item_id);
                            $datas = [
                                "cmin_item_id" => $min_item_id,
                                "product_id" => $min_item['product_id'],
                                "batchcard_id" => $min_item['batchcard_id'],
                                "quantity" => $min_item['quantity'],
                                "created_at" => date('Y-m-d H:i:s')
                            ];
                            $this->fgs_cmin_item->insert_data($datas,$cmin_id);
                            $fgs_min_item = fgs_min_item::where('batchcard_id','=',$min_item['batchcard_id'])
                                        ->where('product_id','=',$min_item['product_id'])
                                        ->update(['cmin_status' => 1]);
                   
                            $fgs_product_stock = fgs_product_stock_management::where('product_id','=',$min_item['product_id'])
                                                ->where('batchcard_id','=',$min_item['batchcard_id'])
                                                ->where('stock_location_id','=',$fgs_min_data->stock_location)
                                                ->first();
                            $update_stock = $fgs_product_stock['quantity']+$min_item['quantity'];
                            $production_stock = $this->fgs_product_stock_management->update_data(['id'=>$fgs_product_stock['id']],['quantity'=>$update_stock]);
                        }
                        if($cmin_id )
                        {
                            $request->session()->flash('success', "You have successfully created a CMIN !");
                              return redirect('fgs/CMIN/CMIN-list');
                        }
                        else
                        {
                            $request->session()->flash('error', "MAC creation is failed. Try again... !");
                            return redirect('FGS/CMIN-add');
                        }
                        
                    }
                   
                }
                if($validator->errors()->all())
                {
                    return redirect('FGS/CMIN-add')->withErrors($validator)->withInput();
                }
            }
            $condition1[] = ['user.status', '=', 1];
            $data['users'] = $this->User->get_all_users($condition1);

            if($request->id){
                $edit['min'] = $this->fgs_min->find_min_datas(['fgs_min.id' => $request->id]);

                $edit['items'] = $this->fgs_min_item->get_items(['fgs_min_item_rel.master' =>$request->id]);
               return view('pages.FGS.CMIN.CMIN-add',compact('edit','data'));
            }
            else
            return view('pages.FGS.CMIN.CMIN-add',compact('data'));
        }
        
         public function findMinNumberForCMIN(Request $request){
        if ($request->q) {
            $condition[] = ['fgs_min.min_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_min->find_min_num_for_cmin($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->min_details($request->id, null);
            exit;
        }
       }
        public function minInfo(Request $request)
       {
        if ($request->q) {
           $condition[] = ['fgs_min.min_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_min->find_min_num_for_cmin($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->min_details($request->id, null);
            exit;
        }
      }

      public function min_details($id, $active = null)
      {
        $invoice = $this->fgs_min->get_master_data(['fgs_min.id' => $id]);
       //return $invoice;
        $invoice_item = $this->fgs_min_item->get_min_item(['fgs_min_item_rel.master' => $id]);
        
                    

        $data = '

          <div class="row" style=" width:100%; ">
         
            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               MIN number (' . $invoice->min_number . ')
                   </label>
              <div class="form-devider"></div>
            </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
                </thead>
                <tbody style=" width:100%;">
                    <tr style=" width:100%;">
                        <th>Min Date</th>
                        <td>' . date('d-m-Y', strtotime($invoice->min_date)) . '</td>
                    </tr>
                    <tr style=" width:100%;">
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($invoice->created_at)) . '</td>
                            
                    </tr>
                    <tr style=" width:100%;">
                            <th>Stock Location</th>
                            <td>' . $invoice->location_name . '</td>
                            
                    </tr>
                </tbody>
           </table>
           <br>
            <div class="row" style=" width:100%; ">
             <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
                $data .= 'MIN Items ';
                $data .= '</label>
                 <div class="form-devider"></div>
             </div>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered mg-b-0" id="example1">';
            
            $data .= '<thead>
                <tr>
                <th ></th> 
                <th>SKU CODE</th>
                <th>Description</th>
                <th>Batch NUMBER</th>
                <th> Qty</th>
                </tr>
               </thead>
               <tbody >';
            foreach ($invoice_item as $item) {
                $data .= '<tr>
                    <td ><input type="checkbox" name="min_item_id[]" id="min_item_id" value="'.$item->id.'"></td>
                       <td>'.$item->sku_code.'</td>
                       <td>'.$item->discription.'</td>
                       <td>'.$item->batch_no.'</td>
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
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <textarea type="text"  name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks" rows= "4">     </textarea>                       
                        </div>
                       
                        <input type="hidden"  name="stock_location" id="stock_location" value="{{ $invoice->location_name }}" class="form-control" placeholder="Invoice Date"> 
                          
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
    public function CMINList(Request $request)
    {
        $condition =[];
        if($request->cmin_no)
        {
            $condition[] = ['fgs_cmin.cmin_number','like', '%' . $request->cmin_no . '%'];
        }
        if($request->stock_location)
        {
            $condition[] = ['fgs_cmin.stock_location','like', '%' . $request->stock_location . '%'];
        }
        
        if($request->from)
        {
            $condition[] = ['fgs_cmin.cmin_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_cmin.cmin_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $cmin = fgs_cmin::select('fgs_cmin.*','fgs_product_category.category_name','product_stock_location.location_name','fgs_min.min_number')
                   ->leftJoin('fgs_min','fgs_min.id','fgs_cmin.min_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_min.product_category')
                        ->leftJoin('product_stock_location','product_stock_location.id','fgs_cmin.stock_location')
                        ->where($condition)
                        ->paginate(15);
        return view('pages/FGS/CMIN/CMIN-list', compact('cmin'));
    }

    public function CMINItemList(Request $request, $cmin_id)
    {
        $condition = ['fgs_cmin_item_rel.master' =>$request->cmin_id];
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
}   
        
 
