<?php

namespace App\Exports;
use App\Models\PurchaseDetails\inv_mrr_item;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MRRExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
            $items= inv_mrr_item::select('inv_mrr_item.id as id','inv_mac.mac_number','inv_supplier_invoice_master.invoice_number','inv_miq.miq_number','inv_mrd.mrd_number','inv_lot_allocation.lot_number')
                     ->leftjoin('inv_mrr_item_rel','inv_mrr_item_rel.item','=','inv_mrr_item.id')
                     ->leftjoin('inv_mrr','inv_mrr.id','=','inv_mrr_item_rel.master')
                      ->leftjoin('inv_mac','inv_mac.id','=','inv_mrr.macid')
                      ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_mac.invoice_id')
                      ->leftjoin('inv_miq','inv_miq.invoice_id','=','inv_supplier_invoice_master.id')
                      ->leftjoin('inv_mrd','inv_mrd.invoice_id','=','inv_supplier_invoice_master.id')
                      ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.master' ,'=','inv_supplier_invoice_master.id')
                      ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=', 'inv_supplier_invoice_rel.item')

                      ->where($condition)
                      ->get();


        }
        else
        {

            $condition=[];
            if ($this->request->miq_no) {
                $condition[] = ['inv_supplier_invoice_master.invoice_number','like', '%' . $this->request->invoice_no . '%'];
            }
            if ($this->request->mrd_no) {
                $condition[] = ['inv_mrd.mrd_number','like', '%' . $this->request->mrd_no . '%'];
            }
               
            if($this->request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $this->request->supplier . '%'];
            }
            
            
            
            if ($this->request->from) {
                $condition[] = ['inv_mrd.mrd_date', '>=', date('Y-m-d', strtotime('01-' . $this->request->from))];
                $condition[] = ['inv_mrd.mrd_date', '<=', date('Y-m-t', strtotime('01-' . $this->request->from))];
            }
            $items= inv_mrr_item::select('inv_mrr_item.id as id','inv_mac.mac_number','inv_supplier_invoice_master.invoice_number','inv_miq.miq_number','inv_mrd.mrd_number','inv_mrr.mrr_number','inv_lot_allocation.lot_number')
                     ->leftjoin('inv_mrr_item_rel','inv_mrr_item_rel.item','=','inv_mrr_item.id')
                     ->leftjoin('inv_mrr','inv_mrr.id','=','inv_mrr_item_rel.master')
                      ->leftjoin('inv_mac','inv_mac.id','=','inv_mrr.mac_id')
                      ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_mac.invoice_id')
                      ->leftjoin('inv_miq','inv_miq.invoice_master_id','=','inv_supplier_invoice_master.id')
                      ->leftjoin('inv_mrd','inv_mrd.invoice_id','=','inv_supplier_invoice_master.id')
                       ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.master' ,'=','inv_supplier_invoice_master.id')
                      ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=', 'inv_supplier_invoice_rel.item')
                      ->where($condition)
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
                    'mrr_number'=>$item['mrr_number'],
                    'invoice_number'=>$item['invoice_number'],
                    'lot_number'=>$item['lot_number'],
                    'miq'=>$item['miq_number'],
                    'mac'=>$item['mac_number'],
                    'mrd_number'=>$item['mrd_number']
                   

            );
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'MRR/WOR Number',
            'supplier invoice ',
            'Lot Number',
            'MIQ',
            'MAC/wOA',
            'MRD/WOR',
            
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
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(15);
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
