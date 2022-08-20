@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-invoice') }}">SUPPLIER INVOICE</a></span>
                    <span> Add FINAL PURCHASE ORDER </span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Add Supplier Invoice

                </h4>



                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            Supplier Invoice :
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div>
                <form method="POST" id="commentForm" autocomplete="off" novalidate="novalidate"
                    data-select2-id="commentForm">
                    <div class="row">
                        {{ csrf_field() }}

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" data-select2-id="7">
                            <label>PO Number <span class="spinner-border spinner-button spinner-border-sm"
                                    style="display:none;" role="status" aria-hidden="true"></span></label>
                                <select class="form-control RQ-code" name="rq_master_id">
                                  
                                </select>
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>Invoice number</label>
                            <input type="text" class="form-control datepicker" value="19-08-2022" name="date" placeholder="Date">
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>Invoice date</label>
                            <input type="text" class="form-control datepicker" value="19-08-2022" name="date" placeholder="Date">
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <label>Created by: </label>
                            <select class="form-control user_list" name="create_by">
                                <option value="1" data-select2-id="3">EMP123 - Admin Admin</option>
                            </select>
                        </div>


                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                    class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                Save
                            </button>
                        </div>
                    </div>
                </form>
                <div class="data-bindings">

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
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script>
      $(function(){
        'use strict'

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
    </script>


@stop
