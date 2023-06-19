<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\Web\FGS\FgsreportController;

class AllTransactionExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $info;

    public function __construct($info) 
    {
        $this->info = $info;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach($this->info as $pi)
        {
            if($pi->oef_date)
            {
                $oef_date = date('d-m-Y', strtotime($pi->oef_date));
            }
            else
            {
                $oef_date = '';
            }
            if($pi->grs_date)
            {
                $grs_date = date('d-m-Y', strtotime($pi->grs_date));
            }
            else
            {
                $grs_date = '';
            }
            if($pi->pi_date)
            {
                $pi_date = date('d-m-Y', strtotime($pi->pi_date));
            }
            else
            {
                $pi_date = '';
            }
            $data[] = array(
                '#'=>$i++,
                'oef_number'=>$pi->oef_number,
                'oef_date'=>$oef_date,
                'order_number'=>$pi->order_number,
                'firm_name'=>$pi->firm_name,
                'grs_number'=>$pi->grs_number,
                'grs_date'=>$grs_date,
                'product_category'=>$pi->category_name,
                'location1'=>$pi->location_name1,
                'location2'=>$pi->location_name2,
                'pi_number'=>$pi->pi_number,
                'pi_date'=>$pi_date,

            );
        }
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'OEF NUMBER',
            'OEF DATE',
            'ORDER NUMBER',
            'FIRM NAME',
            'GRS NUMBER',
            'GRS DATE',
            'PRODUCT CATEGORY',
            'STOCK LOCATION(DECREASE)',
            'STOCK LOCATION(INCREASE)',
            'PI Number',
            'PI DATE',
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
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }

}
