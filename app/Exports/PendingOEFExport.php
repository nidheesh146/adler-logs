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

class PendingOEFExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    private $request;

    public function __construct($request) 
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Check if request is null
        if ($this->request == 'null') {
            $items = $this->getDefaultItems();
        } else {
            $items = $this->getFilteredItems();
        }

        // Prepare data for export
        $i = 1;
        $data = [];
       // dd($items);
        foreach ($items as $item) {

            $rate_aftr_discount = $item['rate'] - ($item['rate'] * $item['discount']) / 100;
            $value = $item['order_qty'] * $rate_aftr_discount;
            
            if ($item->mrp) {
                $total_rate = $item['quantity_to_allocate'] * $item['mrp'];
                $discount_value = $total_rate * $item['discount'] / 100;
                $discounted_value = $total_rate - $discount_value;
                $igst_value = $discounted_value * $item['igst'] / 100;
                $sgst_value = $discounted_value * $item['sgst'] / 100;
                $cgst_value = $discounted_value * $item['cgst'] / 100;
                $total_value = $discounted_value + $igst_value + $sgst_value + $cgst_value;
            } else {
                $total_value = 0;
            }

            $data[] = array(
                '#' => $i++,
                'Doc_Date' => date('d-m-Y', strtotime($item['oef_date'])),
                'Doc_No' => $item['oef_number'],
                'Customer_Name' => $item['firm_name'],
                'Zone' => $item['zone_name'],
                'State' => $item['state_name'],
                'City' => $item['city'],
                'Order_No' => $item['order_number'],
                'Order_Date' => date('d-m-Y', strtotime($item['order_date'])),
                'Item_Code' => $item['sku_code'],
                'Item_Description' => $item['discription'],
                'Business_Category' => $item['old_category_name'],  // Fixed case
                'Product_Category' => $item['new_category_name'],  // Fixed case
                'Pending_Qty' => $item['quantity_to_allocate'],
                'Pending_Value' => number_format((float)($total_value), 2, '.', '')
            );
        }
        return collect($data);
    }

    public function headings(): array
    {
        return [
            '#',
            'Doc Date',
            'Doc No',
            'Customer Name',
            'Zone',
            'State',
            'City',
            'Order No',
            'Order Date',
            'Item Code',
            'Item Description',
            'Business Category',
            'Product Category',
            'Pending Qty',
            'Pending Value',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['size' => 12, 'bold' => true]], // Style for the first row (header)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(15);
            },
        ];
    }

    private function getDefaultItems()
    {
        return fgs_oef_item::select(
            'fgs_oef.*',
            'product_product.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            'fgs_oef_item.remaining_qty_after_cancel',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name as old_category_name',
            'fgs_product_category_new.category_name as new_category_name',  // new category
            'fgs_oef_item.rate as mrp',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',
            'zone.zone_name',
            'state.state_name',
            'customer_supplier.city'
        )
        ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
        ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
        ->leftJoin('product_product', 'product_product.id', '=', 'fgs_oef_item.product_id')
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'product_product.product_category_id')
        ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'product_product.new_product_category_id')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        ->leftJoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
        ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
        ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
        ->where('fgs_oef.status', '=', 1)
        ->where('fgs_oef_item.status', '=', 1)
        ->where('fgs_oef_item.quantity_to_allocate', '!=', 0)
        ->where('fgs_oef_item.remaining_qty_after_cancel', '!=', 0)
        ->where('fgs_oef_item.coef_status', '=', 0)
        ->distinct('fgs_oef.id')
        ->orderBy('fgs_oef.id', 'DESC')
        ->get();
    }

    private function getFilteredItems()
    {
        $condition = [];
        if ($this->request->oef_number) {
            $condition[] = ['fgs_oef.oef_number', 'like', '%' . $this->request->oef_number . '%'];
        }
        if ($this->request->order_number) {
            $condition[] = ['fgs_oef.order_number', 'like', '%' . $this->request->order_number . '%'];
        }
        if ($this->request->from) {
            $condition[] = ['fgs_oef.oef_date', '>=', date('Y-m-d', strtotime('01-' . $this->request->from))];
            $condition[] = ['fgs_oef.oef_date', '<=', date('Y-m-t', strtotime('01-' . $this->request->from))];
        }

        return fgs_oef_item::select(
            'fgs_oef.*',
            'product_product.*',
            'customer_supplier.firm_name',
            'customer_supplier.shipping_address',
            'customer_supplier.contact_person',
            'customer_supplier.contact_number',
            'fgs_oef_item.remaining_qty_after_cancel',
            'fgs_oef_item.quantity_to_allocate',
            'fgs_product_category.category_name as old_category_name',
            'fgs_product_category_new.category_name as new_category_name',  // new category
            'fgs_oef_item.rate as mrp',
            'fgs_oef_item.discount',
            'inventory_gst.igst',
            'inventory_gst.cgst',
            'inventory_gst.sgst',
            'zone.zone_name',
            'state.state_name',
            'customer_supplier.city'
        )
        ->leftJoin('fgs_oef_item_rel', 'fgs_oef_item_rel.item', '=', 'fgs_oef_item.id')
        ->leftJoin('fgs_oef', 'fgs_oef.id', '=', 'fgs_oef_item_rel.master')
        ->leftJoin('product_product', 'product_product.id', '=', 'fgs_oef_item.product_id')
        ->leftJoin('fgs_product_category', 'fgs_product_category.id', '=', 'product_product.product_category_id')
        ->leftJoin('fgs_product_category_new', 'fgs_product_category_new.id', '=', 'fgs_oef.new_product_category')
        ->leftJoin('customer_supplier', 'customer_supplier.id', '=', 'fgs_oef.customer_id')
        ->leftJoin('inventory_gst', 'inventory_gst.id', '=', 'fgs_oef_item.gst')
        ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
        ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
        ->where('fgs_oef.status', '=', 1)
        ->where('fgs_oef_item.status', '=', 1)
        ->where('fgs_oef_item.quantity_to_allocate', '!=', 0)
        ->where('fgs_oef_item.remaining_qty_after_cancel', '!=', 0)
        ->where($condition)
        ->distinct('fgs_oef.id')
        ->orderBy('fgs_oef.id', 'DESC')
        ->get();
    }
}
