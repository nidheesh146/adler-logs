<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
use App\Models\PurchaseDetails\inv_purchase_req_item;

class AllRequisitionItemsExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $request;

    public function __construct($request) 
    {
        //$this->status = $status;
        $this->request = $request;
    }
    public function collection()
    {
        if($this->request=='null')
        {
            $items= inv_purchase_req_item::select(['inv_purchase_req_item.requisition_item_id','inv_purchase_req_item.actual_order_qty','inv_item_type.type_name',
            'inv_purchase_req_item_approve.created_user','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inventory_rawmaterial.short_description','inv_purchase_req_item.created_at',
            'inv_purchase_req_item_approve.approved_qty','inv_purchase_req_master.pr_no','inv_purchase_req_master.PR_SR','inv_unit.unit_name','inv_purchase_req_item_approve.status'])
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->where('inv_purchase_req_item_approve.status','!=',2)
                    ->groupBy('inv_purchase_req_item.requisition_item_id')
                    ->orderby('inv_purchase_req_item.requisition_item_id','desc')
                    ->get();
        }
        else
        {
            $condition = []; 
            $wherein = [4,5,1,0];

            if ($this->request->pr_no) {
                $condition[] = ['inv_purchase_req_master.pr_no', 'like', '%'.$this->request->pr_no.'%'];
            }
            if ($this->request->prsr == 'sr') {
                $condition[] = ['inv_purchase_req_master.PR_SR', '=', "SR"];
            }else{
                $condition[] = ['inv_purchase_req_master.PR_SR', '=', "PR"];
            }
            
            if ($this->request->item_code) {
                $condition[] = ['inventory_rawmaterial.Item_code','like', '%'.$this->request->item_code.'%'];
            }
            if ($this->request->requestor) {
                //$condition[] = [DB::raw("CONCAT(user.f_name,user.l_name)"), 'like', '%' . $request->requestor . '%'];
                //$condition[] = ['user.l_name','like', '%'.$request->requestor.'%'];
                $condition[] = ['user.f_name','like', '%'.$this->request->requestor.'%'];
            }
            if ($this->request->status || $this->request->status == '0') {
                //$condition[] = ['inv_purchase_req_item_approve.status','=',$this->request->status];
                $wherein = [$this->request->status];
            }
            $items= inv_purchase_req_item::select(['inv_purchase_req_item.requisition_item_id','inv_purchase_req_item.actual_order_qty','inv_item_type.type_name',
            'inv_purchase_req_item_approve.created_user','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inventory_rawmaterial.short_description','inv_purchase_req_item.created_at',
            'inv_purchase_req_item_approve.approved_qty','inv_purchase_req_master.pr_no','inv_purchase_req_master.PR_SR','inv_unit.unit_name','inv_purchase_req_item_approve.status'])
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->where('inv_purchase_req_item_approve.status','!=',2)
                    ->where($condition)
                    ->whereIn('inv_purchase_req_item_approve.status',$wherein)
                   
                    ->groupBy('inv_purchase_req_item.requisition_item_id')
                    ->orderby('inv_purchase_req_item.requisition_item_id','desc')
                    ->get();

        }
        $i=1;
        $data = [];
        foreach($items as $item)
        {
            if($item['status'] ==4)
                $status = 'Pending';
            if($item['status'] == 5)
                $status = 'On hold';
            if($item['status'] == 1)
                $status = 'Approved';
		    if($item['status'] == 0)
                $status = 'Rejected';
            $data[] = array(
                    '#'=>$i++,
                    'pr_no'=>$item['pr_no'],
                    'item_code'=>$item['item_code'],
                    'hsn_code'=>$item['hsn_code'],
                    'item_type'=>$item['type_name'],
                    'description'=>$item['short_description'],
                    'actual_order_qty'=>$item['actual_order_qty'],
                    'unit'=>$item['unit_name'],
                    'status'=>$status,
                    'created_at'=>date('d-m-Y',strtotime($item['created_at'])),
                    // 'updated_at'=>date('d-m-Y',strtotime($item['updated_at'])),
            );
           
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'Item Code',
            'HSN/SAC Code',
            'Item Type',
            'Item Description',
            'Order Quantity',
            'Unit',
            'Status',
            'Created At',
            // 'Last Updated At',
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
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
