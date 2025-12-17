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
            $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime('01-01-2024'))];
            $condition1[] = ['inv_final_purchase_order_master.type', '=', 'PO'];
            $condition1[] = ['inv_final_purchase_order_master.status', '=', 1];
           
            $po_item = inv_final_purchase_order_item::select(
                'inv_final_purchase_order_rel.master',
                'inv_final_purchase_order_rel.item',
                'inv_final_purchase_order_item.order_qty',
               // DB::raw('inv_final_purchase_order_item.order_qty - COALESCE((SELECT SUM(order_qty) FROM inv_supplier_invoice_item WHERE po_master_id = inv_final_purchase_order_master.id AND po_item_id = inv_final_purchase_order_item.id), 0) AS qty_to_invoice'),
                'inv_final_purchase_order_item.current_invoice_qty',
                'inv_final_purchase_order_item.rate',
                'inv_final_purchase_order_item.discount',
                'inv_final_purchase_order_item.gst',
                'inventory_rawmaterial.item_code',
                'inventory_rawmaterial.short_description',
                'inventory_rawmaterial.hsn_code',
                'inv_purchase_req_quotation.rq_no',
                'inv_unit.unit_name',
                'inventory_gst.igst',
                'inventory_gst.sgst',
                'inventory_gst.cgst',
                'inv_item_type.type_name as type',
                'inv_final_purchase_order_master.po_number',
                'inv_supplier.vendor_name',
                'inv_final_purchase_order_master.po_date',
                'inv_final_purchase_order_item.cancelled_qty',
                'inv_final_purchase_order_master.created_at',
                'inv_final_purchase_order_master.updated_at',
                'user.f_name',
                'user.l_name',
                'inv_supplier.id as supplier_id',
                'inv_purchase_req_quotation.quotation_id',
                'inv_final_purchase_order_item.item_id',
                'inv_purchase_req_master.pr_no as pr_number',
                'inv_supplier.vendor_id',
                'inv_supplier.vendor_name'
            )
            ->leftJoin('inv_final_purchase_order_rel', 'inv_final_purchase_order_rel.item', '=', 'inv_final_purchase_order_item.id')
            ->leftJoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id', '=', 'inv_final_purchase_order_rel.master')
            ->leftJoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id', '=', 'inv_final_purchase_order_item.item_id')
            ->leftJoin('inv_purchase_req_master_item_rel', 'inv_purchase_req_master_item_rel.item', '=', 'inv_purchase_req_item.requisition_item_id')
            ->leftJoin('inv_purchase_req_master', 'inv_purchase_req_master.master_id', '=', 'inv_purchase_req_master_item_rel.master')
            ->leftJoin('inv_purchase_req_quotation', 'inv_purchase_req_quotation.quotation_id', '=', 'inv_final_purchase_order_master.rq_master_id')
            ->leftJoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
            ->leftJoin('inventory_rawmaterial', 'inventory_rawmaterial.id', '=', 'inv_purchase_req_item.Item_code')
            ->leftJoin('user', 'user.user_id', '=', 'inv_final_purchase_order_master.created_by')
            ->leftJoin('inv_unit', 'inv_unit.id', '=', 'inventory_rawmaterial.issue_unit_id')
            ->leftJoin('inventory_gst', 'inventory_gst.id', '=', 'inv_final_purchase_order_item.gst')
            ->leftJoin('inv_item_type', 'inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
            ->leftJoin('inv_supplier_invoice_item', function ($join) {
                $join->on('inv_supplier_invoice_item.po_master_id', '=', 'inv_final_purchase_order_master.id')
                     ->on('inv_supplier_invoice_item.po_item_id', '=', 'inv_final_purchase_order_item.id');
            })
            ->where(function ($query) {
                $query->whereNull('inv_supplier_invoice_item.id')
                      ->orWhereRaw('(inv_final_purchase_order_item.order_qty - COALESCE((SELECT SUM(order_qty) FROM inv_supplier_invoice_item WHERE po_master_id = inv_final_purchase_order_master.id AND po_item_id = inv_final_purchase_order_item.id), 0)) > 0');
            })
            //->havingRaw('SUM(inv_final_purchase_order_item.order_qty) - COALESCE(SUM(inv_supplier_invoice_item.order_qty), 0) > 0.01') 
            
            ->where('inv_final_purchase_order_master.status', '=', 1)
            ->where('inv_final_purchase_order_item.order_qty', '>', 0) // Add this line to filter out order_qty <= 0

            ->where($condition2)
            ->where($condition1)
            ->where(function ($query) {
                $query->where('inv_final_purchase_order_master.po_number', 'like', 'WOI3-2324-322')
                      ->orWhere('inv_final_purchase_order_master.po_date', '>=', '2024-05-01');
            })
            ->distinct() // Ensures duplicate rows are filtered out
            ->orderby('inv_final_purchase_order_master.id', 'asc')
            ->get();  // Use get() for export instead of paginate
         //   dd('check');
                $data = [];
                $i = 1;
                $data = [];
                
                foreach ($po_item as $item) {
                    $expected_delivery_date = DB::table('inv_purchase_req_quotation_item_supp_rel')
                        ->where('quotation_id', '=', $item['quotation_id'])
                        ->where('supplier_id', '=', $item['supplier_id'])
                        ->where('item_id', '=', $item['item_id'])
                        ->select('committed_delivery_date')
                        ->first();
                
                    if ($item->delivery_schedule != NULL) {
                        $committed_delivery_date = date('d-m-Y', strtotime($item->delivery_schedule));
                    } else {
                        $committed_delivery_date = '';
                    }
                
                    $gst = '';
                    if ($item['igst'] != 0) {
                        $gst .= 'IGST:' . $item['igst'] . '%,';
                    }
                    if ($item['cgst'] != 0) {
                        $gst .= 'CGST:' . $item['cgst'] . '%,';
                    }
                    if ($item['sgst'] != 0) {
                        $gst .= 'SGST:' . $item['sgst'] . '%';
                    }
                
                    $total_rate = $item['rate'];
                    $discount_value = $total_rate * $item['discount'] / 100;
                    $discounted_value = $total_rate - $discount_value;
                    $igst_value = $total_rate * $item['igst'] / 100;
                    $sgst_value = $total_rate * $item['sgst'] / 100;
                    $cgst_value = $total_rate * $item['cgst'] / 100;
                    $total_gst_value = $igst_value + $cgst_value + $sgst_value;
                    $product_value = $discounted_value + $igst_value + $cgst_value + $sgst_value;
                    $order_value = $product_value * $item['order_qty'];
                
                    // Calculate remaining quantity
                    $invoicedQty = DB::table('inv_supplier_invoice_item')
                        ->where('po_master_id', '=', $item['master_id'])
                        ->where('po_item_id', '=', $item['id'])
                        ->sum('order_qty');
                    $remaining_qty = $item['order_qty'] - $invoicedQty;
                
                    if ($remaining_qty > 0) { 
                        $data[] = [
                            '#' => $i++,
                            'pr_number' => $item['pr_number'],
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
                            'product_value' => number_format((float)$product_value, 2, '.', ''),
                            'order_value' => number_format((float)$order_value, 2, '.', ''),
                            'order_qty' => $item['order_qty'],
                           'remaining_qty' => $remaining_qty, // Updated field
                            'cancelled_qty' => $item['cancelled_qty'],
                            'unit_name' => $item['unit_name'],
                            'expected_delivery_date' => $committed_delivery_date,
                        ];
                    }
                    
                }
            }                
        else
        {
           // dd('else condition');
            $condition2=[];
            //$condition1=[];
            $condition1[] = ['inv_final_purchase_order_master.po_date', '>=', date('Y-m-d', strtotime('01-01-2024'))];
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
            
            $po_item = inv_final_purchase_order_item::select(
                'inv_final_purchase_order_rel.master',
                'inv_final_purchase_order_rel.item',
                'inv_final_purchase_order_item.order_qty',
                DB::raw('inv_final_purchase_order_item.order_qty - COALESCE((SELECT SUM(order_qty) FROM inv_supplier_invoice_item WHERE po_master_id = inv_final_purchase_order_master.id AND po_item_id = inv_final_purchase_order_item.id), 0) AS qty_to_invoice'),
                'inv_final_purchase_order_item.current_invoice_qty',
                'inv_final_purchase_order_item.rate',
                'inv_final_purchase_order_item.discount',
                'inv_final_purchase_order_item.gst',
                'inventory_rawmaterial.item_code',
                'inventory_rawmaterial.short_description',
                'inventory_rawmaterial.hsn_code',
                'inv_purchase_req_quotation.rq_no',
                'inv_unit.unit_name',
                'inventory_gst.igst',
                'inventory_gst.sgst',
                'inventory_gst.cgst',
                'inv_item_type.type_name as type',
                'inv_final_purchase_order_master.po_number',
                'inv_supplier.vendor_name',
                'inv_final_purchase_order_master.po_date',
                'inv_final_purchase_order_item.cancelled_qty',
                'inv_final_purchase_order_master.created_at',
                'inv_final_purchase_order_master.updated_at',
                'user.f_name',
                'user.l_name',
                'inv_supplier.id as supplier_id',
                'inv_purchase_req_quotation.quotation_id',
                'inv_final_purchase_order_item.item_id',
                'inv_purchase_req_master.pr_no as pr_number',
                'inv_supplier.vendor_id',
                'inv_supplier.vendor_name',
                'inv_final_purchase_order_item.delivery_schedule'
            )
            ->leftJoin('inv_final_purchase_order_rel', 'inv_final_purchase_order_rel.item', '=', 'inv_final_purchase_order_item.id')
            ->leftJoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id', '=', 'inv_final_purchase_order_rel.master')
            ->leftJoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id', '=', 'inv_final_purchase_order_item.item_id')
            ->leftJoin('inv_purchase_req_master_item_rel', 'inv_purchase_req_master_item_rel.item', '=', 'inv_purchase_req_item.requisition_item_id')
            ->leftJoin('inv_purchase_req_master', 'inv_purchase_req_master.master_id', '=', 'inv_purchase_req_master_item_rel.master')
            ->leftJoin('inv_purchase_req_quotation', 'inv_purchase_req_quotation.quotation_id', '=', 'inv_final_purchase_order_master.rq_master_id')
            ->leftJoin('inv_supplier', 'inv_supplier.id', '=', 'inv_final_purchase_order_master.supplier_id')
            ->leftJoin('inventory_rawmaterial', 'inventory_rawmaterial.id', '=', 'inv_purchase_req_item.Item_code')
            ->leftJoin('user', 'user.user_id', '=', 'inv_final_purchase_order_master.created_by')
            ->leftJoin('inv_unit', 'inv_unit.id', '=', 'inventory_rawmaterial.issue_unit_id')
            ->leftJoin('inventory_gst', 'inventory_gst.id', '=', 'inv_final_purchase_order_item.gst')
            ->leftJoin('inv_item_type', 'inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
            ->leftJoin('inv_supplier_invoice_item', function ($join) {
                $join->on('inv_supplier_invoice_item.po_master_id', '=', 'inv_final_purchase_order_master.id')
                     ->on('inv_supplier_invoice_item.po_item_id', '=', 'inv_final_purchase_order_item.id');
            })
            ->where(function ($query) {
                $query->whereNull('inv_supplier_invoice_item.id')
                      ->orWhereRaw('(inv_final_purchase_order_item.order_qty - COALESCE((SELECT SUM(order_qty) FROM inv_supplier_invoice_item WHERE po_master_id = inv_final_purchase_order_master.id AND po_item_id = inv_final_purchase_order_item.id), 0)) > 0');
            })
            
            ->where('inv_final_purchase_order_master.status', '=', 1)
            ->where('inv_final_purchase_order_item.order_qty', '>', 0)
            ->where($condition2)
            ->where($condition1)
            ->where(function ($query) {
                $query->where('inv_final_purchase_order_master.po_number', 'like', 'WOI3-2324-322')
                      ->orWhere('inv_final_purchase_order_master.po_date', '>=', '2024-01-01');
            })
            ->distinct()
            ->orderBy('inv_final_purchase_order_master.po_date', 'desc')
            ->get();
          // dd('else');

           $i = 1;
$data = [];
foreach ($po_item as $item) {
    if (empty($item->item_code)) {
        continue;
    }
    
    $key = $item->master . '-' . $item->item;
    $invoicedQty = $invoiceTotals[$key] ?? 0;

    $committed_delivery_date = $item->delivery_schedule 
        ? date('d-m-Y', strtotime($item->delivery_schedule)) 
        : 'N.A.';

    $gst = '';
    if ($item->igst != 0) $gst .= 'IGST:' . $item->igst . '% ';
    if ($item->cgst != 0) $gst .= 'CGST:' . $item->cgst . '% ';
    if ($item->sgst != 0) $gst .= 'SGST:' . $item->sgst . '%';

    $total_rate = $item->rate;
    $discount_value = $total_rate * $item->discount / 100;
    $discounted_value = $total_rate - $discount_value;
    $igst_value = $total_rate * $item->igst / 100;
    $sgst_value = $total_rate * $item->sgst / 100;
    $cgst_value = $total_rate * $item->cgst / 100;
    $total_gst_value = $igst_value + $cgst_value + $sgst_value;
    $product_value = $discounted_value + $total_gst_value;
    $order_value = $product_value * $item->order_qty;

    $qty_to_invoice = number_format((float)$item->qty_to_invoice, 2, '.', '');  

    // Skip items where qty_to_invoice is 0.0 or less
    if ($qty_to_invoice <= 0) {
        continue;
    }

    $data[] = [
        '#' => $i++,
        'pr_number' => $item->pr_number,
        'po_number' => $item->po_number,
        'vendor' => $item->vendor_name,
        'po_date' => date('d-m-Y', strtotime($item->po_date)),
        'updated_at' => date('d-m-Y', strtotime($item->updated_at)),
        'item_code' => $item->item_code,
        'hsn_code' => $item->hsn_code,
        'type' => $item->type,
        'short_description' => $item->short_description,
        'rate' => $item->rate,
        'discount' => $item->discount,
        'discounted_value' => $discounted_value,
        'gst' => $gst,
        'total_gst_value' => $total_gst_value,
        'product_value' => number_format($product_value, 2, '.', ''),
        'order_value' => number_format($order_value, 2, '.', ''),
        'order_qty' => $item->order_qty,
        'qty_to_invoice' => $qty_to_invoice,
        'cancelled_qty' => $item->cancelled_qty,
        'unit_name' => $item->unit_name,
        'expected_delivery_date' => $committed_delivery_date,
    ];
}

return collect($data);
        }}        
    public function headings(): array
    {
        return [
            '#',
            'PR Number',
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
