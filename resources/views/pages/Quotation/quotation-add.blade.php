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
                    @if (Session::get('error'))
                    <div class="alert alert-danger" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('error') }}
                    </div>
                    @endif
                    @if(!empty($data['error']))
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                     {{ $data['error'] }}
                   </div>
                    @endif                   
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" autocomplete="off" action="{{ url('inventory/add/quotation') }}" id="commentForm" >
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
                            <!-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">RQ NO: *</label>
                                <input type="text" class="form-control" name="rq_no"  placeholder="Enter QR NO">
                            </div>  -->

                            {{-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label>Requestor *</label>
                                <input type="text" class="form-control" readonly value="{{ (session('user')['employee_id'])}}" name="Requestor" placeholder="Requestor">
                            </div><!-- form-group --> --}}

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Date *</label>
                            <input type="text"  class="form-control datepicker" value="{{date("d-m-Y")}}" name="date" placeholder="Date">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Delivery Schedule *</label>
                                <input type="text"  class="form-control datepicker" value="{{date("d-m-Y")}}" name="delivery" placeholder="Date">
                            </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Supplier *</label>
                                <select class="form-control Supplier" name="Supplier[]" multiple="multiple">
                                        <option value="">--- select one ---</option>
                                </select>
                            </div><!-- form-group -->

                         

                        </div> 
                      
                        
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Purchase Requisition Approved List
                                </label>
                                {{-- <div class="form-devider"></div> --}}
                            </div>
                        </div>

                        <div class="table-responsive">
                            {{-- <h4> Purchase Requisition Approved List </h4> --}}
				            <table class="table table-bordered mg-b-0" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>PR No:</th>
                                        <th>Item code </th>
                                        <th>Supplier</th>
                                        <th>Rate</th>
                                        <th>Discount %</th>
                                        <th>GST %</th>
                                        <th>Currency</th>
                                        <th>Net value </th>
                                        <th>Actual Order Qty</th>
                                    </tr>
                                </thead>
                                <tbody >
                                @if($data['response']['purchase_requisition'][0])
                                @foreach($data['response']['purchase_requisition'] as $item)
                                <tr>
                                    <td><input type="checkbox" class="purchase_requisition_item" id="purchase_requisition_item" name="purchase_requisition_item[]" value="{{$item['id']}}"></td>
                                    <th>{{$item['purchase_reqisition_list'][0]['purchase_reqisition']['pr_no']}}</th>
                                    <th>{{$item['purchase_reqisition_list'][0]['item_code']['item_code']}}</th>
                                   <td>{{$item['purchase_reqisition_list'][0]['supplier']['vendor_name']}}</td>
                                    <td>{{$item['purchase_reqisition_list'][0]['rate']}}</td>
                                    <td>{{$item['purchase_reqisition_list'][0]['discount_percent']}}</td>
                                    <td>{{$item['purchase_reqisition_list'][0]['gst']}}</td>
                                    <td>{{$item['purchase_reqisition_list'][0]['currency']}}</td>
                                    <td>{{$item['purchase_reqisition_list'][0]['net_value']}}</td>	
                                    <td>{{$item['purchase_reqisition_list'][0]['actual_order_qty']}}</td>						 
                                </tr>	
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($data['response']))
                                @include('includes.pagination',['data'=>$data['response']])
                            @endif
                 
                <br/>
                <div class="form-devider"></div>

                @if($data['response']['purchase_requisition'][0])
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                            role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                            Save 
                        </button>
                    </div>
                </div>
                @endif

            </form>

        </div>
    </div>
    </div>
	</div>
	<!-- az-content-body -->
</div>

<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
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

           $("#commentForm").validate({
            rules: {
                date: {
                    required: true,
                },
                delivery: {
                    required: true,
                },
                "Supplier[]": {
                    required: true,
                },
      
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });

        $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
        $('.datepicker').mask('99-99-9999');


</script>

@stop
