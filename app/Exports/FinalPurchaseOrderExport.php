<?php

namespace App\Exports;
use App\Models\PurchaseDetails\inv_final_purchase_order_rel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
class FinalPurchaseOrderExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
   // private $status;
   private $request;

    public function __construct($request) 
    {
        //$this->status = $status;
        $this->request = $request;
    }
    public function collection()
    {
        ## 2. Export specific columns
        // if($this->status=='all')
        // {
        //     $orders = inv_final_purchase_order_master::select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.status',
        //                                         'user.f_name','user.l_name','user.employee_id','inv_supplier.vendor_id' ,'inv_supplier.vendor_name','inv_purchase_req_quotation.rq_no', 'inv_final_purchase_order_master.created_at',
        //                                         'inv_final_purchase_order_master.updated_at')
        //                                         ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
        //                                         ->leftjoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
        //                                         ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=', 'inv_final_purchase_order_master.rq_master_id')
        //                                         ->get();
        // }
        // else 
        // {
        //     $orders = inv_final_purchase_order_master::select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.status',
        //                                         'user.f_name','user.l_name','user.employee_id','inv_supplier.vendor_id' ,'inv_supplier.vendor_name','inv_purchase_req_quotation.rq_no', 'inv_final_purchase_order_master.created_at',
        //                                         'inv_final_purchase_order_master.updated_at')
        //                                         ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
        //                                         ->leftjoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
        //                                         ->where('inv_final_purchase_order_master.status', '=', $this->status)
        //                                         ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=', 'inv_final_purchase_order_master.rq_master_id')
        //                                         ->get();
        // }
       // print_r($this->request);exit;
        if($this->request=='null')
        {
            // $orders = inv_final_purchase_order_master::select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.status',
            //                                     'user.f_name','user.l_name','user.employee_id','inv_supplier.vendor_id' ,'inv_supplier.vendor_name','inv_purchase_req_quotation.rq_no', 'inv_final_purchase_order_master.created_at',
            //                                     'inv_final_purchase_order_master.updated_at')
            //                                     ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
            //                                     ->leftjoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
            //                                     ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=', 'inv_final_purchase_order_master.rq_master_id')
            //                                     ->where('inv_final_purchase_order_master.type','=','PO')
            //                                     ->orderby('inv_final_purchase_order_master.id','desc')
            //                                     ->get();
            $orders = inv_final_purchase_order_rel::select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.status',
                'user.f_name','user.l_name','user.employee_id','inv_supplier.vendor_id' ,'inv_supplier.vendor_name','inv_purchase_req_quotation.rq_no', 'inv_final_purchase_order_master.created_at',
                'inv_final_purchase_order_master.updated_at','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_final_purchase_order_item.order_qty',
                'inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.cancelled_qty','inv_unit.unit_name','inventory_gst.igst','inventory_gst.sgst',
                'inventory_gst.cgst')
                                                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                                                    ->leftjoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
                                                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                                                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                                                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                                    ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
                                                    ->leftjoin('inventory_gst','inventory_gst.id','=','inv_final_purchase_order_item.gst' )
                                                    ->leftjoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
                                                    ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=', 'inv_final_purchase_order_master.rq_master_id')
                                                    //->where($condition1)
                                                    ->orderby('inv_final_purchase_order_rel.id','desc')
                                                    ->get();
        }
        else
        {
            $condition1 =[];
            if($this->request->order_type)
            {
                if($this->request->order_type == 'wo')
                {
                    $condition1[] = ['inv_final_purchase_order_master.type','=', "WO"];
                }
                else
                {
                    $condition1[] = ['inv_final_purchase_order_master.type','=', "PO"];
                }
            }
            if ($this->request->rq_no) {
                $condition1[] = ['inv_purchase_req_quotation.rq_no', 'like', '%' . $this->request->rq_no . '%'];
            }
            if ($this->request->supplier) {
                $condition1[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $this->request->supplier . '%'];
            }
            if ($this->request->status) {
                if ($this->request->status == "reject") {
                    $condition1[] = ['inv_final_purchase_order_master.status', '=', 0];
                }
                $condition1[] = ['inv_final_purchase_order_master.status', '=', $this->request->status];
            }
            if ($this->request->po_no) {
                $condition1[] = ['inv_final_purchase_order_master.po_number', 'like', '%' . $this->request->po_no . '%'];
            }
            if ($this->request->po_from) {
                $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime($this->request->po_from))];
                //$condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-d', strtotime($this->request->po_from))];
            }
            if ($this->request->po_to) {
                //$condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime($this->request->po_from))];
                $condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-t', strtotime($this->request->po_from))];
            }

           
            /*$orders = inv_final_purchase_order_master::select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.status',
                'user.f_name','user.l_name','user.employee_id','inv_supplier.vendor_id' ,'inv_supplier.vendor_name','inv_purchase_req_quotation.rq_no', 'inv_final_purchase_order_master.created_at',
                'inv_final_purchase_order_master.updated_at')
                ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
                ->leftjoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
                ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=', 'inv_final_purchase_order_master.rq_master_id')
                ->where($condition1)
                ->orderby('inv_final_purchase_order_master.id','desc')
                ->get();*/
            $orders = inv_final_purchase_order_rel::select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.status',
                'user.f_name','user.l_name','user.employee_id','inv_supplier.vendor_id' ,'inv_supplier.vendor_name','inv_purchase_req_quotation.rq_no', 'inv_final_purchase_order_master.created_at',
                'inv_final_purchase_order_master.updated_at','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_final_purchase_order_item.order_qty',
                'inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.cancelled_qty','inv_unit.unit_name','inventory_gst.igst','inventory_gst.sgst',
                'inventory_gst.cgst')
                                                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                                                    ->leftjoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
                                                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                                                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                                                    //->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_final_purchase_order_item.item_id')
                                                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                                    ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
                                                    ->leftjoin('inventory_gst','inventory_gst.id','=','inv_final_purchase_order_item.gst' )
                                                    ->leftjoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
                                                    ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=', 'inv_final_purchase_order_master.rq_master_id')
                                                    ->where($condition1)
                                                    ->orderby('inv_final_purchase_order_rel.id','desc')
                                                    ->get();
        }
        $i=1;
        $data = [];
        foreach($orders as $order)
        {
            if($order['status']==0)
                $status = "Cancelled";
            if($order['status']==1)
                $status = "Approved";
            if($order['status']==2)
                $status = "Delete";
            if($order['status']==3)
                $status = "Due";
            if($order['status']==4)
                $status = "Pending";
            if($order['status']==5)
                $status = "Hold";
            $data[] = array(
                '#'=>$i++,
                'po_number' =>$order['po_number'],
                'item_code' =>$order['item_code'],
                'hsn_code' =>$order['hsn_code'],
                'description' =>$order['discription'],
                'quantity' =>$order['order_qty'] ,
                'unit'=>$order['unit_name'],
                'rate' =>$order['rate'],
                'discount' =>$order['discount'],
                'rate' =>$order['rate'],
                'gst' =>"IGST:".$order['igst'].", SGST:".$order['sgst'].", CGST:".$order['cgst'],
                'po_date'=>date('d-m-Y',strtotime($order['po_date'])),
                'status'=>$status,
                'supplier'=>$order['vendor_name'],
                'cancelled_qty' =>$order['cancelled_qty'],
                'createdBy'=>$order['f_name']." ".$order['l_name'],
                
                'rq_no'=>$order['rq_no'],
                'created_at'=>date('d-m-Y',strtotime($order['created_at'])),
                'updated_at'=>date('d-m-Y',strtotime($order['updated_at'])),
            );
            
        }
       
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'PO/WO Number',
            'Item Code',
            'HSN/SAC Code',
            'Item Description',
            'Quantity',
            'Unit',
            'Rate',
            'Discount(%)',
            'GST(%)',
            'PO/WO Date',
            'Status',
            'Supplier',
            'Cancelled qty',
            'Processed By',
            'RQ Number',
            'Created At',
            'Last Updated At',
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
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(15);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
