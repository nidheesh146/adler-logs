<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class InventorygstController extends Controller
{
    public function get_data()
    {
        $gst_details = DB::table('inventory_gst')
            ->orderBy('id', 'DESC')
            ->get();
        return view('pages/inventory/gst/inventory-gst', compact('gst_details'));
    }
    public function add_gst_details(Request $request)
    {
        DB::table('inventory_gst')
            ->insert(
                [

                    'igst' => $request->igst,
                    'cgst' => $request->cgst,
                    'sgst' => $request->sgst
                ]
            );
        $gst_details = DB::table('inventory_gst')
            ->orderBy('id', 'DESC')
            ->get();
        return view('pages/inventory/gst/inventory-gst', compact('gst_details'));
    }
}
