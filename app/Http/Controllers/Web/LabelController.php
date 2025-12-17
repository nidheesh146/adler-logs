<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Redirect;
use Picqer;
use Validator;
use App\Models\label_print_report;
use App\Models\FGS\fgs_grs;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_pi;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_dni;
use App\Models\FGS\fgs_dni_item;
use App\Models\FGS\fgs_dni_item_rel;
use App\Models\FGS\delivery_challan_item;
use App\Models\FGS\delivery_challan;
use App\Models\PurchaseDetails\customer_supplier;
use App\Models\batchcard;
use App\Models\product;
use App\Exports\PrintingReport;
use Maatwebsite\Excel\Facades\Excel;

class LabelController extends Controller
{
    public function __construct()
    {

        $this->fgs_grs = new fgs_grs;
        $this->fgs_grs_item = new fgs_grs_item;
        $this->fgs_pi = new fgs_pi;
        $this->fgs_pi_item = new fgs_pi_item;
        $this->fgs_pi_item_rel = new fgs_pi_item_rel;
        $this->fgs_dni = new fgs_dni;
        $this->fgs_dni_item = new fgs_dni_item;
        $this->fgs_dni_item_rel = new fgs_dni_item_rel;
        $this->delivery_challan = new delivery_challan;
        $this->delivery_challan_item = new delivery_challan_item;
        $this->customer_supplier = new customer_supplier;
    }
    public function batchcardSearch(Request $request)
    {
        $string = [];

        $batchcard = DB::table('batchcard_batchcard')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->select('batchcard_batchcard.id', 'batch_no')
            ->where('batchcard_batchcard.batch_no', 'LIKE', '%' . $request->q . '%')
            //->where('product_product.is_sterile','=', 1)
            ->get();
        //print_r($batchcard);exit;
        if (count($batchcard) > 0) {
            foreach ($batchcard  as $card) {
                $string[] = [
                    'id' => $card->id,
                    'text' => $card->batch_no
                ];
            }
            return response()->json($string, 200);
        } else {
            return response()->json(['message' => 'batch code is not valid'], 500);
        }
    }

    public function batchcardData($batch_no_id)
    {

        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.*', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batch_no_id)
            ->first();
        return response()->json($batchcard_data, 200);
    }

    public function instrumentLabel()
    {
        $title = "Create Instrument Label";
        return view('pages/label/label', compact('title'));
    }

    public function nonSterileProductLabel()
    {
        return view('pages/label/label');
    }
    public function nonSterileProductLabel2()
    {
        return view('pages/label/non-sterile-label2');
    }
    public function mrplabel()
    {
        return view('pages/label/mrp-label');
    }
    public function ahplMRPLabel()
    {
        return view('pages/label/ahpl-mrp-label');
    }
    public function ahplMRP1Label()
    {
        return view('pages/label/ahpl-mrp-label');
    }
    public function snnMRPLabel()
    {
        return view('pages/label/snn-mrp-label');
    }
    public function docAdlerMRPLabel()
    {
        return view('pages/label/doc-wise-adler-mrp-label');
    }
    public function docSNNMRPLabel()
    {
        return view('pages/label/doc-wise-snn-mrp-label');
    }
    public function docAHPLMRPLabel()
    {
        return view('pages/label/doc-wise-ahpl-mrp-label');
    }

    public function getDocNumbers(Request $request, $doc_type)
    {
        if ($doc_type) {
            if ($doc_type == 'GRS') {
                $condition[] = ['fgs_grs.status', '=', 1];
                $data = $this->fgs_grs->get_all_grs_for_label($condition);
            }
            if ($doc_type == 'PI') {
                $condition[] = ['fgs_pi.status', '=', 1];
                $data = $this->fgs_pi->get_all_pi_for_label($condition);
            }
            if ($doc_type == 'DNI') {
                $condition[] = ['fgs_dni.status', '=', 1];
                $data = $this->fgs_dni->get_all_dni_for_label($condition);
            }
            if ($doc_type == 'DC') {
                $condition[] = ['delivery_challan.status', '=', 1];
                $data = $this->delivery_challan->get_all_dc_for_label($condition);
            }

            if ($data)
                return $data;
            else
                return 0;
        }
    }

    public function getDocNumberInfo($doc_type, $doc_id)
    {
        if ($doc_type == 'GRS') {
            $grs = $this->fgs_grs->get_master_data(['fgs_grs.id' => $doc_id]);
            //return $grs;
            $grs_item = $this->fgs_grs_item->get_grs_item_for_label(['fgs_grs_item_rel.master' => $doc_id]);
            $data = '

                <div class="row">
                
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                    GRS Number (' . $grs->grs_number . ')
                        </label>
                    <div class="form-devider"></div>
                    </div>
                
                <table class="table table-bordered mg-b-0" style="padding-right: 15px;padding-left: 15px;">
                        <thead>
                        </thead>
                        <tbody >
                            <tr>
                                <th>GRS Date</th>
                                <td>' . date('d-m-Y', strtotime($grs
                ->grs_date)) . '</td>
                            </tr>
                            <tr >
                                    <th>Created Date</th>
                                    <td>' . date('d-m-Y', strtotime($grs
                ->created_at)) . '</td>
                                    
                            </tr>
                            <tr >
                                    <th>Stock Location1</th>
                                    <td>' . $grs->location_name1 . '</td>
                                    
                            </tr>
                            <tr>
                                    <th>Stock Location2</th>
                                    <td>' . $grs->location_name2 . '</td>
                                    
                            </tr>
                        </tbody>
                </table>
                </div>
                <br>
                    <div class="row" >
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';

            $data .= 'GRS Items ';
            $data .= '</label>
                        <div class="form-devider"></div>
                    </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered mg-b-0" id="example1"  style="padding-right: 15px;padding-left: 15px;">';

            $data .= '<thead>
                        <tr>
                        <th><input type="checkbox" class="item-select-radio  check_all"></th> 
                        <th>SKU CODE</th>
                        <th>Description</th>
                        <th>Batch NUMBER</th>
                        <th>Quantity per Pack</th>
                        <th> Qty</th>
                        <th>NO. Of LABEL Print &nbsp;<input type="number" name="label_print_count" class="label_print_count" disabled></th>
                        </tr>
                    </thead>
                    <tbody id="prbody1">';
            foreach ($grs_item as $item) {
                $data .= '<tr>
                            <td ><input type="checkbox" class="check_item" name="item_id[]" id="check_item" onclick="enableTextBox(this)" value="' . $item->id . '"></td>
                            <td>' . $item->sku_code . '</td>
                            <td>' . $item->discription . '</td>
                            <td>' . $item->batch_no . '</td>
                            <td>' . $item->quantity_per_pack . '</td>
                            <td>' . $item->batch_quantity . '</td>
                            <td><input type="number" class="qty_to_print" id="qty_to_print" name="qty_to_print[]" disabled   value="' . $item->batch_quantity / $item->quantity_per_pack . '" perPackqty="' . $item->quantity_per_pack . '" qty="' . $item->batch_quantity . '"></td>
                            
                            </tr>';
            }
            $data .= '</tbody>';
            $data .= '</table>
            </div>
                <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <input type="hidden" name="created_at" value=" ' . date('d-m-Y', strtotime($grs->created_at)) . ' ">
                                <input type="hidden" name="stock_location1" value="' . $grs->location_name1 . '">
                                <input type="hidden" name="stock_location2" value="' . $grs->location_name2 . '">
                                
                                </div>
                        </div>
                        <br>
                        <div class="row" >
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded grs-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                        Save 
                                    </button>
                                </div>
                            </div>
                           
                            ';
            $data .= '
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            var dataTable = $("#example1").dataTable({
                "bInfo":false,
                "ordering": false,
                "paging": false,
            });
        });
    </script>';
            return $data;
        }
        if ($doc_type == 'PI') {
            $pi = $this->fgs_pi->get_master_data(['fgs_pi.id' => $doc_id]);
            $pi_item = $this->fgs_pi_item->get_pi_item(['fgs_pi_item_rel.master' => $doc_id]);

            $data = '

                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                    PI number (' . $pi->pi_number . ')
                        </label>
                    <div class="form-devider"></div>
                    </div>
                
                    <table class="table table-bordered mg-b-0" style="padding-right: 15px;padding-left: 15px;">
                        <thead>
                        </thead>
                        <tbody >
                            <tr>
                                <th>Customer</th>
                                <td>' . $pi->firm_name . '</td>
                                <th>City, Zone, State</th>
                                <td>' . $pi->city . ' ,' . $pi->zone_name . ',' . $pi->state_name . '</td>
                            </tr>
                            <tr>
                                <th>PI Date</th>
                                <td>' . date('d-m-Y', strtotime($pi->pi_date)) . '</td>
                                <th>Created Date</th>
                                <td>' . date('d-m-Y', strtotime($pi->created_at)) . '</td>     
                            </tr>

                        </tbody>
                </table>
                </div>
                <br>
                    <div class="row" >
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';

            $data .= 'PI Items ';

            $data .= '</label>
                        <div class="form-devider"></div>
                    </div>
                    </div>
                <div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1"  style="padding-right: 15px;padding-left: 15px;">';
             $data .= '
                    <thead>
                    <tr>
                        <th ><input type="checkbox" class="item-select-radio  check_all"></th> 
                        <th>PRODUCT</th>
                        <th>DESCRIPTION</th>
                        <th>Quantity Per Pack</th>
                        <th>QUANTITY</th>
                        <th>No.of Label Print &nbsp;<input type="number" name="label_print_count" class="label_print_count" disabled></th>
                    </tr>
                    </thead>
                    <tbody id="prbody1">';
            foreach ($pi_item as $item) {
                if($item->quantity_per_pack==NULL)
                {
                    $item->quantity_per_pack = 1;
                }
                $data .= '
                        <tr>
                            <td ><input type="checkbox" class="check_item" name="item_id[]" onclick="enableTextBox(this)" id="check_item" value="' . $item->id . '"></td>
                            <td>' . $item->sku_code . '</td>
                            <td>' . $item->discription . '</td>
                            <td>' . $item->quantity_per_pack . '</td>
                            <td>' . $item->batch_qty . 'Nos</td>
                            <td><input type="number" class="qty_to_print" id="qty_to_print" name="qty_to_print[]" disabled value="' . $item->batch_qty / $item->quantity_per_pack . '" perPackqty="' . $item->quantity_per_pack . '" qty="' . $item->batch_qty . '"></td>
                        </tr>';
            }
            $data .= '</tbody>';
            $data .= '</table>
                </div>
               <div class="row">
                    <br>
                        <div class="row" >
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded invoice-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                        Print 
                                    </button>
                                </div>
                            </div>';
                            $data .= '
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
                            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
                            <script>
                                $(document).ready(function() {
                                    var dataTable = $("#example1").dataTable({
                                        "bInfo":false,
                                        "ordering": false,
                                        "paging": false,
                                    });
                                });
                            </script>';
            return $data;
        }
        if ($doc_type == 'DNI') {
            $condition1[] = ['fgs_dni.id', '=', $doc_id];
            $dni = $this->fgs_dni->get_single_dni($condition1);
            //return $invoice;
            $dni_items = fgs_dni_item_rel::select('fgs_dni_item.pi_id', 'fgs_pi.pi_number', 'fgs_pi.pi_date')
                ->leftJoin('fgs_dni_item', 'fgs_dni_item.id', 'fgs_dni_item_rel.item')
                ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_dni_item.pi_id')
                ->where('fgs_dni_item_rel.master', '=', $doc_id)
                ->distinct('fgs_dni_item_rel.id')
                ->get();
            foreach ($dni_items as $items) {
                $pi_item = fgs_pi_item_rel::select(
                    'fgs_grs.grs_number',
                    'fgs_grs.grs_date',
                    'product_product.sku_code',
                    'product_product.hsn_code',
                    'product_product.discription',
                    'product_product.quantity_per_pack',
                    'batchcard_batchcard.batch_no',
                    'fgs_grs_item.batch_quantity as quantity',
                    'fgs_oef_item.rate',
                    'fgs_oef_item.discount',
                    'currency_exchange_rate.currency_code',
                    'fgs_pi.pi_number',
                    'inventory_gst.igst',
                    'inventory_gst.cgst',
                    'inventory_gst.sgst',
                    'inventory_gst.id as gst_id',
                    'fgs_oef.oef_number',
                    'fgs_oef.oef_date',
                    'fgs_oef.order_number',
                    'fgs_oef.order_date',
                    'order_fulfil.order_fulfil_type',
                    'transaction_type.transaction_name',
                    'fgs_mrn_item.manufacturing_date',
                    'fgs_mrn_item.expiry_date',
                    'fgs_product_category.category_name',
                    'fgs_pi_item.remaining_qty_after_cancel',
                    'fgs_pi_item.id as pi_item_id',
                    'product_price_master.mrp',
                    'fgs_dni_item.id as dni_item_id',
                    'fgs_dni_item.remaining_qty_after_srn'
                )
                    ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_pi_item_rel.item')
                    ->leftJoin('fgs_dni_item', 'fgs_dni_item.pi_item_id', 'fgs_pi_item.id')
                    ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
                    ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
                    ->leftJoin('currency_exchange_rate', 'currency_exchange_rate.currency_id', '=', 'customer_supplier.currency')
                    ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
                    ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
                    ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
                    ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
                    ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                    ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
                    ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                    ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                    ->leftjoin('product_product', 'product_product.id', '=', 'fgs_grs_item.product_id')
                    ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'product_product.id')
                    ->leftjoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_pi_item.mrn_item_id')
                    ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
                    ->where('fgs_pi_item_rel.master', '=', $items['pi_id'])
                    ->where('fgs_grs.status', '=', 1)
                    ->where('fgs_pi_item.status', '=', 1)
                    ->where('fgs_pi_item.remaining_qty_after_cancel', '!=', 0)
                    ->where('fgs_dni_item.remaining_qty_after_srn', '!=', 0)
                    ->where('fgs_pi_item.cpi_status', '=', 0)
                    ->orderBy('fgs_grs_item.id', 'DESC')
                    ->distinct('fgs_dni_item.id')
                    ->get();
                $items['pi_item'] = $pi_item;
            }
            if ($dni_items && $dni) {
                $data = '<div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            DNI Number (' . $dni->dni_number . ')
                            </label>
                        <div class="form-devider"></div>
                    </div>
                    </div>
                    <table class="table table-bordered mg-b-0">
                            <thead>
                        
                            </thead>
                            <tbody>
                                <tr>
                                    <th>DNI Date</th>
                                    <td>' . date('d-m-Y', strtotime($dni->dni_date)) . '</td>
                                    <th>Customer</th>
                                    <td>' . $dni->firm_name . '</td>
                                    
                                </tr>
                                <tr>
                                    <th>Zone</th>
                                    <td>' . $dni->zone_name . '</td>
                                    <th>State</th>
                                    <td>' . $dni->state_name . '</td>
                                    
                                </tr>
                                <tr>
                                    <th>Billing Address</th>
                                    <td>' . $dni->billing_address . '</td>
                                    <th>Shipping Address</th>
                                    <td>' . $dni->shipping_address . '</td>
                                </tr>
                            </tbody>
                    </table>
                    <br>
                    <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
                //    foreach($dni_items as $dni_item)
                //    {
                $data .= '<div class="table-responsive">
                                    <table class="table table-bordered mg-b-0" id="example1">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="item-select-radio  check_all" id="check_all" ></th>
                                                <th>Product</th>
                                                <th>Description</th>
                                                <th>HSN Code</th>
                                                <th>Batchcard</th>
                                                <th>Quantity Per Pack</th>
                                                <th>Quantity</th>
                                                <th>Rate</th>
                                                <!--th>Discount</th-->
                                                <th>Net Value</th>
                                                <th>No.of Label Print &nbsp;<input type="number" name="label_print_count" class="label_print_count" disabled></th>
                                            </tr>
                                        </thead>
                                        <tbody id="prbody1">';
                $i = 1;
                foreach ($dni_items as $dni_item) {
                    foreach ($dni_item['pi_item'] as $item) {
                        $data .= '<tr>
                                                <td><input type="checkbox" class="check_item" name="item_id[]" onclick="enableTextBox(this)" id="check_item" value="' . $item['dni_item_id'] . '"></td>
                                                <td>' . $item['sku_code'] . '</td>
                                                <td>' . $item['discription'] . '</td>	
                                                <td>' . $item['hsn_code'] . '</td>
                                                <td>' . $item['batch_no'] . '</td>
                                                <td>' . $item['quantity_per_pack'] . '</td>
                                                <td class="qty">' . $item['remaining_qty_after_srn'] . ' Nos</td>
                                                <td>' . $item['rate'] . '  ' . $item['currency_code'] . '</td>
                                                <!--td>' . $item['discount'] . '%</td-->
                                                <td>' . ($item['rate'] * $item['remaining_qty_after_srn']) - (($item['remaining_qty_after_srn'] * $item['discount'] * $item['rate']) / 100) . ' ' . $item['currency_code'] . '</td>
                                                <td><input type="number" class="qty_to_print" id="qty_to_print" name="qty_to_print[]" disabled value="' . $item->remaining_qty_after_srn / $item->quantity_per_pack . '" perPackqty="' . $item->quantity_per_pack . '" qty="' . $item->remaining_qty_after_srn . '"></td>
                                            </tr>';
                    }
                }
                $data .= '</tbody>
                                        </table>
                                        <div class="box-footer clearfix">
                                        <br/>
                                        <div class="row" >
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                <button type="submit" class="btn btn-primary btn-rounded invoice-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                                    Print 
                                                </button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <br/>';
                //    }


                $data .= '</div>';
                $data .= '
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
                <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        var dataTable = $("#example1").dataTable({
                            "bInfo":false,
                            "ordering": false,
                            "paging": false,
                        });
                    });
                </script>';
                return $data;
            }
        }
        if ($doc_type == 'DC') {
            $dc = $this->delivery_challan->get_dc_data(['delivery_challan.id' => $doc_id]);
            //print_r($pi);exit;
            //return $invoice;
            $dc_item = $this->delivery_challan_item->get_dc_item(['delivery_challan_item_rel.master' => $doc_id]);
            //print_r($pi_item);exit;
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
                    </div>';
            $data .= '<div class="table-responsive">
                        <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <th ><input type="checkbox" class="item-select-radio  check_all"></th> 
                                <th>PRODUCT</th>
                                <th>DESCRIPTION</th>
                                <th>Quantity Per Pack</th>
                                <th>QUANTITY</th>
                                <th>NO. Of LABEL Print &nbsp;<input type="number" name="label_print_count" class="label_print_count" disabled></th>
                            </tr>
                        </thead>
                        <tbody id="prbody1">';
                    foreach ($dc_item as $item) {
                        $data .= '
                                <tr>
                                    <td ><input type="checkbox" class="check_item" name="item_id[]" onclick="enableTextBox(this)" id="check_item" value="' . $item->id . '"></td>
                               <td>' . $item->sku_code . '</td>
                               <td>' . $item->discription . '</td>
                               <td>' . $item->quantity_per_pack . 'Nos</td>
                               <td>' . $item->batch_qty . 'Nos</td>
                               <td><input type="number" class="qty_to_print" id="qty_to_print" name="qty_to_print[]" disabled value="' . $item->batch_qty / $item->quantity_per_pack . '"  perPackqty="' . $item->quantity_per_pack . '" qty="' . $item->batch_qty . '"></td>
                        </tr>';
            }
                    $data .= '</tbody>';
                    $data .= '</table>
                    <div class="box-footer clearfix">
                    <br/>
                    <div class="row" >
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded invoice-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                Print 
                            </button>
                        </div>
                    </div>
                    </div>
                </div>
                <br/>
                </div>';
        
                   $data .= '
                   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
                <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        var dataTable = $("#example1").dataTable({
                            "bInfo":false,
                            "ordering": false,
                            "paging": false,
                        });
                    });
                </script>';
                
          return $data;
        }
    }

    public function generateDocAdlerMRPLabel(Request $request)
    {
        $doc_type = $request->doc_type;
        $doc_id = $request->doc_id;
        if ($doc_type == 'GRS') {
            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $grs_item_id) {
                $item['item'] = $this->fgs_grs_item->getSingleItem_label(['fgs_grs_item.id' => $grs_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        if ($doc_type == 'PI') {
            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $pi_item_id) {
                $item['item'] = $this->fgs_pi_item->getSingleItem_label(['fgs_pi_item.id' => $pi_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        if ($doc_type == 'DNI') {
            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $dni_item_id) {
                $condition[] = ['fgs_dni_item.id', '=', $dni_item_id];
                $item['item'] = $this->fgs_dni_item->getSingleItem_label(['fgs_dni_item.id' => $dni_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        if ($doc_type == 'DC') {

            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $dc_item_id) {
                $item['item'] = $this->delivery_challan_item->getSingleItem_label(['delivery_challan_item.id' => $dc_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        //print_r(json_encode($print_item));exit;
        $pdf = PDF::loadView('pages.label.doc-wise-adler-mrp-print', compact('print_item', 'total_print_count'));
        $customPaper = array(0, 0, 362.1024, 850.39);
        $pdf->set_paper($customPaper, 'landscape');
        $file_name = "snn_mrp";
        return $pdf->stream($file_name . '.pdf');
        //print_r(json_encode($print_item));
        // if($print_item)
        // {
        //     return view('pages/label/doc-wise-snn-mrp-print', compact('print_item','total_print_count'));

        // } 
        // else
        // {
        //     return Redirect::back()->with('error', 'Batch Code & Sku code not matching..');
        // }
    }
    public function generateDocSNNMRPLabel(Request $request)
    {
        $doc_type = $request->doc_type;
        $doc_id = $request->doc_id;
        if ($doc_type == 'GRS') {
            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $grs_item_id) {
                $item['item'] = $this->fgs_grs_item->getSingleItem_label(['fgs_grs_item.id' => $grs_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        if ($doc_type == 'PI') {
            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $pi_item_id) {
                $item['item'] = $this->fgs_pi_item->getSingleItem_label(['fgs_pi_item.id' => $pi_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        if ($doc_type == 'DNI') {
            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $dni_item_id) {
                $condition[] = ['fgs_dni_item.id', '=', $dni_item_id];
                $item['item'] = $this->fgs_dni_item->getSingleItem_label(['fgs_dni_item.id' => $dni_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        if ($doc_type == 'DC') {

            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $dc_item_id) {
                $item['item'] = $this->delivery_challan_item->getSingleItem_label(['delivery_challan_item.id' => $dc_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        //print_r(json_encode($print_item));exit;
        $pdf = PDF::loadView('pages.label.doc-wise-snn-mrp-print', compact('print_item', 'total_print_count'));
        $customPaper = array(0, 0, 362.1024, 850.39);
        $pdf->set_paper($customPaper, 'landscape');
        $file_name = "snn_mrp";
        return $pdf->stream($file_name . '.pdf');
        //print_r(json_encode($print_item));
        // if($print_item)
        // {
        //     return view('pages/label/doc-wise-snn-mrp-print', compact('print_item','total_print_count'));

        // } 
        // else
        // {
        //     return Redirect::back()->with('error', 'Batch Code & Sku code not matching..');
        // }
    }
    public function mailingLabel()
    {
        return view('pages/label/mailing-label');
    }
    public function generateMailingLabel(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $validation['moreItems.*.customer'] = ['required'];
            $validation['moreItems.*.quantity'] = ['required'];
            $validator = Validator::make($request->all(), $validation);
            if (!$validator->errors()->all()) 
            {
                $i = 0;
                $total_print_count = 0;
                foreach ($request->moreItems as $key => $value) 
                {
                    $label['customer'] = $this->customer_supplier->get_single_customer_supplier(['customer_supplier.id'=>$value['customer']]);
                    $label['print_count'] = $value['quantity'];
                    $total_print_count = $total_print_count + $value['quantity'];
                    $print_item[] = $label;
                    $i++;
                }
                //print_r($print_item);exit;
                $pdf = PDF::loadView('pages.label.mailing-label-print', compact('print_item', 'total_print_count'));
                $pdf->set_paper('A4', 'portrait');
                $file_name = "mailing_label";
                return $pdf->stream($file_name . '.pdf');
            }
        }

    }
    public function generateDocAHPLMRPLabel(Request $request)
    {
        $doc_type = $request->doc_type;
        $doc_id = $request->doc_id;
        if ($doc_type == 'GRS') {
            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $grs_item_id) {
                $item['item'] = $this->fgs_grs_item->getSingleItem(['fgs_grs_item.id' => $grs_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        if ($doc_type == 'PI') {
            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $pi_item_id) {
                $item['item'] = $this->fgs_pi_item->getSingleItem(['fgs_pi_item.id' => $pi_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        if ($doc_type == 'DNI') {
            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $dni_item_id) {
                $condition[] = ['fgs_dni_item.id', '=', $dni_item_id];
                $item['item'] = fgs_dni_item::select(
                    'fgs_dni_item.*',
                    'fgs_dni.dni_number',
                    'fgs_dni.dni_date',
                    'fgs_pi.pi_number',
                    'fgs_pi.pi_date',
                    'fgs_grs.grs_number',
                    'fgs_grs.grs_date',
                    'fgs_pi_item.remaining_qty_after_cancel',
                    'product_product.sku_code',
                    'product_product.discription',
                    'product_product.hsn_code',
                    'product_product.drug_license_number',
                    'batchcard_batchcard.batch_no',
                    'fgs_mrn_item.manufacturing_date',
                    'fgs_mrn_item.expiry_date',
                    'fgs_oef.oef_number',
                    'fgs_oef.oef_date',
                    'fgs_oef_item.rate',
                    'fgs_oef_item.discount',
                    'inventory_gst.igst',
                    'inventory_gst.cgst',
                    'inventory_gst.sgst',
                    'inventory_gst.id as gst_id',
                    'fgs_oef.order_number',
                    'fgs_oef.order_date',
                    'order_fulfil.order_fulfil_type',
                    'transaction_type.transaction_name',
                    'fgs_product_category.category_name',
                    'customer_supplier.firm_name',
                    'customer_supplier.shipping_address',
                    'customer_supplier.billing_address',
                    'zone.zone_name'
                )
                    ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
                    ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
                    ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_dni.customer_id')
                    ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_dni_item.pi_id')
                    ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
                    ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
                    ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
                    ->leftjoin('product_product', 'product_product.id', '=', 'fgs_pi_item.product_id')
                    ->leftjoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_pi_item.mrn_item_id')
                    ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
                    ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
                    ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                    ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
                    ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                    ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                    ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
                    ->leftJoin('zone', 'zone.id', 'customer_supplier.zone')
                    ->where($condition)
                    ->where('fgs_pi_item.cpi_status', '=', 0)
                    ->first();
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        if ($doc_type == 'DC') {

            $i = 0;
            $total_print_count = 0;
            foreach ($request->item_id as $dc_item_id) {
                $item['item'] = $this->delivery_challan_item->getSingleItem(['delivery_challan_item.id' => $dc_item_id]);
                $item['print_count'] = $request->qty_to_print[$i];
                $total_print_count = $total_print_count + $request->qty_to_print[$i];
                $print_item[] = $item;
                $i++;
            }
        }
        //print_r(json_encode($print_item));exit;
        $pdf = PDF::loadView('pages.label.doc-wise-ahpl-mrp-print', compact('print_item', 'total_print_count'));
        $pdf->set_paper('A4', 'portrait');
        $file_name = "snn_mrp";
        return $pdf->stream($file_name . '.pdf');
        //print_r(json_encode($print_item));
        // if($print_item)
        // {
        //     return view('pages/label/doc-wise-snn-mrp-print', compact('print_item','total_print_count'));

        // } 
        // else
        // {
        //     return Redirect::back()->with('error', 'Batch Code & Sku code not matching..');
        // }
    }

    public function sterilizationProductLabel()
    {
        return view('pages/label/sterilization-product-label');
    }
    public function patientLabel()
    {
        $title = "Create Patient Label ";
        return view('pages/label/sterilization-product-label', compact('title'));
    }

    public function generatePatientLabel(Request $request)
    {
        // $is_sterile = $this->check_label_type($request->batchcard_no);
        // if($is_sterile==0)
        // {
        //     $request->session()->flash('error', "This is non-sterile product batchcard.Try with sterile product batchcard...");
        //     return redirect('label/patient-label');
        // }
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        // if($batchcard_data->label_format_number!=10)
        // {
        //     $request->session()->flash('error', "This is not a patient label batchcard.Try with patient label batchcard...");
        //     return redirect('label/patient-label');
        // }

        $no_of_label = $request->no_of_label;
        $lot_no = $request->sterilization_lot_no;
        $per_pack_quantity = $request->per_pack_quantity;
        $manufacture_date = $request->manufacturing_date;
        $sterilization_expiry_date = $request->sterilization_expiry_date;
        return view('pages/label/patient-label-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
        //Redirect::away('label/print/patient-label');
    }

    public function getBatchcard($sku_code)
    {
        $batchcard_no = DB::table('product_product')
            ->leftJoin('batchcard_batchcard', 'product_product.id', '=', 'batchcard_batchcard.product_id')
            ->where('product_product.sku_code', '=', $sku_code)
            ->pluck('batchcard_batchcard.batch_no')
            ->first();
        //return $batchcard_no;
        return response()->json($batchcard_no, 200);
    }

    public function generateMRPLabel(Request $request)
    {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $sku_code = $request->sku_code;
        $product = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'product_product.sku_code',
            //  'product_price_master.mrp',
            'product_price_master.mrp',
              'product_product.label_format_number', 'product_product.drug_license_number', 'batchcard_batchcard.start_date')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->leftJoin('product_price_master', 'product_price_master.product_id', '=', 'product_product.id')
            ->where('product_product.sku_code', '=', $sku_code)
            ->where('batchcard_batchcard.batch_no', '=', $batcard_no)
            ->first();
        if ($product) {
            return view('pages/label/mrp-label-print', compact('product', 'no_of_label'));
        } else {
            return Redirect::back()->with('error', 'Batch Code & Sku code not matching..');
        }
    }
    public function generateAHPLMRPLabel(Request $request)
    {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $sku_code = $request->sku_code;
        $product = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'product_product.sku_code', 'product_price_master.mrp', 'product_product.label_format_number', 'product_product.drug_license_number', 'batchcard_batchcard.start_date')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->leftJoin('product_price_master', 'product_price_master.product_id', '=', 'product_product.id')
            ->where('product_product.sku_code', '=', $sku_code)
            ->where('batchcard_batchcard.batch_no', '=', $batcard_no)
            ->first();
        if ($product) {
            $pdf = PDF::loadView('pages.label.ahpl-mrp-print', compact('product', 'no_of_label'));
            $customPaper = array(0, 0, 362.1024, 850.39);
            $pdf->set_paper($customPaper, 'landscape');
            $file_name = "ahpl_mrp";
            return $pdf->stream($file_name . '.pdf');
        } else {
            return Redirect::back()->with('error', 'Batch Code & Sku code not matching..');
        }
    }
    public function generateAHPLMRP1Label(Request $request)
    {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $sku_code = $request->sku_code;
        $product = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'product_product.sku_code', 'product_price_master.mrp', 'product_product.label_format_number', 'product_product.drug_license_number', 'batchcard_batchcard.start_date')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->leftJoin('product_price_master', 'product_price_master.product_id', '=', 'product_product.id')
            ->where('product_product.sku_code', '=', $sku_code)
            ->where('batchcard_batchcard.batch_no', '=', $batcard_no)
            ->first();
        if ($product) {
            return view('pages/label/ahpl-mrp-print', compact('product', 'no_of_label'));
        } else {
            return Redirect::back()->with('error', 'Batch Code & Sku code not matching..');
        }
    }
    public function generateSNNMRPLabel(Request $request)
    {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $sku_code = $request->sku_code;
        $product = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'product_product.sku_code', 'product_price_master.mrp', 'product_product.label_format_number', 'product_product.drug_license_number', 'batchcard_batchcard.start_date')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->leftJoin('product_price_master', 'product_price_master.product_id', '=', 'product_product.id')
            ->where('product_product.sku_code', '=', $sku_code)
            ->where('batchcard_batchcard.batch_no', '=', $batcard_no)
            ->first();
        if ($product) {
            return view('pages/label/snn-mrp-print', compact('product', 'no_of_label'));
        } else {
            return Redirect::back()->with('error', 'Batch Code & Sku code not matching..');
        }
    }

    public function generateSterilizationProductLabel(Request $request)
    {
        // $is_sterile = $this->check_label_type($request->batchcard_no);
        // if($is_sterile==0)
        // {
        //     $request->session()->flash('error', "This is non-sterile product batchcard.Try with sterile product batchcard...");
        //     return redirect('label/sterilization-label');
        // }
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        if (($batchcard_data->label_format_number != 02) && ($batchcard_data->label_format_number != 04)) {
            $request->session()->flash('error', "This is not a sterilization label batchcard.Try with sterilization label batchcard...");
            return redirect('label/sterilization-label');
        }

        $no_of_label = $request->no_of_label;
        $lot_no = $request->sterilization_lot_no;
        $manufacture_date = $request->manufacturing_date;
        $sterilization_expiry_date = $request->sterilization_expiry_date;
        $per_pack_quantity = $request->per_pack_quantity;

        $color = [0, 0, 0];
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128, 1, 40, $color);
        // $gs1_code_barcode = $generator->getBarcode($batchcard_data->gs1_code, $generator::TYPE_CODE_128, 1,70, $color );

        return view('pages/label/sterilization-label-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'sku_code_barcode', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
    }
    public function generateNonSterileProductLabel(Request $request)
    {

        // $is_sterile = $this->check_label_type($request->batchcard_no);
        // if($is_sterile==1)
        // {
        //     $request->session()->flash('error', "This is sterile product batchcard.Try with non-sterile product batchcard...");
        //     return redirect('label/non-sterile-product-label');
        // }
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        if ($batchcard_data->label_format_number != 16) {
            $request->session()->flash('error', "This is not a non-sterilization label batchcard.Try with non-sterilization label batchcard...");
            return redirect('label/non-sterile-product-label');
        }
        $no_of_label = $request->no_of_label;
        $manufacturing_date = $request->manufacturing_date;
        $per_pack_quantity = $request->per_pack_quantity;

        $color = [0, 0, 0];
        $manf_date_combo = '[11]' . date('Y-m-d', strtotime($request->manufacturing_date));
        // $gs1_label_batch_combo ='[01]' .$batchcard_data->gs1_code.'[10]'.$batchcard_data->batch_no;
        $label_batch_combo = '[10]' . $batchcard_data->batch_no;
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
        $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1, 45, $color);
        //$gs1_label_batch_combo_barcode = $generator->getBarcode($gs1_label_batch_combo, $generator::TYPE_CODE_128);
        $label_batch_combo_barcode = $generator->getBarcode($label_batch_combo, $generator::TYPE_CODE_128);
        return view('pages/label/non-sterilization-label-print', compact('batchcard_data', 'no_of_label', 'sku_code_barcode', 'label_batch_combo', 'label_batch_combo_barcode', 'manf_date_combo', 'manf_date_combo_barcode', 'manufacturing_date', 'per_pack_quantity'));
    }
    public function generateNonSterileProductLabel2(Request $request)
    {
//dd('hi');
        // $is_sterile = $this->check_label_type($request->batchcard_no);
        // if($is_sterile==1)
        // {
        //     $request->session()->flash('error', "This is sterile product batchcard.Try with non-sterile product batchcard...");
        //     return redirect('label/non-sterile-product-label');
        // }
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        if ($batchcard_data->label_format_number != 19) {
            $request->session()->flash('error', "This is not a non-sterilization label batchcard.Try with non-sterilization label batchcard...");
            return redirect('label/non-sterile-product-label2');
        }
        $mrp = DB::table('product_price_master')
        ->where('product_id', $batchcard_data->product_id)
        ->value('mrp');
        $no_of_label = $request->no_of_label;
        $manufacturing_date = $request->manufacturing_date;
        $per_pack_quantity = $request->per_pack_quantity;

        $color = [0, 0, 0];
        $manf_date_combo = '[11]' . date('Y-m-d', strtotime($request->manufacturing_date));
        // $gs1_label_batch_combo ='[01]' .$batchcard_data->gs1_code.'[10]'.$batchcard_data->batch_no;
        $label_batch_combo = '[10]' . $batchcard_data->batch_no;
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
        $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1, 55, $color);
        //$gs1_label_batch_combo_barcode = $generator->getBarcode($gs1_label_batch_combo, $generator::TYPE_CODE_128);
        $label_batch_combo_barcode = $generator->getBarcode($label_batch_combo, $generator::TYPE_CODE_128);
        return view('pages/samplelabel/new-non-sterilization-label-print', compact('batchcard_data', 'no_of_label', 'sku_code_barcode', 'label_batch_combo', 'label_batch_combo_barcode', 'manf_date_combo', 'manf_date_combo_barcode', 'manufacturing_date', 'per_pack_quantity','mrp'));
    }

    public function generateInstrumentLabel(Request $request)
    {
        /* $is_sterile = $this->check_label_type($request->batchcard_no);
        if($is_sterile==1)
        {
            $request->session()->flash('error', "This is sterile product batchcard.Try with non-sterile product batchcard...");
            return redirect('label/instrument-label');
        }*/
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        if ($batchcard_data->label_format_number != 17) {
            $request->session()->flash('error', "This is not a instrument label batchcard.Try with instrument label batchcard...");
            return redirect('label/instrument-label');
        }
        $no_of_label = $request->no_of_label;
        $per_pack_quantity = $request->per_pack_quantity;
        $manufacturing_date = $request->manufacturing_date;

        $color = [0, 0, 0];
        $manf_date_combo = '[11]' . date('Y-m-d', strtotime($request->manufacturing_date));
        // $gs1_label_batch_combo = '[01]'.$batchcard_data->gs1_code.'[10]'.$batchcard_data->batch_no;
        $label_batch_combo = '[10]' . $batchcard_data->batch_no;
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
        $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1, 45, $color);
        //$gs1_label_batch_combo_barcode = $generator->getBarcode($gs1_label_batch_combo, $generator::TYPE_CODE_128);
        $label_batch_combo_barcode = $generator->getBarcode($label_batch_combo, $generator::TYPE_CODE_128);
        return view('pages/label/instrument-label-print', compact('batchcard_data', 'no_of_label', 'sku_code_barcode', 'label_batch_combo', 'label_batch_combo_barcode', 'manf_date_combo', 'manf_date_combo_barcode', 'manufacturing_date', 'per_pack_quantity'));
    }

    public function check_label_type($batch_card)
    {
        $prdt_id =  DB::table('batchcard_batchcard')->where('id', '=', $batch_card)->pluck('product_id')->first();
        $is_sterile =  DB::table('product_product')->where('id', '=', $prdt_id)->pluck('is_sterile')->first();
        //echo  $is_sterile;exit;
        return $is_sterile;
    }

    public function printingReport(Request $request)
    {
        $condition = [];
        if ($request->batchcard) {
            $condition[] = ['batchcard_batchcard.batch_no', 'LIKE', '%' . $request->batchcard . '%'];
        }
        if ($request->label) {
            $condition[] = ['label_print_report.label_name', 'LIKE', '%' . $request->label . '%'];
        }
        if ($request->manufaturing_from) {
            $condition[] = ['label_print_report.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $request->manufaturing_from))];
            $condition[] = ['label_print_report.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $request->manufaturing_from))];
        }
        $data['labels'] = label_print_report::select('label_print_report.*', 'batchcard_batchcard.batch_no', 'product_product.sku_code')
            ->leftJoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'label_print_report.batchcard')
            ->leftJoin('product_product', 'product_product.id', '=', 'label_print_report.product_id')
            ->where($condition)
            ->orderby('label_print_report.id', 'desc')
            ->paginate(10);

        return view('pages/label/print-report', compact('data'));
    }
    public function insertPrintingData(Request $request)
    {
        $success = label_print_report::insert(
            [
                'batchcard' => $request->batch_id,
                'no_of_labels_printed' => $request->no_of_labels,
                'manufacturing_date' => date('Y-m-d', strtotime($request->manufacturing_date)),
                'product_id' => $request->product_id,
                'expiry_date' => $request->expiry_date,
                'label_name' => $request->label_name
            ]
        );
        if ($success)
            return 1;
        else
            return 0;
    }
    public function exportPrintingReport(Request $request)
    {
        $batchcard = $request->batchcard;
        $label = $request->label;
        $manufaturing_from = $request->manufaturing_from;
        return Excel::download(new PrintingReport($batchcard, $label, $manufaturing_from), 'LabelPrintingReport' . date('d-m-Y') . '.xlsx');
    }
    public function docWiseComparison()
    {
        return view('pages/label/doc-wise-item-comparison');
    }
    public function docNumberInfoForComparison($doc_type, $doc_id)
    {
        if ($doc_type == 'GRS') {
            $grs = $this->fgs_grs->get_master_data(['fgs_grs.id' => $doc_id]);
            //return $grs;
            $grs_item = $this->fgs_grs_item->get_grs_item_for_label(['fgs_grs_item_rel.master' => $doc_id]);
            $data = '

                <div class="row">
                
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                    GRS Number (' . $grs->grs_number . ')
                        </label>
                    <div class="form-devider"></div>
                    </div>
                
                <table class="table table-bordered mg-b-0" style="padding-right: 15px;padding-left: 15px;">
                        <thead>
                        </thead>
                        <tbody >
                            <tr>
                                <th>GRS Date</th>
                                <td>' . date('d-m-Y', strtotime($grs
                ->grs_date)) . '</td>
                            </tr>
                            <tr >
                                    <th>Created Date</th>
                                    <td>' . date('d-m-Y', strtotime($grs
                ->created_at)) . '</td>
                                    
                            </tr>
                            <tr >
                                    <th>Stock Location1</th>
                                    <td>' . $grs->location_name1 . '</td>
                                    
                            </tr>
                            <tr>
                                    <th>Stock Location2</th>
                                    <td>' . $grs->location_name2 . '</td>
                                    
                            </tr>
                        </tbody>
                </table>
                </div>
                <br>
                    <div class="row" >
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';

            $data .= 'GRS Items ';
            $data .= '</label>
                        <div class="form-devider"></div>
                    </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered mg-b-0" id="example1"  style="padding-right: 15px;padding-left: 15px;">';
                   
            $data .= '<thead>
                        Barcode :<input type="text" name="form-control scanned_code" class="scanned_code" id="scanned_code">
                        <tr>
                        <th></th> 
                        <th>SKU CODE</th>
                        <th>Description</th>
                        <th>Batch NUMBER</th>
                        <th>Quantity per Pack</th>
                        <th> Qty</th>
                        <th>NO. Of LABEL Print &nbsp;</th>
                        <th>Scanned Item Count</th>
                        </tr>
                    </thead>
                    <tbody id="prbody1">';
                    $i=1;
            foreach ($grs_item as $item) {
                $data .= '<tr>
                            <td >'.$i++.'</td>
                            <td>' . $item->sku_code . '</td>
                            <td>' . $item->discription . '</td>
                            <td>' . $item->batch_no . '</td>
                            <td>' . $item->quantity_per_pack . '</td>
                            <td>' . $item->batch_quantity . '</td>
                            <td><input type="number" class="qty_to_print" id="qty_to_print" name="qty_to_print[]" disabled   value="' . $item->batch_quantity / $item->quantity_per_pack . '" perPackqty="' . $item->quantity_per_pack . '" qty="' . $item->batch_quantity . '"></td>
                            <td><input type="number" readonly class="scanned_count '. str_replace('.','_',$item->sku_code) .' " id="scanned_count" name="scanned_count" value="0">
                            <span class="error-span" style="display:none;color:red;">Quantity Mismatch..</span></td>
                            </tr>';
            }
            $data .= '</tbody>';
            $data .= '</table>
            </div> 
                <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <input type="hidden" name="created_at" value=" ' . date('d-m-Y', strtotime($grs->created_at)) . ' ">
                                <input type="hidden" name="stock_location1" value="' . $grs->location_name1 . '">
                                <input type="hidden" name="stock_location2" value="' . $grs->location_name2 . '">
                                
                                </div>
                        </div>
                        <br>
                        <div class="row" >
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button class="btn btn-primary btn-rounded compare-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                        Compare 
                                    </button>
                                </div>
                            </div>
                           
                            ';
            $data .= '
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            var dataTable = $("#example1").dataTable({
                "bInfo":false,
                "ordering": false,
                "paging": false,
            });
        });
    </script>';
            return $data;
        }
        if ($doc_type == 'PI') {
            $pi = $this->fgs_pi->get_master_data(['fgs_pi.id' => $doc_id]);
            $pi_item = $this->fgs_pi_item->get_pi_item(['fgs_pi_item_rel.master' => $doc_id]);

            $data = '

                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                    PI number (' . $pi->pi_number . ')
                        </label>
                    <div class="form-devider"></div>
                    </div>
                
                    <table class="table table-bordered mg-b-0" style="padding-right: 15px;padding-left: 15px;">
                        <thead>
                        </thead>
                        <tbody >
                            <tr>
                                <th>Customer</th>
                                <td>' . $pi->firm_name . '</td>
                                <th>City, Zone, State</th>
                                <td>' . $pi->city . ' ,' . $pi->zone_name . ',' . $pi->state_name . '</td>
                            </tr>
                            <tr>
                                <th>PI Date</th>
                                <td>' . date('d-m-Y', strtotime($pi->pi_date)) . '</td>
                                <th>Created Date</th>
                                <td>' . date('d-m-Y', strtotime($pi->created_at)) . '</td>     
                            </tr>

                        </tbody>
                </table>
                </div>
                <br>
                    <div class="row" >
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';

            $data .= 'PI Items ';

            $data .= '</label>
                        <div class="form-devider"></div>
                    </div>
                    </div>
                <div class="table-responsive">
                Barcode :<input type="text" name="form-control scanned_code" class="scanned_code" id="scanned_code">
                <table class="table table-bordered mg-b-0" id="example1"  style="padding-right: 15px;padding-left: 15px;">';
             $data .= '
                    <thead>
                    <tr>
                        <th ></th> 
                        <th>PRODUCT</th>
                        <th>DESCRIPTION</th>
                        <th>Batch Number</th>
                        <th>Quantity Per Pack</th>
                        <th>QUANTITY</th>
                        <th>No.of Label Print &nbsp;</th>
                        <th>Scanned Item Count</th>
                    </tr>
                    </thead>
                    <tbody id="prbody1">';
                    $i=1;
            foreach ($pi_item as $item) {
                $data .= '
                        <tr>
                            <td >'.$i++.'</td>
                            <td>' . $item->sku_code . '</td>
                            <td>' . $item->discription . '</td>
                            <td>'. $item->batch_no.'</td>
                            <td>' . $item->quantity_per_pack . '</td>
                            <td>' . $item->batch_qty . 'Nos</td>
                            <td><input type="number" class="qty_to_print" id="qty_to_print" name="qty_to_print[]" disabled value="' . $item->batch_qty / $item->quantity_per_pack . '" perPackqty="' . $item->quantity_per_pack . '" qty="' . $item->batch_qty . '"></td>
                            <td><input type="number" readonly class="scanned_count '. str_replace('.','_',$item->sku_code) .'" id="scanned_count" name="scanned_count" value="0">
                            <span class="error-span" style="display:none;color:red;">Quantity Mismatch..</span></td>
                        </tr>';
            }
            $data .= '</tbody>';
            $data .= '</table>
                </div>
               <div class="row">
                    <br>
                        <div class="row" >
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button  class="btn btn-primary btn-rounded compare-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                        Compare 
                                    </button>
                                </div>
                            </div>';
                            $data .= '
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
                            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
                            <script>
                                $(document).ready(function() {
                                    var dataTable = $("#example1").dataTable({
                                        "bInfo":false,
                                        "ordering": false,
                                        "paging": false,
                                    });
                                });
                            </script>';
            return $data;
        }
        if ($doc_type == 'DNI') {
            $condition1[] = ['fgs_dni.id', '=', $doc_id];
            $dni = $this->fgs_dni->get_single_dni($condition1);
            //return $invoice;
            $dni_items = fgs_dni_item_rel::select('fgs_dni_item.pi_id', 'fgs_pi.pi_number', 'fgs_pi.pi_date')
                ->leftJoin('fgs_dni_item', 'fgs_dni_item.id', 'fgs_dni_item_rel.item')
                ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_dni_item.pi_id')
                ->where('fgs_dni_item_rel.master', '=', $doc_id)
                ->distinct('fgs_dni_item_rel.id')
                ->get();
            foreach ($dni_items as $items) {
                $pi_item = fgs_pi_item_rel::select(
                    'fgs_grs.grs_number',
                    'fgs_grs.grs_date',
                    'product_product.sku_code',
                    'product_product.hsn_code',
                    'product_product.discription',
                    'product_product.quantity_per_pack',
                    'batchcard_batchcard.batch_no',
                    'fgs_grs_item.batch_quantity as quantity',
                    'fgs_oef_item.rate',
                    'fgs_oef_item.discount',
                    'currency_exchange_rate.currency_code',
                    'fgs_pi.pi_number',
                    'inventory_gst.igst',
                    'inventory_gst.cgst',
                    'inventory_gst.sgst',
                    'inventory_gst.id as gst_id',
                    'fgs_oef.oef_number',
                    'fgs_oef.oef_date',
                    'fgs_oef.order_number',
                    'fgs_oef.order_date',
                    'order_fulfil.order_fulfil_type',
                    'transaction_type.transaction_name',
                    'fgs_mrn_item.manufacturing_date',
                    'fgs_mrn_item.expiry_date',
                    'fgs_product_category.category_name',
                    'fgs_pi_item.remaining_qty_after_cancel',
                    'fgs_pi_item.id as pi_item_id',
                    'product_price_master.mrp',
                    'fgs_dni_item.id as dni_item_id',
                    'fgs_dni_item.remaining_qty_after_srn'
                )
                    ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_pi_item_rel.item')
                    ->leftJoin('fgs_dni_item', 'fgs_dni_item.pi_item_id', 'fgs_pi_item.id')
                    ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
                    ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
                    ->leftJoin('currency_exchange_rate', 'currency_exchange_rate.currency_id', '=', 'customer_supplier.currency')
                    ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
                    ->leftJoin('fgs_product_category', 'fgs_product_category.id', 'fgs_grs.product_category')
                    ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
                    ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
                    ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                    ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
                    ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                    ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                    ->leftjoin('product_product', 'product_product.id', '=', 'fgs_grs_item.product_id')
                    ->leftjoin('product_price_master', 'product_price_master.product_id', '=', 'product_product.id')
                    ->leftjoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_pi_item.mrn_item_id')
                    ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_pi_item.batchcard_id')
                    ->where('fgs_pi_item_rel.master', '=', $items['pi_id'])
                    ->where('fgs_grs.status', '=', 1)
                    ->where('fgs_pi_item.status', '=', 1)
                    ->where('fgs_pi_item.remaining_qty_after_cancel', '!=', 0)
                    ->where('fgs_dni_item.remaining_qty_after_srn', '!=', 0)
                    ->where('fgs_pi_item.cpi_status', '=', 0)
                    ->orderBy('fgs_grs_item.id', 'DESC')
                    ->distinct('fgs_dni_item.id')
                    ->get();
                $items['pi_item'] = $pi_item;
            }
            if ($dni_items && $dni) {
                $data = '<div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            DNI Number (' . $dni->dni_number . ')
                            </label>
                        <div class="form-devider"></div>
                    </div>
                    </div>
                    <table class="table table-bordered mg-b-0">
                            <thead>
                        
                            </thead>
                            <tbody>
                                <tr>
                                    <th>DNI Date</th>
                                    <td>' . date('d-m-Y', strtotime($dni->dni_date)) . '</td>
                                    <th>Customer</th>
                                    <td>' . $dni->firm_name . '</td>
                                    
                                </tr>
                                <tr>
                                    <th>Zone</th>
                                    <td>' . $dni->zone_name . '</td>
                                    <th>State</th>
                                    <td>' . $dni->state_name . '</td>
                                    
                                </tr>
                                <tr>
                                    <th>Billing Address</th>
                                    <td>' . $dni->billing_address . '</td>
                                    <th>Shipping Address</th>
                                    <td>' . $dni->shipping_address . '</td>
                                </tr>
                            </tbody>
                    </table>
                    <br>
                    <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">';
                //    foreach($dni_items as $dni_item)
                //    {
                $data .= '<div class="table-responsive">
                            Barcode :<input type="text" name="form-control scanned_code" class="scanned_code" id="scanned_code">
                                    <table class="table table-bordered mg-b-0" id="example1">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Product</th>
                                                <th>Description</th>
                                                <th>HSN Code</th>
                                                <th>Batchcard</th>
                                                <th>Quantity Per Pack</th>
                                                <th>Quantity</th>
                                                <th>No.of Label Print &nbsp;</th>
                                                <th>Scanned Item Count</th>
                                            </tr>
                                        </thead>
                                        <tbody id="prbody1">';
                $i = 1;
                foreach ($dni_items as $dni_item) {
                    foreach ($dni_item['pi_item'] as $item) {
                        $data .= '<tr>
                                                <td>'.$i++.'</td>
                                                <td>' . $item['sku_code'] . '</td>
                                                <td>' . $item['discription'] . '</td>	
                                                <td>' . $item['hsn_code'] . '</td>
                                                <td>' . $item['batch_no'] . '</td>
                                                <td>' . $item['quantity_per_pack'] . '</td>
                                                <td class="qty">' . $item['remaining_qty_after_srn'] . ' Nos</td>
                                                <td><input type="number" class="qty_to_print" id="qty_to_print" name="qty_to_print[]" disabled value="' . $item->remaining_qty_after_srn / $item->quantity_per_pack . '" perPackqty="' . $item->quantity_per_pack . '" qty="' . $item->remaining_qty_after_srn . '"></td>
                                                <td><input type="number" readonly class="scanned_count '. str_replace('.','_',$item->sku_code) .'" id="scanned_count" name="scanned_count" value="0">
                                                <span class="error-span" style="display:none;color:red;">Quantity Mismatch..</span></td>
                                            </tr>';
                    }
                }
                $data .= '</tbody>
                                        </table>
                                        <div class="box-footer clearfix">
                                        <br/>
                                        <div class="row" >
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                <button class="btn btn-primary btn-rounded compare-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                                    Compare 
                                                </button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <br/>';
                //    }


                $data .= '</div>';
                $data .= '
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
                <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        var dataTable = $("#example1").dataTable({
                            "bInfo":false,
                            "ordering": false,
                            "paging": false,
                        });
                    });
                </script>';
                return $data;
            }
        }
        if ($doc_type == 'DC') {
            $dc = $this->delivery_challan->get_dc_data(['delivery_challan.id' => $doc_id]);
            //print_r($pi);exit;
            //return $invoice;
            $dc_item = $this->delivery_challan_item->get_dc_item(['delivery_challan_item_rel.master' => $doc_id]);
            //print_r($pi_item);exit;
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
                    </div>';
            $data .= '<div class="table-responsive">
                        Barcode :<input type="text" name="form-control scanned_code" class="scanned_code" id="scanned_code">
                        <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <th ></th> 
                                <th>PRODUCT</th>
                                <th>DESCRIPTION</th>
                                <th>Batch Number</th>
                                <th>Quantity Per Pack</th>
                                <th>QUANTITY</th>
                                <th>NO. Of LABEL Print &nbsp;</th>
                                <th>Scanned Item Count</th>
                            </tr>
                        </thead>
                        <tbody id="prbody1">';
                        $i=1;
                    foreach ($dc_item as $item) {
                        $data .= '
                                <tr>
                                    <td >'.$i++.'</td>
                               <td>' . $item->sku_code . '</td>
                               <td>' . $item->discription . '</td>
                               <td>' .$item->batch_no. '</td>
                               <td>' . $item->quantity_per_pack . 'Nos</td>
                               <td>' . $item->batch_qty . 'Nos</td>
                               <td><input type="number" class="qty_to_print" id="qty_to_print" name="qty_to_print[]" disabled value="' . $item->batch_qty / $item->quantity_per_pack . '"  perPackqty="' . $item->quantity_per_pack . '" qty="' . $item->batch_qty . '"></td>
                               <td><input type="number" readonly class="scanned_count '. str_replace('.','_',$item->sku_code) .'" id="scanned_count" name="scanned_count" value="0">
                               <span class="error-span" style="display:none;color:red;">Quantity Mismatch..</span></td>
                        </tr>';
            }
                    $data .= '</tbody>';
                    $data .= '</table>
                    <div class="box-footer clearfix">
                    <br/>
                    <div class="row" >
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button class="btn btn-primary btn-rounded compare-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                Compare 
                            </button>
                        </div>
                    </div>
                    </div>
                </div>
                <br/>
                </div>';
        
                   $data .= '
                   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
                <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        var dataTable = $("#example1").dataTable({
                            "bInfo":false,
                            "ordering": false,
                            "paging": false,
                        });
                    });
                </script>';
                
          return $data;
        }


    }
    public function jayonMRPLabel()
    {
        return view('pages/label/jayon-mrp-label');
    }
    public function generateJayonMRPLabel(Request $request)
    {
        $batcard_no = $request->batchcard_no;
        $no_of_label = $request->no_of_label;
        $sku_code = $request->sku_code;
        $product = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'product_product.sku_code', 'product_price_master.mrp', 'product_product.label_format_number', 'product_product.drug_license_number', 'batchcard_batchcard.start_date')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->leftJoin('product_price_master', 'product_price_master.product_id', '=', 'product_product.id')
            ->where('product_product.sku_code', '=', $sku_code)
            ->where('batchcard_batchcard.batch_no', '=', $batcard_no)
            ->first();
        if ($product) {
            $pdf = PDF::loadView('pages.label.jayon-mrp-label-print', compact('product', 'no_of_label'));
            $customPaper = array(0, 0, 362.1024, 850.39);
            $pdf->set_paper($customPaper, 'landscape');
            $file_name = "jayon_mrp";
            return $pdf->stream($file_name . '.pdf');
        } else {
            return Redirect::back()->with('error', 'Batch Code & Sku code not matching..');
        }
    }
public function AutogenLabel(Request $request)
{
    if($request->no_of_label){
        $labelno=$request->no_of_label;
        DB::table('autogen_label')->insert(['labelno'=>$labelno]);
        return redirect()->back();
    }else{
        $data=DB::table('autogen_label')->first();

        return view('pages/label/auto-gen-label',compact('data'));
    }



}
public function sterilizationProductLabel2()
{
    return view('pages/label/sterilization-product-label2');
}
public function generateSterilizationProductLabel2(Request $request)
{
   // dd('hi');
    // $is_sterile = $this->check_label_type($request->batchcard_no);
    // if($is_sterile==0)
    // {
    //     $request->session()->flash('error', "This is non-sterile product batchcard.Try with sterile product batchcard...");
    //     return redirect('label/sterilization-label');
    // }
    $batcard_no = $request->batchcard_no;
    $batchcard_data = DB::table('batchcard_batchcard')
        ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
        ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
        ->where('batchcard_batchcard.id', '=', $batcard_no)
        ->first();
            if ( ($batchcard_data->label_format_number != 21)) {
    // if ( ($batchcard_data->label_format_number != 21 && $batchcard_data->label_format_number != 2)) {
        $request->session()->flash('error', "This is not a sterilization label batchcard.Try with sterilization label batchcard...");
        return redirect('label/sterilization-label2');
    }

    $no_of_label = $request->no_of_label;
    $lot_no = $request->sterilization_lot_no;
    $manufacture_date = $request->manufacturing_date;
    $sterilization_expiry_date = $request->sterilization_expiry_date;
    $per_pack_quantity = $request->per_pack_quantity;

    $color = [0, 0, 0];
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128, 1, 40, $color);
    // $gs1_code_barcode = $generator->getBarcode($batchcard_data->gs1_code, $generator::TYPE_CODE_128, 1,70, $color );

    return view('pages/label/sterilization-label-print2', compact('batchcard_data', 'no_of_label', 'lot_no', 'sku_code_barcode', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
}
public function NewSterile()
    {
        return view('pages/label/new-sterile');
    }
public function NewSterileGenrate(Request $request)
{
   // dd('ho');
    $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        if (($batchcard_data->label_format_number != 02) && ($batchcard_data->label_format_number != 04)) {
            $request->session()->flash('error', "This is not a sterilization label batchcard.Try with sterilization label batchcard...");
            return redirect('label/new-sterile');
        }

        $no_of_label = $request->no_of_label;
        $lot_no = $request->sterilization_lot_no;
        $manufacture_date = $request->manufacturing_date;
        $sterilization_expiry_date = $request->sterilization_expiry_date;
        $per_pack_quantity = $request->per_pack_quantity;

        $color = [0, 0, 0];
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128, 1, 40, $color);
        // $gs1_code_barcode = $generator->getBarcode($batchcard_data->gs1_code, $generator::TYPE_CODE_128, 1,70, $color );

        return view('pages/label/new-sterile-label-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'sku_code_barcode', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
    }
    public function newpatientLabel()
    {
        $title = "Create New Patient Label ";
        return view('pages/label/new-sterilization-product-label', compact('title'));
    }
    public function newgeneratePatientLabel(Request $request)
    {
        // $is_sterile = $this->check_label_type($request->batchcard_no);
        // if($is_sterile==0)
        // {
        //     $request->session()->flash('error', "This is non-sterile product batchcard.Try with sterile product batchcard...");
        //     return redirect('label/patient-label');
        // }
        $batcard_no = $request->batchcard_no;
        $batchcard_data = DB::table('batchcard_batchcard')
            ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
            ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
            ->where('batchcard_batchcard.id', '=', $batcard_no)
            ->first();
        // if($batchcard_data->label_format_number!=10)
        // {
        //     $request->session()->flash('error', "This is not a patient label batchcard.Try with patient label batchcard...");
        //     return redirect('label/patient-label');
        // }

        $no_of_label = $request->no_of_label;
        $lot_no = $request->sterilization_lot_no;
        $per_pack_quantity = $request->per_pack_quantity;
        $manufacture_date = $request->manufacturing_date;
        $sterilization_expiry_date = $request->sterilization_expiry_date;
        return view('pages/label/new-patient-label-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
        //Redirect::away('label/print/patient-label');
    }
    public function NewAneurysm()
    {
        return view('pages/label/aneurysm-clip-sterile-packaging');
    }
    public function NewAneurysmGenrate(Request $request)
    {
        {
            $batcard_no = $request->batchcard_no;
            $batchcard_data = DB::table('batchcard_batchcard')
                ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
                ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
                ->where('batchcard_batchcard.id', '=', $batcard_no)
                ->first();
    
            $no_of_label = $request->no_of_label;
            $lot_no = $request->sterilization_lot_no;
            $per_pack_quantity = $request->per_pack_quantity;
            $manufacture_date = $request->manufacturing_date;
            $sterilization_expiry_date = $request->sterilization_expiry_date;
    
            return view('pages/label/aneurysm-clip-sterile-packaging-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity'));
        }
    }
    // public function NewNonSterile()
    // {
    //     return view('pages/label/new-non-sterile-label');
    // }
    // public function NewNonSterileGenrate(Request $request)
    // {
    //     $batcard_no = $request->batchcard_no;
    
    //     // Fetch the batchcard data and related product information
    //     $batchcard_data = DB::table('batchcard_batchcard')
    //         ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
    //         ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
    //         ->where('batchcard_batchcard.id', '=', $batcard_no)
    //         ->first();
    
        
    
    //     $no_of_label = $request->no_of_label;
    //     $manufacturing_date = $request->manufacturing_date;
    //     $per_pack_quantity = $request->per_pack_quantity;
    
    //     $color = [0, 0, 0];
    //     $manf_date_combo = '[11]' . date('Y-m-d', strtotime($request->manufacturing_date));
    //     $label_batch_combo = '[10]' . $batchcard_data->batch_no;
    
    //     $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    //     $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128);
    //     $manf_date_combo_barcode = $generator->getBarcode($manf_date_combo, $generator::TYPE_CODE_128, 1, 55, $color);
    //     $label_batch_combo_barcode = $generator->getBarcode($label_batch_combo, $generator::TYPE_CODE_128);
    
    //     return view('pages/label/new-non-sterile-label-print', compact('batchcard_data', 'no_of_label', 'sku_code_barcode', 'label_batch_combo', 'label_batch_combo_barcode', 'manf_date_combo', 'manf_date_combo_barcode', 'manufacturing_date', 'per_pack_quantity'));
    
    // }


    public function SterilizationProductLABLE2()
{
    return view('pages/label/new-sterilization-label-2');
}
public function GenerateSterilizationProductLABLE2(Request $request)
{
    dd('check');
    // $is_sterile = $this->check_label_type($request->batchcard_no);
    // if($is_sterile==0)
    // {
    //     $request->session()->flash('error', "This is non-sterile product batchcard.Try with sterile product batchcard...");
    //     return redirect('label/sterilization-label');
    // }
    $batcard_no = $request->batchcard_no;
    $batchcard_data = DB::table('batchcard_batchcard')
        ->select('batchcard_batchcard.id as batch_id', 'batchcard_batchcard.batch_no', 'batchcard_batchcard.product_id', 'batchcard_batchcard.id', 'product_product.*')
        ->leftJoin('product_product', 'batchcard_batchcard.product_id', '=', 'product_product.id')
        ->where('batchcard_batchcard.id', '=', $batcard_no)
        ->first();
            if ( ($batchcard_data->label_format_number != 21)) {
    // if ( ($batchcard_data->label_format_number != 21 && $batchcard_data->label_format_number != 2)) {
        $request->session()->flash('error', "This is not a sterilization label batchcard.Try with sterilization label batchcard...");
        return redirect('label/new-sterilization-label-2');
    }
    // Fetch MRP from price master
    $mrp = DB::table('product_price_master')
    ->where('product_id', $batchcard_data->product_id)
    ->value('mrp');

    $no_of_label = $request->no_of_label;
    $lot_no = $request->sterilization_lot_no;
    $manufacture_date = $request->manufacturing_date;
    // $sterilization_expiry_date = date('Y-m', strtotime('+5 years -1 month')); // 5 years from now, minus 1 month

    $sterilization_expiry_date = $request->sterilization_expiry_date;
    // $manufacture_date = $request->manufacturing_date;
    // $sterilization_expiry_date = date('m-Y', strtotime('+5 years -1 month')); // 5 years from now, minus 1 month
    $per_pack_quantity = $request->per_pack_quantity;

    $color = [0, 0, 0];
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $sku_code_barcode = $generator->getBarcode($batchcard_data->sku_code, $generator::TYPE_CODE_128, 1, 40, $color);
    // $gs1_code_barcode = $generator->getBarcode($batchcard_data->gs1_code, $generator::TYPE_CODE_128, 1,70, $color );

    return view('pages/label/new-sterilization-label-2-print', compact('batchcard_data', 'no_of_label', 'lot_no', 'sku_code_barcode', 'manufacture_date', 'sterilization_expiry_date', 'per_pack_quantity','mrp'));
}

}

