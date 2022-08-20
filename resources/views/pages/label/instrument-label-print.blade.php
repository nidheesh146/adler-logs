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
                <div class="page-container" style="margin-top:0cm;margin-bottom:0.45cm;width:21.1cm;height:29.3cm;">
                @for ($j=0;$j<$label_per_page;$j++)
                <div class=" column" style="width:10.2cm;float: left;height:4.7cm;float: left;padding:2px 2px;font-size:13px;">
                <div class="subcolumn1" style="float:left;width:95%; height:content-fit;">
                        <div class="sub1" style="float:left;width:content-fit;padding:2px;margin-bottom: 13px; writing-mode: vertical-lr;transform: rotate(180deg);border-color:black;border-style: solid;border-width: thin;padding: 2px;font-size:3.7px;padding-left: 5px;">
                            <img src="{{asset('/img/alderlogo/consulting_use.png')}}" style="float:left;writing-mode: vertical-lr;transform: rotate(90deg); width:14px;height:14px;">
                            <span style="margin-left:2px;">visit :http://www.adler-healthcare.com <br>To see instructions for use please</span>
                        </div> 
                        
                        <div class="sub2" style="float:left;width:8%;padding:1px;height:70px">
                            <span class="smalltext" style="font-size:5px;text-align: center;writing-mode: vertical-lr;transform: rotate(180deg);margin-left:40%;margin-top:6px;">
                            LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd M y' , strtotime('14-12-2021') )}}
                            </span>
                        </div>
                        <div class="sub3" style="float:left;width:50%;padding:1px;font-size:8px;line-height:1.3;">
                            <span style="">{{$batchcard_data->groups}}</span><br/>
                            <span style="font-weight:bold;font-size:9px">{{$batchcard_data->brand}}</span><br/>
                            <span style="">{{$batchcard_data->family}}</span><br/>
                            <span style="">{{$batchcard_data->snn_description}}</span><br/>
                            <span style="font-weight:bold;font-size:9px">Ref: {{$batchcard_data->sku_code}}</span><br/>
                            <div style="padding-top:2px;width: fit-content;">
                                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:22px">
                                <br/>
                                <div style="text-align:center;font-size:6.2px;font-weight:400;">{{$batchcard_data->sku_code}}</div>
                            </div>
                        </div>
                        <div class="sub4" style="float:left;width:content-fit;height:70px;padding:1px;font-size:9px;text-align:center;">
                            <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                            <span class="smalltext1">{{$manufacturing_date}}</span><br/>
                            <strong>Qty :</strong>{{$batchcard_data->quantity_per_pack}} Nos
                            <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">&nbsp;
                            <span class="smalltext1">{{$batchcard_data->batch_no}}</span></br>
                            
                        </div>
                    </div> 
                    <div class="subcolumn2" style="float:left;margin-top:2px;">
                        <div style="float:left;width:80%;">
                            <div class="barcode2" style="font-size:5.6px">
                                <img src="data:image/png;base64,{{ base64_encode($gs1_label_batch_combo_barcode)}}" style="width:95%;height:22px">   
                                <br/>
                                <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:400">{{$gs1_label_batch_combo}}</div>
                            </div>
                        </div>
                        <div style="float:left;width:15%; margin-left:1px;">
                            <div class="logo" style="">
                               
                                <!--@if($batchcard_data->is_read_instruction_logo==1) -->
                                <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:32%;">
                                <!--@endif -->
                                <!-- @if($batchcard_data->is_donot_reuse_logo==1)
                                <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}"  style="width:35%;float:left;">
                                @endif -->
                            </div>
                        </div>
                    </div>
                    <div class="subcolumn3" style="float:left;margin-top:2px;">
                        <div style="float:left;width:80%;">
                            <div class="barcode3"  style="float:left;margin-top:4px;">
                                <div class="barcode" style="width:35%;float:left;font-size:5.6px">
                                    <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:100%;height:25px">   
                                    <br/>
                                    <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:400">{{$manf_date_combo}}</div>
                                </div>
                                <div style="margin-left:2px;float:left;width:62%;">
                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="float:left;width:32px;height:17px; margin-left:2.5px;margin-top: 8px">
                                    <span style="font-size:6px; padding-left:2px;font-weight:bold;display:block;margin-top:-4px">VL No:{{$batchcard_data->drug_license_number}}</span> 
                                    <span class=" cls" style="padding-left:2px;font-size:6px;font-weight:bold;display:block;margin-left: 35px;margin-top: 2px;";>ADLER HEALTHCARE PVT. LTD</span>
                                    <div class="" style="display:block;margin-left: 35px;">
                                        <span style="font-weight:400;font-size:6px;display:block;padding-left:2px">
                                                    Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar</span>
                                        <span style="font-weight:400;font-size:6px;display:block;padding-left:2px">Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="float:left;width:15%; margin-left:1px;">
                            <div class="logo" style="margin-top: 8px;">
                                <!-- @if($batchcard_data->is_donot_reuse_logo==1)
                                <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}"  style="width:35%;float:left;">
                                @endif -->
                                <!-- @if($batchcard_data->is_non_sterile_logo==1) -->
                                <img src="{{asset('/img/alderlogo/non_sterile.png')}}"  style="width:50%;">
                                <!-- @endif -->
                            </div>
                        </div>
                    </div>   
                </div>
                @endfor
                <div style="break-after:page"></div>
                </div>
                @endfor
                @if($remaining!=0)
                <div class="page-container" style="margin-top:0.9cm;margin-bottom:0.45cm;width:21.1cm;height:29.3cm;">
                @for ($j=0;$j<$remaining;$j++)
                <div class=" column" style="width:10.2cm;float: left;height:4.7cm;float: left;padding:2px 2px;font-size:13px;">
                    <div class="subcolumn1" style="float:left;width:95%; height:content-fit;">
                        <div class="sub1" style="float:left;width:content-fit;padding:2px;margin-bottom: 13px; writing-mode: vertical-lr;transform: rotate(180deg);border-color:black;border-style: solid;border-width: thin;padding: 2px;font-size:2.7px;padding-left: 5px;">
                            <img src="{{asset('/img/alderlogo/consulting_use.png')}}" style="float:left;writing-mode: vertical-lr;transform: rotate(90deg); width:14px;height:14px;">
                            <span style="margin-left:2px;">visit :http://www.adler-healthcare.com <br>To see instructions for use please</span>
                        </div> 
                        
                        <div class="sub2" style="float:left;width:8%;padding:1px;height:70px">
                            <span class="smalltext" style="font-size:4px;text-align: center;writing-mode: vertical-lr;transform: rotate(180deg);margin-left:40%;margin-top:12px;">
                                LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd-M-y' , strtotime($batchcard_data->start_date) ) }}
                            </span>
                        </div>
                        <div class="sub3" style="float:left;width:50%;padding:1px;font-size:5px;line-height:1.5;">
                            <span style="">{{$batchcard_data->groups}}</span><br/>
                            <span style="font-weight:bold;font-size:6px">{{$batchcard_data->brand}}</span><br/>
                            <span style="">{{$batchcard_data->family}}</span><br/>
                            <span style="">{{$batchcard_data->snn_description}}</span><br/>
                            <span style="font-weight:bold;font-size:6px">Ref: {{$batchcard_data->sku_code}}</span><br/>
                            <div style="padding-top:2px;width: fit-content;">
                                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:22px">
                                <br/>
                                <div style="text-align:center;"><small>{{$batchcard_data->sku_code}}</small></div>
                            </div>
                        </div>
                        <div class="sub4" style="float:left;width:content-fit;height:70px;padding:1px;font-size:7px;text-align:center;">
                            <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                            <span class="smalltext">{{$manufacturing_date}}</span><br/>
                            <strong>Qty :</strong>{{$batchcard_data->quantity_per_pack}} Nos
                            <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">&nbsp;
                            <span class="smalltext">{{$batchcard_data->batch_no}}</span></br>
                            
                        </div>
                    </div> 
                    <div class="subcolumn2" style="float:left;margin-top:2px;">
                        <div style="float:left;width:75%;">
                            <div class="barcode2" style="font-size:5.6px">
                                <img src="data:image/png;base64,{{ base64_encode($gs1_label_batch_combo_barcode)}}" style="width:95%;height:22px">   
                                <br/>
                                <div style="text-align:center;margin-top:1px;"><small>{{$gs1_label_batch_combo}}</small></div>
                            </div>
                        </div>
                        <div style="float:left;width:15%; margin-left:1px;">
                            <div class="logo" style="">
                               
                                <!--@if($batchcard_data->is_read_instruction_logo==1) -->
                                <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:32%;">
                                <!--@endif -->
                                <!-- @if($batchcard_data->is_donot_reuse_logo==1)
                                <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}"  style="width:35%;float:left;">
                                @endif -->
                            </div>
                        </div>
                    </div>
                    <div class="subcolumn3" style="float:left;margin-top:2px;">
                        <div style="float:left;width:75%;">
                            <div class="barcode3"  style="float:left;margin-top:4px;">
                                <div class="barcode" style="width:40%;float:left;font-size:5.6px">
                                    <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:100%;height:25px">   
                                    <br/>
                                    <div style="text-align:center;margin-top:1px;"><small>{{$manf_date_combo}}</small></div>
                                </div>
                                <div style="margin-left:2px;float:left;width:50%;">
                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="float:left;width:35px;height:20px; margin-left:2.5px;margin-top: 8px">
                                    <span style="font-size:5px; padding-left:2px;font-weight:bold;display:block;">VL No:{{$batchcard_data->drug_license_number}}</span> 
                                    <span class=" cls" style="padding-left:2px;font-size:5px;font-weight:bold;display:block;";>ADLER HEALTHCARE PVT. LTD</span>
                                    <span style="font-size:4px;display:block;padding-left:2px">
                                                Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar</span>
                                    <span style="font-size:3.5px;display:block;padding-left:2px">Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA</span>
                                </div>
                            </div>
                        </div>
                        <div style="float:left;width:15%; margin-left:1px;">
                            <div class="logo" style="margin-top: 8px;">
                                <!-- @if($batchcard_data->is_donot_reuse_logo==1)
                                <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}"  style="width:35%;float:left;">
                                @endif -->
                                <!-- @if($batchcard_data->is_non_sterile_logo==1) -->
                                <img src="{{asset('/img/alderlogo/non_sterile.png')}}"  style="width:44%;">
                                <!-- @endif -->
                            </div>
                        </div>
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

    
