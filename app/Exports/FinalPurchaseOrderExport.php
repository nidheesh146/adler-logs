<?php

namespace App\Exports;
use App\Models\PurchaseDetails\inv_final_purchase_order_master;
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
            $orders = inv_final_purchase_order_master::select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.status',
                                                'user.f_name','user.l_name','user.employee_id','inv_supplier.vendor_id' ,'inv_supplier.vendor_name','inv_purchase_req_quotation.rq_no', 'inv_final_purchase_order_master.created_at',
                                                'inv_final_purchase_order_master.updated_at')
                                                ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
                                                ->leftjoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
                                                ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=', 'inv_final_purchase_order_master.rq_master_id')
                                                ->where('inv_final_purchase_order_master.type','=','PO')
                                                ->orderby('inv_final_purchase_order_master.id','desc')
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
                $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime('01-' . $this->request->po_from))];
                $condition1[] = ['inv_final_purchase_order_master.po_date', '<=', date('Y-m-t', strtotime('01-' . $this->request->po_from))];
            }

           
            $orders = inv_final_purchase_order_master::select('inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.status',
                'user.f_name','user.l_name','user.employee_id','inv_supplier.vendor_id' ,'inv_supplier.vendor_name','inv_purchase_req_quotation.rq_no', 'inv_final_purchase_order_master.created_at',
                'inv_final_purchase_order_master.updated_at')
                ->leftjoin('user','user.user_id','=', 'inv_final_purchase_order_master.created_by')
                ->leftjoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
                ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=', 'inv_final_purchase_order_master.rq_master_id')
                ->where($condition1)
                ->orderby('inv_final_purchase_order_master.id','desc')
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
                'po_date'=>date('d-m-Y',strtotime($order['po_date'])),
                'status'=>$status,
                'createdBy'=>$order['employee_id']." - ".$order['f_name']." ".$order['l_name'],
                'supplier'=>$order['vendor_id']. ' - '.$order['vendor_name'],
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
            'PO/WO Date',
            'Status',
            'Created By',
            'Supplier',
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
                
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
