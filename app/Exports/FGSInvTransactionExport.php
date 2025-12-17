<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use App\Models\FGS\fgs_min_item;
use App\Models\FGS\fgs_cmin_item;
use App\Models\FGS\fgs_mtq_item;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_cmtq_item;
use App\Models\FGS\fgs_mis_item;
use App\Models\FGS\fgs_cgrs_item;
use App\Models\FGS\fgs_mrn_item;
use Carbon\Carbon;
// use DB;
use Illuminate\Support\Facades\DB;

class FGSInvTransactionExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    private $datas;
    private $date;

    public function __construct($datas, $date)
    {
        $this->datas = $datas;
        $this->date = $date;
    }
    public function collection()
    {
        $i = 1;
        $data = [];
        if ($this->date) {
            $date = date('Y-m', strtotime('01-' . $this->date));
        } else {
            $date = date('Y-m');
        }
        // $threeMonthsAgo = Carbon::now()->subMonths(3);
        // $dateString = $threeMonthsAgo->format('Y-m');

        foreach ($this->datas as $product_detail) {

            if ($product_detail['expiry_date'] != '0000-00-00') {
                $expiry = date('d-m-Y', strtotime($product_detail['expiry_date']));
            } else {
                $expiry = 'NA';
            }
            $mrn_datas = fgs_mrn_item::select(
                'customer_supplier.firm_name',
                'customer_supplier.city',
                'state.state_name',
                'zone.zone_name',
                'fgs_product_category.category_name',
                // 'transaction_type.transaction_name',
                'customer_supplier.sales_type',
                'product_group1.group_name',
                // 'inventory_gst.igst',
                // 'inventory_gst.cgst',
                // 'inventory_gst.sgst',
                // 'inventory_gst.id as gst_id',
                'fgs_mrn.*',
                'product_product.sku_code',
                'product_product.discription',
                'product_product.hsn_code',
                'batchcard_batchcard.batch_no',
                'batchcard_batchcard.id as batch_id',
                'fgs_mrn_item.quantity',
                'fgs_mrn_item.id as mrn_item_id'
            )
                ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                ->leftjoin('product_product', 'product_product.id', '=', 'fgs_mrn_item.product_id')
                ->leftjoin('product_group1', 'product_product.product_group1_id', '=', 'product_group1.id')
                ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_mrn_item.batchcard_id')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_mrn.supplier')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_mrn.product_category')
                // ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                ->where(DB::raw("DATE_FORMAT(fgs_mrn.mrn_date, '%Y-%m')"), '=', $date)
                ->where('fgs_mrn_item.id', $product_detail['mrn_item_id'])
                ->where('fgs_mrn_item.status', 1)
                ->first();

            // ->where($condition)
            // ->where('fgs_mrn_item.status',1)
            // ->distinct('fgs_mrn_item.id')
            // ->orderBy('fgs_mrn_item.id','desc')

            $grs_datas  = fgs_grs_item::select(
                'customer_supplier.firm_name',
                'customer_supplier.city',
                'state.state_name',
                'zone.zone_name',
                'fgs_product_category.category_name',
                'fgs_grs.grs_number',
                'fgs_grs.grs_date',
                'fgs_grs.created_at as grs_wef',
                'fgs_grs_item.batch_quantity',
                'fgs_grs_item.id as grs_item_id',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'inventory_gst.id as gst_id',
                'fgs_oef.order_number',
                'fgs_oef_item.rate',
                'fgs_oef_item.discount',
                'batchcard_batchcard.batch_no',


            )
                ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
                ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
                ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                // ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.master', '=', 'fgs_oef.id')
                ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
                ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_grs_item.mrn_item_id')
                ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_mrn.supplier')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_mrn.product_category')
                ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
                ->where('fgs_grs_item.mrn_item_id', '=', $mrn_datas->mrn_item_id)
                // ->where(DB::raw("DATE_FORMAT(fgs_grs.created_at, '%Y-%m')"),'>=', $dateString)
                ->where(DB::raw("DATE_FORMAT(fgs_grs.grs_date, '%Y-%m')"), '=', $date)

                // ->where('fgs_grs_item.mrn_item_id', '=', $mrn_datas->mrn_item_id)
                ->where('fgs_grs.status', 1)
                ->where('fgs_grs_item.status', 1)
                ->first();
            // dd($grs_datas);
            // if ($grs_datas) {
            //     $grs_number = '';
            //     foreach ($grs_datas as $grs_data) {
            //         $grs_number .= $grs_data->grs_number . ',';
            //     }
            //     $grs_date = '';
            //     foreach ($grs_datas as $grs_data) {
            //         $grs_date .= date('d-m-Y', strtotime($grs_data->grs_date)) . ',';
            //     }
            //     $grs_wef = '';
            //     foreach ($grs_datas as $grs_data) {
            //         $grs_wef .= date('d-m-Y', strtotime($grs_data->grs_wef)) . ',';
            //     }
            // } else {
            //     $grs_number = '';
            //     $grs_date = '';
            //     $grs_wef = '';
            // }

            $cgrs_data = fgs_cgrs_item::select(
                'fgs_cgrs.cgrs_number',
                'fgs_cgrs.cgrs_date',
                'fgs_cgrs.created_at as cgrs_wef',
                'fgs_cgrs_item.batch_quantity',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'inventory_gst.id as gst_id',
                'fgs_oef.order_number',
                'fgs_oef_item.rate',
                'batchcard_batchcard.batch_no',
                'fgs_oef_item.discount',

            )
                ->leftJoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
                ->leftJoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')
                ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_cgrs_item.grs_item_id')
                ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
                ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
                ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                // ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.master', '=', 'fgs_oef.id')
                ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
                ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                ->leftJoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_grs_item.mrn_item_id')
                ->leftjoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                ->leftjoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_mrn.supplier')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_mrn.product_category')
                ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_grs_item.batchcard_id')
                //->where(DB::raw("DATE_FORMAT(fgs_cgrs.created_at, '%Y-%m')"), '>=', $dateString)
                ->where(DB::raw("DATE_FORMAT(fgs_cgrs.cgrs_date, '%Y-%m')"), '=', $date)

                ->where('fgs_grs_item.mrn_item_id', '=', $product_detail['mrn_item_id'])

                //->where('fgs_coef_item.status','=',1)
                ->first();
            // foreach ($grs_datas as $grs_data) {
            //     $cgrs_data = fgs_cgrs_item::select('fgs_cgrs.cgrs_number', 'fgs_cgrs.cgrs_date', 'fgs_cgrs.created_at as cgrs_wef', 'fgs_cgrs_item.batch_quantity')
            //         ->leftJoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
            //         ->leftJoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')
            //         ->where('fgs_cgrs_item.grs_item_id', '=', $grs_data['grs_item_id'])
            //         //->where('fgs_coef_item.status','=',1)
            //         ->first();
            //     if ($cgrs_data) {
            //         $cgrs_number .= $cgrs_data->cgrs_number . ',';
            //         $cgrs_date .= date('d-m-Y', strtotime($cgrs_data->cgrs_date)) . ',';
            //         $cgrs_wef .= date('d-m-Y', strtotime($cgrs_data->cgrs_wef)) . ',';
            //     } else {
            //         $cgrs_number = '';
            //         $cgrs_date = '';
            //         $cgrs_wef = '';
            //     }
            // }



            $min_datas = fgs_min_item::select('fgs_min.min_number', 'fgs_min.min_date', 'fgs_min.created_at as min_wef')
                ->leftJoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
                ->leftJoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')
                ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_min_item.batchcard_id')
                ->where('fgs_min_item.batchcard_id', '=', $product_detail['batch_id'])
                //->where(DB::raw("DATE_FORMAT(fgs_min.created_at, '%Y-%m')"), '>=', $dateString)
                ->where(DB::raw("DATE_FORMAT(fgs_min.min_date, '%Y-%m')"), '=', $date)

                ->where('fgs_min_item.status', '=', 1)
                ->first();

            // if ($min_datas) {
            //     $min_number = '';
            //     $min_date = '';
            //     $min_wef = '';
            //     foreach ($min_datas as $min_data) {
            //         $min_number .= $min_data->min_number . ',';
            //         $min_date .= date('d-m-Y', strtotime($min_data->min_date)) . ',';
            //         $min_wef .= date('d-m-Y', strtotime($min_data->min_wef)) . ',';
            //     }
            // } else {
            //     $min_number = '';
            //     $min_date = '';
            //     $min_wef = '';
            // }

            $cmin_datas = fgs_cmin_item::select('fgs_cmin.cmin_number', 'fgs_cmin.cmin_date', 'fgs_cmin.created_at as cmin_wef')
                ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
                ->leftJoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')
                ->where('fgs_cmin_item.batchcard_id', '=', $product_detail['batch_id'])
                //->where(DB::raw("DATE_FORMAT(fgs_cmin.created_at, '%Y-%m')"), '>=', $dateString)
                ->where(DB::raw("DATE_FORMAT(fgs_cmin.cmin_date, '%Y-%m')"), '=', $date)

                ->first();
            // if ($cmin_datas) {
            //     $cmin_number = '';
            //     $cmin_date = '';
            //     $cmin_wef = '';
            //     foreach ($cmin_datas as $cmin_data) {
            //         $cmin_number .= $cmin_data->cmin_number . ',';
            //         $cmin_date .= date('d-m-Y', strtotime($cmin_data->cmin_date)) . ',';
            //         $cmin_wef .= date('d-m-Y', strtotime($cmin_data->cmin_wef)) . ',';
            //     }
            // } else {
            //     $cmin_number = '';
            //     $cmin_date = '';
            //     $cmin_wef = '';
            // }

            $mtq_datas = fgs_mtq_item::select('fgs_mtq.mtq_number', 'fgs_mtq.mtq_date', 'fgs_mtq.created_at as mtq_wef', 'fgs_mtq_item.id as mtq_item_id')
                ->leftJoin('fgs_mtq_item_rel', 'fgs_mtq_item_rel.item', '=', 'fgs_mtq_item.id')
                ->leftJoin('fgs_mtq', 'fgs_mtq.id', '=', 'fgs_mtq_item_rel.master')
                ->where('fgs_mtq_item.batchcard_id', '=', $product_detail['batch_id'])
                //->where(DB::raw("DATE_FORMAT(fgs_mtq.created_at, '%Y-%m')"), '>=', $dateString)
                ->where(DB::raw("DATE_FORMAT(fgs_mtq.mtq_date, '%Y-%m')"), '=', $date)

                ->first();
            // if ($mtq_datas) {
            //     $mtq_number = '';
            //     $mtq_date = '';
            //     $mtq_wef = '';
            //     foreach ($mtq_datas as $mtq_data) {
            //         $mtq_number .= $mtq_data->mtq_number . ',';
            //         $mtq_date .= date('d-m-Y', strtotime($mtq_data->mtq_date)) . ',';
            //         $mtq_wef .= date('d-m-Y', strtotime($mtq_data->mtq_wef)) . ',';
            //     }
            // } else {
            //     $mtq_number = '';
            //     $mtq_date = '';
            //     $mtq_wef = '';
            // }

            $cmtq_datas = fgs_cmtq_item::select('fgs_cmtq.cmtq_number', 'fgs_cmtq.cmtq_date', 'fgs_cmtq.created_at as mtq_wef', 'fgs_cmtq_item.id as cmtq_item_id')
                ->leftJoin('fgs_cmtq_item_rel', 'fgs_cmtq_item_rel.item', '=', 'fgs_cmtq_item.id')
                ->leftJoin('fgs_cmtq', 'fgs_cmtq.id', '=', 'fgs_cmtq_item_rel.master')
                //->where(DB::raw("DATE_FORMAT(fgs_cmtq.created_at, '%Y-%m')"), '>=', $dateString)
                ->where(DB::raw("DATE_FORMAT(fgs_cmtq.cmtq_date, '%Y-%m')"), '=', $date)

                ->where('fgs_cmtq_item.batchcard_id', '=', $product_detail['batch_id'])
                ->first();
            // if ($cmtq_datas) {
            //     $cmtq_number = '';
            //     $cmtq_date = '';
            //     $cmtq_wef = '';
            //     foreach ($cmtq_datas as $cmtq_data) {
            //         $cmtq_number .= $cmtq_data->cmtq_number . ',';
            //         $cmtq_date .= date('d-m-Y', strtotime($cmtq_data->cmtq_date)) . ',';
            //         $cmtq_wef .= date('d-m-Y', strtotime($cmtq_data->cmtq_wef)) . ',';
            //     }
            // } else {
            //     $cmtq_number = '';
            //     $cmtq_date = '';
            //     $cmtq_wef = '';
            // }

            if ($mtq_datas) {
                //     $mis_number = '';
                //     $mis_date = '';
                //     $mis_wef = '';
                //     foreach ($mtq_datas as $mtq_data) {
                $mis_data = fgs_mis_item::select('fgs_mis.mis_number', 'fgs_mis.mis_date', 'fgs_mis.created_at as mis_wef')
                    ->leftJoin('fgs_mis_item_rel', 'fgs_mis_item_rel.item', '=', 'fgs_mis_item.id')
                    ->leftJoin('fgs_mis', 'fgs_mis.id', '=', 'fgs_mis_item_rel.master')
                    ->where('fgs_mis_item.mtq_item_id', '=', $mtq_datas->mtq_item_id)
                    //->where('fgs_coef_item.status','=',1)
                    ->first();
                // if ($mis_data) {
                //     $mis_number .= $mis_data->mis_number . ',';
                //     $mis_date .= date('d-m-Y', strtotime($mis_data->mis_date)) . ',';
                //     $mis_wef .= date('d-m-Y', strtotime($mis_data->mis_wef)) . ',';
                // } else {
                //     $mis_number = '';
                //     $mis_date = '';
                //     $mis_wef = '';
                // }
                // }
            } else {
                $mis_data = '';
            }
            if (date('m', strtotime($mrn_datas->created_at)) == 6 || date('m', strtotime($mrn_datas->created_at)) == 5 || date('m', strtotime($mrn_datas->created_at)) == 4) {
                $qtr = "Q1";
            }
            if (date('m', strtotime($mrn_datas->created_at)) == 7 || date('m', strtotime($mrn_datas->created_at)) == 8 || date('m', strtotime($mrn_datas->created_at)) == 9) {
                $qtr = "Q2";
            }
            if (date('m', strtotime($mrn_datas->created_at)) == 10 || date('m', strtotime($mrn_datas->created_at)) == 11 || date('m', strtotime($mrn_datas->created_at)) == 12) {
                $qtr = "Q3";
            }
            if (date('m', strtotime($mrn_datas->created_at)) == 1 || date('m', strtotime($mrn_datas->created_at)) == 2 || date('m', strtotime($mrn_datas->created_at)) == 3) {
                $qtr = "Q4";
            }
            $currentDate = Carbon::now();
            $currentYear = $currentDate->year;

            if ($currentDate->month >= 4) {
                $startYear = $currentYear;
                $endYear = $currentYear + 1;
            } else {
                $startYear = $currentYear - 1;
                $endYear = $currentYear;
            }

            $financialYear = $startYear . '-' . substr($endYear, -2);

            if ($mrn_datas) {
                $data[] = array(
                    'doc_type' => "MRN",
                    'doc_no' => $mrn_datas->mrn_number,
                    'doc_date' => date('d-m-Y', strtotime($mrn_datas->mrn_date)),
                    'customer_name' => $mrn_datas->firm_name,
                    'Order_No' => null,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'batch' => $mrn_datas->batch_no,
                    // 'order_date' => date('d-m-Y', strtotime($oef_details->order_date)),
                    'qty' => $mrn_datas->quantity,
                    'rate' => null,
                    'disc' =>"",
                    'disc_value' => "",
                    'Taxable_Value' => "",
                    'gst' => "",
                    'gst_value' => "",
                        'Total Amount' => "",
                    'Zone' => $mrn_datas->zone_name,
                    'State' => $mrn_datas->state_name,
                    'City' => $mrn_datas->city,
                    'Product Category' => $mrn_datas->category_name,
                    'productgroup' => $mrn_datas->group_name,
                    'Sales Type' => $mrn_datas->sales_type,
                    'Remarks' => $mrn_datas->remarks,
                    'Month' => date('F', strtotime($mrn_datas->created_at)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($mrn_datas->created_at)),
                    'FY(Financial Year)' => $financialYear

                );
            }
            if ($grs_datas!=null) {
            if ($grs_datas->rate) {
                $total_rate = $grs_datas['batch_quantity'] * $grs_datas['rate'];
                $discount_value = $total_rate * $grs_datas['discount'] / 100;
                $taxable_value = $total_rate - $discount_value;
                $igst_value = $total_rate * $grs_datas['igst'] / 100;
                $sgst_value = $total_rate * $grs_datas['sgst'] / 100;
                $cgst_value = $total_rate * $grs_datas['cgst'] / 100;
                $gst_value = $igst_value + $cgst_value + $sgst_value;
                $total_value = $taxable_value + $igst_value + $cgst_value + $sgst_value;
            } else {
                $taxable_value = 0;
                $igst_value = 0;
                $sgst_value = 0;
                $cgst_value = 0;
                $gst_value = 0;
                $discount_value = 0;
                $total_value = 0;
            }
           
                $data[] = array(
                    'doc_type' => "GRS",
                    'doc_no' => $grs_datas->grs_number,
                    'doc_date' => date('d-m-Y', strtotime($grs_datas->grs_date)),
                    'customer_name' => $grs_datas->firm_name,
                    'Order_No' => null,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'batch' => $mrn_datas->batch_no,
                    'qty' => $grs_datas->batch_quantity,
                    'rate' => $grs_datas->rate,
                    'disc' => $grs_datas->discount,
                    'disc_value' => $discount_value,
                    'Taxable_Value' => number_format((float)($taxable_value), 2, '.', ''),
                    'gst' => "IGST:" . $grs_datas['igst'] . ", SGST:" . $grs_datas['sgst'] . ", CGST:" . $grs_datas['cgst'],
                    'gst_value' => $gst_value,
                        'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                    'Zone' => $grs_datas->zone_name,
                    'State' => $grs_datas->state_name,
                    'City' => $grs_datas->city,
                    'Product Category' => $grs_datas->category_name,
                    'productgroup' => $mrn_datas->group_name,
                    'Sales Type' => $grs_datas->sales_type,
                    'Remarks' => $grs_datas->remarks,
                    'Month' => date('F', strtotime($grs_datas->created_at)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($grs_datas->created_at)),
                    'FY(Financial Year)' => $financialYear

                );
            }
            if ($cgrs_data) {
                $data[] = array(
                    'doc_type' => "GRS",
                    'doc_no' => $cgrs_data->cgrs_number,
                    'doc_date' => date('d-m-Y', strtotime($cgrs_data->grs_date)),
                    'customer_name' => $cgrs_data->firm_name,
                    'Order_No' => null,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'batch' => $mrn_datas->batch_no,
                    'qty' => $cgrs_data->batch_quantity,
                    'rate' => $grs_datas->rate,
                    'disc' => $grs_datas->discount,
                    'disc_value' => $discount_value,
                    'Taxable_Value' => number_format((float)($taxable_value), 2, '.', ''),
                    'gst' => "IGST:" . $grs_datas['igst'] . ", SGST:" . $grs_datas['sgst'] . ", CGST:" . $grs_datas['cgst'],
                    'gst_value' => $gst_value,
                    'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                    'Zone' => $cgrs_data->zone_name,
                    'State' => $cgrs_data->state_name,
                    'City' => $cgrs_data->city,
                    'Product Category' => $cgrs_data->category_name,
                    'productgroup' => $mrn_datas->group_name,
                    'Sales Type' => $cgrs_data->sales_type,
                    'Remarks' => $cgrs_data->remarks,
                    'Month' => date('F', strtotime($cgrs_data->created_at)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($cgrs_data->created_at)),
                    'FY(Financial Year)' => $financialYear

                );
            }
            if ($min_datas) {
                $data[] = array(
                    'doc_type' => "MIN",
                    'doc_no' => $min_datas->min_number,
                    'doc_date' => date('d-m-Y', strtotime($min_datas->min_date)),
                    'customer_name' => "",
                    'Order_No' => null,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'batch' => $mrn_datas->batch_no,
                    'qty' => $min_datas->batch_quantity,
                    'rate' => "",
                    'disc' =>"",
                    'disc_value' => "",
                    'Taxable_Value' => "",
                    'gst' => "",
                    'gst_value' =>"",
                     'Total Amount' => "",
                    'Zone' => "",
                    'State' => "",
                    'City' => "",
                    'Product Category' => $min_datas->category_name,
                    'productgroup' => $mrn_datas->group_name,
                    'Sales Type' => $min_datas->sales_type,
                    'Remarks' => $min_datas->remarks,
                    'Month' => date('F', strtotime($min_datas->created_at)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($min_datas->created_at)),
                    'FY(Financial Year)' => $financialYear

                );
            }
            if ($cmin_datas) {
                $data[] = array(
                    'doc_type' => "GRS",
                    'doc_no' => $cmin_datas->min_number,
                    'doc_date' => date('d-m-Y', strtotime($cmin_datas->min_date)),
                    'customer_name' => "",
                    'Order_No' => null,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'batch' => $mrn_datas->batch_no,
                    'qty' => $cmin_datas->batch_quantity,
                    'rate' => "",
                    'disc' => "",
                    'disc_value' =>"",
                    'Taxable_Value' => "",
                    'gst' => "",
                    'gst_value' => "",
                    'Total Amount' => "",                    
                    'Zone' => "",
                    'State' => "",
                    'City' => "",
                    'Product Category' => $cmin_datas->category_name,
                    'productgroup' => $mrn_datas->group_name,
                    'Sales Type' => $cmin_datas->sales_type,
                    'Remarks' => $cmin_datas->remarks,
                    'Month' => date('F', strtotime($cmin_datas->created_at)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($cmin_datas->created_at)),
                    'FY(Financial Year)' => $financialYear

                );
            }
            if ($mtq_datas) {
                $data[] = array(
                    'doc_type' => "GRS",
                    'doc_no' => $mtq_datas->min_number,
                    'doc_date' => date('d-m-Y', strtotime($mtq_datas->min_date)),
                    'customer_name' => "",
                    'Order_No' => null,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'batch' => $mrn_datas->batch_no,
                    'qty' => $mtq_datas->batch_quantity,
                    'rate' => "",
                    'disc' => "",
                    'disc_value' => "",
                    'Taxable_Value' => "",
                    'gst' => "",
                    'gst_value' => "",
                    'Total Amount' => "",
                    'Zone' => "",
                    'State' => "",
                    'City' => "",
                    'Product Category' => $mtq_datas->category_name,
                    'productgroup' => $mrn_datas->group_name,
                    'Sales Type' => $mtq_datas->sales_type,
                    'Remarks' => $mtq_datas->remarks,
                    'Month' => date('F', strtotime($mtq_datas->created_at)),
                    'Qtr' => "",
                    'CY(Calender Year)' => date('Y', strtotime($mtq_datas->created_at)),
                    'FY(Financial Year)' => $financialYear

                );
            }
            if ($cmtq_datas) {
                $data[] = array(
                    'doc_type' => "GRS",
                    'doc_no' => $cmtq_datas->min_number,
                    'doc_date' => date('d-m-Y', strtotime($cmtq_datas->min_date)),
                    'customer_name' => "",
                    'Order_No' => null,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'batch' => $mrn_datas->batch_no,
                    'qty' => $cmtq_datas->batch_quantity,
                    'rate' => "",
                    'disc' => "",
                    'disc_value' => "",
                    'Taxable_Value' => "",
                    'gst' =>"",
                    'gst_value' => "",
                    'Total Amount' => "",
                    'Zone' => "",
                    'State' => "",
                    'City' => "",
                    'Product Category' => $cmtq_datas->category_name,
                    'productgroup' => $mrn_datas->group_name,
                    'Sales Type' => $cmtq_datas->sales_type,
                    'Remarks' => $mtq_datas->remarks,
                    'Month' => date('F', strtotime($cmtq_datas->created_at)),
                    'Qtr' => "",
                    'CY(Calender Year)' => date('Y', strtotime($cmtq_datas->created_at)),
                    'FY(Financial Year)' => $financialYear

                );
            }
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            'Doc Type',
            'Doc No',
            'Doc Date',
            'Customer/Supplier Name',
            'Order No./Supplier Doc No.',
            'Item Code',
            'Description',
            'Batch No.',
            // 'Order Date',
            'Qty',
            'Rate',
            'Disc.',
            'Disc Value',
            'Taxable Value',
            'gst',
            'gst Value',
            'Total Amount',
            'Zone',
            'State',
            'City',
            'Product Category',
            'Product Group',
            //'Transaction Type',
            'Sales Type',
            'Remarks',
            'Month',
            'CY(Calender Year)',
            'FY(Financial Year)'
        ];
    }
    public function styles(Worksheet $sheet)
    {

        return [
            // Style the first row as bold text.
            1    => ['font' => ['size' => 12, 'bold' => true]],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(15);

                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
