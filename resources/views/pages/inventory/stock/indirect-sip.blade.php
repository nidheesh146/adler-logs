@extends('layouts.default')
@section('content')

@inject('stock', 'App\Http\Controllers\Web\PurchaseDetails\StockController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #97a3b9;">Stock Issue To Production</a></span>
             <span><a href="" style="color: #596881;">Stock Issue To Production - Indirect</a></span>
		</div><br/>
		<h4 class="az-content-title" style="font-size: 20px;">Stock Issue To Production - Indirect
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
            <nav class="nav nav-tabs">
                <a class="nav-link "  href="{{url('inventory/Stock/ToProduction/Direct')}}">Stock Issue To Production -Direct</a>
                <a class="nav-link active"  href="#">Stock Issue To Production -Indirect</a>
            </nav> 
        </div><br/>
        <div class="tab-content">
            <div id="tabCont1" class="tab-pane active show"">
                <form method="post" id="commentForm" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Item Code*</label>
                            <select class="form-control  item_code" name="item_code">
                            </select> 
                            <label id="item_code-error" class="error" for="item_code" style="display:none;">This field is required.</label>
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>Description</label>
                            <textarea type="text" readonly class="form-control" id="item_description" name="Description"                            placeholder="Description"></textarea>
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>MAC No</label>
                            <input type="text"  class="form-control" name="mac_no" id="mac_no" readonly>
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>Transaction Slip No</label>
                            <input type="text"  class="form-control" name="transaction_slip" id="transaction_slip" >
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>Accepted Quantity(MAC Quantity)</label>
                            <div class="input-group mb-3">
                                <input type="hidden" name="mac_item_id" id="mac_item_id">
                                <input type="text" class="form-control" name="accepted_qty" id="accepted_qty"  aria-describedby="unit-div1" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div1">Unit</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>Quantity To Production</label>
                            <div class="input-group mb-6">
                                <input type="text" class="form-control" name="qty_to_production" id="qty_to_production" placeholder="Quantity To Production" aria-describedby="unit-div2">
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div2">Unit</span>
                                </div>
                            </div>
                            <label id="qty_to_production-error" class="error" for="qty_to_production" style="display:none;">This field is required.</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Work Centre*</label>
                            <select class="form-control  work_centre" name="work_centre" id="work_centre">
                                <option></option>
                                @foreach($work_centre as $centre)
                                <option value="{{ $centre['id'] }}">{{$centre['centre_code']}}</option>
                                @endforeach
                            </select> 
                        </div>
                    </div>
                    <div class="form-devider"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit submit-btn" id="submit-btn" class="btn btn-primary btn-rounded " style="float: right;" disabled="disabled">
                                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> 
                                <i class="fas fa-save"></i>
                                        Save
                            </button>
                        </div>
                    </div>
                <form>
            </div>
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
    $('.work_centre').select2({
        placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true
    });
    $(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });
    $('.item_code').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/indirect/itemcodesearch') }}",
          processResults: function (data) {
            return { results: data };

          }
        }
    }).on('change', function (e) 
    {
         $("#item_description").text('');
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.discription){
                $("#item_description").text(res.discription);
            }
            $.get("{{ url('inventory/stock/item-mac-info') }}?item_id="+res.id,function(data)
            {
                if(data)
                {
                $('#unit-div1').text(data['unit_name']);
                $('#unit-div2').text(data['unit_name']);
                $('#mac_no').val(data['mac_number']);
                $('#mac_item_id').val(data['mac_item_id']);
                $('#accepted_qty').val(data['available_qty']);
                $('#submit-btn').removeAttr('disabled');
                }
                else
                {
                    alert('Out of Stock');
                }
               // alert data;
            });
        }

    });  

    $("#commentForm").validate({
            rules: {
                item_code: {
                    required: true,
                },
                qty_to_production: {
                    required: true,
                    number: true,
                },
                transaction_slip:{
                    required: true, 
                },
                work_centre:{
                    required: true, 
                }
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
     
          

</script>
@stop