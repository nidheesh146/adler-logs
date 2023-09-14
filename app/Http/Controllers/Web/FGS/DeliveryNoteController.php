<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\FGS\transaction_type;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;

use App\Models\PurchaseDetails\inv_supplier;


class DeliveryNoteController extends Controller
{
    public function __construct()
    {
        $this->transaction_type = new transaction_type;
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
        if ($request->oef_number) {
            $condition[] = ['customer_supplier.firm_name', 'like', '%' . $request->customer . '%'];
        }
        // if ($request->order_number) {
        //     $condition[] = ['fgs_oef.order_number', 'like', '%' . $request->order_number . '%'];
        // }
        if ($request->from) {
            $condition[] = ['delivery_challan.doc_date', '=', date('Y-m-d', strtotime($request->from))];
        }
        $challan_details = DB::table('delivery_challan')->select(
            'delivery_challan.*',

            'transaction_type.transaction_name',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'fgs_product_category.category_name',
            'product_stock_location.location_name'
        )
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'delivery_challan.transaction_type')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'delivery_challan.product_category')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
            ->leftjoin('product_stock_location', 'product_stock_location.id', '=', 'delivery_challan.stock_location_Increase')
            ->where($condition)
            ->distinct('delivery_challan.id')
            ->orderBy('delivery_challan.id', 'DESC')
            ->paginate(15);
        return view('pages/FGS/Delivery_challan/Challan-list', compact('challan_details'));
    }

    public function ChallanAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['customer'] = ['required'];
            $validation['ref_no'] = ['required'];
            $validation['ref_date'] = ['required', 'date'];
            //$validation['doc_no'] = ['required'];
            $validation['doc_date'] = ['required', 'date'];
            $validation['transaction_condition'] = ['required'];
            $validation['product_category'] = ['required'];
            $validation['transaction_type'] = ['required'];
            $validation['stock_location1'] = ['required'];
            $validation['stock_location2'] = ['required'];



            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
                    $years_combo = date('y', strtotime('-1 year')) . date('y');
                } else {
                    $years_combo = date('y') . date('y', strtotime('+1 year'));
                }
                $data['doc_no'] = "DC-" . $this->year_combo_num_gen(DB::table('delivery_challan')->where('delivery_challan.doc_no', 'LIKE', 'DC-' . $years_combo . '%')->count());
                $data['customer_id'] = $request->customer;
                $data['ref_no'] = $request->ref_no;
                $data['ref_date'] = date('Y-m-d', strtotime($request->ref_date));
                // $data['doc_no'] = ;
                $data['doc_date'] = date('Y-m-d', strtotime($request->doc_date));
                $data['transaction_type'] = $request->transaction_type;
                $data['product_category'] = $request->product_category;
                $data['transaction_condition'] = $request->transaction_condition;
                $data['stock_location_decrease'] = $request->stock_location1;
                $data['stock_location_increase'] = $request->stock_location2;


                $add = DB::table('delivery_challan')->insertGetId($data);
                // DB::table('delivery_challan')->where('id',$add)
                // ->update(['doc_no'=>'DC-2324-'.$add]);
                if ($add) {
                    $request->session()->flash('success', "You have successfully added a Challan !");
                    return redirect('fgs/Delivery_challan/Challan-list');
                } else {
                    $request->session()->flash('error', "Challan insertion is failed. Try again... !");
                    return redirect('pages/FGS/Delivery_challan/Challan-add');
                }
            } else {
                return redirect('fgs/OEF-add')->withErrors($validator)->withInput();
            }
        } else {
            $transaction_type = transaction_type::get();
            $category = fgs_product_category::get();
            $data['locations'] = product_stock_location::get();
            return view('pages/FGS/Delivery_challan/Challan-add', compact('transaction_type', 'category', 'data'));
        }
    }
    public function fetchStockProductBatchCardschallan(Request $request)
    {
        $dc = DB::table('delivery_challan')->where('id','=',$request->dc_id)->first();
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
        if(!$request->q){
            return response()->json(['message'=>'Product is not valid'], 500); 
        }
        $condition =[];
            $data =  $this->product->get_product_info_fgs(strtoupper($request->q));   
        
        if(!empty( $data)){
            return response()->json( $data, 200); 
        }else{
            return response()->json(['message'=>'Product is not exist'], 500); 
        }
    }
    public function fetchBatchCardQtychallan(Request $request)
    {
        $dc = DB::table('delivery_challan')->where('id','=',$request->dc_id)->first();
        $data = fgs_product_stock_management::where('batchcard_id', '=', $request->batch_id)
                        ->where('stock_location_id','=',$dc->stock_location_decrease)
                        ->first();
        return $data;
    }
    public function ChallanItemAdd(Request $request, $dc_id)
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
              
                $stkloc2=$delivery_challan->stock_location_Increase;
                $stkloc1=$delivery_challan->stock_location_decrease;
                
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
                $stk2=fgs_product_stock_management::where('product_id',$value['product'])
                ->where('batchcard_id',$batch_card_id)
                ->where('stock_location_id',$stkloc2)->first();
               
                $stk1=fgs_product_stock_management::where('product_id',$value['product'])
                ->where('batchcard_id',$batch_card_id)
                ->where('stock_location_id',$stkloc1)->first();
                
                $item_id = DB::table('delivery_challan_item')->insertGetId($data);

                DB::table('delivery_challan_item_rel')->insert([
                    'master' => $dc_id,
                    'item' => $item_id
                ]);
                
            if(!empty($stk2)){
                $q=$stk2->quantity+$qty;
                
                fgs_product_stock_management::where('id',$stk2->id)
                ->update(['quantity'=>$q]);
            }else{
                fgs_product_stock_management::insert([
                    'batchcard_id'=>$batch_card_id,
                    'product_id'=>$value['product'],
                    'quantity'=>$qty,
                    'stock_location_id'=>$stkloc2,
                    'manufacturing_date'=>date('Y-m-d', strtotime($value['manufacturing_date'])),
                    'expiry_date'=>$expiry_date
                ]);
            }
           
            if($stk1){
                $q=$stk1->quantity-$qty;
                
                fgs_product_stock_management::where('id',$stk1->id)
                ->update(['quantity'=>$q]);
            }else{
                fgs_product_stock_management::insert([
                    'batchcard_id'=>$batch_card_id,
                    'product_id'=>$value['product'],
                    'quantity'=>$qty,
                    'stock_location_id'=>$stkloc2,
                    'manufacturing_date'=>date('Y-m-d', strtotime($value['manufacturing_date'])),
                    'expiry_date'=>$expiry_date
                ]);
            }
                //$this->fgs_mrn_item->insert_data($data, $request->mrn_id);
                // $this->fgs_mrn->update_data(['id' => $request->mrn_id], $mrn_data);
                // $this->fgs_product_stock_management->insert_data($stock);
            }
            $request->session()->flash('success', "You have successfully added a MRN item !");
            return redirect('fgs/Delivery_challan/Challan-item-list/'.$dc_id);
        }
        //if ($delivery_challan->product_category == 3) {
            //return view('pages/FGS/Delivery_challan/Challan-item-add-trade', compact('delivery_challan','dc_id'));
        //} else {
            return view('pages/FGS/Delivery_challan/Challan-item-Add', compact('delivery_challan','dc_id'));
        //}

        // return view('pages/FGS/Delivery_challan/Challan-item-add-trade');

    }
    public function Challanitemlist(Request $request, $id)
    {
        
        $product_cat = DB::table('delivery_challan')
            ->where('id', $id)
            ->first();

        $condition = ['delivery_challan_item_rel.master' => $id];
        if ($request->product) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->product . '%'];
        }
        if ($request->batchnumber) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batchnumber . '%'];
        }
        // if ($request->manufaturing_from) {
        //     $condition[] = ['fgs_mrn_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->manufaturing_from))];
        //     $condition[] = ['fgs_mrn_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->manufaturing_from))];
        // }
        $items = DB::table('delivery_challan_item')
            ->select('delivery_challan_item.*', 'product_product.sku_code', 'product_product.discription', 'product_product.hsn_code', 'batchcard_batchcard.batch_no')
            ->leftjoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
            ->leftjoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'delivery_challan_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'delivery_challan_item.batchcard_id')
            ->where($condition)
            ->where('delivery_challan_item.status', 1)
            ->orderBy('delivery_challan_item.id', 'asc')
            ->get();

        return view('pages/FGS/Delivery_challan/Challan-item-list', compact('product_cat', 'items','id'));
    }
    public function challanpdf($id)
    {
        set_time_limit(300);
        $data = DB::table('delivery_challan')
        ->select(
            'delivery_challan.*',
            'transaction_type.transaction_name',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'fgs_product_category.category_name',
            'product_stock_location.location_name',
            'zone.zone_name'
        )
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'delivery_challan.transaction_type')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'delivery_challan.product_category')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
            ->leftjoin('zone','zone.id','=','customer_supplier.zone')
            ->leftjoin('product_stock_location', 'product_stock_location.id', '=', 'delivery_challan.stock_location_Increase')
        ->where('delivery_challan.id',$id)->first();

        $item= DB::table('delivery_challan_item')
        ->select('delivery_challan_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'batchcard_batchcard.batch_no','delivery_challan.doc_no')
        ->leftjoin('delivery_challan_item_rel','delivery_challan_item_rel.item','=','delivery_challan_item.id')
       // ->leftjoin('delivery_challan_item_rel','delivery_challan_item_rel.item','=','delivery_challan_item.id')
        ->leftjoin('delivery_challan','delivery_challan.id','=','delivery_challan_item_rel.master')
        ->leftjoin('product_product','product_product.id','=','delivery_challan_item.product_id')
        ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','delivery_challan_item.batchcard_id')
        ->where('delivery_challan_item_rel.master',$id)
        ->get();
//dd($data);
        $pdf = PDF::loadView('pages.FGS.Delivery_challan.Delivery_challan_pdf',compact('data','item'));
        // $pdf->set_paper('A4', 'landscape');
        $file_name = "Challan"  .$data->doc_date;
        return $pdf->stream($file_name . '.pdf');
    }
    // public function get_customer($id)
    // {
    //     $customer=inv_supplier::where('id',$id)->first();
    //     return $customer;
    // }
}
