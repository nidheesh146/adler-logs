<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\product;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_qurantine_stock_management;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_cmtq;
use App\Models\FGS\fgs_cmtq_item;
use App\Models\FGS\fgs_cmtq_item_rel;
use App\Models\FGS\fgs_mtq;
use App\Models\FGS\fgs_mtq_item;
use App\Models\FGS\fgs_mtq_item_rel;
use App\Models\User;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FGScmtqtransactionExport;

class CMTQController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->product = new product;
        $this->fgs_mtq = new fgs_mtq;
        $this->fgs_mtq_item = new fgs_mtq_item;
        $this->fgs_mtq_item_rel = new fgs_mtq_item_rel;
        $this->fgs_cmtq = new fgs_cmtq;
        $this->fgs_cmtq_item = new fgs_cmtq_item;
        $this->fgs_cmtq_item_rel = new fgs_cmtq_item_rel;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_qurantine_stock_management = new fgs_qurantine_stock_management;
        $this->User = new User;
    }
    public function CMTQlist(Request $request)
    {
        $condition = [];
        $condition =[];
        if($request->mtq_no)
        {
            $condition[] = ['fgs_cmtq.cmtq_number','like', '%' . $request->cmtq_no . '%'];
        }
        // if($request->ref_number)
        // {
        //     $condition[] = ['fgs_mtq.ref_number','like', '%' . $request->ref_number . '%'];
        // }
        if($request->from)
        {
            $condition[] = ['fgs_cmtq.cmtq_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_cmtq.cmtq_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $cmtq = fgs_cmtq::select('fgs_cmtq.*')
                 ->leftJoin('fgs_mtq','fgs_mtq.id','=','fgs_cmtq.mtq_id')
                        ->where($condition)
                        ->distinct('fgs_cmtq.id')
                        ->paginate(15);
        return view('pages/FGS/CMTQ/CMTQ-list',compact('cmtq'));
       
    }
    public function CMTQAdd(Request $request)
    {
        if($request->isMethod('post'))
        { 
           $validation['cmtq_date'] = ['required'];
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
                $data['cmtq_number'] = "CMTQ-".$this->year_combo_num_gen(DB::table('fgs_cmtq')->where('fgs_cmtq.cmtq_number', 'LIKE', 'Cmtq-'.$years_combo.'%')->count()); 
                $data['cmtq_date'] = date('Y-m-d', strtotime($request->cmtq_date));
                $data['mtq_id'] = $request->mtq_number;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $data['remarks'] = $request->remarks;
                $data['stock_location_id2'] = $request->stock_location_id2;
                $data['stock_location_id1'] = $request->stock_location_id1;
                $data['remarks'] = $request->remarks;
                 $fgs_mtq_data = $this->fgs_mtq->get_master_data(['fgs_mtq.id' => $data['mtq_id']]);
                 $cmtq_id = $this->fgs_cmtq->insert_data($data);

                foreach ($request->mtq_item_id as $mtq_item_id) {
                        $mtq_item =fgs_mtq_item::find($mtq_item_id);
                        $datas = [
                            "mtq_item_id" => $mtq_item_id,
                            "product_id" => $mtq_item['product_id'],
                            "batchcard_id" => $mtq_item['batchcard_id'],
                            "quantity" => $mtq_item['quantity'],
                            "created_at" => date('Y-m-d H:i:s')
                        ];

                         $this->fgs_cmtq_item->insert_data($datas,$cmtq_id);
                         $fgs_mtq_item = fgs_mtq_item::
                                        where('product_id','=',$mtq_item['product_id'])
                                        ->update(['cmtq_status' => 1]);

                        $qurantine_stock = fgs_qurantine_stock_management::where('product_id','=',$mtq_item['product_id'])
                                        ->where('batchcard_id','=',$mtq_item['batchcard_id'])
                                        ->first();
                            $update_stock = $qurantine_stock['quantity']-$mtq_item['quantity'];
                            $product_stock = $this->fgs_qurantine_stock_management->update_data(['id'=>$qurantine_stock['id']],['quantity'=>$update_stock]);



                         $fgs_product_stock = fgs_product_stock_management::where('product_id','=',$mtq_item['product_id'])
                                        ->where('batchcard_id','=',$mtq_item['batchcard_id'])
                                        ->where('stock_location_id','=',$fgs_mtq_data->stock_location_id1)
                                        ->first();
                            $update_stock = $fgs_product_stock['quantity']+$mtq_item['quantity'];
                            $production_stock = $this->fgs_product_stock_management->update_data(['id'=>$fgs_product_stock['id']],['quantity'=>$update_stock]);
                   
                if($cmtq_id)
                {
                    $request->session()->flash('success', "You have successfully added a CMTQ !");
                    return redirect('fgs/CMTQ-list');
                }
                else
                {
                    $request->session()->flash('error', "Cmtq insertion is failed. Try again... !");
                    return redirect('fgs/CMTQ/CMTQ-add');
                }

            }
        }
            if($validator->errors()->all())
                {
                    return redirect('FGS/CMTQ/CMTQ-add')->withErrors($validator)->withInput();
                }
            }
        
        else
        {
            $condition1[] = ['user.status', '=', 1];
            $data['users'] = $this->User->get_all_users($condition1);
            $locations = product_stock_location::get();
            $category = fgs_product_category::get();
            return view('pages/FGS/CMTQ/CMTQ-add',compact('locations','category','data'));
        }
       
    }
    public function CMTQitemlist(Request $request)
    {
        $cmtq_id = $request->cmtq_id;
        $cmtq_info = fgs_cmtq::find($request->cmtq_id);
        $cmtq_number = $cmtq_info['cmtq_number'];
        $condition = ['fgs_cmtq_item_rel.master' =>$request->cmtq_id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_cmtq_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_cmtq_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $items = $this->fgs_cmtq_item->getMTQItems($condition);
        return view('pages/FGS/CMTQ/CMTQ-item-list',compact('cmtq_id','items','cmtq_number'));
    }
   
    public function findMTQNumberForCMTQ(Request $request){
        if ($request->q) {
            $condition[] = ['fgs_mtq.mtq_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_mtq->find_mtq_num_for_cmtq($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mtq_details($request->id, null);
            exit;
        }
       }
        public function mtqInfo(Request $request)
       {
        if ($request->q) {
           $condition[] = ['fgs_mtq.mtq_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_mtq->find_mtq_num_for_cmtq($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mtq_details($request->id, null);
            exit;
        }
      }

      public function mtq_details($id, $active = null)
      {
        $mtq = $this->fgs_mtq->get_master_data(['fgs_mtq.id' => $id]);
       //return $invoice;
        $mtq_item = $this->fgs_mtq_item->get_mtq_item(['fgs_mtq_item_rel.master' => $id]);
        $data = '

        <div class="row" >
         
            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               mtq number (' . $mtq->mtq_number . ')
                   </label>
                <div class="form-devider"></div>
            </div>
          
            <table class="table table-bordered mg-b-0" style="padding-right: 15px;padding-left: 15px;">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <th>mtq Date</th>
                        <td>' . date('d-m-Y', strtotime($mtq->mtq_date)) . '</td>
                    </tr>
                    <tr >
                            <th>Created Date</th>
                            <td>' . date('d-m-Y', strtotime($mtq->created_at)) . '</td>
                            
                    </tr>
                    
                     
                  
                </tbody>
           </table>
        </div>
        <br>
        <div class="row" >
             <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
                $data .= 'mtq Items ';
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
                <th> BATCH NO</th>
                <th> QUANTITY</th>
                </tr>
               </thead>
               <tbody >';
               foreach ($mtq_item as $item) {
                $data .= '<tr>
                       <td ><input type="checkbox" name="mtq_item_id[]" id="mtq_item_id" value="'.$item->id.'"></td>
                       <td>'.$item->sku_code.'</td>
                       <td>'.$item->hsn_code.'</td>
                       <td>'.$item->discription.'</td>
                       <td>'.$item->batch_no.'</td>
                       <td>'.$item->quantity.'</td>
                      
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
                       
                        
                        <input type="hidden" name="created_at" value=" '. date('d-m-Y', strtotime($mtq->created_at)). ' ">
                        <input type="hidden" name="order_number" value="' .$mtq->order_number. '">
                         <input type="hidden" name="stock_location_id1" value="' .$mtq->stock_location_id1. '">
                          <input type="hidden" name="stock_location_id2" value="' .$mtq->stock_location_id2. '">
                        <input type="hidden" name="customer_id" value="' .$mtq->customer_id. '">
                        <input type="hidden" name="transaction_type" value="' .$mtq->transaction_type. '">
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
   
    public function CMTQpdf($cmtq_id)
    {
        $data['cmtq'] = $this->fgs_cmtq->get_single_cmtq(['fgs_cmtq.id' => $cmtq_id]);
        $data['items'] = $this->fgs_cmtq_item->getAllItems(['fgs_cmtq_item_rel.master' => $cmtq_id]);
        $pdf = PDF::loadView('pages.FGS.CMTQ.pdf-view', $data);
        $pdf->set_paper('A4', 'landscape');
       // $pdf->setOptions(['isPhpEnabled' => true]); 
       $pdf->setOptions(['isPhpEnabled' => true]);       
      

        $file_name = "CMTQ" . $data['cmtq']['firm_name'] . "_" . $data['cmtq']['cmtq_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    public function cmtq_transaction(Request $request)
    {
        $condition = [];
        if ($request->cmtq_no) {
            $condition[] = ['fgs_cmtq.cmtq_number', 'like', '%' . $request->cmtq_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_cmtq_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }
        $items = fgs_cmtq_item::select(
            'fgs_cmtq.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_cmtq.cmtq_number',
            'fgs_cmtq.cmtq_date',
            'fgs_cmtq.created_at as cmtq_wef',
            'fgs_cmtq_item.id as cmtq_item_id',
            'fgs_cmtq_item.quantity'
        )
            ->leftJoin('fgs_cmtq_item_rel', 'fgs_cmtq_item_rel.item', '=', 'fgs_cmtq_item.id')
            ->leftJoin('fgs_cmtq', 'fgs_cmtq.id', '=', 'fgs_cmtq_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_cmtq_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cmtq_item.batchcard_id')
            //->where('fgs_cmtq_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_cmtq_item.status',1)
            //->distinct('fgs_cmtq_item.id')
            ->orderBy('fgs_cmtq_item.id', 'desc')
            ->paginate(15);

        return view('pages/fgs/CMTQ/CMTQ-transaction-list', compact('items'));
    }
    public function cmtq_transaction_export(Request $request)
    {
        $condition = [];
        if ($request->cmtq_no) {
            $condition[] = ['fgs_cmtq.cmtq_number', 'like', '%' . $request->cmtq_no . '%'];
        }

        if ($request->item_code) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->item_code . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_cmtq_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }
        $items = fgs_cmtq_item::select(
            'fgs_cmtq.*',
            'product_product.sku_code',
            'product_product.discription',
            'product_product.hsn_code',
            'fgs_cmtq.cmtq_number',
            'fgs_cmtq.cmtq_date',
            'fgs_cmtq.created_at as cmtq_wef',
            'fgs_cmtq_item.id as cmtq_item_id',
            'fgs_cmtq_item.quantity',
            'fgs_product_category.category_name',

        )
            ->leftJoin('fgs_cmtq_item_rel', 'fgs_cmtq_item_rel.item', '=', 'fgs_cmtq_item.id')
            ->leftJoin('fgs_cmtq', 'fgs_cmtq.id', '=', 'fgs_cmtq_item_rel.master')
            ->leftJoin('fgs_mtq', 'fgs_mtq.id', '=', 'fgs_cmtq.mtq_id')

            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_cmtq_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cmtq_item.batchcard_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_mtq.product_category_id')

            //->where('fgs_cmtq_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_cmtq_item.status',1)
            //->distinct('fgs_cmtq_item.id')
            ->orderBy('fgs_cmtq_item.id', 'desc')
            ->get();

        return Excel::download(new FGScmtqtransactionExport($items), 'FGS-CMTQ-transaction' . date('d-m-Y') . '.xlsx');
    }
}   