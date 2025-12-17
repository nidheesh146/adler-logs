
<!doctype html>
<html>
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\QuotationController')
@inject('supplierfn', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
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
        color:black;
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
          font-size: 12px;
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
  <body style="color:black;">
@inject('fn', 'App\Http\Controllers\Web\FGS\OEFController')
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
            width:25%;
        }
        .col22{
            margin-top:-25px;
            float:left;
            width:25%;
        }
        .col23{
          
            float:left;
            width:25%;
        }
        .col24{
            margin-top:-25px;
            float:right;
            width:25%;
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
        .col6{
            width: 50%;
        }
    </style>
   
   <?php $oef = $fn->getOEFData($mailData->oef_id); ?>
    <?php $items= $fn->getOEFItemsData($mailData->oef_id); ?>
            
    <div class="row">
        <span style="font-weight:bold;font-size: 20px; background-color: #f4f5f8; padding: 0 4px;position: absolute;">
       Order Acknowledgement<!--Padding is optional-->
        </span>
    </div>
    <br/> <br/> <br/> 
    <div class="row">
        
            <table style="font-weight:bold;font-size: 15px;width:25%;">
                <tr>
                    <td style="font-weight:bold;font-size: 15px;">Reference No</td>
                    <td style="font-weight:bold;font-size: 15px;">: {{$oef['oef_number']}} </td>
                   
                </tr>
                
            </table>
       
    </div>
   <br>
    <div class="row3" style="">
        <div class="col23" style="display:flex;">
          <table style="width:98%;">
            <tr>
              <td>
                <table style="">
                    <tr>
                        <td><span size="14px" style="font-weight:bold;font-size:16px;">{{$oef['firm_name']}} </span>
                        <p style="font-size:13px;"><?php echo nl2br(isset($oef['billing_address']) ? $oef['billing_address'] : $oef['dummy_billing_address']); ?><br/>
                          {{$oef['city']}}, {{$oef['state_name']}}<br/>
                          Email: {{$oef['email']}}
                          </p>
                        </td>
                    </tr>
                </table>
              </td>
              <td>
                <table style="float:left;">
                </table>
              </td>
              <td>
                <table style="float:right;height:100px;flex: 50%;font-size:15px;">
                    <tr style="font-size:15px;">
                        <td style="font-weight:bold;font-size:15px;">Order No.</td>
                        <td style="font-weight:bold;font-size:15px;">: {{$oef['order_number']}} </td>
                    </tr>
                    <tr style="font-size:15px;">
                        <td style="font-weight:bold;font-size:15px;"> Order Date</td>
                        <td style="font-weight:bold;font-size:15px;">: {{date('d-m-Y', strtotime($oef['order_date']))}}</td>
                    </tr>
                    <tr style="font-size:15px;">
                        <td style="font-weight:bold;font-size:15px;">Our Ref. No</td>
                        <td style="font-weight:bold;font-size:15px;">: {{$oef['oef_number']}}</td>
                    </tr>
                    <tr style="font-size:15px;">
                        <td style="font-weight:bold;font-size:15px;"> Doc Ref. Date</td>
                        <td style="font-weight:bold;font-size:15px;">: {{date('d-m-Y', strtotime($oef['oef_date']))}}</td>
                    </tr>
                    
                </table>
              </td>
            </tr>
          </table>
        </div>
        <div class="col23">
            
        </div>
     
    </div>
    <div  style="font-size:16px; padding-top: 30px;">
        <span></span>
        <span style="padding-left:0px;" >Dear Sir / Madam,</span><br>
        <span>This is to acknowledge that we are in receipt of your order having following details:</span>
    </div><br>
    <style>
        th{
            text-align:center;
        }
    </style>
    <div class="row3"  style="border-bottom:solid 0.5px black;">
        <table border="1" cellpadding="0" cellspacing="0" width="200px" style="border-collapse:collapse;">
            <tr style="font-weight:bold;background-color:#BED7EF;">
                <th rowspan="2">SL NO</th>
                <th rowspan="2">ITEM NO.</th>
                <th rowspan="2" width='40%'>ITEM DESCRIPTION</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2">UNIT</th>
                <th rowspan="2">RATE</th>
                <th colspan="2">DISC</th>
                <th rowspan="2">TAXABLE VALUE</th>
                @if($oef['zone_name']!='Export')
                @if($oef['state_name']=='Maharashtra')
                <th colspan="2">CGST</th>
                <th colspan="2">SGST/UTGST</th>
                @else
                <th colspan="2">IGST</th>
                @endif
                @endif
                <th rowspan="2">TOTAL AMOUNT</th>
            </tr>
            <tr style="font-weight:bold;font-size: 16px;background-color:#BED7EF;">
                <th>%</th>
                <th>Value</th>
                @if($oef['zone_name']!='Export')
                  @if($oef['state_name']=='Maharashtra')
                  <th>%</th>
                  <th>Value</th>
                  <th>%</th>
                  <th>Value</th>
                  @else
                  <th>%</th>
                  <th>Value</th>
                  @endif
                @endif
            </tr>
            <?php $i=1;
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
             ?>
            @foreach($items as $item)
            <tr style="text-align: center;font-size:16px;">
                <td style="font-size:14px;">{{$i++}}</td>
                <td style="font-size:14px;">{{$item['sku_code']}}</td>
                <td style="text-align: left;font-size:14px;">{{$item['discription']}}</td>
                <td style="font-size:14px;">{{$item['quantity']}}</td>
                <td style="font-size:14px;">Nos</td>
                <td style="text-align:right;font-size:14px;">{{number_format((float)$item['rate'], 2, '.', '')}}</td>
                <td style="font-size:14px;">{{$item['discount']}}</td>
                <?php $discount_value = ($item['rate']* $item['quantity'])-(($item['rate']* $item['quantity']*$item['discount'])/100);?>
                <td style="text-align:right;font-size:14px;">{{number_format((float)(($item['rate']* $item['quantity']*$item['discount'])/100), 2, '.', '')}}</td>
                <td style="text-align:right;font-size:14px;">{{number_format((float)($discount_value), 2, '.', '')}}</td>
                @if($oef['zone_name']!='Export')
                  @if($oef['state_name']=='Maharashtra')
                  <td style="font-size:14px;">{{$item['cgst']}}</td>
                  <td style="text-align:right;font-size:14px;">{{number_format((float)(($discount_value*$item['cgst'])/100), 2, '.', '')}}</td>
                  <td style="font-size:14px;">{{$item['sgst']}}</td>
                  <td style="text-align:right;font-size:14px;">{{number_format((float)(($discount_value*$item['sgst'])/100), 2, '.', '')}}</td>
                  @else
                  <td style="font-size:14px;">{{$item['igst']}}</td>
                  <td style="text-align:right;font-size:14px;">{{number_format((float)(($discount_value*$item['igst'])/100), 2, '.', '')}}</td>
                  @endif
                @endif
                <?php $total_amount =$discount_value+(($discount_value*$item['cgst'])/100)+ (($discount_value*$item['cgst'])/100)+ (($discount_value*$item['igst'])/100);  ?>
                <td style="text-align:right;font-size:14px;"><b>{{number_format((float)($total_amount), 2, '.', '')}}</b></td>
                <?php 
                $total =$total+ $item['rate']* $item['quantity'];
                $total_discount = $total_discount+($item['rate']* $item['quantity']*$item['discount'])/100;
                $total_igst = $total_igst+($discount_value*$item['igst'])/100; 
                $total_sgst = $total_sgst+($discount_value*$item['sgst'])/100;
                $total_cgst = $total_cgst+($discount_value* $item['quantity']*$item['cgst'])/100;
                ?>
                 <?php 
                 $qsum = $qsum+$item['quantity'];
                 $rsum = $rsum+$item['rate'];
                 $tsum = $tsum+$discount_value;
                 $isum = $isum+$total_igst;
                 $totalsum = $totalsum+$total_amount;
                 ?>
            </tr>
            @endforeach
            <tr style="text-align: center;background-color:#BED7EF;font-size:16px;">
                <td></td>
                <td></td>
                <td></td>
                <td><b>{{  $qsum }}</b></td>
                <td></td>
                <td style="text-align:right;"><b> {{number_format((float)($rsum), 2, '.', '')}}</b></td>
                <td></td>
                <td></td>
                <td style="text-align:right;"><b>{{number_format((float)($tsum), 2, '.', '')}}</b></td>
                @if($oef['zone_name']!='Export')
                @if($oef['state_name']=='Maharashtra')
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @else
                <td></td>
                <td></td>
                @endif
                @endif
                <td style="text-align:right;"><b>{{number_format((float)($totalsum), 2, '.', '')}}</b></td>
            </tr>       
        
        </table><br>
    </div>
    <br/>
    <div class="row4" style="height:120px;display:flex;font-size:16px;">
      <table border="0" style="width:98%;">
        <tr>
          <td style="width:60%;font-size:16px;" rowspan="4"><strong>Remarks/Notes </strong><br/>
              @if($oef['remarks'])
                <?php echo nl2br($oef['remarks']); ?>
              @endif
          </td>
          <td style="width:160px;font-size:16px;">Sum of Taxable Value</td>
          <td style="width:160px;font-size:16px;">:</td>
          <td style="text-align:right;font-size:16px;">{{number_format((float)($total-$total_discount), 2, '.', '')}}</td>
        </tr>
        <tr>
          <td style="width:160px;font-size:16px;">Sum of CGST</td>
          <td style="width:160px;font-size:16px;">:</td>
          <td style="text-align:right;font-size:16px;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
        </tr>
        <tr>
          <td style="width:160px;font-size:16px;">Sum of SGST/UTGST</td>
          <td style="width:160px;font-size:16px;">:</td>
          <td style="text-align:right;font-size:16px;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
        </tr>
        <tr>
          <td style="width:160px;font-size:16px;">Sum of IGST</td>
          <td style="width:160px;font-size:16px;">:</td>
          <td style="text-align:right;font-size:16px;">{{number_format((float)($total_igst), 2, '.', '')}}</td>
        </tr>
        <tr>
          <td style="width:160px;font-size:16px;"><strong>Amount in Words</strong></td>
          <td style="width:160px;font-size:16px;">Rounf Off</td>
          <td style="width:160px;font-size:16px;">:</td>
                     <?php 
                    $t = number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', '');
                    $round = round($t);
                    $roundoff = number_format((float)($round-$t), 2, '.', '');
                    ?>
          <td style="text-align:right;font-size:16px;">{{ $roundoff }}</td>
        </tr>
        <tr>
          <td style="width:160px;font-size:16px;"><?php echo( $fn->getIndianCurrencyInt(round(number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', '')))) ?></td>
          <th style="width:148px; text-align: left;">GRAND TOTAL</th>
                     <?php
                     $grand = 0;
                     $grandt = 0;
                    $grand = round(number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', ''))
                   
                    ?>
          <td style="width:160px;font-size:16px;">:</td>
          <th class="grand_total_value" style="text-align:right;">{{ $grand }}.00 </th>
        </tr>
      </table>
  {{--<div class="col41" style="width:40%;">
            <div class="remarks" style="">
                <strong>Remarks/Notes </strong><br/>
                @if($oef['remarks'])
                <?php echo nl2br($oef['remarks']); ?>
                @endif
            </div><br/>
            <div class="valuewords">
                <strong>Amount in Words</strong><br/>
                <?php echo( $fn->getIndianCurrencyInt(round(number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', '')))) ?> 
                
                <span class="value_in_words"></span>
            </div>       
        </div>
        <div class="col41" style="width:40%;">
        </div>
        <div class="col42" style="width:20%;">
            <div class="" style="height:50px;">
            </div>
        </div>
        <div class="col43" style="width:40%;font-size:16px;text-align:right;">
            <table border="1" cellpadding="0" cellspacing="0" width="200px" style="border-collapse:collapse;height:100px;font-size:16px;">
                <tr style="font-size:16px;">
                    <td style="width:160px;font-size:16px;">Sum of Taxable Value</td>
                    <td style="text-align:right;font-size:16px;">{{number_format((float)($total-$total_discount), 2, '.', '')}}</td>
                </tr>
                @if($oef['zone_name']!='Export')
                  @if($oef['state_name']=='Maharashtra')
                  <tr style="font-size:16px;">
                      <td style="width:160px;font-size:16px;">Sum of CGST</td>
                      <td style="text-align:right;font-size:16px;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
                  </tr>
                  <tr style="font-size:16px;">
                      <td style="width:160px;font-size:16px;">Sum of SGST/UTGST</td>
                      <td style="text-align:right;font-size:16px;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
                  </tr>
                  @else
                  <tr style="font-size:16px;">
                      <td style="width:160px;font-size:16px;">Sum of IGST</td>
                      <td style="text-align:right;font-size:16px;">{{number_format((float)($total_igst), 2, '.', '')}}</td>
                  </tr>
                  @endif
                @endif
                <tr style="font-size:16px;">
                    <td style="width:160px;font-size:16px;">Rounf Off</td>
                     <?php 
                    $t = number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', '');
                    $round = round($t);
                    $roundoff = number_format((float)($round-$t), 2, '.', '');
                    ?>
                    <td style="text-align:right;font-size:16px;">{{ $roundoff }}</td>
                </tr>
                <tr style="border-top:1px solid black;">
                   <th style="width:148px; text-align: left;">GRAND TOTAL</th>
                     <?php
                     $grand = 0;
                     $grandt = 0;
                    $grand = round(number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', ''))
                   
                    ?>
                    <th class="grand_total_value" style="text-align:right;">{{ $grand }}.00 </th>
                </tr> 
                
            </table>
            
        </div>--}}
    </div>
   <br>
   {{--<div class="col41" style="font-size:16px;">
              <div class="remarks" style="">
                  <strong>Remarks/Notes </strong><br/>
                  @if($oef['remarks'])
                  <?php echo nl2br($oef['remarks']); ?>
                  @endif
              </div><br/>
              <div class="valuewords">
                  <strong>Amount in Words</strong><br/>
                  <?php echo( $fn->getIndianCurrencyInt(round(number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', '')))) ?> 
                  
                  <span class="value_in_words"></span>
              </div>       
    </div>--}}
   <br><br><br><br>
    <div class="row3" style="border-bottom:solid 0.5px black;font-size:16px; ">
       
        <span><p style="font-size:16px;">We also request you to make a note of the Order Acceptance Number and Date mentioned above. 
Kindly mention this reference number and date in all future correspondence you have with us 
concerning this order.</p></span><br>
  <span><p style="font-size:16px;">Kindly note that this order has been booked only for items which were clearly specified (with correct 
numbers). You may receive a separate query from us relating to items from this order for which the 
specifications were not clear or complete. When you receive this query, kindly send us a fresh order with 
complete and clear specifications (code numbers) for items mentioned in our query letter.</p></span><br>
  <span>
    <p style="font-size:16px;">
    {{--Please Note : This is a computer generated e-mail intimating the booking of your order. If you have query 
related to your orders, please communicate with our Commercial/Logistics department.--}}
This is computer generated e-mail. Please do not reply to this mail. If you have any query related to your order/order acknowledgement, please write to orders@adler-healthcare.com or chandrashekhar.purohit@adler-healthcare.com 

Please also note that, order once booked, can not be cancelled.
    </p>
  </span><br><br>
    </div><br>
  <div class="row3">  
    
    <span style="color:#1434A4; font-size: 14px;"><strong>ADLER HEALTHCARE PVT. LTD</strong></span>
            <p style="font-size: 14px;"> Plot No-A1 MIDC, Sadavali(Devrukh), <br/>
             Tal- Sangmeshwar, Dist -Ratnagiri , <br/> PIN-415804, Maharashtra, India<br/>
             Contact No: 8055136000, 8055146000<br/>
             E-Mail:adler-customer.care@adler-healthcare.com<br>
            CIN :U33125PN2020PTC195161 <br/>
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
