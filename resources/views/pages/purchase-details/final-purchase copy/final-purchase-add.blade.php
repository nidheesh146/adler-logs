@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\PurchaseController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="">Order</a></span>
				 <span><a href=""> Final @if(request()->get('order_type')=='wo') Work @else Purchase @endif Order</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;"> Final @if(request()->get('order_type')=='wo') Work @else Purchase @endif Order
              <div class="right-button">
              <div>  
              </div>
          </div>
        </h4>
		<!-- @include('includes.purchase-details.pr-sr-tab') -->
      
			
		   @if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
		   </div>
		   @endif
		   
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
										<form autocomplete="off"  id="formfilter">
											<th scope="row">
												<div class="row filter_search" style="margin-left: 0px;">
                                                    <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
														<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
															<label  style="font-size: 12px;">Supplier</label>
															<input type="text" value="{{request()->get('supplier')}}"  class="form-control " name="supplier" placeholder="Supplier" >
														</div>
														<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
															<label>RQ No:</label>
															<input type="text" value="{{request()->get('rq_no')}}" name="rq_no"  id="rq_no" class="form-control" placeholder="RQ NO">
														</div><!-- form-group -->
														<input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">					
													</div>
													<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
														<!-- <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;"> -->
															<label style="width: 100%;">&nbsp;</label>
															<button type="submit" class="badge badge-pill badge-primary search-btn" 
															onclick="document.getElementById('formfilter').submit();"
															style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
															@if(count(request()->all('')) > 1)
																<a href="{{url()->current()}}" class="badge badge-pill badge-warning"
																style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
															@endif
														<!-- </div>  -->
													</div>
												</div>
											</th>
										</form>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				<div class="tab-pane tab-pane active  show" id="purchase">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                <i class="fas fa-address-card"></i>  Add Final @if(request()->get('order_type')=='wo') Work @else Purchase @endif Order 
                            </label>
                            {{-- <div class="form-devider"></div> --}}
                        </div>
                    </div>
                    <form method="post" action="{{url('inventory/final-purchase-insert')}}">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
					<div class="table-responsive">
						<table class="table table-bordered mg-b-0" id="example1">
							<thead>
                                <tr>
                                    <th colspan="6">
										
                                    </th>
                                </tr>
								<tr>
                                    <th></th>
									<th style="width:120px;">RQ NO:</th>
									<th>Date</th>
									<th>delivery Schedule </th>
									<th>Suppliers</th>
									{{-- <th>Item count</th> --}}
								</tr>
							</thead>
							<tbody >
                            @foreach($data['quotation'] as $item)
							    <tr>
									<td><input type="checkbox" class="quotation_id" id="quotation_id" name="quotation_id[]" value="{{$item['quotation_id']}}"></td> 
									<td><input type="hidden" id="quotation_no" name="quotation_no[]" value="{{$item['rq_no']}}">{{$item['rq_no']}}</td>
									<td><input type="hidden" id="date" name="date[]" value="{{$item['date'] ? date('d-m-Y',strtotime($item['date'])) : '-'}}">{{$item['date'] ? date('d-m-Y',strtotime($item['date'])) : '-'}}</td>
									<td><input type="hidden" id="delivery_schedule" name="delivery_schedule[]" value="{{$item['delivery_schedule'] ? date('d-m-Y',strtotime($item['delivery_schedule'])) : '-'}}">{{$item['delivery_schedule'] ? date('d-m-Y',strtotime($item['delivery_schedule'])) : '-'}}</td>
									<td>
										@if($item['quotation_id'])

										<?php
											$supp = $SupplierQuotation->get_supplier($item['quotation_id']);
											echo $supp['supplier'];
										?>
										@endif
									</td>
								</tr>  
                            @endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
                        {{ $data['quotation']->appends(request()->input())->links() }}
						</div> 
                        <br/>
                        <div class="form-devider"></div>
                        @if(count($data['quotation'])>0)
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                        Save 
                                    </button>
                                </div>
                            </div>
                        @endif
					</div>
                    </form>
				</div>
			</div>
		</div>
	</div>
	<!-- az-content-body -->
</div>


<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
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

    //$('#prbody').show();
  });
  
	$('.search-btn').on( "click", function(e)  {
		//var supplier = $('#supplier').val();
		var rq_no = $('#rq_no').val();
		var po_no = $('#po_no').val();
		var from = $('#from').val();
		if(!rq_no & !po_no & !from)
		{
			e.preventDefault();
		}
	});

	
                let igst = $(this).val();
                let igst_percent = $(this).find('option:selected').text();
                var igst_val = parseInt(igst_percent.split('%', 1)[0]);
               
                let Rate = $('#Rate').val();
                let actual_qty = $('#ActualorderQty').val();
                let total = Rate*actual_qty;
                let Discount = $('#Discount').val() ? $('#Discount').val() : 0;
                let discount_rate = (actual_qty*Rate*Discount)/100;
                let netvalue = (total-discount_rate);

                new_net_val = (netvalue*igst_val/100)+netvalue;
                $('#Netvalue').val(new_net_val.toFixed(2));

                $('.append-option').remove();
                $('.edit-zero').remove();
                $('#gst-id').val('');
                // $('#CGST').load();
                // $('#SGST').load();
                $.ajax ({
                    type: 'GET',
                    url: "{{url('getSGSTandCGST')}}",
                    data: { id: '' + igst + '' },
                    success : function(data) {
                        $('#gst-id').val(data.id);
                       $('#SGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.sgst + '%</option>');
                       $('#CGST').append('<option class="append-option" value=' + data.id + ' selected>' + data.cgst + '%</option>');
    
                    }
                });
                
            });
            
            


</script>


@stop