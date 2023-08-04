<?php

namespace App\Http\Controllers\Web\PurchaseDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;

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
    // public function get_itemtype()
    // {
    //     $inv_item=DB::table('inv_item_type_2')->get();
    //     return view('pages/inventory/gst/inventory-itemtype-add',compact('inv_item'));
    // }
    public function Add_itemtype(Request $request)
    {
        // $
        // $inv_item=DB::table('inv_item_type_2')->get();
        // return view('pages/inventory/gst/inventory-itemtype-add',compact('inv_item'));
        $validator = Validator::make($request->all(), [
            'role' => ['required', 'min:1', 'max:20'],

        ]);
        if (!$validator->errors()->all()) {
            $datas['type_name'] = $request->role;

            if (!$request->id) {
                
                DB::table('inv_item_type_2')->insert($datas);
                $request->session()->flash('success', 'Type has been successfully inserted');
                return redirect("inventory/inventory-itemtype-add");
            }
            DB::table('inv_item_type_2')->where('id',$request->id)->update($datas);

            $request->session()->flash('success', 'Type has been successfully updated');
            return redirect("inventory/inventory-itemtype-add");
        }
        $data['type'] = DB::table('inv_item_type_2')->get();
        if ($request->id) {
            //$edit = $this->Role->get_role($request->role_id);
            $edit = DB::table('inv_item_type_2')->where('id', $request->id)->first();
            //print_r($edit);exit;
            return view('pages/inventory/gst/inventory-itemtype-add', compact('data', 'edit'));
        } else
            return view('pages/inventory/gst/inventory-itemtype-add', compact('data'));
    }
}

