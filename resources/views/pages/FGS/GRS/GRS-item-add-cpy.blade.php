@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Goods Reservation Slip(GRS)</span>
				 <span><a href="">
                 OEF Items
				</a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;margin-bottom: 20px">
            OEF Items 
            </h4>
            <div class="form-devider"></div>
            Following OEF items on selected stock location.<br/><br/>
            <form method="post" id="commentForm" novalidate="novalidate">
                {{ csrf_field() }}	
                @if(Session::get('error'))
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{Session::get('error')}}
                </div>
                @endif
                @if (Session::get('success'))
                <div class="alert alert-success " style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                </div>
                @endif
                @foreach ($errors->all() as $errorr)
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $errorr }}
                    </div>
                    @endforeach
                    <div class="tab-content"> 
                        <div class="tab-pane  active  show " id="purchase"> 
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0" >
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Product</th>
                                            <th>Description</th>
                                            <th>Actual Quantity</th>
                                            <th>Unreserved Quantity</th>
                                            <th>Rate</th>
                                            <th>Discount</th>
                                            <th>GST</th>
                                        </tr>                                                
                                    </thead>
                                    <tbody id="prbody1">
                                        @foreach($oef_items as $item)
                                            <tr>
                                                    <input type="hidden" id="grs_id" value="{{$grs_id}}" name="grs_id">
                                                    <td><input type="checkbox" name="oef_item_id[]" value="{{$item['id']}}"></td>
                                                    <td>{{$item['sku_code']}}</td>
                                                    <td>{{$item['discription']}}</td>
                                                    <td>{{$item['quantity']}} Nos</td>
                                                    <td>{{$item['quantity_to_allocate']}} Nos</td>
                                                    <td>{{$item['rate']}}</td>
                                                    <td>{{$item['discount']}}%</td>
                                                    <td>IGST:{{$item['igst']}}%<br/>
                                                        CGST:{{$item['cgst']}}%<br/>
                                                        SGST:{{$item['sgst']}}%
                                                    </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="box-footer clearfix">
                                    {{ $oef_items->appends(request()->input())->links() }}
                                </div>
                            </div>
				        </div>
			        </div>
                    <div class="form-devider"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true">
                                </span> 
                                <i class="fas fa-save"></i>
                                {{ request()->item ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </div>
                <form>
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
	$('#prbody1').show();
	$('#prbody2').show();
  });
  	
	$('.search-btn').on( "click", function(e)  {
		var grs_no = $('#grs_no').val();
		var oef_no = $('#oef_no').val();
		var from = $('#from').val();
		if(!grs_no  & !oef_no & !from)
		{
			e.preventDefault();
		}
	});
</script>


@stop