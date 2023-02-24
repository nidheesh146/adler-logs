<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\PurchaseDetails\inv_supplier_invoice_item;
use App\Models\PurchaseDetails\inv_supplier_invoice_master;
use DB;
class SupplierInvoiceExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
            // $item = inv_supplier_invoice_item::select('inv_supplier_invoice_master.invoice_number')
            //                 ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
            //                 ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
            //                 ->where('inv_supplier_invoice_item.is_merged','=',0)
            //                 ->orderBy('inv_supplier_invoice_master.id','desc')
            //                 ->get();
            $items = inv_supplier_invoice_item::select(['inv_supplier_invoice_item.id','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
                    'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number',
                    'inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name','inv_item_type.type_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst',
                    'user.f_name','user.l_name','inv_supplier_invoice_master.created_at','inv_unit.unit_name'])
                                ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                                ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                                ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_supplier_invoice_item.item_id')
                                ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                                ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                                ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_master.po_master_id')
                                ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                                ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
                                ->leftjoin('inventory_gst','inventory_gst.id','=','inv_supplier_invoice_item.gst')
                                >leftjoin('user','user.user_id','=','inv_supplier_invoice_master.created_by')
                                ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                                // ->leftjoin('inv_lot_allocation', function($join)
                                // {
                                //     $join->on('inv_lot_allocation.si_invoice_item_id', '=', 'inv_supplier_invoice_item.id');
                                //     $join->where('inv_lot_allocation.status','=',1);
                                // })
                                //->where($condition)
                                //->where('inv_item_type.type_name','=','Direct Items')
                                ->where('inv_supplier_invoice_item.is_merged','=',0)
                                ->groupBy('inv_supplier_invoice_item.id')
                                ->orderBy('inv_supplier_invoice_item.id','desc')
                                ->get();
        }
        else
        {
            $condition = [];
            if ($this->request->order_type) {
                if($this->request->order_type=='wo')
                $condition[] = ['inv_supplier_invoice_master.type', '=', 'WO'];
                else
                $condition[] = ['inv_supplier_invoice_master.type', '=', 'PO'];
            }
            if (!$this->request->order_type) {
                $condition[] = ['inv_supplier_invoice_master.type', '=', 'PO'];
            }

            // if ($this->request->po_no) {
            //     $condition[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $this->request->po_no . '%'];
            // }
            if ($this->request->invoice_no) {
                $condition[] = ['inv_supplier_invoice_master.invoice_number', 'like', '%' . $this->request->invoice_no . '%'];
            }
            if ($this->request->supplier) {
                // $condition2[] = ['inv_supplier.id', '=', $request->supplier];
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $this->request->supplier . '%'];
                // $condition2[] = ['inv_supplier.vendor_name', 'like', '%'.$request->supplier.'%'];
            }
            if ($this->request->from) {
                $condition[] = ['inv_supplier_invoice_master.invoice_date', '>=', date('Y-m-d', strtotime('01-' . $this->request->from))];
                $condition[] = ['inv_supplier_invoice_master.invoice_date', '<=', date('Y-m-t', strtotime('01-' . $this->request->from))];
            }
            $items = inv_supplier_invoice_item::select(['inv_supplier_invoice_item.id','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
                    'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number',
                    'inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name','inv_item_type.type_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst',
                    'user.f_name','user.l_name','inventory_rawmaterial.discription','inv_supplier_invoice_master.created_at','inv_unit.unit_name'])
                                ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                                ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                                ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_supplier_invoice_item.item_id')
                                ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                                ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                                ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_master.po_master_id')
                                ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                                ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
                                ->leftjoin('inventory_gst','inventory_gst.id','=','inv_supplier_invoice_item.gst')
                                ->leftjoin('user','user.user_id','=','inv_supplier_invoice_master.created_by')
                                ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                                // ->leftjoin('inv_lot_allocation', function($join)
                                // {
                                //     $join->on('inv_lot_allocation.si_invoice_item_id', '=', 'inv_supplier_invoice_item.id');
                                //     $join->where('inv_lot_allocation.status','=',1);
                                // })
                                ->where($condition)
                                //->where('inv_item_type.type_name','=','Direct Items')
                                ->where('inv_supplier_invoice_item.is_merged','=',0)
                                ->groupBy('inv_supplier_invoice_item.id')
                                ->orderBy('inv_supplier_invoice_item.id','desc')
                                ->get();
            $i=1;
            $data = [];
            foreach($items as $item)
            {
                $data[] = array(
                    '#'=>$i++,
                    'invoice_number'=>$item['invoice_number'],
                    'item_code'=>$item['item_code'],
                    'hsn_code'=>$item['hsn_code'],
                    'item_type'=>$item['type_name'],
                    'description'=>$item['discription'],
                    'supplier'=>$item['vendor_id'].'-'.$item['vendor_name'],
                    'quantity'=>$item['order_qty'],
                    'unit'=>$item['unit_name'],
                    'rate'=>$item['rate'],
                    'discount'=>$item['discount'],
                    'gst' =>"IGST:".$item['igst'].", SGST:".$item['sgst'].", CGST:".$item['cgst'],
                    'created_by'=>$item['f_name']. ' '.$item['l_name'], 
                    'invoice_date'=>date('d-m-Y',strtotime($item['invoice_date'])),
                    'transaction_date'=>date('d-m-Y',strtotime($item['created_at'])),
                );

            }

        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'Invoice Number',
            'Item Code',
            'HSN/SAC Code',
            'Item Type',
            'Item Description',
            'Supplier',
            'Quantity',
            'Unit',
            'Rate',
            'Discount',
            'GST',
            //'Currency',
            'Created By',
            'Invoice Date',
            'Transaction Date'
            //'Last Updated At',
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
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(30);
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
