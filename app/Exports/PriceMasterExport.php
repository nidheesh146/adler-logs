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

class PriceMasterExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{ 
    private $request;

    public function __construct($request) 
    {
        $this->request = $request;
    }
    public function collection()
    {
        if($this->request=='null')
        {
            $items=   product_price_master::select('product_price_master.*','product_product.discription','product_product.sku_code','product_productgroup.group_name')
                      ->leftjoin('product_product','product_product.id','=','product_price_master.product_id')
                    ->leftjoin('product_productgroup','product_productgroup.id','=','product_product.product_group_id')              
                          ->where('product_price_master.is_active','=',1)
                          
                                   ->orderBy('product_price_master.id','DESC')
                                    ->get();
        }
        else
        {
           $items=   product_price_master::select('product_price_master.*','product_product.discription','product_product.sku_code','product_product.hsn_code','product_productgroup.group_name')
                      ->leftjoin('product_product','product_product.id','=','product_price_master.product_id')
                    ->leftjoin('product_productgroup','product_productgroup.id','=','product_product.product_group_id')              
                          ->where('product_price_master.is_active','=',1)
                          
                                   ->orderBy('product_price_master.id','DESC')
                                    ->get();
        }
        $i=1;
        $data = [];
        foreach($items as $item)
        {
            $rate_aftr_discount = $item['rate']-($item['rate']*$item['discount'])/100;
            $value = $item['order_qty']*$rate_aftr_discount;
            // if($item['expiry_control']==1)
            // $expiry_control = 'Yes';
            // else
            // $expiry_control = 'No';
            $data[]= array(
                    '#'=>$i++,
                    'SKU CODE' =>$item['sku_code'],
                    'HSN CODE' =>$item['hsn_code'],
                    'Description'=> $item['discription'],
                    'Group Name' => $item['group_name'],
                    'Purchase'=>$item['purchase'],
                    'Sale'=>$item['sales'],
                    'Transfer'=>$item['transfer'],
                    'MRP'=>$item['mrp'],
                    'created_at'=>date('d-m-Y',strtotime($item['created_at'])),

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
            'Group Name',
            'Purchase',
            'Sales',
            'Transfer',
            'MRP',
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
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
