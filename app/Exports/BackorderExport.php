<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\Web\FGS\FgsreportController;

class BackorderExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    private $info;

    public function __construct($data_oef, $data_grs, $data_pi)
    {
        $this->data_oef = $data_oef;
        $this->data_grs = $data_grs;
        $this->data_pi = $data_pi;
    }
    public function collection()
    {
        $i = 1;

        $data = [];
        foreach ($this->data_oef as $oef) {
            if ($oef->oef_date) {
                $oef_date = date('d-m-Y', strtotime($oef->oef_date));
            } else {
                $oef_date = '';
            }
            if($oef->mrp)
            {
                $total_rate = $oef['remaining_qty_after_cancel']*$oef['mrp'];
                $discount_value = $total_rate*$oef['discount']/100;
                $discounted_value = $total_rate-$discount_value;
                $igst_value = $total_rate*$oef['igst']/100;
                $sgst_value = $total_rate*$oef['sgst']/100;
                $cgst_value = $total_rate*$oef['cgst']/100;
                $total_value = $discounted_value+$igst_value+$cgst_value+$sgst_value;
            }

            $data[] = array(
                '#' => $i++,
                'Doc_Date' => $oef_date,
                'Doc_No' => $oef['oef_number'],
                'Customer_Name' => $oef['firm_name'],
                'Order_No' => $oef['order_number'],
                'Order_Date' => $oef['order_date'],
                'Item_Code' => $oef['sku_code'],
                'Item_Description' => $oef['discription'],
                'Category'=>$oef['category_name'],
                'Pending_Qty'=> $oef['remaining_qty_after_cancel'],
                'Total_Pending_Value' => $oef['mrp'],
                'discount' =>$oef['discount'],
                'gst' =>"IGST:".$oef['igst'].", SGST:".$oef['sgst'].", CGST:".$oef['cgst'],
                'value'=>(number_format((float)($total_value), 2, '.', '')),

            );
        }
        foreach ($this->data_grs as $grs) {
            if ($grs->grs_date) {
                $grs_date = date('d-m-Y', strtotime($grs->grs_date));
            } else {
                $grs_date = '';
            }
            if($grs->mrp)
            {
                $total_rate = $grs['remaining_qty_after_cancel']*$grs['mrp'];
                $discount_value = $total_rate*$grs['discount']/100;
                $discounted_value = $total_rate-$discount_value;
                $igst_value = $total_rate*$grs['igst']/100;
                $sgst_value = $total_rate*$grs['sgst']/100;
                $cgst_value = $total_rate*$grs['cgst']/100;
                $total_value = $discounted_value+$igst_value+$cgst_value+$sgst_value;
            }

            $data[] = array(
                '#' => $i++,
                'Doc_Date' => $grs_date,
                'Doc_No' => $grs['grs_number'],
                'Customer_Name' => $grs['firm_name'],
                'Order_No' => "",
                'Order_Date' => "",
                'Item_Code' => $grs['sku_code'],
                'Item_Description' => $grs['discription'],
                'Category'=>$grs['category_name'],
                'Pending_Qty' => $grs['remaining_qty_after_cancel'],
                'Total_Pending_Value' => $grs['mrp'],
                'discount' =>$grs['discount'],
                'gst' =>"IGST:".$grs['igst'].", SGST:".$grs['sgst'].", CGST:".$grs['cgst'],
                'value'=>(number_format((float)($total_value), 2, '.', '')),


            );
        }
        foreach ($this->data_pi as $pi) {
            if ($pi->pi_date) {
                $pi_date = date('d-m-Y', strtotime($pi->pi_date));
            } else {
                $pi_date = '';
            }
            if($pi->mrp)
            {
                $total_rate = $pi['remaining_qty_after_cancel']*$pi['mrp'];
                $discount_value = $total_rate*$pi['discount']/100;
                $discounted_value = $total_rate-$discount_value;
                $igst_value = $total_rate*$pi['igst']/100;
                $sgst_value = $total_rate*$pi['sgst']/100;
                $cgst_value = $total_rate*$pi['cgst']/100;
                $total_value = $discounted_value+$igst_value+$cgst_value+$sgst_value;
                
            }
        


            $data[] = array(
                '#' => $i++,
                'Doc_Date' => $pi_date,
                'Doc_No' => $pi['pi_number'],
                'Customer_Name' => $pi['firm_name'],
                'Order_No' => "",
                'Order_Date' => "",
                'Item_Code' => $pi['sku_code'],
                'Item_Description' => $pi['discription'],
                'Category'=>$pi['category_name'],
                'Pending_Qty' => $pi['remaining_qty_after_cancel'],
                'Total_Pending_Value' => $pi['mrp'],
                'discount' =>$pi['discount'],
                'gst' =>"IGST:".$pi['igst'].", SGST:".$pi['sgst'].", CGST:".$pi['cgst'],
                'value'=>(number_format((float)($total_value), 2, '.', '')),

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
            'Order No',
            'Order Date',
            'Item Code',
            'Item Description',
            'Category',
            'Pending Qty',
            'Rate',
            'Discount(%)',
            'GST(%)',
            'Value',
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
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(12);
                // $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);

                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
