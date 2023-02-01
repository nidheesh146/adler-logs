@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Stock Return From Production</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Stock Return From Production
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/Stock/FromProduction-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Stock Return From Production</button> 
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
                                                <label style="font-size: 12px;">SIR NUMBER</label>
                                                <input type="text" value="{{request()->get('sir_number')}}" name="sir_number" id="sir_number" class="form-control" placeholder="SIR NUMBER">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label style="font-size: 12px;">ITEM CODE</label>
                                                <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEM CODE">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
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
		
		<div class="table-responsive">
			<table class="table table-bordered mg-b-0" id="example1">
				<thead>
					<tr>
                        <th>SIR Number</th>
                        <th>Item Code</th>
                        <th>Item Type</th>
						<th>Lot Number</th>
                        <th>Quantity</th>
                        <th>Action</th>
					</tr>
				</thead>
				<tbody>
                    @foreach($data['sir'] as $srp)
                    <tr>
                        <td>{{$srp['sir_number']}}</td>
                        <td>{{$srp['item_code']}}</td>
                        <td>{{$srp['type_name']}}</td>
                        <td>{{$srp['lot_number']}}</td>
                        <td>{{$srp['qty_to_return']}} {{$srp['unit_name']}}</td>
                        <td><a class="badge badge-info sip-edit" id="sip-edit" style="font-size: 13px;" data-toggle="modal" sirId="{{$srp['id']}}" sip="{{$srp['sir_number']}}" item="{{$srp['item_code']}}" qty="{{$srp['quantity']}}" data-target="#myModal" ><i class="fas fa-edit"></i> Edit</a>
                        <a class="badge badge-danger" style="font-size: 13px;" href="{{url('inventory/Stock/FromProduction/delete/'.$srp['id'])}}" onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a></td>
                    </tr>
                    @endforeach
				</tbody>
			</table>
			<div class="box-footer clearfix">
            {{ $data['sir']->appends(request()->input())->links() }}
		   </div> 
		</div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->
    <div id="myModal" class="modal">
        <div class="modal-dialog modal-md" role="document">
            <form id="form1" method="post" action="{{url('inventory/stock-FromProduction-edit')}}" autocomplete="off">
                {{ csrf_field() }} 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">#Edit Stock Return From Production (<span class="sirNumber"></span>)</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <table>
                                    <tr>
                                    <td>Item Code : </td><td><input type="text" class="item form-control" disabled></td>
                                    </tr>
                                    <tr> 
                                        <td>
                                        Quantity :&nbsp;
                                        </td>
                                        <td>
                                        <div class="input-group">
                                            <input type="text" class="quantity form-control" id="quantity" name="quantity"  aria-describedby="unit-div">
                                            <div class="input-group-append">
                                                <span class="input-group-text unit-div" id="unit-div"></span>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" name="sirId"  id="sir_Id"  class="sir_Id">
                            </div>
                        </div>
                        <!-- <div class="form-devider"></div> -->
                    </div>
                    <div class="modal-footer">
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-6">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

	
      

<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script>
    $(document).ready(function() {
        $('body').on('click', '#sip-edit', function (event) {
            event.preventDefault()
            var sir_id = $(this).attr('sirId');
            $('.quantity').val('');
            $('.sir_Id').val('');
            $('#quantity-error').empty();
            $.ajax ({
                    type: 'GET',
                    url: "{{url('getSingleSIR')}}",
                    data: { sir_id: '' + sir_id + '' },
                    success : function(data) {
                        $('.sirNumber').html(data['sir_number']);
                        $('.item').val(data['item_code']);
                        $('.quantity').val(data['quantity']);
                        $('.sir_Id').val(data['id']);
                        $('.unit-div').html(data['unit_name']);
                    }
                });
           

        });
        $("#form1").validate({
            rules: {
                quantity: {
                    required: true,
                    number: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
    $('.search-btn').on( "click", function(e)  {
		var sir_number = $('#sir_number').val();
		var item_code = $('#item_code').val();
		var lot_number = $('#lot_number').val();
        var supplier = $('#supplier').val();
		if(!sir_number  & !item_code & !lot_number & !supplier)
		{
			e.preventDefault();
		}
	});
</script>
@stop