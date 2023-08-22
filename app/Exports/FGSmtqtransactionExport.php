<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FGSmtqtransactionExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
            

            $data[] = array(
                '#' => $i++,
                'Item_Code' => $item->sku_code,
                'Description' => $item->discription,
                'MTQ_number' => $item->mtq_number,
                'Qty' => $item->quantity,
                'MTQ_date' => date('d-m-Y',strtotime($item->mtq_date)),
         
            );
        }
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'Item Code',
            'Description',
            'MTQ number',
            'Qty',
            'MTQ_date',
            
            // '#',
            // 'GRS Number',
            // 'GRS Date',
            // 'MTQ Number',
            // 'MTQ Date',
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

