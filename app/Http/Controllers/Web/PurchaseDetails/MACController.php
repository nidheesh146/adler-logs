<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MACController extends Controller
{
    public function MAClist()
    {
        return view('pages.inventory.MAC.MAC-list');
    }

    public function MACAdd(Request $request)
    {
        if($request->id){
            $edit=1;
            return view('pages.inventory.MAC.MAC-Add',compact('edit'));
        }
        else
        return view('pages.inventory.MAC.MAC-Add');
    }

    public function MACAddItemInfo()
    {
        return view('pages.inventory.MAC.MAC-itemInfo');
    }
}
