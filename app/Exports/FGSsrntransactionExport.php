<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class FGSsrntransactionExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $items;

    public function __construct($items) 
    {
        $this->items = $items;
    }
    public function collection()
    {
        $i = 1;
        $data = [];
        
        foreach ($this->items as $item) {
            if ($item->rate) {
                $total_rate = $item->quantity * $item->rate;
                $discount_value = $total_rate * $item->discount / 100;
                $taxable_value = $total_rate - $discount_value;
                $igst_value = $taxable_value * $item->igst / 100;
                $sgst_value = $taxable_value * $item->sgst / 100;
                $cgst_value = $taxable_value * $item->cgst / 100;
                $gst_value = $igst_value + $cgst_value + $sgst_value;
                $total_value = $taxable_value + $gst_value;
            } else {
                $taxable_value = $igst_value = $sgst_value = $cgst_value = $gst_value = $discount_value = $total_value = 0;
            }
    
            // Fetch other charges and calculate grand total
            $other_charges = $item->other_charges;
            if ($item->calc_unit == 1) {
                $other_charges_value = ($total_value * $other_charges) / 100;
            } else {
                $other_charges_value = $other_charges;
            }
            $grand_total = $total_value - $other_charges_value;
    
            // Order details
            if (isset($item->oef_number)) {
                $order_no = $item->order_number;
                $order_date = date('d-m-Y', strtotime($item->order_date));
                $oef_remarks = $item->oef_remarks;
                $transaction_name = $item->transaction_name;
            } else {
                $order_no = "-";
                $order_date = "-";
                $oef_remarks = "-";
                $transaction_name = "-";
            }
    
            // Quarter calculation
            $month = date('m', strtotime($item->srn_date));
            if (in_array($month, [4, 5, 6])) {
                $qtr = "Q1";
            } elseif (in_array($month, [7, 8, 9])) {
                $qtr = "Q2";
            } elseif (in_array($month, [10, 11, 12])) {
                $qtr = "Q3";
            } else {
                $qtr = "Q4";
            }
    
            // Financial Year calculation
            $currentYear = date('Y', strtotime($item->srn_date));
            if ($month >= 4) {
                $startYear = $currentYear;
                $endYear = $currentYear + 1;
            } else {
                $startYear = $currentYear - 1;
                $endYear = $currentYear;
            }
            $financialYear = $startYear . '-' . substr($endYear, -2);
    
            // Data array
            $data[] = [
                '#' => $i++,
                'doc_no' => $item->srn_number,
                'doc_date' => date('d-m-Y', strtotime($item->srn_date)),
                'customer_name' => $item->firm_name,
                'hsn_code' => $item->hsn_code,
                'item_code' => $item->sku_code,
                'description' => $item->discription,
                'order_no' => $order_no,
                'order_date' => $order_date,
                'qty' => $item->quantity,
                'rate' => number_format($item->rate, 2, '.', ''),
                'disc' => $item->discount,
                'disc_value' => $discount_value,
                'Taxable_Value' => number_format($taxable_value, 2, '.', ''),
                'gst' => "IGST:" . $item->igst . ", SGST:" . $item->sgst . ", CGST:" . $item->cgst,
                'gst_value' => $gst_value,
                'Total Amount' => number_format($total_value, 2, '.', ''),
                'Other Charges' => number_format($other_charges_value, 2, '.', ''),
                'Grand Total' => number_format($grand_total, 2, '.', ''),
                'Zone' => $item->zone_name,
                'State' => $item->state_name,
                'City' => $item->city,
                'Business Category' =>$item->category_name,
                'Product Category' => $item->new,
                'Product Group' => $item->group1_name,
                // 'Transaction Type' => $transaction_name,
                'Transaction Type' =>'Sales Return',
                'Sales Type' => $item->sales_type,
                'Month' => date('F', strtotime($item->created_at)),
                'Qtr' => $qtr,
                'CY(Calender Year)' => date('Y', strtotime($item->created_at)),
                'FY(Financial Year)' => $financialYear,
                'SRN Remarks' => $oef_remarks,
                'DNI Remarks' => $item->remarks,
            ];
        }
        return collect($data);
    }
    
    public function headings(): array
        {
        return [
            '#',
            'Doc No',
            'Doc Date',
            'Customer Name',
            'HSN Code',
            'Item Code',
            'Description',
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
            'Other charges',
            'Grand Total',
            'Zone',
            'State',
            'City',
            'Business Category',
            'Product Category',
            'Product Group',
            'Transaction Type',
            'Sales Type',
            'Month',
            'Qtr',
            'CY(Calender Year)',
            'FY(Financial Year)',
            'SRN Remarks',
        ];
    }
    public function styles(Worksheet $sheet)
    {   
        
        return [
        // Style the first row as bold text.
        1    => ['font' => ['size' => 12,'bold' => true]],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('X')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Z')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('AA')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('AB')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('AC')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('AD')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('AE')->setWidth(50);
               
              
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }

}