@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Stock From Production</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Stock From Production
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/Stock/FromProduction-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Stock From Production</button> 
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
                                                <label>SRP No:</label>
                                                <input type="text" value="{{request()->get('mac_no')}}" name="mac_no" id="mac_no" class="form-control" placeholder="MAC NO">
                                            
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>DEPARTMENT:</label>
                                                <input type="text" value="{{request()->get('miq_no')}}" name="miq_no" id="miq_no" class="form-control" placeholder="MIQ No"> 
                                                
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">ISSUE PERSON</label>
                                                <input type="text" value="{{request()->get('item')}}" id="item" class="form-controll" name="item" placeholder="ITEM">
                                                
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
                        <th>SRP Number</th>
                        <th>Item Code</th>
                        <th>Item Type</th>
						<th>Lot Number</th>
                        <th>Quantity</th>
                        <th>Supplier</th>
                        <th>Action</th>
					</tr>
				</thead>
				<tbody>
                    @foreach($data['srp'] as $srp)
                    <tr>
                        <td>{{$srp['srp_number']}}</td>
                        <td>{{$srp['item_code']}}</td>
                        <td>{{$srp['type_name']}}</td>
                        <td>{{$srp['lot_number']}}</td>
                        <td>{{$srp['quantity']}} {{$srp['unit_name']}}</td>
                        <td>{{$srp['vendor_name']}}</td>
                        <td><a class="badge badge-info sip-edit" id="sip-edit" style="font-size: 13px;" data-toggle="modal" sipId="{{$srp['id']}}" sip="{{$srp['sip_number']}}" item="{{$srp['item_code']}}" qty="{{$srp['quantity']}}" data-target="#myModal" ><i class="fas fa-edit"></i> Edit</a>
                        <a class="badge badge-danger" style="font-size: 13px;" href="{{url('inventory/Stock/ToProduction/delete/'.$srp['id'])}}" onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a></td>
                    </tr>
                    @endforeach
				</tbody>
			</table>
			<div class="box-footer clearfix">
            {{ $data['srp']->appends(request()->input())->links() }}
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
    $(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });
    $('.search-btn').on( "click", function(e)  {
		var miq_no = $('#miq_no').val();
		var invoice_no = $('#invoice_no').val();
		var from = $('#from').val();
        var supplier = $('#supplier').val();
        var prepared = $('#prepared').val();
		if(!miq_no  & !invoice_no & !from & !supplier & !prepared)
		{
			e.preventDefault();
		}
	});
</script>
@stop