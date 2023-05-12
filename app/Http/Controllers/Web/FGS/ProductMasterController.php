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
