<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\FGS\fgs_product_stock_management;
use DB;


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
        $current_stock = 0;
        foreach($this->mrnitem as $item)
        {
            $mrnitem = DB::table('fgs_mrn_item')->select('fgs_mrn_item.*')->where('batchcard_id',$item->batchcard_id)->orderBy('id','DESC')->first();
           // $manufacturing_date = DB::table('fgs_mrn_item')->select('manufacturing_date')->where('batchcard_id',$item->batchcard_id)->orderBy('id','DESC')->get()->first();
            if($mrnitem->expiry_date !='0000-00-00')
            {
                $expiry_date = date('d-m-Y', strtotime($mrnitem->expiry_date));
            }
            else
            {
                $expiry_date = 'NA';
            }
            $current_stock = DB::table('fgs_product_stock_management')->where('batchcard_id','=',$item->batchcard_id)->whereIn('stock_location_id',[1,2,3,6,7,10,11])->sum('quantity');
            if($current_stock==0)
            $current_stock = '0';
            //echo $current_stock;exit;
            $mrns = DB::table('fgs_mrn_item')->select('fgs_mrn.*','product_stock_location.location_name','fgs_mrn_item.quantity')
                            ->leftJoin('fgs_mrn_item_rel', 'fgs_mrn_item_rel.item', '=', 'fgs_mrn_item.id')
                            ->leftJoin('fgs_mrn', 'fgs_mrn.id', '=', 'fgs_mrn_item_rel.master')
                            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_mrn.stock_location')
                            ->where('fgs_mrn_item.batchcard_id','=',$item->batchcard_id)
                            ->where('fgs_mrn.status', '=', 1)
                            ->where('fgs_mrn_item.status', '=', 1)
                            ->get();
            $mins = DB::table('fgs_min_item')->select('fgs_min.*','product_stock_location.location_name','fgs_min_item.quantity')
                            ->leftJoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
                            ->leftJoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')
                            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_min.stock_location')
                            ->where('fgs_min_item.batchcard_id','=',$item->batchcard_id)
                            ->where('fgs_min.status', '=', 1)
                            ->where('fgs_min_item.status', '=', 1)
                            ->get();
            $cmins = DB::table('fgs_cmin_item')->select('fgs_cmin.*','product_stock_location.location_name','fgs_cmin_item.quantity as quantity')
                            ->leftJoin('fgs_cmin_item_rel', 'fgs_cmin_item_rel.item', '=', 'fgs_cmin_item.id')
                            ->leftJoin('fgs_cmin', 'fgs_cmin.id', '=', 'fgs_cmin_item_rel.master')
                            ->leftJoin('fgs_min_item', 'fgs_min_item.id', '=', 'fgs_cmin_item.cmin_item_id')
                            ->leftJoin('fgs_min_item_rel', 'fgs_min_item_rel.item', '=', 'fgs_min_item.id')
                            ->leftJoin('fgs_min', 'fgs_min.id', '=', 'fgs_min_item_rel.master')
                            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_min.stock_location')
                            ->where('fgs_cmin_item.batchcard_id','=',$item->batchcard_id)
                            ->where('fgs_min.status', '=', 1)
                            ->where('fgs_cmin.status', '=', 1)
                            ->where('fgs_min_item.status', '=', 1)
                            ->get();
            $grss = DB::table('fgs_grs_item')->select('fgs_grs.*','product_stock_location.location_name','fgs_grs_item.batch_quantity as quantity','customer_supplier.firm_name')
                            ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_grs_item.id')
                            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
                            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_grs.stock_location1')
                            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_grs.customer_id')
                            ->where('fgs_grs_item.batchcard_id','=',$item->batchcard_id)
                            ->where('fgs_grs.status', '=', 1)
                            ->where('fgs_grs_item.status', '=', 1)
                            ->get();
            $cgrss = DB::table('fgs_cgrs_item')->select('fgs_cgrs.*','fgs_grs_item.batchcard_id','product_stock_location.location_name','fgs_cgrs_item.batch_quantity as quantity','customer_supplier.firm_name')
                            ->leftJoin('fgs_cgrs_item_rel', 'fgs_cgrs_item_rel.item', '=', 'fgs_cgrs_item.id')
                            ->leftJoin('fgs_cgrs', 'fgs_cgrs.id', '=', 'fgs_cgrs_item_rel.master')
                            ->leftJoin('fgs_grs_item','fgs_grs_item.id','=','fgs_cgrs_item.grs_item_id')
                            ->leftJoin('fgs_grs_item_rel', 'fgs_grs_item_rel.item', '=', 'fgs_cgrs_item.grs_item_id')
                            ->leftJoin('fgs_grs', 'fgs_grs.id', '=', 'fgs_grs_item_rel.master')
                            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_grs.stock_location1')
                            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_grs.customer_id')
                            ->where('fgs_grs_item.batchcard_id','=',$item->batchcard_id)
                            ->where('fgs_grs.status', '=', 1)
                            ->where('fgs_cgrs.status', '=', 1)
                            ->where('fgs_grs_item.status', '=', 1)
                            ->get();
            $pis = DB::table('fgs_pi_item')->select('fgs_pi.*','fgs_pi_item.batch_qty as quantity','customer_supplier.firm_name')
                            ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_pi_item.id')
                            ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master') 
                            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_pi.customer_id')
                            ->where('fgs_pi_item.batchcard_id','=',$item->batchcard_id)
                            ->where('fgs_pi.status', '=', 1)
                            ->where('fgs_pi_item.status', '=', 1)
                            ->get();
            $cpis = DB::table('fgs_cpi_item')->select('fgs_cpi.*','fgs_cpi_item.quantity','customer_supplier.firm_name')
                            ->leftJoin('fgs_cpi_item_rel', 'fgs_cpi_item_rel.item', '=', 'fgs_cpi_item.id')
                            ->leftJoin('fgs_cpi', 'fgs_cpi.id', '=', 'fgs_cpi_item_rel.master') 
                            ->leftJoin('fgs_pi_item','fgs_pi_item.id','=','fgs_cpi_item.pi_item_id')
                            ->leftJoin('fgs_pi_item_rel', 'fgs_pi_item_rel.item', '=', 'fgs_cpi_item.pi_item_id')
                            ->leftJoin('fgs_pi', 'fgs_pi.id', '=', 'fgs_pi_item_rel.master')
                            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_cpi.customer_id')
                            ->where('fgs_cpi_item.batchcard_id','=',$item->batchcard_id)
                            ->where('fgs_cpi.status', '=', 1)
                           // ->where('fgs_cpi_item.status', '=', 1)
                            ->get();
            $dnis = DB::table('fgs_dni_item')->select('fgs_dni.*','fgs_dni_item.quantity','customer_supplier.firm_name')
                            ->leftJoin('fgs_dni_item_rel', 'fgs_dni_item_rel.item', '=', 'fgs_dni_item.id')
                            ->leftJoin('fgs_dni', 'fgs_dni.id', '=', 'fgs_dni_item_rel.master') 
                            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_dni.customer_id')
                            ->where('fgs_dni_item.batchcard_id','=',$item->batchcard_id)
                            ->where('fgs_dni.status', '=', 1)
                            ->where('fgs_dni_item.status', '=', 1)
                            ->get();
            $dcs = DB::table('delivery_challan_item')->select('delivery_challan.*','customer_supplier.firm_name','delivery_challan_item.batch_qty as quantity',
            'product_stock_location.location_name as location_increase','stock_location.location_name as location_decrease')
                            ->leftJoin('delivery_challan_item_rel', 'delivery_challan_item_rel.item', '=', 'delivery_challan_item.id')
                            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'delivery_challan_item_rel.master') 
                            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'delivery_challan.customer_id')
                            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'delivery_challan.stock_location_increase')
                            ->leftJoin('product_stock_location as stock_location','stock_location.id','delivery_challan.stock_location_decrease')
                            ->where('delivery_challan_item.batchcard_id','=',$item->batchcard_id)
                            ->where('delivery_challan.status', '=', 1)
                            ->where('delivery_challan_item.status', '=', 1)
                            ->get();
            $cdcs = DB::table('fgs_cdc_item')->select('fgs_cdc.*','customer_supplier.firm_name','fgs_cdc_item.quantity','product_stock_location.location_name',
            'product_stock_location.location_name as location_increase','stock_location.location_name as location_decrease')
                            ->leftJoin('fgs_cdc_item_rel', 'fgs_cdc_item_rel.item', '=', 'fgs_cdc_item.id')
                            ->leftJoin('fgs_cdc', 'fgs_cdc.id', '=', 'fgs_cdc_item_rel.master') 
                            ->leftJoin('delivery_challan', 'delivery_challan.id', '=', 'fgs_cdc.dc_id') 
                            ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_cdc.customer_id')
                            ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'delivery_challan.stock_location_increase')
                            ->leftJoin('product_stock_location as stock_location','stock_location.id','delivery_challan.stock_location_decrease')
                            ->where('fgs_cdc_item.batchcard_id','=',$item->batchcard_id)
                            ->where('fgs_cdc.status', '=', 1)
                            //->where('fgs_cdc_item.status', '=', 1)
                            ->get();
            $srns = DB::table('fgs_srn')->select('fgs_srn.*')->get();
            foreach($srns as $srn)
            {
                if($srn->dni_number_manual==NULL)
                {
                    $srn_items = DB::table('fgs_srn_item')->select('fgs_srn.*','fgs_srn_item.quantity','product_stock_location.location_name','customer_supplier.firm_name')
                                        ->leftJoin('fgs_srn_item_rel', 'fgs_srn_item_rel.item', '=', 'fgs_srn_item.id')
                                        ->leftJoin('fgs_srn', 'fgs_srn.id', '=', 'fgs_srn_item_rel.master')
                                        ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_srn.location_increase')
                                        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_srn.customer_id')
                                        ->where('fgs_srn_item.batchcard_id','=',$item->batchcard_id)
                                        ->where('fgs_srn.status', '=', 1)
                                        ->get();

                }
                else
                {
                    $srn_items = DB::table('fgs_manual_srn_item')->select('fgs_srn.*','fgs_manual_srn_item.quantity','product_stock_location.location_name','customer_supplier.firm_name')
                                    ->leftJoin('fgs_manual_srn_item_rel', 'fgs_manual_srn_item_rel.item', '=', 'fgs_manual_srn_item.id')
                                    ->leftJoin('fgs_srn', 'fgs_srn.id', '=', 'fgs_manual_srn_item_rel.master')
                                    ->leftJoin('product_stock_location', 'product_stock_location.id', '=', 'fgs_srn.location_increase')
                                    ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_srn.customer_id')
                                    ->where('fgs_manual_srn_item.batchcard_id','=',$item->batchcard_id)
                                    ->where('fgs_srn.status', '=', 1)
                                    ->where('fgs_manual_srn_item.status', '=', 1)
                                    ->get();
                }
            }
            $data[] = array(
                '#'=>$i++,
                'sku_code'=>$item->sku_code,
                'hsn_code'=>$item->hsn_code,
                'description'=>$item->discription,
                'batch_no'=>$item->batch_no,
                'manufacture_date'=>date('d-m-Y', strtotime($mrnitem->manufacturing_date)),
                'expiry_date'=>$expiry_date,
                'doc_name'=>'',
                'doc_no'=>'',
                'doc_date'=>'',
                'doc_qty'=>' ',
                //'rem_qty'=>'',
                

            );
            foreach($mrns as $mrn)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'MRN',
                    'doc_no'=>$mrn->mrn_number,
                    'doc_date'=>date('d-m-Y', strtotime($mrn->mrn_date)),
                    'customer'=>'',
                    'location'=>$mrn->location_name,
                    'doc_qty'=>$mrn->quantity.'Nos',
                   // 'rem_qty'=>$current_stock,
                    
                );
            }
            foreach($mins as $min)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'MIN',
                    'doc_no'=>$min->min_number,
                    'doc_date'=>date('d-m-Y', strtotime($min->min_date)),
                    'customer'=>'',
                    'location'=>$min->location_name,
                    'doc_qty'=>$min->quantity.'Nos',
                    //'rem_qty'=>$current_stock,
                    
                );
            }
            foreach($cmins as $cmin)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'CMIN',
                    'doc_no'=>$cmin->cmin_number,
                    'doc_date'=>date('d-m-Y', strtotime($cmin->cmin_date)),
                    'customer'=>'',
                    'location'=>$cmin->location_name,
                    'doc_qty'=>$cmin->quantity.'Nos',
                   // 'rem_qty'=>$current_stock,
                    
                );
            }
            foreach($grss as $grs)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'GRS',
                    'doc_no'=>$grs->grs_number,
                    'doc_date'=>date('d-m-Y', strtotime($grs->grs_date)),
                    'customer'=>$grs->firm_name,
                    'location'=>$grs->location_name,
                    'doc_qty'=>$grs->quantity.'Nos',
                   // 'rem_qty'=>$current_stock,
                    
                );
            }
            foreach($cgrss as $cgrs)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'CGRS',
                    'doc_no'=>$cgrs->cgrs_number,
                    'doc_date'=>date('d-m-Y', strtotime($cgrs->cgrs_date)),
                    'customer'=>$cgrs->firm_name,
                    'location'=>$cgrs->location_name,
                    'doc_qty'=>$cgrs->quantity.'Nos',
                   // 'rem_qty'=>$current_stock,
                    
                );
            }
            foreach($pis as $pi)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'PI',
                    'doc_no'=>$pi->pi_number,
                    'doc_date'=>date('d-m-Y', strtotime($pi->pi_date)),
                    'customer'=>$pi->firm_name,
                    'location'=>'',
                    'doc_qty'=>$pi->quantity.'Nos',
                   // 'rem_qty'=>$current_stock,
                    
                );
            }
            foreach($cpis as $cpi)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'CPI',
                    'doc_no'=>$cpi->cpi_number,
                    'doc_date'=>date('d-m-Y', strtotime($cpi->cpi_date)),
                    'customer'=>$cpi->firm_name,
                    'location'=>'',
                    'doc_qty'=>$cpi->quantity.'Nos',
                    //'rem_qty'=>$current_stock,
                    
                );
            }
            foreach($dnis as $dni)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'DNI/EXI',
                    'doc_no'=>$dni->dni_number,
                    'doc_date'=>date('d-m-Y', strtotime($dni->dni_date)),
                    'customer'=>$dni->firm_name,
                    'location'=>'',
                    'doc_qty'=>$dni->quantity.'Nos',
                   // 'rem_qty'=>$current_stock
                    
                );
            }
            foreach($dcs as $dc)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'DC',
                    'doc_no'=>$dc->doc_no,
                    'doc_date'=>date('d-m-Y', strtotime($dc->doc_date)),
                    'customer'=>$dc->firm_name,
                    'location'=>'Increase-'.$dc->location_increase.', Decrease-'.$dc->location_decrease,
                    'doc_qty'=>$dc->quantity.'Nos',
                   // 'rem_qty'=>$current_stock
                    
                );
            }
            foreach($cdcs as $cdc)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'CDC',
                    'doc_no'=>$cdc->cdc_number,
                    'doc_date'=>date('d-m-Y', strtotime($cdc->cdc_date)),
                    'customer'=>$cdc->firm_name,
                    'location'=>'Increase-'.$cdc->location_decrease.', Decrease-'.$cdc->location_increase,
                    'doc_qty'=>$cdc->quantity.'Nos',
                    //'rem_qty'=>$current_stock
                    
                );
            }
            foreach($srn_items as $srn_item)
            {
                $data[] = array(
                    '#'=>'',
                    'sku_code'=>'',
                    'hsn_code'=>'',
                    'description'=>'',
                    'batch_no'=>'',
                    'manufacture_date'=>'',
                    'expiry_date'=>'',
                    'doc_name'=>'SRN',
                    'doc_no'=>$srn_item->srn_number,
                    'doc_date'=>date('d-m-Y', strtotime($srn_item->srn_date)),
                    'customer'=>$srn_item->firm_name,
                    'location'=>$srn_item->location_name,
                    'doc_qty'=>$srn_item->quantity.'Nos',
                   // 'rem_qty'=>$current_stock
                    
                );
            }
           
            
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
            'CUSTOMER',
            'LOCATION',
            'DOC QTY',
           // 'REMINING QTY'
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
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(50);
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
