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
        if($this->location=='all')
        {
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        //->where('product_stock_location.location_name','=','Location-1')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            
        }
        elseif($this->location=='location1')
        {
            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','Location-1')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
            
        }
        
        elseif($this->location=='location2')
        {

            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','Location-2')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
        }
        elseif($this->location=='location3')
        {

            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','Location-3')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
        }
        elseif($this->location=='SNN')
        {

            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','SNN Mktd')
                        ->where('fgs_product_stock_management.quantity','!=',0)
                        ->distinct('fgs_product_stock_management.id')
                        ->orderBy('fgs_product_stock_management.id','DESC')
                        ->get();
        }
        elseif($this->location=='AHPL')
        {

            $stock = fgs_product_stock_management::select('fgs_product_stock_management.manufacturing_date','fgs_product_stock_management.expiry_date','fgs_product_stock_management.quantity','product_product.sku_code','product_product.discription','batchcard_batchcard.batch_no','product_product.hsn_code','product_type.product_type_name',
            'product_group1.group_name','fgs_product_category.category_name','product_oem.oem_name','product_product.quantity_per_pack','product_product.is_sterile','product_stock_location.location_name')
                        ->leftJoin('product_product','product_product.id','=','fgs_product_stock_management.product_id')
                        ->leftJoin('batchcard_batchcard','batchcard_batchcard.id','=','fgs_product_stock_management.batchcard_id' )
                        ->leftJoin('product_stock_location','product_stock_location.id','=','fgs_product_stock_management.stock_location_id' )
                        ->leftJoin('product_type','product_type.id','=','product_product.product_type_id')
                        ->leftJoin('product_group1','product_group1.id','=','product_product.product_group1_id')
                        ->leftJoin('fgs_product_category','fgs_product_category.id','=','product_product.product_category_id')
                        ->leftJoin('product_oem','product_oem.id','=','product_product.product_oem_id')
                        ->where('product_stock_location.location_name','=','AHPL Mktd')
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
            if($stk['expiry_date']!='0000-00-00') 
            $expiry = date('d-m-Y', strtotime($stk['expiry_date']));
            else 
            $expiry = 'NA';  
            if($stk['is_sterile']==1) 
            $condition = 'Sterile'; 
            else 
            $condition = 'Non-Sterile'; 
            $data[]= array(
                '#'=>$i++,
                'sku_code'=>$stk['sku_code'],
                'description'=>$stk['discription'],
                'batchno'=>$stk['batch_no'],
                'quantity'=>$stk['quantity']. 'Nos',
                'location'=>$stk['location_name'],
                'mfg_date'=>date('d-m-Y',strtotime($stk['manufacturing_date'])),
                'expiry_date'=> $expiry,
                'product_type'=>$stk['product_type_name'],
                'hsn'=>$stk['hsn_code'],
                'condition'=>$condition,
                'category'=>$stk['category_name'],
                'group'=>$stk['group_name'],
                'oem'=>$stk['oem_name'],
                'pack_size'=>$stk['quantity_per_pack'],

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
            'Location',
            'Mfg. Date',
            'Expiry Date',
            'Product Type',
            'HSN Code',
            'Product Condition',
            'Product Category',
            'Product Group',
            'OEM',
            'Std. Pack Size',

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
