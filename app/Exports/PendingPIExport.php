<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class PendingPIExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $pi_data;

    public function __construct($pi_data) 
    {
        $this->pi_data = $pi_data;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach($this->pi_data as $item)
        {
            if($item['expiry_date']=='0000-00-00') 
            $expiry = 'NA'; 
            else 
            $expiry = date('d-m-Y',strtotime($item['expiry_date']));
            if($item->rate)
            {
                $total_rate = $item['pi_qty_balance']*$item['rate'];
                $discount_value = $total_rate*$item['discount']/100;
                $discounted_value = $total_rate-$discount_value;
                $igst_value = $discounted_value*$item['igst']/100;
                $sgst_value = $discounted_value*$item['sgst']/100;
                $cgst_value = $discounted_value*$item['cgst']/100;
                $total_value = $discounted_value+$igst_value+$cgst_value+$sgst_value;
                
            }
            else
            {
                $total_value = 0;
            }
            $data[]= array(
                '#' => $i++,
                'Doc_Date' => date('d-m-Y', strtotime($item['pi_date'])),
                'Doc_No' => $item['pi_number'],
                'Customer_Name' => $item['firm_name'],
                'Zone'=>$item['zone_name'],
                'State'=>$item['state_name'],
                'city'=>$item['city'],
                'Order_No' => $item['order_number'],
                'Order_Date' => date('d-m-Y', strtotime($item['order_date'])),
                'Item_Code' => $item['sku_code'],
                'Item_Description' => $item['discription'],
                'business Category'=>$item['category_name'],
                'product Category'=>$item['new_category_name'],
                // 'batch'=>$item['batch_no'],
                'Pending_Qty' => $item['pi_qty_balance'],
                // 'rate' => $pi['mrp'],
                // 'discount' =>$pi['discount'],
                // 'gst' =>"IGST:".$pi['igst'].", SGST:".$pi['sgst'].", CGST:".$pi['cgst'],
                'pending_value'=>(number_format((float)($total_value), 2, '.', '')),
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
            'Zone',
            'State',
            'City',
            'Order No',
            'Order Date',
            'Item Code',
            'Item Description',
            'business Category',
            'product Category',
        //    'Batch',
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
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(10);
                
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
