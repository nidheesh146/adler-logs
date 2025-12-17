
<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Simple Transactional Email</title>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      
      /*All the styling goes here*/
      
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; 
      }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; 
      }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; 
      }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%; 
      }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px; 
      }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        margin: 0 auto;
        max-width: 580px;
        padding: 10px; 
      }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%; 
      }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; 
      }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

      .footer {
        clear: both;
        margin-top: 10px;
        text-align: center;
        width: 100%; 
      }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center; 
      }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 30px; 
      }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; 
      }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 15px; 
      }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; 
      }

      a {
        color: #3498db;
        text-decoration: underline; 
      }

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; 
      }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center; 
      }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; 
      }

      .btn-primary table td {
        background-color: #3498db; 
      }

      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; 
      }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; 
      }

      .first {
        margin-top: 0; 
      }

      .align-center {
        text-align: center; 
      }

      .align-right {
        text-align: right; 
      }

      .align-left {
        text-align: left; 
      }

      .clear {
        clear: both; 
      }

      .mt0 {
        margin-top: 0; 
      }

      .mb0 {
        margin-bottom: 0; 
      }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; 
      }

      .powered-by a {
        text-decoration: none; 
      }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        margin: 20px 0; 
      }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table.body h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; 
        }
        table.body p,
        table.body ul,
        table.body ol,
        table.body td,
        table.body span,
        table.body a {
          font-size: 16px !important; 
        }
        table.body .wrapper,
        table.body .article {
          padding: 10px !important; 
        }
        table.body .content {
          padding: 0 !important; 
        }
        table.body .container {
          padding: 0 !important;
          width: 100% !important; 
        }
        table.body .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; 
        }
        table.body .btn table {
          width: 100% !important; 
        }
        table.body .btn a {
          width: 100% !important; 
        }
        table.body .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; 
        }
      }

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; 
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; 
        }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; 
        }
        #MessageViewBody a {
          color: inherit;
          text-decoration: none;
          font-size: inherit;
          font-family: inherit;
          font-weight: inherit;
          line-height: inherit;
        }
        .btn-primary table td:hover {
          background-color: #34495e !important; 
        }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; 
        } 
      }

    </style>
  </head>
  <body style="font-color:black;">
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
            width: 30%;
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


    <?php $mpi = $fn->getMPIData($mailData->mpi_id); ?>
    <?php $mpi_items= $fn->getMPIItemsData($mailData->mpi_id); ?>
    <div class="row">
        <span style="font-weight:bold;font-size: 24px; background-color: #f4f5f8; padding: 0 4px;position: absolute;">
            Material Ready for Dispatch<!--Padding is optional-->
        </span>
    </div>
    <br /> <br /> <br />
    <div class="row3" style="display:flex;">
        <div class="col23">
            <table style="font-weight:bold;width:40%;">
                <tr>
                    <td>
                    To <br/>
                        <span style="font-size:12px;font-weight:bold;">{{$mpi->firm_name}} </span>
                        <p>{{$mpi->billing_address}}<br />
                            {{$mpi->city}}, {{$mpi->state_name}}

                        </p>
                    </td>
                </tr>

            </table>
        </div>
        <div class="col23" style="width:10%;">
        </div>
        <div class="col23" style="top:200px;width:40%;">
            <table style="font-weight:bold;font-size: 12px;float:right;text-align:right;">
                <tr>
                    <td>Reference No</td>
                    <td>:{{$mpi->merged_pi_name}}</td>

                </tr>
                <tr>
                    <td>Reference Date</td>
                    <td>:{{date('d-m-Y', strtotime($mpi->created_at))}}</td>

                </tr>

            </table>
        </div>

    </div>
    <br>
    
    <div style="font-size:12px; padding-top: 0px;">
    <br/><br/>
        <span style="padding-left:0px;">Dear Sir / Madam,</span><br>
        <span>We are pleased to inform you that the material against your following order/s is ready for dispatch. The details for the same are as follows:</span>
    </div><br>
    </div><br>
    <style>
        th {
            text-align: center;
        }
    </style>
     
    <div class="row3">
    <table border="1" cellpadding="0" cellspacing="0" width="200px" style="border-collapse:collapse;">
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
            @foreach($mpi_items as $mpi_item)
                <tr style="text-align: center;">
                <?php $pi_items = $fn->getPIItems($mpi_item->pi_id); 
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
              
                foreach($pi_items as $item)
                {
                    
                    $discount_value = ($item['rate']* $item['remaining_qty_after_cancel'])-(($item['rate']* $item['remaining_qty_after_cancel']*$item['discount'])/100);
                    $total_amount =$discount_value+(($discount_value*$item['cgst'])/100)+ (($discount_value*$item['cgst'])/100)+ (($discount_value*$item['igst'])/100);
                    $total =$total+ $item['rate']* $item['remaining_qty_after_cancel'];
                    $total_discount = $total_discount+($item['rate']* $item['remaining_qty_after_cancel']*$item['discount'])/100;
                    $total_igst = $total_igst+($discount_value*$item['igst'])/100;
                    $total_sgst = $total_sgst+($discount_value*$item['sgst'])/100;
                    $total_cgst = $total_cgst+($discount_value*$item['cgst'])/100;
                    $totalsum = $totalsum+$total_amount;
    
                    $discount_value = ($item['rate'] * $item['quantity']) - (($item['rate'] * $item['quantity'] * $item['discount']) / 100);
                    $total_amount = $discount_value + (($discount_value * $item['cgst']) / 100) + (($discount_value * $item['cgst']) / 100) + (($discount_value * $item['igst']) / 100);
                    
                    $tot = $tot+round(number_format($total_amount, 2, '.', ''));
            }?> 
               <?php  $grand_total =  $grand_total+ $tot; ?>
                   {{-- <td>{{$mpi_item['order_number']}}</td>
                    <td>{{date('d-m-Y', strtotime($mpi_item->order_date))}}</td>
                    <td>{{$mpi_item['pi_number']}}</td>
                    <td>{{date('d-m-Y', strtotime($mpi_item->pi_date))}}</td>
                    <td>{{round(number_format($tot, 2, '.', ''))}}.00</td> --}}

                    <?php $OEFInfo = $fn->getOEFInfo($mpi_item->pi_id); ?>
                    <td>@foreach($OEFInfo as $item)
                            {{ $item['order_number'] }}
                        @endforeach
                    </td>
                    <td>
                      <?php $OEFInfo = $fn->getOEFInfo($mpi_item->pi_id); ?>
                        @foreach($OEFInfo as $item)
                            {{ date('d-m-Y', strtotime($item['order_date'])) }}
                        @endforeach
                    </td>
                    <td>{{$mpi_item['pi_number']}}</td>
                    <td>{{date('d-m-Y', strtotime($mpi_item->pi_date))}}</td>
                    <td>{{round(number_format($tot, 2, '.', ''))}}.00</td>
                </tr>
                    
            @endforeach
                    
            <tr style="text-align: center;background-color:#BED7EF;">
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;"><b>Total</b>  </td>
                <td><b>{{round(number_format($grand_total, 2, '.', ''))}}.00</b></td>
            </tr>
                
        </table>
    </div><br>

    <div class="row3" style="font-size:12px; ">

    <span>
        You are requested to deposit the payment of Rs. {{(round(number_format((float)($grand_total), 2, '.', '')))}}.00 (In Words:  Rs. <?php echo( $fn->getIndianCurrencyInt((round(number_format((float)($grand_total), 2, '.', ''))))) ?>  Only ) in our bank account, which details are given below and confirm to us by return mail to enable us to dispatch your shipment immediately.
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
            Please note that dispatches can be made only against receipt of funds in our above said bank account.
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

    <div class="footer">
              <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="content-block">
                    {{-- <span class="apple-link">Company Inc, 3 Abbey Road, San Francisco CA 94102</span>
                    <br> Don't like these emails? <a href="http://i.imgur.com/CScmqnj.gif">Unsubscribe</a>. --}}
                  </td>
                </tr>
                <tr>
                  <td class="content-block powered-by">
				               Powered by {{config('app.title')}} 
                  </td>
                </tr>
              </table>
            </div>

</body>
</html>
