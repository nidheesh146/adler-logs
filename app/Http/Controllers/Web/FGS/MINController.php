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
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_min;
use App\Models\FGS\fgs_min_item;
use App\Models\FGS\fgs_min_item_rel;
class MINController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->fgs_min = new fgs_min;
        $this->fgs_min_item = new fgs_min_item;
        $this->fgs_min_item_rel = new fgs_min_item_rel;
        $this->product = new product;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->production_stock_management = new production_stock_management;
    }
    public function MINList(Request $request)
    {
        $condition =[];
        if($request->min_no)
        {
            $condition[] = ['fgs_min.min_number','like', '%' . $request->min_no . '%'];
        }
        if($request->ref_number)
        {
            $condition[] = ['fgs_min.ref_number','like', '%' . $request->ref_number . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_min.min_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_min.min_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $min = fgs_min::select('fgs_min.*','fgs_product_category.category_name','product_stock_location.location_name')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_min.product_category')
                        ->leftJoin('product_stock_location','product_stock_location.id','fgs_min.stock_location')
                        ->where($condition)
                        ->paginate(15);
        return view('pages/FGS/MIN/MIN-list', compact('min'));
    }
    public function MINAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['ref_number'] = ['required'];
            $validation['ref_date'] = ['required','date'];
            $validation['min_date'] = ['required','date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $data['min_number'] = "MRN-".$this->po_num_gen(DB::table('fgs_min')->where('fgs_min.min_number', 'LIKE', 'MIN')->count(),1); 
                $data['min_date'] = date('Y-m-d', strtotime($request->min_date));
                $data['ref_number'] = $request->ref_number;
                $data['ref_date'] = date('Y-m-d', strtotime($request->ref_date));
                $data['product_category'] = $request->product_category;
                $data['stock_location'] = $request->stock_location;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $add = $this->fgs_min->insert_data($data);
                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a MIN !");
                    return redirect('fgs/MIN/item-list/'.$add);
                }
                else
                {
                    $request->session()->flash('error', "MRN insertion is failed. Try again... !");
                    return redirect('fgs/MIN-add');
                }

            }
            else
            {
                return redirect('fgs/MIN-add')->withErrors($validator)->withInput();
            }
        }
        else
        {        
            $locations = product_stock_location::get();
            $category = fgs_product_category::get();
            return view('pages/FGS/MIN/MIN-add', compact('locations','category'));
        }
       
       
    }
    public function MINitemlist(Request $request, $min_id)
    {
        $items = $this->fgs_min_item->getItems(['fgs_min_item_rel.master' =>$request->min_id]);
        return view('pages/FGS/MIN/MIN-item-list', compact('min_id','items'));
    }
    public function MINitemAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['moreItems.*.product'] = ['required'];
            $validation['moreItems.*.batch_no'] = ['required'];
            $validation['moreItems.*.qty'] = ['required'];
            $validation['moreItems.*.manufacturing_date'] = ['required','date'];
            $validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                foreach ($request->moreItems as $key => $value) {
                    $data = [
                        "product_id" => $value['product'],
                        "batchcard_id"=> $value['batch_no'],
                        "batchcard_qty" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" =>  date('Y-m-d', strtotime($value['expiry_date'])),
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                    $this->fgs_mrn_item->insert_data($data,$request->mrn_id);
                }
                $request->session()->flash('success',"You have successfully added a MIN item !");
                return redirect('fgs/MIN/item-list/'.$request->mrn_id);
            }
        }
        else{
            return view('pages/FGS/MIN/MIN-item-add');
        }
    }
}
