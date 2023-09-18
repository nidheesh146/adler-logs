<?php

namespace App\Exports;
use App\Models\FGS\dc_transfer_stock;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;

class DCstockExport implements FromCollection,WithHeadings, WithStyles,WithEvents,WithPreCalculateFormulas

{
    private $data;

    public function __construct($data) 
    {
        $this->data = $data;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        $total = 0;
        foreach($this->data as $stk)
        {
            if($stk['expiry_date']!='0000-00-00') 
            $expiry = date('d-m-Y', strtotime($stk['expiry_date']));
            else 
            $expiry = 'NA';  
           
            $data[]= array(
                '#'=>$i++,
                'sku_code'=>$stk['sku_code'],
                'batchno'=>$stk['batch_no'],
                'quantity'=>$stk['quantity'],
                'uom'=>'Nos',
                'location'=>$stk['location_name'],
                'mfg_date'=>date('d-m-Y',strtotime($stk['manufacturing_date'])),
                'expiry_date'=> $expiry,
                'hsn'=>$stk['hsn_code'],
                'category'=>$stk['category_name'],
                

            );
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'SKU Code',
            'Description',
            'Batchcard',
            'Quantity',
            'UOM',
            'Mfg. Date',
            'Expiry Date',
            'HSN Code',
            'Product Category',
            

        ];
    }
    public function styles(Worksheet $sheet)
    {           
        $numOfRows = count($this->data)+1;
        $totalRow = $numOfRows + 2;


        // Add cell with SUM formula to last row
       
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
          
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}

