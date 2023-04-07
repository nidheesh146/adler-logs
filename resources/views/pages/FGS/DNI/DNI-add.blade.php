@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">Tax Invoice Cum Delivery Note(DNI)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Tax Invoice Cum Delivery Note(DNI)
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
                    <form  method="post" autocomplete="off" >
               

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
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">Customer  *</label>
                                <select  class="form-control customer" name="customer">
                                </select>
                            </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">Customer Biiling Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" readonly></textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">Customer Shipping Address</label>
                                <textarea name="shipping_address"  class="form-control" id="shipping_address" readonly></textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label>DNI Date *</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control dni_date" id="dni_date name="pi_date"  placeholder="">
                            </div>
                        </div> 
                        <div class="form-devider"></div>
                        <div class="row">
                            <div class="invoice-heading" style="display:none;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;margin-left:12px;">
                                <i class="fas fa-address-card"></i> Add Tax Invoice Cum Delivery Note  
                            </label>
                            </div>
                            <div class="form-devider"></div>
                            
                            <div class="table-responsive" id="pitable">
                                <!-- <table class="table table-bordered mg-b-0" id="example1">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="width:120px;">GRS Number</th>
                                            <th>OEF Number</th>
                                            <th>Product category</th>
                                            <th>STOCK LOCATION1(DECREASE)</th>
                                            <th>STOCK LOCATION2(INCREASE)</th>
                                            <th>GRS Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">

                                        
                                    </tbody>
                                </table> -->
                               
                            </div>
                            <div class="form-devider"></div>
                        </div>                      
                        <br/>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded sbmit-btn" style="float: right;display:none;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
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
  

    
        $('.oef_date').on('change',function()
        {
            var oef_date = new Date($(this).val());
            var date  = new Date(oef_date.setDate(oef_date.getDate()+30));
            var aftr_30_days = ( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
            $('#due_date').val(aftr_30_days);
        });
    
    
              

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
            url: "{{ url('fgs/domestic_customersearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    }).on('change', function (e) {
        $('#Itemcode-error').remove();
        $("#billing_address").text('');
        $("#shipping_address").text('');
        $('#pitable').empty();
        $('.invoice-heading').hide();
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.zone_name!="Export")
            {
                if(res.billing_address){
                    $("#billing_address").val(res.billing_address);
                }
                if(res.shipping_address){
                    $("#shipping_address").val(res.shipping_address);
                }
                $.get("{{ url('fgs/DNI/fetchPI') }}?customer_id="+res.id,function(data)
                {
                    if(data!=0)
                    {
                    $('.invoice-heading').show();
                    $('#pitable').append(data);
                    $('.sbmit-btn').show();
                    }
                });
            }
            else
            {
                alert('This is not a domestic customer...');
                $('.customer').val('');
                $("#billing_address").html('');
                $("#shipping_address").html('');
            }
        }
    });
</script>


@stop