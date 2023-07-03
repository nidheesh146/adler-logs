<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class InventoryTransactionExport implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    private $item_details;

    public function __construct($item_details) 
    {
        $this->item_details = $item_details;
    }
    public function collection()
    {
        function get_user($id)
        {
            $user=DB::table('user')
                    ->where('user_id','=',$id)
                    ->first();
            return $user->f_name.''.$user->l_name;
        }
        $i=1;
        $data = [];
        foreach($this->item_details as $item_detail)
        {
            if($item_detail->expiry_control ==1) 
            $expiry = 'Yes';
            elseif($item_detail->expiry_control==0) 
            $expiry = 'No'; 
            else
            $expiry = ''; 

            if($item_detail->miq_number)
            $miqdate = date('d-m-Y', strtotime($item_detail->miq_date));
            else
            $miqdate = '';

            if($item_detail->mac_number)
            $macdate = date('d-m-Y', strtotime($item_detail->mac_date));
            else
            $macdate = '';

            if($item_detail->mrd_number)
            $mrddate = date('d-m-Y', strtotime($item_detail->mrd_date));
            else
            $mrddate = '';

            if($item_detail->mrr_number)
            $mrrdate = date('d-m-Y', strtotime($item_detail->mrr_date));
            else
            $mrrdate = '';

            if($item_detail->sip_number)
            $sipdate = date('d-m-Y', strtotime($item_detail->sip_date));
            else
            $sipdate = '';

            if($item_detail->sir_number)
            $sirdate = date('d-m-Y', strtotime($item_detail->sir_date));
            else
            $sirdate = '';

            if($item_detail->sto_number)
            $stodate = date('d-m-Y', strtotime($item_detail->sto_date));
            else
            $stodate = '';

            if($item_detail->invoice_created_by)
            {
            $invoice_user= DB::table('user')
                                    ->where('user_id','=',$item_detail->invoice_created_by)
                                    ->first();
            $invoice_created_user = $invoice_user->f_name.' '.$invoice_user->l_name;
            }
            else
            {
                $invoice_created_user = '';
            }

            if($item_detail->lot_created_by)
            {
                $lot_user= DB::table('user')
                            ->where('user_id','=',$item_detail->lot_created_by)
                            ->first();
                //$lot_created_user = $lot_user->f_name.' '.$lot_user->l_name;
                $lot_created_user = '';
            }
            else
            {
                $lot_created_user = '';
            }

            if($item_detail->miq_created_by)
            {
                $miq_user= DB::table('user')
                                        ->where('user_id','=',$item_detail->miq_created_by)
                                        ->first();
                $miq_created_user = $miq_user->f_name.' '.$miq_user->l_name;
            }
            else
            {
                $miq_created_user = '';
            }

            if($item_detail->mac_created_by)
            {
                $mac_user= DB::table('user')
                                        ->where('user_id','=',$item_detail->mac_created_by)
                                        ->first();
                $mac_created_user = $mac_user->f_name.' '.$mac_user->l_name;
            }
            else
            {
                $mac_created_user = '';
            }

            if($item_detail->mrd_created_by)
            {
                $mrd_user= DB::table('user')
                            ->where('user_id','=',$item_detail->mrd_created_by)
                            ->first();
                $mrd_created_user = $mrd_user->f_name.' '.$mrd_user->l_name;
            }
            else
            {
                $mrd_created_user = '';
            }

            if($item_detail->mrr_created_by)
            {
                $mrr_user= DB::table('user')
                            ->where('user_id','=',$item_detail->mrr_created_by)
                            ->first();
                $mrr_created_user = $mrr_user->f_name.' '.$mrr_user->l_name;
            }
            else
            {
                $mrr_created_user = '';
            }
            if($item_detail->sip_created_by)
            {
                $sip_user= DB::table('user')
                            ->where('user_id','=',$item_detail->sip_created_by)
                            ->first();
                $sip_created_user = $sip_user->f_name.' '.$sip_user->l_name;
            }
            else
            {
                $sip_created_user = '';
            }
            // if($item_detail->sir_created_by)
            // {
            //     $sir_user= DB::table('user')
            //                 ->where('user_id','=',$item_detail->sir_created_by)
            //                 ->first();
            //     $sir_created_user = $sir_user->f_name.' '.$sir_user->l_name;
            // }
            $sir_created_user = '';

            if($item_detail->sto_created_by)
            {
                $sto_user= DB::table('user')
                            ->where('user_id','=',$item_detail->sto_created_by)
                            ->first();
                $sto_created_user = $sto_user->f_name.' '.$sto_user->l_name;
            }
            else
            {
                $sto_created_user = '';
            }

                                   
            $data[] = array(
                '#'=>$i++,
                'year'=>date('Y', strtotime($item_detail->invoice_date)),
                'month'=>date('m/y', strtotime($item_detail->invoice_date)),
                'basic_doc_no'=>$item_detail->item_code.'-'.$item_detail->po_number,
                'transaction_date'=>date('d-m-Y', strtotime($item_detail->transaction_date)),
                'item_code'=>$item_detail->item_code,
                'discription'=>$item_detail->discription,
                'type'=>$item_detail->type_name,
                'vendor_name'=>$item_detail->vendor_name,
                'vendor_id'=>$item_detail->vendor_id,
                'po_number'=>$item_detail->po_number,
                'unit_name'=>$item_detail->unit_name,
                'invoice_number'=>$item_detail->invoice_number,
                'invoice_qty'=>$item_detail->invoice_qty,
                'invoice_created_by'=>$invoice_created_user,
                'lot_number'=>$item_detail->lot_number,
                'lot_created_by'=>$lot_created_user,
                'miq_number'=>$item_detail->miq_number,
                'miq_date'=>$miqdate,
                'basic_rate'=>$item_detail->basic_rate,
                'value_inr'=>$item_detail->value_inr,
                'expiry_control'=>$expiry,
                'miq_created_by'=>$miq_created_user,

                'mac_number'=>$item_detail->mac_number,
                'mac_date'=>$macdate,
                'accepted_quantity'=>$item_detail->accepted_quantity,
                'mac_created_by'=>$mac_created_user,

                'mrd_number'=>$item_detail->mrd_number,
                'mrd_date'=>$mrddate,
                'rejected_quantity'=>$item_detail->rejected_quantity,
                'remarks'=>$item_detail->remarks,
                'mrd_created_by'=>$mrd_created_user,

                'mrr_number'=>$item_detail->mrr_number,
                'mrr_date'=>$mrrdate,
                'mrr_created_by'=>$mrr_created_user,

                'sip_number'=>$item_detail->sip_number,
                'sip_date'=>$sipdate,
                'qty_to_production'=>$item_detail->qty_to_production,
                'sip_created_by'=>$sip_created_user,
                'centre_code'=>$item_detail->centre_code,

                'sir_number'=>$item_detail->sir_number,
                'sir_date'=>$sirdate,
                'qty_to_return'=>$item_detail->qty_to_return,
                'sir_created_by'=>'',

                'sto_number'=>$item_detail->sto_number,
                'sto_date'=>$stodate,
                'transfer_qty'=>$item_detail->transfer_qty,
                'sto_created_by'=>$sto_created_user,

            );
        }
        return collect($data);
    }
    public function headings(): array
        {
        return [
            '#',
            'Cr. year',
            'Month', 
            'Code (ITEM+PO/WO)',
            'Txn_Entry_Dt',
            'Item Code',
            'Item Description',
            'Item Group',
            'Supplier Name',
            'Supplier Code',
            'PO / WO Number', 
            'Stk_Kpng_Unt',
            'Invoice No.', 
            'Invoice Qty.', 
            'Invoice Created',
            'Lot Number',
            'Lot Created',
            'MIQ No.', 
            'MIQ Date.',
            'Unit_Rate',
            'Value in INR', 
            'Expiry Control Required Y/N',
            'MIQ Created',
            'MAC No.', 
            'MAC Date.',
            'Accepted Qty.',
            'MAC Created',
            'MRD No.', 
            'MRD Date.',
            'Rejected Qty.',
            'Rejection Reason.',
            'MRD Created',
            'MRR No',
            'MRR Date',
            'MRR created',
            'SIP Number',
            'SIP Date',
            'SIP Qty.',
            'SIP Created',
            'Work Center',
            'SIR Number',
            'SIR Date',
            'SIR Qty.',
            'SIR Created',
            'STO Number',
            'STO Date',
            'STO Qty.',
            'STO Created',
            
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
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(20);
                
                // $cellRange = 'F1:F20000';
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
