@extends('layouts.default')
@section('content')
<style>
    input[type="radio"]{
        appearance: none;
        border: 1px solid #d3d3d3;
        width: 30px;
        height: 30px;
        content: none;
        outline: none;
        margin: 0;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        background-color: #fff;
    }

                input[type="radio"]:checked {
                appearance: none;
                outline: none;
                padding: 0;
                content: none;
                border: none;
                }

                input[type="radio"]:checked::before{
                position: relative;
                color: green !important;
                content: "\00A0\2713\00A0" !important;
                border: 1px solid #d3d3d3;
                font-weight: bolder;
                font-size: 21px;
                }
 .select2-search--inline .select2-search__field {
    width: auto !important; /* Let the width adjust dynamically based on content */
    min-width: 150px !important; /* Set a sensible minimum width */
    max-width: 100% !important; /* Prevent it from overflowing the container */
}       

</style>
<div class="az-content az-content-dashboard">
  <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">QUALITY INWARD FORM</a></span> 
                <span><a href="">QUALITY INWARD FORM</a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">QUALITY INWARD FORM</h4>
           
            <div class="row">  
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                    @endif
                    @if (Session::get('error'))
                    <div class="alert alert-danger " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('error') }}
                    </div>
                    @endif
                    @foreach ($errors->all() as $errorr)
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      {{ $errorr }}
                    </div>
                   @endforeach
                   <div class="card bd-0">
                        <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
                            <nav class="nav nav-tabs">
                                <a class="nav-link active" data-toggle="tab" href="#tabCont1">Details</a>
                            </nav> 
                        </div>
                        <div class="card-body bd bd-t-0 tab-content">
                            <div id="tabCont1" class="tab-pane active show">
                            <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                                <form  action="{{url('quality/quality-inward')}}" method="POST" autocomplete="off" >
                                    {{ csrf_field() }}  
                                    
                                   <div class="row">
    @php
        $formattedDate = \Carbon\Carbon::parse($batchcards['start_date'])->format('Y-m-d');
    @endphp

                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label>Batch Creation Date *</label>
                                        <input type="date" class="form-control" name="start_date" value="{{$formattedDate }}" readonly>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label>QC Inward Date *</label>
                                        <input type="date" class="form-control" name="inward_doc_date" 
                                        value="{{ $batchcards->inward_doc_date ?? date('Y-m-d') }}">
                                    </div>
                                        
                                    <!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Batch Card No *</label>
                                            <input type="text" class="form-control"  value="{{$batchcards->batch_no ?? 'N/A'}}" name="batch_no" placeholder="Batch Card No" readonly>
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>SKU Code *</label>
                                            <textarea value="" class="form-control" name="sku_code" placeholder="Sku code"readonly>{{$batchcards->sku_code ?? 'N/A'}}</textarea>
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Item Description *</label>
                                            <textarea value="" class="form-control" name="description" placeholder="Description"readonly>{{$batchcards->discription ?? 'N/A'}}</textarea>
                                          
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Product Group *</label>
                                            <input type="text" class="form-control"  value="{{$batchcards->groupname ?? 'N/A'}}" name="product_group" placeholder="Product Group" readonly>
                                        </div>
                                        
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Material Lot No</label>
                                            @if(!empty($batchcards->lot_number))
                                                <input type="text" class="form-control" name="material_lot_no" value="{{ $batchcards->lot_number }}" readonly>
                                            @else
                                             @php
                                                $selected_batches = !empty($batchcards->multiple_batch) ? explode(',', $batchcards->multiple_batch) : [];
                                            @endphp
                                            <select class="form-control batchcard_no" name="material_lot_no[]" id="batchcard_no" multiple>
                                                <option value="">--- select one ---</option>
                                                @foreach ($selected_batches as $batch) 
                                                    <option value="{{ $batch }}" {{ in_array($batch, $selected_batches) ? 'selected' : '' }}>
                                                        {{ $batch }}
                                                    </option>
                                                @endforeach
                                            </select>
                                                 @if(empty($selected_batches))
                                                    <small class="text-muted d-block mt-2">No batchcards or lot number available. You may enter a lot number manually below:</small>
                                                    <input type="text" name="manual_material_lot_no" class="form-control mt-1" placeholder="Enter material lot number">
                                                @endif
                                            @endif
                                            
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>BatchCard Inward Qty *</label>
                                            <input type="text" class="form-control" name="batchcard_inward_qty" placeholder="Batchcard Inward Quantity" value="{{$batchcards->quantity ?? 'N/A' }}"  required>
                                        </div>
                                      
                                    </div>
                                    </br> 
                        
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <input type="hidden" name="product_id" value="{{$batchcards->product_id}}">
                                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                                 aria-hidden="true"></span> <i class="fas fa-save"></i>
                                                Save
                                            
                                            </button>
                                        </div>
                                    </div>
                                    <!-- <div class="form-devider"></div> -->
                                </form>
                            </div>
                          
                        </div>
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
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

<script>
    // $(document).ready(function () {
    //     const endDateInput = $('#pending_status_group');
    //     const settledOption = $('.settled_option');

    //     function toggleSettledOption() {
    //         if (endDateInput.val()) {
    //             settledOption.show();
    //         } else {
    //             settledOption.hide();
    //         }
    //     }
    //     toggleSettledOption();
    //     endDateInput.on('input', toggleSettledOption);
    // });
    $(document).ready(function () {
    const acceptedQuantityInput = $('#accepted_quantity');
    const endDateInput = $('#pending_status_group');
    const pendingStatusContainer = $('#pending_status_container');
    const settledOption = $('.settled_option');

    function updateVisibility() {
        const acceptedQuantity = parseFloat(acceptedQuantityInput.val()) || 0; // Ensure value is a number
        const endDate = endDateInput.val();

        // Show Pending Status dropdown only if accepted quantity > 0
        if (acceptedQuantity > 0) {
            pendingStatusContainer.show();

            // Show "Settled" option only if both conditions are met
            if (endDate) {
                settledOption.show();
            } else {
                settledOption.hide();
            }
        } else {
            pendingStatusContainer.hide();
            settledOption.hide(); // Ensure "Settled" is hidden if quantity <= 0
        }
    }

    // Initial check on page load
    updateVisibility();

    // Event listeners for input changes
    acceptedQuantityInput.on('input', updateVisibility);
    endDateInput.on('input', updateVisibility);
});


    $('.inspector').select2({
    placeholder: "--- select one ---", 
    allowClear: true,
    closeOnSelect: false,
    theme: "classic",
});
$(document).ready(function() {
    $('#rejected_qty').on('input', function() {
        const rejectedQty = $(this).val().trim();
        if (rejectedQty) {
            $('#rejected_reason_container').show();
        } else {
            $('#rejected_reason_container').hide().val('');
            $('#rejected_reason_container').find('input, textarea, select').val('');
        }
    });
});


$('.batchcard_no').select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search',
            minimumInputLength: 5,
            allowClear: true,
            multiple: true,
            ajax: {
                url: "{{url('quality/batchcardSearch')}}",
                processResults: function (data) {
                return {
                        results: data
                    };
                }
            }
        });

        $('.inspector').select2({
    placeholder: "--- select one ---", 
    allowClear: true,
    closeOnSelect: false,
    theme: "classic",
});
</script>


@stop