@extends('layouts.default')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

@inject('stock', 'App\Http\Controllers\Web\PurchaseDetails\StockController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Config setting</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Config setting
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				
	  		</div>
		</h4>
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
        <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
            {{--<nav class="nav nav-tabs">
                <a class="nav-link active" href="">Config setting</a>
                <a class="nav-link " href="{{url('inventory/Stock/ToProduction/Packing')}}">Stock Issue To Production -Packing</a>
                <a class="nav-link"  href="{{url('inventory/Stock/ToProduction/Indirect')}}">Stock Issue To Production -Indirect</a>
            </nav>--}}
        </div><br/>
		<form method="post" action="{{url('settings/config/add')}}">
            {{ csrf_field() }}
            <div class="row">
                
                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label>Name </label>
                    <input type="text" class="form-control" readonly name="setting_name" id="setting_name" placeholder="Setting name" aria-describedby="unit-div2" value="{{$data->name}}">
                </div><!-- form-group -->
                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label>Value </label>
                    <input type="number" class="form-control" name="setting_value" id="setting_value" value="{{$data->value}}" placeholder="Setting value" aria-describedby="unit-div2">
                </div>
                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label>Rev Date </label>
                    <input type="text" class="form-control datepicker" name="rev_date" id="setting_value"  value="{{date('d-m-Y',strtotime($data->re_date))}}" placeholder="Setting value" aria-describedby="unit-div2">
                </div>
                
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Rev No</label>
                    <input type="text"  class="form-control" name="rev_no" value="{{$data->rev_number}}" id="rev_no" >
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Quotation head 1</label>
                    <input type="text"  class="form-control" name="qt_head1" value="{{$data->qtn_head1}}" id="qt_head1" >
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Email id</label>
                    <input type="email"  class="form-control" name="qt_mail1" id="qt_mail1"  value="{{$data->qtn_email_id1}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Quotation head 2</label>
                    <input type="text"  class="form-control" name="qt_head2" id="qt_head2"value="{{$data->qtn_head2}}" >
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Email id</label>
                    <input type="email"  class="form-control" name="qt_mail2" id="qt_mail2" value="{{$data->qtn_email_id2}}" >
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Quotation head 3</label>
                    <input type="text" class="form-control" name="qt_head3" id="qt_head3"  value="{{$data->qtn_head3}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Email id</label>
                    <input type="email"  class="form-control" name="qt_mail3" id="qt_mail3" value="{{$data->qtn_email_id3}}" >
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Purchase head 1</label>
                    <input type="text"  class="form-control" name="pur_head1" id="pur_head1"  value="{{$data->purchase_head1}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Email id</label>
                    <input type="email"  class="form-control" name="pur_mail1" id="pur_mail1" value="{{$data->pur_email_id1}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Purchase head 2</label>
                    <input type="text"  class="form-control" name="pur_head2" id="pur_head2"value="{{$data->purchase_head2}}" >
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Email id</label>
                    <input type="email"  class="form-control" name="pur_mail2" id="pur_mail2" value="{{$data->pur_email_id2}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Purchase head 3</label>
                    <input type="text"  class="form-control" name="pur_head3" id="pur_head3" value="{{$data->purchase_head3}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Email id</label>
                    <input type="email"  class="form-control" name="pur_mail3" id="pur_mail3" value="{{$data->pur_email_id3}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>FGS head 1</label>
                    <input type="text"  class="form-control" name="fgs_head1" id="fgs_head1"  value="{{$data->fgs_head1}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Email id</label>
                    <input type="email"  class="form-control" name="fgs_mail1" id="fgs_mail1" value="{{$data->fgs_email_id1}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>FGS head 2</label>
                    <input type="text"  class="form-control" name="fgs_head2" id="fgs_head2"value="{{$data->fgs_head2}}" >
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Email id</label>
                    <input type="email"  class="form-control" name="fgs_mail2" id="fgs_mail2" value="{{$data->fgs_email_id2}}" >
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>FGS head 3</label>
                    <input type="text"  class="form-control" name="fgs_head3" id="fgs_head3" value="{{$data->fgs_head3}}">
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Email id</label>
                    <input type="email"  class="form-control" name="fgs_mail3" id="fgs_mail3" value="{{$data->fgs_email_id3}}">
                    <input type="hidden"  class="form-control" name="config_id" id="config_id" value="{{$data->id}}">
                </div>
                <br/>
            </div>
            
            
            <div class="form-devider"></div>
            
           
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary btn-rounded submitbtn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                        Save 
                    </button>
                </div>
           
           
        </form>
		</div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->
    
      

<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>



<script>
    $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });

  //  .datepicker('update', new Date());
    $('.datepicker').mask('99-99-9999');
});
    $('.work_centre').select2({
        placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true
    });
   
   
   
   
   

</script>
@stop