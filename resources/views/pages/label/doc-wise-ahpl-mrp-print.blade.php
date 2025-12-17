<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>SNN_MRP_LABEL</title>
</head>
@inject('fn', 'App\Http\Controllers\Web\FGS\OEFController')
<body>
    <style>
        .col1,.col3{
            float:left;
            width:33%;
            font-size:11px;
        }
        .col2{
            width:28%;
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
            margin-left:150px;
            margin-top:-25px;
            float:left;
            width:25%;
        }
        .col24{
            margin-left:180px;
            margin-top:-25px;
            float:left;
            width:25%;
        }
        
         .row2{
            display:block;
            font-size:11px;
            height:90px;
            /* border-bottom:solid 0.5px black; */
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
            border-collapse: collapse;
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
        .col51{
            width:80%;
            float:left;
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
        .smalltext{
        font-size:8px;
    }
    .column {
  float: left;
  width: 33.33%;
}

/* Stops the float property from affecting content after the columns */
.columns:after {
  content: "";
  display: table;
  clear: both;
}
    </style>
   
   <div class="row label-div columns" id="label-div">
                <?php
                    $label_per_page =12;
                    $page_count = (int)($total_print_count/12);
                    $remaining = $total_print_count%12;
                 ?>
                <div class="page-container" style="margin-top:0.2cm;width:21.1cm;height:29.3cm;display:flex;"> 
                @foreach($print_item as $printItem)
                @for($k=0;$k<$printItem['print_count'];$k++)
                <div class=" label-container" style="flex:50%; width:10.1cm;float: left;height:5cm;margin:2px;padding:2px;font-size:14px; margin-left:10px;margin-bottom:6px; line-height:130%;">
                    <div class="subcolumn" style="float:left;width:95%;">
                        <div class="address" style="font-weight:bold;">
                            <span style="font-size:14px;">Mfd. and Mktd by. : ADLER HEALTHCARE PVT. LTD.</span><br/>
                            Plot No. A-1, MIDC Sadavali, Tal. Sangameshwar<br/>
                            Dist. Ratnagiri  PIN-415804, Maharashtra, India 
                        </div>
                        <strong>For Product Feedback, Contact on:</strong>
                        <div><span style="color:blue;font-size:11px;">adler-customer.care@adler-healthcare.com</span>&nbsp;|<span style="font-size:12px;">Tel: +91 8055136000&nbsp;</span><br/>
                            <span style="font-size:14px;">{{$printItem['item']->drug_license_number}}</span><br/>
                            <strong>Code No: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$printItem['item']->sku_code}}</strong><br/>
                            <strong>MRP : Rs.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$printItem['item']->mrp}} /-&nbsp;&nbsp;(Incl. of all taxes/pc) </strong><br/>
                        </div>  
                        <br/>
                    </div> 
                    <div class="subcolumn" style="float:left;width:5%;">
                        <span class="smalltext" style="font-size:10px;margin-top:5px; float: left;writing-mode: vertical-lr;transform: rotate(180deg);">
                        LBL/F-18_REV00 Date: {{date( 'd M Y' , strtotime('01-01-2023') )}}
                        </span>

                    </div>    
                </div>
               
                @endfor
                @endforeach
                <div style="break-after:page"></div>
                 </div>
                <style>
                        <?php for($i=1;$i<=$label_per_page;$i++) { ?>
                            .label-content<?php echo $i; ?>{
                                display:none;
                            }
                        <?php }?>
                </style>
               
            </div>
    
            
    
   

</body>
</html>