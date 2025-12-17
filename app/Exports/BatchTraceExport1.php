<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\FGS\fgs_product_stock_management;



class BatchTraceExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    
    private $mrnitem;
   

    public function __construct($mrnitem) 
    {
        $this->mrnitem = $mrnitem;
       
    }
    public function collection()
    {
        $i=1;
        $data = [];
       
        foreach($this->mrnitem as $item)
        {
            if($item['mrn_number'])
            {
                $mrnremining_qty=fgs_product_stock_management::where('product_id',$item['mrnprd'])
                ->where('batchcard_id',$item['batchcard_id'])
                ->where('stock_location_id',$item['mrn_stklocation'])
                ->pluck('quantity')[0];

            }
            if($item->expiry_date !='0000-00-00')
            {
                $expiry_date = date('d-m-Y', strtotime($item->expiry_date));
            }
            else
            {
                $expiry_date = 'NA';
            }
            if($item['grs_number'])
            {
                $grs_qty = $item['grs_qty'].'Nos';
            }
            else {
                $grs_qty ='';
            }
            if($item['pi_number'])
            {
                $pi_qty = $item['pi_qty'].'Nos';
            }
            else {
                $pi_qty ='';
            }
            if($item['min_number'])
            {
               
                $minremining_qty=fgs_product_stock_management::where('product_id',$item['minpr'])
                ->where('batchcard_id',$item['minbat'])
                ->where('stock_location_id',$item['min_stkloc'])
                ->pluck('quantity')[0];
                
            }
            if($item['cmin_number'])
            {
                $min_qty = $item['minqty'].'Nos';
                $cminremining_qty=fgs_product_stock_management::where('product_id',$item['cminprd'])
                ->where('batchcard_id',$item['cminbtch'])
                ->where('stock_location_id',$item['cminstk'])
                ->pluck('quantity')[0];
                
            }
            else {
                $min_qty ='';
            }
            if($item['dni_number'])
            {
                $dni_qty = $item['dni_number'].'Nos';
            }
            else {
                $dni_qty ='';
            }
            if($item['mtq_number'])
            {
                $dni_qty = $item['mtq_number'].'Nos';
            }
            else {
                $dni_qty ='';
            }
            if($item['cpi_number'])
            {
                $cpi_qty = $item['cpi_number'].'Nos';
            }
            else {
                $cpi_qty ='';
            }
           
            $data[] = array(
                '#'=>$i++,
                'sku_code'=>$item->sku_code,
                'hsn_code'=>$item->hsn_code,
                'description'=>$item->discription,
                'batch_no'=>$item->batch_no,
                'manufacture_date'=>date('d-m-Y', strtotime($item->manufacturing_date)),
                'expiry_date'=>$expiry_date,
                'doc_name'=>'MRN',
                'doc_no'=>$item->mrn_number,
                'doc_date'=>$item->mrn_date,
                'doc_qty'=>$item->quantity.'Nos',
                'rem_qty'=>$mrnremining_qty.'Nos'
                

            );
            if($item->min_number!=null){
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'MIN',
                    'doc_no'=>$item->min_number,
                    'doc_date'=>$item->min_date,
                    'doc_qty'=>$item->minqty.'Nos',
                    'rem_qty'=>$mrnremining_qty.'Nos'
                );
            }
            if($item->cmin_number!=null){
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'CMIN',
                    'doc_no'=>$item->cmin_number,
                    'doc_date'=>$item->cmin_date,
                    'doc_qty'=>$item->cminqty.'Nos',
                    'rem_qty'=>$mrnremining_qty.'Nos'
                );
            }
            if($item->grs_number!=null){
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'GRS',
                    'doc_no'=>$item->grs_number,
                    'doc_date'=>$item->grs_date,
                    'doc_qty'=>$item->grs_qty.'Nos',
                    'rem_qty'=>$mrnremining_qty.'Nos'
                );
            }
           
            if($item->pi_number!=null){
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'PI',
                    'doc_no'=>$item->pi_number,
                    'doc_date'=>$item->pi_date,
                    'doc_qty'=>$item->pi_qty.'Nos',
                    'rem_qty'=>$mrnremining_qty.'Nos'
                );
            }
            if($item->cpi_number!=null){
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'CPI',
                    'doc_no'=>$item->cpi_number,
                    'doc_date'=>$item->cpi_date,
                    'doc_qty'=>$item->cpi_qty.'Nos',
                    'rem_qty'=>$mrnremining_qty.'Nos'
                );
            }
            if($item->dni_number!=null){
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'DNI/EXI',
                    'doc_no'=>$item->dni_number,
                    'doc_date'=>$item->dni_date,
                    'doc_qty'=>$item->pi_qty.'Nos',
                    'rem_qty'=>$mrnremining_qty.'Nos'
                );
            }
            // if($item->mtq_number!=null){
            //     $data[] = array(
            //         '#'=>'',
            //         'sku_code'=>'',
            //         'hsn_code'=>'',
            //         'description'=>'',
            //         'batch_no'=>'',
            //         'manufacture_date'=>'',
            //         'expiry_date'=>'',
            //         'doc_name'=>'MTQ',
            //         'doc_no'=>$item->mtq_number,
            //         'doc_date'=>$item->mtq_date,
            //         'doc_qty'=>$item->mtqqty.'Nos',
            //         'rem_qty'=>$item->mtqqty.'Nos'
            //     );
            // }
            
        }
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'SKUs CODE',
            'HSN CODE',
            'DESCRIPTION',
            'BATCH NO.',
            'DATE OF MFG.',
            'DATE OF EXPIRY',
            'DOC NAME',
            'DOC NO',
            'DOC DATE',
            'DOC QTY',
            'REMINING QTY'
            // 'PI NUMBER',
            // 'PI QTY',
            // 'DNI/EXI NUMBER',
            // 'DNI/EXI QTY',
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
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
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
