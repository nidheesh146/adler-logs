@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="{{url('inventory/quotation')}}" style="color: #596881;">QUOTATION</a></span> 
                <span><a href="{{url('inventory/quotation')}}" style="color: #596881;">REQUEST FOR QUOTATION </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                Request For Quotation
            </h4>
            <div class="az-dashboard-nav">
                
            </div>

			<div class="row">                   
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                    @endif
                    @if(!empty($data['error']))
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                     {{ $data['error'] }}
                   </div>
                    @endif                   
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST"  action="{{ url('inventory/add/quotation') }}" >
                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Request for Quotation  
                                </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="exampleInputEmail1">RQ NO: *</label>
                                <input type="text" class="form-control" name="rq_no"  placeholder="Enter QR NO">
                            </div> 

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Requestor *</label>
                                <input type="text" class="form-control" readonly value="{{ (session('user')['employee_id'])}}" name="Requestor" placeholder="Requestor">
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Date *</label>
                                <input type="date"  class="form-control datepicker" name="date" placeholder="Date">
                            </div>


                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Supplier *</label>
                                <select class="form-control Supplier" name="Supplier">
                                        <option value="">--- select one ---</option>
                                </select>
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Delivery Schedule *</label>
                                <input type="date"  class="form-control datepicker" name="delivery" placeholder="Date">
                            </div>

                        </div> 
                      
                        
                        <div class="form-devider"></div>

                        <div class="table-responsive">
                            <h4> Purchase Requisition Approved List </h4>
				            <table class="table table-bordered mg-b-0" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Item code </th>
                                        <th>Supplier</th>
                                        <th>Actual order Qty</th>
                                        <th>Rate</th>
                                        <th>Discount %</th>
                                        <th>GST %</th>
                                        <th>Currency</th>
                                        <th>Net value </th>
                                        <th>Actual Order Qty</th>
                                    </tr>
                                </thead>
                                <tbody >
                                @foreach($requisition_items as $item)
                                <tr>
                                    <td><input type="checkbox" id="purchase_requisition_item" name="purchase_requisition_item[]" value="{{$item['id']}}">{{$item['id']}}</td>
                                    <th>{{$item['item_code']['item_code']}}</th>
                                    <td>{{$item['supplier']['vendor_name']}}</td>
                                    <td>{{$item['actual_order_qty']}}</td>
                                    <td>{{$item['rate']}}</td>
                                    <td>{{$item['discount_percent']}}</td>
                                    <td>{{$item['gst']}}</td>
                                    <td>{{$item['currency']}}</td>
                                    <td>{{$item['net_value']}}</td>	
                                    <td>{{$item['actual_order_qty']}}</td>						 
                                </tr>	
                                @endforeach
                                </tbody>
                            </table>
				<div class="box-footer clearfix">
					<style>
					.pagination-nav {
						width: 100%;
					}
					
					.pagination {
						float: right;
						margin: 0px;
						margin-top: -16px;
					}
					</style>
				</div>
                <br/>
                <div class="form-devider"></div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><i class="fas fa-save"></i>
                            Save 
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
	</div>
	<!-- az-content-body -->
</div>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script>
    $('.Supplier').select2({
                    placeholder: 'Choose one',
                    searchInputPlaceholder: 'Search',
                    minimumInputLength: 3,
                    allowClear: true,
                    ajax: {
                    url: "{{url('inventory/suppliersearch')}}",
                    processResults: function (data) {
                      return {
                        results: data
                      };
                    }
                  }
                });
</script>

@stop
