<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\web\QualityController;


class InspectionExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $datas;

    public function __construct($datas) 
    {
        $this->datas = $datas;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach ($this->datas as $product_detail) {
            $data[] = [
                '#' => $i++,
                'batch_creation_date' => !empty($product_detail->start_date) ? date('d-M-Y', strtotime($product_detail->start_date)) : '',
                'inward_doc_date' => !empty($product_detail->inward_doc_date) ? date('d-M-Y', strtotime($product_detail->inward_doc_date)) : '',
                'batch_no' => $product_detail->batch_no ?? '',
                'sku_code' => $product_detail->sku_code ?? '',
                'description' => $product_detail->description ?? '',
                'quantity' => $product_detail->quantity ?? '',
                'group_name'=>$product_detail->group_name ?? '',
                'material_lot_no' => $product_detail->material_lot_no ?? $product_detail->multiple_batch,
            ];
        }
    
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'Batch Creation Date',
            'QC Inward Date',
            'Batch No',
            'SKU Code',
            'Item description',
            'Batch Inward Qty',
            'product Group',
            'Material Lot No',
           
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
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
               
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
