<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FGSTransferController extends Controller
{
    public function fgsTransfer()
    {
        return view('pages/inventory/FGS-Transfer/fgs-transfer-add');
    }
}
