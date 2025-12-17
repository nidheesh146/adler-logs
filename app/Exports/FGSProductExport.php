<?php

namespace App\Exports;
use App\Models\PurchaseDetails\product_price_master;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class FGSProductExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $products;

    public function __construct($products) 
    {//$condition[] = ['product_product.product_group1_id','!=','null'];

    
       
        $this->products = $products;
    }
    public function collection()
    
    {
        $i=1;
        foreach($this->products as $item)
        {
            if($item['is_sterile']==1)
             $product_condition = 'Sterile';
             else
             $product_condition = 'Non-sterile';
             if($item['status_type']==1)
             $status ="Active";
             else
             $status ="Inactive";
            if($item['gst'])
            $gst = $item['gst'].'%';
            else
            $gst ='';
            $data[]= array(
                    '#'=>$i++,
                    'SKU CODE' =>$item['sku_code'],
                    'HSN CODE' =>$item['hsn_code'],
                    'Description'=> $item['discription'],
                    'Product Type'=>$item['product_type_name'],
                    'Product Condition'=>$product_condition,
                    'Business Category'=>$item['category_name'],
                    'Product Category'=>$item['new_category_name'],
                    'Group Name' => $item['group1_name'],
                    // 'OEM'=>$item['oem_name'],
                    'Std pack size'=>$item['quantity_per_pack'],
                    'GST'=>$gst,
                    'status'=>$status,
                    'WEF'=>date('d-m-Y',strtotime($item['created'])),
                    //$condition[] = ['product_product.product_group1_id','!=','null'],

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
            'Description',
            'Product Type',
            'Product Condition',
            'Business Category',
            'Product Category',
            'Product Group ',
            // 'OEM',
            'Std pack size',
            'GST',
            'Status',
            'WEF',
            
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
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(35);
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
