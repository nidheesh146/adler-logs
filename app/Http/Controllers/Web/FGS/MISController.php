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
use App\Models\FGS\fgs_mtq;
use App\Models\FGS\fgs_mtq_item;
use App\Models\FGS\fgs_mis;
use App\Models\FGS\fgs_mis_item;
use App\Models\FGS\fgs_mis_item_rel;
class MISController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->product = new product;
        $this->fgs_mtq = new fgs_mtq;
        $this->fgs_mtq_item = new fgs_mtq_item;
        $this->fgs_mis = new fgs_mis;
        $this->fgs_mis_item = new fgs_mis_item;
        $this->fgs_mis_item_rel = new fgs_mis_item_rel;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_qurantine_stock_management = new fgs_qurantine_stock_management;
    }
    public function MISlist(Request $request)
    {
        $condition = [];
        $condition =[];
        if($request->mis_no)
        {
            $condition[] = ['fgs_mis.mis_number','like', '%' . $request->mis_no . '%'];
        }
        if($request->mtq_no)
        {
            $condition[] = ['fgs_mtq.mtq_number','like', '%' . $request->mtq_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_mis.mis_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_mis.mis_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $mis = $this->fgs_mis->get_all_mis($condition);
        return view('pages/FGS/MIS/MIS-list',compact('mis'));
       
    }
    public function MISAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['mtq_no'] = ['required'];
            $validation['mis_date'] = ['required','date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location'] = ['required'];
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
                $data['mis_number'] = "MIS-".$this->year_combo_num_gen(DB::table('fgs_mis')->where('fgs_mis.mis_number', 'LIKE', 'MIS-'.$years_combo.'%')->count()); 
                $data['mis_date'] = date('Y-m-d', strtotime($request->mis_date));
                $data['mtq_id'] = $request->mtq_no;
                $data['product_category_id'] = $request->product_category;
                $data['stock_location_id'] = $request->stock_location;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $mis_id = $this->fgs_mis->insert_data($data);
                $condition[] = ['fgs_mtq_item_rel.master','=',$request->mtq_no];
                $mtq_item = $this->fgs_mtq_item->get_items($condition);
                foreach($mtq_item as $item)
                {
                    $mis_item['product_id'] = $item['product_id'];
                    $mis_item['miq_item_id'] = $item['id'];
                    $mis_item['created_at'] =date('Y-m-d H:i:s');
                    $mis_item_id = $this->fgs_mis_item->insert_data($mis_item,$mis_id);
                }
                if($mis_id)
                {
                    $request->session()->flash('success', "You have successfully added a MIS !");
                    return redirect('fgs/MIS/item-list/'.$add);
                }
                else
                {
                    $request->session()->flash('error', "MIS insertion is failed. Try again... !");
                    return redirect('fgs/MIS-add');
                }

            }
            else
            {
                return redirect('fgs/MIS-add')->withErrors($validator)->withInput();
            }
        }
        else
        {
            $locations = product_stock_location::get();
            $category = fgs_product_category::get();
            return view('pages/FGS/MIS/MIS-add',compact('locations','category'));
        }
       
    }
    public function findMTQNumberForMIS(Request $request)
    {
        if ($request->q) {
            $condition[] = ['fgs_mtq.mtq_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_mtq->find_mtq_num_for_mis($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mtq_details($request->id);
            exit;
        }
    }
    public function findMTQInfo(Request $request)
    {
        if ($request->q) {
            $condition[] = ['fgs_mtq.mtq_number', 'like', '%' . strtoupper($request->q) . '%'];
           
            $data = $this->fgs_mtq->find_mtq_num_for_mis($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->mtq_details($request->id,$request->category);
            exit;
        }
    }
    function mtq_details($id,$category)
    {
        $condition1[] = ['fgs_mtq.id','=',$id];
        $condition1[] = ['fgs_mtq.product_category_id','=',$category];
        $mtq = $this->fgs_mtq->get_master_data($condition1);
        //return $invoice;
        $condition2[] = ['fgs_mtq_item_rel.master','=',$id];
        $condition2[] = ['fgs_mtq.product_category_id','=',$category];
        $mtq_item = $this->fgs_mtq_item->get_items($condition2);
        if($mtq_item && $mtq)
        {
        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                MTQ (' . $mtq->mtq_number . ')
                   </label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
            
                </thead>
                <tbody>
                    <tr>
                        <th>MTQ Date</th>
                        <td>' . date('d-m-Y', strtotime($mtq->mtq_date)) . '</td>
                        <th>Referance Number</th>
                        <td>'.$mtq->ref_number.'</td>
                        
                    </tr>
                    <tr>
                        <th>Referance Date</th>
                        <td>'. date('d-m-Y', strtotime($mtq->ref_date)).'</td>
                        <th>Product Category</th>
                        <td>'.$mtq->category_name.'</td>
                        
                    </tr>
                    <tr>
                        <th>Stock Location1</th>
                        <td>'.$mtq->location_name1.'</td>
                        <th>Stock Location2</th>
                        <td>'.$mtq->location_name2.'</td>
                    </tr>
                </tbody>
           </table>
           <br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
            $data .= 'MTQ Items ';
            $data .= '</label>
               <div class="form-devider"></div>
                </div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1">';

        
            $data .= '<thead>
                   <tr>
                        <th>Product</th>
                        <th>HSN Code</th>
                        <th>Description</th>
                        <th>Batch No.</th>
                        <th>Batch Qty</th>
                        <th>UOM </th>
                        <th>DATE OF MFG. </th>
                        <th>DATE OF EXPIRY </th>
                   </tr>
               </thead>
               <tbody >';
            foreach ($mtq_item as $item) {
                $data .= '<tr>
                        <td>'.$item->sku_code.'</td>
                       <td>'.$item->hsn_code.'</td>
                       <td>'.$item->discription.'</td>
                       <td>'.$item->batch_no.'</td>
                       <td>'.$item->quantity.'</td>
                       <td>Nos</td>
                       <td>'.date('d-m-Y', strtotime($item->manufacturing_date)).'</td>
                       <td>'.date('d-m-Y', strtotime($item->expiry_date)).'</td>
                   </tr>';
            }
            $data .= '</tbody>';
        

        $data .= '</table>
       </div>';
        return $data;
        }
        // else
        // return 0;
    }
    public function MISitemlist(Request $request)
    {
        $mis_id = $request->mis_id;
        //echo $mis_id;exit;
        $mis_info = fgs_mis::find($request->mis_id);
        $mis_number = $mis_info['mis_number'];
        $condition[] = ['fgs_mis_item_rel.master','=',$request->mis_id];
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
            $condition[] = ['fgs_mtq_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_mtq_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $items = $this->fgs_mis_item->getMISItems($condition);
        return view('pages/FGS/MIS/MIS-item-list',compact('mis_id','items','mis_number'));
    }
}
