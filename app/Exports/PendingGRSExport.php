<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PendingGRSExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $grs_data;

    public function __construct($grs_data) 
    {
        $this->grs_data = $grs_data;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach($this->grs_data as $item)
        {
            if($item['expiry_date']=='0000-00-00') 
            $expiry = 'NA'; 
            else 
            $expiry = date('d-m-Y',strtotime($item['expiry_date']));
            if($item->rate)
            {
                $total_rate = $item['remaining_qty_after_cancel']*$item['rate'];
                $discount_value = $total_rate*$item['discount']/100;
                $discounted_value = $total_rate-$discount_value;
                $igst_value = $total_rate*$item['igst']/100;
                $sgst_value = $total_rate*$item['sgst']/100;
                $cgst_value = $total_rate*$item['cgst']/100;
                $total_value = $discounted_value+$igst_value+$cgst_value+$sgst_value;
                
            }
            $data[]= array(
                '#' => $i++,
                'Doc_Date' =>date('d-m-Y', strtotime($item['grs_date'])),
                'Doc_No' => $item['grs_number'],
                'Customer_Name' => $item['firm_name'],
                'Order_No' => $item['order_number'],
                'Order_Date' =>date('d-m-Y', strtotime($item['order_date'])),
                'Item_Code' => $item['sku_code'],
                'Item_Description' => $item['discription'],
                'Category'=>$item['category_name'],
                'Pending_Qty' => $item['remaining_qty_after_cancel'],
                // 'rate' => $grs['mrp'],
                // 'discount' =>$grs['discount'],
                // 'gst' =>"IGST:".$grs['igst'].", SGST:".$grs['sgst'].", CGST:".$grs['cgst'],
                'value'=>(number_format((float)($total_rate), 2, '.', '')),
            );
        }
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'Doc Date',
            'Doc No',
            'Customer Name',
            'Order No',
            'Order Date',
            'Item Code',
            'Item Description',
            'Category',
            'Pending Qty',
            // 'Rate',
            // 'Discount(%)',
            // 'GST(%)',
            'Pending Value',
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
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
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
