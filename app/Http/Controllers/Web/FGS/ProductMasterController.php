<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use Validator;
use DB;
class ProductMasterController extends Controller
{
    public function __construct()
    {
         $this->product = new product;
    }
    public function productList(Request $request)
    {
        $data['products'] = $this->product->get_products(['product_product.item_type'=>'FINISHED GOODS']);
        return view('pages/fgs/product-master/product-list',compact('data'));
    }
    public function productAdd(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $validation['sku_code'] = ['required'];
            $validation['description'] = ['required'];
            $validation['hsn_code'] = ['required'];
            $validation['product_group'] = ['required'];
            $validation['product_brand'] = ['required'];
            $validation['pack_size'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $data['sku_code'] = $request->sku_code; 
                $data['short_name'] = $request->short_name;
                $data['discription'] = $request->description;
                $data['hsn_code'] = $request->hsn_code;
                $data['gs1_code'] = $request->gs1_code;
                $data['product_group1_id'] = $request->product_group;
                $data['product_type_id'] = $request->product_type;
                $data['product_oem_id'] = $request->product_oem;
                $data['brand_details_id'] = $request->product_brand;
                $data['minimum_stock'] = $request->min_level;
                $data['maximum_stock'] = $request->max_level;
                $data['is_sterile'] = $request->sterile_nonsterile;
                $data['quantity_per_pack'] = $request->pack_size;
                $data['item_type'] = 'FINISHED GOODS';
                $data['created_by_id']= config('user')['user_id'];
                $data['is_active']=1;
                $data['created'] =date('Y-m-d H:i:s');
                $data['updated'] =date('Y-m-d H:i:s');
                $add = $this->product->insert_data($data);
                if($add)
                {
                    $request->session()->flash('success', "You have successfully added a product !");
                    return redirect('fgs/product-master/list');
                }
                else
                {
                    $request->session()->flash('error', "Product insertion is failed. Try again... !");
                    return redirect('fgs/product-master/add');
                }

            }
            else
            {
                return redirect('fgs/product-master/add')->withErrors($validator)->withInput();
            }
        }
        else
        {
           // $data['category'] = fgs_product_category::get();
            $data['product_oem'] = DB::table('product_oem')->get();
            $data['product_type'] = DB::table('product_type')->get();
            $data['product_group1'] = DB::table('product_group1')->get();
            $data['product_productbrand'] = DB::table('product_productbrand')->get();
            $data['product_productfamily']= DB::table('product_productfamily')->where('is_active','=',1)->get();
            $data['product_productgroup'] = DB::table('product_productgroup')->get();
            return view('pages/FGS/product-master/product-add', compact('data'));
        }
    }
}
