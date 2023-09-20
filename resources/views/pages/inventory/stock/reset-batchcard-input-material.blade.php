@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Reset Batchcard Input Material</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Reset Batchcard Input Material
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
        <div class="form-devider"></div>
		<form method="post" action="{{url('stock/product_input_material')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">Batchcard *</label>
                    <select class="form-control  batchcard" name="batchcard" id="batchcard">
                        <option>..select..</option>
                    </select>  
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">Product</label>
                    <input class="form-control" type="text" name="product" id="product" value="" readonly>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label>Product Description </label>
                    <textarea type="text" class="form-control" name="description" id="description" placeholder="Description" readonly value="" ></textarea>
                </div><!-- form-group -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">SKU Quantity</label>
                    <input class="form-control" type="text" name="quantity" id="quantity" value="" readonly>
                </div>
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <h5>Input Materials</h5>
                    <input type="hidden" name="batch_id" id="batch_id" value="0">
                    <input type="hidden" name="product_id" id="product_id" value="0">
                    <table class="table table-bordered mg-b-0 input-material">
                                                
                    </table> 
                </div>
            </div>
			 
            <div class="form-devider"></div>
            <div class="row savebtn" style="display:none;">
           
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary btn-rounded submitbtn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                        Save 
                    </button>
                </div>
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

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $('#batchcard').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
            allowClear: true,
          ajax: {
          url: "{{ url('stock/getbatchcard') }}",
          processResults: function (data) {
            return { results: data };

          }
        }
    }).on('change', function (e) 
    {
        $('.spinner-button').hide();
        $('.savebtn').hide();
         $("#description").val('');
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.discription){
                $("#description").val(res.discription);
            }
            if(res.quantity){
                $("#quantity").val(res.quantity);
            }
            if(res.sku_code){
                $("#product").val(res.sku_code);
            }
            $("#product_id").val(res.product_id);
            $("#batch_id").val(res.id);
            $.get("{{ url('stock/product_input_material') }}?product_id="+res.product_id+"&&batch_id="+res.id,function(data)
            {
                $('.input-material').html(data);
                $('.savebtn').show();
            });
        }

    });  
   

        
     
          

</script>
@stop