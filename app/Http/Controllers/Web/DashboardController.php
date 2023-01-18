<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\PurchaseDetails\inv_supplier;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->inv_supplier = new inv_supplier;
    }
    public function index()
    {
        $pendingreq=DB::table('inv_purchase_req_item_approve')->where('status','4')->count();
        $supplier=inv_supplier::where('status','1')->count() ;
        return view('pages.dashboard.dashboard',compact('pendingreq','supplier'));
    }
}
