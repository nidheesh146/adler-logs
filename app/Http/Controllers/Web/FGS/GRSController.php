<?php

namespace App\Http\Controllers\Web\fgs;
use Validator;
use DB;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_maa_stock_management;
use App\Models\FGS\production_stock_management;
use App\Models\FGS\dc_transfer_stock;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\fgs_product_category_new;
use App\Models\FGS\fgs_oef;
use App\Models\FGS\fgs_oef_item;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_grs_item_rel;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_pi_item;
use Carbon\Carbon;           // ← make sure this is here
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendingGRSExport;
use App\Exports\FGSgrstransactionExport;
use App\Models\FGS\transaction_type;

class GRSController extends Controller
{
    public function __construct()
    {
        $this->product_stock_location = new product_stock_location;
        $this->fgs_product_category = new fgs_product_category;
        $this->product = new product;
        $this->fgs_oef = new fgs_oef;
        $this->fgs_oef_item = new fgs_oef_item;
        $this->fgs_mrn_item = new fgs_mrn_item;
        $this->fgs_grs = new fgs_grs;
        $this->fgs_grs_item = new fgs_grs_item;
        $this->fgs_grs_item_rel = new fgs_grs_item_rel;
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->fgs_maa_stock_management = new fgs_maa_stock_management;
        $this->production_stock_management = new production_stock_management;
    }

    public function GRSList(Request $request)
    {
        $condition = [];
        if($request->grs_no)
        {
            $condition[] = ['fgs_grs.grs_number','like', '%' . $request->grs_no . '%'];
        }
        if($request->oef_no)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_no . '%'];
        }
        if($request->customer_no)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_grs.grs_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $grs = $this->fgs_grs->get_all_grs($condition);
        return view('pages/FGS/GRS/GRS-list',compact('grs'));
    }
    public function GRSAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validation rules
            $validation = [
                'oef_number' => ['required'],
                'customer' => ['required'],
                'grs_date' => ['required', 'date'],
                'product_category' => ['required'],
                'subdivision' => ['required_if:stock_location1,8,20'],
                'stock_location2' => ['required']
            ];
    
            $validator = Validator::make($request->all(), $validation);
    
            if (!$validator->fails()) {
                // Determine effective location_id (use subdivision if stock_location1 is 8 or 20)
                $location_id = $request->stock_location1;
    
                if (in_array((int)$location_id, [8, 20]) && !empty($request->subdivision)) {
                    $location_id = $request->subdivision;
                }
    
                // Check if product exists at the chosen location
                $exist = $this->product_exist_current_location($request->oef_number, $location_id);
                // dd([
                //     'oef_number' => $request->oef_number,
                //     'stock_location1' => $request->stock_location1,
                //     'exists_result' => $exist
                // ]);
                
                if ($exist == 0) {
                    $request->session()->flash('error', "OEF items do not exist at the current location. Try again with another location... !");
                    return redirect('fgs/GRS-add')->withInput();
                }
    
                // Get OEF record
                $oef = DB::table('fgs_oef')->where('id', $request->oef_number)->first();
                if (!$oef) {
                    $request->session()->flash('error', "Invalid OEF Number selected.");
                    return redirect('fgs/GRS-add')->withInput();
                }
    
                // Transaction type handling
                $transactionType = transaction_type::where('transaction_name', $request->stock_location2)->first();
                $transactionTypeId = $transactionType ? $transactionType->id : 4;
    
                // Generate financial year code
                $years_combo = date('m') >= 4
                    ? date('y') . date('y', strtotime('+1 year'))
                    : date('y', strtotime('-1 year')) . date('y');
    
                $grsCount = DB::table('fgs_grs')
                    ->where('fgs_grs.grs_number', 'LIKE', 'GRS-' . $years_combo . '%')
                    ->count();
    
                // Prepare GRS insert data
                $data = [
                    'grs_number' => "GRS-" . $this->year_combo_num_gen($grsCount),
                    'grs_date' => date('Y-m-d', strtotime($request->grs_date)),
                    'oef_id' => $request->oef_number,
                    'customer_id' => $request->customer,
                    'product_category' => $oef->product_category,
                    'new_product_category' => $oef->new_product_category,
                    'stock_location1' => $location_id, // Use either main or subdivision
                    'stock_location2' => $transactionTypeId,
                    'created_by' => config('user')['user_id'],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
    
                // Insert into database
                $add = $this->fgs_grs->insert_data($data);
    
                if ($add) {
                    $request->session()->flash('success', "You have successfully added a GRS!");
                    return redirect('fgs/GRS/item-list/' . $add);
                } else {
                    $request->session()->flash('error', "GRS insertion failed. Try again... !");
                    return redirect('fgs/GRS-add')->withInput();
                }
            }
    
            // On validation failure
            return redirect('fgs/GRS-add')->withErrors($validator)->withInput();
    
        } else {
            // GET method: Load form
            $data['locations'] = product_stock_location::where('status', 1)
                ->whereNotIn('id', [12, 13, 14])->get();
              //  dd($data['locations']);
            $data['category'] = fgs_product_category::get();
            $data['oef_numbers'] = fgs_oef::all();
            $data['product_category'] = fgs_product_category_new::get();
    
            return view('pages/FGS/GRS/GRS-add', compact('data'));
        }
    }
    
    public function fetchBusinessCategory(Request $request)
{
    $oefNumber = $request->oef_number;

    // Fetch the GRS based on OEF number
    $grs = DB::table('fgs_oef')->where('id', $oefNumber)->first();

    if ($grs) {
        // Fetch the business category
        $productCategory = fgs_product_category::where('id', $grs->product_category)->first();

        return response()->json([
            'category_name' => $productCategory ? $productCategory->category_name : '',
        ]);
    }

    return response()->json(['category_name' => '']);
}
public function fetchProductCategory(Request $request)
{
    $oefNumber = $request->oef_number;

    $grs = DB::table('fgs_oef')->where('id', $oefNumber)->first();

    if ($grs) {
        $newProductCategory = fgs_product_category_new::find($grs->new_product_category);
        $transactionType = transaction_type::find($grs->transaction_type);

        return response()->json([
            'new_product_category_name' => $newProductCategory->category_name ?? '',
            'transaction_type_id'       => $transactionType->id ?? '',
            'transaction_type_name'     => $transactionType->transaction_name ?? '',
        ]);
    }

    return response()->json([
        'new_product_category_name' => '',
        'transaction_type_id'       => '',
        'transaction_type_name'     => '',
    ]);
}

    function product_exist_current_location($oef_id, $stock_location)
    {
        $condition[] = ['fgs_oef_item_rel.master','=', $oef_id];
        $condition[] = ['fgs_oef_item.quantity_to_allocate','!=',0];
        $condition[] = ['fgs_oef_item.coef_status','=',0]; 
        $condition[] = ['fgs_oef_item.status','=',1];
        $oef_items = $this->fgs_oef_item->getAllItems($condition);
       // echo $stock_location;exit;
       // print_r(json_encode($oef_items));exit;
        $i=0;
        foreach($oef_items as $item)
        {
            $is_exist= fgs_product_stock_management::where('product_id','=',$item['product_id'])
                                ->where('stock_location_id','=',$stock_location)
                                ->where('quantity','!=',0)
                                ->exists();
     
            if($is_exist)
            $i++;
        }
      
        if($i==0)
        return 0;
        else 
        return 1;

    }
    public function findOEFforGRS(Request $request)
    {
        // if ($request->q) {
        //     $condition[] = ['fgs_oef.oef_number', 'like', '%' . strtoupper($request->q) . '%'];
           
        //     $data = $this->fgs_oef->find_oef_num_for_grs($condition);
        //     if (!empty($data[0])) {
        //         return response()->json($data, 200);
        //     } else {
        //         return response()->json(['message' => 'item code is not valid'], 500);
        //     }
        // } else {
        //     echo $this->OEF_details($request->id);
        //     exit;
        // }
        // dd($request->customer_id);
        if($request->customer_id)
        {
            $condition[] = ['fgs_oef.customer_id','=', $request->customer_id];
            $data = $this->fgs_oef->find_oef_num_for_grs($condition);
            if($data)
            return $data;
            else
            return 0;
        }
    }

    public function findOEFInfo(Request $request)
    {
        if ($request->q) {
            $condition[] = ['fgs_oef.oef_number', 'like', '%' . strtoupper($request->q) . '%'];    
            $data = $this->fgs_oef->find_oef_num_for_grs($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->OEF_details($request->id);
            exit;
        }
    }
    public function OEF_details($id)
    {
        $oef = $this->fgs_oef->get_single_oef(['fgs_oef.id'=>$id]);
        $condition[] = ['fgs_oef_item_rel.master','=', $id];
        $condition[] = ['fgs_oef_item.quantity_to_allocate','!=',0];  
        $condition[] = ['fgs_oef_item.coef_status','=',0];          
        $oef_items = $this->fgs_oef_item->getAllItems($condition);
        if($oef->billing_address && $oef->shipping_address){
            $shipping=$oef->shipping_address;
            $billing=$oef->billing_address;
            }else{
            $shipping=$oef->dummy_shipping_address;
            $billing=$oef->dummy_billing_address;
            }
        $data = '<div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               Order Execution Form(OEF) (' . $oef->oef_number . ')
                   </label>
               <div class="form-devider"></div>
           </div>
           </div>
           <table class="table table-bordered mg-b-0">
                <thead>
            
                </thead>
                <tbody>
                    <tr>
                        <th>OEF Date</th>
                        <td>' . date('d-m-Y', strtotime($oef->oef_date)) . '</td>
                        <th>Due Date</th>
                        <td>' . date('d-m-Y', strtotime($oef->due_date)) . '</td>
                    </tr>
                    <tr>
                        <th>Customer </th>
                        <td>'.$oef->firm_name.'</td>
                        <th>Contact Person & Designation</th>
                        <td>'.$oef->contact_person.'<br/>
                        '.$oef->designation.'</td>
                    </tr>
                    <tr>
                        <th>Contact Number </th>
                        <td>'.$oef->contact_number.'</td>
                        <th>Email ID</th>
                        <td>'.$oef->email.'</td>
                    </tr>
                    <tr>
                        <th>Shipping Address</th>
                        <td>' . $shipping. '</td>
                        <th>Billing Address</th>
                        <td>' . $billing . '</td>
                    </tr>
                    <tr>
                        <th>Order Number</th>
                        <td>'.$oef->order_number.'</td>
                        <th>Order Date</th>
                        <td>'.date('d-m-Y', strtotime($oef->order_date)).'</td>
                    </tr>
                    <tr>
                    <tr>
                        <th>Sales Type</th>
                        <td>'.$oef->sales_type.'</td>
                        <th>Transaction Type</th>
                        <td>'.$oef->transaction_name.'</td>
                    </tr>
                    <tr>
                        <th>Order FulFil</th>
                        <td>'.$oef->order_fulfil_type.'</td>
                        <th>Remarks</th>
                        <td>'.$oef->remarks.'</td>
                    </tr>
                    </tr>
                </tbody>
           </table>
           <br>
           <div class="row">
           <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
       
            $data .= 'OEF Items ';
            $data .= '</label>
               <div class="form-devider"></div>
                </div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1">';

        
            $data .= '<thead>
                   <tr>
                   <th>Product</th>
                   <th>Descriptio</th>
                   <th>Quantity</th>
                   <th>Rate</th>
                   <th>Discount</th>
                   <th>GST </th>           
                   </tr>
               </thead>
               <tbody >';
            foreach ($oef_items as $item) {
                $data .= '<tr>
                        <td>'.$item->sku_code.'</td>
                       <td>'.$item->discription.'</td>
                       <td>'.$item->quantity_to_allocate.'Nos</td>
                       <td>'.$item->rate.'  '.$oef->currency_code. '</td>
                       <td>'.$item->discount.'%</td>
                       <td>IGST:'.$item->igst.'% ,
                            SGST:'.$item->sgst.'%,
                            CGST:'.$item->cgst.'%<br/>
                       </td>
                       
                   </tr>';
            }
            $data .= '</tbody>';
        

        $data .= '</table>
       </div>';
        return $data;
    }

    public function GRSitemlist(Request $request, $grs_id)
    {
        $grs_master = fgs_grs::find($grs_id);
        $condition[] = ['fgs_oef_item_rel.master','=', $grs_master->oef_id];
        $condition[] = ['fgs_oef_item.quantity_to_allocate','!=',0];
        $condition[] = ['fgs_oef_item.coef_status','=',0];             
        $oef_items = $this->fgs_oef_item->getItems($condition);
        foreach($oef_items as $item)
        {
            $product_batchcards = fgs_product_stock_management::select('fgs_product_stock_management.batchcard_id','batchcard_batchcard.batch_no','fgs_product_stock_management.quantity as batchcard_available_qty')
                                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=', 'fgs_product_stock_management.batchcard_id')
                                    ->where('fgs_product_stock_management.stock_location_id','=',$grs_master->stock_location1)
                                    ->where('fgs_product_stock_management.product_id','=',$item['product_id'])
                                    ->where('fgs_product_stock_management.quantity','!=',0)
                                    ->get();
            if(count($product_batchcards)>0)
            {
                $item['batchcards'] = $product_batchcards;
            }
           
        }
        $condition1[] = ['fgs_grs_item_rel.master','=', $grs_id];
        $grs_items = $this->fgs_grs_item->getItems($condition1);
       // print_r($grs_items);exit;
        return view('pages/FGS/GRS/GRS-item-list', compact('grs_id','oef_items','grs_items'));
    }
   public function GRSitemAdd(Request $request, $grs_id, $oef_item_id)
    {
       // dd('testing');
        if ($request->isMethod('post')) {
            // 1) validation
            $validator = Validator::make($request->all(), [
                'grs_id'      => ['required'],
                'batchcard'   => ['required'],
                'oef_item_id' => ['required'],
                'batch_qty'   => ['required'],
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // 2) one timestamp for both check & insert
            $now = Carbon::now()->toDateTimeString();

            // 3) duplicate check including created_at
            $already = $this->fgs_grs_item
              //  ->where('grs_id',       $request->grs_id)
                ->where('oef_item_id',  $request->oef_item_id)
                ->where('batchcard_id', $request->batchcard)
                ->where('created_at',   $now)
                ->first();

            if ($already) {
              //  dd('already exists');
                $request->session()->flash(
                    'warning',
                    "You already added this item at {$now}"
                );
                return redirect()->back()->withInput();
            }

            // 4) build payload
            $oef_item = fgs_oef_item::findOrFail($request->oef_item_id);
            $data = [
                'product_id'                 => $oef_item->product_id,
                'oef_item_id'                => $request->oef_item_id,
                'mrn_item_id'                => $request->mrn_item_id,
                'batchcard_id'               => $request->batchcard,
                'batch_quantity'             => $request->batch_qty,
                'remaining_qty_after_cancel' => $request->batch_qty,
                'qty_to_invoice'             => $request->batch_qty,
                'created_at'                 => $now,
            ];
           //print_r($data);
            // 5) insert & stock logic
            $add = $this->fgs_grs_item->insert_data($data, $request->grs_id);

            $grs_master = fgs_grs::findOrFail($request->grs_id);
            $fgs_stock  = fgs_product_stock_management::select('id as fgs_stock_id', 'quantity')
                ->where('product_id',       $oef_item->product_id)
                ->where('stock_location_id',$grs_master->stock_location1)
                ->where('batchcard_id',     $request->batchcard)
                ->first();

            // DC‑stock branch
            if (in_array($grs_master->stock_location1, [8,9,12,13,14])) {
                $dc_stock = dc_transfer_stock::select('id as dc_stock_id', 'quantity')
                    ->where('product_id',       $oef_item->product_id)
                    ->where('stock_location_id',$grs_master->stock_location1)
                    ->where('batchcard_id',     $request->batchcard)
                    ->first();

                $dc_stock->quantity -= $request->batch_qty;
              //  dd('491');
                $dc_stock->save();
            }

            // Update OEF item quantities
            $oef_item->quantity_to_allocate       -= $request->batch_qty;
            $oef_item->remaining_qty_after_cancel  = $oef_item->quantity_to_allocate;
            $oef_item->save();

            // Update FGS stock
            $fgs_stock_record = fgs_product_stock_management::findOrFail($fgs_stock->fgs_stock_id);
            $fgs_stock_record->quantity -= $request->batch_qty;

            $fgs_stock_record->save();

            // MAA stock
            $maa_stock = fgs_maa_stock_management::where('product_id', $oef_item->product_id)
                ->where('batchcard_id', $request->batchcard)
                ->first();

            if ($maa_stock) {
                //dd('510');
                $maa_stock->quantity += $request->batch_qty;
                $maa_stock->save();
            } else {
               // dd('514');

                fgs_maa_stock_management::create([
                    'product_id'   => $oef_item->product_id,
                    'batchcard_id' => $request->batchcard,
                    'quantity'     => $request->batch_qty,
                    'created_at'   => $now,
                ]);
            }
          //  exit;
            // final flash & redirect
            if ($add) {
                $request->session()->flash('success', 'You have successfully added a GRS Item!');
                return redirect('fgs/GRS/item-list/'.$request->grs_id);
            }

            $request->session()->flash('error', 'GRS Item insertion failed. Try again!');
            return redirect('fgs/GRS/'.$request->grs_id.'/add-item/'.$request->oef_item_id);
        }

        // GET: render form
        $grs_master = fgs_grs::findOrFail($grs_id);
        $oef_item   = $this->fgs_oef_item->getSingleItem(['fgs_oef_item.id' => $oef_item_id]);

        if ($oef_item) {
            $batchcards = fgs_product_stock_management::select(
                    'fgs_product_stock_management.batchcard_id',
                    'batchcard_batchcard.batch_no',
                    'fgs_mrn_item.id as mrn_item_id',
                    'fgs_product_stock_management.quantity as batchcard_available_qty',
                    'fgs_mrn_item.manufacturing_date',
                    'fgs_mrn_item.expiry_date'
                )
                ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
                ->leftJoin('fgs_mrn_item',       'fgs_mrn_item.batchcard_id', '=', 'batchcard_batchcard.id')
                ->leftJoin('fgs_mrn_item_rel',   'fgs_mrn_item_rel.item',     '=', 'fgs_mrn_item.id')
                ->leftJoin('fgs_mrn',            'fgs_mrn.id',                '=', 'fgs_mrn_item_rel.master')
                ->where('fgs_product_stock_management.stock_location_id', $grs_master->stock_location1)
                ->where('fgs_product_stock_management.product_id',       $oef_item->product_id)
                ->where('fgs_product_stock_management.quantity', '>', 0)
                ->orderBy('batchcard_batchcard.id', 'ASC')
                ->groupBy('fgs_product_stock_management.id')
                ->get();

            if ($batchcards->count()) {
                $oef_item['batchcards'] = $batchcards;
            }
        }

        return view('pages/FGS/GRS/GRS-item-add', compact('grs_id', 'oef_item'));
    }
        // if($request->isMethod('post'))
        // {
        //     $validation['grs_id'] = ['required'];
        //     $validation['oef_item_id'] = ['required'];
        //     $validator = Validator::make($request->all(), $validation);
        //     if(!$validator->errors()->all())
        //     {
        //         foreach($request->oef_item_id as $oef_item_id)
        //         {

        //         }
        //     }
        //     else
        //     {
        //         return redirect('fgs/GRS/add-item/'.$request->grs_id)->withErrors($validator)->withInput();
        //     }
        // }
        // else
        // {
        //     $grs_master = fgs_grs::find($grs_id);
        //     $condition[] = ['fgs_oef_item_rel.master','=', $grs_master['oef_id']];
        //     $condition[] = ['fgs_oef_item.quantity_to_allocate','!=',0];            
        //     $oef_items = $this->fgs_oef_item->getItems($condition);
        //     foreach($oef_items as $item)
        //     {
        //         $product_batchcards = fgs_product_stock_management::select('fgs_product_stock_management.batchcard_id','batchcard_batchcard.batch_no','fgs_product_stock_management.quantity as batchcard_available_qty')
        //                             ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=', 'fgs_product_stock_management.batchcard_id')
        //                             ->where('fgs_product_stock_management.stock_location_id','=',$grs_master['stock_location1'])
        //                             ->where('fgs_product_stock_management.product_id','=',$item['product_id'])
        //                             ->where('fgs_product_stock_management.quantity','!=',0)
        //                             ->get();
        //         if(count($product_batchcards)>0)
        //         {
        //             $item['batchcards'] = $product_batchcards;
        //         }
        //     }
        //     //print_r(json_encode($oef_items));exit;
        //     return view('pages/FGS/GRS/GRS-item-add', compact('grs_id','oef_items'));
        // }
    

    public function GRSpdf($grs_id)
    {
        $data['grs'] = $this->fgs_grs->get_single_grs(['fgs_grs.id' => $grs_id]);
        $data['items'] = $this->fgs_grs_item->getAllItems(['fgs_grs_item_rel.master' => $grs_id]);
       // print_r($data['items']);
        $pdf = PDF::loadView('pages.FGS.GRS.pdf-view', $data);
        //$pdf->set_paper('A4', 'landscape');
        //$pdf->setOptions(['isPhpEnabled' => true]);       
        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->set_base_path(public_path());
        $file_name = "GRS" . $data['grs']['firm_name'] . "_" . $data['grs']['grs_date'];
        return $pdf->stream($file_name . '.pdf');
    }
    public function pendingGRS(Request $request)
    {
        $condition = [];
        if($request->grs_no)
        {
            $condition[] = ['fgs_grs.grs_number','like', '%' . $request->grs_no . '%'];
        }
        if($request->order_no)
        {
            $condition[] = ['fgs_oef.order_number','like', '%' . $request->order_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_grs.grs_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        
        $grs_items = fgs_grs_item::select('fgs_grs_item.*','fgs_item_master.sku_code','fgs_item_master.discription','fgs_item_master.hsn_code','fgs_grs.grs_number','fgs_grs.grs_date',
        'batchcard_batchcard.batch_no','fgs_mrn_item.manufacturing_date','fgs_mrn_item.expiry_date','fgs_product_category.category_name','product_stock_location.location_name as location_name1',
        'stock_location.location_name as location_name2','fgs_oef.oef_number','fgs_oef.oef_date','customer_supplier.firm_name', 'fgs_oef.order_number','fgs_oef.order_date','fgs_grs.created_at as grs_created_at',
        'fgs_oef_item.rate','fgs_oef_item.discount','inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst')
                    ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                    ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                    ->leftjoin('fgs_item_master','fgs_item_master.id','=','fgs_grs_item.product_id')
                    ->leftjoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_grs_item.batchcard_id')
                    ->leftjoin('fgs_mrn_item','fgs_mrn_item.id','=','fgs_grs_item.mrn_item_id')
                    ->leftJoin('fgs_product_category','fgs_product_category.id','fgs_grs.product_category')
                    ->leftJoin('product_stock_location','product_stock_location.id','fgs_grs.stock_location1')
                    ->leftJoin('product_stock_location as stock_location','stock_location.id','fgs_grs.stock_location2')
                    ->leftJoin('fgs_oef','fgs_oef.id','fgs_grs.oef_id')
                    ->leftjoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                    ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                    ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                    ->whereNotIn('fgs_grs.id',function($query) {

                        $query->select('fgs_pi_item.grs_id')->from('fgs_pi_item');
                    
                    })
                    ->where($condition)
                    ->where('fgs_grs_item.cgrs_status','=',0)
                    ->where('fgs_grs.status','=',1)
                    ->where('fgs_grs_item.status','=',1)
                    ->orderBy('fgs_grs_item.id','DESC')
                    ->distinct('fgs_grs_item.id')
                    ->paginate(15);
        return view('pages/FGS/GRS/pending-grs',compact('grs_items'));
    }
    public function pendingGRSExport(Request $request)
    {
        $condition = [];
        if($request->grs_no)
        {
            $condition[] = ['fgs_grs.grs_number','like', '%' . $request->grs_no . '%'];
        }
        if($request->oder_no)
        {
            $condition[] = ['fgs_oef.order_number','like', '%' . $request->order_no . '%'];
        }
        if($request->from)
        {
            $condition[] = ['fgs_grs.grs_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
            $condition[] = ['fgs_grs.grs_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        }
        $grs_data = fgs_grs_item::select(
            'fgs_grs_item.*', 'fgs_item_master.sku_code', 'fgs_item_master.discription', 'fgs_item_master.hsn_code', 'fgs_grs.grs_number', 'fgs_grs.grs_date',
            'batchcard_batchcard.batch_no', 'fgs_mrn_item.manufacturing_date','customer_supplier.city', 'fgs_mrn_item.expiry_date', 'fgs_product_category.category_name', 'fgs_product_category_new.category_name as new_category_name', 'product_stock_location.location_name as location_name1',
            'stock_location.location_name as location_name2', 'fgs_oef.oef_number', 'fgs_oef.oef_date', 'customer_supplier.firm_name', 'fgs_oef.order_number', 'fgs_oef.order_date', 'fgs_grs.created_at as grs_created_at',
            'fgs_oef_item.rate', 'fgs_oef_item.discount', 'inventory_gst.igst', 'inventory_gst.cgst', 'inventory_gst.sgst', 'zone.zone_name','state.state_name',
        )
        ->leftjoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
        ->leftjoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
        ->leftjoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
        ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
        ->leftjoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_grs_item.mrn_item_id')
        ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', 'fgs_grs.new_product_category')
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
        ->leftJoin('product_stock_location', 'product_stock_location.id', 'fgs_grs.stock_location1')
        ->leftJoin('product_stock_location as stock_location', 'stock_location.id', 'fgs_grs.stock_location2')
        ->leftJoin('fgs_oef', 'fgs_oef.id', 'fgs_grs.oef_id')
        ->leftjoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
        ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')  // Only this one instance
        
        ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
        ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
        ->whereNotIn('fgs_grs.id', function ($query) {
            $query->select('fgs_pi_item.grs_id')->from('fgs_pi_item');
        })
        ->where($condition)
        ->where('fgs_grs_item.cgrs_status', '=', 0)
        ->where('fgs_grs.status', '=', 1)
        ->where('fgs_grs_item.status', '=', 1)
        ->orderBy('fgs_grs_item.id', 'DESC')
        ->distinct('fgs_grs_item.id')
        ->get();
    
    return Excel::download(new PendingGRSExport($grs_data), 'GRSBackOrderReport' . date('d-m-Y') . '.xlsx');
    }    
    public function grs_transaction(Request $request)
    {
        $condition = [];
    
        // ✅ Fix variable name from mrn_no to grs_no
        if ($request->grs_no) {
            $condition[] = ['fgs_grs.grs_number', 'like', '%' . $request->grs_no . '%'];
        }
    
        if ($request->item_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->item_code . '%'];
        }
    
        if ($request->from) {
            $fromDate = date('Y-m-d', strtotime('01-' . $request->from));
            $toDate = date('Y-m-t', strtotime('01-' . $request->from)); // ✅ end of month
            $condition[] = ['fgs_grs.grs_date', '>=', $fromDate];
            $condition[] = ['fgs_grs.grs_date', '<=', $toDate];
        }
    
        $items = fgs_grs_item::select(
            'fgs_grs.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'fgs_grs.grs_number',
            'fgs_grs.grs_date',
            'fgs_grs.created_at as min_wef',
            'fgs_grs_item.id as grs_item_id',
            'fgs_grs_item.batch_quantity as quantity'
        )
            ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
            ->where($condition)
            ->orderBy('fgs_grs_item.id', 'desc')
            ->paginate(15);
    
        return view('pages/FGS/GRS/GRS-transaction-list', compact('items'));
    }
    
    public function grs_transaction_export(Request $request)
    {
        $condition = [];
    
        if ($request->grs_no) {
            $condition[] = ['grs1.grs_number', 'like', '%' . $request->grs_no . '%'];
        }
    
        if ($request->item_code) {
            $condition[] = ['fgs_item_master.sku_code', 'like', '%' . $request->item_code . '%'];
        }
    
        if ($request->from) {
            $condition[] = ['grs1.grs_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        }
    
        $items = fgs_grs_item::select(
            'grs1.*',
            'fgs_item_master.sku_code',
            'fgs_item_master.discription',
            'fgs_item_master.hsn_code',
            'grs1.grs_number',
            'grs1.grs_date',
            'grs1.created_at as min_wef',
            'fgs_grs_item.id as grs_item_id',
            'fgs_grs_item.batch_quantity as quantity',
            'customer_supplier.firm_name',
            'customer_supplier.city',
            'state.state_name',
            'fgs_oef.oef_number',
            'zone.zone_name',
            'loc1.location_name as location1_name',
            'loc2.location_name as location2_name',
            'grs1.stock_location1',
            'grs1.stock_location2',
            'fgs_product_category.category_name',
            'fgs_product_category_new.category_name as new_category_name',
            'customer_supplier.sales_type'
        )
            ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
            ->leftJoin('fgs_grs as grs1', 'grs1.id', '=', 'fgs_grs_item_rel.master')
            ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'grs1.oef_id') // ✅ fgs_oef joined BEFORE it's used
            // removed grs2 join since it's not necessary
            ->leftJoin('fgs_item_master', 'fgs_item_master.id', '=', 'fgs_grs_item.product_id')
            ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_grs_item.mrn_item_id')
            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'grs1.customer_id')
            ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
            ->leftJoin('product_stock_location as loc1', 'loc1.id', '=', 'grs1.stock_location1')
            ->leftJoin('product_stock_location as loc2', 'loc2.id', '=', 'grs1.stock_location2')
            ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_mrn.product_category')
            ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'grs1.new_product_category')
            ->where($condition)
            ->orderBy('fgs_grs_item.id', 'desc')
            ->get();
    
        return Excel::download(new FGSgrstransactionExport($items), 'FGS-GRS-transaction' . date('d-m-Y') . '.xlsx');
    }
    

    public function grsItemExistInPI($grs_item_id)
    {
        $pi_item = fgs_pi_item::where('grs_item_id','=',$grs_item_id)->where('status','=',1)->get();
        if(count($pi_item)>0)
        return 1;
        else
        return 0;

    }
    public function grsExistInPI($grs_id)
    {
        $pi_item = fgs_pi_item::where('grs_id','=',$grs_id)->where('status','=',1)->get();
        if(count($pi_item)>0)
        return 1;
        else
        return 0;
    }
    public function GRSItemDelete($grs_item_id, Request $request)
    {
        $grs_item = fgs_grs_item::where('id', '=', $grs_item_id)->first();
        $grs_id = fgs_grs_item_rel::where('item', '=', $grs_item_id)->first();
        $grs_data = fgs_grs::find($grs_id->master);
        $mma_stock_update = fgs_maa_stock_management::where('product_id', '=', $grs_item->product_id)
            ->where('batchcard_id', '=', $grs_item->batchcard_id)
            ->decrement('quantity', $grs_item->qty_to_invoice);
        $prduct_stock_update = fgs_product_stock_management::where('product_id', '=', $grs_item->product_id)
            ->where('batchcard_id', '=', $grs_item->batchcard_id)
            ->where('stock_location_id','=',$grs_data->stock_location1)
            ->increment('quantity', $grs_item->qty_to_invoice);
            
        if($grs_data->stock_location1==8 || $grs_data->stock_location1==9 || $grs_data->stock_location1==12 || $grs_data->stock_location1== 13 || $grs_data->stock_location1==14)
        {
            $dc_stock_update = dc_transfer_stock::where('product_id', '=', $grs_item->product_id)
                ->where('batchcard_id', '=', $grs_item->batchcard_id)
                ->where('stock_location_id','=',$grs_data->stock_location1)
                ->increment('quantity', $grs_item->qty_to_invoice);
        }
        $oef_item = fgs_oef_item::where('id', '=', $grs_item->oef_item_id)->first();
        $new_qty_to_allocate = $oef_item->quantity_to_allocate + $grs_item->qty_to_invoice;
        $oef_item_update = fgs_oef_item::where('id', '=', $grs_item->oef_item_id)->update(['quantity_to_allocate' => $new_qty_to_allocate, 'remaining_qty_after_cancel' => $new_qty_to_allocate]);
        $grs_item_update = fgs_grs_item::where('id', '=', $grs_item_id)->update(['status' => 0]);

        if ($mma_stock_update &&  $prduct_stock_update && $grs_item_update) {
            $request->session()->flash('success', "You have successfully deleted a GRS Item !");
            return redirect('fgs/GRS/item-list/' . $grs_id->master);
        }
    }

    public function GRSDelete($grs_id,Request $request)
    {
       // echo $grs_id;exit;
        $grs = fgs_grs::where('id','=',$grs_id)->first();
        $grs_items = fgs_grs_item_rel::leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_grs_item_rel.item')
                        ->where('fgs_grs_item.status','=',1)
                        ->where('fgs_grs_item_rel.master','=',$grs_id)->get();
        if(count($grs_items)>0)
        {
            $request->session()->flash('error', "You can't deleted this GRS(".$grs->grs_number.").It have items !");
        }
        else
        {
            $update = $this->fgs_grs->update_data(['id'=>$grs_id],['status'=>0]);
            $request->session()->flash('success', "You have successfully deleted a GRS(".$grs->grs_number.") !");
        }
        return redirect('fgs/GRS-list');

    }

    public function GRSEdit($grs_id,Request $request,)
    {
        if($request->isMethod('post'))
        {
            $validation['grs_id'] = ['required'];
            $validation['grs_number'] = ['required'];
            $validation['grs_date'] = ['required'];
            $validation['new_product_category'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if(!$validator->errors()->all())
            {
                $data['grs_number']=$request->grs_number;
                $data['grs_date']=date('Y-m-d', strtotime($request->grs_date));
                $data['new_product_category'] = $request->new_product_category;
                $update = $this->fgs_grs->update_data(['id'=>$grs_id],$data);
                if($update)
                $request->session()->flash('success', "You have successfully update a GRS.");
                else
                $request->session()->flash('error', "You have failed to update a GRS.");
                return redirect('fgs/GRS-list');
            }
            if($validator->errors()->all())
            {
                return redirect('fgs/GRS-list')->withErrors($validator)->withInput();
            }
        }
        else
        {
          //  dd('else');
            $grs= $this->fgs_grs->get_single_grs(['fgs_grs.id'=>$grs_id]);
            $product_category = DB::table('fgs_product_category_new')->get();
            //print_r($grs);exit;
            return view('pages/FGS/GRS/GRS-add', compact('grs','product_category'));
        }
    }

    public function GRSItemEdit($grs_item_id, Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['grs_id'] = ['required'];
            $validation['batchcard'] = ['required'];
            $validation['grs_item_id'] = ['required'];
            $validation['batch_qty'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {

                $grs_master = fgs_grs::find($request->grs_id);
                $grs_item = fgs_grs_item::find($request->grs_item_id);
                $fgs_stock_old_batch = fgs_product_stock_management::select('id as fgs_stock_id', 'quantity')
                    ->where('product_id', '=', $grs_item['product_id'])
                    ->where('stock_location_id', '=', $grs_master['stock_location1'])
                    ->where('batchcard_id', '=', $grs_item['batchcard_id'])
                    ->first();

                $old_batch_stock_updation = $fgs_stock_old_batch['quantity'] + $grs_item->qty_to_invoice;
                $old_stock_mngment = fgs_product_stock_management::find($fgs_stock_old_batch['fgs_stock_id']);
                $old_stock_mngment->quantity = $old_batch_stock_updation;
                $old_stock_mngment->save();

                if($grs_master->stock_location1==8 || $grs_master->stock_location1==9 || $grs_master->stock_location1==12 || $grs_master->stock_location1== 13 || $grs_master->stock_location1==14)
                {
                    $dc_stock_old_batch = dc_transfer_stock::select('id as dc_stock_id', 'quantity')
                            ->where('product_id', '=', $grs_item['product_id'])
                            ->where('stock_location_id', '=', $grs_master['stock_location1'])
                            ->where('batchcard_id', '=', $grs_item['batchcard_id'])
                            ->first();

                    $old_dc_batch_stock_updation = $dc_stock_old_batch['quantity'] + $grs_item->qty_to_invoice;
                    $old_dc_stock_mngment = dc_transfer_stock::find($dc_stock_old_batch['dc_stock_id']);
                    $old_dc_stock_mngment->quantity = $old_dc_batch_stock_updation;
                    $old_dc_stock_mngment->save();
                }

                $maa_stock_old_batch = fgs_maa_stock_management::select('id as maa_stock_id', 'quantity')
                    ->where('product_id', '=', $grs_item['product_id'])
                    ->where('batchcard_id', '=', $grs_item['batchcard_id'])
                    ->first();
                $old_batch_maa_stock_updation = $maa_stock_old_batch['quantity'] - $grs_item->qty_to_invoice;
                $old_batch_update = $this->fgs_maa_stock_management->update_data(['id' => $maa_stock_old_batch['id']], ['quantity' => $old_batch_maa_stock_updation]);

                $fgs_stock_new_batch = fgs_product_stock_management::select('id as fgs_stock_id', 'quantity')
                    ->where('product_id', '=', $grs_item['product_id'])
                    ->where('stock_location_id', '=', $grs_master['stock_location1'])
                    ->where('batchcard_id', '=', $request->batchcard)
                    ->first();

                $new_batch_stock_updation = $fgs_stock_new_batch['quantity'] - $request->batch_qty;
                $new_stock_mngment = fgs_product_stock_management::find($fgs_stock_new_batch['fgs_stock_id']);
                $new_stock_mngment->quantity = $new_batch_stock_updation;
                $new_stock_mngment->save();

                if($grs_master->stock_location1==8 || $grs_master->stock_location1==9 || $grs_master->stock_location1==12 || $grs_master->stock_location1== 13 || $grs_master->stock_location1==14)
                {
                    $dc_stock_new_batch = dc_transfer_stock::select('id as dc_stock_id', 'quantity')
                            ->where('product_id', '=', $grs_item['product_id'])
                            ->where('stock_location_id', '=', $grs_master['stock_location1'])
                            ->where('batchcard_id', '=', $request->batchcard)
                            ->first();

                    $new_dc_batch_stock_updation = $dc_stock_new_batch['quantity'] - $request->batch_qty;
                    $new_dc_stock_mngment = dc_transfer_stock::find($dc_stock_new_batch['dc_stock_id']);
                    $new_dc_stock_mngment->quantity = $new_dc_batch_stock_updation;
                    $new_dc_stock_mngment->save();
                }

                $maa_stock_new_batch = fgs_maa_stock_management::select('id as maa_stock_id', 'quantity')
                    ->where('product_id', '=', $grs_item['product_id'])
                    ->where('batchcard_id', '=', $request->batchcard)
                    ->first();

                if ($maa_stock_new_batch) {
                    $maa_new_stock_updation = $maa_stock_new_batch['quantity'] + $request->batch_qty;
                    $update = $this->fgs_maa_stock_management->update_data(['id' => $maa_stock_new_batch['id']], ['quantity' => $maa_new_stock_updation]);
                } else {
                    $stock['product_id'] = $grs_item['product_id'];
                    $stock['batchcard_id'] = $request->batchcard;
                    $stock['quantity'] = $request->batch_qty;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $stock_add = $this->fgs_maa_stock_management->insert_data($stock);
                }

                $data['batchcard_id'] = $request->batchcard;
                $data['batch_quantity'] = $request->batch_qty;
                $data['remaining_qty_after_cancel'] = $request->batch_qty;
                $data['qty_to_invoice'] = $request->batch_qty;
                //$data['created_at'] =date('Y-m-d H:i:s');
                $update = $this->fgs_grs_item->update_data(['fgs_grs_item.id' => $request->grs_item_id], $data);

                if ($update) {
                    $request->session()->flash('success', "You have successfully updated a GRS Item!");
                    return redirect('fgs/GRS/item-list/' . $request->grs_id);
                } else {
                    $request->session()->flash('error', "GRS Item updation is failed. Try again... !");
                    return redirect('fgs/GRS/item-list/' . $request->grs_id);
                }
            }
            if ($validator->errors()->all()) {
                return redirect('fgs/GRS/item-list/' . $request->grs_id)->withErrors($validator)->withInput();
            }
        } else {
            $grs_item = $this->fgs_grs_item->getSingleItem(['fgs_grs_item.id' => $grs_item_id]);
            $grs_id = fgs_grs_item_rel::where('item', '=', $grs_item_id)->first();
            $grs_master = fgs_grs::find($grs_id->master);
            $grs_id = $grs_id->master;

            //$oef_item = $this->fgs_oef_item->getSingleItem(['fgs_oef_item.id'=>$oef_item_id]);
            if ($grs_item) {
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
                    ->where('fgs_product_stock_management.stock_location_id', '=', $grs_master['stock_location1'])
                    ->where('fgs_product_stock_management.product_id', '=', $grs_item['product_id'])
                    ->where('fgs_mrn_item.product_id', '=', $grs_item['product_id'])
                    ->where('fgs_mrn.stock_location', '=', $grs_master['stock_location1'])
                    //->where('fgs_mrn.product_category', '=', $grs_master['product_category'])
                    ->where('fgs_product_stock_management.quantity', '>', 0)
                    ->orderByRaw('SUBSTRING(batchcard_batchcard.batch_no, 1, 2) ASC')

                    // ->orderBy('batchcard_batchcard.id', 'ASC')
                    ->groupBy('fgs_product_stock_management.id')
                    ->get();
                if (count($product_batchcards) > 0) {
                    $grs_item['batchcards'] = $product_batchcards;
                }
                //print_r(json_encode($oef_item));exit;

            }
            return view('pages/FGS/GRS/GRS-item-add', compact('grs_id', 'grs_item'));
        }
    }

    public function get_batch($grs_id, $oef_item_id)
    {

        $grs_master = fgs_grs::find($grs_id);
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
                ->where('fgs_product_stock_management.stock_location_id', '=', $grs_master['stock_location1'])
                ->where('fgs_product_stock_management.product_id', '=', $oef_item['product_id'])
                ->where('fgs_mrn_item.product_id', '=', $oef_item['product_id'])
                ->where('fgs_mrn.stock_location', '=', $grs_master['stock_location1'])
                ->where('fgs_mrn.product_category', '=', $grs_master['product_category'])
                ->where('fgs_product_stock_management.quantity', '>', 0)
                ->orderBy('batchcard_batchcard.id', 'ASC')
                ->groupBy('fgs_product_stock_management.id')
                ->get();
        }
        if (count($product_batchcards) > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}
