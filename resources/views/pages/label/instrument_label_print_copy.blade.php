@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
    <div class="container">
	    <div class="az-content-body">     
            <div class="az-content-title">
                <button  style="float: right; margin-left: 9px;font-size: 14px;width:90px;" class="badge badge-pill badge-info " id="print">
                <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print
            </button>
            </div>
            <?php 
            $label_per_page =12;
            $page_count = (int)($no_of_label/12);
            $remaining = $no_of_label%12;
        
            ?>
            <input type="hidden" name="batch_id" id="batch_id" value="{{$batchcard_data->batch_id}}">
            <input type="hidden" name="no_of_labels" id="no_of_labels" value="{{$no_of_label}}">
            <input type="hidden" name="manufacturing_date" id="manufacturing_date" value="{{$manufacturing_date}}">
            <input type="hidden" name="product_id" id="product_id" value="{{$batchcard_data->product_id}}">
            <input type="hidden" name="label_name" id="label_name" value="Instrument Label">

            <div class="label-div" id="label-div" style="margin:0px; padding:0px;">
                @for ($i = 1; $i<= $page_count; $i++)
                <div style="margin-top:0px;">
                @for ($j = 1; $j<= $label_per_page; $j++)
                <div class=" column " style="height:16%;float:left;width: 48%;padding:0px 2px;font-size:13px; margin-left:10px;@if($j==11 || $j==12) margin-bottom:0px; @else margin-bottom:11.3px;  @endif">
                    <div class="subcolumn1" style="float:left;width:95%; height:content-fit;">
                        <div class="sub1" style="float:left;width:content-fit;font-weight:bold;">
                            <img src="{{asset('/img/alderlogo/IFU.png')}}" style="float:left; height:81px;">
                        </div> 
                        
                        <div class="sub2" style="float:left;width:8%;padding:1px;height:70px">
                            <span class="smalltext" style="font-size:4.7px;font-weight:500;text-align: center;writing-mode: vertical-lr;transform: rotate(180deg);margin-left:40%;margin-top:5px;">
                            LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{strtoupper(date( 'dMY' , strtotime('01-08-2023')) )}}
                            </span>
                        </div>
                        <div class="sub3" style="float:left;width:45%;padding:1px;font-size:8px;line-height:1.1;">
                            <span style="">{{$batchcard_data->groups}}</span><br/>
                            <span style="font-weight:bold;font-size:9px">{{$batchcard_data->brand}}</span><br/>
                            <span style="">{{$batchcard_data->family}}</span><br/>
                            <span style="">{{$batchcard_data->snn_description}}</span><br/>
                            <span style="font-weight:bold;font-size:9px">Ref: {{$batchcard_data->sku_code}}</span><br/>
                            <div style="padding-top:1px;width: fit-content;">
                                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:20px">
                                <br/>
                                <div style="text-align:center;font-size:6.2px;font-weight:550;">{{$batchcard_data->sku_code}}</div>
                            </div>
                        </div>
                        <div class="sub4" style="float:left;width:content-fit;height:70px;padding:1px;font-size:9px;text-align:center;">
                            <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                            <span class="smalltext1">{{$manufacturing_date}}</span><br/>
                            <strong>Qty :</strong>{{$per_pack_quantity}} Nos
                            <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">&nbsp;
                            <span class="smalltext1">{{$batchcard_data->batch_no}}</span></br>
                        </div>
                    </div> 
                    <div class="subcolumn2" style="float:left;margin-top:1px;">
                        <div style="float:left;width:80%;">
                            <div class="barcode2" style="font-size:5.6px">
                                <img src="data:image/png;base64,{{ base64_encode($label_batch_combo_barcode)}}" style="width:95%;height:22px">   
                                <br/>
                                <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">{{$label_batch_combo}}</div>
                            </div>
                        </div>
                        <div style="float:left;width:15%; margin-left:1px;">
                            <div class="logo" style="text-align:center;">
                                <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:40%;float:left;padding:0px;margin-left: 3px;">
                               
                            </div>
                        </div>
                    </div>
                    <div class="subcolumn3" style="float:left;margin-top:2px;">
                        <div style="float:left;width:83%;">
                            <div class="barcode3"  style="float:left;margin-top:4px;">
                                <!--div class="barcode" style="width:30%;float:left;font-size:5.6px">
                                    <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:100%;">   
                                    <br/>
                                    <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">{{$manf_date_combo}}</div>
                                </div-->
                                <div style="margin-left:2px;float:left;width:68%;">
                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="float:left;width:32px;height:17px; margin-left:2.5px;margin-top: 8px">
                                    <span style="font-size:6px; padding-left:2px;font-weight:bold;display:block;margin-top:-4px">{{$batchcard_data->drug_license_number}}</span>
                                    <span class=" cls" style="padding-left:2px;font-size:7.5px;font-weight:bold;display:block;margin-left: 35px;margin-top: 2px;";>ADLER HEALTHCARE PVT. LTD</span>
                                    <div class="" style="display:block;margin-left: 35px;">
                                        <span style="font-weight:550;font-size:6.1px;display:block;padding-left:2px">
                                                    Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar</span>
                                        <span style="font-weight:550;font-size:6.3px;display:block;padding-left:2px">Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="float:left;width:15%; margin-left:1px;">
                            <div class="logo" style="    margin-top: 5px;">
                                
                                <img src="{{asset('/img/alderlogo/non_sterile.png')}}"  style="width:60%;margin-bottom:2px;">
                               
                            </div>
                        </div>
                    </div>   
                </div>
                
                @endfor
                </div>
                <div style="break-after:page"></div>
                @endfor
                <style>
                        <?php for($i=1;$i<=$label_per_page;$i++) { ?>
                            .label-content<?php echo $i; ?>{
                                display:none;
                            }
                        <?php }?>
                </style>
            
                @if($remaining!=0)
                <style>
                        <?php for($i=1;$i<=$remaining;$i++) { ?>
                            .label-content<?php echo $i; ?>{
                                display:block;
                            }
                        <?php }?>
                </style>
                <div  style="margin-top:0mm;"> 
                @for ($j=1;$j<=$label_per_page;$j++)
                <div class=" column label-content{{$j}}" style="height:16%;float:left;width: 48%;padding:0px 2px;font-size:13px; margin-left:10px;@if($j==11 || $j==12) margin-bottom:0px; @else margin-bottom:11.3px;  @endif">
                    <div class="subcolumn1" style="float:left;width:95%; height:content-fit;">
                        <div class="sub1" style="float:left;width:content-fit;font-weight:bold;">
                            <img src="{{asset('/img/alderlogo/IFU.png')}}" style="float:left; height:81px;">
                        </div> 
                        
                        <div class="sub2" style="float:left;width:8%;padding:1px;height:70px">
                            <span class="smalltext" style="font-size:4.7px;font-weight:500;text-align: center;writing-mode: vertical-lr;transform: rotate(180deg);margin-left:40%;margin-top:5px;">
                            LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{strtoupper(date( 'dMY' , strtotime('01-08-2023')) )}}
                            </span>
                        </div>
                        <div class="sub3" style="float:left;width:45%;padding:1px;font-size:8px;line-height:1.1;">
                            <span style="">{{$batchcard_data->groups}}</span><br/>
                            <span style="font-weight:bold;font-size:9px">{{$batchcard_data->brand}}</span><br/>
                            <span style="">{{$batchcard_data->family}}</span><br/>
                            <span style="">{{$batchcard_data->snn_description}}</span><br/>
                            <span style="font-weight:bold;font-size:9px">Ref: {{$batchcard_data->sku_code}}</span><br/>
                            <div style="padding-top:1px;width: fit-content;">
                                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:20px">
                                <br/>
                                <div style="text-align:center;font-size:6.2px;font-weight:550;">{{$batchcard_data->sku_code}}</div>
                            </div>
                        </div>
                        <div class="sub4" style="float:left;width:content-fit;height:70px;padding:1px;font-size:9px;text-align:center;">
                            <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                            <span class="smalltext1">{{$manufacturing_date}}</span><br/>
                            <strong>Qty :</strong>{{$per_pack_quantity}} Nos
                            <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">&nbsp;
                            <span class="smalltext1">{{$batchcard_data->batch_no}}</span></br>
                        </div>
                    </div> 
                    <div class="subcolumn2" style="float:left;margin-top:1px;">
                        <div style="float:left;width:80%;">
                            <div class="barcode2" style="font-size:5.6px">
                                <img src="data:image/png;base64,{{ base64_encode($label_batch_combo_barcode)}}" style="width:95%;height:22px">   
                                <br/>
                                <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">{{$label_batch_combo}}</div>
                            </div>
                        </div>
                        <div style="float:left;width:15%; margin-left:1px;">
                            <div class="logo" style="text-align:center;">
                                <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:40%;float:left;padding:0px;margin-left: 3px;">
                               
                            </div>
                        </div>
                    </div>
                    <div class="subcolumn3" style="float:left;margin-top:2px;">
                        <div style="float:left;width:83%;">
                            <div class="barcode3"  style="float:left;margin-top:4px;">
                                <!--div class="barcode" style="width:30%;float:left;font-size:5.6px">
                                    <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:100%;">   
                                    <br/>
                                    <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">{{$manf_date_combo}}</div>
                                </div-->
                                <div style="margin-left:2px;float:left;width:68%;">
                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="float:left;width:32px;height:17px; margin-left:2.5px;margin-top: 8px">
                                    <span style="font-size:6px; padding-left:2px;font-weight:bold;display:block;margin-top:-4px">{{$batchcard_data->drug_license_number}}</span> 
                                    <span class=" cls" style="padding-left:2px;font-size:7.5px;font-weight:bold;display:block;margin-left: 35px;margin-top: 2px;";>ADLER HEALTHCARE PVT. LTD</span>
                                    <div class="" style="display:block;margin-left: 35px;">
                                        <span style="font-weight:550;font-size:6.1px;display:block;padding-left:2px">
                                                    Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar</span>
                                        <span style="font-weight:550;font-size:6.3px;display:block;padding-left:2px">Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="float:left;width:15%; margin-left:1px;">
                            <div class="logo" style="    margin-top: 5px;">
                                <img src="{{asset('/img/alderlogo/non_sterile.png')}}"  style="width:60%;margin-bottom:2px;">
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
    
    var batch_id = $('#batch_id').val();
    var no_of_labels = $('#no_of_labels').val();
    var manufacturing_date = $('#manufacturing_date').val();
    var product_id = $('#product_id').val();
    var label_name = $('#label_name').val();
    $.ajax({
           type:'POST',
           url:"{{ url('label/insert-printing-data') }}",
           data:{ "_token": "{{ csrf_token() }}",batch_id:batch_id, no_of_labels:no_of_labels, manufacturing_date:manufacturing_date, product_id:product_id,label_name:label_name},
           success:function(data)
           {
            if(data==1)
            Labelprint();
            else
            alert('Label printing failed!!!')
           }
    });
});
</script>
@stop

    
