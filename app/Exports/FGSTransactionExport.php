<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\Web\FGS\FgsreportController;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_coef_item;
use App\Models\FGS\fgs_cgrs_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_cpi_item;
use App\Models\FGS\fgs_min_item;
use App\Models\FGS\fgs_cmin_item;
use App\Models\FGS\fgs_pi_item_rel;
use App\Models\FGS\fgs_srn_item_rel;
use App\Models\FGS\fgs_dni_item;
use App\Models\FGS\fgs_srn_item;
use App\Models\FGS\fgs_srn;


use App\Models\FGS\fgs_oef_item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FGSTransactionExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    private $datas;
    private $date;

    public function __construct($datas,$date)
    {
        $this->datas = $datas;
        $this->date = $date;

    }
    public function collection()
    {
        $i = 1;
        $data = [];
        $date=date('Y-m', strtotime('01-'.$this->date));
        // dd($date);
        foreach ($this->datas as $product_detail) {
            $taxable_value = 0;
            $igst_value = 0;
            $sgst_value = 0;
            $cgst_value = 0;
            $gst_value = 0;
            $discount_value = 0;
            $total_value = 0;
            $total_rate = 0;
            // $threeMonthsAgo = Carbon::now()->subMonths(2);
            // $dateString = $threeMonthsAgo->format('Y-m');
            if ($product_detail['expiry_date'] != '0000-00-00') {
                $expiry = date('d-m-Y', strtotime($product_detail['expiry_date']));
            } else {
                $expiry = 'NA';
            }
            $oef_data = fgs_grs_item::select(
                'customer_supplier.firm_name',
                'customer_supplier.city',
                'state.state_name',
                'zone.zone_name',
                'fgs_oef.oef_number',
                'fgs_oef.order_number',
                'fgs_oef.order_date',
                'fgs_oef_item.rate',
                'fgs_oef.oef_date',
                'fgs_oef_item.quantity',
                'fgs_oef_item.id as oef_item_id',
                'fgs_product_category.category_name',
                'fgs_product_category_new.category_name as new_category_name',
                'transaction_type.transaction_name',
                'customer_supplier.sales_type',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'inventory_gst.id as gst_id'
            )
                ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
                ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
                ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
                ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
                ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->where('fgs_grs_item.mrn_item_id', '=', $product_detail['mrn_item_id'])
                // ->where('fgs_oef_item.product_id', '=', $product_detail['product_id'])
                // ->where(DB::raw("DATE_FORMAT(fgs_oef.created_at, '%Y-%m')"), '>=', $dateString)
                ->where('fgs_oef.status', 1)
                ->where('fgs_oef_item.status', 1)
                ->first();
            // dd($oef_data);
            //non grs oef
            // $oef_details = fgs_oef_item::select(
            //     'customer_supplier.firm_name',
            //     'customer_supplier.city',
            //     'state.state_name',
            //     'zone.zone_name',
            //     'fgs_oef.*',
            //     'fgs_oef_item.rate',
            //     'fgs_oef.created_at as oef_wef',
            //     'fgs_oef_item.quantity',
            //     'fgs_oef_item.id as oef_item_id',
            //     'fgs_product_category.category_name',
            //     'transaction_type.transaction_name',
            //     'customer_supplier.sales_type',
            //     'inventory_gst.igst',
            //     'inventory_gst.cgst',
            //     'inventory_gst.sgst',
            //     'inventory_gst.id as gst_id'
            // )
            //     //->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
            //     ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
            //     ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
            //     ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
            //     ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
            //     ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
            //     ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
            //     ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
            //     ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
            //     ->where('fgs_oef_item.product_id', '=', $product_detail['product_id'])
            //     ->where(DB::raw("DATE_FORMAT(fgs_oef.created_at, '%Y-%m')"), '>=', $dateString)
            //     ->where('fgs_oef.status', 1)
            //     ->where('fgs_oef_item.status', 1)
            //     ->orderBy('fgs_oef_item.id', 'desc')
            //     ->first();
            // dd($oef_details);
            if ($oef_data) {
                if ($oef_data['oef_date'])
                    $oef_date = date('d-m-Y', strtotime($oef_data['oef_date']));
                else
                    $oef_date = '';

                if ($oef_data['oef_wef'])
                    $oef_wef = date('d-m-Y', strtotime($oef_data['oef_wef']));
                else
                    $oef_wef = '';

                if ($oef_data['oef_number'])
                    $oef_number = $oef_data['oef_number'];
                else
                    $oef_number = '';

                if ($oef_data['quantity'])
                    $oef_qty = $oef_data['quantity'];
                else
                    $oef_qty = '';
            } else {
                $oef_number = '';
                $oef_date = '';
                $oef_wef = '';
                $oef_qty = '';
            }
            // if ($oef_details) {
            //     if ($oef_details['oef_date'])
            //         $nongrsoef_date = date('d-m-Y', strtotime($oef_details['oef_date']));
            //     else
            //         $nongrsoef_date = '';

            //     if ($oef_details['oef_wef'])
            //         $nongrsoef_wef = date('d-m-Y', strtotime($oef_details['oef_wef']));
            //     else
            //         $nongrsoef_wef = '';

            //     if ($oef_details['oef_number'])
            //         $nongrsoef_number = $oef_details['oef_number'];
            //     else
            //         $nongrsoef_number = '';

            //     if ($oef_details['quantity'])
            //         $nongrsoef_qty = $oef_details['quantity'];
            //     else
            //         $nongrsoef_qty = '';
            // } else {
            //     $nongrsoef_number = '';
            //     $nongrsoef_date = '';
            //     $nongrsoef_wef = '';
            //     $nongrsoef_qty = '';
            // }

             if ($oef_data) {

                $coef_data = fgs_coef_item::select(
                    'customer_supplier.firm_name',
                    'customer_supplier.city',
                    'state.state_name',
                    'zone.zone_name',
                    'fgs_coef.coef_number',
                    'fgs_product_category.category_name',
                    'fgs_product_category_new.category_name as new_category_name',
                    'transaction_type.transaction_name',
                    'customer_supplier.sales_type',
                    'inventory_gst.igst',
                    'inventory_gst.cgst',
                    'inventory_gst.sgst',
                    'fgs_oef_item.rate',
                    'inventory_gst.id as gst_id',
                    'fgs_coef.coef_date',
                    'fgs_coef.created_at as coef_wef',
                    'fgs_coef_item.quantity'
                )
                    ->leftJoin('fgs_coef_item_rel', 'fgs_coef_item_rel.item', '=', 'fgs_coef_item.id')
                    ->leftJoin('fgs_coef', 'fgs_coef.id', '=', 'fgs_coef_item_rel.master')
                    ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_coef.oef_id')
                    ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.master', '=', 'fgs_oef.id')
                    ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_oef_item_rel.item')
                    ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
                    ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                    ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                    ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                    ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
                    ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
                    ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                    // ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_oef_item.grs_item_id')
                    // ->where('fgs_grs_item.mrn_item_id', '=', $product_detail['mrn_item_id'])
                    // ->where(DB::raw("DATE_FORMAT(fgs_coef.created_at, '%Y-%m')"), '>=', $dateString)

                    ->where('fgs_coef.status', 1)
                    ->where('fgs_oef_item.status', 1)
                    //->where('fgs_coef.oef_id', '=', $oef_data['oef_item_id'])
                    ->where('fgs_oef_item.coef_status', '=', 1)

                    //->where('fgs_coef_item.status','=',1)
                    ->first();

                if ($coef_data) {
                    $coef_number = $coef_data['coef_number'];
                    $coef_qty = $coef_data['quantity'];
                    $coef_date = date('d-m-Y', strtotime($coef_data['coef_date']));
                    $coef_wef = date('d-m-Y', strtotime($coef_data['coef_wef']));
                } else {
                    $coef_number = '';
                    $coef_qty = "";
                    $coef_date = '';
                    $coef_wef = '';
                }
            }
            else {

                $coef_data='';
                $coef_number = '';
                $coef_qty = "";
                $coef_date = '';
                $coef_wef = '';
            }

            $pi_datas = fgs_pi_item::select(
                'customer_supplier.firm_name',
                'customer_supplier.city',
                'state.state_name',
                'zone.zone_name',
                'fgs_product_category.category_name',
                'fgs_product_category_new.category_name as new_category_name',
                'transaction_type.transaction_name',
                'customer_supplier.sales_type',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'fgs_oef_item.rate',
                'inventory_gst.id as gst_id',
                'fgs_pi.pi_number',
                'fgs_pi.pi_date',
                'fgs_pi.created_at as pi_wef',
                'fgs_pi_item.remaining_qty_after_cancel',
                'fgs_pi_item.id as pi_item_id'
            )
                ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
                ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
                ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
                ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.master', '=', 'fgs_oef.id')
                ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_oef_item_rel.item')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
                ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
                ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                // ->where(DB::raw("DATE_FORMAT(fgs_pi.created_at, '%Y-%m')"), '>=', $dateString)
                ->where('fgs_pi_item.mrn_item_id', '=', $product_detail['mrn_item_id'])
                ->where('fgs_pi.status', 1)
                ->where('fgs_pi_item.status', 1)
                ->first();

            if ($pi_datas) {
                // $pi_number = '';
                // $pi_qty = '';
                // $pi_date = '';
                // $pi_wef = '';
                // foreach ($pi_datas as $pi_data) {
                $pi_number = $pi_datas->pi_number;
                $pi_qty = $pi_datas->remaining_qty_after_cancel;
                $pi_date = date('d-m-Y', strtotime($pi_datas->pi_date));
                $pi_wef = date('d-m-Y', strtotime($pi_datas->pi_wef));
                // }
            } else {
                $pi_number = '';
                $pi_qty = '';
                $pi_date = '';
                $pi_wef = '';
            }
            $cpi_datas = fgs_pi_item::select(
                'customer_supplier.firm_name',
                'customer_supplier.city',
                'state.state_name',
                'zone.zone_name',
                'fgs_product_category.category_name',
                'fgs_product_category_new.category_name as new_category_name',
                'transaction_type.transaction_name',
                'customer_supplier.sales_type',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'inventory_gst.id as gst_id',
                'fgs_cpi.cpi_number',
                'fgs_cpi.cpi_date',
                'fgs_oef_item.rate',
                'fgs_cpi.created_at as cpi_wef',
                'fgs_cpi_item.quantity',
                'fgs_cpi_item.id as cpi_item_id'
            )
                ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
                ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
                ->leftJoin('fgs_cpi', 'fgs_cpi.pi_id', '=', 'fgs_pi.id')
                ->leftJoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.master', '=', 'fgs_cpi.id')
                ->leftJoin('fgs_cpi_item', 'fgs_cpi_item.id', '=', 'fgs_cpi_item_rel.item')
                ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_cpi_item.grs_id')
                ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.master', '=', 'fgs_oef.id')
                ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_oef_item_rel.item')

                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
                ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
                ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->where('fgs_cpi_item.mrn_item_id', '=', $product_detail['mrn_item_id'])
                // ->where(DB::raw("DATE_FORMAT(fgs_cpi.created_at, '%Y-%m')"), '>=', $dateString)
                ->where('fgs_cpi.status', 1)
                ->first();
            if ($cpi_datas) {
                    $total_rate = $cpi_datas['quantity'] * $cpi_datas['rate'];
                    $discount_value = $total_rate * $cpi_datas['discount'] / 100;
                    $taxable_value = $total_rate - $discount_value;
                    $igst_value = $total_rate * $cpi_datas['igst'] / 100;
                    $sgst_value = $total_rate * $cpi_datas['sgst'] / 100;
                    $cgst_value = $total_rate * $cpi_datas['cgst'] / 100;
                    $gst_value = $igst_value + $cgst_value + $sgst_value;
                    $total_value = $taxable_value + $igst_value + $cgst_value + $sgst_value;
            
                $cpi_number = $cpi_datas->cpi_number;
                $cpi_qty = $cpi_datas->quantity;
                $cpi_date = date('d-m-Y', strtotime($cpi_datas->cpi_date));
                $cpi_wef = date('d-m-Y', strtotime($cpi_datas->cpi_wef));
            } else {
                $cpi_number = '';
                $cpi_qty = '';
                $cpi_date = '';
                $cpi_wef = '';
            }
            $dni_details = fgs_pi_item::select(
                'customer_supplier.firm_name',
                'customer_supplier.city',
                'state.state_name',
                'zone.zone_name',
                'fgs_product_category.category_name',
                'fgs_product_category_new.category_name as new_category_name',
                'transaction_type.transaction_name',
                'customer_supplier.sales_type',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'inventory_gst.id as gst_id',
                'fgs_dni.dni_number',
                'fgs_dni.dni_date',
                'fgs_oef_item.rate',
                'fgs_pi_item.remaining_qty_after_cancel',
                'fgs_pi_item.id as pi_item_id'
            )
                ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
                ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
                ->leftJoin('fgs_dni_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
                ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
                ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
                ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
                ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.master', '=', 'fgs_oef.id')
                ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_oef_item_rel.item')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
                
                ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
                // ->where(DB::raw("DATE_FORMAT(fgs_dni.created_at, '%Y-%m')"), '>=', $dateString)
                ->where('fgs_dni_item.mrn_item_id', '=', $product_detail['mrn_item_id'])
                ->where('fgs_dni.dni_exi', 'DNI')
                ->where('fgs_pi.status', 1)
                ->where('fgs_pi_item.status', 1)
                ->first();
            if ($dni_details) {
                $dni_number = $dni_details->dni_number;
                $dni_qty = $dni_details->remaining_qty_after_cancel;
                $dni_date = date('d-m-Y', strtotime($dni_details->dni_date));
            } else {
                $dni_number = '';
                $dni_qty = '';
                $dni_date = '';
            }
            $exi_details = fgs_pi_item::select(
                'customer_supplier.firm_name',
                'customer_supplier.city',
                'state.state_name',
                'zone.zone_name',
                'fgs_oef_item.rate',
                'fgs_product_category.category_name',
                'fgs_product_category_new.category_name as new_category_name',
                'transaction_type.transaction_name',
                'customer_supplier.sales_type',
                'inventory_gst.igst',
                'inventory_gst.cgst',
                'inventory_gst.sgst',
                'inventory_gst.id as gst_id',
                'fgs_dni.dni_number',
                'fgs_dni.dni_date',
                'fgs_pi_item.remaining_qty_after_cancel',
                'fgs_pi_item.id as pi_item_id'
            )
                ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
                ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
                ->leftJoin('fgs_dni_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
                ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
                ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master')
                ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
                ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.master', '=', 'fgs_oef.id')
                ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_oef_item_rel.item')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'fgs_oef.product_category')
                ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
                ->where(DB::raw("DATE_FORMAT(fgs_dni.dni_date, '%Y-%m')"), '>=', $date)
                ->where('fgs_dni_item.product_id', '=', $product_detail['product_id'])
                ->where('fgs_dni.dni_exi', 'EXI')
                ->where('fgs_pi.status', 1)
                ->where('fgs_pi_item.status', 1)
                ->first();

            if ($exi_details) {
                $exi_number = $exi_details->dni_number;
                $exi_qty = $exi_details->remaining_qty_after_cancel;
                $exi_date = date('d-m-Y', strtotime($exi_details->dni_date));
            } else {
                $exi_number = '';
                $exi_qty = '';
                $exi_date = '';
            }
            $srn_details = fgs_srn_item_rel::select(
                'fgs_srn.*',
                
                'product_product.sku_code',
                'product_product.hsn_code',
                'product_product.discription',
                
                'fgs_oef_item.rate',
                'fgs_oef_item.discount',
                'currency_exchange_rate.currency_code',
                'fgs_pi.pi_number',
                'fgs_pi.pi_date',
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
                'fgs_srn_item.quantity'
            )
                ->leftJoin('fgs_srn_item', 'fgs_srn_item_rel.item', 'fgs_srn_item.id')
                ->leftJoin('fgs_srn', 'fgs_srn_item_rel.master', 'fgs_srn.id')
                ->leftJoin('fgs_dni_item', 'fgs_dni_item.id', 'fgs_srn_item.dni_item_id')
                ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', 'fgs_dni_item.id')
                ->leftJoin('fgs_dni', 'fgs_dni.id', 'fgs_dni_item_rel.master')
                ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_dni_item.pi_id')
                ->leftjoin('product_product', 'product_product.id', '=', 'fgs_srn_item.product_id')
                // ->leftjoin('batchcard_batchcard', 'batchcard_batchcard.id', '=', 'fgs_srn_item.batchcard_id')
                ->leftjoin('fgs_mrn_item', 'fgs_mrn_item.id', '=', 'fgs_srn_item.mrn_item_id')
                ->leftJoin('fgs_pi_item', 'fgs_pi_item.id', '=', 'fgs_dni_item.pi_item_id')
                ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_pi_item.grs_id')
                ->leftJoin('fgs_grs_item', 'fgs_grs_item.id', '=', 'fgs_pi_item.grs_item_id')
                ->leftJoin('fgs_oef_item', 'fgs_oef_item.id', '=', 'fgs_grs_item.oef_item_id')
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
                ->leftJoin('currency_exchange_rate', 'currency_exchange_rate.currency_id', '=', 'customer_supplier.currency')
                ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_grs.oef_id')
                ->leftJoin('order_fulfil', 'order_fulfil.id', '=', 'fgs_oef.order_fulfil')
                ->leftJoin('transaction_type', 'transaction_type.id', '=', 'fgs_oef.transaction_type')
                ->leftjoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
                ->where(DB::raw("DATE_FORMAT(fgs_srn.srn_date, '%Y-%m')"), '>=', $date)
                ->where('fgs_srn_item.product_id', $product_detail['product_id'])
                // ->distinct('fgs_dni_item.pi_id')
                ->distinct('fgs_srn_item.id')
                ->orderBy('fgs_srn_item.id', 'ASC')
                ->first();

            if ($srn_details) {
                $srn_number = $srn_details->srn_number;
                $total_rate = $srn_details['quantity'] * $srn_details['rate'];
                $discount_value = $total_rate * $srn_details['discount'] / 100;
                $taxable_value = $total_rate - $discount_value;
                $igst_value = $total_rate * $srn_details['igst'] / 100;
                $sgst_value = $total_rate * $srn_details['sgst'] / 100;
                $cgst_value = $total_rate * $srn_details['cgst'] / 100;
                $gst_value = $igst_value + $cgst_value + $sgst_value;
                $total_value = $taxable_value + $igst_value + $cgst_value + $sgst_value;
                $srn_qty = $srn_details->quantity;
                $srn_date = date('d-m-Y', strtotime($srn_details->srn_date));
            } else {
                $srn_number = '';
                $srn_qty = '';
                $srn_date = '';
            }
            $man_srn_details = fgs_srn::select(
                'fgs_srn.*',
                'customer_supplier.firm_name',
                'customer_supplier.city',
                'state.state_name',
                'zone.zone_name',
               
            )
                ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_srn.customer_id')
                ->leftJoin('currency_exchange_rate', 'currency_exchange_rate.currency_id', '=', 'customer_supplier.currency')
                ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
                ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
                //   ->where(DB::raw("DATE_FORMAT(fgs_srn.srn_date, '%Y-%m')"), '>=', $date)
                  ->where('fgs_srn.dni_id', 0)
                ->get();

            if (($oef_data)) {
                if ($oef_data->rate) {
                    $total_rate = $oef_data['quantity'] * $oef_data['rate'];
                    $discount_value = $total_rate * $oef_data['discount'] / 100;
                    $taxable_value = $total_rate - $discount_value;
                    $igst_value = $total_rate * $oef_data['igst'] / 100;
                    $sgst_value = $total_rate * $oef_data['sgst'] / 100;
                    $cgst_value = $total_rate * $oef_data['cgst'] / 100;
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
            }
            if (($coef_data)) {
                if ($coef_data->rate) {
                    $total_rate = $coef_data['quantity'] * $coef_data['rate'];
                    $discount_value = $total_rate * $coef_data['discount'] / 100;
                    $taxable_value = $total_rate - $discount_value;
                    $igst_value = $total_rate * $coef_data['igst'] / 100;
                    $sgst_value = $total_rate * $coef_data['sgst'] / 100;
                    $cgst_value = $total_rate * $coef_data['cgst'] / 100;
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
            }
            if (($oef_data)) {
                $discount_value = number_format((float)($oef_data->rate * (float)$oef_qty) - (($oef_data->rate * (float)$oef_qty * $oef_data->discount) / 100));

                if (date('m', strtotime($oef_data->oef_date)) == 6 || date('m', strtotime($oef_data->oef_date)) == 5 || date('m', strtotime($oef_data->oef_date)) == 4) {
                    $qtr = "Q1";
                }
                if (date('m', strtotime($oef_data->oef_date)) == 7 || date('m', strtotime($oef_data->oef_date)) == 8 || date('m', strtotime($oef_data->oef_date)) == 9) {
                    $qtr = "Q2";
                }
                if (date('m', strtotime($oef_data->oef_date)) == 10 || date('m', strtotime($oef_data->oef_date)) == 11 || date('m', strtotime($oef_data->oef_date)) == 12) {
                    $qtr = "Q3";
                }
                if (date('m', strtotime($oef_data->oef_date)) == 1 || date('m', strtotime($oef_data->oef_date)) == 2 || date('m', strtotime($oef_data->oef_date)) == 3) {
                    $qtr = "Q4";
                }
                // } elseif ($oef_details) {

                //     $discount_value = number_format((float)($oef_details->rate * (float) $oef_qty) - (($oef_details->rate * (float)$oef_qty * $oef_details->discount) / 100));
                //     if (date('m', strtotime($oef_details->created_at)) == 06 || date('m', strtotime($oef_details->created_at)) == 05 || date('m', strtotime($oef_details->created_at)) == 04) {
                //         $qtr = "Q1";
                //     }
                //     if (date('m', strtotime($oef_details->created_at)) == 07 || date('m', strtotime($oef_details->created_at)) == 8 || date('m', strtotime($oef_details->created_at)) == 9) {
                //         $qtr = "Q2";
                //     }
                //     if (date('m', strtotime($oef_details->created_at)) == 10 || date('m', strtotime($oef_details->created_at)) == 11 || date('m', strtotime($oef_details->created_at)) == 12) {
                //         $qtr = "Q3";
                //     }
                // if (date('m', strtotime($oef_details->created_at)) == 01 || date('m', strtotime($oef_details->data->created_at)) == 02 || date('m', strtotime($oef_details->created_at)) == 03) {
                //     $qtr = "Q4";
                // }
            } else {
                $discount_value = 0;
                $qtr = "";
            }

            //fin year
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

            // $total_amount = $discount_value + (($discount_value * $cgst) / 100) + (($discount_value * $sgst) / 100) + (($discount_value * $igst) / 100);
            if ($oef_data) {
                $data[] = array(
                    'doc_type' => "OEF",
                    'doc_no' => $oef_number,
                    'doc_date' => date('d-m-Y', strtotime($oef_data->oef_date)),
                    'customer_name' => $oef_data->firm_name,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'order_no' => $oef_data->order_number,
                    'order_date' => date('d-m-Y', strtotime($oef_data->order_date)),
                    'qty' => $oef_qty,
                    'rate' => number_format((float)$oef_data->rate, 2, '.', ''),
                    'disc' => $oef_data->discount,
                    'disc_value' => $discount_value,
                    'Taxable_Value' => number_format((float)($taxable_value), 2, '.', ''),
                    'gst' => "IGST:" . $oef_data['igst'] . ", SGST:" . $oef_data['sgst'] . ", CGST:" . $oef_data['cgst'],
                    'gst_value' => $gst_value,
                    'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                    'Zone' => $oef_data->zone_name,
                    'State' => $oef_data->state_name,
                    'City' => $oef_data->city,
                    'Business Category' => $oef_data->category_name,
                    'Product Category' => $oef_data->new_category_name,
                    'Transaction Type' => $oef_data->transaction_name,
                    'Sales Type' => $oef_data->sales_type,
                    'Remarks' => $oef_data->remarks,
                    'Month' => date('F', strtotime($oef_data->created_at)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($oef_data->created_at)),
                    'FY(Financial Year)' => $financialYear


                );
            }


            if ($coef_data != '') {

                $data[] = array(
                    'doc_type' => "COEF",
                    'doc_no' => $coef_number,
                    'doc_date' => $coef_date,
                    'customer_name' => $coef_data->firm_name,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'order_no' => $coef_data->order_number,
                    'order_date' => '',
                    'qty' => $coef_qty,
                    'rate' => number_format((float)$coef_data->rate, 2, '.', ''),
                    'disc' => $coef_data->discount,
                    'disc_value' => $discount_value,
                    'Taxable_Value' => number_format((float)($taxable_value), 2, '.', ''),
                    'gst' => "IGST:" . $coef_data['igst'] . ", SGST:" . $coef_data['sgst'] . ", CGST:" . $coef_data['cgst'],
                    'gst_value' => $gst_value,
                    'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                    'Zone' => $coef_data->zone_name,
                    'State' => $coef_data->state_name,
                    'City' => $coef_data->city,
                    'Business Category' => $coef_data->category_name,
                    'Product Category' => $coef_data->new_category_name,
                    'Transaction Type' => $coef_data->transaction_name,
                    'Sales Type' => $coef_data->sales_type,
                    'Remarks' => $coef_data->remarks,
                    'Month' => date('F', strtotime($coef_data->created_at)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($coef_data->created_at)),
                    'FY(Financial Year)' => $financialYear


                );
            }
            if ($pi_datas) {

                $data[] = array(
                    'doc_type' => "PI",
                    'doc_no' => $pi_number,
                    'doc_date' => $pi_date,
                    'customer_name' => $pi_datas->firm_name,
                    'item_code' => $product_detail['sku_code'],
                    'description' => $product_detail['discription'],
                    'order_no' => $pi_datas->order_number,
                    'order_date' => '',
                    'qty' => $pi_qty,
                    'rate' => number_format((float)$pi_datas->rate, 2, '.', ''),
                    'disc' => $pi_datas->discount,
                    'disc_value' => $discount_value,
                    'Taxable_Value' => number_format((float)($taxable_value), 2, '.', ''),
                    'gst' => "IGST:" . $pi_datas['igst'] . ", SGST:" . $pi_datas['sgst'] . ", CGST:" . $pi_datas['cgst'],
                    'gst_value' => $gst_value,
                    'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                    'Zone' => $pi_datas->zone_name,
                    'State' => $pi_datas->state_name,
                    'City' => $pi_datas->city,
                    'Business Category' => $pi_datas->category_name,
                    'Product Category' => $pi_datas->new_category_name,
                    'Transaction Type' => $pi_datas->transaction_name,
                    'Sales Type' => $pi_datas->sales_type,
                    'Remarks' => $pi_datas->remarks,
                    'Month' => date('F', strtotime($pi_datas->created_at)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($pi_datas->created_at)),
                    'FY(Financial Year)' => $financialYear


                );
            }

                if ($cpi_datas != '') {

                    $data[] = array(
                        'doc_type' => "CPI",
                        'doc_no' => $cpi_number,
                        'doc_date' => $cpi_date,
                        'customer_name' => $cpi_datas->firm_name,
                        'item_code' => $product_detail['sku_code'],
                        'description' => $product_detail['discription'],
                        'order_no' => $cpi_datas->order_number,
                        'order_date' => '',
                        'qty' => $cpi_qty,
                        'rate' => number_format((float)$cpi_datas->rate, 2, '.', ''),
                        'disc' => $cpi_datas->discount,
                        'disc_value' => $discount_value,
                        'Taxable_Value' => number_format((float)($taxable_value), 2, '.', ''),
                        'gst' => "IGST:" . $cpi_datas['igst'] . ", SGST:" . $cpi_datas['sgst'] . ", CGST:" . $cpi_datas['cgst'],
                        'gst_value' => $gst_value,
                        'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                        'Zone' => $cpi_datas->zone_name,
                        'State' => $cpi_datas->state_name,
                        'City' => $cpi_datas->city,
                        'Business Category' => $cpi_datas->category_name,
                        'Product Category' => $cpi_datas->new_category_name,
                        'Transaction Type' => $cpi_datas->transaction_name,
                        'Sales Type' => $cpi_datas->sales_type,
                        'Remarks' => $cpi_datas->remarks,
                        'Month' => date('F', strtotime($cpi_datas->created_at)),
                        'Qtr' => $qtr,
                        'CY(Calender Year)' => date('Y', strtotime($cpi_datas->created_at)),
                        'FY(Financial Year)' => $financialYear


                    );
                }
                if ($dni_details != '') {

                    $data[] = array(
                        'doc_type' => "DNI",
                        'doc_no' => $dni_number,
                        'doc_date' => $dni_date,
                        'customer_name' => $dni_details->firm_name,
                        'item_code' => $product_detail['sku_code'],
                        'description' => $product_detail['discription'],
                        'order_no' => $dni_details->order_number,
                        'order_date' => '',
                        'qty' => $dni_qty,
                        'rate' => number_format((float)$dni_details->rate, 2, '.', ''),
                        'disc' => $dni_details->discount,
                        'disc_value' => $discount_value,
                        'Taxable_Value' => number_format((float)($taxable_value), 2, '.', ''),
                        'gst' => "IGST:" . $dni_details['igst'] . ", SGST:" . $dni_details['sgst'] . ", CGST:" . $dni_details['cgst'],
                        'gst_value' => $gst_value,
                        'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                        'Zone' => $dni_details->zone_name,
                        'State' => $dni_details->state_name,
                        'City' => $dni_details->city,
                        'Business Category' => $dni_details->category_name,
                        'Product Category' => $dni_details->new_category_name,
                        'Transaction Type' => $dni_details->transaction_name,
                        'Sales Type' => $dni_details->sales_type,
                        'Remarks' => $dni_details->remarks,
                        'Month' => date('F', strtotime($dni_details->created_at)),
                        'Qtr' => $qtr,
                        'CY(Calender Year)' => date('Y', strtotime($dni_details->created_at)),
                        'FY(Financial Year)' => $financialYear


                    );
                }
                if ($exi_details != '') {

                    $data[] = array(
                        'doc_type' => "EXI",
                        'doc_no' => $exi_number,
                        'doc_date' => $exi_date,
                        'customer_name' => $exi_details->firm_name,
                        'item_code' => $product_detail['sku_code'],
                        'description' => $product_detail['discription'],
                        'order_no' => $exi_details->order_number,
                        'order_date' => '',
                        'qty' => $exi_qty,
                        'rate' => number_format((float)$exi_details->rate, 2, '.', ''),
                        'disc' => $exi_details->discount,
                        'disc_value' => $discount_value,
                        'Taxable_Value' => number_format((float)($taxable_value), 2, '.', ''),
                        'gst' => "IGST:" . $exi_details['igst'] . ", SGST:" . $exi_details['sgst'] . ", CGST:" . $exi_details['cgst'],
                        'gst_value' => $gst_value,
                        'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                        'Zone' => $exi_details->zone_name,
                        'State' => $exi_details->state_name,
                        'City' => $exi_details->city,
                        'Business Category' => $exi_details->category_name,
                        'Product Category' => $exi_details->new_category_name,
                        'Transaction Type' => $exi_details->transaction_name,
                        'Sales Type' => $exi_details->sales_type,
                        'Remarks' => $exi_details->remarks,
                        'Month' => date('F', strtotime($exi_details->created_at)),
                        'Qtr' => $qtr,
                        'CY(Calender Year)' => date('Y', strtotime($exi_details->created_at)),
                        'FY(Financial Year)' => $financialYear


                    );
                }
                if ($srn_details != '') {

                    $data[] = array(
                        'doc_type' => "SRN",
                        'doc_no' => $srn_number,
                        'doc_date' => $srn_date,
                        'customer_name' => $srn_details->firm_name,
                        'item_code' => $product_detail['sku_code'],
                        'description' => $product_detail['discription'],
                        'order_no' => $srn_details->order_number,
                        'order_date' => '',
                        'qty' => $exi_qty,
                        'rate' => number_format((float)$srn_details->rate, 2, '.', ''),
                        'disc' => $srn_details->discount,
                        'disc_value' => $discount_value,
                        'Taxable_Value' => number_format((float)($taxable_value), 2, '.', ''),
                        'gst' => "IGST:" . $srn_details['igst'] . ", SGST:" . $srn_details['sgst'] . ", CGST:" . $srn_details['cgst'],
                        'gst_value' => $gst_value,
                        'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                        'Zone' => $srn_details->zone_name,
                        'State' => $srn_details->state_name,
                        'City' => $srn_details->city,
                        'Business Category' => $srn_details->category_name,
                        'Product Category' => $srn_details->_new_category_name,
                        'Transaction Type' => $srn_details->transaction_name,
                        'Sales Type' => $srn_details->sales_type,
                        'Remarks' => $srn_details->remarks,
                        'Month' => date('F', strtotime($srn_details->created_at)),
                        'Qtr' => $qtr,
                        'CY(Calender Year)' => date('Y', strtotime($srn_details->created_at)),
                        'FY(Financial Year)' => $financialYear


                    );
                }
            
        
        foreach ($man_srn_details as $man_srn_detail) {
            $data[] = array(
                'doc_type' => "SRN",
                'doc_no' => $man_srn_detail->srn_number,
                'doc_date' => $man_srn_detail->srn_date,
                'customer_name' => $man_srn_detail->firm_name,
                'item_code' => '',
                'description' => '',
                'order_no' => '',
                'order_date' => '',
                'qty' => '',
                'rate' => '',
                'disc' => '',
                'disc_value' => '',
                'Taxable_Value' => '',
                'gst' => "IGST:" .'0' . ", SGST:" . '0' . ", CGST:" . '0',
                'gst_value' => '',
                'Total Amount' => '',
                'Zone' => $man_srn_detail->zone_name,
                'State' => $man_srn_detail->state_name,
                'City' => '',
                'Product Category' => $man_srn_detail->category_name,
                'Transaction Type' => '',
                'Sales Type' => '',
                'Remarks' => '',
                'Month' => date('F', strtotime($man_srn_detail->created_at)),
                'Qtr' => $qtr,
                'CY(Calender Year)' => date('Y', strtotime($man_srn_detail->created_at)),
                'FY(Financial Year)' => $financialYear
            );
        }
    }
        //dd($data);
        return collect($data);
    }
    public function headings(): array
    {
        return [
            'Doc Type',
            'Doc No',

            'Doc Date',
            'Customer Name',
            'Item Code',
            'Description',
            'Order No',
            'Order Date',
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
            'Business Category',
            'Product Category',
            'Transaction Type',
            'Sales Type',
            'Remarks',
            'Month',
            'Qty',
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
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(15);
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
