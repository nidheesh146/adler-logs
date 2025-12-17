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
use App\Models\fgs_item_master;
use App\Models\FGS\fgs_cmin_item;
use App\Models\FGS\fgs_cmin_item_rel;
use App\Models\FGS\fgs_mrn;
use App\Models\FGS\fgs_mrn_item;
use Carbon\Carbon;
use App\Models\batchcard;
use App\Models\fgs_item_master as ModelsFgs_item_master;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CMINController extends Controller
{
    public function __construct()
    { 
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->fgs_min = new fgs_min;
        $this->fgs_item_master= new fgs_item_master;
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
   

      public function CMINpdf($cmin_id)
    { 
        $data['cmin'] = $this->fgs_cmin->get_single_min(['fgs_cmin.id' => $cmin_id]);
        $data['items'] = $this->fgs_cmin_item->getItems(['fgs_cmin_item_rel.master' => $cmin_id]);
        $pdf = PDF::loadView('pages.FGS.CMIN.pdf-view', $data);
        // $pdf->set_paper('A4', 'landscape');
       // $pdf->setOptions(['isPhpEnabled' => true]);       
       $pdf->setOptions(['isPhpEnabled' => true]);       

        $file_name = "CMIN" . $data['cmin']['firm_name'] . "_" . $data['cmin']['cmin_date'];
        return $pdf->stream($file_name . '.pdf');
    }

   /* public function CMINAdd(Request $request)
        { 

           if($request->isMethod('post'))
            {
                $validation['cmin_date'] = ['required','date'];
                $validation['min_number'] = ['required'];
                $validation['created_by'] = ['required'];
                $validation['invoice_item.*.sku_code'] = ['required'];
                $validation['invoice_item.*.batch_no'] = ['required'];
                $validation['invoice_item.*.qty'] = ['required'];
                $validator = Validator::make($request->all(), $validation);
                if(!$validator->errors()->all())
                {
                    if(!$request->id)
                    {
                        $qty_to_cancel_array = $request->qty_to_cancel;
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
                        
                        $Data['stock_location']= $fgs_min_data->stock_location;
                        $Data['remarks']= $request->remarks;
                        $cmin_id = $this->fgs_cmin->insert_data($Data);
                        $i=0;
                        $man_date_array = $request->manufacturing_date;

                        foreach ($request->min_item_id as $i => $min_item_id) 
                        {
                            $min_item =fgs_min_item::find($min_item_id);
                            $man_date = $man_date_array[$i];
                            $fgs_product_stock = fgs_product_stock_management::where('product_id', '=', $min_item['product_id'])
                            ->where('batchcard_id', '=', $min_item['batchcard_id'])
                            ->where('stock_location_id', '=', $fgs_min_data->stock_location)
                            ->first();
                            
                            if ($fgs_product_stock->expiry_date == '0000-00-00') {
                                $exp = '0000-00-00';
                            } else {
                                $date = Carbon::parse($man_date);
                                $expDate = $date->addYears(5);
                                $exp = $expDate->toDateString();
                            }
                            $datas = [
                                "cmin_item_id" => $min_item_id,
                                "product_id" => $min_item['product_id'],
                                "batchcard_id" => $min_item['batchcard_id'],
                                "quantity" => $qty_to_cancel_array[$i],
                                "manufacturing_date" => date('Y-m-d',strtotime($man_date)),
                                "expiry_date" => $exp,
                                "created_at" => date('Y-m-d H:i:s')
                            ];
                            $this->fgs_cmin_item->insert_data($datas,$cmin_id);
                            if($min_item['quantity']==$qty_to_cancel_array[$i])
                            {
                                $fgs_min_item = fgs_min_item::where('id','=',$min_item['id'])
                                            ->update(['cmin_status' => 1]);
                            }
                            
                            $updatestock = $min_item['quantity']-$qty_to_cancel_array[$i];
                            $fgs_min_item = fgs_min_item::where('id','=',$min_item['id'])
                                                            ->update(['remaining_qty_after_cancel' => $updatestock]);
                              //mrn
                              $fgs_mrn_item = fgs_mrn_item::select('fgs_mrn_item.*')
                                                    ->leftjoin('fgs_mrn_item_rel','fgs_mrn_item_rel.item','=','fgs_mrn_item.id')
                                                    ->leftjoin('fgs_mrn','fgs_mrn.id','=','fgs_mrn_item_rel.master')
                                                    ->where('fgs_mrn_item.product_id', '=', $min_item['product_id'])
                                                    ->where('fgs_mrn_item.batchcard_id', '=', $min_item['batchcard_id'])
                                                    ->where('fgs_mrn.stock_location', '=', $fgs_min_data->stock_location)
                                                    ->first();
                              
                         
                             //dd($fgs_mrn_item); 
                                    if ($fgs_mrn_item){
                                        fgs_mrn_item::where('id' , $fgs_mrn_item['id'])
                                        ->update([
                                            'manufacturing_date' => date('Y-m-d', strtotime($man_date)),
                                            "expiry_date" => $exp
                                        ]);
                                        }
                                     else
                                    {
                                        fgs_mrn_item::insert([
                                            'product_id' => $min_item['product_id'],
                                            'quantity'=>$qty_to_cancel_array[$i],
                                            'status'=>1,
                                            'batchcard_id' => $min_item['batchcard_id'],
                                            'manufacturing_date' => date('Y-m-d', strtotime($man_date)),
                                            "expiry_date" => $exp
                                        ]);
                                    }
                            // fgs_mrn_item::where('id',$fgs_mrn_item->id)
                            //         ->update(['manufacturing_date' => date('Y-m-d',strtotime($man_date)),
                            //         "expiry_date" => $exp
                            //         ]);
                            $update_stock = $fgs_product_stock['quantity']+$qty_to_cancel_array[$i];
                            $data1 = [
                                "quantity" => $update_stock,
                                "manufacturing_date" => date('Y-m-d',strtotime($man_date)),
                                "expiry_date" => $exp
                            ];
                            $production_stock = $this->fgs_product_stock_management->update_data(['id' => $fgs_product_stock['id']],$data1);
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
        } */
        public function CMINAdd(Request $request)
        {
            if ($request->isMethod('post')) {
                $validation = [
                    'cmin_date' => ['required', 'date'],
                    'min_number' => ['required'],
                    'created_by' => ['required'],
                    'invoice_item.*.sku_code' => ['required'],
                    'invoice_item.*.batch_no' => ['required'],
                    'invoice_item.*.qty' => ['required'],
                ];
                $validator = Validator::make($request->all(), $validation);
        
                if (!$validator->errors()->all()) {
                    if (!$request->id) {
                        $years_combo = (date('m') == 01 || date('m') == 02 || date('m') == 03)
                            ? date('y', strtotime('-1 year')) . date('y')
                            : date('y') . date('y', strtotime('+1 year'));
        
                        $Data = [
                            'cmin_number' => "CMIN-" . $this->year_combo_num_gen(
                                DB::table('fgs_cmin')->where('fgs_cmin.cmin_number', 'LIKE', 'CMIN-' . $years_combo . '%')->count()
                            ),
                            'cmin_date' => date('Y-m-d', strtotime($request->cmin_date)),
                            'created_by' => $request->created_by,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'min_id' => $request->min_number,
                            'remarks' => $request->remarks,
                        ];
        
                        $fgs_min_data = $this->fgs_min->get_master_data(['fgs_min.id' => $Data['min_id']]);
                        $Data['stock_location'] = $fgs_min_data->stock_location;
        
                        $cmin_id = $this->fgs_cmin->insert_data($Data);
        
                        $qty_to_cancel_array = $request->qty_to_cancel;
                        $man_date_array = $request->manufacturing_date;
        
                        foreach ($request->min_item_id as $i => $min_item_id) {
                            $min_item = fgs_min_item::find($min_item_id);
                            $man_date = $man_date_array[$i];
        
                            // Fetch product details to check sterility
                            $product = fgs_item_master::find($min_item['product_id']);
                            if ($product && $product->is_sterile == 1) {
                                $exp = Carbon::parse($man_date)->addYears(5)->subDay()->toDateString();
                            } else {
                                $exp = null;
                            }
        
                            $fgs_product_stock = fgs_product_stock_management::where('product_id', $min_item['product_id'])
                                ->where('batchcard_id', $min_item['batchcard_id'])
                                ->where('stock_location_id', $fgs_min_data->stock_location)
                                ->where('manufacturing_date', date('Y-m-d', strtotime($man_date)))
                                ->first();
        
                            $quantity_to_adjust = abs($qty_to_cancel_array[$i]);
        
                            if ($fgs_product_stock) {
                                $new_quantity = $fgs_product_stock->quantity + $quantity_to_adjust;
                                $data1 = [
                                    "quantity" => $new_quantity,
                                    "manufacturing_date" => date('Y-m-d', strtotime($man_date)),
                                    "expiry_date" => $exp,
                                ];
                                $this->fgs_product_stock_management->update_data(['id' => $fgs_product_stock->id], $data1);
                            } else {
                                $data1 = [
                                    "product_id" => $min_item['product_id'],
                                    "batchcard_id" => $min_item['batchcard_id'],
                                    "stock_location_id" => $fgs_min_data->stock_location,
                                    "quantity" => $quantity_to_adjust,
                                    "manufacturing_date" => date('Y-m-d', strtotime($man_date)),
                                    "expiry_date" => $exp,
                                ];
                                $this->fgs_product_stock_management->insert_data($data1);
                            }
        
                            $datas = [
                                "cmin_item_id" => $min_item_id,
                                "product_id" => $min_item['product_id'],
                                "batchcard_id" => $min_item['batchcard_id'],
                                "quantity" => $quantity_to_adjust,
                                "manufacturing_date" => date('Y-m-d', strtotime($man_date)),
                                "expiry_date" => $exp,
                                "created_at" => now(),
                            ];
                            $this->fgs_cmin_item->insert_data($datas, $cmin_id);
        
                            if ($min_item['quantity'] == $qty_to_cancel_array[$i]) {
                                fgs_min_item::where('id', '=', $min_item['id'])->update(['cmin_status' => 1]);
                            }
        
                            $remaining_qty = $min_item['remaining_qty_after_cancel'] - $qty_to_cancel_array[$i];
                            fgs_min_item::where('id', '=', $min_item['id'])->update(['remaining_qty_after_cancel' => $remaining_qty]);
        
                            $fgs_mrn_item = fgs_mrn_item::select('fgs_mrn_item.*')
                                ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                                ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                                ->where('fgs_mrn_item.product_id', '=', $min_item['product_id'])
                                ->where('fgs_mrn_item.batchcard_id', '=', $min_item['batchcard_id'])
                                ->where('fgs_mrn.stock_location', '=', $fgs_min_data->stock_location)
                                ->first();
        
                            if ($fgs_mrn_item) {
                                fgs_mrn_item::where('id', $fgs_mrn_item['id'])->update([
                                    'manufacturing_date' => date('Y-m-d', strtotime($man_date)),
                                    "expiry_date" => $exp,
                                ]);
                            } else {
                                fgs_mrn_item::insert([
                                    'product_id' => $min_item['product_id'],
                                    'quantity' => $quantity_to_adjust,
                                    'status' => 1,
                                    'batchcard_id' => $min_item['batchcard_id'],
                                    'manufacturing_date' => date('Y-m-d', strtotime($man_date)),
                                    "expiry_date" => $exp,
                                ]);
                            }
                        }
        
                        if ($cmin_id) {
                            $request->session()->flash('success', "You have successfully created a CMIN!");
                            return redirect('fgs/CMIN/CMIN-list');
                        } else {
                            $request->session()->flash('error', "CMIN creation failed. Try again!");
                            return redirect('FGS/CMIN-add');
                        }
                    }
                }
        
                if ($validator->errors()->all()) {
                    return redirect('FGS/CMIN-add')->withErrors($validator)->withInput();
                }
            }
        
            $condition1[] = ['user.status', '=', 1];
            $data['users'] = $this->User->get_all_users($condition1);
        
            if ($request->id) {
                $edit['min'] = $this->fgs_min->find_min_datas(['fgs_min.id' => $request->id]);
                $edit['items'] = $this->fgs_min_item->get_items(['fgs_min_item_rel.master' => $request->id]);
                return view('pages.FGS.CMIN.CMIN-add', compact('edit', 'data'));
            } else {
                return view('pages.FGS.CMIN.CMIN-add', compact('data'));
            }
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

          <div class="row" style="padding-right: 15px;padding-left: 15px; ">
         
            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               MIN number (' . $invoice->min_number . ')
                   </label>
              <div class="form-devider"></div>
            </div>
           
           <table class="table table-bordered mg-b-0">
                <thead>
                </thead>
                <tbody >
                    <tr >
                        <th>Min Date</th>
                        <td>' . date('d-m-Y', strtotime($invoice->min_date)) . '</td>
                    </tr>
                    <tr>
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($invoice->created_at)) . '</td>
                            
                    </tr>
                    <tr >
                            <th>Stock Location</th>
                            <td>' . $invoice->location_name . '</td>
                            
                    </tr>
                </tbody>
           </table>
           </div>
           <br>
            <div class="row" style="padding-right: 15px;padding-left: 15px;" >
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
                <th><input type="checkbox" id="selectAll" onclick="toggleCheckboxes(this)"></th>                
                <th>SKU CODE</th>
                <th>Description</th>
                <th>Batch NUMBER</th>
                <th> Qty</th>
                <th> Qty to cancel</th>
                <th> Manfacturing Date</th>
                </tr>
               </thead>
               <tbody >';
            foreach ($invoice_item as $item) {
                $data .= '<tr>
                       <td ><input type="checkbox" class="rowCheckbox" name="min_item_id[]" onclick="enableTextBox(this)" id="min_item_id" value="' . $item->id . '"></td>
                       <td>'.$item->sku_code.'</td>
                       <td>'.$item->discription.'</td>
                       <td>'.$item->batch_no.'</td>
                       <td>'.$item->remaining_qty_after_cancel.'</td>
                       <td><input type="number" class="qty_to_cancel" id="qty_to_cancel" name="qty_to_cancel[]" min="1" max="'.$item->remaining_qty_after_cancel.'" disabled></td>
                       <td>
                       <input 
                         type="text" 
                         class="form-control datepicker manufacturing_date" 
                         name="manufacturing_date[]" 
                         value=" ' . date('d-m-Y', strtotime($item->manufacturing_date)) . '" 
                         id="manufacturing_date" disabled>
                     </td>
                      
                      </tr>';
            }
            $data .= '</tbody>';
        $data .= '</table>
       </div>
       <div class="row" style="padding-right: 15px;padding-left: 15px;" >
                <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1" style="margin-top: 6px; ">
                            <label>Remarks:</label>
                </div>
        </div>
        <div class="row" style="padding-right: 15px;padding-left: 15px;"  >
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <textarea type="text"  name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks" rows= "4">     </textarea>                       
                        
                       
                    <input type="hidden"  name="stock_location" id="stock_location" value="{{ $invoice->location_name }}" class="form-control" placeholder="Invoice Date"> 
                </div>  
        </div>
           
        <br>
        <div class="row" style="padding-right: 15px;padding-left: 15px;" >
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded" style="float: right !important;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                Save 
                            </button>
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
                        ->where('fgs_cmin.status',1)

                        ->orderby('fgs_cmin.id','DESC')
                        ->paginate(15);
        return view('pages/FGS/CMIN/CMIN-list', compact('cmin'));
    }

    public function CMINItemList(Request $request, $cmin_id)
    {
        $condition = ['fgs_cmin_item_rel.master' =>$request->cmin_id];
        if($request->product)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->product . '%'];
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
        // $items = fgs_min_item::select('fgs_min_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','batchcard_batchcard.batch_no','fgs_min.min_number')
        //                 ->leftjoin('fgs_min_item_rel','fgs_min_item_rel.item','=','fgs_min_item.id')
        //                 ->leftjoin('fgs_min','fgs_min.id','=','fgs_min_item_rel.master')
        //                 ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_min_item.product_id')
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
    public function CMINedit(Request $request, $cmin_item_id)
    {
        $cmin_item = fgs_cmin_item::select('fgs_cmin_item.*', 'fgs_product_stock_management.id as stock_id', 'fgs_product_stock_management.batchcard_id as batch_card_id', 'fgs_product_stock_management.product_id')
            ->leftjoin('fgs_product_stock_management', 'fgs_cmin_item.batchcard_id', '=', 'fgs_product_stock_management.batchcard_id')
            ->where('fgs_cmin_item.id', $cmin_item_id)->first();
    
        if ($request->isMethod('post')) {
            $quantity = $request->quantity;
            $validation['batch_no'] = ['required'];
            $validation['manufacturing_date'] = ['required', 'date'];
    
            $validator = Validator::make($request->all(), $validation);
    
            if (!$validator->errors()->all()) {
                $product = $cmin_item->product_id;
                $old_batch = $cmin_item->batch_card_id;
    
                $oldbatchstk = fgs_product_stock_management::where('product_id', '=', $product)
                    ->where('batchcard_id', '=', $old_batch)
                    ->first();
    
                $min = fgs_min::leftjoin('fgs_min_item_rel', 'fgs_min_item_rel.master', '=', 'fgs_min.id')
                    ->where('fgs_min_item_rel.item', $cmin_item->cmin_item_id)->first();
    
                $batchcard_exist = DB::table('batchcard_batchcard')->where('id', '=', $request->batch_no)->first();
                $qty = $batchcard_exist->quantity - $request->quantity;
    
                DB::table('batchcard_batchcard')
                    ->where('id', '=', $request->batch_no)
                    ->update(["quantity" => $qty]);
    
                $expiry_date = ($request['expiry_date1'] != 'N.A') ? date('Y-m-d', strtotime($request['expiry_date1'])) : '';
    
                $min_item = fgs_min_item::find($cmin_item->cmin_item_id);
    
                fgs_min_item::where('id', $min_item->id)
                    ->update([
                        'batchcard_id' => $request->batch_no,
                        'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
                        'expiry_date' => $expiry_date
                    ]);
    
                DB::table('fgs_mrn_item')
                    ->where('batchcard_id', '=', $request->batch_no)
                    ->update([
                        'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
                    ]);
    
                $new_stock = DB::table('fgs_product_stock_management')
                    ->where('product_id', '=', $product)
                    ->where('batchcard_id', '=', $request->batch_no)
                    ->where('stock_location_id', $min->stock_location)
                    ->first();
    
                if (!empty($oldbatchstk)) {
                    DB::table('fgs_product_stock_management')
                        ->where('id', $oldbatchstk->id)
                        ->decrement('quantity', $request->quantity);
                }
    
                if (!empty($new_stock)) {
                    $new_stock_update = $new_stock->quantity + $request->quantity;
    
                    DB::table('fgs_product_stock_management')
                        ->where('id', $new_stock->id)
                        ->update([
                            'quantity' => $new_stock_update,
                            'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
                            'expiry_date' => $expiry_date,
                            'batchcard_id' => $request->batch_no
                        ]);
                } else {
                    DB::table('fgs_product_stock_management')->insert([
                        'product_id' => $product,
                        'batchcard_id' => $request->batch_no,
                        'stock_location_id' => $min->stock_location,
                        'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
                        'expiry_date' => $expiry_date,
                        'quantity' => $request->quantity
                    ]);
                }
    
                $data = [
                    'batchcard_id' => $request->batch_no,
                    'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
                    'expiry_date' => $expiry_date,
                ];
    
                $update_cmin = $this->fgs_cmin_item->update_data(['id' => $cmin_item_id], $data);
    
                $request->session()->flash('success', "You have successfully updated a MIN Item!");
                return redirect('fgs/CMIN/items-list/' . $request->cmin_id);
            }
    
            $request->session()->flash('error', "You have failed to update a MIN Item!");
            return redirect('fgs/CMIN/items-list/' . $request->cmin_id);
        } else {
            $item_details = fgs_cmin_item::select(
                'fgs_cmin_item.*',
                'fgs_cmin.id as cmin_id',
                'fgs_item_master.sku_code',
                'fgs_item_master.discription',
                'fgs_item_master.id as product_id',
                'fgs_item_master.hsn_code',
                'batchcard_batchcard.id as batch_id',
                'batchcard_batchcard.batch_no',
                'fgs_cmin.cmin_number',
                'fgs_product_stock_management.quantity as stk_qty',
                'fgs_product_stock_management.stock_location_id'
            )
                ->leftjoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
                ->leftjoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')
                ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_cmin_item.product_id')
                ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cmin_item.batchcard_id')
                ->leftjoin('fgs_product_stock_management', 'fgs_product_stock_management.batchcard_id', '=', 'batchcard_batchcard.id')
                ->where('fgs_cmin_item.id', $cmin_item_id)
                ->orderBy('fgs_cmin_item.id', 'DESC')
                ->first();
    
            $batchcards = batchcard::select(
                'batchcard_batchcard.batch_no',
                'batchcard_batchcard.id as batch_id',
                'batchcard_batchcard.start_date',
                'batchcard_batchcard.target_date',
                'batchcard_batchcard.quantity'
            )
                ->where('batchcard_batchcard.product_id', '=', $item_details->product_id)
                ->orderBy('batchcard_batchcard.id', 'asc')
                ->get();
    
            return view('pages/FGS/CMIN/CMIN-item-edit', compact('item_details', 'batchcards'));
        }
    }
    public function CMINNewManualAddPage(Request $request)
{
    if($request->isMethod('post'))
    {
        $validation['cmin_date'] = ['required','date'];
        $validation['stock_location_increase'] = ['required'];
        $validation['remarks'] = [''];
        $validation['product_category'] =['required']; // Adjust validation rules based on your needs
        $validation['new_product_category'] =['required']; // Adjust validation rules based on your needs
        $validator = Validator::make($request->all(), $validation);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if(!$validator->errors()->all())
        {
            $file = $request->file('cmin_file');
            if ($file) 
            {
                $ExcelOBJ = new \stdClass();

                $path = storage_path() . '/app/' . $request->file('cmin_file')->store('temp');
                $ExcelOBJ->inputFileName = $path;
                $ExcelOBJ->inputFileType = 'Xlsx';
                $ExcelOBJ->spreadsheet = new Spreadsheet();
                $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
                $ExcelOBJ->reader->setReadDataOnly(true);
                $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
                $no_column = 5;
                $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];

                if ($sheet1_column_count == $no_column) {
                    $res = $this->Excelsplitsheet($ExcelOBJ, $request);
                    if ($res) {
                        $request->session()->flash('success', "You have successfully added a CMIN!");
                        return redirect('fgs/manual-CMIN');
                    } else {
                        $request->session()->flash('error', "The data is already uploaded.");
                        return redirect()->back();
                    }
                } else {
                    $request->session()->flash('error', "Column count mismatch. Please download the template and verify.");
                    return redirect()->back();
                }
            }
        }
    }
    else
    {
        $locations = product_stock_location::get();
        $category = fgs_product_category::get();
        return view('pages/FGS/CMIN/CMIN-manual',compact('locations','category'));
    }
}

public function Excelsplitsheet($ExcelOBJ, $request)
{
   // dd("excel");
    $ExcelOBJ->SQLdata = [];
    $ExcelOBJ->arrayinc = 0;

    foreach ($ExcelOBJ->worksheetData as $key => $worksheet) {
       // dd("excelfor");

        $ExcelOBJ->sectionName = '';
        $ExcelOBJ->sheetName = $worksheet['worksheetName'];
        $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
        $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
        $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
        $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
        $ExcelOBJ->date_created = date('Y-m-d H:i:s');
        $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
        $res = $this->insert_cmin_items($ExcelOBJ, $request);
        return $res;
    }
}
public function insert_cmin_items($ExcelOBJ, $request)
{
    $years_combo = date('Y'); // Default year combination
    $product_category = $request->input('product_category_hidden');
    $new_product_category = $request->input('new_product_category_hidden');
    
    if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
        $years_combo = date('y', strtotime('-1 year')) . date('y');
    } else {
        $years_combo = date('y') . date('y', strtotime('+1 year'));
    }

    // Prepare data for insertion into fgs_cmin
    $data = [
        'cmin_number' => "CMIN-" . $this->year_combo_num_gen(
            DB::table('fgs_cmin')->where('fgs_cmin.cmin_number', 'LIKE', 'CMIN-' . $years_combo . '%')->count()
        ),
        'cmin_date' => date('Y-m-d', strtotime($request->cmin_date)),
        'remarks' => $request->remarks,
        'stock_location' => $request->stock_location_increase,
        'product_category' => $product_category,
        'new_product_category' => $new_product_category,
        'created_by' => config('user')['user_id'],
        'min_id' => $request->min_number,
        'status' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    \Log::info('Preparing data for fgs_cmin:', $data);

    // Insert into fgs_cmin and get the inserted ID
    $cmin_id = $this->fgs_cmin->insert_data($data);

    if (!$cmin_id) {
        \Log::error('Failed to insert into fgs_cmin.', $data);
        return response()->json(['error' => 'Failed to insert into fgs_cmin'], 500);
    }

    \Log::info('Successfully inserted into fgs_cmin. ID:', [$cmin_id]);

    $item_id = null; // Initialize $item_id

    // Process the Excel file and insert items
    foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
        if ($key > 0 && !empty($excelsheet[0])) {
            $product_id = DB::table('product_product')->where('sku_code', $excelsheet[0])->value('id');
            $batchcard_id = DB::table('batchcard_batchcard')
                ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
                ->where('batchcard_batchcard.batch_no', $excelsheet[1])
                ->value('batchcard_batchcard.id');

            \Log::info("Processing Excel Row: Product SKU: {$excelsheet[0]}, Batch No: {$excelsheet[1]}, Found Product ID: {$product_id}, Batchcard ID: {$batchcard_id}");

            if ($product_id && $batchcard_id) {
                $fgs_min_item_id = DB::table('fgs_min_item')
                    ->where('product_id', $product_id)
                    ->where('batchcard_id', $batchcard_id)
                    ->value('id');

                \Log::info("fgs_min_item_id found:", ['id' => $fgs_min_item_id]);

                // if (!$fgs_min_item_id) {
                //     \Log::warning("Skipping item insert: No fgs_min_item_id for Product ID: {$product_id}, Batchcard ID: {$batchcard_id}");
                //     continue; // Skip to next iteration
                // }

                // Retrieve is_sterile flag from fgs_item_master
                $is_sterile = DB::table('fgs_item_master')
                    ->where('id', $product_id)
                    ->value('is_sterile');

                \Log::info("is_sterile value:", ['is_sterile' => $is_sterile]);

                // Determine manufacturing date
                $manufacturing_date = null;
                if (!empty($excelsheet[2])) {
                    $manufacturing_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d');
                }
                \Log::info("Manufacturing Date:", ['date' => $manufacturing_date]);

                // Determine expiry date
                $expiry_date = null;
                if ($is_sterile == 1 && $manufacturing_date) {
                    $expiry_date = \Carbon\Carbon::parse($manufacturing_date)->addYears(5)->subDay()->format('Y-m-d');
                }

                \Log::info("Expiry Date Calculated:", ['expiry_date' => $expiry_date]);

                // Prepare item data
                $item = [
                    'product_id' => $product_id,
                    'batchcard_id' => $batchcard_id,
                    'quantity' => $excelsheet[4],
                    'manufacturing_date' => $manufacturing_date,
                    'expiry_date' => $expiry_date,
                    'cmin_item_id' => $fgs_min_item_id,
                ];

                // Insert into fgs_cmin_item
                $item_id = DB::table('fgs_cmin_item')->insertGetId($item);

                \Log::info("Inserted item into fgs_cmin_item:", ['item_id' => $item_id]);

                // Insert into relation table
                DB::table('fgs_cmin_item_rel')->insert([
                    'master' => $cmin_id,
                    'item' => $item_id,
                ]);

                // Now update the stock
                $existing_stock = DB::table('fgs_product_stock_management')
                    ->where('product_id', $product_id)
                    ->where('batchcard_id', $batchcard_id)
                    ->where('stock_location_id', $request->stock_location_increase)
                    ->where('manufacturing_date', $manufacturing_date)
                    ->where('expiry_date', $expiry_date)
                    ->first();

                if ($existing_stock) {
                    // Update existing stock quantity
                    $new_quantity = $existing_stock->quantity + $excelsheet[4];
                    DB::table('fgs_product_stock_management')
                        ->where('id', $existing_stock->id)
                        ->update(['quantity' => $new_quantity]);

                    \Log::info("Updated stock quantity:", ['id' => $existing_stock->id, 'new_quantity' => $new_quantity]);
                } else {
                    // Insert new stock entry
                    DB::table('fgs_product_stock_management')->insert([
                        'product_id' => $product_id,
                        'batchcard_id' => $batchcard_id,
                        'stock_location_id' => $request->stock_location_increase,
                        'manufacturing_date' => $manufacturing_date,
                        'expiry_date' => $expiry_date,
                        'quantity' => $excelsheet[4],
                    ]);

                    \Log::info("Inserted new stock record:", ['product_id' => $product_id, 'batchcard_id' => $batchcard_id]);
                }
            }
        }
    }

    return response()->json(['success' => true, 'cmin_id' => $cmin_id, 'last_item_id' => $item_id]);
}


public function fetchCategoriesCMIN(Request $request)
{
    $CMINInputNumber = $request->input('min_number');
    
    // Fetch the delivery_challan record based on the selected dc_number
    $CMIN_Number = fgs_min::where('id', $CMINInputNumber)->first(); // Use 'min_number' as the column to filter

    if ($CMIN_Number) {
        // Fetch product categories based on the IDs from the deliveryChallan
        $productCategory = DB::table('fgs_product_category')->where('id', $CMIN_Number->product_category)->first();
        $newProductCategory = DB::table('fgs_product_category_new')->where('id', $CMIN_Number->new_product_category)->first();

        return response()->json([
            'success' => true,
            'product_category' => $productCategory,
            'new_product_category' => $newProductCategory,
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Invalid MIN number selected.']);
}
    }
    
        
    
        
 
