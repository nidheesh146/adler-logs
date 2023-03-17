<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\product;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_mrn;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_mrn_item_rel;
class MRNController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->fgs_mrn = new fgs_mrn;
        $this->fgs_mrn_item = new fgs_mrn_item;
        $this->fgs_mrn_item_rel = new fgs_mrn_item_rel;
        $this->product = new product;
        $this->fgs_product_stock = new fgs_product_stock;
    }

    public function MRNList()
    {
        $mrn = fgs_mrn::select('fgs_mrn.*','fgs_product_category.category_name','product_stock_location.location_name')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_mrn.product_category')
                        ->leftJoin('product_stock_location','product_stock_location.id','fgs_mrn.stock_location')
                        ->get();
        return view('pages/FGS/MRN/MRN-list', compact('mrn'));
    }

    public function MRNAdd(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validation['supplier_doc_number'] = ['required'];
            $validation['supplier_doc_date'] = ['required','date'];
            $validation['mrn_date'] = ['required','date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $data['mrn_number'] = "MRN-".$this->po_num_gen(DB::table('fgs_mrn')->where('fgs_mrn.mrn_number', 'LIKE', 'MRN')->count(),1); 
                $data['mrn_date'] = date('Y-m-d', strtotime($request->mrn_date));
                $data['supplier_doc_number'] = $request->supplier_doc_number;
                $data['supplier_doc_date'] = date('Y-m-d', strtotime($request->supplier_doc_date));
                $data['product_category'] = $request->product_category;
                $data['stock_location'] = $request->stock_location;
                $data['created_by']= config('user')['user_id'];
                $data['status']=1;
                $data['created_at'] =date('Y-m-d H:i:s');
                $data['updated_at'] =date('Y-m-d H:i:s');
                $add = $this->fgs_mrn->insert_data($data);
                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a MRN !");
                    return redirect('fgs/MRN/item-list/'.$add);
                }
                else
                {
                    $request->session()->flash('error', "MRN insertion is failed. Try again... !");
                    return redirect('fgs/MRN-add');
                }

            }
            else
            {
                return redirect('fgs/MRN-add')->withErrors($validator)->withInput();
            }
        }
        else
        {        
            $locations = product_stock_location::get();
            $category = fgs_product_category::get();
            return view('pages/FGS/MRN/MRN-add', compact('locations','category'));
        }
       
    }
    public function MRNitemlist(Request $request,$mrn_id)
    {
        $items = $this->fgs_mrn_item->getItems(['fgs_mrn_item_rel.master' =>$request->mrn_id]);
        return view('pages/FGS/MRN/MRN-item-list', compact('mrn_id','items'));
    }


    // function productsearch(Request $request,$sku = null){
    //     if(!$request->q){
    //         return response()->json(['message'=>'Product code is not valid'], 500); 
    //     }
    //     $condition[] = ['product_product.sku_code','like','%'.strtoupper($request->q).'%'];
    //     $data  = $this->product->get_product_mrn($condition);
    //     if(!empty( $data)){
    //         return response()->json( $data, 200); 
    //     }else{
    //         return response()->json(['message'=>'Product code is not valid'], 500); 
    //     }

    // }
    public function fetchProductBatchCards(Request $request)
    {
        $batchcards = fgs_product_stock::select('batchcard_batchcard.batch_no','fgs_product_stock.stock_qty','batchcard_batchcard.id as batch_id')
                                        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock.batchcard_id')
                                        ->where('fgs_product_stock.product_id','=',$request->product_id)
                                        ->where('fgs_product_stock.stock_qty','!=',0)
                                        ->get();
        return $batchcards;
    }

    public function MRNitemAdd(Request $request, $mrn_id)
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
                $request->session()->flash('success',"You have successfully added a MRN item !");
                return redirect('fgs/MRN/item-list/'.$request->mrn_id);
            }
        }
        else{
            return view('pages/FGS/MRN/MRN-item-add');
        }
        
    }
}
