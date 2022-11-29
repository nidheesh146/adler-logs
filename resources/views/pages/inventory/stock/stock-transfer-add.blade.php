@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Add Stock Transfer Order</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Add Stock Transfer Order
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				
	  		</div>
		</h4>
		<div class="az-dashboard-nav">
			<nav class="nav"> </nav>	
		</div>
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
        <div class="row row-sm mg-b-20 mg-lg-b-0">
            <div class="table-responsive" style="margin-bottom: 13px;">
                <table class="table table-bordered mg-b-0">
                    <tbody>
                        <tr>
                            <style>
                                .select2-container .select2-selection--single {
                                    height: 26px;
                                    width: 122px;
                                }
                                .select2-selection__rendered {
                                    font-size:12px;
                                }
                            </style>
                            <form autocomplete="off">
                                <th scope="row">
                                    <div class="row filter_search" style="margin-left: 0px;">
                                       <div class="col-sm-10 col-md- col-lg-10 col-xl-12 row">
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label style="font-size: 12px;">ITEM CODE</label>
                                                <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEM CODE">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label style="font-size: 12px;">LOT NUMBER</label>
                                                <input type="text" value="{{request()->get('lot_number')}}" name="lot_number" id="lot_number" class="form-control" placeholder="LOT NUMBER"> 
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label style="font-size: 12px;">SUPPLIER</label>
                                                <input type="text" value="{{request()->get('supplier')}}" id="supplier" class="form-control" name="supplier" placeholder="SUPPLIER">
                                            </div>
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                            <!-- <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                @endif
                                            </div> -->
                                        </div>
                                    </div>
                                </th>
                            </form>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
		<form method="post" action="{{url('inventory/stock/transfer-order')}}">
            {{ csrf_field() }}
		    <div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1">
                    <thead>
                        <tr>
                            <th></th>
                            <th>SIP Number</th>
                            <th>Item Code</th>
                            <th>Item Type</th>
                            <th>Lot Number</th>
                            <th>Quantity</th>
                            <th>Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data['items'] as $item)
                        <tr>
                        <td><input type="checkbox" class="sir_id" id="sir_id" name="sir_id[]" value="{{$item['id']}}"></td>
                        <td>{{$item['sir_number']}}</td>
                        <td>{{$item['item_code']}}</td>
                        <td>{{$item['type_name']}}</td>
                        <td>{{$item['lot_number']}}</td>
                        <td>{{$item['quantity']}} {{$item['unit_name']}}</td>
                        <td>{{$item['vendor_name']}}</td>
                        </tr>
                        @endforeach              
                        
                    </tbody>
                </table>
                <div class="box-footer clearfix">
                {{ $data['items']->appends(request()->input())->links() }}
                </diV>
            </div> 
            <br/>
            <div class="form-devider"></div>
            @if(count($data['items'])>0)
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                            Save 
                        </button>
                    </div>
                </div>
            @endif
            
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
    $(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });
    $('.search-btn').on( "click", function(e)  {
		var lot_number = $('#lot_number').val();
		var item_code = $('#item_code').val();
        var supplier = $('#supplier').val();
		if(!lot_number  & !item_code & !supplier)
		{
			e.preventDefault();
		}
	});
</script>
@stop