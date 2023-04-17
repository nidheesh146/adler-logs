<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\PurchaseDetails\product_price_master;
use Validator;
use DB;
class PriceController extends Controller
{
    public function __construct()
    {
         $this->product = new product;
         $this->product_price_master = new product_price_master;
    }

    public function priceList(Request $request)
    {
        $prices = $this->product_price_master->get_all([]);
        return view('pages/FGS/price-master/price-master-list',compact('prices'));
    }
    public function priceAdd(Request $request,$id=null)
    {
        if ($request->isMethod('post'))
        {
            
            $validation['product'] = ['required'];
            $validation['purchase_price'] = ['required'];
            $validation['sales_price'] = ['required'];
            $validation['transfer_price'] = ['required'];
            $validation['mrp'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all()) 
            { 
                
                $data['purchase'] = $request->purchase_price;
                $data['sales'] = $request->sales_price;
                $data['transfer'] = $request->transfer_price;
                $data['mrp'] = $request->mrp;
                if($request->id){
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $data['updated_by'] = config('user')['user_id'];
                    $this->product_price_master->update_data(['id'=>$request->id],$data);
                    $request->session()->flash('success',"You have successfully updated a price master !");
                    return redirect("fgs/price-master/add/".$id);
                }else{
                    $data =  $this->product_price_master->get_single_product_price(['product_price_master.product_id'=>$request->product]); 
                    if($data)
                    {
                       $request->session()->flash('error',"Data already exist!"); 
                        return redirect("fgs/price-master/add");
                    }


                    $data['product_id'] = $request->product;
                    $data['created_by'] = config('user')['user_id'];
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $this->product_price_master->insert_data($data);
                    $request->session()->flash('success',"You have successfully added a price master !");
                    return redirect("fgs/price-master/list");
                }
            }
            if($validator->errors()->all()) 
            { 
            if($request->id)
                return redirect("fgs/price-master/add/".$id)->withErrors($validator)->withInput();
            else
                return redirect("fgs/price-master/add")->withErrors($validator)->withInput();
            }
        }
        else
        {
          if($request->id)
            {
           
             $data =  $this->product_price_master->get_single_product_price(['product_price_master.id'=>$request->id]); 

             return view('pages/FGS/price-master/price-master-add',compact('data'));
            }
           else
           return view('pages/FGS/price-master/price-master-add'); 
        }   
    }
   
    public function productsearch(Request $request)
    {
        if(!$request->q){
            return response()->json(['message'=>'Product is not valid'], 500); 
        }
        $data =  $this->product->get_product_info(strtoupper($request->q));
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Product is not exist'], 500); 
        }
    }

    public function priceMasterUpload()
    {
        
    }
}
