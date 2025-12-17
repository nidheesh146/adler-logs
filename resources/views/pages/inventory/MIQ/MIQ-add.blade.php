@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-invoice') }}">Material Inwards To Quarantine</a></span>
                    <span> Material Inwards To Quarantine info</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Material Inwards To Quarantine Info @if(!empty($data['miq'])) ({{$data['miq']['miq_number']}}) @endif

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
                        Material Inwards To Quarantine :
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <form method="POST" id="commentForm" autocomplete="off" >
                    <div class="row">
                        {{ csrf_field() }}

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" data-select2-id="7">
                            <label>Invoice number *<span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                            @if(!empty($data['miq']))
                            <input type="hidden" name="invoice_number" value="{{$data['miq']->invoice_master_id}}">
                            @endif
                            <select class="form-control invoice_number" name="invoice_number" @if(!empty($data['miq'])) disabled @endif>
                                <!-- <option value="" ></option> -->
                                @if(!empty($data['miq']))
                                    <option value="{{$data['miq']->invoice_master_id}}" selected>{{$data['miq']->invoice_number}}</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>Supplier *</label>
                            <input type="text" class="form-control supplier-name" @if(!empty($data['miq'])) value="{{$data['miq']->vendor_name}}" @else value="" @endif readonly  name="supplier" placeholder="Supplier" redonly>
                        </div>
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>MIQ date *</label>
                            <input type="text" class="form-control datepicker" value="{{ (!empty($data['miq'])) ? date('d-m-Y',strtotime($data['miq']->miq_date)) : date("d-m-Y")}}" name="miq_date" placeholder="MIQ date">
                        </div>

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label>Created by: *</label>
                            <select class="form-control user_list" name="created_by">
                            @foreach ($data['users'] as $user)
                                <option value="{{$user->user_id}}"   @if(!empty($data['miq']) && $data['miq']->created_by == $user->user_id) selected  @endif   >{{$user->f_name}} {{$user->l_name}}</option>
                            @endforeach                                                 
                            </select>
                        </div>


                    </div>
                    <div class="row">
                    @if(!empty($data['miq']))
    <!-- Edit Items & Confirm Submit Button -->
    <button type="submit" class="btn btn-primary btn-rounded" id="submitBtn" style="float: right;" disabled>
        <span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span>
        <i class="fas fa-save"></i> Edit Items & Confirm Submit
    </button>
@else
    <button type="submit" class="btn btn-primary btn-rounded" id="submitBtn" style="float: right;" disabled>
        <span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span>
        <i class="fas fa-save"></i> Submit
    </button>
@endif
                    </div>
                </form>
                <div class="data-bindings">
                </div>
                @if(!empty($data['miq']))
                <div class="row">
    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
        Supplier Invoice items</label>
        <div class="form-devider"></div>
    </div>
</div>
<div id="error-message" class="alert alert-warning" style="display:none;">
    <strong>Warning!</strong> Expiry Control,Currency,Landmark Value and Expiry Date must be populated for all items.
</div>
<div class="table-responsive">
    <table class="table table-bordered mg-b-0" id="example1">
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Item Type</th>
                <th>LOT No.</th>
                <th>Quantity</th>
                <th>Stock keeping Unit</th>
                <th>Unit Rate</th>
                <th>Expiry Control</th>
                <th>Expiry Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($data['miq_items'])
            @foreach($data['miq_items'] as $item)
                <tr class="item-row" data-item-id="{{$item['item_id']}}">
                    <td>{{$item['item_code']}}</td>
                    <td>{{$item['type_name']}}</td>
                    <td>{{$item['lot_number']}}</td>
                    <td>{{$item['order_qty']}}</td>
                    <td>{{$item['unit_name']}}</td>
                    <td>{{$item['rate']}}</td>
                    <td>@if($item['expiry_control']=='1') Yes @elseif($item['expiry_control']=='0') No @else {{$item['expiry_control']}} @endif</td>
                    <td>{{$item['expiry_date']}}</td>
                    <td>
                        <a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/MIQ/'.$item['item_id'].'/item')}}" class="dropdown-item">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>


            </div>
        @endif
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
        // Function to check if expiry fields are populated
        function checkExpiryFields() {
            let allFieldsPopulated = true;

            $('.item-row').each(function() {
                let expiryControl = $(this).find('td').eq(6).text().trim();
                let expiryDate = $(this).find('td').eq(7).text().trim();

                // Check if expiry control and expiry date are populated
                if (expiryControl === '' || expiryDate === '') {
                    allFieldsPopulated = false;
                }
            });

            // Enable/disable the submit button based on the fields' status
            if (allFieldsPopulated) {
                $('#submitBtn').prop('disabled', false);
                $('#error-message').hide();  // Hide error message if fields are populated
            } else {
                $('#submitBtn').prop('disabled', true);
                $('#error-message').show();  // Show error message if fields are empty
            }
        }

        // Check the expiry fields on page load
        checkExpiryFields();

        // Optionally, re-check when the user interacts with the form
        // $('#submitBtn').click(function() {
        //     checkExpiryFields();  // This can be called after changes if needed
        // });
    });
</script>
    <script>
      $(function(){
        'use strict'

        $("#commentForm").validate({
        rules: {
            invoice_number: {
                required: true,
            },
            miq_date:{
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


        $('.invoice_number').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 1,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/find-invoice-number') }}",
          processResults: function (data) {

            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();

        let res = $(this).select2('data')[0];
        $('.supplier-name').val(res.vendor_name);
        if(res){
          $.get("{{ url('inventory/MIQ/find-invoice-info') }}?id="+res.id,function(data){

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