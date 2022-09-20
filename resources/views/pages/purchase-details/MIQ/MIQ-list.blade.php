@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Material Inwards To Quarantine(MIQ)</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Material Inwards To Quarantine(MIQ)
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/MIQ-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> MIQ</button> 
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
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>MIQ No:</label>
                                                <input type="text" value="{{request()->get('lot_no')}}" name="lot_no" id="lot_no" class="form-control" placeholder="LOT NO">
                                            
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>Invoice No:</label>
                                                <input type="text" value="{{request()->get('invoice_no')}}" name="invoice_no" id="invoice_no" class="form-control" placeholder="INVOICE NO"> 
                                                
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>Invoice Date:</label>
                                                <input type="text" value="{{request()->get('invoice_no')}}" name="invoice_no" id="invoice_no" class="form-control" placeholder="INVOICE NO"> 
                                                
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">Prepared By</label>
                                                <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier1" class="form-control" placeholder="SUPPLIER">
                                                
                                            </div>
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">Date</label>
                                                <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier1" class="form-control" placeholder="SUPPLIER">
                                                
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
						<th>MIQ NO</th>
                        <th>Invoice No.</th>
                        <th>Invoice Date</th>
						<th>Date</th>
						<th>Supplier</th>
						<th>Prepared By</th> 
						<th>Action</th>
					
					</tr>
				</thead>
				<tbody>
                    <tr>
                        <td>MQ12354</td>
                        <td>1234</td>
                        <td>18-09-2022</td>
                        <td>19-09-2022</td>
                        <td>supplier</td>
                        <td>admin admin</td>
                        <td><a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/MIQ/1/item')}}"  class="dropdown-item"><i class="fas fa-eye"></i> Item</a> 	</td>
                    </tr>
					
				</tbody>
			</table>
			<div class="box-footer clearfix">
				<style>
				.pagination-nav{
					width:100%;
				}
				.pagination{
					float:right;
					margin:0px;   
					margin-top: -16px;
				}

				</style>
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
<script src="<?= url('') ?>/js/additional-methods.js"></script>

@stop