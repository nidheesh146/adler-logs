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
                <div class=" column" style=" height:14.13%;float: left;width: 45%;border-style:solid;border-color:#f0f0f5;border-width: thin;margin:5px;padding:8px;font-size:13px;">
                    <div class="subcolumn1" style="float:left;width:95%;">
                        <div class="sub1" style="float:left;width:30px;height:70px;padding:2px;margin-bottom: 13px; writing-mode: vertical-lr;transform: rotate(180deg);border-color:black;border-style: solid;border-width: thin;padding: 2px;">
                            <img src="{{asset('/img/alderlogo/consulting_use.png')}}" style="float:left;writing-mode: vertical-lr;transform: rotate(90deg); width:25px;height:25px;">
                            <span style="font-size:3.5px;width:content-fit;">To see instructions for use please visit:http://www.adler-healthcare.com</span>
                        </div> 
                        
                        <div class="sub2" style="float:left;width:5%;padding:1px;height:70px">
                            <span class="smalltext" style="font-size:4px;text-align: left;writing-mode: vertical-lr;transform: rotate(180deg);">
                                LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd-M-y' , strtotime($batchcard_data->start_date) )}}
                            </span>
                        </div>
                        <div class="sub3" style="float:left;width:50%;padding:1px;height:70px;font-size:6px">
                            <span style="">{{$batchcard_data->groups}}</span><br/>
                            <span style="font-weight:bold;">{{$batchcard_data->brand}}</span><br/>
                            <span style="">{{$batchcard_data->family}}</span><br/>
                            <span style="">{{$batchcard_data->snn_description}}</span><br/>
                            <span style="font-weight:bold">Ref: {{$batchcard_data->sku_code}}</span><br/>
                            <div style="padding-top:2px;">
                                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:25px">
                                <br/>
                                <small>{{$batchcard_data->sku_code}}</small>
                            </div>
                        </div>
                        <div class="sub4" style="float:left;width:25%;height:70px;padding:1px;font-size:7px">
                            <img src="/img/alderlogo/manufacturing.png" style="width:15px;">&nbsp;
                            <span class="smalltext">{{$batchcard_data->start_date}}</span><br/>
                            <img src="/img/alderlogo/lot.png" style="width:25px;">&nbsp;
                            <span class="smalltext">{{$batchcard_data->batch_no}}</span></br>
                            <strong>Qty :{{$batchcard_data->quantity_per_pack}}</strong>
                        </div>
                    </div> 
                    <div class="subcolumn2" style="float:left;height:40%">
                        <div style="float:left;width:70%;">
                            <div class="barcode2" style="font-size:5.6px">
                                <img src="data:image/png;base64,{{ base64_encode($gs1_label_batch_combo_barcode)}}" style="width:95%;height:22px">   
                                <br/>
                                <div style="text-align:center;"><small>{{$gs1_label_batch_combo}}</small></div>
                            </div>
                            <div class="barcode3"  style="float:left;margin-top:2px;">
                                <div class="barcode" style="width:40%;float:left;font-size:5.6px">
                                    <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:100%;height:25px">   
                                    <br/>
                                    <div style="text-align:center;"><small>{{$manf_date_combo}}</small></div>
                                </div>
                                <div style="margin-left:2px;float:left;width:50%;">
                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="float:left;width:30px;height:30px; margin-left:2.5px;">
                                    <span style="font-size:5px; padding-left:2px;font-weight:bold;display:block;">ML No:{{$batchcard_data->drug_license_number}}</span> 
                                    <span class=" cls" style="padding-left:2px;font-size:5px;font-weight:bold;display:block;";>ADLER HEALTHCARE PVT. LTD</span>
                                    <span style="font-size:5px;display:block;padding-left:2px">
                                                Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar</span>
                                    <span style="font-size:5px;display:block;padding-left:2px">Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA</span>
                                </div>
                            </div>
                        </div>
                        <div style="float:left;width:25%; margin-left:1px;">
                            <div class="logo">
                               
                                @if($batchcard_data->is_read_instruction_logo==1)
                                <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:35%;float:left;">
                                @endif
                                @if($batchcard_data->is_donot_reuse_logo==1)
                                <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}"  style="width:35%;float:left;">
                                @endif
                                @if($batchcard_data->is_non_sterile_logo==1)
                                <img src="{{asset('/img/alderlogo/non_sterile.png')}}"  style="width:35%;float:left;">
                                @endif
                                @if($batchcard_data->is_temperature_logo==1)
                                <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:35%;float:left;">
                                @endif
                            </div>
                        </div>
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

    
