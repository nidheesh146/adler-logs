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
                $data['min_number'] = "MIN-".$this->po_num_gen(DB::table('fgs_min')->where('fgs_min.min_number', 'LIKE', 'MIN')->count(),1); 
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
    public function fetchFGSStockProduct(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Product code is not valid'], 500); 
        }
        //echo($request->q['term']);exit;
        $min = fgs_min::find($request->min_id);
        $condition[] = ['product_product.sku_code','like','%'.strtoupper($request->q['term']).'%'];
        $data  = fgs_product_stock_management::select('product_product.id','product_product.sku_code as text','product_product.discription',
                        'product_product.hsn_code','product_product.is_sterile','fgs_product_stock_management.quantity')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->where($condition)
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->where('fgs_product_stock_management.stock_location_id','=',$min['stock_location'])
                        ->get()->toArray();

        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Product code is not valid'], 500); 
        }
    }
    public function fetchBatchCardsFromFGSStock(Request $request)
    {
        $min = fgs_min::find($request->min_id);
        $batchcards = fgs_product_stock_management::select('batchcard_batchcard.batch_no','fgs_product_stock_management.quantity','batchcard_batchcard.id as batch_id')
                                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id')
                                        ->where('fgs_product_stock_management.product_id','=',$request->product_id)
                                        ->where('fgs_product_stock_management.stock_location_id','=',$min['stock_location'])
                                        ->where('fgs_product_stock_management.quantity','!=',0)
                                        ->get();
        return $batchcards;
    }
    public function MINitemlist(Request $request, $min_id)
    {
        $condition = ['fgs_min_item_rel.master' =>$request->min_id];
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
            $condition[] = ['fgs_min_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->manufaturing_from))];
            $condition[] = ['fgs_min_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->manufaturing_from))];
        }
        $items = fgs_min_item::select('fgs_min_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_min.min_number')
                        ->leftjoin('fgs_min_item_rel','fgs_min_item_rel.item','=','fgs_min_item.id')
                        ->leftjoin('fgs_min','fgs_min.id','=','fgs_min_item_rel.master')
                        ->leftjoin('product_product','product_product.id','=','fgs_min_item.product_id')
                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_min_item.batchcard_id')
                        ->where($condition)
                        //->where('inv_mac.status','=',1)
                        ->orderBy('fgs_min_item.id','DESC')
                        ->distinct('fgs_min_item.id')
                        ->paginate(15);
        //$items = $this->fgs_min_item->getItems(['fgs_min_item_rel.master' =>$request->min_id]);
        //print_r($items);exit; 
       // echo $min_id;exit;
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
           // $validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $min_info = fgs_min::find($request->min_id);
                
                foreach ($request->moreItems as $key => $value) {
                    if($value['expiry_date']!='N.A')
                    $expiry_date = date('Y-m-d', strtotime($value['expiry_date']));
                    else
                    $expiry_date = '';
                    $data = [
                        "product_id" => $value['product'],
                        "batchcard_id"=> $value['batch_no'],
                        "quantity" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" =>  date('Y-m-d', strtotime($value['expiry_date'])),
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                    $min_data =[
                        'remarks' => $request->remarks
                    ];
                    $this->fgs_min_item->insert_data($data,$request->min_id);
                    $this->fgs_min->update_data(['id'=>$request->min_id],$min_data);
                    $fgs_product_stock = fgs_product_stock_management::where('product_id','=',$value['product'])
                                                ->where('batchcard_id','=',$value['batch_no'])
                                                ->first();
                    $update_stock = $fgs_product_stock['quantity']-$value['qty'];
                    $production_stock = $this->fgs_product_stock_management->update_data(['id'=>$fgs_product_stock['id']],['quantity'=>$update_stock]);
                }
                $request->session()->flash('success',"You have successfully added a MIN item !");
                return redirect('fgs/MIN/item-list/'.$request->min_id);
            }
        }
        else{
            $min_id = $request->min_id;
            return view('pages/FGS/MIN/MIN-item-add',compact('min_id'));
        }
    }
}
