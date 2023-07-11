<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class NetBillingExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $dni_items;

    public function __construct($dni_items) 
    {
        $this->dni_items = $dni_items;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach($this->dni_items as $item)
        {
            $gst ='';
            if($item['igst']!=0)
                $gst .='IGST:'.$item['igst'].'%,';
                       
            if($item['cgst']!=0)
                $gst .='CGST:'.$item['cgst'].'%,';
                        
            if($item['sgst']!=0)
                $gst .='SGST:'.$item['sgst'].'%';
            if($item['expiry_date']=='0000-00-00') 
                $expiry = 'NA'; 
            else 
                $expiry = date('d-m-Y',strtotime($item['expiry_date']));
            
                $discount_value = ($item['rate']* $item['remaining_qty_after_cancel'])-(($item['rate']* $item['remaining_qty_after_cancel']*$item['discount'])/100);
                $total_amount =$discount_value+(($discount_value*$item['cgst'])/100)+ (($discount_value*$item['cgst'])/100)+ (($discount_value*$item['igst'])/100);
            $data[]= array(
                '#'=>$i++,
                'dni_number'=>$item['dni_number'],
                'sku_code'=>$item['sku_code'],
                'hsn_code'=>$item['hsn_code'],
                'description'=>$item['discription'],
                'batchcard'=>$item['batch_no'],
                'manufacturing_date'=>date('d-m-Y',strtotime($item['manufacturing_date'])),
                'expiry_date'=>$expiry,
                'product_category'=>$item['category_name'],
                'order_fulfil_type'=>$item['order_fulfil_type'],
                'transaction_name'=>$item['transaction_name'],
                'qty'=>$item['remaining_qty_after_cancel'],
                'rate'=>$item['rate'],
                'discount'=>$item['discount'],
                'GST'=>$gst,
                'Net Value'=>number_format((float)($total_amount), 2, '.', ''), 
                'dni_date'=>date('d-m-Y',strtotime($item['dni_date'])),
                'customer'=>$item['firm_name'],
                'shipping_address'=>$item['shipping_address'],
                'billing_address'=>$item['billing_address'],
                'zone'=>$item['zone_name'],
            );
           
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'DNI Number',
            'SKU Code',
            'HSN Code',
            'Description',
            'Batchcard',
            'Manufacturing Date',
            'Expiry Date',
            'Product Category',
            'Order Fulfil Type',
            'Transaction Type',
            'Quantity',
            'Rate',
            'Discount',
            'GST',
            'Net Amount',
            'DNI Date',
            'Customer',
            'Billing Address',
            'Shipping Address',
            'Zone',
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
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(15);
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
