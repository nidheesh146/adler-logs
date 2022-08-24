@extends('layouts.default')
@section('content')
<style>
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
<div class="az-content az-content-dashboard">
  <br>
    <div class="container">
	    <div class="az-content-body">     
            <div class="az-content-title">
                <button  style="float: right; margin-left: 9px;font-size: 14px;width:90px;" class="badge badge-pill badge-info " id="print">
                <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print
            </button>
            </div>
            <div class="row label-div columns" id="label-div">
                <?php
                    $label_per_page =12;
                    $page_count = (int)($no_of_label/12);
                    $remaining = $no_of_label%12;
                 ?>
                @for ($i = 0; $i< $page_count; $i++)
                <div class="page-container" style="margin-top:0.2cm;width:21.1cm;height:29.3cm;">
                @for ($j=0;$j<$label_per_page;$j++)
                <div class=" label-container" style=" width:10.1cm;float: left;height:5cm;margin:2px;padding:2px;font-size:14px; margin-left:10px;margin-bottom:6px; line-height:130%;">
                    <div class="subcolumn" style="float:left;width:95%;">
                        <div class="address" style="font-weight:bold;">
                            MKtd and Distributed by : Smith & Nephew<br/>
                            Healthcare Pvt.Ltd. B-501-509 Dynasty<br/>
                            Business Park, Andheri East, Mumbai-400059 
                        </div>
                        <strong>For Product Feedback, Contact on:</strong>
                        <div><strong>Email :</strong>Complaint.india@smith-nephew.com<br/>
                         Tel: +91-22-40055090 &nbsp; ML No: {{$product->drug_license_number}}<br/>
                         <strong>Code No: {{$product->sku_code}}</strong><br/>
                         <strong>MRP (Incl. of all taxes/pc) : Rs.{{$product->mrp}} /-</strong><br/>
                        </div>  
                        <br/>
                    </div> 
                    <div class="subcolumn" style="float:left;width:5%;">
                        <span class="smalltext" style="font-size:10px;margin-top:22px; float: left;writing-mode: vertical-lr;transform: rotate(180deg);">
                        LBL/F-08_rev00_{{date( 'd M y' , strtotime('29-01-2022') )}}
                        </span>

                    </div>    
                </div>
                @endfor
                <div style="break-after:page"></div>
                </div>
                @endfor
                @if($remaining!=0)
                <div class="page-container" style="margin-top:0.2cm;margin-bottom:0.45cm;width:21.1cm;height:29.3cm;">
                @for ($j=0;$j<$remaining;$j++)
                <div class=" column" style="width:10.1cm;float: left;height:5cm;margin:2px;padding:2px;font-size:15px; margin-left:10px;margin-bottom:6px; line-height:135%;">
                    <div class="subcolumn" style="float:left;width:95%;">
                        <div class="address" style="font-weight:bold;">
                            MKtd and Distributed by : Smith & Nephew<br/>
                            Healthcare Pvt.Ltd. B-501-509 Dynasty<br/>
                            Business Park, Andheri East, Mumbai-400059 
                        </div>
                        <strong>For Product Feedback, Contact on:</strong>
                        <div><strong>Email :</strong>Complaint.india@smith-nephew.com<br/>
                         Tel: +91-22-40055090 &nbsp; ML No: {{$product->drug_license_number}}<br/>
                         <strong>Code No: {{$product->sku_code}}</strong><br/>
                         <strong>MRP (Incl. of all taxes/pc) : Rs.{{$product->mrp}} /-</strong><br/>
                        </div>  
                    </div> 
                    <div class="subcolumn" style="float:left;width:5%;">
                        <span class="smalltext" style="font-size:8px;margin-top:22px; float: left;writing-mode: vertical-lr;transform: rotate(180deg);">
                        LBL/F-08_rev00_{{date( 'd M y' , strtotime('29-01-2022') )}}
                        </span>

                    </div>    
                </div>
                @endfor
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script src="<?= url('') ?>/js/azia.js"></script>

<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>

<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/print/jQuery.print.min.js"></script>
<script>
//$("#print").on("click", function () {
function Labelprint(){

    var mywindow = window.open();
      var content = document.getElementById("label-div").innerHTML;
      var realContent = document.body.innerHTML;
      mywindow.document.write('<html><head><title></title>');
      mywindow.document.write( "<link rel='stylesheet' href='/public/css/print.css' type='text/css' media='print'/>" );
      mywindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="print"/>');
      mywindow.document.write('</head><body>');

      mywindow.document.write(content);
      mywindow.document.write('</body></html>')
      mywindow.document.close(); // necessary for IE >= 10
      //mywindow.focus(); // necessary for IE >= 10*/
      mywindow.print();
      document.body.innerHTML = realContent;
      mywindow.close();
      history.back();
      //return false;
}
$("#print").on("click", function () {
Labelprint();
      });
</script>
@stop

    
