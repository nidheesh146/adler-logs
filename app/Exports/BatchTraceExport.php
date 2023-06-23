<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\Web\FGS\FgsreportController;

class BatchTraceExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    
    private $mrnitem;

    public function __construct($mrnitem) 
    {
        $this->mrnitem = $mrnitem;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach($this->mrnitem as $item)
        {
            if($item->expiry_date !='0000-00-00')
            {
                $expiry_date = date('d-m-Y', strtotime($item->expiry_date));
            }
            else
            {
                $expiry_date = 'NA';
            }
            if($item['grs_number'])
            {
                $grs_qty = $item['grs_qty'].'Nos';
            }
            else {
                $grs_qty ='';
            }
            if($item['pi_number'])
            {
                $pi_qty = $item['pi_qty'].'Nos';
            }
            else {
                $pi_qty ='';
            }
           
            $data[] = array(
                '#'=>$i++,
                'sku_code'=>$item->sku_code,
                'hsn_code'=>$item->hsn_code,
                'description'=>$item->discription,
                'batch_no'=>$item->batch_no,
                'manufacture_date'=>date('d-m-Y', strtotime($item->manufacturing_date)),
                'expiry_date'=>$expiry_date,
                'mrn_number'=>$item->mrn_number,
                'mrn_qty'=>$item->quantity.'Nos',
                'grs_number'=>$item->grs_number,
                'grs_qty'=>$grs_qty,
                'pi_number'=>$item->pi_number,
                'pi_qty'=>$pi_qty,
                'dni_number'=>$item->dni_number,
                'dni_qty'=>$pi_qty,

            );
        }
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'SKU CODE',
            'HSN CODE',
            'DESCRIPTION',
            'BATCH NO.',
            'DATE OF MFG.',
            'DATE OF EXPIRY',
            'MRN NUMBER',
            'MRN QTY',
            'GRS NUMBER',
            'GRS QTY',
            'PI NUMBER',
            'PI QTY',
            'DNI/EXI NUMBER',
            'DNI/EXI QTY',
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
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(15);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }

}
