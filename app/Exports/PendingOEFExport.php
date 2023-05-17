<?php

namespace App\Exports;

use App\Models\FGS\fgs_oef_item;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PendingOEFExport implements FromCollection, WithHeadings, WithStyles,WithEvents
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
            $items = fgs_oef_item::select('fgs_oef_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','product_price_master.mrp',
            'fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_number','fgs_oef.order_date','order_fulfil.order_fulfil_type','transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.pan_number','customer_supplier.gst_number',
            'customer_supplier.shipping_address','customer_supplier.billing_address','customer_supplier.sales_type','customer_supplier.contact_person','customer_supplier.sales_type','customer_supplier.city',
            'customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email','currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3',
            'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id')
                            ->leftjoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                            ->leftjoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                            ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                            ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                            ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                            ->leftJoin('state','state.state_id','=','customer_supplier.state')
                            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                            ->leftjoin('product_product','product_product.id','=','fgs_oef_item.product_id')
                            ->leftjoin('product_price_master','product_price_master.product_id','=','product_product.id')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                            //->where($condition)
                            ->whereNotIn('fgs_oef.id',function($query) {

                                $query->select('fgs_grs.oef_id')->from('fgs_grs')->where('fgs_grs.status','=',1);
                            
                            })->where('fgs_oef.status','=',1)
                            ->where('fgs_oef_item.coef_status','=',0)
                            ->orderBy('fgs_oef_item.id','DESC')
                            ->distinct('fgs_oef_item.id')
                            ->get();

        }
        else
        {
            $condition =[];
            if($this->request->oef_number)
            {
                $condition[] = ['fgs_oef.oef_number','like', '%' . $this->request->oef_number . '%'];
            }
            if($this->request->order_number)
            {
                $condition[] = ['fgs_oef.order_number','like', '%' . $this->request->order_number . '%'];
            }
            if($this->request->from)
            {
                $condition[] = ['fgs_oef.oef_date', '>=', date('Y-m-d', strtotime('01-' . $this->request->from))];
                $condition[] = ['fgs_oef.oef_date', '<=', date('Y-m-t', strtotime('01-' . $this->request->from))];
            }
            $items = fgs_oef_item::select('fgs_oef_item.*','product_product.sku_code','product_product.discription','product_product.hsn_code','product_price_master.mrp',
            'fgs_oef.oef_number','fgs_oef.oef_date','fgs_oef.order_number','fgs_oef.order_date','order_fulfil.order_fulfil_type','transaction_type.transaction_name','customer_supplier.firm_name','customer_supplier.pan_number','customer_supplier.gst_number',
            'customer_supplier.shipping_address','customer_supplier.billing_address','customer_supplier.sales_type','customer_supplier.contact_person','customer_supplier.sales_type','customer_supplier.city',
            'customer_supplier.contact_number','customer_supplier.designation','customer_supplier.email','currency_exchange_rate.currency_code','zone.zone_name','state.state_name','customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3',
            'inventory_gst.igst','inventory_gst.cgst','inventory_gst.sgst','inventory_gst.id as gst_id')
                            ->leftjoin('fgs_oef_item_rel','fgs_oef_item_rel.item','=','fgs_oef_item.id')
                            ->leftjoin('fgs_oef','fgs_oef.id','=','fgs_oef_item_rel.master')
                            ->leftJoin('order_fulfil','order_fulfil.id','=','fgs_oef.order_fulfil')
                            ->leftJoin('transaction_type','transaction_type.id','=','fgs_oef.transaction_type')
                            ->leftJoin('customer_supplier','customer_supplier.id','=','fgs_oef.customer_id')
                            ->leftJoin('zone','zone.id','=','customer_supplier.zone')
                            ->leftJoin('state','state.state_id','=','customer_supplier.state')
                            ->leftJoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')
                            ->leftjoin('product_product','product_product.id','=','fgs_oef_item.product_id')
                            ->leftjoin('product_price_master','product_price_master.product_id','=','product_product.id')
                            ->leftjoin('inventory_gst','inventory_gst.id','=','fgs_oef_item.gst')
                            ->whereNotIn('fgs_oef.id',function($query) {

                                $query->select('fgs_grs.oef_id')->from('fgs_grs')->where('fgs_grs.status','=',1);
                            
                            })->where($condition)
                            ->where('fgs_oef.status','=',1)
                            ->where('fgs_oef_item.coef_status','=',0)
                            ->orderBy('fgs_oef_item.id','DESC')
                            ->distinct('fgs_oef_item.id')
                            ->get();

        }
        $i=1;
        $data = [];
        foreach($items as $item)
        {

            $rate_aftr_discount = $item['rate']-($item['rate']*$item['discount'])/100;
            $value = $item['order_qty']*$rate_aftr_discount;
            if($item['expiry_control']==1)
            $expiry_control = 'Yes';
            else
            $expiry_control = 'No';
            $data[]= array(
                '#'=>$i++,
                'oef_number'=>$item['oef_number'],
                'sku_code'=>$item['sku_code'],
                'hsn_code'=>$item['hsn_code'],
                'discription'=>$item['discription'],
                'quantity'=>$item['quantity'],
                'rate'=>$item['rate'],
                'discount'=>$item['discount'],
                'gst' =>"IGST:".$item['igst'].", SGST:".$item['sgst'].", CGST:".$item['cgst'],
                'oef_date'=>date('d-m-Y',strtotime($item['oef_date'])),
                'order_number'=>$item['order_number'],
                'order_date'=>date('d-m-Y',strtotime($item['order_date'])),
                'order_fulfil_type'=>$item['order_fulfil_type'],
                'transaction_name'=>$item['transaction_name'],
                'order_date'=>date('d-m-Y',strtotime($item['order_date'])),
                'transaction_name'=>$item['transaction_name'],
                'due_date'=>date('d-m-Y',strtotime($item['due_date'])),
                'firm_name'=>$item['firm_name'],
                'contact_person'=>$item['contact_person'].'-'.$item['designation'],
                'contact_number'=>$item['contact_number'],
                'email'=>$item['email'],
                'shipping_address'=>$item['shipping_address'],
                'billing_address'=>$item['billing_address'],
                'created_at'=>date('d-m-Y',strtotime($item['created_at'])),


            );
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            '#',
            'OEF Number',
            'Product Sku Code',
            'HSNCode',
            'Description',
            'Quantity',
            'Rate',
            'Discount',
            'GST',
            'OEF Date',
            'Order Number',
            'Order Date',
            'Order Fulfil',
            'Transaction Type',
            'Due Date',
            'Customer',
            'Contact Person',
            'Contact Number',
            'Email',
            'Shipping_address',
            'Billing Address',
            //'Created By',
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
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(15);
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }

}
