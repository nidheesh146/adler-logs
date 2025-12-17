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
use App\Models\FGS\fgs_cdc;
use App\Models\FGS\fgs_cdc_item;
use App\Models\FGS\fgs_product_category;
use App\Models\FGS\product_stock_location;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\User;


use App\Models\PurchaseDetails\inv_supplier;


class CDCController extends Controller
{
    public function __construct()
    {
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
                            ->where('id', '=', $dc_item['id'])
                            ->update(['cdc_status' => 1]);
                        $update_qty = $dc_item['remaining_qty_after_cancel'] - $qty_to_cancel_array[$i];
                        $fgs_dc_item = delivery_challan_item::where('id', '=', $dc_item_id)
                            ->update(['remaining_qty_after_cancel' => $update_qty]);
                    } else {
                        $update_qty = $dc_item['remaining_qty_after_cancel'] - $qty_to_cancel_array[$i];
                        $fgs_dc_item = delivery_challan_item::where('id', '=', $dc_item_id)
                            ->update(['remaining_qty_after_cancel' => $update_qty]);
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
                        //print_r($dc_master);exit;
                        if ($dc_stock) {
                            $dc_stock_updation = $dc_stock['quantity'] - $qty_to_cancel_array[$i];
                            $update = $this->dc_transfer_stock->update_data(['id' => $dc_stock['dc_stock_id']], ['quantity' => $dc_stock_updation]);
                        }
                    }
                    $i++;
                }
                if ($cdc_id) {
                    $request->session()->flash('success', "You have successfully added a CDC !");
                    return redirect('fgs/CDC/CDC-list');
                } else {
                    $request->session()->flash('error', "CDC insertion is failed. Try again... !");
                    return redirect('FGS/CDC/CDC-add');
                }
            }

            if ($validator->errors()->all()) {
                return redirect('FGS/CDC/CDC-add')->withErrors($validator)->withInput();
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
            return view('pages/FGS/CDC/CDC-add', compact('data'));
        }
    }
    public function CDCItemList(Request $request, $id)
    {
        $condition = ['fgs_cdc_item_rel.master' =>$request->id];
        if($request->product)
        {
            $condition[] = ['product_product.sku_code','like', '%' . $request->product . '%'];
        }
        $items = $this->fgs_cdc_item->get_items($condition);
        //print_r($items);exit; 
       // echo $min_id;exit;
        return view('pages/FGS/CDC/CDC-item-list', compact('id','items'));
    }
    public function CDCpdf($id)
    {
        $data['cdc'] = $this->fgs_cdc->get_single_cdc(['fgs_cdc.id' => $id]);
        $data['item'] = $this->fgs_cdc_item->getItems(['fgs_cdc_item_rel.master' => $id]);
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
                <th ></th> 
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
                       <td ><input type="checkbox" name="dc_item_id[]" onclick="enableTextBox(this)" id="dc_item_id" value="' . $item->id . '"></td>
                       <td>' . $item->doc_no . '</td>
                       <td>' . $item->sku_code . '</td>
                       <td>' . $item->discription . '</td>
                       <td>' . $item->batch_qty . 'Nos</td>
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
}
