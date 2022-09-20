<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MIQController extends Controller
{
    public function MIQlist()
    {
        return view('pages.purchase-details.MIQ.MIQ-list');
    }

    public function MIQAdd()
    {
        return view('pages.purchase-details.MIQ.MIQ-add');
    }

    public function MIQAddItemInfo($id)
    {
        return view('pages.purchase-details.MIQ.MIQ-itemInfo-add');
    }
}
