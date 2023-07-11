@extends('layouts.default')
@section('content')
<style>
    input[type="radio"]{
        appearance: none;
        border: 1px solid #d3d3d3;
        width: 30px;
        height: 30px;
        content: none;
        outline: none;
        margin: 0;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        background-color: #fff;
    }

                input[type="radio"]:checked {
                appearance: none;
                outline: none;
                padding: 0;
                content: none;
                border: none;
                }

                input[type="radio"]:checked::before{
                position: relative;
                color: green !important;
                content: "\00A0\2713\00A0" !important;
                border: 1px solid #d3d3d3;
                font-weight: bolder;
                font-size: 21px;
                }

</style>
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">BATCH CARD</a></span> 
                <span><a href="">BATCH CARD ADD</a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">Batch Card</h4>
			<div class="row">  
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                    @endif
                    @if (Session::get('error'))
                    <div class="alert alert-danger " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('error') }}
                    </div>
                    @endif
                    @foreach ($errors->all() as $errorr)
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      {{ $errorr }}
                    </div>
                   @endforeach
                   <div class="form-devider"></div>
                   <div class="card bd-0">
                        <!-- <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
                            <nav class="nav nav-tabs">
                                <a class="nav-link active" data-toggle="tab" href="#tabCont1">Primary SKU</a>
                                <a class="nav-link" data-toggle="tab" href="#tabCont2">Assembly </a>
                            </nav> 
                        </div> -->
                        <div class="card-body bd bd-t-0 tab-content">
                            <div id="tabCont1" class="tab-pane active show">
                            <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                                <form method="POST" id="commentForm" autocomplete="off" >
                                    {{ csrf_field() }}  
                                    
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Product *</label>
                                            <select class="form-control Product" name="product" id="product">
                                                @foreach($products as $product)
                                                <option value="{{$product['id']}}" @if($batchcard['product_id']==$product['id']) selected @endif>{{$product['sku_code']}}</option>
                                                @endforeach
                                            </select>
                                            <!-- <input type="hidden" name="product_id" id="product_id" value=""> -->
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Batch Card No *</label>
                                            <input type="text" class="form-control"  value="{{$batchcard['batch_no']}}" name="batchcard" placeholder="Batch Card No" >
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Process Sheet No *</label>
                                            <input type="text" class="form-control"  value="{{$batchcard['process_sheet_no']}}" name="process_sheet"  id="process_sheet" placeholder="Process Sheet No" readonly>
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>SKU Quantity *</label>
                                            <input type="text" class="form-control"  value="{{$batchcard['quantity']}}" name="sku_quantity" id="sku_quantity" placeholder="SKU Quantity">
                                        </div><!-- form-group -->
                                       {{-- <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Input Material *</label>
                                            <select class="form-control input_material" name="input_material" id="input_material">
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Input Material Quantity*</label>
                                            <input type="text" class="form-control"  value="" name="input_material_qty" placeholder="Input Material Quantity">
                                        </div> --}}
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>Start Date *</label>
                                            <input type="date" class="form-control start_date"  value="{{date('Y-m-d',strtotime($batchcard['start_date']))}}" name="start_date" placeholder="Start Date">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                            <label>Target Date *</label>
                                            @php $date= date('Y-m-d', strtotime('+60 days')) @endphp
                                            <input type="text" class="form-control target_date" name="target_date" value="{{date('d-m-Y', strtotime($date))}}" placeholder="Target Date">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Product Description *</label>
                                            <textarea value="" class="form-control description" name="description" placeholder="Description" readonly>{{$batchcard['discription']}}</textarea>
                                        </div>
                                        <table class="table table-bordered mg-b-0 data-bindings">
                                            <tr>
                                                <th>Sl No.</th>
                                                <th>Option1</th>
                                                <th>Option2</th>
                                                <th>Option3
                                            </tr>
                                            <?php $i=1; ?>
                                            @foreach($input_materials as $material)
                                            <tr>
                                                <th>{{ $i++ }}
                                                <input type="hidden" name="product_inputmaterial_id" value="{{$material['id']}}"></th></th>
                                                @if($material['item1_id']==0)
                                                <td><input type="radio" class="item-select-radio" checked name="material{{$i}}" value="{{$material['id']}}">Assembly<br/>
                                                <input type="hidden" name="rawmaterial_id{{$i}}" value="0">
                                                <input type="hidden" class="materialqty materialqty{{$i}}" name="materialqty{{$i}}" value="0">
                                                @else
                                                <input type="hidden" class="input_material_qty input_material_qty{{$i}}" name="input_material_qty{{$i}}" value="">
                                                <td>
                                                <input type="radio" class="item-select-radio" checked name="material{{$i}}" value="{{$material['id']}}"><br/>
                                                Item Code<input type="text" class="form-control"  value="{{$material['item_code1']}}" readonly>
                                                <input type="hidden" name="product_inputmaterial_id{{$i}}" value="{{$material['id']}}">
                                                    <input type="hidden" name="rawmaterial_id{{$i}}" value="{{$material['material_id1']}}">
                                                    
                                                    Quantity
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control material-qty qty{{$i}}" name="qty{{$i}}" value="@if($material['quantity1']) {{$material['quantity1']}} @else 0 @endif" required aria-describedby="unit-div1">
                                                        <input type="hidden" class="materialqty materialqty{{$i}}" name="materialqty{{$i}}" value="@if($material['quantity1']) {{$material['quantity1']}} @else 0 @endif">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text unit-div" id="unit-div1">{{$material['unit1']}}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($material['item2_id']!=NULL )
                                                    @if($material['item2_id']==0)
                                                    <td><input type="radio" class="item-select-radio"  name="material{{$i}}" value="{{$material['id']}}">Assembly<br/>
                                                        <input type="hidden" name="rawmaterial_id{{$i}}" value="0">
                                                        <input type="hidden" class="materialqty materialqty{{$i}}" name="materialqty{{$i}}" value="@if($material['quantity1']) {{$material['quantity1']}} @else 0 @endif">
                                                    @else
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="item-select-radio" name="material{{$i}}" value="{{$material['id']}}"><br/>
                                                        Item Code<input type="text" class="form-control"  value="{{$material['item_code2']}}" readonly>
                                                        <input type="hidden" name="product_inputmaterial_id{{$i}}" value="{{$material['id']}}">
                                                        <input type="hidden" name="rawmaterial_id{{$i}}" value="{{$material['material_id2']}}">
                                                        Item Description<textarea value="" class="form-control" name="description" placeholder="Description" readonly>{{$material['description2']}}</textarea>
                                                        Quantity
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control material-qty qty{{$i}}" name="qty{{$i}}" value="@if($material['quantity2']) {{$material['quantity2']}} @else 0 @endif" required aria-describedby="unit-div1" >
                                                            <input type="hidden" class="materialqty materialqty{{$i}}" name="materialqty{{$i}}" value="@if($material['quantity2']) {{$material['quantity2']}} @else 0 @endif">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text unit-div" id="unit-div1">{{$material['unit2']}}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                </td><td> <div style="margin-top: 35%;"><div class="alert alert-success success" style="width: 100%;"> No alternative raw material exist..</div>
                                                @endif
                                                @if($material['item3_id']!=NULL )
                                                    @if($material['item3_id']==0)
                                                    </td>
                                                    <td>
                                                    <input type="radio" class="item-select-radio"  name="material{{$i}}" value="{{$material['id']}}"><br/>
                                                    Item Code<input type="text" class="form-control"  value="{{$material['item_code3']}}" readonly>
                                                    <input type="hidden" name="product_inputmaterial_id{{$i}}" value="{{$material['id']}}">
                                                    <input type="hidden" name="rawmaterial_id{{$i}}" value="{{$material['material_id3']}}">
                                                    Item Description<textarea value="" class="form-control" name="description" placeholder="Description" readonly>{{$material['description3']}}</textarea>
                                                    Quantity
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control material-qty qty{{$i}}" name="qty{{$i}}" value="@if($material['quantity3']) {{$material['quantity3']}} @else 0 @endif" required aria-describedby="unit-div1" >
                                                        <input type="hidden" class="materialqty materialqty{{$i}}" name="materialqty{{$i}}" value="@if($material['quantity3']) {{$material['quantity3']}} @else 0 @endif">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text unit-div" id="unit-div1">{{$material['unit3']}}</span>
                                                        </div>
                                                    </div>
                                                    @else
                                                    </td><td><div style="margin-top: 35%;"><div class="alert alert-success success" style="width: 100%;"> No alternative raw material exist..</div></div>
                                                    @endif
                                                @else
                                                </td><td> <div style="margin-top: 35%;"><div class="alert alert-success success" style="width: 100%;"> No alternative raw material exist..</div>
                                                @endif
                                                </td><tr/><tr><td></td><td></td><td></td></tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    </br> 
                        
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                                role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                            Update
                                            
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-devider"></div>
                                </form>
                            </div>
                            
                        </div>
                    </div>  

                </div>
            </div>
            
        </div>
	</div>
	<!-- az-content-body -->
</div>




<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

<script>
  $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
  //  .datepicker('update', new Date());
    $('.datepicker').mask('99-99-9999');

    $("#commentForm").validate({
            rules: {
                product: {
                    required: true,
                },
                sku_quantity: {
                    required: true,
                    number: true,
                },
                // process_sheet:{
                //     required: true,
                // },
                start_date: {
                    required: true,
                },
                target_date: {
                    required: true,
                },
                batchcard: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
        $("#commentForm1").validate({
            rules: {
                product: {
                    required: true,
                },
                sku_quantity: {
                    required: true,
                    number: true,
                },
                // process_sheet:{
                //     required: true,
                // },
                start_date: {
                    required: true,
                },
                target_date: {
                    required: true,
                },
                description: {
                    required: true,
                },
                batchcard: {
                    required: true,
                },
                primary_sku_batchcards:{
                    required:true,
                },
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });

    
    });
    $('.Product, .Product1').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('batchcard/productsearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    }).on('change', function (e) {
        $('.spinner-button').show();

        let res = $(this).select2('data')[0];
        $('#process_sheet').val(res['process_sheet_no']);
        if(res){
            $('.description').text(res.discription);
          $.get("{{ url('batchcard/product/find-input-materials') }}?product_id="+res.id,function(data){
            $('.data-bindings').html(data);
            $('.spinner-button').hide();
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
        }
      });
      
    
    $('.start_date').on('change',function(e)
    {
        var start_date = new Date($(this).val());
        var date  = new Date(start_date.setDate(start_date.getDate()+60));
        var target_date = ( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
        $('.target_date').val(target_date);
    });


    $('.primary_sku_batchards').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: "{{ url('inventory/stock/find-batchcard') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    });
    $('#sku_quantity').on("input", function() {
        var material_count = ($(".material-qty").length );
        if($(this).val()!='')
        {
            for(i=1;i<=material_count;i++)
            {
                qty_per_sku = $(this).val()*$(".materialqty"+i+"").val();
                //alert(qty_per_sku);
                $(".qty"+i+"").val(qty_per_sku);
            }
        }
        else
        {
            for(i=1;i<=material_count;i++)
            {
                $(".qty"+i+"").val($(".materialqty"+i+"").val());
            }
        }
       
    });
    $('.input_material').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: "{{ url('inventory/itemcodesearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    });
    $(document).on('change','.item-select-radio', function(event){
        event.preventDefault();
        if (this.checked)
        {
            var ele=$(this).siblings('.input-group').find('.materialqty');
            var qty=$(this).siblings('.input-group').find('.materialqty').val();
            $(this).siblings('.input-group').find('.materialqty').removeAttr('disabled');
            // console.log( $(this).siblings('.input-group').find('.materialqty').attr('value'));
            // $(this).parent().parent().find('.input_material_qty').val(qty);
        }else{
            alert('Option 1 is unchecked!');
        }
     
    });
</script>


@stop