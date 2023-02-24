<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_supplier;
use App\Models\PurchaseDetails\inv_purchase_req_quotation_item_supp_rel;
use App\Models\PurchaseDetails\inv_purchase_req_quotation;
class SupplierQuotationExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
            $datas =inv_purchase_req_quotation_item_supp_rel::select('inv_purchase_req_quotation.rq_no','inv_purchase_req_quotation.date','inv_purchase_req_quotation.delivery_schedule','user.f_name','user.l_name',
                'inv_supplier.vendor_id','inv_supplier.vendor_name','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_unit.unit_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst',
                'currency_exchange_rate.currency_code','inv_purchase_req_quotation_item_supp_rel.quantity','inv_purchase_req_quotation_item_supp_rel.rate','inv_purchase_req_quotation_item_supp_rel.discount',
                'inv_purchase_req_quotation_item_supp_rel.specification','inv_purchase_req_quotation_item_supp_rel.remarks','inv_purchase_req_quotation_item_supp_rel.committed_delivery_date',
                'inv_purchase_req_quotation_item_supp_rel.selected_item','inv_purchase_req_quotation.created_at','inv_item_type.type_name','inv_supplier.id as supplierId','inv_purchase_req_quotation.quotation_id as quotationId')
                ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_purchase_req_quotation_item_supp_rel.quotation_id')
                //->leftjoin('inv_purchase_req_quotation_item_supp_rel','inv_purchase_req_quotation_item_supp_rel.quotation_id','=','inv_purchase_req_quotation_supplier.quotation_id')
                ->leftjoin('user','user.user_id','=','inv_purchase_req_quotation.created_user')
                ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_item_supp_rel.supplier_id')
                ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_quotation_item_supp_rel.item_id')
                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
                ->leftjoin('inventory_gst','inventory_gst.id','=','inv_purchase_req_quotation_item_supp_rel.gst')
                ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                ->leftjoin('currency_exchange_rate','inv_purchase_req_quotation_item_supp_rel.currency','=','currency_exchange_rate.currency_id')
                ->orderby('inv_purchase_req_quotation.quotation_id','desc')
                //->where($condition)
                ->get();
           
               
        }
        else
        {
            $condition = [];
            if ($this->request->rq_no) {
                $condition[] = ['inv_purchase_req_quotation.rq_no', 'like', '%'.$this->request->rq_no.'%'];
            }
            if ($this->request->prsr) {
                $condition[] = ['inv_purchase_req_quotation.type', '=', strtolower($this->request->prsr)];
            }
            if (!$this->request->prsr) {
                $condition[] = ['inv_purchase_req_quotation.type', '=', 'PR'];
            }
            if ($this->request->from) {
                $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '>=', date('Y-m-d', strtotime('01-' . $this->request->from))];
                $condition[] = ['inv_purchase_req_quotation.delivery_schedule', '<=', date('Y-m-t', strtotime('01-' . $this->request->from))];
            }
           
            if ($this->request->supplier) {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $this->request->supplier . '%'];
            }
                $datas =inv_purchase_req_quotation_item_supp_rel::select('inv_purchase_req_quotation.rq_no','inv_purchase_req_quotation.date','inv_purchase_req_quotation.delivery_schedule','user.f_name','user.l_name',
                                'inv_supplier.vendor_id','inv_supplier.vendor_name','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_unit.unit_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst',
                                'currency_exchange_rate.currency_code','inv_purchase_req_quotation_item_supp_rel.quantity','inv_purchase_req_quotation_item_supp_rel.rate','inv_purchase_req_quotation_item_supp_rel.discount',
                                'inv_purchase_req_quotation_item_supp_rel.specification','inv_purchase_req_quotation_item_supp_rel.remarks','inv_purchase_req_quotation_item_supp_rel.committed_delivery_date',
                                'inv_purchase_req_quotation_item_supp_rel.selected_item','inv_purchase_req_quotation.created_at','inv_item_type.type_name','inv_supplier.id as supplierId','inv_purchase_req_quotation.quotation_id as quotationId')
                                ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_purchase_req_quotation_item_supp_rel.quotation_id')
                                //->leftjoin('inv_purchase_req_quotation_item_supp_rel','inv_purchase_req_quotation_item_supp_rel.quotation_id','=','inv_purchase_req_quotation_supplier.quotation_id')
                                ->leftjoin('user','user.user_id','=','inv_purchase_req_quotation.created_user')
                                ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_item_supp_rel.supplier_id')
                                ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_quotation_item_supp_rel.item_id')
                                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                                ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
                                ->leftjoin('inventory_gst','inventory_gst.id','=','inv_purchase_req_quotation_item_supp_rel.gst')
                                ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                                ->leftjoin('currency_exchange_rate','inv_purchase_req_quotation_item_supp_rel.currency','=','currency_exchange_rate.currency_id')
                                ->orderby('inv_purchase_req_quotation.quotation_id','desc')
                                ->where($condition)
                                ->get();
        }
        $i=1;
        $items = [];
        foreach($datas as $data)
        {
             $supplier_quotation = inv_purchase_req_quotation_supplier::select('inv_purchase_req_quotation_supplier.supplier_quotation_num',
                'inv_purchase_req_quotation_supplier.freight_charge','inv_purchase_req_quotation_supplier.quotation_date')
                                        ->where('inv_purchase_req_quotation_supplier.quotation_id','=',$data['quotationId'])
                                        ->where('inv_purchase_req_quotation_supplier.supplier_id','=',$data['supplierId'])
                                        ->first();
            if($data['selected_item']==1)
            $selected ="selected";
            else
            $selected ="";
            if($data['committed_delivery_date']=='')
            $committed_delivery_date = '';
            else
            $committed_delivery_date = date('d-m-Y',strtotime($data['committed_delivery_date']));
            if($supplier_quotation['quotation_date']=='')
            $quotation_date = '';
            else
            $quotation_date = date('d-m-Y',strtotime($supplier_quotation['quotation_date']));

            $items[] = array(
                '#'=>$i++,
                'rq_number'=>$data['rq_no'],
                'item_code'=>$data['item_code'],
                'hsn_code'=>$data['hsn_code'],
                'item_type'=>$data['type_name'],
                'description'=>$data['discription'],
                'supplier_quotation_no'=>$supplier_quotation['supplier_quotation_num'],
                'supplier'=>$data['vendor_id'].'-'.$data['vendor_name'],
                'quantity'=>$data['quantity'],
                'unit'=>$data['unit_name'],
                'rate'=>$data['rate'],
                'discount'=>$data['discount'],
                'gst' =>"IGST:".$data['igst'].", SGST:".$data['sgst'].", CGST:".$data['cgst'],
                'currency'=>$data['currency_code'],
                'specification'=>$data['specification'],
                'remarks'=>$data['remarks'],
                'committed_delivery_date' =>$committed_delivery_date,
                'fright_charge'=>$supplier_quotation['freight_charge'],
                'quotation_date'=>$quotation_date,
                'delivery_schedule'=>date('d-m-Y',strtotime($data['delivery_schedule'])),
                'selected'=>$selected,
                'created_by'=>$data['f_name']. ' '.$data['l_name'],               
                'created_at'=>date('d-m-Y',strtotime($data['created_at'])),


            );
        }
        return collect($items);
    }
    public function headings(): array
    {
        return [
            '#',
            'RQ Number',
            'Item Code',
            'HSN/SAC Code',
            'Item Type',
            'Item Description',
            'Supplier Quoation Number',
            'Supplier',
            'Quantity',
            'Unit',
            'Rate',
            'Discount',
            'GST',
            'Currency',
            'Specification',
            'Remarks',
            'Committed Delivery Date',
            'Fright Charge',
            'Quotation Date',
            'Delivery Schedule',
            'Selected Item',
            'Created By',
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
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(15);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
