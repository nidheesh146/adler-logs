<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LotAllocationController extends Controller
{
    public function lotAllocation()
    {
        return view('pages.lot-allocation.lot-allocation');
    }
}
