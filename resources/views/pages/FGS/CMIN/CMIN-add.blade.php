@extends('layouts.default')
@section('content') 
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-invoice') }}">  CANCELLATION MATERIAL ISSUE NOTE (CMIN)  </a></span>
                 </div>
                <h4 class="az-content-title" style="font-size: 20px;"> CANCELLATION MATERIAL ISSUE NOTE (CMIN)  @if(!empty($edit)) ({{$edit['mac']->mac_number}}) @endif

                </h4>
                @foreach ($errors->all() as $errorr)
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ $errorr }}
                </div>
               @endforeach 
               @if (Session::get('error'))
               <div class="alert alert-danger " style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                   <i class="icon fa fa-check"></i> {{ Session::get('error') }}
               </div>
               @endif              
               @if (Session::get('success'))
               <div class="alert alert-success " style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
               </div>
               @endif
                        


               
                <form method="POST" id="commentForm" autocomplete="off" >
                     <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details  
                                </label>
                                <div class="form-devider"></div>
                            </div>
                         </div>
                    <div class="row">
                        {{ csrf_field() }}

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" data-select2-id="7">
                            <label>MIN number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                            @if(!empty($edit['min']))
                            <input type="hidden" name="min_number" value="{{$edit['min']->min_number}}">

                            @endif
                            <select class="form-control min_number" name="min_number" @if(!empty($edit['min'])) disabled @endif>
                                <!-- <option value="" ></option> -->
                                @if(!empty($edit['min']))
                                    <option value="{{$edit['min']->min_number}}" selected>{{$edit['min']->min_number}}</option>
                                @endif
                            </select>
                        </div>
                       
                       <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>CMIN date *</label>
                            <input type="text" class="form-control datepicker" value="{{date("d-m-Y")}}" name="cmin_date" placeholder="MIN date" id="cmin_date">
                        </div>
                         @if(!empty($edit['min']))
                        <input type="hidden" name="stock_location" value="{{$edit['min']->stock_location}}">
@endif
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>Created by: *</label>
                            <select class="form-control user_list" name="created_by">
                            @foreach ($data['users'] as $user)
                                <option value="{{$user->user_id}}"   @if(!empty($edit['min']) && $edit['min']->created_by == $user->user_id) selected  @endif   >{{$user->f_name}} {{$user->l_name}}</option>
                            @endforeach                                          
                            </select>
                        </div>

   @if(!empty($edit['items']))

    foreach ($edit['items'] as $item) {
        
                   <input type="text" name="sku_code" value="{{ $item->sku_code }}">
                        
                        <input type="hidden" name="discription" value="{{ $item->discription }}">
                        <input type="hidden" name="batch_no" value="{{ $item->batch_no }}">
                        <input type="hidden" name="quantity" value="{{ $item->quantity }}">
                       }
@endif
                            
                       

                </form>
                <div class="data-bindings" style="width:100%;">
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


    $('.min_number').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 2,
          allowClear: true,
          ajax: {
          url: "{{ url('fgs/CMIN/find-min-number-for-cmin') }}",
          processResults: function (data) {
            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();

        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('fgs/CMIN/find-min-info') }}?id="+res.id,function(data){
            $('.data-bindings').html(data);
            $('.spinner-button').hide();
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
        }
      });
    
    </script>


@stop
