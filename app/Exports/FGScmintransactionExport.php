<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Carbon\Carbon;

class FGScmintransactionExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $items;

    public function __construct($items) 
    {
        $this->items = $items;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach($this->items as $item)
        {
            
            if (date('m', strtotime($item->grs_date)) == 6 || date('m', strtotime($item->grs_date)) == 5 || date('m', strtotime($item->grs_date)) == 4) {
                $qtr = "Q1";
            }
            if (date('m', strtotime($item->grs_date)) == 7 || date('m', strtotime($item->grs_date)) == 8 || date('m', strtotime($item->grs_date)) == 9) {
                $qtr = "Q2";
            }
            if (date('m', strtotime($item->grs_date)) == 10 || date('m', strtotime($item->grs_date)) == 11 || date('m', strtotime($item->grs_date)) == 12) {
                $qtr = "Q3";
            }
            if (date('m', strtotime($item->grs_date)) == 1 || date('m', strtotime($item->grs_date)) == 2 || date('m', strtotime($item->grs_date)) == 3) {
                $qtr = "Q4";
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

            $data[] = array(
                '#' => $i++,
                'doc_no' => $item->cmin_number,
                    'doc_date' => date('d-m-Y', strtotime($item->cmin_date)),
                    'customer_name' => $item->firm_name,
                    'item_code' => $item['sku_code'],
                    'description' => $item['discription'],
                    // 'order_no' => $oef_details->order_number,
                    // 'order_date' => date('d-m-Y', strtotime($oef_details->order_date)),
                    'qty' => $item->quantity,
                    // 'rate' => number_format((float)$oef_details->rate, 2, '.', ''),
                    // 'disc' => $oef_details->discount,
                    // 'disc_value' => $discount_value,
                    // 'Taxable_Value' => number_format((float)$discount_value, 2, '.', ''),
                    // 'gst' => $cgst_i . ',' . $sgst_i . ',' . $igst_i,
                    // 'gst_value' => $gst_value,
                    // 'Total Amount' => $total_amount,
                    'Zone' => $item->zone_name,
                    'State' => $item->state_name,
                    'City' => $item->city,
                    'Product Category' => $item->category_name,
                    'Business Category' => $item->new_category_name,
                    // 'Transaction Type' => $mrn_datas->transaction_name,
                    'Sales Type' => $item->sales_type,
                    'Remarks' => $item->remarks,
                    'Month' => date('F', strtotime($item->cmin_date)),
                    'Qtr' => $qtr,
                    'CY(Calender Year)' => date('Y', strtotime($item->cmin_date)),
                    'FY(Financial Year)' => $financialYear
          
         
            );
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
            'Item Code',
            'Description',
           
            'Qty',
            
            'Zone',
            'State',
            'City',
            'Business Category',
            'Product Category',
            
            //'Transaction Type',
            'Sales Type',
            'Remarks',
            'Month',
            'Qty',
            'CY(Calender Year)',
            'FY(Financial Year)'
            
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
        1    => ['font' => ['size' => 12,'bold' => true]],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                
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

