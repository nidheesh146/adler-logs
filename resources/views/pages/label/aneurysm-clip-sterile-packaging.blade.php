@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">LABEL CARD</a></span> 
                <span><a href="" style="color: #596881;">
                @if(isset($title))
                CREATE PATIENT LABEL
                @else
                CREATE STERILIZATION PRODUCT LABEL2
                @endif</a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            @if(isset($title))
                Create Aneursym Clip Sterile Packaging
            @else
                Create Sterilization Label2
            @endif
            </h4>
            
            <div class="form-devider"></div>
			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                    @endif
                    @if(Session::get('error'))
                        <div class="alert alert-danger "  role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ Session::get('error') }}
                    </div>
                    @endif                   
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" action="{{url('label/aneurysm-clip-sterile-packaging-print')}}" id="commentForm" >
                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Batch Card *</label>
                                <select class="form-control batchcard_no" name="batchcard_no" id="batchcard_no">
                                
                                </select>
                            </div><!-- form-group -->


                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Sterilization Lot No *</label>
                                <input type="text" value="" class="form-control" name="sterilization_lot_no" placeholder="Sterilization Lot No" disable>
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>No of label *</label>
                            <input type="number" 
                                value="" class="form-control" name="no_of_label" placeholder="No of label">
                            </div><!-- form-group -->

                           
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Per Pack Quantity *</label>
                                <input type="text" value="" class="form-control" name="per_pack_quantity" id="per_pack_quantity" placeholder="Per pack quantity">
                            </div><!-- form-group -->
                            
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Manufacturing Date *</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control" name="manufacturing_date" value="" id="manufacturing_date" >
                            </div><!-- form-group -->
                            
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Sterilization Expiry date *</label>
                                 @php $date= date('Y-m-d', strtotime('+5 years')) @endphp 
                                <input type="text"  value="{{date('d-m-Y', strtotime($date .' -1 day'))}}" class="form-control" name="sterilization_expiry_date" id="sterilization_expiry_date" >
                            </div><!-- form-group -->

                        </div>               
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span> 
                                <i class="fas fa-save"></i>
                                Generate
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
  $(function(){
    'use strict'         

    $("#commentForm").validate({
            rules: {
                batchcard_no: {
                    required: true,
                },
                sterilization_lot_no: {
                    required: true,
                },
                no_of_label: {
                    required: true,
                },
                per_pack_quantity: {
                    required: true,
                },
                manufacturing_date: {
                    required: true,
                },
                sterilization_expiry_date: {
                    required: true,
                },
            },
            // submitHandler: function(form) {
            //     $('.spinner-button').show();
            //     form.submit();
            // }
    });
    var is_sterile = 1;
    $('.batchcard_no').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 5,
        allowClear: true,
        ajax: {
            url: "{{url('label/batchcardSearch')}}",
            processResults: function (data) {
            return {
                    results: data
                };
            }
        }
    });  

    $("#batchcard_no").on('change', function(e) {
        var batch_no_id = $(this).val();
        var type = "sterile";
        $.get( "{{ url('label/batchcardData') }}/"+batch_no_id, function(res) {
            if(res.quantity_per_pack) {
                $('#per_pack_quantity').val(res.quantity_per_pack);
            }
        });
    });
    $(document).ready(function(){
        $('#manufacturing_date').on('change',function(e)
        {
            var manufacturing_date = new Date($(this).val());
            var myDate  = new Date(manufacturing_date.setDate(manufacturing_date.getDate()-1));
            var date = new Date(myDate.setFullYear(myDate.getFullYear() + 5));
            var expiry_date = ( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
            $('#sterilization_expiry_date').val(expiry_date);
        });
    });

    
  });

  


    

</script>


@stop