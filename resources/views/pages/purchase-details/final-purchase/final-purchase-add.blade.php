@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
    <div class="az-content-body">
        <div class="az-content-breadcrumb"> 
             <span><a href="{{url('inventory/final-purchase')}}">FINAL  ORDER</a></span>
             <span> {{(!empty($data['master_data'])) ? 'Edit' : 'Add'}} FINAL ORDER {{(!empty($data['master_data'])) ? '( '.$data['master_data']->po_number.' )' : ''}}</span>
        </div>
        <h4 class="az-content-title" style="font-size: 20px;">{{(!empty($data['master_data'])) ? 'Edit' : 'Add'}} final  order		{{(!empty($data['master_data'])) ? '( '.$data['master_data']->po_number.' )' : ''}}	 
        
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
              Final purchase order:
            </label>
            <div class="form-devider"></div>
        </div>
    </div>
    <form method="POST" id="commentForm" autocomplete="off" >
    <div class="row">

        {{ csrf_field() }}  
            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <label>RQ number <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                  role="status" aria-hidden="true"></span></label>
                  @if(!empty($data['master_data']))
                  <input type="hidden" name="rq_master_id" value="{{$data['master_data']->rq_master_id}}">
                  @endif
                <select class="form-control  RQ-code" name="rq_master_id"  @if(!empty($data["master_data"])) disabled @endif >
                 
              @if(!empty($data['master_data']))
                  <option value="{{$data['master_data']->rq_master_id}}" selected>{{$data['master_data']->rq_no}}</option>
               @endif
                                                                    
                </select>
            
            </div>
            
            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
              <label>Purchase order date</label>
              <input type="text"  class="form-control datepicker" value="{{ (!empty($data['master_data'])) ? date('d-m-Y',strtotime($data['master_data']->po_date)) : date("d-m-Y")}}" name="date" placeholder="Date">        <form id="Supplier_form">
      
          
          </div>


      
          <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label>Created by: </label>
       
            <select class="form-control user_list" name="create_by">
              @foreach ($data['users'] as $user)
              <option value="{{$user->user_id}}"   @if(!empty($data['master_data']) && $data['master_data']->created_by == $user->user_id) selected  @endif   >{{$user->f_name}} {{$user->l_name}}</option>
              @endforeach
                                                                
            </select>
        
        </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
              role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
            Save
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


    $("#commentForm").validate({
            rules: {
              rq_master_id: {
                    required: true,
                },
                date: {
                    required: true,
                },
                create_by: {
                  required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });


    $('.user_list').select2({ });

                           $('.RQ-code').select2({
                                placeholder: 'Choose one',
                                searchInputPlaceholder: 'Search',
                                minimumInputLength: 6,
                                allowClear: true,
                                ajax: {
                                url: "{{ url('inventory/find-rq-number') }}",
                                processResults: function (data) {

                                  return { results: data };

                                }
                              }
                            }).on('change', function (e) {
                              $('.spinner-button').show();

                              let res = $(this).select2('data')[0];
                              if(res){
                                $.get("{{url('inventory/find-rq-number')}}?id="+res.id,function(data){
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
    autoclose:true
    });
        $('.datepicker').mask('99-99-9999');

</script>


@stop