<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebapiController;
use Illuminate\Http\Request;

class SupplierQuotationController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
    }

    public function getSupplierQuotation() 
    {
        $Request['Method'] = 'GET';
        $Request['URL'] = config('app.ApiURL') . '/inventory/supplier-quotation-master-add-edit-delete/';
        $data = $this->HttpRequest->HttpClient($Request);
        //  print_r(json_encode($data['response']['supplier_quotation']));
        //  exit;
        $items = $data['response']['supplier_quotation'];
        return view('pages/supplier-quotation/supplier-quotation', compact('items'));
    }

    public function getSupplierQuotationAdd()
    {
        return view('pages/supplier-quotation/supplier-quotation-add');
    }
    
    public function getSupplierQuotationAddItem($supplierquotationmaster_id)
    {
        return view('pages/supplier-quotation/supplier-quotation-add-item');
    }
}

