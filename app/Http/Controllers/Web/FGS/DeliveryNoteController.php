<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use Validator;
use DB;
use PDF;
use App\Models\FGS\transaction_type;
use App\Models\FGS\delivery_challan;
use App\Models\FGS\delivery_challan_item;
use App\Models\FGS\dc_transfer_stock;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_product_category_new;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Exports\DCstockExport;


use App\Models\PurchaseDetails\inv_supplier;


class DeliveryNoteController extends Controller
{
    public function __construct()
    {
        $this->transaction_type = new transaction_type;
        $this->Delivery_Challan = new Delivery_Challan;
        $this->delivery_challan_item = new delivery_challan_item;
        $this->dc_transfer_stock = new dc_transfer_stock;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_oef_item = new fgs_oef_item;
        $this->fgs_oef = new fgs_oef;
        $this->fgs_product_category = new fgs_product_category;
        $this->product_stock_location = new product_stock_location;
        $this->inv_supplier = new inv_supplier;
    }

    public function ChallanList(Request $request)
    {
        // $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        // echo $f->format(123456);
        //echo $this->getIndianCurrency('123456.78');
        //exit;
        $condition = [];
        if ($request->customer) {
            $condition[] = ['customer_supplier.firm_name', 'like', '%' . $request->customer . '%'];
        }
        if ($request->dc_number) {
            $condition[] = ['delivery_challan.doc_no', 'like', '%' . $request->dc_number . '%'];
        }
        if ($request->from) {
            $condition[] = ['delivery_challan.doc_date', '=', date('Y-m-d', strtotime($request->from))];
        }
       
        $challan_details = DB::table('delivery_challan')->select(
            'delivery_challan.*',

            'transaction_type.transaction_name',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_stock_location.location_name as location_decrease',
            'stock_location.location_name as location_increase'
        )
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'delivery_challan.transaction_type')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'delivery_challan.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', 'delivery_challan.new_product_category')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
            ->leftjoin('product_stock_location', 'product_stock_location.id', '=', 'delivery_challan.stock_location_decrease')
            ->leftJoin('product_stock_location as stock_location','stock_location.id','delivery_challan.stock_location_increase')
            ->where($condition)
            ->where('delivery_challan.status',1)
            ->distinct('delivery_challan.id')
            ->orderBy('delivery_challan.id', 'DESC')
            ->paginate(15);
        return view('pages/FGS/Delivery_challan/Challan-list', compact('challan_details'));
    }

    public function ChallanAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['customer'] = ['required'];
            // $validation['ref_no'] = ['required'];
            // $validation['ref_date'] = ['required', 'date'];
            $validation['oef_number'] = ['required'];
            $validation['doc_date'] = ['required', 'date'];
            // $validation['transaction_condition'] = ['required'];
            // $validation['product_category'] = ['required'];
            // $validation['transaction_type'] = ['required'];
            $validation['stock_location_decrease'] = ['required'];
            //$validation['stock_location2'] = ['required'];



            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
                    $years_combo = date('y', strtotime('-1 year')) . date('y');
                } else {
                    $years_combo = date('y') . date('y', strtotime('+1 year'));
                }
                //  dd($request->stock_location_increase);
                $data['doc_no'] = "DC-" . $this->year_combo_num_gen(DB::table('delivery_challan')->where('delivery_challan.doc_no', 'LIKE', 'DC-' . $years_combo . '%')->count());
                $data['customer_id'] = $request->customer;
                $data['ref_no'] = $request->ref_no;
                $data['oef_id'] = $request->oef_number;
                $data['ref_date'] = date('Y-m-d', strtotime($request->ref_date));
                // $data['doc_no'] = ;
                $data['remarks'] = $request->remarks;

                $data['doc_date'] = date('Y-m-d', strtotime($request->doc_date));
                $data['transaction_type'] = $request->transaction_type;
                $data['product_category'] = $request->product_category;
                $data['new_product_category'] = $request->new_product_category;
                $data['transaction_condition'] = $request->transaction_condition;
                $data['stock_location_decrease'] = $request->stock_location_decrease;
                $data['stock_location_increase'] = $request->stock_location_increase;
                if ($request->stock_location_increase == 8 || $request->stock_location_increase == 20) {
                    $data['stock_location_increase'] = $request->sub_locations;
                } else {
                    $data['stock_location_increase'] = $request->stock_location_increase;
                }
                //print_r($data);exit;
                $add = DB::table('delivery_challan')->insertGetId($data);
                // DB::table('delivery_challan')->where('id',$add)
                // ->update(['doc_no'=>'DC-2324-'.$add]);
                if ($add) {
                   // dd("ifadd");
                    $request->session()->flash('success', "You have successfully added a Challan !");
                    return redirect('fgs/Delivery_challan/Challan-list');
                } else {
                   // dd('else140');
                    $request->session()->flash('error', "Challan insertion is failed. Try again... !");
                    return redirect('pages/FGS/Delivery_challan/Challan-add');
                }
            } else {
               // dd('else145');
                return redirect('fgs/Delivery_challan/Challan-list')->withErrors($validator)->withInput();
            }
        } else {
            $transaction_type = transaction_type::get();
            $category = fgs_product_category::get();
            $product_category = fgs_product_category_new::get();
            $data['locations'] = product_stock_location::where('status',1)->get();
            //dd($data['locations']);
            return view('pages/FGS/Delivery_challan/Challan-add', compact('transaction_type', 'category', 'data','product_category'));
        }
    }
    public function ChallanDelete($id,Request $request)
    {
       // echo $grs_id;exit;
        $challan = Delivery_Challan::where('id','=',$id)->first();
        $challan_items = delivery_challan_item::leftJoin('delivery_challan_item_rel','delivery_challan_item_rel.item','=',
        'delivery_challan_item.id')
                        ->where('delivery_challan_item.status','=',1)
                        ->where('delivery_challan_item_rel.master','=',$id)->get();
        if(count($challan_items)>0)
        {
            $request->session()->flash('error', "You can't deleted this DC(".$challan->doc_no.").It have items !");
        }
        else
        {
            $update = Delivery_Challan::where('id',$id)
            ->update(['status'=>0]);
            $request->session()->flash('success', "You have successfully deleted a DC(".$challan->doc_no.") !");
        }
        return redirect('fgs/Delivery_challan/Challan-list');

    }
    public function Challanitemlist(Request $request, $dc_id)
    {
        
        $dc_master = Delivery_Challan::find($dc_id);
        $condition[] = ['fgs_oef_item_rel.master', '=', $dc_master->oef_id];
        $condition[] = ['fgs_oef_item.quantity_to_allocate', '!=', 0];
        $condition[] = ['fgs_oef_item.coef_status', '=', 0];
        $oef_items = $this->fgs_oef_item->getItems($condition);
        foreach ($oef_items as $item) {
            $product_batchcards = fgs_product_stock_management::select('fgs_product_stock_management.batchcard_id', 'batchcard_batchcard.batch_no', 'fgs_product_stock_management.quantity as batchcard_available_qty')
                ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
                ->where('fgs_product_stock_management.stock_location_id', '=', $dc_master->stock_location_decrease)
                ->where('fgs_product_stock_management.product_id', '=', $item['product_id'])
                ->where('fgs_product_stock_management.quantity', '!=', 0)
                ->get();
            if (count($product_batchcards) > 0) {
                $item['batchcards'] = $product_batchcards;
            }
        }
        $condition1[] = ['delivery_challan_item_rel.master', '=', $dc_id];
        $dc_items = $this->delivery_challan_item->getItems($condition1);
       // print_r($dc_items);exit;
        return view('pages/FGS/Delivery_challan/challan-item-list', compact('dc_id', 'oef_items', 'dc_items'));
    }

    public function ChallanitemAdd(Request $request, $dc_id, $oef_item_id)
    {
       // dd('test');
        if ($request->isMethod('post')) {
            $validation['dc_id'] = ['required'];
            $validation['batchcard'] = ['required'];
            $validation['oef_item_id'] = ['required'];
            $validation['batch_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                $oef_item = fgs_oef_item::find($request->oef_item_id);
                $data['product_id'] = $oef_item['product_id'];
                $data['oef_item_id'] = $request->oef_item_id;
                $data['mrn_item_id'] = $request->mrn_item_id;
                $data['batchcard_id'] = $request->batchcard;
                $data['batch_qty'] = $request->batch_qty;
                $data['remaining_qty_after_cancel'] = $request->batch_qty;
                $data['created_at'] = date('Y-m-d H:i:s');
                //print_r($data);exit;
                $add = $this->delivery_challan_item->insert_data($data, $request->dc_id);
                $dc_master = delivery_challan::find($request->dc_id);
                $mrn_item = fgs_mrn_item::find($request->mrn_item_id);
                $fgs_stock = fgs_product_stock_management::select('id as fgs_stock_id', 'quantity')
                    ->where('product_id', '=', $oef_item['product_id'])
                    ->where('stock_location_id', '=', $dc_master['stock_location_decrease'])
                    ->where('batchcard_id', '=', $request->batchcard)
                    ->first();
                $oef_qty_updation = $oef_item['quantity_to_allocate'] - $request->batch_qty;
                $oef_item['quantity_to_allocate'] = $oef_qty_updation;
                $oef_item['remaining_qty_after_cancel'] = $oef_qty_updation;
                $oef_item->save();

                $stock_updation = $fgs_stock['quantity'] - $request->batch_qty;
                $stock_mngment = fgs_product_stock_management::find($fgs_stock['fgs_stock_id']);
                $stock_mngment->quantity = $stock_updation;
                $stock_mngment->save();
                if ($dc_master->transaction_condition != 2) {
                    $dc_stock = dc_transfer_stock::select('id as dc_stock_id', 'quantity')
                        ->where('product_id', '=', $oef_item['product_id'])
                        ->where('batchcard_id', '=', $request->batchcard)
                        ->where('stock_location_id', '=', $dc_master->stock_location_increase)
                        ->first();
                    $fgs_stock = fgs_product_stock_management::select('id as dc_stock_id', 'quantity')
                        ->where('product_id', '=', $oef_item['product_id'])
                        ->where('batchcard_id', '=', $request->batchcard)
                        ->where('stock_location_id', '=', $dc_master->stock_location_increase)
                        ->first();
                // $stock_add = $this->dc_transfer_stock->insert_data($stock);
                    //print_r($dc_master);exit;
                    if ($dc_stock) {
                       // dd('if');
                        $dc_stock_updation = $dc_stock['quantity'] + $request->batch_qty;
                        $update = $this->dc_transfer_stock->update_data(['id' => $dc_stock['dc_stock_id']], ['quantity' => $dc_stock_updation]);
                    } else {
                       // dd('else');
                        $stock['product_id'] = $oef_item['product_id'];
                       // dd($stock);
                        $stock['batchcard_id'] = $request->batchcard;
                        $stock['stock_location_id'] = $dc_master->stock_location_increase;
                        $stock['quantity'] = $request->batch_qty;
                        //dd($stock);

                       // $stock['manufacturing_date'] = date('Y-m-d', strtotime($fgs_stock['manufacturing_date']));
                       // $stock['expiry_date'] = date('Y-m-d', strtotime($fgs_stock['expiry_date']));
                       // dd($stock);

                        $stock['created_at'] = date('Y-m-d H:i:s');
                      //  dd($stock);
                        $stock_add = $this->dc_transfer_stock->insert_data($stock);
                    }
                    if ($fgs_stock) {
                        $fgs_stock_updation = $fgs_stock['quantity'] + $request->batch_qty;
                        $update = $this->fgs_product_stock_management->update_data(['id' => $fgs_stock['dc_stock_id']], ['quantity' => $fgs_stock_updation]);
                    } else {
                       // dd('265');
                        $fgsstock['product_id'] = $oef_item['product_id'];
                        $fgsstock['batchcard_id'] = $request->batchcard;
                        $fgsstock['stock_location_id'] = $dc_master->stock_location_increase;
                        $fgsstock['quantity'] = $request->batch_qty;
                       // $fgsstock['manufacturing_date'] = date('Y-m-d', strtotime($fgs_stock['manufacturing_date']));
                       // $fgsstock['expiry_date'] = date('Y-m-d', strtotime($fgs_stock['expiry_date']));
                       /// $stock['created_at'] = date('Y-m-d H:i:s');
                        $fgs_stock_add = $this->fgs_product_stock_management->insert_data($fgsstock);
                    }
                }

                if ($add) {
                    $request->session()->flash('success', "You have successfully added a Challan Item!");
                    return redirect('fgs/Delivery_challan/Challan-item-list/' . $request->dc_id);
                } else {
                    $request->session()->flash('error', "Challan Item insertion is failed. Try again... !");
                    return redirect('fgs/Delivery_challan/' . $request->dc_id . '/add-item/' . $request->oef_item_id);
                }
            } else {
                return redirect('fgs/Delivery_challan/' . $request->dc_id . '/add-item/' . $request->oef_item_id)->withErrors($validator)->withInput();
            }
        } else {
            $dc_master = delivery_challan::find($dc_id);
            $oef_item = $this->fgs_oef_item->getSingleItem(['fgs_oef_item.id' => $oef_item_id]);

            if ($oef_item) {
                $product_batchcards = fgs_product_stock_management::select(
                    'fgs_product_stock_management.batchcard_id',
                    'batchcard_batchcard.batch_no',
                    'fgs_mrn_item.id as mrn_item_id',
                    'fgs_product_stock_management.quantity as batchcard_available_qty',
                    'fgs_product_stock_management.manufacturing_date',
                    'fgs_product_stock_management.expiry_date'
                )
                ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
                ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.batchcard_id', '=', 'batchcard_batchcard.id')
                ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                ->where('fgs_product_stock_management.stock_location_id', '=', $dc_master->stock_location_decrease)
                ->where('fgs_product_stock_management.product_id', '=', $oef_item['product_id'])
               // ->where('fgs_mrn_item.product_id', '=', $oef_item['product_id'])
               // ->where('fgs_mrn.stock_location', '=', $dc_master->stock_location_decrease)
               // ->where('fgs_mrn.product_category', '=', $dc_master['product_category'])
                ->where('fgs_product_stock_management.quantity', '>', 0)
                // ->where('batchcard_batchcard.quantity', '>', 0)
                ->orderBy('batchcard_batchcard.id', 'ASC')
                ->groupBy('fgs_product_stock_management.id')
                ->get();
                    // ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
                    // ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.batchcard_id', '=', 'batchcard_batchcard.id')
                    // ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                    // ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                    // ->where('fgs_product_stock_management.stock_location_id', '=', $dc_master->stock_location_decrease)
                    // ->where('fgs_product_stock_management.product_id', '=', $oef_item['product_id'])
                    // ->where('fgs_mrn_item.product_id', '=', $oef_item['product_id'])
                    // ->where('fgs_mrn.stock_location', '=', $dc_master->stock_location_decrease)
                    // //->where('fgs_mrn.product_category', '=', $dc_master['product_category'])
                    // ->where('fgs_product_stock_management.quantity', '>', 0)
                    // ->where('fgs_mrn_item.status',1)
                    // ->orderBy('batchcard_batchcard.id', 'ASC')
                    // ->groupBy('fgs_product_stock_management.id')
                    // ->get();
                //print_r($product_batchcards);exit;
                if (count($product_batchcards) > 0) {
                    $oef_item['batchcards'] = $product_batchcards;
                }
            }
            return view('pages/FGS/Delivery_challan/challan-item-add', compact('dc_id', 'oef_item'));
        }
    }
    public function Challanitemdelete($id, Request $request)
    {
        $dc_item = delivery_challan_item::where('id', $id)->first();

        $dc = delivery_challan::leftjoin('delivery_challan_item_rel', 'delivery_challan_item_rel.master', 'delivery_challan.id')
            ->where('delivery_challan_item_rel.item', $id)
            ->first();
        $oef_item = fgs_oef_item::find($dc_item->oef_item_id);

        $fgs_stock_dec = fgs_product_stock_management::select('id as fgs_stock_id', 'quantity')
            ->where('product_id', '=', $dc_item['product_id'])
            ->where('stock_location_id', '=', $dc['stock_location_decrease'])
            ->where('batchcard_id', '=', $dc_item['batchcard_id'])
            ->first();

        if ($fgs_stock_dec) {
            $stock_updation = $fgs_stock_dec['quantity'] + $dc_item->batch_qty;
            $stock_mngment = fgs_product_stock_management::find($fgs_stock_dec['fgs_stock_id']);
            $stock_mngment->quantity = $stock_updation;
            $stock_mngment->save();
        }
        $fgs_stock_inc = fgs_product_stock_management::select('id as fgs_stock_id', 'quantity')
            ->where('product_id', '=', $oef_item['product_id'])
            ->where('stock_location_id', '=', $dc['stock_location_increase'])
            ->where('batchcard_id', '=', $dc_item->batchcard)
            ->first();
        if ($fgs_stock_inc) {
            $stock_updation = $fgs_stock_inc['quantity'] - $dc_item->batch_qty;
            $stock_mngment = fgs_product_stock_management::find($fgs_stock_dec['fgs_stock_id']);
            $stock_mngment->quantity = $stock_updation;
            $stock_mngment->save();
        }
        $oef_qty_updation = $oef_item['quantity_to_allocate']+$dc_item->batch_qty;

        $oef_item['quantity_to_allocate'] = $oef_qty_updation;
        $oef_item['remaining_qty_after_cancel'] = $oef_item['remaining_qty_after_cancel']+$dc_item->batch_qty;
        $oef_item->save();
        $itm = delivery_challan_item::where('id', $id)
            ->update(['status' => 0]);
        // If($itm){
        $request->session()->flash('success', "You have successfully deleted a DC Item !");
        return redirect('fgs/Delivery_challan/Challan-item-list/' . $dc->master);
        // }

    }

    public function fetchStockProductBatchCardschallan(Request $request)
    {
        $dc = DB::table('delivery_challan')->where('id', '=', $request->dc_id)->first();
        $batchcards = fgs_product_stock_management::select('batchcard_batchcard.batch_no', 'fgs_product_stock_management.quantity', 'batchcard_batchcard.id as batch_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
            ->where('fgs_product_stock_management.product_id', '=', $request->product_id)
            ->where('fgs_product_stock_management.stock_location_id', '=', $dc->stock_location_decrease)
            ->where('fgs_product_stock_management.quantity', '!=', 0)
            ->orderBy('batchcard_batchcard.id', 'DESC')
            ->get();
        return $batchcards;
    }
    public function productsearch(Request $request)
    {
        if (!$request->q) {
            return response()->json(['message' => 'Product is not valid'], 500);
        }
        $condition = [];
        $data =  $this->product->get_product_info_fgs(strtoupper($request->q));

        if (!empty($data)) {
            return response()->json($data, 200);
        } else {
            return response()->json(['message' => 'Product is not exist'], 500);
        }
    }
    public function fetchBatchCardQtychallan(Request $request)
    {
        $dc = DB::table('delivery_challan')->where('id', '=', $request->dc_id)->first();
        $data = fgs_product_stock_management::where('batchcard_id', '=', $request->batch_id)
            ->where('stock_location_id', '=', $dc->stock_location_decrease)
            ->first();
        return $data;
    }
    public function ChallanItemAd1d(Request $request, $dc_id)
    {

        $delivery_challan = DB::table('delivery_challan')
            ->where('id', $dc_id)
            ->first();

        if ($request->isMethod('post')) {
            if ($delivery_challan->product_category == 3) {
                $validation['moreItems.*.product'] = ['required'];
                $validation['moreItems.*.batch_no'] = ['required'];
                $validation['moreItems.*.manufacturing_date'] = ['required', 'date'];
            } else {
                $validation['moreItems.*.product'] = ['required'];
                $validation['moreItems.*.batch_no'] = ['required'];
                $validation['moreItems.*.qty'] = ['required'];
                $validation['moreItems.*.manufacturing_date'] = ['required', 'date'];
            }
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {

                $stkloc2 = $delivery_challan->stock_location_Increase;
                $stkloc1 = $delivery_challan->stock_location_decrease;

                foreach ($request->moreItems as $key => $value) {
                    // if ($product_cat->product_category == 3) {
                    //     $batch_card_id = DB::table('batchcard_batchcard')
                    //         ->insertGetId([
                    //             "batch_no" => $value['batch_no'],
                    //             "quantity" => $value['qty'],
                    //             "is_trade" => 1
                    //         ]);
                    //     $qty = $value['qty'];
                    // } else {
                    $batch_card_id = $value['batch_no'];
                    $qty = $value['qty'];
                }

                if ($value['expiry_date'] != 'N.A')
                    $expiry_date = date('Y-m-d', strtotime($value['expiry_date']));
                else
                    $expiry_date = '';
                // if(empty($value['batch_no']))
                // {
                // $batchcard_id=$batch_card_id;
                // $qty=$request->qty;
                // }else{
                //    $batchcard_id=$value['batch_no'];
                //    $qty=$value['qty'];
                // }
                $data = [
                    "product_id" => $value['product'],
                    // "batchcard_id" => $value['batch_no'],moreItems[0][batch_no]
                    "batchcard_id" => $batch_card_id,
                    "quantity" => $qty,
                    "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                    "expiry_date" => $expiry_date,
                    "created_at" => date('Y-m-d H:i:s')
                ];
                // $data = [
                //     'remarks' => $request->remarks
                // ];
                // $stock = [
                //     "product_id" => $value['product'],
                //     // "batchcard_id" => $value['batch_no'],
                //     "batchcard_id" => $batch_card_id,
                //     "quantity" => $qty,
                //     "stock_location_id" => $mrn_info['stock_location'],
                //     "quantity" => $qty,
                //     "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                //     "expiry_date" => $expiry_date,
                // ];
                $stk2 = fgs_product_stock_management::where('product_id', $value['product'])
                    ->where('batchcard_id', $batch_card_id)
                    ->where('stock_location_id', $stkloc2)->first();

                $stk1 = fgs_product_stock_management::where('product_id', $value['product'])
                    ->where('batchcard_id', $batch_card_id)
                    ->where('stock_location_id', $stkloc1)->first();

                $item_id = DB::table('delivery_challan_item')->insertGetId($data);

                DB::table('delivery_challan_item_rel')->insert([
                    'master' => $dc_id,
                    'item' => $item_id
                ]);

                if (!empty($stk2)) {
                    $q = $stk2->quantity + $qty;

                    fgs_product_stock_management::where('id', $stk2->id)
                        ->update(['quantity' => $q]);
                } else {
                    fgs_product_stock_management::insert([
                        'batchcard_id' => $batch_card_id,
                        'product_id' => $value['product'],
                        'quantity' => $qty,
                        'stock_location_id' => $stkloc2,
                        'manufacturing_date' => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        'expiry_date' => $expiry_date
                    ]);
                }

                if ($stk1) {
                    $q = $stk1->quantity - $qty;

                    fgs_product_stock_management::where('id', $stk1->id)
                        ->update(['quantity' => $q]);
                } else {
                    fgs_product_stock_management::insert([
                        'batchcard_id' => $batch_card_id,
                        'product_id' => $value['product'],
                        'quantity' => $qty,
                        'stock_location_id' => $stkloc2,
                        'manufacturing_date' => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        'expiry_date' => $expiry_date
                    ]);
                }
                //$this->fgs_mrn_item->insert_data($data, $request->mrn_id);
                // $this->fgs_mrn->update_data(['id' => $request->mrn_id], $mrn_data);
                // $this->fgs_product_stock_management->insert_data($stock);
            }
            $request->session()->flash('success', "You have successfully added a MRN item !");
            return redirect('fgs/Delivery_challan/Challan-item-list/' . $dc_id);
        }
        //if ($delivery_challan->product_category == 3) {
        //return view('pages/FGS/Delivery_challan/Challan-item-add-trade', compact('delivery_challan','dc_id'));
        //} else {
        return view('pages/FGS/Delivery_challan/Challan-item-Add', compact('delivery_challan', 'dc_id'));
        //}

        // return view('pages/FGS/Delivery_challan/Challan-item-add-trade');

    }
    /*public function Challanitemlist(Request $request, $id)
    {
       
        $product_cat = DB::table('delivery_challan')
            ->where('id', $id)
            ->first();

        $condition = ['delivery_challan_item_rel.master' => $id];
        if ($request->product) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->product . '%'];
        }
        if ($request->batchnumber) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batchnumber . '%'];
        }
        // if ($request->manufaturing_from) {
        //     $condition[] = ['fgs_mrn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->manufaturing_from))];
        //     $condition[] = ['fgs_mrn_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->manufaturing_from))];
        // }
        $items = DB::table('delivery_challan_item')
            ->select('delivery_challan_item.*', 'fgs_item_master.sku_code', 'fgs_item_master.discription', 'fgs_item_master.hsn_code', 'batchcard_batchcard.batch_no')
            ->leftjoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftjoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'delivery_challan_item.batchcard_id')
            ->where($condition)
            ->where('delivery_challan_item.status', 1)
            ->orderBy('delivery_challan_item.id', 'asc')
            ->get();

        return view('pages/FGS/Delivery_challan/Challan-item-list', compact('product_cat', 'items','id'));
    }*/
    public function challanpdf($dc_id)
    {
        $data['dc'] = DB::table('delivery_challan')->select(
            'delivery_challan.*',
            'transaction_type.transaction_name',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'product_stock_location.location_name as location_decrease',
            'stock_location.location_name as location_increase',
            'zone.zone_name',
            'fgs_oef.oef_number',
            'fgs_oef.oef_date',
            'fgs_oef.remarks as oef_remark',
            'fgs_oef.dummy_shipping_address',
        )
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'delivery_challan.transaction_type')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'delivery_challan.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', 'delivery_challan.new_product_category')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
            ->leftjoin('zone', 'zone.id', '=', 'customer_supplier.zone')
            ->leftjoin('product_stock_location', 'product_stock_location.id', '=', 'delivery_challan.stock_location_decrease')
            ->leftJoin('product_stock_location as stock_location','stock_location.id','delivery_challan.stock_location_increase')
            ->leftjoin('fgs_oef','fgs_oef.id','=','delivery_challan.oef_id')
            ->where('delivery_challan.id',$dc_id)->first();
    
        $condition1[] = ['delivery_challan_item_rel.master', '=', $dc_id];
        $allItems = $this->delivery_challan_item->getItemspdf($condition1);
    
        $updatedItems = [];
    
        foreach ($allItems as $item) {
            if ($item->product_id == 4832 && $item->batchcard_id == 46638) {
                $conditionBatch = [
                    ['delivery_challan_item_rel.master', '=', $dc_id],
                    ['delivery_challan_item.product_id', '=', 4832],
                    ['delivery_challan_item.batchcard_id', '=', 46638],
                ];
                $specialItem = $this->delivery_challan_item->getItemspdfbatch($conditionBatch)->first();
    
                if ($specialItem) {
                    $updatedItems[] = $specialItem;
                }
            } else {
                $updatedItems[] = $item;
            }
        }
    
        $data['items'] = $updatedItems;
    
        // ✅ Add this to generate the PDF
        $pdf = PDF::loadView('pages.FGS.Delivery_challan.Delivery_challan_pdf', $data);
        $pdf->setOptions(['isPhpEnabled' => true]);
    
        $file_name = "Challan" . $data['dc']->firm_name . "_" . $data['dc']->doc_no;
        return $pdf->stream($file_name . '.pdf');
    }
    
    // public function get_customer($id)
    // {
    //     $customer=inv_supplier::where('id',$id)->first();
    //     return $customer;
    // }
    public function dc_transfer_stock(Request $request)
    { {
            $condition = [];
            if ($request->sku_code) {
                $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
            }
            if ($request->batch_no) {
                $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
            }
            $stock  = $this->dc_transfer_stock->get_stock($condition);
          //  print_r($stock);exit;
            if ($request->category_name) {
                $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
            }

            //$title ="Location1 - Stock";
             $location = 'All';
            //$condition[] = ['product_stock_location.location_name','like', '%' . 'Location-1(Std.)' . '%'];

            // $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
            $pcategory = $this->fgs_product_category->get()->unique('category_name');
            //print_r(json_encode($stock));exit;
            // return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
            return view('pages/FGS/Delivery_challan/DC-stock-management', compact('stock', 'pcategory','location'));
        }
    }
    // public function dc_transfer_stock_consignment(Request $request)


    // {
    //     $condition = [];
    //     if ($request->sku_code) {
    //         $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
    //     }
    //     if ($request->batch_no) {
    //         $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
    //     }

    //     if ($request->category_name) {
    //         $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
    //     }

    //     //$title ="Location1 - Stock";
    //      $location = 'consignment';
    //     //  $condition[] = ['product_stock_location.location_name','like', '%' . 'consignment' . '%'];
    //     $condition[] = ['dc_transfer_stock.stock_location_id', '=', 8];

    //     $stock  = $this->dc_transfer_stock->get_stock($condition);

    //     // $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
    //     $pcategory = $this->fgs_product_category->get()->unique('category_name');
    //     //print_r(json_encode($stock));exit;
    //     // return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
    //     return view('pages/FGS/Delivery_challan/DC-stock-management', compact('stock', 'pcategory','location'));
    // }
    public function dc_transfer_stock_consignment(Request $request)
{
    $condition = [];

    if ($request->sku_code) {
        $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
    }
    if ($request->batch_no) {
        $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
    }
    if ($request->customer_name) {
        $condition[] = ['customer_supplier.firm_name', 'like', '%' . $request->customer_name . '%'];
    }
    if ($request->category_name) {
        $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
    }

    $location = 'consignment';

    // ✅ Pass array of allowed location IDs
    $location_ids = [8, 21, 22, 23];

    // ✅ Pass $location_ids to model
    $stock  = $this->dc_transfer_stock->get_stock_consignment($condition, $location_ids);

    $pcategory = $this->fgs_product_category->get()->unique('category_name');

    return view('pages/FGS/Delivery_challan/DC-stock-management', compact('stock', 'pcategory', 'location'));
}



    public function dc_transfer_stock_loaner(Request $request)
    {
       // dd('loaner');
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }

        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }

        //$title ="Location1 - Stock";
         $location = 'loaner';
        //  $condition[] = ['product_stock_location.location_name','like', '%' . 'Loaner' . '%'];
        $condition[] = ['dc_transfer_stock.stock_location_id', '=', 9];

        $stock  = $this->dc_transfer_stock->get_stock($condition);

        // $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        //print_r(json_encode($stock));exit;
        // return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
        return view('pages/FGS/Delivery_challan/DC-stock-management', compact('stock', 'pcategory','location'));
    }
    public function dc_transfer_stock_scheme(Request $request)
    {
       // dd('scheme');
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }

        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }

        //$title ="Location1 - Stock";
         $location = 'Scheme';
        //  $condition[] = ['product_stock_location.location_name','like', '%' . 'Loaner' . '%'];
        $condition[] = ['dc_transfer_stock.stock_location_id', '=', 25];

        $stock  = $this->dc_transfer_stock->get_stock($condition);
     //   print_r($stock);exit;
        // $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        //print_r(json_encode($stock));exit;
        // return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
        return view('pages/FGS/Delivery_challan/DC-stock-management', compact('stock', 'pcategory','location'));
    }
    public function dc_transfer_stock_satellite(Request $request)
    {
       // dd('scheme');
       $condition = [];

       if ($request->sku_code) {
           $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
       }
       if ($request->batch_no) {
           $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
       }
       if ($request->category_name) {
           $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
       }
       
       $location = 'Satellite';
       $location_ids = [15, 16, 17, 18, 20]; // ✅ this is what you'll use in whereIn()
       
       $stock = $this->dc_transfer_stock->get_stock_scheme($condition, $location_ids);
       
       $pcategory = $this->fgs_product_category->get()->unique('category_name');
       
       return view('pages/FGS/Delivery_challan/DC-stock-management', compact('stock', 'pcategory','location'));
           }
    public function dc_transfer_stock_replacement(Request $request)
    {
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }

        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }

        //$title ="Location1 - Stock";
        $location = 'replacement';
        //  $condition[] = ['product_stock_location.location_name','like', '%' . 'replacement' . '%'];
        $condition[] = ['dc_transfer_stock.stock_location_id', '=', 12];

        $stock  = $this->dc_transfer_stock->get_stock($condition);

        // $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        //print_r(json_encode($stock));exit;
        // return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
        return view('pages/FGS/Delivery_challan/DC-stock-management', compact('stock', 'pcategory','location'));
    }
    public function dc_transfer_stock_demo(Request $request)
    {
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }

        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }

        //$title ="Location1 - Stock";
        $location = 'demo';
        //  $condition[] = ['product_stock_location.location_name','like', '%' . 'Demo' . '%'];
        $condition[] = ['dc_transfer_stock.stock_location_id', '=', 13];

        $stock  = $this->dc_transfer_stock->get_stock($condition);

        // $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        //print_r(json_encode($stock));exit;
        // return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
        return view('pages/FGS/Delivery_challan/DC-stock-management', compact('stock', 'pcategory','location'));
    }
    public function dc_transfer_stock_samples(Request $request)
    {
        $condition = [];
        if ($request->sku_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
        }
        if ($request->batch_no) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
        }

        if ($request->category_name) {
            $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
        }

        //$title ="Location1 - Stock";
         $location = 'samples';
        $condition[] = ['dc_transfer_stock.stock_location_id', '=', 14];
        $stock  = $this->dc_transfer_stock->get_stock($condition);

        // $pcondition = $this->fgs_item_master->get()->unique('is_sterile');
        $pcategory = $this->fgs_product_category->get()->unique('category_name');
        //print_r(json_encode($stock));exit;
        // return view('pages/FGS/stock-management/location1stock',compact('title','stock','location'));
        return view('pages/FGS/Delivery_challan/DC-stock-management', compact('stock', 'pcategory','location'));
    }
    public function dc_transfer_stock_export(Request $request)
    { {
            $condition = [];
            if ($request->sku_code) {
                $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
            }
            if ($request->batch_no) {
                $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
            }

            if ($request->category_name) {
                $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
            }
            $stock  = $this->dc_transfer_stock->get_stock_export($condition);

            $data = dc_transfer_stock::select(
                'fgs_item_master.sku_code',
                'fgs_item_master.discription',
                'batchcard_batchcard.batch_no',
                'dc_transfer_stock.*',
                'fgs_product_category.category_name',
                'fgs_product_category_new.category_name as new_category',
                'fgs_item_master.hsn_code',
                'product_stock_location.location_name'
            )
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'dc_transfer_stock.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'dc_transfer_stock.batchcard_id')
            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'dc_transfer_stock.stock_location_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_item_master.new_product_category_id')
            ->where($condition)
            ->whereIn('dc_transfer_stock.stock_location_id', [25, 23, 24, 22, 20, 18, 17, 16, 15, 14, 13, 12, 9, 8])
            ->where('dc_transfer_stock.quantity', '!=', 0)
            ->distinct('dc_transfer_stock.id')
            ->get();
        
            //$pcategory = $this->fgs_product_category->get()->unique('category_name');
          // print_r($data);exit;
            return Excel::download(new DCstockExport($stock), 'FGS_OFF_SHELF_STOCK_ALL_' . date('d-m-Y') . '.xlsx');
        }
    }

    public function dc_transfer_stock_export1(Request $request, $value)
    {
       // dd('hii');
        if ($value == 'samples') {

            $condition = [];
            if ($request->sku_code) {
                $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
            }
            if ($request->batch_no) {
                $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
            }

            if ($request->category_name) {
                $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
            }
            $condition[] = ['dc_transfer_stock.stock_location_id', '=', 14];
            $stock  = $this->dc_transfer_stock->get_stock_export($condition);

        } elseif ($value == 'demo') {
            $condition = [];
            if ($request->sku_code) {
                $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
            }
            if ($request->batch_no) {
                $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
            }

            if ($request->category_name) {
                $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
            }
            $condition[] = ['dc_transfer_stock.stock_location_id', '=', 13];
            $stock  = $this->dc_transfer_stock->get_stock_export($condition);

        } elseif ($value == 'replacement') {
            $condition = [];
            if ($request->sku_code) {
                $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
            }
            if ($request->batch_no) {
                $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
            }

            if ($request->category_name) {
                $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
            }
            $condition[] = ['dc_transfer_stock.stock_location_id', '=', 12];
            $stock  = $this->dc_transfer_stock->get_stock_export($condition);

        } elseif ($value == 'loaner') {
            $condition = [];
            if ($request->sku_code) {
                $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
            }
            if ($request->batch_no) {
                $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
            }

            if ($request->category_name) {
                $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
            }
            $condition[] = ['dc_transfer_stock.stock_location_id', '=', 9];
            $stock  = $this->dc_transfer_stock->get_stock_export($condition);

        } elseif ($value == 'consignment') {
            $condition = [];
            if ($request->sku_code) {
                $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
            }
            if ($request->batch_no) {
                $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
            }

            if ($request->category_name) {
                $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
            }
            $location_ids = [8, 21, 22, 23];
            //  dd($condition);
            $stock  = $this->dc_transfer_stock->get_stock_consignment_report($condition,$location_ids);

        }
        elseif ($value == 'satellite') {
            $condition = [];
            if ($request->sku_code) {
                $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->sku_code . '%'];
            }
            if ($request->batch_no) {
                $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batch_no . '%'];
            }

            if ($request->category_name) {
                $condition[] = ['fgs_product_category.category_name', 'like', '%' . $request->category_name . '%'];
            }
          //  $condition[] = ['dc_transfer_stock.stock_location_id','=',18];
            $stock  = $this->dc_transfer_stock->get_stock_satellite_export($condition);
           // print_r($stock);exit;

        }
        $data = dc_transfer_stock::select('fgs_item_master.sku_code','fgs_item_master.discription', 'batchcard_batchcard.batch_no', 'dc_transfer_stock.*', 'fgs_product_category.category_name', 'fgs_item_master.hsn_code', 'product_stock_location.location_name')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'dc_transfer_stock.product_id')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'dc_transfer_stock.batchcard_id')
            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'dc_transfer_stock.stock_location_id')
            // ->leftJoin('product_type','product_type.id','=','fgs_item_master.product_type_id')
            // ->leftJoin('product_group1','product_group1.id','=','fgs_item_master.product_group1_id')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_item_master.product_category_id')
            // ->leftJoin('product_oem','product_oem.id','=','fgs_item_master.product_oem_id')
            ->where($condition)
            ->where('dc_transfer_stock.quantity', '!=', 0)
            ->distinct('dc_transfer_stock.id')
            //->orderBy('dc_transfer_stock.id','DESC')
            ->get();
       // print_r($stock);exit;
$filename = 'FGS_OFF_SHELF_STOCK_' . strtoupper($value) . '_(' . date('d-m-Y') . ').xlsx';

return Excel::download(new DCstockExport($stock), $filename);
    }

    public function OEFInfo(Request $request){
        $oef = $this->fgs_oef->get_master_data(['fgs_oef.id' => $request->oef_id]);
        return $oef;
    }
    public function get_batch($dc_id,$oef_item_id)
    {
        $dc_master = delivery_challan::find($dc_id);
        $oef_item = $this->fgs_oef_item->getSingleItem(['fgs_oef_item.id' => $oef_item_id]);
       
        if ($oef_item) {
            $product_batchcards = fgs_product_stock_management::select(
                'fgs_product_stock_management.batchcard_id',
                'batchcard_batchcard.batch_no',
                'fgs_mrn_item.id as mrn_item_id',
                'fgs_product_stock_management.quantity as batchcard_available_qty',
                'fgs_mrn_item.manufacturing_date',
                'fgs_mrn_item.expiry_date'
            )
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
            ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.batchcard_id', '=', 'batchcard_batchcard.id')
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            ->where('fgs_product_stock_management.stock_location_id', '=', $dc_master->stock_location_decrease)
            ->where('fgs_product_stock_management.product_id', '=', $oef_item['product_id'])
            //->where('fgs_mrn_item.product_id', '=', $oef_item['product_id'])
            ->where('fgs_mrn.stock_location', '=', $dc_master->stock_location_decrease)
            ->where('fgs_mrn.product_category', '=', $dc_master['product_category'])
            ->where('fgs_product_stock_management.quantity', '>', 0)
            ->orderBy('batchcard_batchcard.id', 'ASC')
            ->orderBy('fgs_mrn_item.id', 'ASC')
            ->groupBy('fgs_product_stock_management.id')
            ->get();
            // print_r($product_batchcards);exit;
            if (count($product_batchcards) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}
