@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-invoice') }}">SUPPLIER INVOICE</a></span>
                    <span>{{$id ? 'Edit' : 'Add' }}  SUPPLIER INVOICE</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">{{$id ? 'Edit' : 'Add' }} Supplier Invoice

                </h4>
                @foreach ($errors->all() as $errorr)
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ $errorr }}
                </div>
               @endforeach               
               @if (Session::get('success'))
               <div class="alert alert-success " style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
               </div>
               @endif
                        


                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            Supplier Invoice :
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <form method="POST" id="commentForm" autocomplete="off" >
                    <div class="row">
                        {{ csrf_field() }}

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" data-select2-id="7">
                            <label>PO Number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                                <select class="form-control RQ-code" name="po_number">
                                  @if(!empty($data['simaster']))
                                    <option value="{{$data['simaster']->id}}" selected >{{$data['simaster']->po_number}}</option>
                                  @endif

                                </select>
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>Invoice number *</label>
                        <input type="text" class="form-control"  value="{{(!empty($data['simaster'])) ? $data['simaster']->invoice_number : '' }}"  name="invoice_number" placeholder="Invoice number">
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>Invoice date *</label>
                            <input type="text" class="form-control datepicker" value="{{(!empty($data['simaster'])) ? date('d-m-Y',strtotime($data['simaster']->invoice_date)) : '' }}" name="date" placeholder="Invoice date">
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>Created by: *</label>
                            <select class="form-control user_list" name="create_by">
                                @foreach ($data['users'] as $item)
                                 <option value="{{$item['user_id']}}"
                                 @if(!empty($data['simaster']) && $data['simaster']->created_by == $item['user_id']) selected @endif
                                 >{{$item['employee_id']}} - {{$item['f_name']}} {{$item['l_name']}}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                    class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    {{$id ? 'Update' : 'Submit' }}
                            </button>
                        </div>
                    </div>
                </form>
                <div class="data-bindings">
                    <?php
                    if(!empty($data['master_list'])){
                      echo $data['master_list'];
                    }
                    ?>
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
            po_number: {
                required: true,
            },
            invoice_number: {
                required: true,
            },
            date:{
                required: true,
            },
            create_by:{
                required: true,
            }

        },
        submitHandler: function(form) {
           // $('.spinner-button').show();
            form.submit();
        }
    });




        $('.RQ-code').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 6,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/find-po-number') }}",
          processResults: function (data) {

            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();

        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('inventory/find-po-number') }}?id="+res.id,function(data){
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
