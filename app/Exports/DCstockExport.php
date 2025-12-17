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
           // print_r($stk);exit;
            if ($stk['expiry_date'] != '0000-00-00' && strtotime($stk['expiry_date']) >= strtotime('1990-01-01')) {
                $expiry = date('d-m-Y', strtotime($stk['expiry_date']));
            }
            else 
            $expiry = 'NA';  
           
            $data[]= array(
                '#'=>$i++,
                'sku_code'=>$stk['sku_code'],
                'hsn'=>$stk['hsn_code'],
                'discription'=>$stk['discription'],
               // print_r($stk['prdt_description']),
                'category'=>$stk['category_name'],
                'new category'=>$stk['new_category'],
                'batchno'=>$stk['batch_no'],
                'quantity'=>$stk['quantity'],
                'customer'=>$stk['firm_name'],
                'uom'=>'Nos',
                'location'=>$stk['location_name'],
                'mfg_date'=>date('d-m-Y',strtotime($stk['manufacturing_date'])),
                'expiry_date'=> $expiry,
              
                
                

            );
        }
      //  print_r($data);exit;
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'SKU Code',
            'HSN Code',
            'Description',
            'Business Category',
            'Product Category',
            'Batchcard',
            'Quantity',
            'Customer',
            'UOM',
            'Location Name',
            'Mfg. Date',
            'Expiry Date',

            
            

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

