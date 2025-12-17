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
            SRN Edit
                
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
                   <form method="POST" id="commentForm" autocomplete="off">
    {{ csrf_field() }}  
    <div class="row">
        <!-- Customer -->
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label for="customer">Customer *</label>
            <select class="form-control customer" name="customer" disabled>
                <option value="{{ $srnDetails->customer_id }}">{{ $srnDetails->firm_name }}</option>
            </select>
        </div>

        <!-- Billing Address -->
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label for="billing_address">Customer Billing Address</label>
            <textarea name="billing_address" class="form-control" id="billing_address" readonly>{{ $srnDetails->billing_address }}</textarea>
        </div>

        <!-- DNI/EXI Number -->
<!-- DNI/EXI Number -->
<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="dni_number">DNI/EXI No. *</label>
    <select class="form-control dni_number" id="dni_number" name="dni_number" disabled>
        <option value="{{ $srnDetails->dni_number ?? '' }}">{{ $srnDetails->dni_number ?? 'Not Available' }}</option>
    </select>
</div>


        <!-- Business Category -->
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label for="product_category">Business Category *</label>
            <select class="form-control" id="product_category" name="product_category" disabled>
                <option value="{{ $srnDetails->product_category }}">{{ $srnDetails->category_name }}</option>
            </select>
        </div>

        <!-- Product Category -->
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label for="new_product_category">Product Category *</label>
            <select class="form-control" id="new_product_category" name="new_product_category" disabled>
                <option value="{{ $srnDetails->new_product_category }}">{{ $srnDetails->new_category_name }}</option>
            </select>
        </div>

        <!-- Stock Location Increase -->
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label for="location_increase">Stock Location Increase *</label>
            <select class="form-control" name="location_increase" id="location_increase" disabled>
                <option value="{{ $srnDetails->location_increase }}">{{ $srnDetails->location_name1 }}</option>
            </select>
        </div>

        <!-- SRN Date -->
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label for="srn_date">SRN Date *</label>
            <input type="text" value="{{ $srnDetails->srn_date }}" class="form-control datepicker" name="srn_date" readonly>
        </div>

        <!-- Remarks -->
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label for="remarks">Remarks *</label>
            <textarea class="form-control" name="remarks" readonly>{{ $srnDetails->remarks }}</textarea>
        </div>

        <!-- Other Charges -->
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label for="other_charges">Other Charges *</label>
            <input type="number" value="{{ old('other_charges', $srnDetails->other_charges) }}" class="form-control" name="other_charges" id="other_charges">
            @if ($errors->has('other_charges'))
                <small class="text-danger">{{ $errors->first('other_charges') }}</small>
            @endif
        </div>
    </div>

    <!-- Save Button -->
    <div class="form-devider"></div>
    <div class="row save-btn">
        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button type="submit" class="btn btn-primary btn-rounded save-button" style="float: right;">
                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span>
                <i class="fas fa-save"></i> Save & Next
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