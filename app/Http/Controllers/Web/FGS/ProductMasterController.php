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
}
