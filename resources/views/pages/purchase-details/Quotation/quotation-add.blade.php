@extends('layouts.default')
@section('content')
<style>
     .select2-container--default .select2-selection--multiple .select2-selection__choice{
        color:white;
        background-color:#5897fb;
    }
</style>
<div class="az-content az-content-dashboard">
    <br>
	<div class="container">
		<div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="{{url('inventory/quotation')}}" style="color: #596881;">QUOTATION</a></span> 
                <span><a href="{{url('inventory/quotation')}}" style="color: #596881;">ADD REQUEST FOR QUOTATION </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
              Add request for quotation
            </h4>
            

			<div class="row">                   
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
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
                   @include('includes.purchase-details.pr-sr-tab')
                    <div class="tab-content">
                        <div class="tab-pane active  show " id="purchase">
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
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Date *</label>
                                    <input type="text"  class="form-control datepicker" value="{{date("d-m-Y")}}" name="date" placeholder="Date">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Delivery Schedule *</label>
                                    <input type="text"  class="form-control datepicker" value="{{date("d-m-Y")}}" name="delivery" placeholder="Date">
                                </div>
                                <input type="hidden" value="{{request()->get('prsr')}}" id="prsr"  name="prsr">
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
                                        <i class="fas fa-address-card"></i>@if(request()->get('prsr')!='sr') Purchase @else Service @endif Requisition Approved List
                                    </label>
                                    {{-- <div class="form-devider"></div> --}}
                                </div>
                            </div>

                            <div class="table-responsive">
                                {{-- <h4> Purchase Requisition Approved List </h4> --}}
                                <table class="table table-bordered mg-b-0" id="example1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@if(request()->get('prsr')!='sr') PR No @else SR No @endif</th>
                                            <th>Item code </th>
                                            <th>Supplier</th>
                                            <th>Rate</th>
                                            <th>Discount %</th>
                                            <th>GST %</th>
                                            <th>Currency</th>
                                            <th>Net value </th>
                                            <th>Actual Order Qty</th>
                                            <th>Approved Qty</th>
                                            <!-- <th>Approved By</th>
                                            <th>Date</th> -->
                                        </tr>
                                    </thead>
                                    <tbody >
                            
                                    @foreach($data['getdata'] as $item)
                                  
                                    <tr>
                                        <td><input type="checkbox" class="purchase_requisition_item" id="purchase_requisition_item" name="purchase_requisition_item[]" value="{{$item['requisition_item_id']}}"></td>
                                        <th>{{$item['pr_no']}}</th>
                                        <th>{{$item['item_code']}}</th>
                                        <td> {{$item['vendor_id']}}</td>
                                        <td>{{$item['rate']}}</td>
                                        <td>{{$item['discount_percent']}}</td>
                                        <td>{{$item['gst']}}</td>
                                        <td>{{$item['currency_code']}}</td>
                                        <td>{{$item['net_value']}}</td>	
                                        <td>{{$item['actual_order_qty']}}</td>
                                        <td>{{$item['approved_qty']}}</td>	
                                        <!-- <td>{{$item['f_name']}} {{$item['l_name']}}</td>
                                        <td>{{$item['f_name']}}</td>					  -->
                                    </tr>
                                    
                                    @endforeach
                            
                                    </tbody>
                                </table>
                                <div class="box-footer clearfix">
                                    {{ $data['getdata']->appends(request()->input())->links() }}
                                </div>   
                                <br/>
                                <div class="form-devider"></div>
                                @if($data['getdata'])
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                                role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
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
                'Supplier[]': {
                   required: true,
                },
                'purchase_requisition_item[]':{
                    required: true
                }
      
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
