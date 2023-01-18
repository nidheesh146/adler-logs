@extends('layouts.default')
@section('content')
<div class="az-content-body">
  
         <div class="az-content-header-top">
          <div>
            <h2 class="az-content-title mg-b-5 mg-b-lg-8 mg-t-20">Hi, welcome back!</h2>
            
          
            <div class="dash az-dashboard-date mg-t-30" >
            
            
            <div class="date">
              <div><?php echo date("d");?></div>
              <div>
                <span><?php echo date("m-y");?></span>
                <span><?php echo date("l");?></span>
              </div>
          </div>
            </div><!-- az-dashboard-date -->
          </div><!-- az-dashboard-date -->
        </div><!-- az-content-body-title -->
        <div class="row row-sm">
          <div class="col-sm-6 col-xl-3">
            <div class="card card-dashboard-twentytwo">
              <div class="media">
                <div class="media-icon bg-purple"><i class="typcn typcn-chart-line-outline"></i></div>
                <div class="media-body">
                  <h6>{{$pendingreq}}</small></h6>
                  <span>pending requesition</span>
                </div>
              </div>
              <div class="chart-wrapper">
                <div id="flotChart1" class="flot-chart"></div>
              </div><!-- chart-wrapper -->
            </div><!-- card -->
          </div><!-- col -->
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-sm-t-0">
            <div class="card card-dashboard-twentytwo">
              <div class="media">
                <div class="media-icon bg-primary"><i class="typcn typcn-chart-line-outline"></i></div>
                <div class="media-body">
                  <h6>{{$supplier}}</small></h6>
                  <span>suppliers</span>
                </div>
              </div>
              <div class="chart-wrapper">
                <div id="flotChart2" class="flot-chart"></div>
              </div><!-- chart-wrapper -->
            </div><!-- card -->
          </div><!-- col-3 -->
         
          
          <div class="col-xl-9 mg-t-20">
           
          </div><!-- col -->
          
         
          
        </div><!-- row -->
      </div><!-- az-content-body -->
      <script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

<script>
  $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });
  //  .datepicker('update', new Date());
    $('.datepicker').mask('99-99-9999');
              

    $("#commentForm").validate({
            rules: {
                product: {
                    required: true,
                },
                sku_quantity: {
                    required: true,
                    number: true,
                },
                process_sheet:{
                    required: true,
                },
                start_date: {
                    required: true,
                },
                target_date: {
                    required: true,
                },
                description: {
                    required: true,
                },
                batchcard: {
                    required: true,
                },
                input_material:{
                    required:true,
                },
                input_material_qty:{
                    required:true,
                    number: true,
                }
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
        $("#commentForm1").validate({
            rules: {
                product: {
                    required: true,
                },
                sku_quantity: {
                    required: true,
                    number: true,
                },
                process_sheet:{
                    required: true,
                },
                start_date: {
                    required: true,
                },
                target_date: {
                    required: true,
                },
                description: {
                    required: true,
                },
                batchcard: {
                    required: true,
                },
                primary_sku_batchcards:{
                    required:true,
                },
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });

    
  });
    $('.Product, .Product1').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('batchcard/productsearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    });
    $('.primary_sku_batchards').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: "{{ url('inventory/stock/find-batchcard') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    });
    $('.input_material').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: "{{ url('inventory/itemcodesearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    });
</script>


@stop