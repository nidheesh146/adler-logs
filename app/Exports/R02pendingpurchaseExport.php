<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
class R02pendingpurchaseExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    private $data;

    public function __construct($data)
    {
        // $this->inv_final_purchase_order_master = new inv_final_purchase_order_master;
        $this->data = $data;
    }
    public function collection()
    {
        $i=1;
        $data = [];
        foreach ($this->data as $item) {

            $delivery_date = $item['delivery_schedule'];
            if ($delivery_date) {
                $date = date('d-m-Y', strtotime($delivery_date));
            } else {
                $date = "";
            }

            $expected_delivey_date = DB::table('inv_purchase_req_quotation_item_supp_rel')
                ->where('quotation_id', '=', $item['quotation_id'])
                ->where('supplier_id', '=', $item['supplier_id'])
                ->where('item_id', '=', $item['item_id'])
                // ->select('committed_delivery_date')
                ->first();
                // dd($expected_delivey_date);
            if ($expected_delivey_date != NULL)
                $committed_delivery_date = date('d-m-Y', strtotime($expected_delivey_date->committed_delivery_date));
            else
                $committed_delivery_date = '';
            $gst = '';
            if ($item['igst'] != 0)
                $gst .= 'IGST:' . $item['igst'] . '%,';

            if ($item['cgst'] != 0)
                $gst .= 'CGST:' . $item['cgst'] . '%,';

            if ($item['sgst'] != 0)
                $gst .= 'SGST:' . $item['sgst'] . '%';

            $total_rate = $item['rate'];
            $discount_value = $total_rate * $item['discount'] / 100;
            $discounted_value = $total_rate - $discount_value;
            $igst_value = $total_rate * $item['igst'] / 100;
            $sgst_value = $total_rate * $item['sgst'] / 100;
            $cgst_value = $total_rate * $item['cgst'] / 100;
            $total_gst_value = $igst_value + $cgst_value + $sgst_value;
            $product_value = $discounted_value + $igst_value + $cgst_value + $sgst_value;
            $order_value = $product_value * $item['order_qty'];
            if ($item['pr_status'] == 1) {
                $apr_date = date('d-m-Y', strtotime($item['pr_date']));
            } else {
                $apr_date = "";
            }
            $data[] = [
                '#' => $i++,
                'pr_number' => $item['pr_no'],
                'pr_date' => $apr_date,
                'po_number' => $item['po_number'],
                'vendor' => $item['vendor_name'],
                'po_date' => date('d-m-Y', strtotime($item['po_date'])),
                'updated_at' => date('d-m-Y', strtotime($item['updated_at'])),
                'item_code' => $item['item_code'],
                'hsn_code' => $item['hsn_code'],
                'type' => $item['type_name'],
                'short_description' => $item['short_description'],
                'rate' => $item['rate'],
                'discount' => $item['discount'],
                'discounted_value' => $discounted_value,
                'gst' => $gst,
                'total_gst_value' => $total_gst_value,
                'product_value' => (number_format((float)($product_value), 2, '.', '')),
                'order_value' => (number_format((float)($order_value), 2, '.', '')),
                'order_qty' => $item['order_qty'],
                'qty_to_invoice' => $item['qty_to_invoice'],
                'cancelled_qty' => $item['cancelled_qty'],
                'unit_name' => $item['unit_name'],
                //'createdBy'=>$item['f_name']." ".$item['l_name'],
                // 'expected_delivery_date' => $committed_delivery_date,
                'expected_delivery_date' => $date,

            ];
        }
        return collect($data);

    }
    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'PR Approve Date',
            'PO/WO Number',
            'Supplier',
            'PO/WO Date',
            'Approved Date',
            'Item Code',
            'HSN/SAC Code',
            'Item Type',
            'Item Description',
            'Rate',
            'Discount(%)',
            'Discounted Value',
            'GST(%)',
            'GST Value',
            'Product Value',
            'Order Value',
            'Order Qty',
            'Pending Qty ',
            'Cancelled qty',
            'Unit',
            'Expected Delivery Date',

        ];
    }
    public function styles(Worksheet $sheet)
    {

        return [
            // Style the first row as bold text.
            1    => ['font' => ['size' => 12, 'bold' => true]],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(20);
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
