<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>PI_{{$pi['firm_name']}}_{{$pi['pi_number']}}</title>
</head>
<body>
@inject('fn', 'App\Http\Controllers\Web\FGS\PIController')
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
   
 
            
    <div class="row">
        <span style="font-weight:bold;font-size: 24px; background-color: #f4f5f8; padding: 0 4px;position: absolute;">
      Material Ready for Dispatch<!--Padding is optional-->
        </span>
    </div>
    <br/> <br/> <br/> 
    <div class="row3">
         <div class="col23">
            <table style="font-weight:bold;font-size: 14px;">
                <tr>
                    <td>Reference No</td>
                    <td>: {{$pi['pi_number']}} </td>
                   
                </tr>
                
            </table>
        </div>
        <div class="col23">
            <table style="font-weight:bold;font-size: 14px;">
                <tr>
                   &nbsp &nbsp &nbsp  &nbsp<td>Reference Date</td>
                    <td>: {{$pi['pi_date']}} </td>
                   
                </tr>
                
            </table>
        </div>
       
    </div>
   <br><br>
    <div class="row3" style="height:100px;">
        <div class="col23">
            <table>
                <tr>
                    <td> <strong><font size="12px">{{$pi['firm_name']}} </font></strong>
            <p>{{$pi['billing_address']}}<br/>
            {{$pi['city']}}, {{$pi['state_name']}}
           
           </p></td>
                   
                </tr>
                
            </table>
        </div>
    </div>
    <div  style="font-size:12px; padding-top: 30px;">
     
        <span style="padding-left:0px;" >Dear Sir / Madam,</span><br>
        <span>We are pleased to inform you that the material against your following orders is ready for dispatch. 
The details for the same are as follows:</span>
    </div><br>
    <style>
        th{
            text-align:center;
        }
    </style>
    <div class="row3" >
        <table >
            <tr>
                <th rowspan="2">Your Order No.</th> 
                <th rowspan="2">
                    Your Order Date  
                </th>
                <th rowspan="2">Our Inv No.</th>
                <th rowspan="2" >Our Inv.Date</th>
                
            </tr><br>
        
            <tr style="text-align: center;">
                
                <td>{{$pi['order_number']}}</td>
                <td>{{$pi['order_date']}}</td>
                <td>{{$pi['pi_number']}}</td>
                <td>{{$pi['pi_date']}}</td>
                
            </tr>
           
        </table>
    </div><br>
    @foreach($items as $item)
           
     <?php 
        $discount_value = ($item['rate']* $item['quantity'])-(($item['rate']* $item['quantity']*$item['discount'])/100);
        $total_amount =$discount_value+(($discount_value*$item['cgst'])/100)+ (($discount_value*$item['cgst'])/100)+ (($discount_value*$item['igst'])/100);

     ?>
    @endforeach
    <div class="row3" style="font-size:12px; ">
       
        <span>Kindly arrange to deposit the payment of Rs.{{number_format((float)($total_amount), 2, '.', '')}} ( In Words Rs. NINTY SIX THOUSAND TWO 
HUNDRED AND FORTY NINE Only ) in The Federal Bank Limited (Nearest Branch) within 15 days from the 
date of receipt of this letter enabling us to dispatch your shipment immediately.</span><br>
  <span>Please ensure correct entry of our Current Account Number (CA No.15245500003334) & 
Company Name (ADLER HEALTHCARE PVT. LTD) in Pay-in-Slip.Please fax us the pay-in-slip to enable 
us to track your payment.</span><br>
  <span>Please note that we will be raising a debit note for 5% on invoice value (for late payment) if your 
payment is not received by us within 15 days from the date of receipt of this letter.</span><br>
 <span>Please note that dispatches can be made only against receipt of funds in Federal Bank Limited.</span><br>
 <span>We would be thankful for your immediate response to this communication.</span><br>    
 <span>Thanking You,</span><br>
 <span>Sincerely Yours,</span><br>
  </div><br>
  <div class="row3">  
    
  <span style="color:#1434A4; font-size: 12px;"><strong>ADLER HEALTHCARE PVT. LTD</strong></span>
            <p style="font-size: 8px;"> Plot No-A1 MIDC, Sadavali(Devrukh), <br/>
             Tal- Sangmeshwar, Dist -Ratnagiri , <br/> PIN-415804, Maharashtra, India<br/>
             Contact No: 8055136000, 8055146000<br/>
             E-Mail:adler-customer.care@adler-healthcare.com<br>
            CIN :U33125PN2020PTC195161 <br/>
           </p>  
 </div>    
   
</body>
</html>