<?php

namespace App\Http\Controllers\Web\fgs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

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
use App\Models\FGS\fgs_cdc;
use App\Models\FGS\fgs_cdc_item;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\User;
use App\Models\batchcard;
use PhpOffice\PhpSpreadsheet\Spreadsheet;



use App\Models\PurchaseDetails\inv_supplier;


class CDCController extends Controller
{
    public function __construct()
    {
        $this->fgs_product_stock_management = new fgs_product_stock_management;
        $this->transaction_type = new transaction_type;
        $this->Delivery_Challan = new Delivery_Challan;
        $this->delivery_challan_item = new delivery_challan_item;
        $this->dc_transfer_stock = new dc_transfer_stock;
        $this->fgs_oef_item = new fgs_oef_item;
        $this->fgs_oef = new fgs_oef;
        $this->fgs_product_category = new fgs_product_category;
        $this->product_stock_location = new product_stock_location;
        $this->inv_supplier = new inv_supplier;
        $this->fgs_cdc = new fgs_cdc;
        $this->fgs_cdc_item = new fgs_cdc_item;


        $this->User = new User;
    }
    public function CDCList(Request $request)
    {
        $condition = [];
        if ($request->cpi_number) {
            $condition[] = ['fgs_cdc.cdc_number', 'like', '%' . $request->cdc_number . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        // if ($request->from) {
        //     $condition[] = ['fgs_cpi.cpi_date', '>=', date('Y-m-d', strtotime('01-' . $request->from))];
        //     $condition[] = ['fgs_cpi.cpi_date', '<=', date('Y-m-t', strtotime('01-' . $request->from))];
        // }
        $cdc = fgs_cdc::select('fgs_cdc.*','delivery_challan.transaction_condition', 'delivery_challan.doc_no', 'delivery_challan.doc_date', 'customer_supplier.firm_name','transaction_type.transaction_name',
        )
            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'fgs_cdc.dc_id')
            ->leftJoin('transaction_type', 'transaction_type.id', '=', 'delivery_challan.transaction_type')
            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_cdc.customer_id')
            ->where($condition)
            ->distinct('fgs_cdc.id')
            ->orderBy('fgs_cdc.id', 'DESC')
            ->paginate(15);
        return view('pages/FGS/CDC/CDC-list', compact('cdc'));
    }
    public function CDCAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validation['cdc_date'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) {
                $dc = DB::table('delivery_challan')->select('transaction_condition','doc_no')->where('id','=',$request->dc_number)->first();
                //print_r($dc);exit;
                if($dc->transaction_condition==2)
                {
                    $request->session()->flash('error', "Not possible to create CDC against this DC. Its a non-returnable !");
                    return redirect('fgs/CDC/CDC-add');
                }
                else
                {
                    if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
                        $years_combo = date('y', strtotime('-1 year')) . date('y');
                    } else {
                        $years_combo = date('y') . date('y', strtotime('+1 year'));
                    }
                    $data['cdc_number'] = "CDC-" . $this->year_combo_num_gen(DB::table('fgs_cdc')->where('fgs_cdc.cdc_number', 'LIKE', 'CDC-' . $years_combo . '%')->count());
                    $data['cdc_date'] = date('Y-m-d', strtotime($request->cdc_date));
                    $data['created_by'] = config('user')['user_id'];
                    $data['dc_id'] = $request->dc_number;
                    $data['status'] = 1;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $data['remarks'] = $request->remarks;
                    $data['customer_id'] = $request->customer_id;

                    $cdc_id = $this->fgs_cdc->insert_data($data);
                    $i = 0;
                    $qty_to_cancel_array = $request->qty_to_cancel;

                    foreach ($request->dc_item_id as $dc_item_id) {
                        $dc_item = delivery_challan_item::find($dc_item_id);
                        $datas = [
                            "dc_item_id" => $dc_item_id,

                            "batchcard_id" => $dc_item['batchcard_id'],
                            "product_id" => $dc_item['product_id'],
                            "quantity" => $qty_to_cancel_array[$i]
                            // "created_at" => date('Y-m-d H:i:s')
                        ];

                        $this->fgs_cdc_item->insert_data($datas, $cdc_id);
                        if ($dc_item['remaining_qty_after_cancel'] == $qty_to_cancel_array[$i]) {
                            $delivery_challan_item = delivery_challan_item::where('id', '=', $dc_item_id)
                                // ->where('id', '=', $dc_item['id'])
                                ->update(['cdc_status' => 1]);
                            $update_qty = $dc_item['remaining_qty_after_cancel'] - $qty_to_cancel_array[$i];
                            $fgs_dc_item = delivery_challan_item::where('id', '=', $dc_item_id)
                                ->update(['remaining_qty_after_cancel' => $update_qty]);
                        } else {
                            $update_qty = $dc_item['remaining_qty_after_cancel'] - $qty_to_cancel_array[$i];
                            $fgs_dc_item = delivery_challan_item::where('id', '=', $dc_item_id)
                                ->update(['remaining_qty_after_cancel' => $update_qty,
                                'cdc_status' => 1]);
                        }
                        $dc_master = delivery_challan::where('id', $request->dc_number)->first();

                        $oef_item = fgs_oef_item::find($dc_item->oef_item_id);

                        // $oef_qty_updation = $oef_item['quantity_to_allocate'] + $qty_to_cancel_array[$i];
                        // $oef_item['quantity_to_allocate'] = $oef_qty_updation;
                        // $oef_item['remaining_qty_after_cancel'] = $oef_qty_updation;
                        // $oef_item->save();
                        // dd($dc_item);
                        $fgs_stock = fgs_product_stock_management::select('id as fgs_stock_id', 'quantity')
                            ->where('product_id', '=', $dc_item->product_id)
                            ->where('stock_location_id', '=', $dc_master['stock_location_decrease'])
                            ->where('batchcard_id', '=', $dc_item->batchcard_id)
                            ->first();
                        // dd($fgs_stock);
                        $stock_updation = $fgs_stock['quantity'] + $qty_to_cancel_array[$i];
                        $stock_mngment = fgs_product_stock_management::find($fgs_stock['fgs_stock_id']);
                        $stock_mngment->quantity = $stock_updation;
                        $stock_mngment->save();

                        if ($dc_master->transaction_condition != 2) {
                            $dc_stock = dc_transfer_stock::select('id as dc_stock_id', 'quantity')
                                ->where('product_id', '=', $dc_item->product_id)
                                ->where('batchcard_id', '=', $dc_item->batchcard_id)
                                ->where('stock_location_id', '=', $dc_master->stock_location_increase)
                                ->first();
                            $fgs_increase_stock = fgs_product_stock_management::select('id as fgsstock_id', 'quantity')
                                ->where('product_id', '=', $dc_item->product_id)
                                ->where('stock_location_id', '=', $dc_master->stock_location_increase)
                                ->where('batchcard_id', '=', $dc_item->batchcard_id)
                                ->first();
                            $fgs_stock_updation = $fgs_increase_stock['quantity'] - $qty_to_cancel_array[$i];
                            $fgs_stock_mngment = fgs_product_stock_management::find($fgs_increase_stock['fgsstock_id']);
                            $fgs_stock_mngment->quantity = $fgs_stock_updation;
                            $fgs_stock_mngment->save();
                            //$update = $this->fgs_product_stock_management->update_data(['id' => $fgs_increase_stock['fgsstock_id']], ['quantity' => $fgs_stock_updation]);    
                            //print_r($dc_master);exit;
                            if ($dc_stock) {
                                $dc_stock_updation = $dc_stock['quantity'] - $qty_to_cancel_array[$i];
                                $update = $this->dc_transfer_stock->update_data(['id' => $dc_stock['dc_stock_id']], ['quantity' => $dc_stock_updation]);
                            }
                        }
                        $i++;
                    }
                }
                if ($cdc_id) {
                    $request->session()->flash('success', "You have successfully added a CDC !");
                    return redirect('fgs/CDC/CDC-list');
                } else {
                    $request->session()->flash('error', "CDC insertion is failed. Try again... !");
                    return redirect('fgs/CDC/CDC-add');
                }
            }

            if ($validator->errors()->all()) {
                return redirect('fgs/CDC/CDC-add')->withErrors($validator)->withInput();
            }
        }
        $condition1[] = ['user.status', '=', 1];
        $data['users'] = $this->User->get_all_users($condition1);

        if ($request->id) {
            $edit['dc'] = $this->fgs_pi->find_dc_datas(['delivery_challan.id' => $request->id]);
            $edit['items'] = $this->fgs_pi_item->get_items(['fgs_pi_item_rel.master' => $request->id]);
            $transaction_type = transaction_type::get();
            return view('pages.FGS.CDC.CDC-add', compact('edit', 'data', 'transaction_type'));
        } else {
            return view('pages/fgs/CDC/CDC-add', compact('data'));
        }
    }
    public function CDCItemList(Request $request, $id)
    {
        $condition = ['fgs_cdc_item_rel.master' =>$request->id];
        if($request->product)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->product . '%'];
        }
        $items = $this->fgs_cdc_item->get_items($condition);
        //print_r($items);exit; 
       // echo $min_id;exit;
        return view('pages/FGS/CDC/CDC-item-list', compact('id','items'));
    }
    public function CDCpdf($id)
    {
        $data['cdc'] = $this->fgs_cdc->get_single_cdc(['fgs_cdc.id' => $id]);
        if($data['cdc']['ref_no']==NULL)
        $data['item'] = $this->fgs_cdc_item->getItems(['fgs_cdc_item_rel.master' => $id]);
        else
        $data['item'] = $this->fgs_cdc_item->getManualItems(['fgs_cdc_item_rel.master' => $id]);

        $pdf = PDF::loadView('pages.FGS.CDC.pdf-view', $data);
        //$pdf->set_paper('A4', 'landscape');
        //$pdf->setOptions(['isPhpEnabled' => true]);       
        $pdf->setOptions(['isPhpEnabled' => true]);       

        $file_name = "CDC" . $data['cdc']['firm_name'] . "_" . $data['cdc']['cdc_date'];
        return $pdf->stream($file_name . '.pdf');
    }

    public function findDCNumberForCDC(Request $request)
    {
        if ($request->q) {

            $condition[] = ['delivery_challan.doc_no', 'like', '%' . strtoupper($request->q) . '%'];

            $data = $this->Delivery_Challan->find_dc_num_for_cdc($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->pi_details($request->id, null);
            exit;
        }
    }

    public function dcInfo(Request $request)
    {
        if ($request->q) {
            $condition[] = ['delivery_challan.doc_no', 'like', '%' . strtoupper($request->q) . '%'];

            $data = $this->Delivery_Challan->find_dc_num_for_cdc($condition);
            if (!empty($data[0])) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'item code is not valid'], 500);
            }
        } else {
            echo $this->dc_details($request->id, null);
            exit;
        }
    }
    public function dc_details($id, $active = null)
    {
        $dc = $this->Delivery_Challan->get_dc_data(['delivery_challan.id' => $id]);
        //print_r($pi);exit;
        //return $invoice;
        $dc_item = $this->delivery_challan_item->get_dc_item(['delivery_challan_item_rel.master' => $id]);
        //print_r(json_encode($dc_item));exit;
        $data = '

        <div class="row">
            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
               DC number (' . $dc->doc_no . ')
                   </label>
              <div class="form-devider"></div>
            </div>
         
            <table class="table table-bordered mg-b-0" style="padding-right: 15px;padding-left: 15px;">
                <thead>
                </thead>
                <tbody >
                    <tr>
                        <th>Customer</th>
                        <td>' . $dc->firm_name . '</td>
                        <th>City, Zone, State</th>
                        <td>' . $dc->city . ' ,' . $dc->zone_name . ',' . $dc->state_name . '</td>
                    </tr>
                    <tr>
                        <th>DC Date</th>
                        <td>' . date('d-m-Y', strtotime($dc->doc_date)) . '</td>
                        <th>Created Date</th>
                        <td>' . date('d-m-Y', strtotime($dc->created_at)) . '</td>     
                    </tr>

                </tbody>
           </table>
        </div>
           <br>
            <div class="row" >
             <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
               <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';

        $data .= 'DC Items ';
        $data .= '</label>
                 <div class="form-devider"></div>
             </div>
            </div>
        <div class="table-responsive">
        <table class="table table-bordered mg-b-0" id="example1" style="padding-right: 15px;padding-left: 15px;">';

        $data .= '
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" onclick="toggleCheckboxes(this)"></th>                
                <th>DC NUMBER</th>
                <th>PRODUCT</th>
                <th>DESCRIPTION</th>
                <th>QUANTITY</th>
                <th>QUANTITY TO CANCEL</th>
            </tr>
            </thead>
            <tbody >';
        foreach ($dc_item as $item) {
            $data .= '
                <tr>
                       <td ><input type="checkbox" class="rowCheckbox" name="dc_item_id[]" onclick="enableTextBox(this)" id="dc_item_id" value="' . $item->id . '"></td>
                       <td>' . $item->doc_no . '</td>
                       <td>' . $item->sku_code . '</td>
                       <td>' . $item->discription . '</td>
                       <td id="qty">' . $item->batch_qty . 'Nos</td>
                       <td style="display:none;">' . $item->grs_id . '</td>
                       <td style="display:none;">' . $item->grs_item_id . '</td>
                       <td style="display:none;">' . $item->mrn_item_id . '</td>
                       <td><input type="text" class="qty_to_cancel" id="qty_to_cancel" name="qty_to_cancel[]" min="0" max="' . $item->batch_qty . '" disabled></td>
                </tr>';
        }
        $data .= '</tbody>';
        $data .= '</table>
        </div>
         <div class="row">
                <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1" style="margin-top: 6px; ">
                   <label>Remarks:</label>
                </div>
        </div>
        <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <textarea type="text"  name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks" rows= "4">     </textarea>    
                       
                        
                        <input type="hidden" name="created_at" value=" ' . date('d-m-Y', strtotime($dc->created_at)) . ' ">
                        <input type="hidden" name="customer_id" value="' . $dc->customer_id . '">
                        </div>
                </div>
               <br>
                <div class="row" >
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded invoice-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                Save 
                            </button>
                        </div>
                    </div>
             
             ';
        return $data;
    }
    public function CDCitemEdit($cdc_id, Request $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {
                Log::info('CDCitemEdit POST request received', ['cdc_id' => $cdc_id, 'request' => $request->all()]);
    
                $cdc_item = fgs_cdc_item::find($cdc_id);
                if (!$cdc_item) {
                    return redirect()->back()->with('error', 'CDC item not found.');
                }
    
                $dc_item = delivery_challan_item::find($cdc_item->dc_item_id);
                $dc_master = delivery_challan::leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.master', 'delivery_challan.id')
                    ->where('delivery_challan_item_rel.item', $cdc_item->dc_item_id)
                    ->select('delivery_challan.*')
                    ->first();
    
                $product_id = $cdc_item->product_id;
                $old_qty = (float) $cdc_item->quantity;
                $new_qty = (float) $request->batch_qty;
                $qty_diff = $new_qty - $old_qty;
    
                $old_batch_id = $cdc_item->batchcard_id;
                $new_batch_id = $request->batchcard;
                $location_id = $dc_master->stock_location_decrease;
                $location_increase_id = $dc_master->stock_location_increase;
    
                // 🟢 fgs_product_stock_management update
                if ($old_batch_id === $new_batch_id) {
                    // Same batch, adjust only delta
                    $stock = fgs_product_stock_management::where([
                        'product_id' => $product_id,
                        'batchcard_id' => $old_batch_id,
                        'stock_location_id' => $location_id,
                    ])->first();
    
                    if ($stock) {
                        $stock->quantity += $qty_diff;
                        $stock->manufacturing_date = date('Y-m-d', strtotime($request['manufacturing_date']));
                        $stock->expiry_date = ($request['expiry_date'] != 'N.A') ? date('Y-m-d', strtotime($request['expiry_date'])) : null;
                        $stock->save();
                    }
                } else {
                    // Different batch, reverse old and apply new
                    $old_stock = fgs_product_stock_management::where([
                        'product_id' => $product_id,
                        'batchcard_id' => $old_batch_id,
                        'stock_location_id' => $location_id,
                    ])->first();
    
                    if ($old_stock) {
                        $old_stock->quantity -= $old_qty;
                        $old_stock->save();
                    }
    
                    $new_stock = fgs_product_stock_management::firstOrNew([
                        'product_id' => $product_id,
                        'batchcard_id' => $new_batch_id,
                        'stock_location_id' => $location_id,
                    ]);
                    $new_stock->quantity = ($new_stock->quantity ?? 0) + $new_qty;
                    $new_stock->manufacturing_date = date('Y-m-d', strtotime($request['manufacturing_date']));
                    $new_stock->expiry_date = ($request['expiry_date'] != 'N.A') ? date('Y-m-d', strtotime($request['expiry_date'])) : null;
                    $new_stock->save();
                }
    
                // 🟢 Update CDC item
                $cdc_item->batchcard_id = $new_batch_id;
                $cdc_item->quantity = $new_qty;
                $cdc_item->manufacturing_date = date('Y-m-d', strtotime($request['manufacturing_date']));
                $cdc_item->expiry_date = ($request['expiry_date'] != 'N.A') ? date('Y-m-d', strtotime($request['expiry_date'])) : null;
                $cdc_item->save();
    
                // 🟢 Update DC Item remaining_qty_after_cancel
                if ($qty_diff > 0) {
                    $dc_item->remaining_qty_after_cancel -= $qty_diff;
                } elseif ($qty_diff < 0) {
                    $dc_item->remaining_qty_after_cancel += abs($qty_diff);
                }
                $dc_item->batchcard_id = $new_batch_id;
                $dc_item->save();
    
                // 🟢 DC Transfer Stock
                if ($dc_master->transaction_condition != 2) {
                    if ($old_batch_id === $new_batch_id) {
                        $dc_stock = dc_transfer_stock::where([
                            'product_id' => $product_id,
                            'batchcard_id' => $old_batch_id,
                            'stock_location_id' => $location_increase_id,
                        ])->first();
    
                        if ($dc_stock) {
                            if ($qty_diff > 0) {
                                $dc_stock->quantity -= $qty_diff;
                            } elseif ($qty_diff < 0) {
                                $dc_stock->quantity += abs($qty_diff);
                            }
                            $dc_stock->save();
                        }
                    } else {
                        $old_dc_stock = dc_transfer_stock::where([
                            'product_id' => $product_id,
                            'batchcard_id' => $old_batch_id,
                            'stock_location_id' => $location_increase_id,
                        ])->first();
    
                        if ($old_dc_stock) {
                            $old_dc_stock->quantity += $old_qty;
                            $old_dc_stock->save();
                        }
    
                        $new_dc_stock = dc_transfer_stock::firstOrNew([
                            'product_id' => $product_id,
                            'batchcard_id' => $new_batch_id,
                            'stock_location_id' => $location_increase_id,
                        ]);
                        $new_dc_stock->quantity = ($new_dc_stock->quantity ?? 0) - $new_qty;
                        $new_dc_stock->save();
                    }
                }
    
                DB::commit();
                $request->session()->flash('success', 'CDC item updated successfully!');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('CDC item update failed', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Failed to update CDC item.');
            }
        
    
        return redirect()->back()->with('error', 'Invalid request method.');
    }
else{    
            // Handle the GET request logic
            $cdc_item = fgs_cdc_item::select(
                'batchcard_batchcard.batch_no',
                'batchcard_batchcard.id as batchcard_id',
                'fgs_cdc_item.*',
                DB::raw('IFNULL(fgs_cdc_item.manufacturing_date, fgs_mrn_item.manufacturing_date) as manufacturing_date'),
                DB::raw('IFNULL(fgs_cdc_item.expiry_date, fgs_mrn_item.expiry_date) as expiry_date')
            )
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_cdc_item.batchcard_id')
            ->leftJoin('delivery_challan_item', 'delivery_challan_item.id', '=', 'fgs_cdc_item.dc_item_id')
            ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'delivery_challan_item.mrn_item_id')
            ->where('fgs_cdc_item.id', $cdc_id)
            ->first();
        
    
            // Fetch DC item and stock details
            $dc_master = delivery_challan::select('delivery_challan.*')
                ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.master', 'delivery_challan.id')
                ->where('delivery_challan_item_rel.item', $cdc_item->dc_item_id)
                ->first();
    
            $dc_item = delivery_challan_item::select('batchcard_batchcard.batch_no', 'delivery_challan_item.*')
                ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'delivery_challan_item.batchcard_id')
                ->where('delivery_challan_item.id', $cdc_item->dc_item_id)
                ->first();
    
            if ($dc_item) {
                $oef_item = $this->fgs_oef_item->getSingleItem(['fgs_oef_item.id' => $dc_item->oef_item_id]);
            }
    
            // Fetch stock management details
            $stk = fgs_product_stock_management::select('fgs_product_stock_management.*')
                ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_product_stock_management.batchcard_id')
                ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.batchcard_id', '=', 'batchcard_batchcard.id')
                ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                ->where('fgs_product_stock_management.stock_location_id', '=', $dc_master->stock_location_decrease)
                ->where('fgs_product_stock_management.product_id', '=', $cdc_item->product_id)
                ->where('fgs_mrn_item.product_id', '=', $cdc_item->product_id)
                ->where('fgs_mrn.stock_location', '=', $dc_master->stock_location_decrease)
                ->first();
    
            // Get batchcards
            $oef_item['batchcards'] = batchcard::select(
                    'batchcard_batchcard.batch_no',
                    'batchcard_batchcard.id as batch_id',
                    'batchcard_batchcard.start_date',
                    'batchcard_batchcard.target_date',
                    'batchcard_batchcard.quantity',
                    'product1.is_sterile'

                )
                ->leftJoin('fgs_item_master as product1', 'product1.id', '=', 'batchcard_batchcard.product_id')
                ->where('batchcard_batchcard.product_id', '=', $cdc_item->product_id)
                ->orderBy('batchcard_batchcard.id', 'asc')
                ->get();
    
            // Handle sterile batch cards if applicable
            foreach ($oef_item['batchcards'] as $batchcard) {
                if ($batchcard->is_sterile == 1) {
                    // dd($batchcard->is_sterile);
                    return view('pages/FGS/CDC/CDC-item-edit-sterile', compact('cdc_id', 'oef_item', 'dc_item', 'cdc_item','stk'));
                }
            }
           // dd($batchcard->is_sterile);
            // Default view if no sterile batchcards
            return view('pages/FGS/CDC/CDC-item-edit', compact('cdc_id', 'oef_item', 'dc_item', 'cdc_item', 'stk'));
        }
    }
    
    public function CDCInvTransactionReport(Request $request)
    {
        $condition = [];
        if($request->doc_no)
        {
            $condition[] = ['fgs_cdc.doc_no','like', '%' . $request->doc_no . '%'];
        }
        if($request->customer)
        {
            $condition[] = ['customer_supplier.firm_name','like', '%' . $request->customer . '%'];
        }
        if($request->oef_no)
        {
            $condition[] = ['fgs_oef.oef_number','like', '%' . $request->oef_no . '%'];
        }
        if($request->sku_code)
        {
            $condition[] = ['fgs_item_master.sku_code','like', '%' . $request->sku_code . '%'];
        }
        if($request->batch_no)
        {
            $condition[] = ['batchcard_batchcard.batch_no','like', '%' . $request->batch_no . '%'];
        }
        $cdc_items= $this->fgs_cdc_item->getCDCItems($condition);
      //  dd($cdc_items);
        return view('pages/FGS/CDC/cdc_inv_transaction_report', compact('cdc_items'));
    }
  
    public function findDCNumberFormanualCDC(Request $request)
{
    if ($request->q && $request->customer_id) {
        $customerId = $request->customer_id; // Get the customer ID from the request
        $dcNumber = strtoupper($request->q); // Get the DC number from the request
        
        // Assuming you have a way to filter DC numbers based on customer ID
        $condition[] = ['delivery_challan.doc_no', 'like', '%' . $dcNumber . '%'];
        $condition[] = ['delivery_challan.customer_id', '=', $customerId]; // Add condition to filter by customer_id

        $data = $this->Delivery_Challan->find_dc_num_for_cdc($condition);
        
        if (!empty($data)) {
            return response()->json($data, 200); // Return matching data
        } else {
            return response()->json(['message' => 'No matching DC numbers found'], 500);
        }
    } else {
        return response()->json(['message' => 'Invalid request parameters'], 400);
    }
}

public function CDCNewManualAddPage(Request $request)
{
    if($request->isMethod('post'))
    {
        $validation['cdc_date'] = ['required','date'];
       $validation['stock_location_increase'] = ['required'];
        $validation['remarks'] = [''];
        $validation['product_category'] =['required']; // Adjust validation rules based on your needs
        $validation['new_product_category'] =['required']; // Adjust validation rules based on your needs
        $validation['customer_id'] =['required']; // Adjust validation rules based on your needs
      // $validation['product_category'] = ['required'];
      $validator = Validator::make($request->all(), $validation);
      if ($validator->fails()) {
          return redirect()->back()->withErrors($validator)->withInput();
      }
                  if(!$validator->errors()->all())
        {
            $file = $request->file('cdc_file');
            if ($file) 
            {
                $ExcelOBJ = new \stdClass();

                $path = storage_path() . '/app/' . $request->file('cdc_file')->store('temp');

                $ExcelOBJ->inputFileName = $path;
                $ExcelOBJ->inputFileType = 'Xlsx';

                // $ExcelOBJ->filename = 'Book1.xlsx';
                // $ExcelOBJ->inputFileName = 'C:\xampp7.4\htdocs\mel\sampleData\Book1.xlsx';
                $ExcelOBJ->spreadsheet = new Spreadsheet();
                $ExcelOBJ->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ExcelOBJ->inputFileType);
                $ExcelOBJ->reader->setReadDataOnly(true);
                $ExcelOBJ->worksheetData = $ExcelOBJ->reader->listWorksheetInfo($ExcelOBJ->inputFileName);
                $no_column = 5;
                $sheet1_column_count = $ExcelOBJ->worksheetData[0]['totalColumns'];
                //echo $sheet1_column_count;exit;
                if ($sheet1_column_count == $no_column) {
                   
                    $res = $this->Excelsplitsheet($ExcelOBJ, $request);
                    // print_r($res);exit;
                    if ($res) {
                        $request->session()->flash('success', "You have successfully added a CDC !");
                        return redirect('fgs/manual-CDC');
                    } else {
                        $request->session()->flash('error',  "The data already uploaded.");
                        return redirect()->back();
                    }
                } else {
                    $request->session()->flash('error',  "Column not matching.. Please download the excel template and check the column count");
                    return redirect()->back();
                }
                
            }
        }
    }
    else
    {
        $locations = product_stock_location::get();
        $category = fgs_product_category::get();
        return view('pages/FGS/CDC/CDC-manual-add-with-upload',compact('locations','category'));
    }
}
public function Excelsplitsheet($ExcelOBJ, $request)
{
  
    $ExcelOBJ->SQLdata = [];
    $ExcelOBJ->arrayinc = 0;

    foreach ($ExcelOBJ->worksheetData as $key => $worksheet) {
        $ExcelOBJ->sectionName = '';
        $ExcelOBJ->sheetName = $worksheet['worksheetName'];
        $ExcelOBJ->reader->setLoadSheetsOnly($ExcelOBJ->sheetName);
        $ExcelOBJ->spreadsheet = $ExcelOBJ->reader->load($ExcelOBJ->inputFileName);
        $ExcelOBJ->worksheet = $ExcelOBJ->spreadsheet->getActiveSheet();
        // print_r(json_encode($ExcelOBJ->worksheet));exit;
        $ExcelOBJ->excelworksheet = $ExcelOBJ->worksheet->toArray();
        $ExcelOBJ->date_created = date('Y-m-d H:i:s');
        $ExcelOBJ->sheetname = $ExcelOBJ->sheetName;
        $res = $this->insert_cdc_items($ExcelOBJ, $request);
        return $res;
    }
}
function insert_cdc_items($ExcelOBJ, $request)
{
    $years_combo = date('Y'); // Example: 2024
    $product_category = $request->input('product_category_hidden');
    $new_product_category = $request->input('new_product_category_hidden');
    $customer_id = $request->input('customer_id_hidden');

    $data = [];
    if (date('m') == 01 || date('m') == 02 || date('m') == 03) {
        $years_combo = date('y', strtotime('-1 year')).date('y');
    } else {
        $years_combo = date('y').date('y', strtotime('+1 year'));
    }

    $data['cdc_number'] = "CDC-".$this->year_combo_num_gen(DB::table('fgs_cdc')->where('fgs_cdc.cdc_number', 'LIKE', 'CDC-'.$years_combo.'%')->count());
    $data['cdc_date'] = date('Y-m-d', strtotime($request->cdc_date));
    $data['remarks'] = $request->remarks;
    $data['stock_location_increase'] = $request->stock_location_increase;
    $data['product_category'] = $product_category;
    $data['new_product_category'] = $new_product_category;
    $data['customer_id'] = $customer_id;
    $data['created_by'] = config('user')['user_id'];
    $data['dc_id'] = $request->dc_number;
    $data['status'] = 1;
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    // Insert into fgs_cdc table
    $cdc_id = $this->fgs_cdc->insert_data($data);

    foreach ($ExcelOBJ->excelworksheet as $key => $excelsheet) {
        if ($key > 0 && $excelsheet[0]) {
            $product_id = DB::table('fgs_item_master')->where('sku_code', $excelsheet[0])->pluck('id')->first();
            $batchcard_id = DB::table('batchcard_batchcard')
                ->select('batchcard_batchcard.id')
                ->where('batch_no', '=', $excelsheet[1])
                ->pluck('id')
                ->first();

            // Check if product_id and batchcard_id exist
            if ($product_id && $batchcard_id) {
                // Check if the delivery_challan_item exists
                $delivery_challan_item = DB::table('delivery_challan_item')
                    ->where('product_id', $product_id)
                    ->where('batchcard_id', $batchcard_id)
                    ->first();

                // If the delivery_challan_item exists, insert the fgs_cdc_item
                if ($delivery_challan_item) {
                    // Insert the fgs_cdc_item with the dc_item_id
                    $item = [
                        'product_id' => $product_id,
                        'batchcard_id' => $batchcard_id,
                        'quantity' => $excelsheet[4],
                        'manufacturing_date' => ($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL,
                        'expiry_date' => ($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ' ',
                        'dc_item_id' => $delivery_challan_item->id,  // Use the id of the delivery_challan_item
                    ];
                    $item_id = DB::table('fgs_cdc_item')->insertGetId($item);

                    DB::table('fgs_cdc_item_rel')->insert([
                        'master' => $cdc_id,
                        'item' => $item_id
                    ]);

                    // Update or insert stock management
                  // Update or insert stock management
$product_stock = fgs_product_stock_management::where('product_id', $product_id)
->where('stock_location_id', $request->stock_location_increase)
->where('batchcard_id', $batchcard_id)
->where('manufacturing_date', ($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL)
->where('expiry_date', ($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ' ')
->first();

if (!empty($product_stock)) {
// If stock exists, update the quantity
$new_stock = $product_stock->quantity + $excelsheet[4];
$res[] = $this->fgs_product_stock_management->update_data(['id' => $product_stock->id], ['quantity' => $new_stock]);
} else {
// If stock does not exist, insert a new record
$stock = [
"product_id" => $product_id,
"batchcard_id" => $batchcard_id,
"quantity" => $excelsheet[4],
"stock_location_id" => $request->stock_location_increase,
'manufacturing_date' => ($excelsheet[2] != "") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[2]))->format('Y-m-d')) : NULL,
'expiry_date' => ($excelsheet[3] != "NA") ? (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($excelsheet[3]))->format('Y-m-d')) : ' ',
];
$this->fgs_product_stock_management->insert_data($stock);
}

                    // Now update the cdc_status of the corresponding delivery_challan_item
                    DB::table('delivery_challan_item')
                        ->where('id', $delivery_challan_item->id)  // Using the dc_item_id
                        ->update(['cdc_status' => 1]);  // Set cdc_status to 1
                }
            }
        }
    }

    return $item_id;
}


    

public function fetchCategories(Request $request)
{
    $customerId = $request->input('customer'); // Get the customer ID
    
    // Fetch the customer details
    $customer = DB::table('customer_supplier')->where('id', $customerId)->first();
    
    if ($customer) {
        return response()->json([
            'success' => true,
            'customer_id' => $customer,
            'billing_address' => $customer->billing_address, // Ensure this is correct
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Customer not found.']);
}


public function fetchCategoriesdc(Request $request)
{
    $dcNumber = $request->input('dc_number');
    // Fetch the delivery_challan record based on the selected dc_number
    $deliveryChallan = delivery_challan::where('id', $dcNumber)->first();
    if ($deliveryChallan) {
        // Fetch product categories based on the IDs from the deliveryChallan
        $productCategory = DB::table('fgs_product_category')->where('id', $deliveryChallan->product_category)->first();
        $newProductCategory = DB::table('fgs_product_category_new')->where('id', $deliveryChallan->new_product_category)->first();
        $customer = DB::table('customer_supplier')->where('id', $deliveryChallan->customer_id)->first();


        return response()->json([
            'success' => true,
            'product_category' => $productCategory, // Ensure you're getting the correct field name
            'new_product_category' => $newProductCategory,
            'customer_id' =>$customer,
'doc_date' => $deliveryChallan->doc_date,
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Invalid DC Number selected.']);
}



    




}

