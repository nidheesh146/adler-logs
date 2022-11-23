@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
            <span><a href="{{ url('inventory/supplier-invoice') }}">SUPPLIER INVOICE</a></span>
            <span>Add  SUPPLIER INVOICE</span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Add Supplier Invoice 
		  <div class="right-button">
			  
		  <div>  
		  </div> 
	  </div>
	</h4>
		<!-- <div class="az-dashboard-nav">
			<nav class="nav"> </nav>	
		</div> -->

		@if (Session::get('success'))
		<div class="alert alert-success " style="width: 100%;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="icon fa fa-check"></i> {{ Session::get('success') }}
		</div>
		@endif
		@include('includes.purchase-details.purchase-work-order-tab')
		<div class="tab-content">
		<div class="row row-sm mg-b-20 mg-lg-b-0">
            <div class="table-responsive" style="margin-bottom: 13px;">
                <table class="table table-bordered mg-b-0">
                    <tbody>
                        <tr>
                            <style>
                                .select2-container .select2-selection--single {
                                    height: 26px;
                                    /* width: 122px; */
                                }
                                .select2-selection__rendered {
                                    font-size:12px;
                                }
                            </style>
                            <form autocomplete="off">
                                <th scope="row">
                                    <div class="row filter_search" style="margin-left: 0px;">
                                       <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
                                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                
                                            </div>
                                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                <label style="font-size: 12px;">@if(request()->get('order_type')=='wo') WO @else PO @endif No:</label>
                                                <input type="text" value="{{request()->get('po_no')}}" name="po_no" id="po_no" class="form-control" placeholder="@if(request()->get('order_type')=='wo') WO NO @else PO NO @endif"> 
												<input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
                                            </div><!-- form-group -->        
											<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                <label  style="font-size: 12px;">PO Date </label>
                                                <input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="Created at (MM-YYYY)">
                                            </div> 
                                                                 
                                        </div>
                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                @endif
                                            </div> 
                                        </div>
                                    </div>
                                </th>
                            </form>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

		
	   

		<div class="tab-pane active show " id="purchase">
			<div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
						
							<th style="width:120px;">@if(request()->get('order_type')=="wo") WO @else PO @endif number :</th>
							<th>PO date</th>
							<th>Supplier</th>
							<th>Created Date</th>
							<th>Created By</th>
							<th>Action</th>
						
						</tr>
					</thead>
					<tbody>
						@foreach($data['po_data'] as $po)
                        <tr>
                            <td>{{$po->po_number}}</td>
                            <td>{{$po->po_date}}</td>
                            <td>{{$po->vendor_name}}</td>
                            <td>{{date('d-m-Y',strtotime($po->created_at)) }}</td>
                            <td>{{$po->f_name}} {{$po->l_name}}</td>
                            <td><a href="" data-toggle="modal"  data-target="#invoiceAddModal" class="invoice-add-model badge badge-primary"   id="invoice-add-model" po="{{$po['po_number']}}" poId="{{$po['id']}}" style="width:70px;padding:4px;margin-top:2px;color:white;">
                                <i class="fa fa-plus"> Invoice</i> 
                                </a>
                            </td>
                        </tr>
                        @endforeach
					</tbody>
				</table>
				<div class="box-footer clearfix">
                    {{ $data['po_data']->appends(request()->input())->links() }}
				</div>
		
			</div>
		</div>
		
	</div>
</div>
	<!-- az-content-body -->

    <div id="invoiceAddModal" class="modal">
        <div class="modal-dialog modal-xl" role="document">
            <form id="excess-order-form" method="post" action="{{url('inventory/supplier-invoice-add')}}" autocomplete="off">
                {{ csrf_field() }} 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">#Add Supplier Invoice-@if(request()->get('order_type')=="wo") Work Order @else Purchase Order @endif <span class="po_number"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                <label>
                                   Invoice Number
                                </label>
                                <input type="text" name="invoice_number" id="invoice_number"  placeholder="Invoice Number">
                                <input type="hidden" name="po_id" id="po_id" value="">
                                <span class="rq-number"></span>
                            </div>
                            <div class="form-group col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                  <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                          Save
                                  </button>
                              </div>
                        </div>
                        <div class="form-devider"></div>
                        <div class="row binding">
                        </div>
                             
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                       
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

<script>
  $(function(){
    'use strict'
    var date = new Date();
    date.setDate(date.getDate());
	$(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });

  });
  $(document).ready(function() {
        $('body').on('click', '#invoice-add-model', function (event) {
            event.preventDefault();
            $('.binding').empty();
            $('#invoice_number').val("");
            var po_id = $(this).attr('poId');
            $('#po_id').val(po_id);
            $.ajax ({
                    type: 'GET',
                    url: "{{url('inventory/getPurchaseOrderItem')}}",
                    data: { po_id: '' + po_id + '' },
                    success : function(data) {
                        $('.binding').append(data);
                    }
                });

        });
  });

  
	$('.search-btn').on( "click", function(e)  {
		var supplier = $('#supplier').val();
		var invoice_no = $('#invoice_no').val();
		var po_no = $('#po_no').val();
		var from = $('#from').val();
		if(!supplier & !invoice_no & !po_no & !from)
		{
			e.preventDefault();
		}
	});
</script>


@stop