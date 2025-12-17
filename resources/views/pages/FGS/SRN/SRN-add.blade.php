@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">Sales Return Note(SRN)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;">
            Sales Return Note(SRN)
                <div class="right-button">
                    <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/SRN-manual-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
                    Manual SRN 
                    </button>
                <div> 
                </div>
                </div> 
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
                                <textarea name="billing_address" class="form-control" id="billing_address" readonly>@if(!empty($grs)) {{$grs['shipping_address']}} @endif</textarea>
                            </div> 
                            {{--<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Shipping Address</label>
                                <textarea name="shipping_address"  class="form-control" id="shipping_address" readonly>@if(!empty($grs)) {{$grs['billing_address']}} @endif</textarea>
                            </div>--}}
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">DNI/EXI No.  *</label>
                                <select class="form-control dni_number" id="dni_number" name="dni_number">
                                <option value="">Select One</option>
                                </select>                            
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="exampleInputEmail1">Business Category *</label>
    <select class="form-control" id="product_category" name="product_category">
        <option>Select one...</option>
        @foreach($category as $cate)
            <option value="{{ $cate['id'] }}" 
                {{ $cate['category_name'] == 'OBM' ? 'selected' : '' }}>
                {{ $cate['category_name'] }}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="exampleInputEmail1">Product Category *</label>
    <select class="form-control" id="new_product_category" name="new_product_category">
        <option>Select one...</option>
        @foreach($new_category as $cate)
            <option value="{{ $cate['id'] }}" 
                {{ $cate['category_name'] == 'ASD' ? 'selected' : '' }}>
                {{ $cate['category_name'] }}
            </option>
        @endforeach
    </select>
</div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Stock location Increase *</label>
                                <select class="form-control" name="location_increase" id="location_increase" required>
                                    <option>Select one...</option>
                                    @foreach($locations as $loc)
                                    @if($loc['location_name']!='MAA (Material Allocation Area)' && $loc['location_name']!='Quarantine' && $loc['location_name']!='Consignment' && $loc['location_name']!='Loaner')
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>SRN Date *</label>
                                <input type="text" value="" class="form-control datepicker" name="srn_date" placeholder="">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Remarks  *</label>
                                <textarea type="text" class="form-control" name="remarks" value="" placeholder=""></textarea>
                            </div><!-- form-group -->
                        </div> 
                        <div class="row">
    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <label for="other_charges">Other Charges *</label>
        <input type="number" class="form-control" name="other_charges" id="other_charges" placeholder="Enter amount">
    </div>
    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <label for="charge_type">Charge Type *</label>
        <select class="form-control" name="charge_type" id="charge_type">
            <option value="">Select Type</option>
            <option value="percentage">Percentage</option>
            <option value="lumpsum">Lump Sum</option>
        </select>
    </div>
</div>
                        
                    <div class="data-bindings">
                    </div>
                    <div class="form-devider"></div>
                    <div class="row save-btn" style="display:none;">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded save-button" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save & Next
                                
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

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
    $(".datepicker").datepicker("setDate", new Date());
  //  .datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');
              

    $("#commentForm").validate({
            rules: {
                dni_no: {
                    required: true,
                },
                srn_date: {
                    required: true,
                }
                
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
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
        $('.dni_number option:gt(0)').remove();
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.billing_address){
                $("#billing_address").val(res.billing_address);
            }
            if(res.shipping_address){
                $("#shipping_address").val(res.shipping_address);
            }
            $.get("{{ url('fgs/SRN/find-dni-number-for-srn') }}?customer_id="+res.id,function(data){
                if(data!=0)
                {
                    $.each(data,function(key, value)
                    {
                      $(".dni_number").append('<option  value=' + value.id + '>' + value.text + '</option>');
                    });
                }
            });
        }
    });
    $('.save-button').on('click', function (e) {
        var i=0;
        $(".check-dni:checked").each(function() 
        {
            i++;
        } );
        if(i!=0)
        {
            form.submit();
        }
        else{
            e.preventDefault();
            alert('Please check any checkbox');
        }

    });
    $('.dni_number').on('change',function(e){
        $('.spinner-button').show();
        let dni_id = $(this).val();
        if(dni_id){
          $.get("{{ url('fgs/SRN/find-dni-info') }}?id="+dni_id,function(data){
            $('.data-bindings').html(data);
            $('.spinner-button').hide();
            $('.save-btn').show();
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
          $('.save-btn').hide();
        }
      });
    function enableTextBox(cash) 
    {
        const checkbox = $(cash);
        if(checkbox.is(':checked')){
            checkbox.closest('tr').find('.srn_qty').attr("disabled", false);
            checkbox.closest('tr').find('.srn_qty').attr("required", "true");
        }else{
            //checkbox.closest('tr').find('.srn_qty').val('');
            checkbox.closest('tr').find('.srn_qty').attr("required", "false");
            checkbox.closest('tr').find('.srn_qty').attr("disabled", true);
        }
    }
      
    $(document).on("change",'#checkall', function() { 
        $('.check-dni').not(this).prop('checked', this.checked);
        if ($('.check-dni').is(':checked')) 
        {
            $('.check-dni').closest('tr').find('.srn_qty').attr("disabled", false);
            $('.check-dni').closest('tr').find('.srn_qty').attr("required", "true");
        }
        else
        {
            //$('.check-dni').closest('tr').find('.srn_qty').val('');
            $('.check-dni').closest('tr').find('.srn_qty').attr("required", "false");
            $('.check-dni').closest('tr').find('.srn_qty').attr("disabled", true);
        }

    });
    
  
</script>


@stop