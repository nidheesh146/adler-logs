<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class InventoryTransactionExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $item_details;

    public function __construct($item_details) 
    {
        $this->item_details = $item_details;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach($this->item_details as $item_detail)
        {
            $data[] = array(
                '#'=>$i++,
                'itemcode'=>$item_detail->item_code,
                'txn_doc_no'=>$item_detail->sip_number,
                'basic_doc_no'=>$item_detail->sip_number,
                'doc_qty'=>$item_detail->qty_to_production.' '.$item_detail->unit_name,
                'workcentre'=>$item_detail->centre_code,
                'supplie_name'=>$item_detail->vendor_name,
                'suppler_code'=>$item_detail->vendor_id,
                'itemcode1'=>$item_detail->item_code,
                'discription'=>$item_detail->discription,
                'lot_number'=>$item_detail->lot_number,

            );
        }
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'Code (ITEM+PO/WO)',
            'Txn_Doc_No',
            'Basic Doc No',
            'Doc Qty',
            'Work Centre',
            'Supplier Name',
            'Supplier Code',
            'Item Code',
            'Item Description',
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
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(15);
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
