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
            $items=   product_price_master::select('product_price_master.*','product_product.discription','product_product.sku_code','product_group1.group_name')
                                    ->leftjoin('product_product','product_product.id','=','product_price_master.product_id')
                                    ->leftjoin('product_group1','product_group1.id','=','product_product.product_group1_id') 
                                    ->where('product_price_master.is_active','=',1)
                                    ->orderBy('product_price_master.id','DESC')
                                    ->get();
        }
        else
        {
            $condition= [];
            if($this->request->sku_code)
            {
                $condition[] = ['product_product.sku_code','like', '%' . $this->request->sku_code . '%'];
            }
            if($this->request->hsn_code)
            {
                $condition[] = ['product_product.hsn_code','like', '%' . $this->request->hsn_code . '%'];
            }
            if($this->request->group_name)
            {
                $condition[] = ['product_productgroup.group_name','like', '%' . $this->request->group_name . '%'];
            }
           $items=   product_price_master::select('product_price_master.*','product_product.discription','product_product.sku_code','product_product.hsn_code','product_group1.group_name')
                            ->leftjoin('product_product','product_product.id','=','product_price_master.product_id')
                            ->leftjoin('product_group1','product_group1.id','=','product_product.product_group1_id')              
                            ->where('product_price_master.is_active','=',1)
                            ->where($condition)
                            ->orderBy('product_price_master.id','DESC')
                            ->get();
        }
        $i=1;
        $data = [];
        foreach($items as $item)
        {
            $rate_aftr_discount = $item['rate']-($item['rate']*$item['discount'])/100;
            $value = $item['order_qty']*$rate_aftr_discount;
            if($item['status_type']==1)
            $status ="Active";
            else
            $status ="Inactive";
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
                    'status'=>$status,
                    'WEF'=>date('d-m-Y',strtotime($item['created_at'])),

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
