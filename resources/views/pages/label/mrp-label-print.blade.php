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
                @for ($i = 0; $i< $no_of_label; $i++)
                <div class=" column" style=" height:13.8%;float: left;width: 45%;border-style: ridge;border-color:#f0f0f5 ;margin:5px;padding:8px;font-size:13px;">
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
                        <span class="smalltext" style="font-size:10px;margin-top:25px; float: left;writing-mode: vertical-lr;transform: rotate(180deg);">
                               {{$product->drug_license_number}}
                        </span>

                    </div>    
                </div>
                @endfor
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

    
