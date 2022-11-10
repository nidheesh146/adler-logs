<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MRDController extends Controller
{
    public function MRDlist()
    {
        return view('pages.inventory.MRD.MRD-list');
    }

    public function MRDAdd(Request $request)
    {
        if($request->id){
            $edit=1;
            return view('pages.inventory.MRD.MRD-Add',compact('edit'));
        }
        else
        return view('pages.inventory.MRD.MRD-Add');
    }

    public function MRDAddItemInfo()
    {
        return view('pages.inventory.MRD.MRD-itemInfo');
    }
}
