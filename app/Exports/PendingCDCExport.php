<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PendingCDCExport implements FromCollection,WithHeadings,WithEvents,WithStyles
{
    private $cdc_item;

    public function __construct($cdc_item) 
    {
        $this->cdc_item = $cdc_item;
    }
    public function collection()
    {
        if($this->cdc_item) {
            $i=1;
            $data = [];
            foreach($this->cdc_item as $item)
            {
    
                $rate_aftr_discount = $item['rate']-($item['rate']*$item['discount'])/100;
                $value = $item['order_qty']*$rate_aftr_discount;
                if($item['expiry_control']==1)
                $expiry_control = 'Yes';
                else
                $expiry_control = 'No';
                if($item->mrp)
                {
                    $total_rate = $item['remaining_qty_after_cancel']*$item['mrp'];
                    $discount_value = $total_rate*$item['discount']/100;
                    $discounted_value = $total_rate-$discount_value;
                    $igst_value = $total_rate*$item['igst']/100;
                    $sgst_value = $total_rate*$item['sgst']/100;
                    $cgst_value = $total_rate*$item['cgst']/100;
                    $total_value = $discounted_value+$igst_value+$cgst_value+$sgst_value;
                    
                }
                else
                {
                    $total_value = 0;
                }
                $data[]= array(
                    '#' => $i++,
                    'Doc_Date' => date('d-m-Y', strtotime($item['cdc_date'])),
                    'Doc_No' => $item['cdc_number'],
                    'Customer_Name' => $item['firm_name'],
                    'Zone'=>$item['zone_name'],
                    'State'=>$item['state_name'],
                    'city'=>$item['city'],
                    'Item_Code' => $item['sku_code'],
                    'Item_Description' => $item['discription'],
                    'Category'=>$item['category_name'],
                    'Pending_Qty'=> $item['remaining_qty_after_cancel'],
                    // 'rate' => $oef['mrp'],
                    // 'discount' =>$oef['discount'],
                    // 'gst' =>"IGST:".$oef['igst'].", SGST:".$oef['sgst'].", CGST:".$oef['cgst'],
                    // 'value'=>(number_format((float)($total_value), 2, '.', ''))
    
    
                );
            }
            return collect($data);
           }
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
            'Item Code',
            'Item Description',
            'Category',
            'Pending Qty',
            // 'Rate',
            // 'Discount(%)',
            // 'GST(%)',
            
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
