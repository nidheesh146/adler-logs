<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\FGS\product_stock_location;


class PendingDCExport implements FromCollection,WithHeadings,WithEvents,WithStyles
{
    private $dc_item;

    public function __construct($dc_item) 
    {
        $this->dc_item = $dc_item;
    }
    public function collection()
    {
        
       if($this->dc_item) {
        $i=1;
        $data = [];
        foreach($this->dc_item as $item)
        {
        //  dd($item);

            if ($item->rate) {
                $total_rate = $item['batch_qty'] * $item['rate'];
                $discount_value = $total_rate * $item['discount'] / 100;
                $taxable_value = $total_rate - $discount_value;
                $igst_value = $taxable_value * $item['igst'] / 100;
                $sgst_value = $taxable_value* $item['sgst'] / 100;
                $cgst_value = $taxable_value * $item['cgst'] / 100;
                $gst_value = $igst_value + $sgst_value + $cgst_value;
                $total_value = $taxable_value + $igst_value + $cgst_value + $sgst_value;
                $gst= "IGST:" . $item['igst'] . ", SGST:" . $item['sgst'] . ", CGST:" . $item['cgst'];
            } else {
                $taxable_value = 0;
                $igst_value = 0;
                $sgst_value = 0;
                $cgst_values = 0;
                $gst_value = 0;
                $discount_value = 0;
                $total_value = 0;
                $gst=0;
            }
            if($item['transaction_condition']==1)
            {
                $condition='Returnable';
            }else
            {
                $condition='Non Returnable';

            }

            if($item['stock_location_increase']!=0){
                $st_increase=product_stock_location::where('id',$item['stock_location_increase'])->first();
            }
            if($item['stock_location_increase']!=0){
                $st_decrese=product_stock_location::where('id',$item['stock_location_decrease'])->first();
            }
            $data[]= array(
                '#' => $i++,
                'Doc_No' => $item['doc_no'],
                'Doc_Date' => date('d-m-Y', strtotime($item['doc_date'])),
                'Item_Code' => $item['sku_code'],
                'Item_Description' => $item['discription'],
                'Customer_Name' => $item['firm_name'],
                'Qty'=> $item['batch_qty'],
                'Rate' => $item->rate,
                'disc' => $item->discount,
                'disc_value' => $discount_value,
                'Taxable_Value' => number_format((float)$taxable_value, 2, '.', ''),
                'gst'=>$gst,
                // 'gst' => "IGST:" . $item['igst'] . ", SGST:" . $item['sgst'] . ", CGST:" . $item['cgst'],
                'gst_value' => $gst_value,
                'Total_Amount' => number_format((float)($total_value), 2, '.', ''),
                'Category'=>$item['category_name'],
                'Condition'=>$condition,
                'Type'=>$item['transaction_name'],
                'stk_decrese'=>$st_decrese['location_name'],
                'stk_increase'=>$st_increase['location_name'],
                'State'=>$item['state_name'],
                'Zone'=>$item['zone_name'],
                'city'=>$item['city'],
                // 'nbvc'=>$item['oefid']
                // 'rate' => $oef['mrp'],
                // 'discount' =>$oef['discount'],
                // 'gst' =>"IGST:".$oef['igst'].", SGST:".$oef['sgst'].", CGST:".$oef['cgst'],
                // 'value'=>(number_format((float)($total_value), 2, '.', ''))


            );
        }
        return collect($data);
       }
    }
    public function headings(): array
    {
        return [
            '#',
            'Doc No',
            'Doc Date',
            'Item Code',
            'Item Description',
            'Customer Name',
            'Qty', 
            'Rate' ,
            'Disc'  ,   
            'Disc Value'     ,
            'Taxable Value',
            'GST',
            'GST Value',
            'Total Amount',
            'Category',
            'Transaction Condition',
            'Transaction Type',
            'Stk Increase',
            'Stk Decrease',
            'State',
            'Zone',
            'City',
            // 'Rate',
            // 'Discount(%)',
            // 'GST(%)',
           
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
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(13);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(15);

                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
