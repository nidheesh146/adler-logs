@extends('layouts.default')
@section('content')

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
                   <div class="card bd-0">
                        <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
                            <nav class="nav nav-tabs">
                                <a class="nav-link active" data-toggle="tab" href="#tabCont1">Primary SKU</a>
                                <a class="nav-link" data-toggle="tab" href="#tabCont2">Assembly </a>
                            </nav> 
                        </div>
                        <div class="card-body bd bd-t-0 tab-content">
                            <div id="tabCont1" class="tab-pane active show">
                            <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                                <form method="POST" id="commentForm" autocomplete="off" >
                                    {{ csrf_field() }}  
                                    
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Product *</label>
                                            <select class="form-control Product" name="product" id="product">
                                            </select>
                                            <!-- <input type="hidden" name="product_id" id="product_id" value=""> -->
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Batch Card No *</label>
                                            <input type="text" class="form-control"  value="{{$batch_no}}" name="batchcard" placeholder="Batch Card No" readonly>
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Process Sheet No *</label>
                                            <input type="text" class="form-control"  value="" name="process_sheet" placeholder="Process Sheet No">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>SKU Quantity *</label>
                                            <input type="text" class="form-control"  value="" name="sku_quantity" id="sku_quantity" placeholder="SKU Quantity">
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
                                            <input type="text" class="form-control datepicker" name="start_date" placeholder="Start Date">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>Target Date *</label>
                                            <input type="text" class="form-control datepicker" name="target_date" placeholder="Target Date">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Description *</label>
                                            <textarea value="" class="form-control" name="description" placeholder="Description"></textarea>
                                        </div>
                                        <table class="table table-bordered mg-b-0 data-bindings">
                                
                                        </table>
                                    </div>
                                    </br> 
                        
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                                role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                            @if(!empty($data['response']['purchase_requisition'][0]))
                                                Update
                                            @else 
                                                Save & Next
                                            @endif
                                            
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-devider"></div>
                                </form>
                            </div>
                            <div id="tabCont2" class="tab-pane">
                            <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                                <form method="POST"  action="{{url('batchcard/assemble-batchcard-add')}}" id="commentForm1" autocomplete="off" >
                                    {{ csrf_field() }}  
                                    <style>
                                         .select2-container--default .select2-selection--multiple .select2-selection__choice{
                                                color:white;
                                                background-color:#3366ff;
                                            }
                                            
                                    </style>
                                    <!-- <div class="form-devider"></div> -->
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Product *</label><br/>
                                            <select class="form-control Product1" name="product1" id="product1" style="width:100%">
                                            </select>
                                            <!-- <input type="hidden" name="product_id" id="product_id" value=""> -->
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Batch Card No *</label>
                                            <input type="text" class="form-control"  value="" name="batchcard" placeholder="Batch Card No">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Process Sheet No *</label>
                                            <input type="text" class="form-control"  value="" name="process_sheet" placeholder="Process Sheet No">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>SKU Quantity *</label>
                                            <input type="text" class="form-control"  value="" name="sku_quantity" placeholder="SKU Quantity">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Primary SKU Batchcards *</label><br/>
                                            <select class="form-control primary_sku_batchards" name="primary_sku_batchcards[]" id="primary_sku" multiple="multiple" style="width:100%">
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <label>Start Date *</label>
                                            <input type="text" class="form-control datepicker" id="start" name="start_date" placeholder="Start Date">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <label>Target Date *</label>
                                            <input type="text" class="form-control datepicker target" id="target" name="target_date" placeholder="Target Date">
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Description *</label>
                                            <textarea value="" class="form-control" name="description" placeholder="Item Description"></textarea>
                                        </div>
                                    </div> 
                        
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                                role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                                Save & Next       
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
                process_sheet:{
                    required: true,
                },
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
                process_sheet:{
                    required: true,
                },
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
        if(res){
          $.get("{{ url('batchcard/product/find-input-materials') }}?product_id="+res.id,function(data){
            $('.data-bindings').html(data);
            $('.spinner-button').hide();
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
        }
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
</script>


@stop