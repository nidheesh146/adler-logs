<?php

namespace App\Exports;
use App\Models\PurchaseDetails\customer_supplier;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomerSupplierExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{ 
    private $request;

    public function __construct($request) 
    {
        $this->request = $request;
    }
    public function collection()
{
    if ($this->request == 'null') {
        $items = customer_supplier::select(
            'customer_supplier.id as id',
            'customer_supplier.firm_name',
            'customer_supplier.contact_person',
            'customer_supplier.designation',
            'customer_supplier.contact_number',
            'customer_supplier.billing_address',
            'customer_supplier.city',
            'state.state_name',
            'zone.zone_name',
            'currency_exchange_rate.currency_code',
            'customer_supplier.pan_number',
            'customer_supplier.gst_number',
            'customer_supplier.dl_number1',
            'customer_supplier.dl_number2',
            'customer_supplier.email',
            'customer_supplier.whatsapp_number',
            'customer_supplier.dl_expiry_date',
            'customer_supplier.zm_name',
            'customer_supplier.zm_email',
            'customer_supplier.sales_type',
            'customer_supplier.rm_name',
            'customer_supplier.rm_email',
            'customer_supplier.asm_name',
            'customer_supplier.asm_email',
            'customer_supplier.me_name',
            'customer_supplier.me_email',
            'customer_supplier.payment_terms',
            'customer_supplier.dl_number3'
        )
        ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
        ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
        ->leftJoin('currency_exchange_rate', 'currency_exchange_rate.currency_id', '=', 'customer_supplier.currency')
        ->orderBy('customer_supplier.id', 'DESC')
        ->get();
    } else {
        $items = customer_supplier::select(
            'customer_supplier.*',
            'customer_supplier.id as id',
            'customer_supplier.firm_name',
            'customer_supplier.contact_person',
            'customer_supplier.designation',
            'customer_supplier.contact_number',
            'customer_supplier.billing_address',
            'customer_supplier.city',
            'state.state_name',
            'zone.zone_name',
            'currency_exchange_rate.currency_code',
            'customer_supplier.pan_number',
            'customer_supplier.gst_number',
            'customer_supplier.dl_number1',
            'customer_supplier.dl_number2',
            'customer_supplier.email',
            'customer_supplier.whatsapp_number',
            'customer_supplier.dl_expiry_date',
            'customer_supplier.zm_name',
            'customer_supplier.zm_email',
            'customer_supplier.sales_type',
            'customer_supplier.rm_name',
            'customer_supplier.rm_email',
            'customer_supplier.asm_name',
            'customer_supplier.asm_email',
            'customer_supplier.me_name',
            'customer_supplier.me_email',
            'customer_supplier.payment_terms',
            'customer_supplier.dl_number3'
        )
        ->leftJoin('state', 'state.state_id', '=', 'customer_supplier.state')
        ->leftJoin('zone', 'zone.id', '=', 'customer_supplier.zone')
        ->leftJoin('currency_exchange_rate', 'currency_exchange_rate.currency_id', '=', 'customer_supplier.currency')
        ->orderBy('customer_supplier.id', 'DESC')
        ->get();
    }

    $i = 1;
    $data = [];
    foreach ($items as $item) {
        $rate_aftr_discount = $item['rate'] - ($item['rate'] * $item['discount']) / 100;
        $value = $item['order_qty'] * $rate_aftr_discount;

        $dl1 = ($item['dl_number1'] == '0000-00-00') ? 'NA' : ($item['dl_number1'] ?? 'NA');
        $dl2 = ($item['dl_number2'] == '0000-00-00') ? 'NA' : ($item['dl_number2'] ?? 'NA');
        $dl_expiry_date = ($item['dl_expiry_date'] == '0000-00-00') ? 'NA' : (isset($item['dl_expiry_date']) ? date('d-m-Y', strtotime($item['dl_expiry_date'])) : 'NA');

        $status = ($item['status_type'] == 1) ? "Active" : "Inactive";

        $data[] = [
            '#' => $i++,
            'Firm Name' => $item['firm_name'],
            'Contact Person' => $item['contact_person'],
            'Designation' => $item['designation'],
            'Contact Number' => $item['contact_number'],
            'Whatsapp Number' => $item['whatsapp_number'],
            'E Mail' => $item['email'],
            'Billing Address' => $item['billing_address'],
            'City' => $item['city'],
            'State' => $item['state_name'],
            'Zone' => $item['zone_name'],
            'Shipping Address' => $item['shipping_address'],
            'Pan Number' => $item['pan_number'],
            'GST Number' => $item['gst_number'],
            'DL Number1' => $dl1,
            'DL Number2' => $dl2,
            'DL Expiry Date' => $dl_expiry_date,
            'DL Number3' => $item['item'],
            'Currency' => $item['currency_code'],
            'ZM Name' => $item['zm_name'],
            'ZM Email' => $item['zm_email'],
            'RM Name' => $item['rm_name'],
            'RM Email' => $item['rm_email'],
            'ASM Name' => $item['asm_name'],
            'ASM Email' => $item['asm_email'],
            'ME/SE Name' => $item['me_name'],
            'ME/SE Email' => $item['me_email'],
            'Payment Terms' => $item['payment_terms'],
            'Sales Type' => $status,
            'master_type' => $item['master_type'],
            'WEF' => date('d-m-Y', strtotime($item['created_at'])),
        ];
    }
    return collect($data);
}

    public function headings(): array
    { 
        return [
            '#',
            'Firm Name',
            'Contact Person',
            'Designation',
            'Contact Number',
            'Whatsapp Number',
            'E Mail',
            'Billing Address',
            'City',
            'State',
            'Zone',
            'Shipping Address',
            'Pan Number',
            'GST Number',
            'DL number ( Form 20B)',
            'DL number ( Form 21B)',
            'DL Expiry Date',
            'DL number ( Other if Any)',
            'Currency',
            'ZM Name',
            'ZM Email',
            'RM Name',
            'RM Email',
            'ASM Name',
            'ASM Email',
            'ME/SE Name',
            'ME/SE Email',
            'Payment Terms',
            'Sales Type',
            'Customer/Supplier',
            'WEF',
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
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('Z')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('AA')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('AB')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('AC')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('AD')->setWidth(20);

                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
