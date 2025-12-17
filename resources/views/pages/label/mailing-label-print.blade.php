<!DOCTYPE html>
<html>
<head>

    <title>MAILING_LABEL</title>
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
                <?php
                    $label_per_page =12;
                    $page_count = (int)($total_print_count/12);
                    $remaining = $total_print_count%12;
                 ?>
                <div class="page-container" style=""> 
                @foreach($print_item as $printItem)
                @for($k=0;$k<$printItem['print_count'];$k++)
                <div class="label-container" style="width:100%;height:22%;margin:2px;padding:2px;font-size:14px; margin-left:10px;margin-bottom:23px; line-height:130%;border:black 0.5px solid;">
                    <div class="subcolumn" style="width:60%;display:inline-block;border-right:black 0.5px solid;">  
                        <div class="row1" style="padding-top:10px;">  
                            <div class="to" style="width:50%;display:inline-block;">
                                To,
                            </div>
                            <div class="zone" style="display:inline-block;text-align:right;margin-top:-3px;float:right;">
                                <span style="text-align:right;font-size:11px;">{{$printItem['customer']->zone_name}}</span>
                                <span style="text-align:right;padding-right:5px;font-size:11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ZONE</span>
                            </div>
                        </div>
                        <br/>
                        <div class="row2">
                            <div class="firm-name" style="font-size:20px;font-weight:bold;">
                                {{$printItem['customer']->firm_name}}
                            </div><br/>
                            <div class="shipping-address" style="font-size:14px;">
                                <p> <?= nl2br($printItem['customer']->shipping_address);?></p><br/>
                            </div>
                        </div>
                        <div class="row3" style="margin-top:25px;">
                            <div class="" style="display:inline-block;font-size:12.5px;">CONTACT PERSON  :</div>
                            <div class="" style="display:inline-block;float:right;font-size:12.5px;padding-right:5px;">{{$printItem['customer']->contact_person}}</div>
                        </div>
                        <br/>
                        <div class="row4" style="margin-top:0px;">
                            <div class="" style="display:inline-block;font-size:12.5px;">MOBILE NO.  :</div>
                            <div class="" style="display:inline-block;float:right;font-size:12.5px;padding-right:5px;">@if($printItem['customer']->contact_number!="NA") <u><b>{{$printItem['customer']->contact_number}} @endif</b></u></div>
                        </div>
                    </div>
                    <div class="subcolumn" style="width:39.4%;display:inline-block;float:right;">
                        <div class="row21" style="padding-top:10px;font-size:18px;font-weight:bold;">  
                            <center><u>BOX NO.<u></center>
                        </div><br/>
                        <div class="row22" style="border-bottom:black solid 0.5px;font-size:18px;text-align:center;">  
                               OF
                        </div>
                        <div class="row23" style="padding-top:10px;font-size:15px;border-bottom:black solid 0.5px;">  
                            <center><u>BOX DIMENSIONS (L x W x H) IN CMS.<u></center>
                            <br>
                            <center>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X 
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </center>
                            <br/> 
                        </div>
                        <div class="row24" style="margin-top:16px;"font-size:18px;font-weight:bold;">  
                            <center><u>GROSS WEIGHT	<u></center>
                        </div>
                        <div class="row25" style="font-size:18px;text-align:center;">  
                                <br/>
                               KGS <br/>
                        </div>

                    </div>  
                    
                </div>
                <!-- <br/> -->
                @endfor
                @endforeach
            </div>
    
            
    
   

</body>
</html>