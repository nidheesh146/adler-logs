<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class FGSexitransactionExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
            if($item->rate)
            {
                $total_rate = $item['quantity']*$item['rate'];
                $discount_value = $total_rate*$item['discount']/100;
                $taxable_value = $total_rate-$discount_value;
                $igst_value = $taxable_value*$item['igst']/100;
                $sgst_value = $taxable_value*$item['sgst']/100;
                $cgst_value = $taxable_value*$item['cgst']/100;
                $gst_value = $igst_value+$cgst_value+$sgst_value;
                $total_value = $taxable_value+$igst_value+$cgst_value+$sgst_value;
                
            }
            else
            {
                $taxable_value = 0;
                $igst_value = 0;
                $sgst_value = 0;
                $cgst_value = 0;
                $gst_value = 0;
                $discount_value =0;
                $total_value = 0;
            }
            

            if (date('m', strtotime($item->dni_date)) == 6 || date('m', strtotime($item->dni_date)) == 5 || date('m', strtotime($item->dni_date)) == 4) {
                $qtr = "Q1";
            }
            if (date('m', strtotime($item->dni_date)) == 7 || date('m', strtotime($item->dni_date)) == 8 || date('m', strtotime($item->dni_date)) == 9) {
                $qtr = "Q2";
            }
            if (date('m', strtotime($item->dni_date)) == 10 || date('m', strtotime($item->dni_date)) == 11 || date('m', strtotime($item->dni_date)) == 12) {
                $qtr = "Q3";
            }
            if (date('m', strtotime($item->dni_date)) == 1 || date('m', strtotime($item->dni_date)) == 2 || date('m', strtotime($item->dni_date)) == 3) {
                $qtr = "Q4";
            }
            //fin year
            $currentDate = Carbon::now();
            $currentYear = date('Y', strtotime($item->dni_date));

            if (date('m', strtotime($item->dni_date)) >= 4) {
                $startYear = $currentYear;
                $endYear = $currentYear + 1;
            } else {
                $startYear = $currentYear - 1;
                $endYear = $currentYear;
            }
            $financialYear = $startYear . '-' . substr($endYear, -2);

            $data[] = array(
                '#' => $i++,
                'doc_no' => $item->dni_number,
                'doc_date' => date('d-m-Y',strtotime($item->dni_date)),
                'customer_name' => $item->firm_name,
                'hsn' => $item['hsn_code'],
                'item_code' => $item['sku_code'],
                'description' => $item['discription'],
                'order_no' => $item->order_number,
                'order_date' => date('d-m-Y', strtotime($item->order_date)),
                'qty' => $item->quantity,
                'rate' => number_format((float)$item->rate, 2, '.', ''),
                'disc' => $item->discount,
                'disc_value' => $discount_value,
                'Taxable_Value' => number_format((float)$taxable_value, 2, '.', ''),
                 'gst' =>'',
                 'gst_value' => '',
                'Total Amount' => number_format((float)($taxable_value), 2, '.', ''),
                'Zone' => $item->zone_name,
                'State' => $item->state_name,
                'City' => $item->city,
                'Business Category' => $item->category_name,
                'Product Category' => $item->new_category_name,
                'Product Group' => $item->group1_name,
                'Transaction Type' => $item->transaction_name,
                'Sales Type' => $item->sales_type,
                
                'Month' => date('F', strtotime($item->created_at)),
                'Qtr' => $qtr,
                'CY(Calender Year)' => date('Y', strtotime($item->created_at)),
                'FY(Financial Year)' => $financialYear,
                'OEF Remarks' => $item->oef_remarks,
                'DNI Remarks' => $item->remarks,
         
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
            'OEF Remarks',
            'EXI Remarks',
            
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