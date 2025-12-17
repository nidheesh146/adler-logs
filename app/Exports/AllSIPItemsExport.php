<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
use App\Models\PurchaseDetails\inv_stock_to_production;
use App\Models\PurchaseDetails\inv_stock_to_production_item;

class AllSIPItemsExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $request;

    public function __construct($request) 
    {
        //$this->status = $status;
        $this->request = $request;
        $this->inv_stock_to_production = new inv_stock_to_production;
        $this->inv_stock_to_production_item = new inv_stock_to_production_item;
    }
    public function collection()
    {
        
            $condition = [];
            if($this->request)
            {
                if ($this->request->sip_number) {
                    $condition[] = ['inv_stock_to_production.sip_number','like', '%' . $this->request->sip_number . '%'];
                }
                if ($this->request->lot_number) {
                    $condition[] = ['inv_lot_allocation.lot_number','like', '%' . $this->request->lot_number . '%'];
                }
                if ($this->request->item_code) {
                    $condition[] = ['inventory_rawmaterial.item_code','like', '%' . $this->request->item_code . '%'];
                }
                if($this->request->supplier)
                {
                    $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $this->request->supplier . '%'];
                }
               
            }
              $data['sip'] =$this->inv_stock_to_production->get_all_data_export($condition);
      
              $data1 = $data['sip'];

      
        $i=1;
        $data = [];
        foreach($data1 as $item)
        {
           
            $data[] = array(
                    '#'=>$i++,
                    'sip_number'=>$item['sip_number'],
                    'type'=>$item['type_name'],
                    'item_code'=>$item['item_code'],
                    'lot_number'=>$item['lot_number'],
                    'qty_to_production'=>$item['qty_to_production'] ,
                    'unit'=>$item['unit_name'],                   
                    'created_at'=>date('d-m-Y',strtotime($item['created_at'])),
                    'batch_no'=>$item['batch_no'],
            );
           
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'SIP Number',
            'Type',
            'Item Code',
            'Lot Number',
            'Quantity',
            'Unit',
            'Created At',
            'BatchCards & Qty',
            
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
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
