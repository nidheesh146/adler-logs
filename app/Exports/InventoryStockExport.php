<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class InventoryStockExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $stock_items;

    public function __construct($stock_items) 
    {
        $this->stock_items = $stock_items;
    }
   
    public function collection()
    {
        $i=1;
        $data = [];
        /*foreach($this->mac_items as $item)
        {
            $gst ='';
            if($item['igst']!=0)
                $gst .='IGST:'.$item['igst'].'%,';
                       
            if($item['cgst']!=0)
                $gst .='CGST:'.$item['cgst'].'%,';
                        
            if($item['sgst']!=0)
                $gst .='SGST:'.$item['sgst'].'%';
            
            $rate_aftr_discount = $item['rate']-($item['rate']*$item['discount'])/100;
            $value = $item['accepted_quantity']*$rate_aftr_discount;
            
            $data[]= array(
                '#'=>$i++,
                'mac_number'=>$item['mac_number'],
                'item_code'=>$item['item_code'],
                'hsn_code'=>$item['hsn_code'],
                'item_type'=>$item['type_name'],
                'description'=>$item['discription'],
                'lot_number'=>$item['lot_number'],
                'supplier_code'=>$item['vendor_id'],
                'supplier_name'=>$item['vendor_name'],
                'accepted_quantity'=>$item['accepted_quantity'],
                'unit'=>$item['unit_name'],
                'rate'=>$item['rate'],
                'discount'=>$item['discount'],
                'rate_aftr_discount'=>$rate_aftr_discount,
                'value'=>$value,
                'mac_date'=>date('d-m-Y',strtotime($item['mac_date'])),
                'created_by'=>$item['f_name']. ' '.$item['l_name'], 
                'created_at'=>date('d-m-Y',strtotime($item['created_at'])),

            );
        }*/

    foreach($this->stock_items as $item)
    {
        $data[]= array(
            '#'=>$i++,
            'item_code'=>$item['item_code'],
            'hsn_code'=>$item['hsn_code'],
            'item_type'=>$item['type_name'],
            'description'=>$item['discription'],
            'stock_qty'=>$item['stock_qty'],
            'unit'=>$item['unit_name'],
            'lot_number'=>$item['lot_number']
        );
    }
    return collect($data);
}
public function headings(): array
{
        return [
            '#',
            'Item Code',
            'HSN/SAC Code',
            'Item Type',
            'Item Description',
            'Stock Quantity',
            'Unit',
            'Lot Number',
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
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(30);
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
