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
        if($this->request=='null')
        {
            $items=   customer_supplier::select('customer_supplier.id as id','customer_supplier.firm_name','customer_supplier.contact_person','customer_supplier.designation','customer_supplier.contact_number',
                'customer_supplier.billing_address','customer_supplier.city','state.state_name','zone.zone_name','currency_exchange_rate.currency_code','customer_supplier.pan_number','customer_supplier.gst_number',
                'customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3','customer_supplier.email','customer_supplier.whatsapp_number','customer_supplier.dl_expiry_date','customer_supplier.sales_person_name','customer_supplier.sales_person_email',
                'customer_supplier.sales_type')
                   ->leftjoin('state','state.state_id','=','customer_supplier.state')                
                   ->leftjoin('zone','zone.id','=','customer_supplier.zone')
                   ->leftjoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')            
                                   ->orderBy('customer_supplier.id','DESC')
                                    ->get();

        }
        else
        {
            $items=   customer_supplier::select('customer_supplier.*','customer_supplier.id as id','customer_supplier.firm_name','customer_supplier.contact_person','customer_supplier.designation','customer_supplier.contact_number',
                'customer_supplier.billing_address','customer_supplier.city','state.state_name','zone.zone_name','currency_exchange_rate.currency_code','customer_supplier.pan_number','customer_supplier.gst_number',
                'customer_supplier.dl_number1','customer_supplier.dl_number2','customer_supplier.dl_number3','customer_supplier.email','customer_supplier.whatsapp_number','customer_supplier.dl_expiry_date','customer_supplier.sales_person_name','customer_supplier.sales_person_email',
                'customer_supplier.sales_type')
                   ->leftjoin('state','state.state_id','=','customer_supplier.state')                
                   ->leftjoin('zone','zone.id','=','customer_supplier.zone')
                   ->leftjoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','customer_supplier.currency')            
                                   ->orderBy('customer_supplier.id','DESC')
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
                    'Firm Name'=>$item['firm_name'],
                    'Contact Person'=>$item['contact_person'],
                    'Designation'=>$item['designation'],
                    'Contact Number'=>$item['contact_number'],
                    'Billing Address'=>$item['billing_address'],
                    'City'=>$item['city'],
                    'State'=>$item['state_name'],
                    'Zone'=>$item['zone_name'],
                    'Shipping Address'=>$item['shipping_address'],
                    'Pan Number'=>$item['pan_number'],
                    'GST Number'=>$item['gst_number'],
                    'DL Number1'=>$item['dl_number1'],
                    'DL Number2'=>$item['dl_number2'],
                    'DL Number3'=>$item['dl_number3'],
                    'E Mail'=>$item['email'],
                    'Currency'=>$item['currency_code'],
                    'Whatsapp Number'=>$item['whatsapp_number'],
                    'DL Expiry Date'=>date('d-m-Y',strtotime($item['dl_expiry_date'])),
                    'Sales Person Name'=>$item['sales_person_name'],
                    'Sales Person Email'=>$item['sales_person_email'],
                     'Sales Type'=>$item['sales_type'],
                    'created_at'=>date('d-m-Y',strtotime($item['created_at'])),

            );
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
            'Billing Address',
            'City',
            'State',
            'Zone',
            'Shipping Address',
            'Pan Number',
            'GST Number',
            'DL Number1',
            'DL Number2',
            'DL Number3',
            'E Mail',
            'Currency',
            'Whatsapp Number',
            'DL Expiry Date',
            'Sales Person Name',
            'Sales Person Email',
            'Sales Type',
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
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);
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
