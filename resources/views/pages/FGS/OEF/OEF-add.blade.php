@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;"> Order Execution Form(OEF)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Order Execution Form(OEF)
            </h4>
            <div class="az-dashboard-nav">
           
            </div>

			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
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
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" autocomplete="off" >
               

                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details  
                                </label>
                                <div class="form-devider"></div>
                            </div>
                         </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer  *</label>
                                <select  class="form-control customer" name="customer">
                                </select>
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Biiling Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" readonly></textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Shipping Address</label>
                                <textarea name="shipping_address"  class="form-control" id="shipping_address" readonly></textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Order Number  *</label>
                                <input type="text" class="form-control" name="order_number" value="" placeholder="Order Number">
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Order Date  *</label>
                                <input type="date" class="form-control datepicker" name="order_date" value="" placeholder="">
                            </div>
                        
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Order Fulfil  *</label>
                                <select class="form-control" name="order_fulfil">
                                    <option>Select one...</option>
                                    @foreach($order_fulfil as $type)
                                    <option value="{{$type['id']}}">{{$type['order_fulfil_type']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Transaction Type  *</label>
                                <select class="form-control" name="transaction_type">
                                    <option>Select one...</option>
                                    @foreach($transaction_type as $type)
                                    <option value="{{$type['id']}}">{{$type['transaction_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>OEF Date *</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control oef_date" id="oef_date" name="oef_date"  placeholder="">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>Due Date *</label>
                                @php $date= date('Y-m-d', strtotime('+30 days')) @endphp 
                                <input type="text" value="{{date('d-m-Y', strtotime($date))}}" class="form-control due_date" id="due_date" name="due_date" placeholder="">
                            </div>
                        </div>                       
            
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" onclick="document.location.href='{{url('fgs/MIN/item-list')}}'" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save & Next
                                
                                </button>
                            </div>
                        </div>
                        <div class="form-devider"></div>
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
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

<script>
  

    // $(".datepicker").datepicker({
    //     format: " dd-mm-yyyy",
    //     endDate: new Date(),
    //     autoclose:true
    // });
    // $(".due_date").datepicker({
    //     format: " dd-mm-yyyy",
    //     autoclose:true
    // });
    // $(".oef_date").datepicker({
    //     format: " dd-mm-yyyy",
    //     autoclose:true
    // });
    // $(".oef_date").datepicker("setDate", new Date());
    // var date = new Date();
    // date.setDate(date.getDate() + 30);
    // $(".due_date").datepicker("setDate", date);
    
        $('.oef_date').on('change',function()
        {
            var oef_date = new Date($(this).val());
            var date  = new Date(oef_date.setDate(oef_date.getDate()+30));
            var aftr_30_days = ( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
            $('#due_date').val(aftr_30_days);
        });
    
    // $(".oef_date").change(function(){
    //    var oef_date= new Date($(this).val());
    //    var day = new Date( oef_date.setDate(oef_date.getDate() + 30));
    //    var date = ((day.getDate() < 10) ? "0" : "") + String(day.getDate()) + "-" +((day.getMonth() < 9) ? "0" : "") + String(day.getMonth() + 1)+ "-" +day.getFullYear();
    //    alert(day);
    // //    date.setDate(date.getDate() + 30);
    // //      alert(day);
    // //    var aftr_30_days = ((day.getDate() < 10) ? "0" : "") + String(day.getDate()) + "-" +((day.getMonth() < 9) ? "0" : "") + String(day.getMonth() + 1)+ "-" +day.getFullYear();
    //    $(".due_date").val(date);
    // });
              

    $("#commentForm").validate({
            rules: {
                Requestor: {
                    required: true,
                },
                Department: {
                    required: true,
                },
                Date: {
                    required: true,
                },
                
                
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
  $(".customer").select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('fgs/customersearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    }).on('change', function (e) {
        $('#Itemcode-error').remove();
        $("#billing_address").text('');
        $("#shipping_address").text('');
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.billing_address){
                $("#billing_address").val(res.billing_address);
            }
            if(res.shipping_address){
                $("#shipping_address").val(res.shipping_address);
            }
        }
    });
</script>


@stop