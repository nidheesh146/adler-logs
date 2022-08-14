<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function getFinalPurchase()
    {
        return view('pages.purchase-details.purchase.final-purchase');
    }

    public function supplierInvoice()
    {
        return view('pages.purchase-details.purchase.supplier-invoice');
    }
    public function lotAllocation()
    {
        return view('pages.purchase-details.purchase.lot-allocation');
    }
}
