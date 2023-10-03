@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br> 
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">Cancellation Order Execution Form(COEF)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
	       <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Cancellation Order Execution Form(COEF)
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
                             <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" data-select2-id="7">
                                <label>OEF number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                                @if(!empty($edit['oef']))
                                <input type="hidden" name="oef_number" value="{{$edit['oef']->oef_number}}">
                                @endif
                                <select class="form-control oef_number" name="oef_number" @if(!empty($edit['oef'])) disabled @endif>
                                    <!-- <option value="" ></option> -->
                                    @if(!empty($edit['oef']))
                                        <option value="{{$edit['oef']->oef_number}}" selected>{{$edit['oef']->oef_number}}</option>
                                    @endif
                                </select>
                            </div>
                       
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>COEF date *</label>
                                <input type="text" class="form-control datepicker" value="{{ date("d-m-Y") }}" name="coef_date" placeholder="COEF Date" id="coef_date">
                                @if(!empty($edit['oef']))
                                    <input type="hidden" name="created_at" value="{{ date('d-m-Y', strtotime($item->created_at)) }}">
                                    <input type="hidden" name="order_number" value="{{ $item->order_number }}">
                                    <input type="hidden" name="order_date" value="{{ date('d-m-Y', strtotime($item->order_date)) }}">
                                    <input type="hidden" name="order_fulfil" value="{{ $item->order_fulfil }}">
                                @endif
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Created by: *</label>
                                <select class="form-control user_list" name="created_by">
                                @foreach ($data['users'] as $user)
                                    <option value="{{$user->user_id}}"   @if(!empty($edit['min']) && $edit['min']->created_by == $user->user_id) selected  @endif   >{{$user->f_name}} {{$user->l_name}}</option>
                                @endforeach                                          
                                </select>
                            </div>
                            @if(!empty($edit['items']))
                                @foreach ($edit['items'] as $item) 
                                <input type="hidden" name="created_at" value="{{ date('d-m-Y', strtotime($item->created_at)) }}">
                                <input type="hidden" name="order_number" value="{{ $item->order_number }}">
                                <input type="hidden" name="order_date" value="{{ date('d-m-Y', strtotime($item->order_date)) }}">
                                <input type="hidden" name="order_fulfil" value="{{ $item->order_fulfil_type }}">
                                @endforeach
                            @endif
                        </div>
                        <div class="data-bindings" style="width:100%;">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- az-content-body -->
    </div>

    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function() {
                $(this).find(':submit').prop('disabled', true);
            });
        });
      $(function(){
        'use strict'

        $("#commentForm").validate({
        rules: {
            miq_number: {
                required: true,
            },
            mac_date:{
                required: true,
            },
            created_by:{
                required: true,
            }

        },
        submitHandler: function(form) {
            $('.spinner-button').show();
            form.submit();
        }
    });


    $('.user_list').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',

      });


      });

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true,
    endDate: new Date()
    });
    $('.datepicker').mask('99-99-9999');

  $(".oef_number").select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('fgs/COEF/find-oef-number-for-coef') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
   }).on('change', function (e) {
        $('.spinner-button').show();
        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('fgs/COEF/find-oef-info') }}?id="+res.id,function(data){
            $('.data-bindings').html(data);
            $('.spinner-button').hide();
        });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
        }
      });
    function enableTextBox(cash) 
    {
        const checkbox = $(cash);
        if(checkbox.is(':checked')){
            checkbox.closest('tr').find('.qty_to_cancel').attr("disabled", false);
            checkbox.closest('tr').find('.qty_to_cancel').attr("required", "true");
        }else{
            checkbox.closest('tr').find('.qty_to_cancel').val('');
            checkbox.closest('tr').find('.qty_to_cancel').attr("required", "false");
            checkbox.closest('tr').find('.qty_to_cancel').attr("disabled", true);
        }
    }
    
    </script>


@stop