<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\web\QualityController;


class QualityAnalysisExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
        foreach($this->datas as $product_detail)
        {
            $data[] = array(
                '#'=>$i++,
                'batch_creation_date' => date('d-M-Y', strtotime($product_detail['batch_creation_date'])),
                'inward_doc_date' => date('d-M-Y', strtotime($product_detail['inward_doc_date'])),
                'batch_no'=>$product_detail['batch_no'],
                'sku_name'=>$product_detail['sku_name'],
                'description' =>$product_detail['description'],
                'batchcard_inward_qty' =>$product_detail['batchcard_inward_qty'],
                'material_lot_no' =>$product_detail['material_lot_no'],
                'start_date'=>date('d-M-Y',strtotime($product_detail['start_date'])),
                'start_time' =>$product_detail['start_time'],
                'end_date'=>date('d-M-Y',strtotime($product_detail['end_date'])),
                'end_time'=>$product_detail['end_time'],
                'inspected_qty'=>$product_detail['inspected_qty'],
                'inspector_name'=>$product_detail['inspector_name'],
                'accepted_quantity'=>$product_detail['accepted_quantity'],
                'rejected_qty'=>$product_detail['rejected_qty'],
                'rejected_reason'=>$product_detail['rejected_reason'],
                'rework_quantity'=>$product_detail['rework_quantity'],
                'rework_reason' =>$product_detail['rework_reason'] ,
                'remaining_quantity' =>$product_detail['remaining_quantity'] ,
                'remaining_reason' =>$product_detail['remaining_reason'] ,
                'accepted_quantity_with_deviation'=>$product_detail['accepted_quantity_with_deviation'],
                'reason_for_deviation' =>$product_detail['reason_for_deviation'],
                'product_group'=>$product_detail['product_group'],
                'pending_status' => $product_detail['pending_status'] == 1 ? 'Settled' : 'Pending',
                'remark' =>$product_detail['remark'] ,

            );
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
            'Material Lot No',
            'Inspection Start Date',
            'Inspection Start Time',
            'Inspection End Date',
            'Inspection End Time',
            'Inspected qty',
            'Inspector Name',
            'Accepted Qty',
            'Rejected Qty',
            'Rejected Reason',
            'Rework Qty',
            'Rework Reason',
            'Remaining Qty',
            'Remaining Reason',
            'Accepted Qty With Deviation',
            'Deviation Reason',
            'Product Group',
            'Pending Status',
            'Remark',
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
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(50);
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
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('X')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(40);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
