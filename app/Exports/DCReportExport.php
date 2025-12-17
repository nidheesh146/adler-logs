<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class DCReportExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $dc_items;

    public function __construct($dc_items) 
    {
        $this->dc_items = $dc_items;
    }
    public function collection()
    {
        $i=1;
        $data = [];
       // dd('hii');
        foreach($this->dc_items as $item)
        {
            if($item['transaction_condition']==1) 
            $condition = 'Returnable'; 
            else 
            $condition = 'Non-returnable';
            $data[]= array(
                '#' => $i++,
                'Doc_No' => $item['doc_no'],
                'Doc_Date' =>date('d-m-Y', strtotime($item['doc_date'])),
                'oef_No' => $item['oef_number'],
                'oef_Date' =>date('d-m-Y', strtotime($item['oef_date'])),
                'ref_No' => $item['ref_no'],
                'ref_Date' =>date('d-m-Y', strtotime($item['ref_date'])),
                'SKU Code' => $item['sku_code'],
                'HSN Code' => $item['hsn_code'],
                'Description' => $item['discription'],
                'Category'=>$item['category_name'],
                'batchcard'=>$item['batch_no'],
                'Qty'=>$item['batch_qty'],
                'Unit'=>'Nos',
                'transaction_condition'=>$condition,
                'Transaction_Type'=>$item['transaction_name'],
                'Location Decrease' =>$item['location_decrease'],
                'Location Increase' =>$item['location_increase'],
                'Customer'=>$item['firm_name'],
                'city'=>$item['city'],
                'Zone'=>$item['zone_name'],
                'State'=>$item['state_name'],
                'Month' => date('F', strtotime($item['doc_date'])),
                'Year' =>date('Y', strtotime($item['doc_date'])),
            );
        }
       // dd($data);
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'Doc Number',
            'Doc Date',
            'OEF Number',
            'OEF Date',
            'Ref Number',
            'REf Date',
            'SKU Code',
            'HSN Code',
            'Description',
            'Category',
            'Batchcard',
            'Quantity',
            'Unit',
            'Transaction Condition',
            'Transaction Type',
            'Location Decrease',
            'Location Increase',
            'Customer',
            'City',
            'Zone',
            'State',
            'Month',
            'Year',
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
