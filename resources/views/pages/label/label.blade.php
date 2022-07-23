@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">LABEL CARD</a></span> 
                <span><a href="" style="color: #596881;">
                {{$title}}
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            {{$title}}
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
                    @if(!empty($data['error']))
                        <div class="alert alert-danger "  role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $data['error'] }}
                    </div>
                    @endif                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" >
                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Batch Card *</label>
                                <select class="form-control select2" name="batchcard_no">
                                    <option value="">--- select one ---</option>
                                </select>
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>No of label *</label>
                            <input type="number" 
                                value="" class="form-control" name="no_of_label" placeholder="No of label">
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Per Pack Quantity *</label>
                                <input type="number" value="" class="form-control" name="per_pack_quantity" placeholder="Per pack quantity">
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Manufacturing Date *</label>
                                <input type="date" value="" class="form-control" name="manufacturing_date" placeholder="Per pack quantity">
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
                Requestor: {
                    required: true,
                },
                Department: {
                    required: true,
                },
                Date: {
                    required: true,
                },
                 email: {
                     email: true,
                },
                PRSR: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });

    
  });
</script>


@stop