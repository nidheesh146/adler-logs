<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
   
    <title>Final @if(str_starts_with($final_purchase['po_number'] , 'PO') )Partial Purchase Order @else Work Order @endif _cancellation_{{$final_purchase['vendor_name']}}_{{$final_purchase['po_number']}}</title>
</head>
<body>
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\PurchaseController')
    <style>
        .col1,.col3{
            float:left;
            width:23%;
            font-size:11px;
        }
        .col2{
            width:45%;
            float:left;
        }
        .attn {
            margin-top:32px;
            font-weight:bold;
           font-size:10px; 
           color:#1434A4;
        }
        .main-head{
            margin-top:10px;
            font-size:24px;
            font-weight:bold;
            font-style:Italic;
        }
        .col21{
            margin-top:-25px;
            float:left; 
            width:70%;
        }
        .col22{
            margin-top:-25px;
            float:left;
            width:30%;
        }
         .row2{
            display:block;
            font-size:11px;
            height:35px;
            border-bottom:solid 0.5px black;
        }
        .row3, .row4{
            display:block;
        }
        .intro{
            font-size:11px;
            font-style:italic;
            padding:10px;
        }
        .row3 table{
            width:100%;
            font-size:10px;
        }
        .row4{
            font-size:10px;
        }
        .col41, .col42{
            width:35%;
            float:left;
        }
        .col43{
            font-size:11px;
            float:right;
        }
        .remarks, .adler {
            height:50px;
        }
        .row3 table th{
            background-color:#B6D0E2;
        }
        .col51, .col52{
            font-size:11px;
            width:33%;
            float:left;
        }
        .col52{
            text-align:center;
        }
        .col53{
            font-size:11px;
            text-align:right;
            float:right;
        }
    </style>
   
    <div class="row1" style="height:150px;border-bottom:solid 2px black;">
        <div class="col1">
            To<br/>
            <strong>{{$final_purchase['vendor_name']}}</strong>
            <p>{{$final_purchase['address']}}<br/>
            <?php  
            if (!function_exists('SplitPhone'))
            {
            function SplitPhone($data)
            {
                $a = "";
                $arr = explode(",",ltrim(rtrim($data,']'),'['));
                $len = count($arr);
                echo trim($arr[0],' " ');
                
            }
            }
            if (!function_exists('SplitMail'))
            {
            function SplitMail($data)
            {
                $a = "";
                $arr = explode(",",ltrim(rtrim($data,']'),'['));
                $len = count($arr);
                echo trim($arr[0],' " ');
            }
            }
            ?>
            Cell No : {{ SplitPhone($final_purchase['contact_number']) }}<br/>
            <span style="font-size:10px;  overflow-wrap: break-word;">Email:<?php SplitMail($final_purchase['email'])?><br/><span>
            GSTIN :</p>

        </div>
        <div class="col2" style="text-align:center;">
            <div class="attn">Kind Attn: {{$final_purchase['contact_person']}}</div>
            <div class="main-head">
                    @if(str_starts_with($final_purchase['po_number'] , 'PO') )
                    Purchase Order Partial Cancellation
                    @else
                    Work Order Partial Cancellation 
                    @endif
            </div>
        </div>
        <div class="col3">
            From<br/>
            <span style="color:#1434A4;"><strong>ADLER HEALTHCARE PVT. LTD</strong></span>
            <p> Plot No-A1 MIDC, Sadavali(Devrukh),  Tal- Sangmeshwar, Dist -Ratnagiri ,  PIN-415804, Maharashtra, India<br/>
            CIN :U33125PN2020PTC195161 <br/>
            Company GSTIN :27AAJCB3689C1J</p>
        </div>
        <div class="col4" style="float:right;">
            <img src="{{asset('/img/logo.png')}}"  style="width:80px;">
        </div>
    </div><br/>
    <div class="row2">
        <div class="col21">
            <table>
                <tr>
                    <td>Supplier Quotation No</td>
                    <td>: {{$final_purchase['rq_no']}}</td>
                </tr>
                <tr>
                    <td >Supplier Quotation Date</td>
                    <td>: {{date('d M y',strtotime($final_purchase['quotation_date']))}}</td>
                </tr>
                <tr>
                    <td >Currency</td>
                    <td>: <?php echo( $fn->find_currency_code($final_purchase['rq_master_id'],$final_purchase['supplierId'])); ?></td>
                </tr>
            </table>
        </div>
        <div class="col22">
            <table style="float:right;">
                <tr>
                    <td> PO No</td>
                    <td>: {{$final_purchase['po_number']}}</td>
                </tr>
                <tr>
                    <td> PO Date</td>
                    <td>: {{date('d M y',strtotime($final_purchase['po_date']))}} </td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>: {{$final_purchase['dept_name']}}</td>
                </tr>
            </table>
        </div>
        
    </div>
    <style>
        th{
            text-align:center;
        }
    </style>
    <div class="row3">
        
        <table border="1">
            <tr>
                <th rowspan="2">S.NO</th>
                <th rowspan="2" style="width:10%;">
                    @if(str_starts_with($final_purchase['po_number'] , 'PO') ) HSN CODE 
                    @else
                    SAC CODE
                    @endif
                </th>
                <th rowspan="2">ITEM.NO</th>
                <th rowspan="2" width='30%'>ITEM DESCRIPTION</th>
                <th rowspan="2">ORDER QTY</th>
                <th rowspan="2">CANCEL QTY</th>
                <th rowspan="2">UNIT</th>
                <th rowspan="2">RATE</th>
                <th rowspan="2">VALUE</th>
                <th colspan="2">DISC</th>
                <th colspan="2">CGST</th>
                <th colspan="2">SGST/UTGST</th>
                <th colspan="2">IGST</th>
            </tr>
            <tr>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
            </tr>
            <?php $i=1;
            $total = 0;
            $total_discount = 0;
            $total_igst = 0;
            $total_cgst = 0;
            $total_sgst = 0;
             ?>
            @foreach($items as $item)
            @if($item['cancelledQty']!=0)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['item_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>{{$item['cancelledQty']+$item['order_qty']}}</td>
                <td>{{$item['cancelledQty']}}</td>
                <td>{{$item['unit_name']}}</td>
                <td>{{number_format((float)$item['rate'], 2, '.', '')}}</td>
                <td>{{number_format((float)($item['rate']* $item['cancelledQty']), 2, '.', '') }}</td>
                <td>{{$item['discount']}}</td>
                <?php $discount_value = ($item['rate']* $item['cancelledQty'])-(($item['rate']* $item['cancelledQty']*$item['discount'])/100);?>
                <td>{{number_format((float)(($item['rate']* $item['cancelledQty']*$item['discount'])/100), 2, '.', '')}}</td>
                <td>{{$item['cgst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['cgst'])/100), 2, '.', '')}}</td>
                <td>{{$item['sgst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['sgst'])/100), 2, '.', '')}}</td>
                <td>{{$item['igst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['igst'])/100), 2, '.', '')}}</td>
                <?php 
                $total =$total+ $item['rate']* $item['cancelledQty'];
                $total_discount = $total_discount+($item['rate']* $item['cancelledQty']*$item['discount'])/100;
                $total_igst = $total_igst+($discount_value*$item['igst'])/100;
                $total_sgst = $total_sgst+($discount_value*$item['sgst'])/100;
                $total_cgst = $total_cgst+($discount_value* $item['cancelledQty']*$item['cgst'])/100;
                ?>
            </tr>
            @endif
            @endforeach 
        
        </table>
    </div>
    <div class="row4" style="border-bottom:solid 1px black;height:170px;">
        <div class="col41">
            <div class="remarks" style="">
                <strong>Remarks/Notes </strong><br/>
                @if($final_purchase['remarks'])
                {{$final_purchase['remarks']}}
                @endif
            </div>
            <div class="adler">
                <i>For</i>
                <br/>
                <strong>ADLER HEALTHCARE PVT. LTD</strong>
            </div>
            <div class="sign" style="margin-top:15px;">
                (Authorized Signatory)<br/>
                (Software Generated Document-No Signature Required)
            </div>
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;">
            </div>
            <div class="supplier-accept" style="height:50px;margin-top:15px;">
                <b>Supplier Acceptance</b>
            </div>
            <div class="supplier-accept" style="height:50px;">
                <b>(Supplier Signature & Date)</b><br/>
                <span>I/We, hereby cancel this order.</span>
            </div>
        </div>
        <div class="col43">
            <table style="height:130px;">
                <tr>
                    <td style="width:160px">Sum of Line Value</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)$total, 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total Discount</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)$total_discount, 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Transportation & Freight Charge</td>
                    <td style="width:30px;">:<?php $freight_charge = $fn->find_freight_charge($final_purchase['rq_master_id'],$final_purchase['supplierId']); ?>{{--$final_purchase['rq_master_id']}}:{{$final_purchase['supplierId']--}}</td>
                    <td style="text-align:right;">{{number_format((float)($freight_charge), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total Net Amount</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total-$total_discount+$freight_charge), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total CGST</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total SGST/UTGST</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total IGST</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total_igst), 2, '.', '')}}</td>
                </tr>
                <!-- <tr>
                    <td style="width:130px">Transportation & Freight Charge</td>
                    <td style="width:30px;">:</td>
                    <td style="float:right;">{{$total_discount}}</td>
                </tr> -->
                
            </table>
            <table style="border-bottom:solid 1px black;width:100%;border-top:solid 1px black;width:100%;">
                <tr>
                    <th style="width:130px">GRAND TOTAL</th>
                    <th style="width:30px;">:</th>
                    <th style="text-align:right;">{{number_format((float)($total-$total_discount+$freight_charge+$total_igst+$total_sgst+$total_sgst), 2, '.', '')}}</th>
                </tr> 
            </table>
        </div>
    </div>
    <div class="row5">
        <div class="col51">
           {{-- @if($type!='cancel')
                @if(str_starts_with($final_purchase['po_number'] , 'PO') )
                Document Format - PUR/F-04-00
                @else
                Document Format - PUR/F-20-00
                @endif
            @endif --}}
        </div>
        <div class="col52">
            :{{$final_purchase['po_number']}}
        </div>
        <div class="col53">
            Page: 1 of 2
        </div>
    </div><br/>
    <div style="border-top:solid 1.5px black; margin-top:5px;font-size:10px;">
    
    </div>
    
    
     
   
</body>
</html>