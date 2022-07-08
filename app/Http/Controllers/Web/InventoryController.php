<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Web\WebapiController;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->HttpRequest = new WebapiController;
    }

    // Purchase Reqisition Master
    public function get_purchase_reqisition(Request $request)
    {
        $Request['Method'] = 'GET';
        $Request['URL']  = config('app.ApiURL').'/inventory/purchase-requisition-master-list-add-edit-delete' . ($request->pr_id ? "?pr_id=".$request->pr_id : '');
        $data =  $this->HttpRequest->HttpClient($Request);
        print_r($data);die;
    }

    // Purchase Reqisition Master Add
    public function add_purchase_reqisition(Request $request)
    {   
die;
        $Request['Method'] = 'POST';
        $Request['Request'] = "";
        $data =  $this->HttpRequest->HttpClient($Request);

    }

  







}
