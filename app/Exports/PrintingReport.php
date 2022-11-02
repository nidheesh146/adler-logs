<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\label_print_report;

class PrintingReport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $batchcard, $label, $manufaturing_from;
    public function __construct($batchcard,$label,$manufaturing_from) 
    {
        $this->batchcard = $batchcard;
        $this->label = $label;
        $this->manufaturing_from = $manufaturing_from;
    }
    public function collection()
    {
        $condition=[];
        if ($this->batchcard) 
        {
            $condition[]=['batchcard_batchcard.batch_no','LIKE','%'.$this->batchcard.'%'];
        }
        if($this->label)
        {
            $condition[]=['label_print_report.label_name','LIKE','%'.$this->label.'%'];
        }
        if ($this->manufaturing_from) {
            $condition[] = ['label_print_report.manufacturing_date', '>=', date('Y-m-d', strtotime('01-' . $this->manufaturing_from))];
            $condition[] = ['label_print_report.manufacturing_date', '<=', date('Y-m-t', strtotime('01-' . $this->manufaturing_from))];
        }
        $labels = label_print_report::select('label_print_report.*','batchcard_batchcard.batch_no','product_product.sku_code')
                                    ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','label_print_report.batchcard')
                                    ->leftJoin('product_product','product_product.id','=','label_print_report.product_id')
                                    ->where($condition)
                                    ->get();
        $i=1;
        foreach($labels as $label){
            if($label['expiry_date']!=NULL)
            $expiry = date('d-m-Y',strtotime($label['expiry_date']));
            else
            $expiry = " ";

            $data[] = array(
                '#'=>$i++,
                'batch_no' =>$label['batch_no'],
                'label_name'=>$label['label_name'],
                'no_of_labels'=>$label['no_of_labels_printed'],
                'manufacturing_date'=>date('d-m-Y',strtotime($label['manufacturing_date'])),
                'expiry_date'=>$expiry,
                
            );
        }
        return collect($data);

    }
    public function headings(): array
    {
        return [
            '#',
            'BatchCard',
            'Label Name',
            'No Of Labels Printed',
            'Manufature Date',
            'Expiry Date',
           
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
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
