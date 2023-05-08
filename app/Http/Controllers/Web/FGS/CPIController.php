<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\User;
use App\Models\FGS\fgs_pi;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_grs_item_rel;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_cpi;
use App\Models\FGS\fgs_cpi_item;
use App\Models\FGS\fgs_cpi_item_rel;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\product;
class CPIController extends Controller
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
        $this->product = new product;
        $this->fgs_cpi = new fgs_cpi;
        $this->fgs_cpi_item = new fgs_cpi_item;
        $this->fgs_cpi_item_rel = new fgs_cpi_item_rel;
        $this->User = new User;
    }
    public function CPIList(Request $request)
    {
        $condition =[];
        if($request->cpi_number)
        {
            $condition[] = ['fgs_cpi.cpi_number','like', '%' . $request->cpi_number . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_cpi.cpi_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_cpi.cpi_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $cpi = fgs_cpi::select('fgs_cpi.*')
                ->where($condition)
                ->distinct('fgs_cpi.id')
                ->paginate(15);
        return view('pages/FGS/CPI/CPI-list', compact('cpi'));
    }
    public function CPIAdd(Request $request) 
    {
        if($request->isMethod('post'))
        {
            $validation['cpi_date'] = ['required'];
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
                $data['cpi_number'] = "CPI-".$this->year_combo_num_gen(DB::table('fgs_cpi')->where('fgs_cpi.cpi_number', 'LIKE', 'CPI-'.$years_combo.'%')->count()); 
                $data['cpi_date'] = date('Y-m-d',strtotime($request->cpi_date));
                $data['created_by'] = config('user')['user_id'];
                $data['pi_id'] = $request->pi_number;
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $data['remarks'] = $request->remarks;
                $data['customer_id'] = $request->customer_id;       

                $cpi_id = $this->fgs_cpi->insert_data($data);

                foreach ($request->pi_item_id as $pi_item_id) {
                        $pi_item =fgs_pi_item::find($pi_item_id);
                        $datas = [
                            "pi_item_id" => $pi_item_id,
                            "grs_id" => $pi_item['grs_id'],
                            "grs_item_id" => $pi_item['grs_item_id'],
                            "mrn_item_id" => $pi_item['mrn_item_id'],
                            "created_at" => date('Y-m-d H:i:s')
                        ];

                         $this->fgs_cpi_item->insert_data($datas,$cpi_id);
                         $fgs_pi_item = fgs_pi_item::
                                        where('grs_id','=',$pi_item['grs_id'])
                                        ->update(['cpi_status' => 1]);
                         $grs_item = fgs_grs_item::where('id','=',$pi_item['grs_item_id'])->first();   
                        $fgs_grs_data = fgs_grs::where('id','=',$pi_item['grs_id'])->first();
                        $fgs_product_stock = fgs_product_stock_management::where('product_id','=',$grs_item['product_id'])
                                        ->where('batchcard_id','=',$grs_item['batchcard_id'])
                                        ->where('stock_location_id','=',$fgs_grs_data->stock_location1)
                                        ->first();

                            $update_stock = $fgs_product_stock['quantity']+$grs_item['batch_quantity'];
                            $production_stock = $this->fgs_product_stock_management->update_data(['id'=>$fgs_product_stock['id']],['quantity'=>$update_stock]);

                            $fgs_maa_stock = fgs_maa_stock_management::where('product_id','=',$grs_item['product_id'])
                                                ->where('batchcard_id','=',$grs_item['batchcard_id'])
                                                ->first();

                            $update_maa_stocks = $fgs_maa_stock['quantity']-$grs_item['batch_quantity'];
                            $maa_stock = $this->fgs_maa_stock_management->update_data(['id'=>$fgs_maa_stock['id']],['quantity'=>$update_maa_stocks]);
                   
                if($cpi_id)
                {
                    $request->session()->flash('success', "You have successfully added a CPI !");
                    return redirect('fgs/CPI/CPI-list');
                }
                else
                {
                    $request->session()->flash('error', "CPI insertion is failed. Try again... !");
                    return redirect('fgs/CPI/CPI-add');
                }

            }
        }
            if($validator->errors()->all())
                {
                    return redirect('FGS/CPI/CPI-add')->withErrors($validator)->withInput();
                }
            }
            $condition1[] = ['user.status', '=', 1];
            $data['users'] = $this->User->get_all_users($condition1);

            if($request->id){
                $edit['pi'] = $this->fgs_pi->find_pi_datas(['fgs_pi.id' => $request->id]);
                $edit['items'] = $this->fgs_pi_item->get_items(['fgs_pi_item_rel.master' =>$request->id]);
                $transaction_type = transaction_type::get();
              return view('pages.FGS.CPI.CPI-add',compact('edit','data','transaction_type'));
            }

            else
            {
                  
            
            return view('pages.FGS.CPI.CPI-add',compact('data'));
       
    }
}
    public function PIitemlist($pi_id)
    {
        $pi_item = fgs_pi_item_rel::select('fgs_grs.grs_number','product_product.sku_code','product_product.hsn_code','product_product.discription',
        'batchcard_batchcard.batch_no','fgs_grs_item.batch_quantity','fgs_pi_item.rate','fgs_pi_item.discount','currency_exchange_rate.currency_code')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_pi_item_rel.item','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date')
                        ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                        ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_pi.customer_id')
                        ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                        ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_pi_item.grs_id')
                        ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_pi_item.grs_item_id')
                        ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_grs_item.pi_item_id')
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
                                <th>pi Number</th>
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
                        <td>'.$grs->pi_number.'</td>
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

    public function CPIpdf($cpi_id)
    {
        $data['cpi'] = $this->fgs_cpi->get_single_cpi(['fgs_cpi.id' => $cpi_id]);
        $data['items'] = $this->fgs_cpi_item->getItems(['fgs_cpi_item.id' => $cpi_id]);
        $pdf = PDF::loadView('pages.FGS.CPI.pdf-view', $data);
        $pdf->set_paper('A4', 'landscape');
        $file_name = "CPI" . $data['cpi']['cpi_number'] . "_" . $data['cpi']['cpi_date'];
        return $pdf->stream($file_name . '.pdf');
    }
     public function findPiNumberForCPI(Request $request){
        if ($request->q) {
            $condition[] = ['fgs_pi.pi_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_pi->find_pi_num_for_cpi($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->pi_details($request->id, null);
            exit;
        }
       }
        public function piInfo(Request $request)
       {
        if ($request->q) {
           $condition[] = ['fgs_pi.pi_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_pi->find_pi_num_for_cpi($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->pi_details($request->id, null);
            exit;
        }
      }

      public function pi_details($id, $active = null)
      {
        $pi = $this->fgs_pi->get_master_data(['fgs_pi.id' => $id]);
       //return $invoice;
        $pi_item = $this->fgs_pi_item->get_pi_item(['fgs_pi_item_rel.master' => $id]);
        $data = '

          <div class="row">
         
            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               MIN number (' . $pi->pi_number . ')
                   </label>
              <div class="form-devider"></div>
            </div>
         
           <table class="table table-bordered mg-b-0" style="padding-right: 15px;padding-left: 15px;">
                <thead>
                </thead>
                <tbody >
                    <tr>
                        <th>PI Date</th>
                        <td>' . date('d-m-Y', strtotime($pi->pi_date)) . '</td>
                    </tr>
                    <tr>
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($pi->created_at)) . '</td>
                            
                    </tr>
                    
                     
                  
             </tbody>
           </table>
           </div>
           <br>
            <div class="row" >
             <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
                $data .= 'PI Items ';
                $data .= '</label>
                 <div class="form-devider"></div>
             </div>
            </div>
        <div class="table-responsive">
        <table class="table table-bordered mg-b-0" id="example1" style="padding-right: 15px;padding-left: 15px;">';
            
            $data .= '
            <thead>
            <tr>
                <th ></th> 
                <th>GRS ID</th>
                <th>GRS ITEM ID</th>
                <th>MRN ID</th>
            </tr>
            </thead>
            <tbody >';
            foreach ($pi_item as $item) {
                $data .= '
                <tr>
                       <td ><input type="checkbox" name="pi_item_id[]" id="pi_item_id" value="'.$item->id.'"></td>
                       <td>'.$item->grs_id.'</td>
                       <td>'.$item->grs_item_id.'</td>
                       <td>'.$item->mrn_item_id.'</td>
                      
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
                       
                        
                        <input type="hidden" name="created_at" value=" '. date('d-m-Y', strtotime($pi->created_at)). ' ">
                        <input type="hidden" name="customer_id" value="' .$pi->customer_id. '">
                        </div>
                </div>
               <br>
                <div class="row" >
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded invoice-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                Save 
                            </button>
                        </div>
                    </div>
             
             ';
        return $data;
    }
   
    public function CPIItemList(Request $request, $cpi_id)
    {
        $condition = ['fgs_cpi_item_rel.master' =>$request->cpi_id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        $items = $this->fgs_cpi_item->get_items($condition);
        //print_r($items);exit; 
       // echo $min_id;exit;
        return view('pages/FGS/Cpi/Cpi-item-list', compact('cpi_id','items'));
    }
  
}
