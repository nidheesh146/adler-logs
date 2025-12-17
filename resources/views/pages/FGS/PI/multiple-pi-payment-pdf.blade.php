<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <title>PI_{{$mpi['firm_name']}}_{{$mpi['merged_pi_name']}}</title>
</head>

<body>
    @inject('fn', 'App\Http\Controllers\Web\FGS\PIController')
    <style>
        .col1,
        .col3 {
            float: left;
            width: 23%;
            font-size: 11px;
        }

        .col2 {
            width: 45%;
            float: left;
        }

        .attn {
            margin-top: 32px;
            font-weight: bold;
            font-size: 10px;
            color: #1434A4;
        }

        .main-head {
            margin-top: 10px;
            font-size: 24px;
            font-weight: bold;
            font-style: Italic;
        }

        .col21 {
            margin-top: -25px;
            float: left;
            width: 25%;
        }

        .col22 {
            margin-top: -25px;
            float: left;
            width: 25%;
        }

        .col23 {

            float: left;
            width: 25%;
        }

        .col24 {
            margin-top: -25px;
            float: right;
            width: 25%;
        }

        .row2 {
            display: block;
            font-size: 11px;
            height: 35px;
            border-bottom: solid 0.5px black;
        }

        .row3,
        .row4 {
            display: block;
        }

        .intro {
            font-size: 11px;
            font-style: italic;
            padding: 10px;
        }

        .row3 table {
            width: 100%;
            font-size: 10px;
        }

        .row4 {
            font-size: 10px;
        }

        .col41,
        .col42 {
            width: 35%;
            float: left;
        }

        .col43 {
            font-size: 11px;
            float: right;
        }

        .remarks,
        .adler {
            height: 50px;
        }

        .col51,
        .col52 {
            font-size: 11px;
            width: 33%;
            float: left;
        }

        .col52 {
            text-align: center;
        }

        .col53 {
            font-size: 11px;
            text-align: right;
            float: right;
        }

        .col6 {
            width: 50%;
        }
    </style>

    <?php $mpi_items= $fn->getMPIItemsData($mpi->id); ?>

    <div class="row">
        <span style="font-weight:bold;font-size: 24px; background-color: #f4f5f8; padding: 0 4px;position: absolute;">
            Material Ready for Dispatch<!--Padding is optional-->
        </span>
    </div>
    <br /> <br /> <br />
    <div class="row3">
        <div class="col23">
            <table style="font-weight:bold;">
                <tr>
                    <td>
                    To <br/>
                         <strong>
                            <font size="12px">{{$mpi['firm_name']}} </font>
                        </strong>
                        <p>{{$mpi['billing_address']}}<br />
                            {{$mpi['city']}}, {{$mpi['state_name']}}

                        </p>
                    </td>
                </tr>

            </table>
        </div>
        <div class="col23">
        </div>
        <div class="col23">
            <table style="font-weight:bold;font-size: 12px;float:right;text-align:right;">
                <tr>
                    <td>Reference No</td>
                    <td>: {{$mpi['merged_pi_name']}} </td>

                </tr>
                <tr>
                    &nbsp &nbsp &nbsp &nbsp<td>Reference Date</td>
                    <td>: {{date('d-m-Y', strtotime($mpi['created_at']))}}</td>

                </tr>

            </table>
        </div>

    </div>
    <br><br>
    {{--<div class="row3" style="height:100px;">
        <div class="col23">
            <table>
                <tr>
                    <td>
                        To <br/> 
                        <strong>
                            <font size="12px">{{$mpi['firm_name']}} </font>
                        </strong>
                        <p>{{$mpi['billing_address']}}<br />
                            {{$mpi['city']}}, {{$mpi['state_name']}}

                        </p>
                    </td>

                </tr>

            </table>
        </div>
    </div>--}}
    <div style="font-size:12px; padding-top: 30px;">
    <br/><br/>
        <span style="padding-left:0px;">Dear Sir / Madam,</span><br>
        <span>We are pleased to inform you that the material against your following order/s is ready for dispatch. The details for the same are as follows:</span>
    </div><br>
    <style>
        th {
            text-align: center;
        }
    </style>
    
    <div class="row3">
        <table border="1" cellpadding="0" cellspacing="0" width="200px" style="border-collapse:collapse;>
            <thead>
            <tr style="background-color:#BED7EF;">
                <th>Your Order No.</th>
                <th>
                    Your Order Date
                </th>
                <th>PI No.</th>
                <th>PI Date</th>
                <th>PI Value</th>
            </tr>
            </thead>
            <tbody>
            <?php 
                $grand_total = 0;
            ?>
            @foreach($mpi_items as $pi)
                <tr style="text-align: center;">
                <?php $items = $fn->getPIItems($pi['pi_id']); 
                $total = 0;
                $total_discount = 0;
                $total_igst = 0;
                $total_cgst = 0;
                $total_sgst = 0;
                $qsum = 0;
                $rsum = 0;
                $tsum = 0;
                $isum = 0;
                $totalsum = 0;
                $tot = 0;
               // print_r(json_encode($items));exit;
                foreach($items as $item)
                {
                    
                // $discount_value = ($item['rate'] * $item['remaining_qty_after_cancel']) - (($item['rate'] * $item['remaining_qty_after_cancel'] * $item['discount']) / 100);
                // $total_amount = $discount_value + (($discount_value * $item['cgst']) / 100) + (($discount_value * $item['cgst']) / 100) + (($discount_value * $item['igst']) / 100);
                $discount_value = ($item['rate']* $item['remaining_qty_after_cancel'])-(($item['rate']* $item['remaining_qty_after_cancel']*$item['discount'])/100);
                $total_amount =$discount_value+(($discount_value*$item['cgst'])/100)+ (($discount_value*$item['cgst'])/100)+ (($discount_value*$item['igst'])/100);
                $total =$total+ $item['rate']* $item['remaining_qty_after_cancel'];
                $total_discount = $total_discount+($item['rate']* $item['remaining_qty_after_cancel']*$item['discount'])/100;
                $total_igst = $total_igst+($discount_value*$item['igst'])/100;
                $total_sgst = $total_sgst+($discount_value*$item['sgst'])/100;
                $total_cgst = $total_cgst+($discount_value*$item['cgst'])/100;
                $totalsum = $totalsum+$total_amount;

               // $discount_value = ($item['rate'] * $item['quantity']) - (($item['rate'] * $item['quantity'] * $item['discount']) / 100);
                $total_amount = $discount_value + (($discount_value * $item['cgst']) / 100) + (($discount_value * $item['cgst']) / 100) + (($discount_value * $item['igst']) / 100);
                
                $tot = $tot+$total_amount;
            }?> 
               <?php  $grand_total =  $grand_total+ $tot; ?>
               <?php $OEFInfo = $fn->getOEFInfo($pi->pi_id); ?>
                    <td>@foreach($OEFInfo as $item)
                            {{ $item['order_number'] }}
                        @endforeach
                    </td>
                    <td>
                      <?php $OEFInfo = $fn->getOEFInfo($pi->pi_id); ?>
                        @foreach($OEFInfo as $item)
                            {{ date('d-m-Y', strtotime($item['order_date'])) }}
                        @endforeach
                    </td>
                    <td>{{$pi['pi_number']}}</td>
                    <td>{{$pi['pi_date']}}</td>
                    <td>{{(number_format(round($tot), 2, '.', ''))}}</td>
                </tr>
                    
            @endforeach
                    
            <tr style="text-align: center;">
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;"><b>Total</b>  </td>
                <td><b>:  {{number_format(round($grand_total), 2, '.', '')}}</b></td>
            </tr>
                
        </table>
    </div><br>

    <div class="row3" style="font-size:12px; ">
        <span>
        You are requested to deposit the payment of Rs. {{round(number_format((float)($grand_total), 2, '.', ''))}} (In Words:  Rs. <?php echo( $fn->getIndianCurrencyInt(round(number_format((float)($grand_total), 2, '.', '')))) ?>  Only ) in our bank account, which details are given below and confirm to us by return mail to enabling us to dispatch your shipment immediately.
        </span>
        <br><br>
        <b>Company's Bank Details:-</b><br>
        Bank Name:- The Federal Bank Limited<br>
        Branch:- Kanjikode (Kl), Palakkad<br>
        Account No.: 15245500003334<br>
        IFSC Code: FDRL0001524<br>

        <br><br>
        <span>
            <p>
            Please note that dispatches can be made only against receipt of funds in our above said back account.
            We would be thankful for your immediate response to this communication.
            </p>
        </span>
        <span>Thanking You,</span><br>
        <span>Sincerely Yours,</span><br>


        
    </div><br>
    <div class="row3">

        <span style="color:#1434A4; font-size: 12px;"><strong>ADLER HEALTHCARE PVT. LTD</strong></span>
        <p style="font-size: 8px;"> Plot No-A1 MIDC, Sadavali(Devrukh), <br />
            Tal- Sangmeshwar, Dist -Ratnagiri , <br /> PIN-415804, Maharashtra, India<br />
            Contact No: 8055136000, 8055146000<br />
            E-Mail:adler-customer.care@adler-healthcare.com<br>
            CIN :U33125PN2020PTC195161 <br />
        </p>
    </div>

</body>

</html>