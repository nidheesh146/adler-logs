@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/MRD') }}"  style="color: #97a3b9;">Rejected Material Return Note(RMRN)</a></span>
                    <span style="color: #596881;"> Rejected Material Return Note @if(!empty($edit)) ({{$edit['rmrn']->mrd_number}}) @endif</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Rejected Material Return Note @if(!empty($edit)) ({{$edit['rmrn']->mrd_number}}) @endif

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
                        


                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                        Rejected Material Return Note(RMRN) :
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <form method="POST" id="commentForm" autocomplete="off" >
                    <div class="row">
                        {{ csrf_field() }}

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" data-select2-id="7">
                            <label>MRD/WOR number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                            <select class="form-control mrd_number" name="mrd_number" @if(!empty($edit['rmrn'])) disabled @endif>
                            @if(!empty($edit['rmrn']))
                                <option value="{{$edit['rmrn']->mrd_id}}" selected>{{$edit['rmrn']->mrd_number}}</option>
                            @endif            
                            </select>
                            @if(!empty($edit['rmrn']))
                                <input type="hidden" name="mrd_number" value="{{$edit['rmrn']->mrd_id}}">
                            @endif
                            </select>
                        </div>

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>RMRN date *</label>
                            <input type="text" class="form-control datepicker" value="{{ (!empty($edit['rmrn'])) ? date('d-m-Y',strtotime($edit['rmrn']->rmrn_date)) : date("d-m-Y")}}" name="rmrn_date" placeholder="MRD date">
                        </div>

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>Created by: *</label>
                            <select class="form-control user_list" name="created_by">
                            @foreach ($data['users'] as $user)
                                <option value="{{$user->user_id}}"   @if(!empty($edit['rmrn']) && $edit['rmrn']->created_by == $user['user_id']) selected  @elseif(config('user')['user_id']== $user['user_id']) selected @endif   >{{$user->f_name}} {{$user->l_name}}</option>
                            @endforeach                                          
                            </select>
                        </div>


                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                    class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    @if(!empty($edit['rmrn'])) Update @else Submit @endif
                            </button>
                        </div>
                    </div>
                </form>
                <div class="data-bindings">
                </div>
                @if(!empty($edit)) 
                 <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            Material Rejection & Delivery ({{$edit['rmrn']['mrd_number']}})
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <table class="table table-bordered mg-b-0">
                    <thead>
                        
                    </thead>
                    <tbody>
                        <tr>
                            <th>MRD Date</th>
                            <td>{{date('d-m-Y', strtotime($edit['rmrn']['mrd_date']))}}</td>
                            
                        </tr>
                        <tr>
                            <th>Created By & Created Date</th>
                            <td>{{$edit['rmrn']['f_name']}} {{$edit['rmrn']['l_name']}}, {{date('d-m-Y', strtotime($edit['rmrn']['created_at']))}}</td>
                        <tr>
                        <tr>
                            <th>Supplier ID</th>
                            <td>{{$edit['rmrn']['vendor_id']}}</td>
                        </tr>
                        <tr>
                            <th>Supplier Name</th>
                            <td>{{$edit['rmrn']['vendor_name']}}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                        MRD Items
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Type</th>
                                <th>Rejected Qty</th>
                                <th>Value in Inr</th>
                                <th>Remarks</th>
                                <th>Courier /Transport Name</th>
                                <th>Receipt /LR Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody >
                            @foreach($edit['items'] as $item)
                            <tr>
                                <th>{{$item['item_code']}}</th>
                                <th>{{$item['type_name']}}</th>
                                <th>@if($item['rejected_quantity']!=NULL) {{$item['rejected_quantity']}} {{$item['unit_name']}} @endif</th>
                                <th>{{$item['value_inr']}}</th>
                                <th>@if($item['remarks']!=NULL) {{$item['remarks']}} @endif</th>
                                <th>{{$item['courier_transport_name']}}</th>
                                <th>{{$item['receipt_lr_number']}}</th>
                                <th><a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/RMRN/'.$item['id'].'/item')}}"  class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 	</th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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
            mrd_date:{
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


        $('.mrd_number').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/find-mrd') }}",
          processResults: function (data) {

            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();

        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('inventory/find-mrd-info') }}?id="+res.id,function(data){
            $('.data-bindings').html(data);
            $('.spinner-button').hide();
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
        }
      });
      });

      $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true,
    endDate: new Date()
    });
    $('.datepicker').mask('99-99-9999');
    </script>


@stop
