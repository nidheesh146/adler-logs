<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
use App\Models\User;
use App\Models\product;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_min;
use App\Models\FGS\fgs_min_item;
use App\Models\FGS\fgs_min_item_rel;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_cmin_item;
use App\Models\FGS\fgs_mrn_item_rel;
use App\Models\batchcard;
use App\Exports\FGSmintransactionExport;
use App\Exports\FGScmintransactionExport;

class MINController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->fgs_min = new fgs_min;
        $this->fgs_min_item = new fgs_min_item;
        $this->fgs_min_item_rel = new fgs_min_item_rel;
        $this->product = new product;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->production_stock_management = new production_stock_management;
        $this->User = new User;
    }
    public function MINList(Request $request)
    {
        $condition = [];
        if ($request->min_no) {
            $condition[] = ['fgs_min.min_number', 'like', '%' . $request->min_no . '%'];
        }
        if ($request->ref_number) {
            $condition[] = ['fgs_min.ref_number', 'like', '%' . $request->ref_number . '%'];
        }
        if ($request->from) {
            $condition[] = ['fgs_min.min_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_min.min_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $min = fgs_min::select('fgs_min.*', 'fgs_product_category.category_name', 'product_stock_location.location_name')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_min.product_category')
            ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_min.stock_location')
            ->where($condition)
            ->orderBy('fgs_min.id', 'DESC')
            ->paginate(15);
        return view('pages/FGS/MIN/MIN-list', compact('min'));
    }
    public function MINAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['ref_number'] = ['required'];
            $validation['ref_date'] = ['required', 'date'];
            $validation['min_date'] = ['required', 'date'];
            $validation['product_category'] = ['required'];
            $validation['stock_location'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
                    $years_combo = date('y', strtotime('-1 year')) . date('y');
                } else {
                    $years_combo = date('y') . date('y', strtotime('+1 year'));
                }
                $data['min_number'] = "MIN-" . $this->year_combo_num_gen(DB::table('fgs_min')->where('fgs_min.min_number', 'LIKE', 'MIN-' . $years_combo . '%')->count());
                $data['min_date'] = date('Y-m-d', strtotime($request->min_date));
                $data['ref_number'] = $request->ref_number;
                $data['ref_date'] = date('Y-m-d', strtotime($request->ref_date));
                $data['product_category'] = $request->product_category;
                $data['stock_location'] = $request->stock_location;
                $data['created_by'] = config('user')['user_id'];
                $data['status'] = 1;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $add = $this->fgs_min->insert_data($data);
                if ($add) {
                    $request->session()->flash('success', "You have successfully added a MIN !");
                    return redirect('fgs/MIN/item-list/' . $add);
                } else {
                    $request->session()->flash('error', "MIN insertion is failed. Try again... !");
                    return redirect('fgs/MIN-add');
                }
            } else {
                return redirect('fgs/MIN-add')->withErrors($validator)->withInput();
            }
        } else {
            $locations = product_stock_location::get();
            $category = fgs_product_category::get();
            return view('pages/FGS/MIN/MIN-add', compact('locations', 'category'));
        }
    }
    public function MINEdit(Request $request,$min_id)
    {
        if ($request->isMethod('post')) {
            $data['ref_number'] = $request->ref_number;
            $data['ref_date'] = date('Y-m-d', strtotime($request->ref_date));
            $data['product_category'] = $request->product_category;
            $data['stock_location'] = $request->stock_location; 
            $data['min_date'] = date('Y-m-d', strtotime($request->min_date));
            $add = fgs_min::where('id',$min_id)
            ->update($data);
            if ($add) {
                // $min = fgs_min::select(
                //     'product_stock_location.location_name as location_name',
                //     'product_stock_location.id as location_id',
                //     'fgs_product_category.category_name AS category_name',
                //     'fgs_product_category.id AS category_id',
                //     'fgs_min.*'
                // )
                // ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_min.stock_location')
                // ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_min.product_category')
                // ->where('fgs_min.id', $min_id)
                // ->first();
        
                // $minitem=fgs_min_item_rel::where('master',$min_id)->first();
                // $locations = product_stock_location::get();
                //     $category = fgs_product_category::get();

                $request->session()->flash('success', "You have successfully Edit MIN !");

                    return redirect('fgs/MIN/item-list/' . $min_id);

        }}else{
        // $min=fgs_min::select('product_stock_location.*','fgs_product_category.*','fgs_min.*')
        // ->leftjoin('product_stock_location','product_stock_location.id','=','fgs_min.stock_location')
        // ->leftjoin('fgs_product_category','fgs_product_category.id','=','fgs_min.product_category')
        // ->where('fgs_min.id',$min_id)->first();
        $min = fgs_min::select(
            'product_stock_location.location_name as location_name',
            'product_stock_location.id as location_id',
            'fgs_product_category.category_name AS category_name',
            'fgs_product_category.id AS category_id',
            'fgs_min.*'
        )
        ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_min.stock_location')
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_min.product_category')
        ->where('fgs_min.id', $min_id)
        ->first();

        $minitem=fgs_min_item_rel::where('master',$min_id)->first();
        $locations = product_stock_location::get();
            $category = fgs_product_category::get();
        return view('pages/FGS/MIN/MIN-edit',compact('min','locations', 'category','minitem'));
    }}
    public function fetchFGSStockProduct(Request $request)
    {
        if (!$request->q) {
            return response()->json(['message' => 'Product code is not valid'], 500);
        }
        //echo($request->q['term']);exit;
        $min = fgs_min::find($request->min_id);
        $condition[] = ['product_product.sku_code', 'like', '%' . strtoupper($request->q['term']) . '%'];
        $data  = fgs_product_stock_management::select(
            'product_product.id',
            'product_product.sku_code as text',
            'product_product.discription',
            'product_product.hsn_code',
            'product_product.is_sterile',
            'fgs_product_stock_management.quantity'
        )
            ->leftJoin('product_product', 'product_product.id', '=', 'fgs_product_stock_management.product_id')
            ->where($condition)
            ->where('fgs_product_stock_management.quantity', '!=', 0)
            ->where('fgs_product_stock_management.stock_location_id', '=', $min['stock_location'])
            ->get()->toArray();

        if (!empty($data)) {
            return response()->json($data, 200);
        } else {
            return response()->json(['message' => 'Product code is not valid'], 500);
        }
    }
    public function fetchBatchCardsFromFGSStock(Request $request)
    {
        $min = fgs_min::find($request->min_id);
        $batchcards = fgs_product_stock_management::select('batchcard_batchcard.batch_no', 'fgs_product_stock_management.quantity', 'batchcard_batchcard.id as batch_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
            ->where('fgs_product_stock_management.product_id', '=', $request->product_id)
            ->where('fgs_product_stock_management.stock_location_id', '=', $min['stock_location'])
            ->where('fgs_product_stock_management.quantity', '!=', 0)
            ->orderBy('batchcard_batchcard.id', 'DESC')
            ->get();
        return $batchcards;
    }
    public function MINitemlist(Request $request, $min_id)
    {
        $condition = ['fgs_min_item_rel.master' => $request->min_id];
        if ($request->product) {
            $condition[] = ['product_product.sku_code', 'like', '%' . $request->product . '%'];
        }
        if ($request->batchnumber) {
            $condition[] = ['batchcard_batchcard.batch_no', 'like', '%' . $request->batchnumber . '%'];
        }
        if ($request->manufaturing_from) {
            $condition[] = ['fgs_min_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->manufaturing_from))];
            $condition[] = ['fgs_min_item.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->manufaturing_from))];
        }
        // $items = fgs_min_item::select('fgs_min_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','batchcard_batchcard.batch_no','fgs_min.min_number')
        //                 ->leftjoin('fgs_min_item_rel','fgs_min_item_rel.item','=','fgs_min_item.id')
        //                 ->leftjoin('fgs_min','fgs_min.id','=','fgs_min_item_rel.master')
        //                 ->leftjoin('product_product','product_product.id','=','fgs_min_item.product_id')
        //                 ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_min_item.batchcard_id')
        //                 ->where($condition)
        //                 //->where('inv_mac.status','=',1)
        //                 ->orderBy('fgs_min_item.id','DESC')
        //                 ->distinct('fgs_min_item.id')
        //                 ->paginate(15);
        $items = $this->fgs_min_item->get_items($condition);
        //print_r($items);exit; 
        // echo $min_id;exit;
        return view('pages/FGS/MIN/MIN-item-list', compact('min_id', 'items'));
    }
    public function MINitemAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['moreItems.*.product'] = ['required'];
            $validation['moreItems.*.batch_no'] = ['required'];
            $validation['moreItems.*.qty'] = ['required'];
            $validation['moreItems.*.manufacturing_date'] = ['required', 'date'];
            // $validation['moreItems.*.expiry_date'] = ['required','date'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                $min_info = fgs_min::find($request->min_id);

                foreach ($request->moreItems as $key => $value) {
                    if ($value['expiry_date'] != 'N.A')
                        $expiry_date = date('Y-m-d', strtotime($value['expiry_date']));
                    else
                        $expiry_date = '';
                    $data = [
                        "product_id" => $value['product'],
                        "batchcard_id" => $value['batch_no'],
                        "quantity" => $value['qty'],
                        "remaining_qty_after_cancel" => $value['qty'],
                        "manufacturing_date" => date('Y-m-d', strtotime($value['manufacturing_date'])),
                        "expiry_date" =>  $expiry_date,
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                    $min_data = [
                        'remarks' => $request->remarks
                    ];
                    $this->fgs_min_item->insert_data($data, $request->min_id);
                    $this->fgs_min->update_data(['id' => $request->min_id], $min_data);
                    $fgs_product_stock = fgs_product_stock_management::where('product_id', '=', $value['product'])
                        ->where('batchcard_id', '=', $value['batch_no'])
                        ->where('stock_location_id','=',$min_info['stock_location'])
                        ->first();
                    $update_stock = $fgs_product_stock['quantity'] - $value['qty'];
                    $production_stock = $this->fgs_product_stock_management->update_data(['id' => $fgs_product_stock['id']], ['quantity' => $update_stock]);
                }
                $request->session()->flash('success', "You have successfully added a MIN item !");
                return redirect('fgs/MIN/item-list/' . $request->min_id);
            }
        } else {
            $min_id = $request->min_id;
            return view('pages/FGS/MIN/MIN-item-add', compact('min_id'));
        }
    }

    public function MINpdf($min_id)
    {
        $data['min'] = $this->fgs_min->get_single_min(['fgs_min.id' => $min_id]);
        $data['items'] = $this->fgs_min_item->getItems(['fgs_min_item_rel.master' => $min_id]);
        $pdf = PDF::loadView('pages.FGS.MIN.pdf-view', $data);
        // $pdf->set_paper('A4', 'landscape');
        $file_name = "MIN" . $data['min']['firm_name'] . "_" . $data['min']['min_date'];
        return $pdf->stream($file_name . '.pdf');
    }

   
    public function fetchBatchCardQtyManufatureDate(Request $request)
    {
        // $batchcard = batchcard::where('batchcard_batchcard.id', '=', $request->batch_id)->first();
        // $data['quantity'] = $batchcard['quantity'];
        // $fgs_mrn_item = fgs_mrn_item::where('fgs_mrn_item.batchcard_id', '=', $request->batch_id)->first();
        // $data['manufacturing_date'] = $fgs_mrn_item['manufacturing_date'];
        // $data['expiry_date'] = $fgs_mrn_item['expiry_date'];
        $min_info = fgs_min::find($request->min_id);
        $data = fgs_product_stock_management::where('batchcard_id', '=', $request->batch_id)
                        ->where('stock_location_id','=',$min_info['stock_location'])
                        ->first();
        return $data;
    }
    public function MINitemedit($min_item_id,Request $request)
    {
        $cmin_item = fgs_cmin_item::where('cmin_item_id','=',$min_item_id)->get();
        $min_rel = DB::table('fgs_min_item_rel')->where('item',$min_item_id)->first();
        $min = fgs_min::find($min_rel->master);
        //echo $min_item_id;exit;
        if(count($cmin_item)>0)
        {
            $request->session()->flash('error', "You can't edit this MIN item !");
            return redirect('fgs/MIN/item-list/' . $min_rel->master);
        }
        else
        {

            $item_details = DB::table('fgs_min_item')
                    ->select('fgs_min_item.*', 'fgs_min.id as min_id','product_product.sku_code', 'product_product.discription','product_product.id as product_id' ,'product_product.hsn_code', 'batchcard_batchcard.batch_no', 'fgs_min.min_number',
                    'product_product.is_sterile','fgs_product_stock_management.quantity as stk_qty','fgs_product_stock_management.stock_location_id')
                    ->leftjoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
                    ->leftjoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')
                    ->leftjoin('product_product', 'product_product.id', '=', 'fgs_min_item.product_id')
                    ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_min_item.batchcard_id')
                    ->leftjoin('fgs_product_stock_management','fgs_product_stock_management.batchcard_id','=','batchcard_batchcard.id')
                    ->where('fgs_min_item.id', $min_item_id)
                    ->orderBy('fgs_min_item.id', 'DESC')
                    ->first();
            //$min = fgs_min::find($request->min_id);
            $batchcards = fgs_product_stock_management::select('batchcard_batchcard.batch_no', 'fgs_product_stock_management.quantity', 'batchcard_batchcard.id as batch_id')
                ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
                ->where('fgs_product_stock_management.product_id', '=', $item_details->product_id)
                ->where('fgs_product_stock_management.stock_location_id', '=', $item_details->stock_location_id)
                ->where('fgs_product_stock_management.quantity', '!=', 0)
                ->orderBy('batchcard_batchcard.id', 'DESC')
                ->get();
            return view('pages/fgs/MIN/MIN-item-edit', compact('item_details', 'min_item_id','batchcards'));
        }
    }
    public function MINitemupdate(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $quantity = $request->quantity;
            $stock_quantity = $request->stk_qty;
            $validation['batch_no'] = ['required'];
            $validation['quantity'] = ['required'];
            //$validation['manufacturing_date'] = ['required', 'date'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $min = fgs_min::find($request->min_id);
                $min_item = fgs_min_item::find($request->min_item_id);
                $product = $request->product_id;
                $old_batch = $min_item->batchcard_id;
                $old_qty = $min_item->quantity;

                //old batch Stock updation 
                $old_stock = DB::table('fgs_product_stock_management')
                                        ->where('product_id','=',$product)
                                        ->where('batchcard_id','=',$old_batch)
                                        ->where('stock_location_id','=',$min->stock_location)
                                        ->first();
                $old_stock_update = $old_stock->quantity + $min_item->quantity; 
                $old_stock_updation = DB::table('fgs_product_stock_management')
                                                ->where('id',$old_stock->id)
                                                ->update(['quantity'=>$old_stock_update]);


                //new batch stock updation
                $new_stock = DB::table('fgs_product_stock_management')
                    ->where('product_id','=',$product)
                    ->where('batchcard_id','=',$request->batch_no)
                    ->first();
                if(!empty($new_stock))
                {
                
                    $new_stock_update = $new_stock->quantity - $request->quantity; 
                    $new_stock_updation = DB::table('fgs_product_stock_management')
                                        ->where('id',$new_stock->id)
                                        ->update([
                                            'quantity'=>$new_stock_update,
                                        ]);
                }
                // else
                // {

                //     $stock = [
                //         "product_id" => $product,
                //         "batchcard_id" => $request->batch_no,
                //         "quantity" => $request->quantity,
                //         "stock_location_id" =>$min->stock_location,
                //         'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
                //         'expiry_date' => $expiry_date,
                //         //'created_at'=>date('Y-m-d'),
                //     ];
                //     $res = $this->fgs_product_stock_management->insert_data($stock);
                // }
                $data['batchcard_id']=$request->batch_no;
                $data['quantity'] = $request->quantity;
                $data['remaining_qty_after_cancel'] = $request->quantity;
                $update_min = $this->fgs_min_item->update_data(['id'=>$min_item->id],$data);
                if($update_min)
                $request->session()->flash('success', "You have successfully updated a MIN Item !");
                else
                $request->session()->flash('error', "You have failed to update a MIN Item !");
                return redirect('fgs/MIN/item-list/' . $min->id);
            }
            else
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

        }
        
    }
    public function delete_min($min_item_id)
    {
        $prdct = DB::table('fgs_min_item')
            ->where('id', $min_item_id)
            ->first();

            $pr_id=$prdct->product_id;

        $qty = DB::table('fgs_product_stock_management')
        //->where('product_id', $prdct->product_id)
            ->where('batchcard_id', $prdct->batchcard_id)
            ->where('product_id', $pr_id)
            ->first();

        $fgs_qty = number_format($prdct->quantity);
        $pstock_qty = number_format($qty->quantity);
        //dd($fgs_qty-$pstock_qty);
        // $value=$fgs_qty-$qty;

        DB::table('fgs_product_stock_management')
        ->where('batchcard_id', $prdct->batchcard_id)
            ->where('product_id', $pr_id)
            ->where('stock_location_id', $qty->stock_location_id)
            //->where('id', $min_item_id)
            ->update([
                'quantity' => $fgs_qty + $pstock_qty
            ]);
        DB::table('fgs_min_item')
            // ->where('product_id', $prdct->product_id)
            // ->where('batchcard_id', $prdct->batchcard_id)
            ->where('id',$min_item_id)
            ->update([
                'status' => 0
            ]);
        
        return redirect()->back();
    }
    public function min_transaction(Request $request)
    {
        $condition=[];
        if($request->min_no)
        {
            $condition[] = ['fgs_min.min_number','like', '%' . $request->min_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_min_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        $items = fgs_min_item::select('fgs_min.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'fgs_min.min_number','fgs_min.min_date','fgs_min.created_at as min_wef','fgs_min_item.id as min_item_id')
            ->leftJoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
            ->leftJoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_min_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_min_item.batchcard_id')
            //->where('fgs_min_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            ->where('fgs_min_item.status',1)
            //->distinct('fgs_min_item.id')
            ->orderBy('fgs_min_item.id','desc')
            ->paginate(15);
            
        return view('pages/fgs/MIN/MIN-transaction-list',compact('items'));
    }
    public function min_transaction_export(Request $request)
    {
        $condition=[];
        if($request->min_no)
        {
            $condition[] = ['fgs_min.min_number','like', '%' . $request->min_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_min_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        $items = fgs_min_item::select('fgs_min.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'fgs_min.min_number','fgs_min.min_date','fgs_min.created_at as min_wef','fgs_min_item.id as min_item_id')
            ->leftJoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
            ->leftJoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_min_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_min_item.batchcard_id')
            //->where('fgs_min_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            ->where('fgs_min_item.status',1)
            //->distinct('fgs_min_item.id')
            ->orderBy('fgs_min_item.id','desc')
            ->get();
            return Excel::download(new FGSmintransactionExport($items), 'FGS-MIN-transaction' . date('d-m-Y') . '.xlsx');

    }
    public function cmin_transaction(Request $request)
    {
        $condition=[];
        if($request->cmin_no)
        {
            $condition[] = ['fgs_cmin.cmin_number','like', '%' . $request->cmin_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_cmin_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        $items = fgs_cmin_item::select('fgs_cmin.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'fgs_cmin.cmin_number','fgs_cmin.cmin_date','fgs_cmin.created_at as cmin_wef','fgs_cmin_item.id as cmin_item_id')
            ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
            ->leftJoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_cmin_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cmin_item.batchcard_id')
            //->where('fgs_cmin_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_cmin_item.status',1)
            //->distinct('fgs_cmin_item.id')
            ->orderBy('fgs_cmin_item.id','desc')
            ->paginate(15);
            
        return view('pages/fgs/MIN/CMIN-transaction-list',compact('items'));
    }
    public function cmin_transaction_export(Request $request)
    {
        $condition=[];
        if($request->cmin_no)
        {
            $condition[] = ['fgs_cmin.cmin_number','like', '%' . $request->cmin_no . '%']; 
        }
        
        if($request->item_code)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->item_code . '%']; 
        }
        if($request->from)
        {
            $condition[] = ['fgs_cmin_item.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
           
        }
        $items = fgs_cmin_item::select('fgs_cmin.*','product_product.sku_code','product_product.discription','product_product.hsn_code',
        'fgs_cmin.cmin_number','fgs_cmin.cmin_date','fgs_cmin.created_at as cmin_wef','fgs_cmin_item.id as cmin_item_id')
            ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
            ->leftJoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')
            ->leftjoin('product_product', 'product_product.id', '=', 'fgs_cmin_item.product_id')
            ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cmin_item.batchcard_id')
            //->where('fgs_cmin_item.batchcard_id', '=', $batch_id)
            ->where($condition)
            //->where('fgs_cmin_item.status',1)
            //->distinct('fgs_cmin_item.id')
            ->orderBy('fgs_cmin_item.id','desc')
            ->get();
            return Excel::download(new FGScmintransactionExport($items), 'FGS-cmin-transaction' . date('d-m-Y') . '.xlsx');

    }
}
