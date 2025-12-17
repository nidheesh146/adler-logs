<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class FgsnetBillingallExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    private $cpi_items;
    private $cgrs_items;
    private $oef_items;
    private $srn_items;
    private $exi_items;
    private $man_srn;

    public function __construct($oef_items,$man_srn)
    {
        // $this->cpi_items = $cpi_items;
        // $this->cgrs_items = $cgrs_items;
        $this->oef_items = $oef_items;
        $this->man_srn = $man_srn;
        // $this->exi_items = $exi_items;
        // $this->dni_items = $dni_items;
    }
    public function collection()
    {
        $i = 1;
        $data = [];
         // fin yr
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
         
        foreach ($this->oef_items as $oef) {
            // dd($oef);

            $netbilling = 0;
            $netbk = 0;
            if ($oef->rate) {
                $total_rate = $oef['quantity'] * $oef['rate'];
                $discount_value = $total_rate * $oef['discount'] / 100;
                $taxable_value = $total_rate - $discount_value;
                $igst_value = $taxable_value * $oef['igst'] / 100;
                $sgst_value = $taxable_value * $oef['sgst'] / 100;
                $cgst_value = $taxable_value * $oef['cgst'] / 100;
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
            $oeftt = $total_value;
            if ($oef->coef_number) {
                if ($oef->rate) {
                    $total_rate = $oef['coefqty'] * $oef['rate'];
                    $discount_value_coef = $total_rate * $oef['discount'] / 100;
                    $taxable_value_coef = $total_rate - $discount_value_coef;
                    $igst_value_coef = $taxable_value_coef * $oef['igst'] / 100;
                    $sgst_value_coef = $taxable_value_coef * $oef['sgst'] / 100;
                    $cgst_value_coef = $taxable_value_coef * $oef['cgst'] / 100;
                    $gst_value_coef = $igst_value_coef + $cgst_value_coef + $sgst_value_coef;
                    $total_value_coef = $taxable_value + $igst_value_coef + $cgst_value_coef + $sgst_value_coef;
                } else {
                    $taxable_value_coef = 0;
                    $igst_value_coef = 0;
                    $sgst_value_coef = 0;
                    $cgst_value_coef = 0;
                    $gst_value_coef = 0;
                    $discount_value_coef = 0;
                    $total_value_coef = 0;
                }
                $coef = $total_value;
            } else {
                $coef = 0;
            }
            if ($oef->cgrs_number) {

                if ($oef->rate) {
                    $total_rate = $oef['cgrsqty'] * $oef['rate'];
                    $discount_value_cgrs = $total_rate * $oef['discount'] / 100;
                    $taxable_value_cgrs = $total_rate - $discount_value_cgrs;
                    $igst_value_cgrs = $taxable_value_cgrs * $oef['igst'] / 100;
                    $sgst_value_cgrs = $taxable_value_cgrs * $oef['sgst'] / 100;
                    $cgst_value_cgrs = $taxable_value_cgrs * $oef['cgst'] / 100;
                    $gst_value_cgrs = $igst_value_cgrs + $cgst_value + $sgst_value;
                    $total_value_cgrs = $taxable_value + $cgst_value_cgrs + $igst_value_cgrs + $sgst_value_cgrs;
                } else {
                    $taxable_value_cgrs = 0;
                    $igst_value_cgrs = 0;
                    $sgst_value_cgrs = 0;
                    $cgst_value_cgrs = 0;
                    $gst_value_cgrs = 0;
                    $discount_value_cgrs = 0;
                    $total_value_cgrs = 0;
                }
                $cgrs = $total_value_cgrs;
            } else {
                $cgrs = 0;
            }
            if ($oef->cpi_number) {

                if ($oef->rate) {
                    $total_rate = $oef['cpiqty'] * $oef['rate'];
                    $discount_value_cpi = $total_rate * $oef['discount'] / 100;
                    $taxable_value_cpi = $total_rate - $discount_value_cpi;
                    $igst_value_cpi = $taxable_value_cpi * $oef['igst'] / 100;
                    $sgst_value_cpi = $taxable_value_cpi * $oef['sgst'] / 100;
                    $cgst_value_cpi = $taxable_value_cpi * $oef['cgst'] / 100;
                    $gst_value_cpi = $igst_value_cpi + $sgst_value_cpi + $cgst_value_cpi;
                    $total_value_cpi = $taxable_value_cpi + $igst_value_cpi + $sgst_value_cpi + $cgst_value_cpi;
                } else {
                    $taxable_value_cpi = 0;
                    $igst_value_cpi = 0;
                    $sgst_value_cpi = 0;
                    $cgst_value_cpi = 0;
                    $gst_value_cpi = 0;
                    $discount_value_cpi = 0;
                    $total_value_cpi = 0;
                }
                $cpi = $total_value_cpi;
            } else {
                $cpi = 0;
            }
            if ($oef->dni_number && $oef->dni_exi == 'DNI') {
                if ($oef->rate) {
                    $total_rate = $oef['dniqty'] * $oef['rate'];
                    $discount_value_dni = $total_rate * $oef['discount'] / 100;
                    $taxable_value_dni = $total_rate - $discount_value_dni;
                    $igst_value_dni = $taxable_value_dni * $oef['igst'] / 100;
                    $sgst_value_dni = $taxable_value_dni * $oef['sgst'] / 100;
                    $cgst_value_dni = $taxable_value_dni * $oef['cgst'] / 100;
                    $gst_value_dni = $igst_value_dni + $sgst_value_dni + $cgst_value_dni;
                    $total_value_dni = $taxable_value_dni + $igst_value_dni + $cgst_value_dni + $sgst_value_dni;
                } else {
                    $taxable_value_dni = 0;
                    $igst_value_dni = 0;
                    $cgst_value_dni = 0;
                    $sgst_value_dni = 0;
                    $gst_value_dni = 0;
                    $discount_value_dni = 0;
                    $total_value_dni = 0;
                }
                $dni = $total_value_dni;
            } else {
                $dni = 0;
            }
            if ($oef->dni_number && $oef->dni_exi == 'EXI') {

                if ($oef->rate) {
                    $total_rate = $oef['dniqty'] * $oef['rate'];
                    $discount_value_exi = $total_rate * $oef['discount'] / 100;
                    $taxable_value_exi = $total_rate - $discount_value_exi;
                    $igst_value_exi = $taxable_value_exi * $oef['igst'] / 100;
                    $sgst_value_exi = $taxable_value_exi * $oef['sgst'] / 100;
                    $cgst_value_exi = $taxable_value_exi * $oef['cgst'] / 100;
                    $gst_value_exi = $igst_value_exi + $sgst_value_exi + $cgst_value_exi;
                    $total_value_exi = $taxable_value_exi + $igst_value_exi + $cgst_value_exi + $sgst_value_exi;
                } else {
                    $taxable_value_exi = 0;
                    $igst_value_exi = 0;
                    $cgst_value_exi = 0;
                    $sgst_value_exi = 0;
                    $gst_value_exi = 0;
                    $discount_value_exi = 0;
                    $total_value_exi = 0;
                }
                $exi = $total_value;
            } else {
                $exi = 0;
            }
            if ($oef->srn_number) {

                if ($oef->rate) {
                    $total_rate = $oef['srnqty'] * $oef['rate'];
                    $discount_value_srn = $total_rate * $oef['discount'] / 100;
                    $taxable_value_srn = $total_rate - $discount_value_srn;
                    $igst_value_srn = $taxable_value_srn * $oef['igst'] / 100;
                    $sgst_value_srn = $taxable_value_srn * $oef['sgst'] / 100;
                    $cgst_valuesrn = $taxable_value_srn * $oef['cgst'] / 100;
                    $gst_value_srn = $igst_value_srn + $sgst_value_srn + $cgst_valuesrn;
                    $total_value_srn = $taxable_value_srn + $igst_value_srn + $cgst_valuesrn + $sgst_value_srn;
                } else {
                    $taxable_value_srn = 0;
                    $igst_value_srn = 0;
                    $sgst_value_srn = 0;
                    $cgst_valuesrn = 0;
                    $gst_value_srn = 0;
                    $discount_value_srn = 0;
                    $total_value_srn = 0;
                }
                $srn = $total_value_srn;
            } else {
                $srn = 0;
            }

            if (date('m', strtotime($oef->oef_date)) == 6 || date('m', strtotime($oef->oef_date)) == 5 || date('m', strtotime($oef->oef_date)) == 4) {
                $qtr = "Q1";
            }
            if (date('m', strtotime($oef->oef_date)) == 7 || date('m', strtotime($oef->oef_date)) == 8 || date('m', strtotime($oef->oef_date)) == 9) {
                $qtr = "Q2";
            }
            if (date('m', strtotime($oef->oef_date)) == 10 || date('m', strtotime($oef->oef_date)) == 11 || date('m', strtotime($oef->oef_date)) == 12) {
                $qtr = "Q3";
            }
            if (date('m', strtotime($oef->oef_date)) == 1 || date('m', strtotime($oef->oef_date)) == 2 || date('m', strtotime($oef->oef_date)) == 3) {
                $qtr = "Q4";
            }
           
            $netbk = $oeftt - $coef - $cgrs - $cpi;
            $netbilling = $dni + $exi - $srn;
           
            $data[] = array(
                '#' => $i++,
                'doc_name' => 'OEF',
                'Doc_number' => $oef->oef_number,
                'Date'  => date('d-m-Y', strtotime($oef->oef_date)),
                'Customer' => $oef->firm_name,
                'SKUCode' => $oef->sku_code,
                'Description' => $oef->discription,
                'HSN_Code' => $oef->hsn_code,
                'order_no' => $oef->order_number,
                'order_date' => date('d-m-Y', strtotime($oef->order_date)),
                'QTY' => $oef->quantity,
                'Rate' => $oef->rate,
                'disc' => $oef->discount,
                'disc_value' => $discount_value,
                'Taxable_Value' => number_format((float)$taxable_value, 2, '.', ''),
                'gst' => "IGST:" . $oef['igst'] . ", SGST:" . $oef['sgst'] . ", CGST:" . $oef['cgst'],
                'gst_value' => $gst_value,
                'Total Amount' => number_format((float)($total_value), 2, '.', ''),
                'Zone' => $oef->zone_name,
                'State' => $oef->state_name,
                'City' => $oef->city,
                'Product Category' => $oef->category_name,
                'Transaction grp' => $oef->group_name,
                'Transaction Type' => $oef->transaction_name,
                'Sales Type' => $oef->sales_type,
                'Month' => date('F', strtotime($oef->oef_date)),
                'Qtr' => $qtr,
                'CY(Calender Year)' => date('Y', strtotime($oef->oef_date)),
                'FY(Financial Year)' => $financialYear,
                'net_billing' => $netbilling,
                'net_bk' => $netbk,

            );

            if ($oef->coef_number) {

                $data[] = array(
                    '#' => $i++,
                    'doc_name' => 'COEF',
                    'Doc_number' => $oef->coef_number,
                    'Date'  => date('d-m-Y', strtotime($oef->coef_date)),
                    'Customer' => $oef->firm_name,
                    'SKUCode' => $oef->sku_code,
                    'Description' => $oef->discription,
                    'HSN_Code' => $oef->hsn_code,
                    'order_no' => $oef->order_number,
                    'order_date' => date('d-m-Y', strtotime($oef->order_date)),
                    'QTY' => $oef->coefqty,
                    'Rate' => $oef->rate,
                    'disc' => $oef->discount,
                    'disc_value' => $discount_value_coef,
                    'Taxable_Value' => number_format((float)$taxable_value_coef, 2, '.', ''),
                    'gst' => "IGST:" . $oef['igst'] . ", SGST:" . $oef['sgst'] . ", CGST:" . $oef['cgst'],
                    'gst_value' => $gst_value,
                    'Total Amount' => number_format((float)($total_value_coef), 2, '.', ''),
                    'Zone' => $oef->zone_name,
                    'State' => $oef->state_name,
                    'City' => $oef->city,
                    'Business Catgeory'  => $oef->category_name,

                    'Product Category' => $oef->category_name,
                    'Transaction grp' => $oef->group_name,
                'Transaction Type' => $oef->transaction_name,
                'Sales Type' => $oef->sales_type,
                    'Month' => date('F', strtotime($oef->coef_date)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($oef->coef_date)),
                    'FY(Financial Year)' => $financialYear

                );
            }
            if ($oef->cgrs_number) {

                $data[] = array(
                    '#' => $i++,
                    'doc_name' => 'CGRS',
                    'Doc_number' => $oef->cgrs_number,
                    'Date'  => date('d-m-Y', strtotime($oef->cgrs_date)),
                    'Customer' => $oef->firm_name,
                    'SKUCode' => $oef->sku_code,
                    'Description' => $oef->discription,
                    'HSN_Code' => $oef->hsn_code,
                    'order_no' => $oef->order_number,
                    'order_date' => date('d-m-Y', strtotime($oef->order_date)),
                    'QTY' => $oef->cgrsqty,
                    'Rate' => $oef->rate,
                    'disc' => $oef->discount,
                    'disc_value' => $discount_value_cgrs,
                    'Taxable_Value' => number_format((float)$taxable_value_cgrs, 2, '.', ''),
                    'gst' => "IGST:" . $oef['igst'] . ", SGST:" . $oef['sgst'] . ", CGST:" . $oef['cgst'],
                    'gst_value' => $gst_value,
                    'Total Amount' => number_format((float)($total_value_cgrs), 2, '.', ''),
                    'Zone' => $oef->zone_name,
                    'State' => $oef->state_name,
                    'City' => $oef->city,
                    'Product Category' => $oef->category_name,
                    'Transaction grp' => $oef->group_name,
                'Transaction Type' => $oef->transaction_name,
                'Sales Type' => $oef->sales_type,
                    'Month' => date('F', strtotime($oef->cgrs_date)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($oef->cgrs_date)),
                    'FY(Financial Year)' => $financialYear
                );
            }

            if ($oef->cpi_number) {

                $data[] = array(
                    '#' => $i++,
                    'doc_name' => 'CPI',
                    'Doc_number' => $oef->cpi_number,
                    'Date'  => date('d-m-Y', strtotime($oef->cpi_date)),
                    'Customer' => $oef->firm_name,
                    'SKUCode' => $oef->sku_code,
                    'Description' => $oef->discription,
                    'HSN_Code' => $oef->hsn_code,
                    'order_no' => $oef->order_number,
                    'order_date' => date('d-m-Y', strtotime($oef->order_date)),
                    'QTY' => $oef->cpiqty,
                    'Rate' => $oef->rate,
                    'disc' => $oef->discount,
                    'disc_value' => $discount_value_cpi,
                    'Taxable_Value' => number_format((float)$taxable_value_cpi, 2, '.', ''),
                    'gst' => "IGST:" . $oef['igst'] . ", SGST:" . $oef['sgst'] . ", CGST:" . $oef['cgst'],
                    'gst_value' => $gst_value,
                    'Total Amount' => number_format((float)($total_value_cpi), 2, '.', ''),
                    'Zone' => $oef->zone_name,
                    'State' => $oef->state_name,
                    'City' => $oef->city,
                    'Business Category' => $oef->category_name,
                    'Product Category' => $oef->new_category_name,
                    'Transaction grp' => $oef->group_name,
                    'Transaction Type' => $oef->transaction_name,
                    'Sales Type' => $oef->sales_type,
                    'Month' => date('F', strtotime($oef->cpi_date)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($oef->cpi_date)),
                    'FY(Financial Year)' => $financialYear

                );
            }

            if ($oef->dni_number && $oef->dni_exi == 'DNI') {

                $data[] = array(
                    '#' => $i++,
                    'doc_name' => 'DNI',
                    'Doc_number' => $oef->dni_number,
                    'Date'  => date('d-m-Y', strtotime($oef->dni_date)),
                    'Customer' => $oef->firm_name,
                    'SKUCode' => $oef->sku_code,
                    'Description' => $oef->discription,
                    'HSN_Code' => $oef->hsn_code,
                    'order_no' => $oef->order_number,
                    'order_date' => date('d-m-Y', strtotime($oef->order_date)),
                    'QTY' => $oef->dniqty,
                    'Rate' => $oef->rate,
                    'disc' => $oef->discount,
                    'disc_value' => $discount_value_dni,
                    'Taxable_Value' => number_format((float)$taxable_value_dni, 2, '.', ''),
                    'gst' => "IGST:" . $oef['igst'] . ", SGST:" . $oef['sgst'] . ", CGST:" . $oef['cgst'],
                    'gst_value' => $gst_value,
                    'Total Amount' => number_format((float)($total_value_dni), 2, '.', ''),
                    'Zone' => $oef->zone_name,
                    'State' => $oef->state_name,
                    'City' => $oef->city,
                    'Business Category' => $oef->category_name,
                    'Product Category' => $oef->new_category_name,
                    'Transaction grp' => $oef->group_name,
                'Transaction Type' => $oef->transaction_name,
                'Sales Type' => $oef->sales_type,
                    'Month' => date('F', strtotime($oef->dni_date)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($oef->dni_date)),
                    'FY(Financial Year)' => $financialYear


                );
            }

            if ($oef->dni_number && $oef->dni_exi == 'EXI') {

                $data[] = array(
                    '#' => $i++,
                    'doc_name' => 'EXI',
                    'Doc_number' => $oef->dni_number,
                    'Date'  => date('d-m-Y', strtotime($oef->dni_date)),
                    'Customer' => $oef->firm_name,
                    'SKUCode' => $oef->sku_code,
                    'Description' => $oef->discription,
                    'HSN_Code' => $oef->hsn_code,
                    'order_no' => $oef->order_number,
                    'order_date' => date('d-m-Y', strtotime($oef->order_date)),
                    'QTY' => $oef->dniqty,
                    'Rate' => $oef->rate,
                    'disc' => $oef->discount,
                    'disc_value' => $discount_value_exi,
                    'Taxable_Value' => number_format((float)$taxable_value_exi, 2, '.', ''),
                    'gst' => "IGST:" . $oef['igst'] . ", SGST:" . $oef['sgst'] . ", CGST:" . $oef['cgst'],
                    'gst_value' => $gst_value,
                    'Total Amount' => number_format((float)($total_value_exi), 2, '.', ''),
                    'Zone' => $oef->zone_name,
                    'State' => $oef->state_name,
                    'City' => $oef->city,
                    'Business Category' => $oef->category_name,
                    'Product Category' => $oef->new_category_name,
                    'Transaction grp' => $oef->group_name,
                'Transaction Type' => $oef->transaction_name,
                'Sales Type' => $oef->sales_type,
                    'Month' => date('F', strtotime($oef->dni_date)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($oef->dni_date)),
                    'FY(Financial Year)' => $financialYear

                );
            }

            if ($oef->srn_number) {



                $data[] = array(
                    '#' => $i++,
                    'doc_name' => 'SRN',
                    'Doc_number' => $oef->srn_number,
                    'Date'  => date('d-m-Y', strtotime($oef->srn_date)),
                    'Customer' => $oef->firm_name,
                    'SKUCode' => $oef->sku_code,
                    'Description' => $oef->discription,
                    'HSN_Code' => $oef->hsn_code,
                    'order_no' => $oef->order_number,
                    'order_date' => date('d-m-Y', strtotime($oef->order_date)),
                    'QTY' => $oef->srnqty,
                    'Rate' => $oef->rate,
                    'disc' => $oef->discount,
                    'disc_value' => $discount_value_srn,
                    'Taxable_Value' => number_format((float)$taxable_value_srn, 2, '.', ''),
                    'gst' => "IGST:" . $oef['igst'] . ", SGST:" . $oef['sgst'] . ", CGST:" . $oef['cgst'],
                    'gst_value' => $gst_value_srn,
                    'Total Amount' => number_format((float)($total_value_srn), 2, '.', ''),
                    'Zone' => $oef->zone_name,
                    'State' => $oef->state_name,
                    'City' => $oef->city,
                    'Business Category' => $oef->category_name,
                    'Product Category' => $oef->new_category_name,
                    'Transaction grp' => $oef->group_name,
                'Transaction Type' => $oef->transaction_name,
                'Sales Type' => $oef->sales_type,
                    'Month' => date('F', strtotime($oef->srn_date)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($oef->srn_date)),
                    'FY(Financial Year)' => $financialYear

                );
            }
        }
        foreach ($this->man_srn as $man_srn) {
            if ($man_srn->srn_number) {

                if (date('m', strtotime($man_srn->srn_date)) == 6 || date('m', strtotime($man_srn->srn_date)) == 5 || date('m', strtotime($man_srn->srn_date)) == 4) {
                    $qtr = "Q1";
                }
                if (date('m', strtotime($man_srn->srn_date)) == 7 || date('m', strtotime($man_srn->srn_date)) == 8 || date('m', strtotime($man_srn->srn_date)) == 9) {
                    $qtr = "Q2";
                }
                if (date('m', strtotime($man_srn->srn_date)) == 10 || date('m', strtotime($man_srn->srn_date)) == 11 || date('m', strtotime($man_srn->srn_date)) == 12) {
                    $qtr = "Q3";
                }
                if (date('m', strtotime($man_srn->srn_date)) == 1 || date('m', strtotime($man_srn->srn_date)) == 2 || date('m', strtotime($man_srn->srn_date)) == 3) {
                    $qtr = "Q4";
                }

                $data[] = array(
                    '#' => $i++,
                    'doc_name' => 'SRN',
                    'Doc_number' => $man_srn->srn_number,
                    'Date'  => date('d-m-Y', strtotime($man_srn->srn_date)),
                    'Customer' => $man_srn->firm_name,
                    'SKUCode' => '',
                    'Description' => '',
                    'HSN_Code' => '',
                    'order_no' => '',
                    'order_date' => '',
                    'QTY' => '',
                    'Rate' => '',
                    'disc' => '',
                    'disc_value' => '',
                    'Taxable_Value' => '',
                    'gst' => '',
                    'gst_value' => '',
                    'Total Amount' => '',
                    'Zone' => $man_srn->zone_name,
                    'State' => $man_srn->state_name,
                    'City' => $man_srn->city,
                    'Business Category' => '',
                    'Product Category' => '',
                    'Transaction grp' => '',
                'Transaction Type' => '',
                'Sales Type' => $man_srn->sales_type,
                    'Month' => date('F', strtotime($man_srn->srn_date)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($man_srn->srn_date)),
                    'FY(Financial Year)' => $financialYear

                );
            }
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'Doc Name',
            'Doc No',
            'Date',
            'Customer',
            'SKU Code',
            'Description',
            'HSN Code',
            'Order No',
            'Order Date',
            'Qty',
            'Rate',
            'Disc.',
            'Disc Value',
            'Taxable Value',
            'GST',
            'GST Value',
            'Total Amount',
            'Zone',
            'State',
            'City',
            'Product Category',
            'Product Group',
            'Transaction Type',
            'Sales Type',
            'Month',
            'Qty',
            'CY(Calender Year)',
            'FY(Financial Year)',
            'Net Billing',
            'Net Booking'


            // '#',
            // 'GRS Number',
            // 'GRS Date',
            // 'OEF Number',
            // 'OEF Date',
            // 'Order Number',
            // 'Order Date',
            // 'Product Sku Code',
            // 'HSNCode',
            // 'Description',
            // 'Quantity',
            // 'Outstanding Quantity',
            // 'Unit',
            // 'Manufacturing date',
            // 'Expiry date',
            // 'Customer',
            // 'WEF',
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
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);


                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
