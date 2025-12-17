<!DOCTYPE html>
<html>
<head>

    <title>JAYON_MRP_LABEL</title>
</head>
<body>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
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
                <div class="page-container" style="width:21.1cm;height:29.3cm;"> 
                {{--print_r($print_item);--}}
                @for($k=0;$k<(2*$no_of_label);$k++)
                <div class="label-container" style="width:48%;height:14%;margin:2px;padding:2px;font-size:14px; margin-left:10px;margin-bottom:6px; line-height:130%;">
                    <div class="subcolumn" style="width:90%;display:inline-block;">    
                        <div class="address" style="font-weight:bold;">
                            <span style="font-size:13px;">Mktd and Distributed by: JAYON IMPLANTS PVT LTD</span><br/>
                            IV/1064, Industrial Development Area,<br/>
                            Kanjikode, Palakkad-678623, Kerala, India 
                        </div>
                        <strong>For Product Feedback, Contact on:</strong>
                        <div><span style="color:blue;font-size:11px;">Email: info@jayonimplants.com</span>&nbsp;|<span style="font-size:12px;">Tel: 0491 2566816, 2567817&nbsp;</span><br/>
                            <span style="font-size:14px;">@if($product->drug_license_number!=NULL) {{$product->drug_license_number}} @endif</span><br/>
                            <strong>Code No: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$product->sku_code}}</strong><br/>
                            <strong>MRP : Rs.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$product->mrp}} /-&nbsp;&nbsp;(Incl. of all taxes/pc) </strong><br/>
                        </div> 
                    </div>
                    <div class="subcolumn" style="width:5%;display:inline-block;">
                        <span class="smalltext" style="white-space: nowrap;font-size:8px;margin-top:8px;writing-mode: vertical-lr;transform: rotate(270deg);">
                        LBL/F-20 _REV00 Date: 31DEC2023
                        {{--LBL/F-00_REV00 Date:date( 'd M Y' , strtotime('00-00-0000') )--}} 
                     
                        </span>

                    </div>  
                    
                </div>
                <br/>
                @endfor
                {{--<div style="break-after:page"></div>
                 </div>--}}
                
               
            </div>
    
            
    
   

</body>
</html>