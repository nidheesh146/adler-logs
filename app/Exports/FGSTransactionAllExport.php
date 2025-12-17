<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\Web\FGS\FgsreportController;


class FGSTransactionAllExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $datas;

    public function __construct($datas) 
    {
        $this->datas = $datas;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach($this->datas as $product_detail)
        {
            $data[] = array(
                '#'=>$i++,
                'sku_code'=>$product_detail['sku_code'],
                'batch_no'=>$product_detail['batch_no'],
                'Date'=>date('d-m-Y',strtotime($product_detail['Date'])),
                'description'=>$product_detail['description'],
                'mrn_number' =>$product_detail['mrn_number'],
                'mrn_qty' =>$product_detail['mrn_qty'],
                'mrn_date' =>$product_detail['mrn_date'],
                'mrn_wef' =>$product_detail['mrn_wef'],
                'oef_number'=>$product_detail['oef_number'],
                'oef_qty'=>$product_detail['oef_qty'],
                'oef_date'=>$product_detail['oef_date'],
                'oef_wef'=>$product_detail['oef_wef'],
                'coef_number'=>$product_detail['coef_number'],
                'coef_qty'=>$product_detail['coef_qty'],
                'coef_date'=>$product_detail['coef_date'],
                'coef_wef'=>$product_detail['coef_wef'],
                'pi_number'=>$product_detail['pi_number'],
                'pi_qty' =>$product_detail['piqty'],
                'pi_date' =>$product_detail['pi_date'] ,
                'pi_wef' =>$product_detail['pi_wef'],
                'cpi_number'=>$product_detail['cpi_number'],
                'cpi_qty' =>$product_detail['cpiqty'],
                'cpi_date' => $product_detail['cpi_date'],
                'cpi_wef' =>$product_detail['cpi_wef'],
                'grs_number'=>$product_detail['grs_number'],
                'grs_date' => $product_detail['grs_date'],
                'grs_wef' =>$product_detail['grs_wef'],
                'cgrs_number'=>$product_detail['cgrs_number'],
                'cgrs_date' => $product_detail['cgrs_date'],
                'cgrs_wef' =>$product_detail['cgrs_wef'],
                'min_number'=>$product_detail['min_number'],
                'min_date' => $product_detail['min_date'],
                'min_wef' =>$product_detail['min_wef'],
                'cmin_number'=>$product_detail['cmin_number'],
                'cmin_date' => $product_detail['cmin_date'],
                'cmin_wef' =>$product_detail['cmin_wef'],
                'mis_number'=>$product_detail['mis_number'],
                'mis_date' => $product_detail['mis_date'],
                'mis_wef' =>$product_detail['mis_wef'],
                'mtq_number'=>$product_detail['mtq_number'],
                'mtq_date' => $product_detail['mtq_date'],
                'mtq_wef' =>$product_detail['mtq_wef'],
                'cmtq_number'=>$product_detail['cmtq_number'],
                'cmtq_date' => $product_detail['cmtq_date'],
                'cmtq_wef' =>$product_detail['cmtq_wef'],
                'dni_number'=>$product_detail['dni_number'],
                'dni_date' => $product_detail['dni_date'],
                'dni_wef' =>$product_detail['dniqty'],
                'srn_number'=>$product_detail['srn_number'],
                'srn_date' => $product_detail['srn_date'],
                'srn_wef' =>$product_detail['srnqty'],


            );
        }
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'SKU Code',
            'Batch No',
            'Date',
            'Description',
            'MRN Number',
            'MRN Qty',
            'MRN Date',
            'MRN WEF',
            'OEF Number',
            'OEF Qty',
            'OEF Date',
            'OEF WEF',
            'COEF Number',
            'COEF Qty',
            'COEF Date',
            'COEF WEF',
            'PI Number',
            'PI Qty',
            'PI Date',
            'PI WEF',
            'CPI Number',
            'CPI Qty',
            'CPI Date',
            'CPI WEF',
            'GRS number',
            'GRS date',
            'GRS WEF',
            'CGRS number',
            'CGRS date',
            'CGRS WEF',
            'MIN number',
            'MIN date',
            'MIN WEF',
            'CMIN number',
            'CMIN date',
            'CMIN WEF',
            'MIS number',
            'MIS date',
            'MIS WEF',
            'MTQ number',
            'MTQ date',
            'MTQ WEF',
            'CMTQ number',
            'CMTQ date',
            'CMTQ WEF',
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
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(15);
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
