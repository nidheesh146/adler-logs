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
use App\Models\FGS\fgs_mtq_item_rel;
class MTQController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->product = new product;
        $this->fgs_mtq = new fgs_mtq;
        $this->fgs_mtq_item = new fgs_mtq_item;
        $this->fgs_mtq_item_rel = new fgs_mtq_item_rel;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_qurantine_stock_management = new fgs_qurantine_stock_management;
    }
    public function MTQlist(Request $request)
    {
        $condition = [];
        $condition =[];
        if($request->mtq_no)
        {
            $condition[] = ['fgs_mtq.mtq_number','like', '%' . $request->mtq_no . '%'];
        }
        if($request->ref_number)
        {
            $condition[] = ['fgs_mtq.ref_number','like', '%' . $request->ref_number . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_mtq.mtq_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_mtq.mtq_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $mtq = $this->fgs_mtq->get_all_mtq($condition);
        return view('pages/FGS/MTQ/MTQ-list',compact('mtq'));
       
    }
    public function MTQAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['ref_no'] = ['required'];
            $validation['ref_date'] = ['required','date'];
            $validation['mtq_date'] = ['required','date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location1'] = ['required'];
            $validation['stock_location2'] = ['required'];
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
                $data['mtq_number'] = "MTQ-".$this->year_combo_num_gen(DB::table('fgs_mtq')->where('fgs_mtq.mtq_number', 'LIKE', 'MTQ-'.$years_combo.'%')->count()); 
                $data['mtq_date'] = date('Y-m-d', strtotime($request->mtq_date));
                $data['ref_number'] = $request->ref_no;
                $data['ref_date'] = date('Y-m-d', strtotime($request->ref_date));
                $data['product_category_id'] = $request->product_category;
                $data['stock_location_id1'] = $request->stock_location1;
                $data['stock_location_id2'] = $request->stock_location2;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $add = $this->fgs_mtq->insert_data($data);
                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a MTQ !");
                    return redirect('fgs/MTQ/item-list/'.$add);
                }
                else
                {
                    $request->session()->flash('error', "MRN insertion is failed. Try again... !");
                    return redirect('fgs/MTQ-add');
                }

            }
            else
            {
                return redirect('fgs/MTQ-add')->withErrors($validator)->withInput();
            }
        }
        else
        {
            $locations = product_stock_location::get();
            $category = fgs_product_category::get();
            return view('pages/FGS/MTQ/MTQ-add',compact('locations','category'));
        }
       
    }
    public function MTQitemlist(Request $request)
    {
        $mtq_id = $request->mtq_id;
        $mtq_info = fgs_mtq::find($request->mtq_id);
        $mtq_number = $mtq_info['mtq_number'];
        $condition = ['fgs_mtq_item_rel.master' =>$request->mtq_id];
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
        $items = $this->fgs_mtq_item->getMTQItems($condition);
        return view('pages/FGS/MTQ/MTQ-item-list',compact('mtq_id','items','mtq_number'));
    }
    public function MTQitemAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['moreItems.*.product'] = ['required'];
            $validation['moreItems.*.batch_no'] = ['required'];
            $validation['moreItems.*.qty'] = ['required'];
            $validation['moreItems.*.manufacturing_date'] = ['required','date'];
            //$validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $mtq_info = fgs_mtq::find($request->mtq_id);
               // print_r($mtq_info);exit;
                foreach ($request->moreItems as $key => $value) 
                {
                    if($value['expiry_date']!='N.A')
                    $expiry_date = date('Y-m-d', strtotime($value['expiry_date']));
                    else
                    $expiry_date = '';
                    $data = [
                        "product_id" => $value['product'],
                        "batchcard_id"=> $value['batch_no'],
                        "quantity" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" => $expiry_date ,
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                    $mtq_data =[
                        'remarks' => $request->remarks
                    ];
                    $stock = [
                        "product_id" => $value['product'],
                        "batchcard_id"=> $value['batch_no'],
                        "quantity" => $value['qty'],
                        //"stock_location_id"=>$mrn_info['stock_location'],
                        "quantity" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" => $expiry_date ,
                    ];
                    $this->fgs_mtq_item->insert_data($data,$request->mtq_id);
                    $this->fgs_mtq->update_data(['id'=>$request->mtq_id],$mtq_data);
                    $qurantine_stock = fgs_qurantine_stock_management::where('product_id','=',$value['product'])->where('batchcard_id','=',$value['batch_no'])->first();
                    if($qurantine_stock)
                    {
                        $qurantine_stock_update = $qurantine_stock['quantity']+$value['qty'];
                        $this->fgs_qurantine_stock_management->update_data(['id'=>$qurantine_stock['id']],['quantity'=>$qurantine_stock_update]);
                    }
                    else
                    {
                        $this->fgs_qurantine_stock_management->insert_data($stock);
                    }
                    
                   
                    $production_stock = fgs_product_stock_management::where('product_id','=',$value['product'])
                                                ->where('batchcard_id','=',$value['batch_no'])
                                                ->where('fgs_product_stock_management.stock_location_id','=',$mtq_info['stock_location_id1'])
                                                ->first();
                    $update_stock = $production_stock['quantity']-$value['qty'];
                    $production_stock = $this->fgs_product_stock_management->update_data(['id'=>$production_stock['id']],['quantity'=>$update_stock]);              
                }
                $request->session()->flash('success',"You have successfully added a MTQ item !");
                return redirect('fgs/MTQ/item-list/'.$request->mtq_id);
            } 
            else
            {
                return redirect('fgs/MTQ/add-item/'.$request->mtq_id)->withErrors($validator)->withInput();
            }
            
        }
        else
        {
            $mtq_id = $request->mtq_id;
            return view('pages/FGS/MTQ/MTQ-item-add',compact('mtq_id'));
        }
    }
    public function fetchProductBatchCardsforMTQ(Request $request)
    {
       // echo $request->mtq_id;
        $fgs_mtq = fgs_mtq::find($request->mtq_id);
        $batchcards = fgs_product_stock_management::select('batchcard_batchcard.batch_no','fgs_product_stock_management.quantity','batchcard_batchcard.id as batch_id',
        'fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date')
                                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id')
                                        ->where('fgs_product_stock_management.product_id','=',$request->product_id)
                                        ->where('fgs_product_stock_management.stock_location_id','=',$fgs_mtq['stock_location_id1'])
                                        ->where('fgs_product_stock_management.quantity','!=',0)
                                        ->get();
        return $batchcards;
    }
    
}
