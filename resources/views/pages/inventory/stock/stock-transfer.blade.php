@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Stock Transfer Order</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Stock Transfer Order
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/Stock/transfer-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Stock Transfer Order</button> 
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
                                                <label style="font-size: 12px;">STO NUMBER</label>
                                                <input type="text" value="{{request()->get('sto_number')}}" name="sto_number" id="sto_number" class="form-control" placeholder="STO NUMBER">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label style="font-size: 12px;">Created</label>
                                                <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEM CODE">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label style="font-size: 12px;">STO date</label>
                                                <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEM CODE">
                                            </div><!-- form-group -->
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
                        <th>STO Number</th>
                        <th>Created By</th>
                        <th>Created at</th>
                        <th>Action</th>
					</tr>
				</thead>
				<tbody>
                 @foreach($data['sto'] as $sto)
                    <tr>
                        <td>{{$sto['sto_number']}}</td>
                        <td>{{$sto['f_name']}} {{$sto['l_name']}}</td>
                        <td>{{date('d-m-Y', strtotime($sto['created_at']))}}</td>
                        <td>{{--<a class="badge badge-info sto-edit" id="sto-edit" style="font-size: 13px;" data-toggle="modal" stoId="{{$sto['id']}}"   data-target="#myModal" ><i class="fas fa-edit"></i> Edit</a>--}}
                        <a class="badge badge-success" style="font-size: 13px;" href="{{url('inventory/Stock/transfer/items/'.$sto['id'])}}" ><i class="fa fa-eye"></i> Items</a></td>
                    </tr>
                    @endforeach   
				</tbody>
			</table>
			<div class="box-footer clearfix">
            {{ $data['sto']->appends(request()->input())->links() }}
		   </div> 
		</div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->
    <div id="myModal" class="modal">
        <div class="modal-dialog modal-md" role="document">
            <form id="form1" method="post" action="{{url('inventory/stock-transfer-edit')}}" autocomplete="off">
                {{ csrf_field() }} 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">#Edit Stock Transfer Order (<span class="stoNumber"></span>)</h4>
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
                                <input type="hidden" name="stoId"  id="sto_Id"  class="sto_Id">
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
        $('body').on('click', '#sto-edit', function (event) {
            event.preventDefault()
            var sto_id = $(this).attr('stoId');
            //alert(sto_id);
            $('.quantity').val('');
            $('.sto_Id').val('');
            $('#quantity-error').empty();
            $.ajax ({
                    type: 'GET',
                    url: "{{url('getSingleSTO')}}",
                    data: { sto_id: '' + sto_id + '' },
                    success : function(data) {
                        $('.stoNumber').html(data['sto_number']);
                        $('.item').val(data['item_code']);
                        $('.quantity').val(data['quantity']);
                        $('.sto_Id').val(data['id']);
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
		var sto_number = $('#sto_number').val();
		var item_code = $('#item_code').val();
		var lot_number = $('#lot_number').val();
        var supplier = $('#supplier').val();
		if(!sto_number  & !item_code & !lot_number & !supplier)
		{
			e.preventDefault();
		}
	});
</script>
@stop