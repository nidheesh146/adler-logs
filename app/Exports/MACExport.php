<?php

namespace App\Exports;
use App\Models\PurchaseDetails\inv_mac_item;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class MACExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
            $items= inv_mac_item::select('inv_mac_item.id as id','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_item_type.type_name',
                'inv_unit.unit_name','inv_lot_allocation.lot_number','inv_mac_item.accepted_quantity','inv_miq_item.expiry_control','inv_miq_item.expiry_date',
                'inv_mac.mac_number','inv_mac.mac_date','inv_mac.created_at','user.f_name','user.l_name','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate',
                'inv_supplier_invoice_item.discount','inv_supplier_invoice_master.invoice_number','inv_supplier.vendor_id','inv_supplier.vendor_name')
                                    ->leftjoin('inv_mac_item_rel','inv_mac_item_rel.item','=','inv_mac_item.id')
                                    ->leftjoin('inv_mac','inv_mac.id','=','inv_mac_item_rel.master')
                                    ->leftjoin('user','user.user_id','=','inv_mac.created_by')
                                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_mac_item.invoice_item_id')
                                    ->leftjoin('inv_miq_item','inv_miq_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_mac.invoice_id')
                                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                                    //->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
                                    //->where($condition)
                                    ->where('inv_mac.status','=',1)
                                    ->orderBy('inv_mac_item.id','DESC')
                                    ->get();
                
        }
        else
        {
            $condition=[];
            if ($this->request->invoice_no) {
                $condition[] = ['inv_supplier_invoice_master.invoice_number','like', '%' . $this->request->invoice_no . '%'];
            }
            if ($this->request->mac_no) {
                $condition[] = ['inv_mac.mac_number','like', '%' . $this->request->mac_no . '%'];
            }
            if($this->request->supplier)
            {
                $condition[] = [DB::raw("CONCAT(inv_supplier.vendor_id,' - ',inv_supplier.vendor_name)"), 'like', '%' . $this->request->supplier . '%'];
            }
            
            if ($this->request->from) {
                $condition[] = ['inv_mac.mac_date', '>=', date('Y-m-d', strtotime('01-' . $this->request->from))];
                $condition[] = ['inv_mac.mac_date', '<=', date('Y-m-t', strtotime('01-' . $this->request->from))];
            }
            if($this->request->order_type)
            {  
                $condition[] = ['inv_supplier_invoice_master.type','=',  strtoupper($this->request->order_type)];
            }
            if(!$this->request->order_type)
            {  
                $condition[] = ['inv_supplier_invoice_master.type','=', 'PO'];
            }
            $items= inv_mac_item::select('inv_mac_item.id as id','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_item_type.type_name',
            'inv_unit.unit_name','inv_lot_allocation.lot_number','inv_mac_item.accepted_quantity','inv_miq_item.expiry_control','inv_miq_item.expiry_date',
            'inv_mac.mac_number','inv_mac.mac_date','inv_mac.created_at','user.f_name','user.l_name','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate',
            'inv_supplier_invoice_item.discount','inv_supplier_invoice_master.invoice_number','inv_supplier.vendor_id','inv_supplier.vendor_name')
                                ->leftjoin('inv_mac_item_rel','inv_mac_item_rel.item','=','inv_mac_item.id')
                                ->leftjoin('inv_mac','inv_mac.id','=','inv_mac_item_rel.master')
                                ->leftjoin('user','user.user_id','=','inv_mac.created_by')
                                ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_mac_item.invoice_item_id')
                                ->leftjoin('inv_miq_item','inv_miq_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                                ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                                ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                                ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                                ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                                ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','inv_mac.invoice_id')
                                ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                                //->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
                                ->where($condition)
                                ->where('inv_mac.status','=',1)
                                ->orderBy('inv_mac_item.id','DESC')
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
                    'mac_number'=>$item['mac_number'],
                    'invoice_number'=>$item['invoice_number'],
                    'item_code'=>$item['item_code'],
                    'hsn_code'=>$item['hsn_code'],
                    'item_type'=>$item['type_name'],
                    'description'=>$item['discription'],
                    'lot_number'=>$item['lot_number'],
                    'supplier'=>$item['vendor_id'].'-'.$item['vendor_name'],
                    'quantity'=>$item['order_qty'],
                    'accepted_quantity'=>$item['accepted_quantity'],
                    'unit'=>$item['unit_name'],
                    'rate'=>$item['rate'],
                    'discount'=>$item['discount'],
                    'rate_aftr_discount'=>$rate_aftr_discount,
                    'value'=>$value,
                    'mac_date'=>date('d-m-Y',strtotime($item['mac_date'])),
                    'created_by'=>$item['f_name']. ' '.$item['l_name'], 
                    'created_at'=>date('d-m-Y',strtotime($item['created_at'])),

            );
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'MAC/WOA Number',
            'Invoice Number',
            'Item Code',
            'HSN/SAC Code',
            'Item Type',
            'Item Description',
            'Lot Number',
            'Supplier',
            'Invoice Quantity',
            'Accepted Quantity',
            'Unit',
            'Rate',
            'Discount',
            'Unit Rate After Discount',
            'Value',
            'MAC Date',
            'Created By',
            'Created At',
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
