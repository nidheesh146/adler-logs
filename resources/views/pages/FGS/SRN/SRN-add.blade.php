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
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Sales Return Note(SRN)
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
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">DNI/EXI No.  *</label>
                                <select name="dni_no" id="dni_no" class="form-control">
                                </select>
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>SRN Date *</label>
                                <input type="text" value="" class="form-control datepicker" name="srn_date" placeholder="">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Remarks  *</label>
                                <textarea type="text" class="form-control" name="remarks" value="" placeholder=""></textarea>
                            </div><!-- form-group -->
                        </div> 
                        <div class="row save-btn" style="display:none;">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save & Next
                                
                                </button>
                            </div>
                        </div>
                        <div class="form-devider"></div>
                    </form>
                    <div class="data-bindings">
                    </div>
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
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

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
  $('#dni_no').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 2,
          allowClear: true,
          ajax: {
          url: "{{ url('fgs/SRN/find-dni-number-for-srn') }}",
          processResults: function (data) {
            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();
        //alert(mtq_id);
        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('fgs/SRN/find-dni-info') }}?id="+res.id,function(data){
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
  
</script>


@stop