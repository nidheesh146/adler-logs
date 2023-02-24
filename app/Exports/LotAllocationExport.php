<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
use App\Models\PurchaseDetails\inv_lot_allocation;

class LotAllocationExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
            $lots = inv_lot_allocation::select(['inv_lot_allocation.*', 'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_number','inventory_rawmaterial.item_code',
                        'inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_item.order_qty as inv_odr_qty','inv_item_type.type_name',
                        'inventory_rawmaterial.discription','inv_unit.unit_name','inventory_rawmaterial.hsn_code','inv_lot_allocation.qty_received','inv_lot_allocation.invoice_rate',
                        'inv_supplier_invoice_item.discount','user.f_name','user.l_name'])
                            ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_lot_allocation.po_id')
                            ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_lot_allocation.supplier_id')
                            ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                            ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                            ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id','=','inv_lot_allocation.si_invoice_item_id')
                            ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item','=','inv_lot_allocation.si_invoice_item_id')
                            ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                            ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
                            ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                            ->leftjoin('user','user.user_id','=','inv_lot_allocation.prepared_by')
                            //->where($condition)
                            ->groupBy('inv_lot_allocation.id')
                            ->orderby('inv_lot_allocation.id','desc')
                            ->get();
        }
        else
        {
            $condition = [];
            if ($this->request->lot_no) {
                $condition[] = ['inv_lot_allocation.lot_number', 'like', '%'.$this->request->lot_no.'%'];
            }
            if ($this->request->po_no) {
                $condition[] = ['inv_final_purchase_order_master.po_number', 'like', '%'.$this->request->po_no.'%'];
            }
            if ($this->request->invoice_no) {
                $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%'.$this->request->invoice_no.'%'];
            }
            if ($this->request->item_code) {
                $condition[] = ['inventory_rawmaterial.item_code', 'like', '%'.$this->request->item_code.'%'];
            }
            if ($this->request->supplier) {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"),'like','%'.$this->request->supplier.'%'];
            }
            $lots = inv_lot_allocation::select(['inv_lot_allocation.*', 'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_number','inventory_rawmaterial.item_code',
                        'inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_item.order_qty as inv_odr_qty','inv_item_type.type_name',
                        'inventory_rawmaterial.discription','inv_unit.unit_name','inventory_rawmaterial.hsn_code','inv_lot_allocation.qty_received','inv_lot_allocation.invoice_rate',
                        'inv_supplier_invoice_item.discount','user.f_name','user.l_name'])
                            ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_lot_allocation.po_id')
                            ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_lot_allocation.supplier_id')
                            ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                            ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                            ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id','=','inv_lot_allocation.si_invoice_item_id')
                            ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item','=','inv_lot_allocation.si_invoice_item_id')
                            ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                            ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
                            ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                            ->leftjoin('user','user.user_id','=','inv_lot_allocation.prepared_by')
                            ->where($condition)
                            ->groupBy('inv_lot_allocation.id')
                            ->orderby('inv_lot_allocation.id','desc')
                            ->get();
        }
        $i=1;
        $data = [];
        foreach($lots as $lot)
        {
            $data[] = array(
                '#'=>$i++,
                'lot_number'=>$lot['lot_number'],
                'invoice_number'=>$lot['invoice_number'],
                'item_code'=>$lot['item_code'],
                'hsn_code'=>$lot['hsn_code'],
                'item_type'=>$lot['type_name'],
                'discription'=>$lot['discription'],
                'supplier'=>$lot['vendor_id'].'-'.$lot['vendor_name'], 
                'invoice_qty'=>$lot['inv_odr_qty'],
                'received_qty'=>$lot['qty_received'],
                'unit'=>$lot['unit_name'],
                'invoice_rate'=>$lot['invoice_rate'],
                'discount'=>$lot['discount'],
                'po_number'=>$lot['po_number'],
                'vehicle_number'=>$lot['vehicle_number'],
                'transporter_name'=>$lot['transporter_name'],
                'Created_by'=>$lot['f_name'].' '.$lot['l_name'],
                'invoice_date'=>date('d-m-Y',strtotime($lot['invoice_date'])),
            );
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'Lot Number',
            'Invoice Number',
            'Item Code',
            'HSN/SAC Code',
            'Item Type',
            'Item Description',
            'Supplier',
            'Invoice Quantity',
            'Received Quantity',
            'Unit',
            'Invoice Rate',
            'Discount',
            'PO Number',
            'Vehicle Number',
            'Transporter Name',
            'Created By',
            'Invoice Date',
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
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(16);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(16);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
