<?php

namespace App\Exports;
use App\Models\FGS\fgs_product_stock_management;
use App\Models\FGS\fgs_maa_stock_management;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StockLocationExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $location;

    public function __construct($location) 
    {
        $this->location = $location;
    }
    public function collection()
    {
        if($this->location=='location1')
        {
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.*','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->where('product_stock_location.location_name','=','Location-1')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            
        }
        elseif($this->location=='location2')
        {

            $stock = fgs_product_stock_management::select('fgs_product_stock_management.*','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->where('product_stock_location.location_name','=','Location-2')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
        }
        elseif($this->location=='MAA')
        {
            $stock = fgs_maa_stock_management::select('fgs_maa_stock_management.*','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no')
                            ->leftJoin('product_product','product_product.id','=','fgs_maa_stock_management.product_id')
                            ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_maa_stock_management.batchcard_id' )
                            ->where('fgs_maa_stock_management.quantity','!=',0)
                            ->distinct('fgs_maa_stock_management.id')
                            ->orderBy('fgs_maa_stock_management.id','DESC')
                            ->get();

        }
        $i=1;
        $data = [];
        foreach($stock as $stk)
        {
            $data[]= array(
                '#'=>$i++,
                'sku_code'=>$stk['sku_code'],
                'description'=>$stk['discription'],
                'batchno'=>$stk['batch_no'],
                'quantity'=>$stk['quantity']. 'Nos',
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
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
