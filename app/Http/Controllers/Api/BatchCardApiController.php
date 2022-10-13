<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\batchcard;
use App\Models\product;
use Illuminate\Http\Request;

class BatchCardApiController extends Controller
{
    public function __construct()
    {
        $this->batchcard = new batchcard;
        $this->product = new product;
        $this->CompDetail = ["address" => "Mktd and Distributed by : Smith & Nephew
Healthcare Pvt. Ltd. B-501-509 Dynasty
Business Park, Andheri East, Mumbai-400059",
                            "email" => "complaint.india@smith-nephew.com",
                            "phone" => "+91-22-80055090",
                            "ml_no" => "MFG/MD/2021/000369",
                            "rev_no" => "LB/F-08_rev00",
                            "Rev_date" => "29 JAN 2022"];

    }
    public function search(Request $request)
    {
        if($request->batch_no){
            if(strlen($request->batch_no) < 4){
                return $this->ResponseApi([], 400, 'batch_no number contain atleast 4 characters');
            }
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%'.strtoupper($request->batch_no).'%'];
            $batchcard = $this->batchcard->get_label_filter($condition);
            $data['batchcard'] = $batchcard;
            $data['company_details'] = $this->CompDetail;
            return $this->ResponseApi($data, (!empty($batchcard[0])) ? 200 : 400 ,(!empty($batchcard[0])) ? "" : "Data is not found");
        }else if($request->sku_code){
            if(strlen($request->sku_code) < 4){
                return $this->ResponseApi([], 400, 'sku_code number contain atleast 4 characters');
            }
          
            $condition[] = ['product_product.sku_code', 'like', '%'.strtoupper($request->sku_code).'%'];
            $product =  $this->product->get_label_filter($condition);
            $data['product'] = $product;
            $data['company_details'] = $this->CompDetail;
            return $this->ResponseApi($data, (!empty($product[0])) ? 200 : 400 ,(!empty($product[0])) ? "" : "Data is not found");
        }
        return $this->ResponseApi([], 400, '');

    }

}
