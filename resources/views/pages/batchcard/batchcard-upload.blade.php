@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="{{url('batchcard/batchcard-upload')}}" style="color: #596881;">BATCHCARD </a></span> 
                <span><a href="{{url('batchcard/batchcard-upload')}}" style="color: #596881;">BATCHCARD UPLOAD</a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">BatchCard Upload
            <button  style="float: right" class="badge badge-pill badge-info pull-right">
            <a href="{{ asset('uploads/batchcard_sample.xlsx') }}" style="color:white;"><i class="fas fa-download"></i>
            Download Template</a>
            </button></h4>
            <div class="form-devider"></div>
			<div class="row">     
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                    @endif
                    @if(!empty(Session::get('error')))
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ Session::get('error') }}
                    </div>
                    @endif                   
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST"  action="{{url('batchcard/batchcard-upload')}}" id="commentForm" enctype='multipart/form-data'>
               
                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Select File </label>
                                
                                <input type="file"  accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control" name="file" value="" >
                            </div> 
                        </div> 
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <button type="submit" style="float: right;" class="btn btn-primary btn-rounded pull-right"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-upload"></i>

                                    Upload
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
    $("#commentForm").validate({
        rules: {
                file: {
                    required: true,
                    extension: "xlsx|xls|xlsm",
                },
                messages: {
                    file: {
                        required: "file .xlsx, .xlsm, .xls only.",
                        extension: "Please upload valid file formats .xlsx, .xlsm, .xls only.",
                    }
                },
                // submitHandler: function(form) {
                // //$('.spinner-button').show();
                // form.submit();
            }
        });

</script>

@stop