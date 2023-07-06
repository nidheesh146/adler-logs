<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\PurchaseDetails\inv_final_purchase_order_rel;
use App\Models\PurchaseDetails\inv_final_purchase_order_master;
use App\Models\PurchaseDetails\inv_final_purchase_order_item;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
class PendingPurchaseRealisationExport implements  FromCollection, WithHeadings, WithStyles, WithEvents
{
    private $request;

    public function __construct($request) 
    {
        $this->inv_final_purchase_order_master = new inv_final_purchase_order_master;
        $this->request = $request;
    }
    public function collection()
    {
        if($this->request=='null')
        {
            $condition2=[];
            $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime('01-04-2023'))];
            $condition1[] = ['inv_final_purchase_order_master.type', '=', 'PO'];
            $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
            $po_data = inv_final_purchase_order_master::select(['inv_purchase_req_quotation.rq_no','inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_date',
                            'inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.status','inv_final_purchase_order_master.id as po_id','inv_final_purchase_order_master.created_at',
                            'user.f_name','user.l_name','inv_final_purchase_order_master.id'])
                            ->where($condition1)
                            ->where('inv_final_purchase_order_master.status','=',1)
                            //->join('inv_final_purchase_order_rel','inv_final_purchase_order_rel.master','=','inv_final_purchase_order_master.id')
                            ->join('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                            ->join('user','user.user_id','=','inv_final_purchase_order_master.created_by')
                            ->leftjoin('inv_supplier','inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                            ->orderby('inv_final_purchase_order_master.id','asc')
                            ->get();
            $po_items=[];
                foreach($po_data as $po)
                {
                    $po_items[] = inv_final_purchase_order_rel::select('inv_final_purchase_order_rel.master','inv_final_purchase_order_rel.item','inv_final_purchase_order_item.order_qty','inv_final_purchase_order_item.qty_to_invoice','inv_final_purchase_order_item.current_invoice_qty',
                    'inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.gst','inventory_rawmaterial.item_code','inventory_rawmaterial.short_description','inventory_rawmaterial.hsn_code','inv_purchase_req_quotation.rq_no',
                    'inv_unit.unit_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst','inv_item_type.type_name','inv_final_purchase_order_master.po_number','inv_supplier.vendor_name','inv_final_purchase_order_master.po_date','inv_final_purchase_order_item.cancelled_qty',
                    'inv_final_purchase_order_master.created_at','inv_final_purchase_order_master.updated_at','user.f_name','user.l_name','inv_supplier.id as supplier_id','inv_purchase_req_quotation.quotation_id','inv_final_purchase_order_item.item_id','inv_purchase_req_master.pr_no')
                            ->leftJoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
                            ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                            ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                            ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                            ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                            ->leftjoin('inv_purchase_req_quotation', 'inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                            ->leftjoin('inv_supplier','inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                            ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                            ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
                            ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','inv_final_purchase_order_item.gst' )
                            ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_final_purchase_order_rel.master','=',$po['id'])
                            ->where('inv_final_purchase_order_item.qty_to_invoice','!=',0)
                            ->orderBy('inv_final_purchase_order_item.id','ASC')
                            //->where($condition2)
                            ->get();
                }
               //print_r(json_encode($po_items));exit;
                $data = [];
                $i=1;
                foreach($po_items as $po_item)
                {
                    foreach($po_item as $item)
                    {
                        $expected_delivey_date = DB::table('inv_purchase_req_quotation_item_supp_rel')
                                                    ->where('quotation_id','=',$item['quotation_id'])
                                                    ->where('supplier_id','=',$item['supplier_id'])
                                                    ->where('item_id','=',$item['item_id'])
                                                    ->select('committed_delivery_date')
                                                    ->first();
                        if($expected_delivey_date!=NULL)
                        $committed_delivery_date = date('d-m-Y',strtotime($expected_delivey_date['committed_delivery_date']));
                        else
                        $committed_delivery_date = '';
                        $gst ='';
                        if($item['igst']!=0)
                            $gst .='IGST:'.$item['igst'].'%,';
                       
                        if($item['cgst']!=0)
                            $gst .='CGST:'.$item['cgst'].'%,';
                        
                        if($item['sgst']!=0)
                            $gst .='SGST:'.$item['sgst'].'%';
                        
                        $data[]=[
                            '#'=>$i++,
                            'pr_number'=>$item['pr_no'],
                            'po_number'=>$item['po_number'],
                            'item_code'=>$item['item_code'],
                            'hsn_code'=>$item['hsn_code'],
                            'type'=>$item['type_name'],
                            'short_description'=>$item['short_description'],
                            'order_qty'=>$item['order_qty'],
                            'qty_to_invoice'=>$item['qty_to_invoice'],
                            'unit_name'=>$item['unit_name'],
                            'rate'=>$item['rate'],
                            'discount'=>$item['discount'],
                            'gst' =>$gst,
                            'vendor'=>$item['vendor_name'],
                            'cancelled_qty' =>$item['cancelled_qty'],
                            //'createdBy'=>$item['f_name']." ".$item['l_name'],
                            'po_date'=>date('d-m-Y',strtotime($item['po_date'])),
                            'expected_delivery_date' =>$committed_delivery_date,
                            'updated_at'=>date('d-m-Y',strtotime($item['updated_at'])),
                        ];
                    }
                }

        }
        else
        {
            $condition2=[];
            //$condition1=[];
            $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime('01-04-2023'))];
            if ($this->request->supplier) {
        
                $condition1[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' .$this->request->supplier . '%'];
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
            }
            if ($this->request->po_no) {
                $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $this->request->po_no . '%'];
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
                
            }
            if ($this->request->item_code) {
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
                $condition2[] = ['inventory_rawmaterial.item_code','like','%'.$this->request->item_code];
            }
            if ($this->request->pr_no) {
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
                $condition2[] = ['inv_purchase_req_master.pr_no','like','%'.$this->request->pr_no];
            }
            if ($this->request->po_from) {
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
                $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime($this->request->po_from))];
                //$condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-d', strtotime($this->request->po_from))];
            }
            if ($this->request->po_to) {
                $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
                $condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-d', strtotime($this->request->po_to))];
            }
            if($this->request->order_type)
            {
                if($this->request->order_type=='wo')
                {
                    $condition1[] = ['inv_final_purchase_order_master.type', '=', 'WO'];
                }
                else
                {
                    $condition1[] = ['inv_final_purchase_order_master.type', '=', 'PO'];
                }
            }
            else
            {
                $condition1[] = ['inv_final_purchase_order_master.type', '=', 'PO'];
            }
            //$po_data = $this->inv_final_purchase_order_master->get_purchase_master_list_with_condition($condition1);
            $po_data = inv_final_purchase_order_master::select(['inv_purchase_req_quotation.rq_no','inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_date',
                            'inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.status','inv_final_purchase_order_master.id as po_id','inv_final_purchase_order_master.created_at',
                            'user.f_name','user.l_name','inv_final_purchase_order_master.id'])
                            ->where($condition1)
                            ->where('inv_final_purchase_order_master.status','=',1)
                            //->join('inv_final_purchase_order_rel','inv_final_purchase_order_rel.master','=','inv_final_purchase_order_master.id')
                            ->join('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                            ->join('user','user.user_id','=','inv_final_purchase_order_master.created_by')
                            ->leftjoin('inv_supplier','inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                            ->orderby('inv_final_purchase_order_master.id','asc')
                            ->get();
            $po_items=[];
                foreach($po_data as $po)
                {
                    $po_items[] = inv_final_purchase_order_rel::select('inv_final_purchase_order_rel.master','inv_final_purchase_order_rel.item','inv_final_purchase_order_item.order_qty','inv_final_purchase_order_item.qty_to_invoice','inv_final_purchase_order_item.current_invoice_qty',
                    'inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.gst','inventory_rawmaterial.item_code','inventory_rawmaterial.short_description','inventory_rawmaterial.hsn_code','inv_purchase_req_quotation.rq_no',
                    'inv_unit.unit_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst','inv_item_type.type_name','inv_final_purchase_order_master.po_number','inv_supplier.vendor_name','inv_final_purchase_order_master.po_date','inv_final_purchase_order_item.cancelled_qty',
                    'inv_final_purchase_order_master.created_at','inv_final_purchase_order_master.updated_at','user.f_name','user.l_name','inv_supplier.id as supplier_id','inv_purchase_req_quotation.quotation_id','inv_final_purchase_order_item.item_id','inv_purchase_req_master.pr_no')
                            ->leftJoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
                            ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                            ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                            ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                            ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                            ->leftjoin('inv_purchase_req_quotation', 'inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                            ->leftjoin('inv_supplier','inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                            ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                            ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
                            ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','inv_final_purchase_order_item.gst' )
                            ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                            ->where('inv_final_purchase_order_rel.master','=',$po['id'])
                            ->where('inv_final_purchase_order_item.qty_to_invoice','!=',0)
                            ->where($condition2)
                            ->orderBy('inv_final_purchase_order_item.id','ASC')
                            ->get();
                }
               //print_r(json_encode($po_items));exit;
               $i=1;
                $data = [];
                foreach($po_items as $po_item)
                {
                    foreach($po_item as $item)
                    {
                        $expected_delivey_date = DB::table('inv_purchase_req_quotation_item_supp_rel')
                                                    ->where('quotation_id','=',$item['quotation_id'])
                                                    ->where('supplier_id','=',$item['supplier_id'])
                                                    ->where('item_id','=',$item['item_id'])
                                                    ->select('committed_delivery_date')
                                                    ->first();
                        if($expected_delivey_date->committed_delivery_date!=NULL)
                        $committed_delivery_date = date('d-m-Y',strtotime($expected_delivey_date->committed_delivery_date));
                        else
                        $committed_delivery_date = ' ';
                        $gst ='';
                        if($item['igst']!=0)
                            $gst .='IGST:'.$item['igst'].'%,';
                       
                        if($item['cgst']!=0)
                            $gst .='CGST:'.$item['cgst'].'%,';
                        
                        if($item['sgst']!=0)
                            $gst .='SGST:'.$item['sgst'].'%';
                        
                        $data[]=[
                            '#'=>$i++,
                            'pr_number'=>$item['pr_no'],
                            'po_number'=>$item['po_number'],
                            'item_code'=>$item['item_code'],
                            'hsn_code'=>$item['hsn_code'],
                            'type'=>$item['type_name'],
                            'short_description'=>$item['short_description'],
                            'order_qty'=>$item['order_qty'],
                            'qty_to_invoice'=>$item['qty_to_invoice'],
                            'unit_name'=>$item['unit_name'],
                            'rate'=>$item['rate'],
                            'discount'=>$item['discount'],
                            //'gst' =>"IGST:".$item['igst'].", SGST:".$item['sgst'].", CGST:".$item['cgst'],
                            'gst' =>$gst,
                            //'po_date'=>date('d-m-Y',strtotime($item['po_date'])),
                            'vendor'=>$item['vendor_name'],
                            'cancelled_qty' =>$item['cancelled_qty'],
                            //'createdBy'=>$item['f_name']." ".$item['l_name'],
                            'po_date'=>date('d-m-Y',strtotime($item['po_date'])),
                            'expected_delivery_date' =>$committed_delivery_date,
                            'updated_at'=>date('d-m-Y',strtotime($item['updated_at'])),
                        ];
                    }
                }

        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'PO/WO Number',
            'Item Code',
            'HSN/SAC Code',
            'Item Type',
            'Item Description',
            'Order Qty',
            'Pending Qty ',
            'Unit',
            'Rate',
            'Discount(%)',
            'GST(%)',
            'Supplier',
            'Cancelled qty',
            'PO/WO Date',
            'Expected Delivery Date',
            'Approved Date',
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
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(17);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(15);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }

}
