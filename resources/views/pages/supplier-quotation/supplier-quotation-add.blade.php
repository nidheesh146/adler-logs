@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="{{url('inventory/supplier-quotation')}}" style="color: #596881;">SUPPLIER</a></span> 
                <span><a href="{{url('inventory/supplier-quotation')}}" style="color: #596881;">SUPPLIER QUOTATION</a></span>
                <span><a href="">{{ request()->pr_id? 'Edit' : 'Add' }} Supplier Quotation Master</a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">{{ request()->pr_id? 'Edit' : 'Add' }} Supplier Quotation Master</h4>
            <div class="az-dashboard-nav">
                <nav class="nav">
                    <a class="nav-link  active  " href="">Supplier Quotation Master </a>
                    <!-- <a class="nav-link  " @if(request()->pr_id) href="{{url('inventory/get-purchase-reqisition-item?pr_id='.request()->pr_id)}}" @endif >  Purchase reqisition item </a>
                     <a class="nav-link  " href=""> </a> -->
                </nav>
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
                    <form method="POST" id="commentForm" >
                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details                                
                                    @if(!empty($data['response']['purchase_requisition'][0]))
                                      ( PR NO : {{$data['response']['purchase_requisition'][0]['pr_no']}} )
                                    @endif
                                
                                 </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>
                        <div class="row">
                        <!-- <div class="form-group col-sm-12 col-md-4 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Request NO: *</label>
                            <input type="text" class="form-control" name="request_no" value="" placeholder="Enter PR NO">
                        </div> -->

                        <div class="form-group col-sm-12 col-md-4 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">RQ NO: *</label>
                            <input type="text" class="form-control" name="rq_no" id="rq_no" value="" placeholder="Enter PR NO">
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label>Supplier *</label>
                            <select class="form-control supplier" name="supplier">
                                <option value="">--- select one ---</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label>Date *</label>
                            <input type="date"  class="form-control" id="date" name="date" placeholder="Date">
                        </div>
                        <!-- form-group -->
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label>PR/SR *</label>
                            <select class="form-control" name="prsr" id="prsr">
                                <option value="">--- select one ---</option>
                                <option value="PR">PR</option>
                                <option value="PR">SR</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label>Delivery Schedule *</label>
                            <input type="date"  class="form-control" id="delivery" name="delivery" placeholder="Date">
                        </div> 
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label>Requestor *</label>
                            <input type="text" class="form-control" readonly value="{{(!empty($data['response']['purchase_requisition'][0]) ? $data['response']['purchase_requisition'][0]['requestor']  :  (session('user')['employee_id'] ? session('user')['employee_id'] : 'Requestor 1'))}}" 
                            name="requestor" placeholder="Requestor">
                        </div><!-- form-group -->
                    </div> 
                      
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                             @if(!empty($data['response']['purchase_requisition'][0]))
                                Update
                            @else 
                                Save & Next
                            @endif
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

<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script>
    $('.supplier').select2({
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


  $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
  //  .datepicker('update', new Date());

    $('.datepicker').mask('99-99-9999');         
    $("#commentForm").validate({
        // onfocusout: function(element, event) {
        //   if ($(element).attr('name') == "rq_no") {
        //       get_quotation_data($(element).val());
        //       //alert($(element).val());
        //    }
        // },
            rules: {
                requestor: {
                    required: true,
                },
                rq_no: {
                    required: true,
                },
                date: {
                    required: true,
                },
                 supplier: {
                    required: true,
                },
                prsr: {
                    required: true,
                },
                delivery:{
                    required: true,
                },
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });

    
  });

  function get_quotation_data(element){
        $('.spinner-button').show();
        $('#Itemcode-error').remove();
        $('#Itemdescription').text('');
        $('#Itemtype').val('');
        $('#HSNSAC').val('');
        $('#Unit').val('');
        $('#MinLevel').val('');
        $('#MaxLevel').val('');
        $('#Itemtypehidden').val('');
     
        $.get( "{{ url('inventory/quotationsearch') }}/"+element, function(res) {
          $('.spinner-button').hide();
          console.log(res);
          if(res.date){
            $('#date').text(res.discription);
          }
          if(res.item_type.type_name){
            $('#Itemtype').val(res.item_type.type_name);
            $('#Itemtypehidden').val(res.item_type.id);
            
          }
          
        }).fail(function(error) {
          $('.spinner-button').hide();
          $('#rq_no').after('<label id="Itemcode-error" class="error Itemcode-error" for="Itemcode">'+error.responseJSON.message+'</label>');
        });
    }

</script>


@stop