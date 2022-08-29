<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title>Final Purchase Order Receipt</title>
</head>
<body>
    <style>
        .col1,.col3{
            float:left;
            width:28%;
            font-size:11px;
        }
        .col2{
            width:40%;
            float:left;
        }
        .attn {
            margin-top:32px;
           font-size:10px; 
        }
        .main-head{
            margin-top:50px;
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
            font-size:9px;
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
            float:left;
        }
        .remarks, .adler {
            height:50px;
        }
        .row3 table th{
            background-color:#B8B8B8;
        }
    </style>
    <?php// print_r(json_encode($items)); ?>
    <div class="row1" style="height:150px;border-bottom:solid 2px black;">
        <div class="col1">
            To<br/>
            <strong>{{$final_purchase['vendor_name']}}</strong>
            <p>{{$final_purchase['address']}}<br/>
            <?php  
            function SplitPhone($data)
            {
                $a = "";
                $arr = explode(",",ltrim(rtrim($data,']'),'['));
                $len = count($arr);
                echo trim($arr[0],' " ');
                // for($x = 0; $x < $len; $x++) {
                //     echo trim($arr[$x],' " ');
                //     if($x!=($len-1))
                //     echo ",";
                // }
                //return $a;
            }
            function SplitMail($data)
            {
                $a = "";
                $arr = explode(",",ltrim(rtrim($data,']'),'['));
                $len = count($arr);
                echo trim($arr[0],' " ');
                // for($x = 0; $x < $len; $x++) {
                //     echo trim($arr[$x]," ' ");
                //     if($x!=($len-1))
                //     echo ",";
                // }
                //return $a;
            }
            ?>
            Cell No : {{ SplitPhone($final_purchase['contact_number']) }}<br/>
            <span style="font-size:10px;  overflow-wrap: break-word;">Email:<?php SplitMail($final_purchase['email'])?><br/><span>
            GSTIN :</p>

        </div>
        <div class="col2" style="text-align:center;">
            <div class="attn">Kind Attn: {{$final_purchase['contact_person']}}</div>
            <div class="main-head">
                <h6>PURCHASE ORDER</h6>
            </div>
        </div>
        <div class="col3">
            From<br/>
            <strong>ADLER HEALTHCARE PVT. LTD</strong>
            <p> Plot No-A1 MIDC, Sadavali(Devrukh),  Tal- Sangmeshwar, Dist -Ratnagiri ,  PIN-415804, Maharashtra, India<br/>
            CIN : <br/>
            Company GSTIN :</p>
        </div>
        <div class="col4">
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
                    <td>: {{date('d-m-Y',strtotime($final_purchase['quotation_date']))}}</td>
                </tr>
                <tr>
                    <td >Currency</td>
                    <td>: {{$final_purchase['unit_name']}}</td>
                </tr>
            </table>
        </div>
        <div class="col22">
            <table style="float:right;">
                <tr>
                    <td>PO No</td>
                    <td>: {{$final_purchase['po_number']}}</td>
                </tr>
                <tr>
                    <td>PO Date</td>
                    <td>: {{date('d-m-Y',strtotime($final_purchase['po_date']))}}</td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>: {{$final_purchase['dept_name']}}</td>
                </tr>
            </table>
        </div>
        
    </div>
    <div class="row3">
        <div class="intro">
            We are pleased to place an order for the following items at the Terms and Conditions given herewith. Please sign a copy of the same and return it to us as an acceptance. 
        </div>
        <table border="1">
            <tr>
                <th rowspan="2">S.NO</th>
                <th rowspan="2">HSN CODE</th>
                <th rowspan="2">ITEM.NO</th>
                <th rowspan="2">ITEM DESCRIPTION</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2">UNIT</th>
                <th rowspan="2">RATE</th>
                <th rowspan="2">VALUE</th>
                <th colspan="2">DISC</th>
            </tr>
            <tr>
                <th>%</th>
                <th>Value</th>
            </tr>
            <?php $i=1;
            $total = 0;
            $total_discount = 0;
             ?>
            @foreach($items as $item)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['item_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>{{$item['order_qty']}}</td>
                <td>{{$item['unit_name']}}</td>
                <td>{{number_format((float)$item['rate'], 2, '.', '')}}</td>
                <td>{{number_format((float)($item['rate']* $item['order_qty']), 2, '.', '') }}</td>
                <td>{{$item['discount']}}</td>
                <td>{{number_format((float)(($item['rate']* $item['order_qty']*$item['discount'])/100), 2, '.', '')}}</td>
                <?php 
                $total =$total+ $item['rate']* $item['order_qty'];
                $total_discount = $total_discount+($item['rate']* $item['order_qty']*$item['discount'])/100;
                ?>
            </tr>
            @endforeach
        
        </table>
    </div>
    <div class="row4" style="border-bottom:solid 1px black;height:170px;">
        <div class="col41">
            <div class="remarks">
                <strong>Remarks/Notes </strong>
            </div>
            <div class="adler">
                <i>For</i>
                <br/>
                <strong>ADLER HEALTHCARE PVT. LTD</strong>
            </div>
            <div class="sign">
                (Authorized Signatory)<br/>
                (Software Generated Document-No Signature Required)
            </div>
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;">
            </div>
            <div class="supplier-accept" style="height:50px;">
                <b>Supplier Acceptance</b>
            </div>
            <div class="supplier-accept" style="height:50px;">
                <b>(Supplier Signature & Date)</b><br/>
                <span>I/We, hereby accept this order with the term mentioned herein.</span>
            </div>
        </div>
        <div class="col43">
            <table style="height:130px;">
                <tr>
                    <td style="width:130px">Sum of Line Value</td>
                    <td style="width:30px;">:</td>
                    <td style="">{{number_format((float)$total, 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:130px">Total Discount</td>
                    <td style="width:30px;">:</td>
                    <td style="float:right;">{{number_format((float)$total_discount, 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:130px">Total Net Amount</td>
                    <td style="width:30px;">:</td>
                    <td style="float:right;">{{number_format((float)($total-$total_discount), 2, '.', '')}}</td>
                </tr>
                <!-- <tr>
                    <td style="width:130px">Total Net Amount</td>
                    <td style="width:30px;">:</td>
                    <td style="float:right;">{{$total_discount}}</td>
                </tr> -->
                
            </table>
            <table style="border-bottom:solid 1px black;width:100%;border-top:solid 1px black;width:100%;">
                <tr>
                    <th style="width:130px">GRAND TOTAL</th>
                    <th style="width:30px;">:</th>
                    <th style="float:right;">{{number_format((float)($total-$total_discount), 2, '.', '')}}</th>
                </tr> 
            </table>
        </div>
    </div>
    <div class="row5">

    </div>

   
</body>
</html>