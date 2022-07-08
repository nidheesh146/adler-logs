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
        if($request->pr_id){
            $Request['GetParam'] = "?pr_id=".$request->pr_id;
        }
        $data =  $this->HttpRequest->HttpClient($Request);

    }

    // Purchase Reqisition Master Add
    public function add_purchase_reqisition(Request $request)
    {




        
        $Request['Method'] = 'POST';
        $Request['Request'] = 
        $data =  $this->HttpRequest->HttpClient($Request);

    }

  







}
