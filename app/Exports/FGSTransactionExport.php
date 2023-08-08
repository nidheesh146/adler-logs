<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\Web\FGS\FgsreportController;
use App\Models\FGS\fgs_mrn_item;
use App\Models\FGS\fgs_grs_item;
use App\Models\FGS\fgs_coef_item;
use App\Models\FGS\fgs_cgrs_item;
use App\Models\FGS\fgs_pi_item;
use App\Models\FGS\fgs_cpi_item;
use App\Models\FGS\fgs_min_item;
use App\Models\FGS\fgs_cmin_item;
use App\Models\FGS\fgs_mtq_item;
use App\Models\FGS\fgs_cmtq_item;
use App\Models\FGS\fgs_mis_item;


class FGSTransactionExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
            if($product_detail['expiry_date']!='0000-00-00') 
            $expiry = date('d-m-Y', strtotime($product_detail['expiry_date']));
            else
            $expiry = 'NA';
            $oef_data = fgs_grs_item::select('fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.created_at as oef_wef','fgs_oef_item.remaining_qty_after_cancel','fgs_oef_item.id as oef_item_id')
                                    ->leftJoin('fgs_oef_item','fgs_oef_item.id','=','fgs_grs_item.oef_item_id')
                                    ->leftJoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                                    ->leftJoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                                    ->where('fgs_grs_item.mrn_item_id','=',$product_detail['mrn_item_id'])
                                    ->first();
            if($oef_data)
            {
                if($oef_data['oef_date'])
                $oef_date = date('d-m-Y',strtotime($oef_data['oef_date']));
                else
                $oef_date = '';

                if($oef_data['oef_wef'])
                $oef_wef = date('d-m-Y',strtotime($oef_data['oef_wef']));
                else
                $oef_wef = '';

                if($oef_data['oef_number'])
                $oef_number = $oef_data['oef_number'];
                else
                $oef_number = '';

                if($oef_data['remaining_qty_after_cancel'])
                $oef_qty = $oef_data['remaining_qty_after_cancel'];
                else
                $oef_qty = '';
            }
            else
            {
                $oef_number = '';
                $oef_date = '';
                $oef_wef = '';
                $oef_qty = '';
            }
            if($oef_data)
            {
                $coef_data = fgs_coef_item::select('fgs_coef.coef_number','fgs_coef.coef_date','fgs_coef.created_at as coef_wef','fgs_coef_item.quantity')
                            ->leftJoin('fgs_coef_item_rel','fgs_coef_item_rel.item','=','fgs_coef_item.id')
                            ->leftJoin('fgs_coef','fgs_coef.id','=','fgs_coef_item_rel.master')
                            ->where('fgs_coef_item.coef_item_id','=',$oef_data['oef_item_id'])
                            //->where('fgs_coef_item.status','=',1)
                            ->first();
                if($coef_data)
                {
                    $coef_number = $coef_data['coef_number'];
                    $coef_qty = $coef_data['quantity'];
                    $coef_date = date('d-m-Y',strtotime($coef_data['coef_date']));
                    $coef_wef = date('d-m-Y',strtotime($coef_data['coef_wef']));
                }
                else
                {
                    $coef_number = '';
                    $coef_qty = "";
                    $coef_date ='';
                    $coef_wef = '';
                }
            }
            else
            {
                $coef_number = '';
                $coef_qty = "";
                $coef_date ='';
                $coef_wef = '';
            }
            $grs_datas =  $grs_details = fgs_grs_item::select('fgs_grs.grs_number','fgs_grs.grs_date','fgs_grs.created_at as grs_wef','fgs_grs_item.remaining_qty_after_cancel','fgs_grs_item.id as grs_item_id')
                                    ->leftJoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=','fgs_grs_item.id')
                                    ->leftJoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                                    ->where('fgs_grs_item.mrn_item_id','=',$product_detail['mrn_item_id'])
                                    ->get();
            if($grs_datas){
                $grs_number = '';
                foreach($grs_datas as $grs_data)
                {
                    $grs_number .= $grs_data->grs_number.',';
                }
                $grs_date = '';
                foreach($grs_datas as $grs_data)
                {
                    $grs_date.= date('d-m-Y',strtotime($grs_data->grs_date)).',';
                }
                $grs_wef = '';
                foreach($grs_datas as $grs_data)
                {
                    $grs_wef.= date('d-m-Y',strtotime($grs_data->grs_wef)).',';
                }
            }
            else
            {
                $grs_number = '';
                $grs_date = '';
                $grs_wef = '';
            }
            if($grs_datas)
            {
                $cgrs_number = '';
                $cgrs_date = '';
                $cgrs_wef = '';
                foreach($grs_datas as $grs_data)
                {
                    $cgrs_data = fgs_cgrs_item::select('fgs_cgrs.cgrs_number','fgs_cgrs.cgrs_date','fgs_cgrs.created_at as cgrs_wef','fgs_cgrs_item.batch_quantity')
                                    ->leftJoin('fgs_cgrs_item_rel','fgs_cgrs_item_rel.item','=','fgs_cgrs_item.id')
                                    ->leftJoin('fgs_cgrs','fgs_cgrs.id','=','fgs_cgrs_item_rel.master')
                                    ->where('fgs_cgrs_item.grs_item_id','=',$grs_data['grs_item_id'])
                                    //->where('fgs_coef_item.status','=',1)
                                    ->first();
                    if($cgrs_data)
                    {
                        $cgrs_number .= $cgrs_data->cgrs_number.',';
                        $cgrs_date.= date('d-m-Y',strtotime($cgrs_data->cgrs_date)).',';
                        $cgrs_wef.= date('d-m-Y',strtotime($cgrs_data->cgrs_wef)).',';
                    }
                    else
                    {
                        $cgrs_number = '';
                        $cgrs_date = '';
                        $cgrs_wef = '';
                    }

                }
            }
            else
            {
                $cgrs_number = '';
                $cgrs_date = '';
                $cgrs_wef = '';
            }

            $pi_datas = fgs_pi_item::select('fgs_pi.pi_number','fgs_pi.pi_date','fgs_pi.created_at as pi_wef','fgs_pi_item.remaining_qty_after_cancel','fgs_pi_item.id as pi_item_id')
                                ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                                ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                                ->where('fgs_pi_item.mrn_item_id','=',$product_detail['mrn_item_id'])
                                ->get();
            if($pi_datas)
            {
                $pi_number = '';
                $pi_qty = '';
                $pi_date = '';
                $pi_wef = '';
                foreach($pi_datas as $pi_data)
                {
                    $pi_number .= $pi_data->pi_number.',';
                    $pi_qty .= $pi_data->remaining_qty_after_cancel.',';
                    $pi_date.= date('d-m-Y',strtotime($pi_data->pi_date)).',';
                    $pi_wef.= date('d-m-Y',strtotime($pi_data->pi_wef)).',';
                }
            }
            else
            {
                $pi_number = '';
                $pi_qty = '';
                $pi_date = '';
                $pi_wef = '';
            }
            $cpi_datas = fgs_pi_item::select('fgs_pi.pi_number','fgs_pi.pi_date','fgs_pi.created_at as pi_wef','fgs_pi_item.remaining_qty_after_cancel','fgs_pi_item.id as pi_item_id')
                                ->leftJoin('fgs_pi_item_rel','fgs_pi_item_rel.item','=','fgs_pi_item.id')
                                ->leftJoin('fgs_pi','fgs_pi.id','=','fgs_pi_item_rel.master')
                                ->where('fgs_pi_item.mrn_item_id','=',$product_detail['mrn_item_id'])
                                ->get();
            if($cpi_datas)
            {
                $cpi_number = '';
                $cpi_qty = '';
                $cpi_date = '';
                $cpi_wef = '';
                foreach($cpi_datas as $cpi_data)
                {
                    $cpi_number .= $cpi_data->cpi_number.',';
                    $cpi_qty .= $cpi_data->quantity.',';
                    $cpi_date.= date('d-m-Y',strtotime($cpi_data->cpi_date)).',';
                    $cpi_wef.= date('d-m-Y',strtotime($cpi_data->cpi_wef)).',';
                }
            }
            else
            {
                $cpi_number = '';
                $cpi_qty = '';
                $cpi_date = '';
                $cpi_wef = '';
            }
            $min_datas = fgs_min_item::select('fgs_min.min_number','fgs_min.min_date','fgs_min.created_at as min_wef')
                                    ->leftJoin('fgs_min_item_rel','fgs_min_item_rel.item','=','fgs_min_item.id')
                                    ->leftJoin('fgs_min','fgs_min.id','=','fgs_min_item_rel.master')
                                    ->where('fgs_min_item.batchcard_id','=',$product_detail['batch_id'])
                                    ->where('fgs_min_item.status','=',1)
                                    ->get();
            if($min_datas)
            {
                $min_number = '';
                $min_date = '';
                $min_wef = '';
                foreach($min_datas as $min_data)
                {
                    $min_number .= $min_data->min_number.',';
                    $min_date.= date('d-m-Y',strtotime($min_data->min_date)).',';
                    $min_wef.= date('d-m-Y',strtotime($min_data->min_wef)).',';
                }

            }
            else
            {
                $min_number = '';
                $min_date = '';
                $min_wef = '';
            }

            $cmin_datas = fgs_cmin_item::select('fgs_cmin.cmin_number','fgs_cmin.cmin_date','fgs_cmin.created_at as cmin_wef')
                                ->leftJoin('fgs_cmin_item_rel','fgs_cmin_item_rel.item','=','fgs_cmin_item.id')
                                ->leftJoin('fgs_cmin','fgs_cmin.id','=','fgs_cmin_item_rel.master')
                                ->where('fgs_cmin_item.batchcard_id','=',$product_detail['batch_id'])
                                ->get();
            if($cmin_datas)
            {
                $cmin_number = '';
                $cmin_date = '';
                $cmin_wef = '';
                foreach($cmin_datas as $cmin_data)
                {
                    $cmin_number .= $cmin_data->cmin_number.',';
                    $cmin_date.= date('d-m-Y',strtotime($cmin_data->cmin_date)).',';
                    $cmin_wef.= date('d-m-Y',strtotime($cmin_data->cmin_wef)).',';
                }

            }
            else
            {
                $cmin_number = '';
                $cmin_date = '';
                $cmin_wef = '';
            }

            $mtq_datas = fgs_mtq_item::select('fgs_mtq.mtq_number','fgs_mtq.mtq_date','fgs_mtq.created_at as mtq_wef','fgs_mtq_item.id as mtq_item_id')
                                ->leftJoin('fgs_mtq_item_rel','fgs_mtq_item_rel.item','=','fgs_mtq_item.id')
                                ->leftJoin('fgs_mtq','fgs_mtq.id','=','fgs_mtq_item_rel.master')
                                ->where('fgs_mtq_item.batchcard_id','=',$product_detail['batch_id'])
                                ->get();
            if($mtq_datas)
            {
                $mtq_number = '';
                $mtq_date = '';
                $mtq_wef = '';
                foreach($mtq_datas as $mtq_data)
                {
                    $mtq_number .= $mtq_data->mtq_number.',';
                    $mtq_date.= date('d-m-Y',strtotime($mtq_data->mtq_date)).',';
                    $mtq_wef.= date('d-m-Y',strtotime($mtq_data->mtq_wef)).',';
                }

            }
            else
            {
                $mtq_number = '';
                $mtq_date = '';
                $mtq_wef = '';
            }

            $cmtq_datas = fgs_mtq_item::select('fgs_mtq.mtq_number','fgs_mtq.mtq_date','fgs_mtq.created_at as mtq_wef','fgs_mtq_item.id as mtq_item_id')
                                ->leftJoin('fgs_mtq_item_rel','fgs_mtq_item_rel.item','=','fgs_mtq_item.id')
                                ->leftJoin('fgs_mtq','fgs_mtq.id','=','fgs_mtq_item_rel.master')
                                ->where('fgs_mtq_item.batchcard_id','=',$product_detail['batch_id'])
                                ->get();
            if($cmtq_datas)
            {
                $cmtq_number = '';
                $cmtq_date = '';
                $cmtq_wef = '';
                foreach($cmtq_datas as $cmtq_data)
                {
                    $cmtq_number .= $cmtq_data->cmtq_number.',';
                    $cmtq_date.= date('d-m-Y',strtotime($cmtq_data->cmtq_date)).',';
                    $cmtq_wef.= date('d-m-Y',strtotime($cmtq_data->cmtq_wef)).',';
                }

            }
            else
            {
                $cmtq_number = '';
                $cmtq_date = '';
                $cmtq_wef = '';
            }

            if($mtq_datas)
            {
                $mis_number = '';
                $mis_date = '';
                $mis_wef = '';
                foreach($mtq_datas as $mtq_data)
                {
                    $mis_data = fgs_mis_item::select('fgs_mis.mis_number','fgs_mis.mis_date','fgs_mis.created_at as mis_wef','fgs_cgrs_item.batch_quantity')
                                        ->leftJoin('fgs_mis_item_rel','fgs_mis_item_rel.item','=','fgs_mis_item.id')
                                        ->leftJoin('fgs_mis','fgs_mis.id','=','fgs_mis_item_rel.master')
                                        ->where('fgs_mis_item.grs_item_id','=',$mtq_data['mtq_item_id'])
                                        //->where('fgs_coef_item.status','=',1)
                                        ->first();
                    if($mis_data) 
                    {
                        $mis_number .= $mis_data->mis_number.',';
                        $mis_date.= date('d-m-Y',strtotime($mis_data->mis_date)).',';
                        $mis_wef.= date('d-m-Y',strtotime($mis_data->mis_wef)).',';
                    }
                    else
                    {
                        $mis_number = '';
                        $mis_date = '';
                        $mis_wef = '';
                    }
                }

            }
            else
            {
                $mis_number = '';
                $mis_date = '';
                $mis_wef = '';
            }

            $data[] = array(
                '#'=>$i++,
                'sku_code'=>$product_detail['sku_code'],
                'batch_no'=>$product_detail['batch_no'],
                //'Date'=>date('d-m-Y',strtotime($product_detail['Date'])),
                'manufaturing_date'=>date('d-m-Y',strtotime($product_detail['manufacturing_date'])),
                'expiry_date'=>$expiry,
                'description'=>$product_detail['description'],
                'mrn_number' =>$product_detail['mrn_number'],
                'mrn_qty' =>$product_detail['quantity'].' No',
                'mrn_date' =>$product_detail['mrn_date'],
                'mrn_wef' =>$product_detail['mrn_wef'],

                'oef_number'=>$oef_number,
                'oef_qty'=>$oef_qty,
                'oef_date'=>$oef_date,
                'oef_wef'=>$oef_wef,

                'coef_number'=>$coef_number,
                'coef_qty'=>$coef_qty,
                'coef_date'=>$coef_date,
                'coef_wef'=>$coef_wef,

                'grs_number'=>$grs_number,
                'grs_date' => $grs_date,
                'grs_wef' =>$grs_wef,

                'cgrs_number'=>$cgrs_number,
                'cgrs_date' => $cgrs_date,
                'cgrs_wef' =>$cgrs_wef,

                'pi_number'=>$pi_number,
                'pi_qty' =>$pi_qty,
                'pi_date' =>$pi_date ,
                'pi_wef' =>$pi_wef,

                'cpi_number'=>$cpi_number,
                'cpi_qty' =>$cpi_qty,
                'cpi_date' => $cpi_date,
                'cpi_wef' =>$cpi_wef,
                
                'min_number'=>$min_number,
                'min_date' => $min_date,
                'min_wef' =>$min_wef,

                'cmin_number'=>$cmin_number,
                'cmin_date' => $cmin_date,
                'cmin_wef' =>$cmin_wef,

                'mtq_number'=>$mtq_number,
                'mtq_date' => $mtq_date,
                'mtq_wef' =>$mtq_wef,

                'cmtq_number'=>$cmtq_number,
                'cmtq_date' => $cmtq_date,
                'cmtq_wef' =>$cmtq_wef,

                'mis_number'=>$mis_number,
                'mis_date' => $mis_date,
                'mis_wef' =>$mis_wef,
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
            'Date of MFG.',
            'Date of Expiry',
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

            'GRS number',
            'GRS date',
            'GRS WEF',

            'CGRS number',
            'CGRS date',
            'CGRS WEF',

            'PI Number',
            'PI Qty',
            'PI Date',
            'PI WEF',

            'CPI Number',
            'CPI Qty',
            'CPI Date',
            'CPI WEF',
           
            'MIN number',
            'MIN date',
            'MIN WEF',

            'CMIN number',
            'CMIN date',
            'CMIN WEF',

            'MTQ number',
            'MTQ date',
            'MTQ WEF',

            'CMTQ number',
            'CMTQ date',
            'CMTQ WEF',

            'MIS number',
            'MIS date',
            'MIS WEF',
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
