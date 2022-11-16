<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function StockToProduction()
    {
        return view('pages.inventory.stock.stock-to-production');
    }
    public function StockToProductionAdd(Request $request)
    {
        if($request->id){
            $edit=1;
            return view('pages.inventory.stock.stock-to-production-add',compact('edit'));
        }
        else
        return view('pages.inventory.stock.stock-to-production-add');
    }
    public function StockToProductionAddItem()
    {
        return view('pages.inventory.stock.stock-to-production-item');
    }

    public function StockFromProduction()
    {
        return view('pages.inventory.stock.stock-from-production');
    }
    public function StockFromProductionAdd(Request $request)
    {
        if($request->id){
            $edit=1;
            return view('pages.inventory.stock.stock-from-production-add',compact('edit'));
        }
        else
        return view('pages.inventory.stock.stock-to-production-add');
    }
    public function StockFromProductionAddItem()
    {
        return view('pages.inventory.stock.stock-from-production-item');
    }

    public function StockTransfer()
    {
        return view('pages.inventory.stock.stock-transfer');
    }
    public function StockTransferAdd(Request $request)
    {
        if($request->id){
            $edit=1;
            return view('pages.inventory.stock.stock-transfer-add',compact('edit'));
        }
        else
        return view('pages.inventory.stock.stock-transfer-add');
    }
    public function StockTransferAddItem()
    {
        return view('pages.inventory.stock.stock-transfer-item');
    }
}
