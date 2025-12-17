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
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;

class stocklocationAllExport implements FromCollection, WithHeadings, WithStyles,WithEvents,WithPreCalculateFormulas

{
    private $stock;
    // $maa;
    private $qurantine;

    public function __construct($stock,$qurantine) 
    {
        $this->stock = $stock;
        //$this->maa=$maa;
        $this->qurantine=$qurantine;

    }
    public function collection()
    {
       
        $i=1;
        $data = [];
        $total = 0;
        foreach($this->stock as $stk)
        {
            if($stk['expiry_date']=='0000-00-00' ||$stk['expiry_date']=='1970-01-01' || $stk['expiry_date']=='NULL' || empty($stk['expiry_date']) ){
                $expiry = 'NA';  
            } 
            else {
                $expiry = date('d-m-Y', strtotime($stk['expiry_date']));
            }
            if($stk['is_sterile']==1) 
            $condition = 'Sterile'; 
            else 
            $condition = 'Non-Sterile'; 
            $total= $total+$stk['quantity'];
            $data[]= array(
                '#'=>$i++,
                'sku_code'=>$stk['sku_code'],
                'description'=>$stk['discription'],
                'batchno'=>$stk['batch_no'],
                'quantity'=>$stk['quantity'],
                'uom'=>'Nos',
                'location'=>$stk['location_name'],
                'mfg_date'=>date('d-m-Y',strtotime($stk['manufacturing_date'])),
                'expiry_date'=> $expiry,
                'product_type'=>$stk['product_type_name'],
                'hsn'=>$stk['hsn_code'],
                'condition'=>$condition,
                'business category'=>$stk['category_name'],
                'product category'=>$stk['new_category_name'],
                'group'=>$stk['group_name'],
                'oem'=>$stk['oem_name'],
                'pack_size'=>$stk['quantity_per_pack'],

            );
        }
        /*foreach($this->maa as $stk1)
        {
            if($stk1['expiry_date']=='0000-00-00' || $stk1['expiry_date']=='NULL' || empty($stk1['expiry_date']))
            {
                $expiry = 'NA';
            }   
            else 
            {            
                $expiry = date('d-m-Y',strtotime ($stk1['expiry_date']));
            }           
             if($stk1['is_sterile']==1) 
            $condition = 'Sterile'; 
            else 
            $condition = 'Non-Sterile'; 
            // $total= $total+$stk1['quantity'];
            $data[]= array(
                '#'=>$i++,
                'sku_code'=>$stk1['sku_code'],
                'description'=>$stk1['discription'],
                'batchno'=>$stk1['batch_no'],
                'quantity'=>$stk1['quantity'],
                'uom'=>'Nos',
                'location'=>'MAA',
                'mfg_date'=>date('d-m-Y',strtotime($stk1['manufacturing_date'])),
                'expiry_date'=> $expiry,
                'product_type'=>$stk1['product_type_name'],
                'hsn'=>$stk1['hsn_code'],
                'condition'=>$condition,
                'category'=>$stk1['category_name'],
                'group'=>$stk1['group_name'],
                'oem'=>$stk1['oem_name'],
                'pack_size'=>$stk1['quantity_per_pack'],

            );
        }*/
        foreach($this->qurantine as $stk2)
        {
            if($stk2['expiry_date']=='0000-00-00' ||$stk2['expiry_date']=='0001-11-30' ||$stk2['expiry_date']=='1970-01-01' || $stk2['expiry_date']=='NULL' || empty($stk2['expiry_date'])) 
            {        
                $expiry = 'NA';   
            }            
            else 
            {
                $expiry = date('d-m-Y', strtotime($stk2['expiry_date']));
            }  
            if($stk2['is_sterile']==1) 
            $condition = 'Sterile'; 
            else 
            $condition = 'Non-Sterile'; 
            // $total= $total+$stk2['quantity'];
            $data[]= array(
                '#'=>$i++,
                'sku_code'=>$stk2['sku_code'],
                'description'=>$stk2['discription'],
                'batchno'=>$stk2['batch_no'],
                'quantity'=>$stk2['quantity'],
                'uom'=>'Nos',
                'location'=>'Quarantine',
                'mfg_date'=>date('d-m-Y',strtotime($stk2['manufacturing_date'])),
                'expiry_date'=> $expiry,
                'product_type'=>$stk2['product_type_name'],
                'hsn'=>$stk2['hsn_code'],
                'condition'=>$condition,
                'busines category'=>$stk2['category_name'],
                'product category'=>$stk2['new_category_name'],
                'group'=>$stk2['group_name'],
                'oem'=>$stk2['oem_name'],
                'pack_size'=>$stk2['quantity_per_pack'],

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
            'Location',
            'Mfg. Date',
            'Expiry Date',
            'Product Type',
            'HSN Code',
            'Product Condition',
            'Business Category',
            'Product Category',
            'Product Group',
            'OEM',
            'Std. Pack Size',

        ];
    }
    public function styles(Worksheet $sheet)
    {           
        $numOfRows = count($this->stock)+1;
        $totalRow = $numOfRows + 2;


        // Add cell with SUM formula to last row
        // $sheet->setCellValue("E{$totalRow}", "=SUM(E2:E{$numOfRows})");
        // $sheet->setCellValue("D{$totalRow}","Total :");
        
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

