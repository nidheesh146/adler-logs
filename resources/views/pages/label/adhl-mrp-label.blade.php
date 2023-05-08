@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">LABEL CARD</a></span> 
                <span><a href="" style="color: #596881;"> CREATE ADHL MRP Label</a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            CREATE ADHL MRP Label
            </h4>
            <div class="form-devider"></div>
			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                    @endif
                    @if(Session::get('error'))
                        <div class="alert alert-danger "  role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ Session::get('error') }}
                    </div>
                    @endif                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" action="{{url('label/adhl-mrp-label')}}">
                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>SKU Code *</label>
                                <input type="text" value="" class="form-control" name="sku_code" id="sku_code" placeholder="SKU Code">
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Batch Card *</label>
                                <input type="text" class="form-control batchcard_no" name="batchcard_no" id="batchcard_no" placeholder="Batch card No" readonly>
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>No of label *</label>
                            <input type="number" 
                                value="" class="form-control" name="no_of_label" placeholder="No of label">
                            </div><!-- form-group -->

                        </div> 
            
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span> 
                                <i class="fas fa-save"></i>
                                Generate
                                </button>
                            </div>
                        </div>
                        <div class="form-devider"></div>
                    </form>

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
    $("#commentForm").validate({
            rules: {
                batchcard_no: {
                    required: true,
                },
                sku_code: {
                    required: true,
                },
                no_of_label: {
                    required: true,
                },
            },
            // submitHandler: function(form) {
            //     $('.spinner-button').show();
            //     form.submit();
            // }
        });

        $('#sku_code').focusout(function(){
            var sku_code = $(this).val();
            $.get( "{{ url('label/getBatchcard') }}/"+sku_code, function(res) {
               $('#batchcard_no').val(res);
            });
        });
});
</script>


@stop