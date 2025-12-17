@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">LABEL CARD</a></span> 
                <span><a href="" style="color: #596881;"> COMPARE ITEMS AVAILABILITY</a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            COMPARE ITEMS AVAILABILITY
            </h4>
            <div class="form-devider"></div>
			<div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    <div class="alert alert-success sucess" style="width: 100%;display:none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i><span class="success-span">Comparison successful. All data are matched..</span>
                    </div>
                    @if(Session::get('error'))
                        <div class="alert alert-danger "  role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ Session::get('error') }}
                    </div>
                    @endif                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <!-- <form method="POST" id="commentForm" action=""> -->
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>DOC Type *</label>
                                <select class="form-control" name="doc_type" id="doc_type">
                                    <option>..Select One..</option>
                                    <option value="GRS">GRS</option>
                                    <option value="PI">PI</option>
                                    <option value="DNI">DNI</option>
                                    <option value="DC">DC</option>
                               </select>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>DOC Number *</label>
                                <select class="form-control" name="doc_number" id="doc_number">
                                    <option>..Select One..</option>
                               </select>
                            </div><!-- form-group -->
                        </div> 
                        <div class="form-devider"></div>
                        <!-- <div class="row"> -->
                            <div class="doc-info-binding">
                            </div>
                        <!-- </div> -->
            
                        {{--<div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span> 
                                <i class="fas fa-save"></i>
                                Generate
                                </button>
                            </div>
                        </div>--}}
                        <div class="form-devider"></div>
                    <!-- </form> -->

                </div>
            </div>
        </div>
	</div>
	<!-- az-content-body -->
</div>




<script src="<?= url('') ?>/js/azia.js"></script>

<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

<script src="<?= url('') ?>/js /jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>

<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>

<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
    <script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script>
var dataTable = $('#example1').dataTable({
        "sPaginationType": "full_numbers",
        "ordering": false,
    });
    
    </script>
    
    <script>
  $(function(){
    'use strict'
        //$("#scanned_code").blur(function() {
        $(document).on("input",'#scanned_code', function() { 
            // if scanned code is combination of [10] and batchcard
            // var scanned_code1 = $(this).val();
            // var scanned_code = scanned_code1.substring(3,scanned_code1.length);
            var scanned_code1 = $(this).val();
            var scanned_code = scanned_code1.replace(".", "_");
            var val = parseInt($('.'+scanned_code).val());
            $('.'+scanned_code).val(parseInt(val+1));
            setTimeout(function(){
                    document.getElementById('scanned_code').value = "";
            }, 1000);
            //$('#scanned_code').fadeOut();
        });
        $(document).on("click",'.compare-btn', function(e) {
           // alert(flag);
           var flag =0;
            $('.scanned_count').each(function()
            {
               // alert(this.value);
               
                var scanned_count = this.value;
                var print_count = $(this).closest('tr').find('.qty_to_print').val();
                //alert(print_count);
                if(scanned_count!=print_count)
                {
                    $(this).closest('tr').find('.error-span').show();
                    flag = 1;
                }
                else
                {
                    flag = 2;
                }
                
            });
            if(flag == 2)
            {
                $('.sucess').show();
                setTimeout(function() {
                    location.reload();
                }, 5000);
                
            }
            e.preventDefault(e);
         });
        $('#doc_type').change(function(){
            $('.doc-info-binding').html('');
            var doc_type = $(this).val();
            //alert(doc_type);
            $("#doc_number").select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
                minimumInputLength: 4,
                allowClear: true,});
            $('#doc_number option:gt(0)').remove();
            $.get( "{{ url('label/getDocNumbers') }}/"+doc_type, function(data) {
                if(data!=0)
                {
                    $.each(data,function(key, value)
                    {
                      $("#doc_number").append('<option  value=' + value.id + '>' + value.doc_number + '</option>');
                    });
                }
            });
        });

        $("#doc_number").on('change', function (e) {
            var doc_type = $('#doc_type').val();
            var doc_id = $(this).val();
            $.get( "{{ url('label/docNumberInfoForComparison') }}/"+doc_type+"/"+doc_id, function(data) {
                $('.doc-info-binding').html(data);
            });

        });
        
        $(document).on("change",'.check_all', function() { 
            $('.check_item').not(this).prop('checked', this.checked);
            if ($('.check_item').is(':checked')) 
            {
                enableTextBox($('.check_item')); 
                $('.label_print_count').attr("disabled", false);
                $('.check_item').closest('prbody1').find('.qty_to_print').attr("disabled", false);
                $('.check_item').closest('prbody1').find('.qty_to_print').attr("required", "true");
                
                
            }
            else
            {
                //$('.check-dni').closest('tr').find('.srn_qty').val('');
                enableTextBox($('.check_item')); 
                $('.label_print_count').attr("disabled", true);
                $('.check_item').closest('prbody1').find('.qty_to_print').attr("required", "false");
                $('.check_item').closest('prbody1').find('.qty_to_print').attr("disabled", true);
            }

        });
        $(document).on("change",'.label_print_count', function() { 
            var qty = $(this).val();
            $('.check_item').closest('tr').find('.qty_to_print').val(qty);
        });
       
});
function enableTextBox(cash) 
{
    const checkbox = $(cash);
   // checkbox.closest('tr').find('.qty_to_print').val('');
    if(checkbox.is(':checked'))
    { 
        checkbox.closest('tr').find('.qty_to_print').attr("disabled", false);
        checkbox.closest('tr').find('.qty_to_print').attr("required", "true");
        // var qtyPerPack = checkbox.attr("perpackqty");
        // var docQty = checkbox.attr("qty");
        // var printcount = docQty/qtyPerPack;
        // checkbox.closest('tr').find('.qty_to_print').val(printcount);
    }
    else
    {
        $('.label_print_count').val('');
        //checkbox.closest('tr').find('.qty_to_print').val('');
        checkbox.closest('tr').find('.qty_to_print').attr("required", "false");
        checkbox.closest('tr').find('.qty_to_print').attr("disabled", true);
    }
}
</script>


@stop