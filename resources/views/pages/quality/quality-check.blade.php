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
                <span><a href="" style="color: #596881;">QUALITY INSPECTION</a></span> 
                <span><a href="">QUALITY INSPECTION</a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">QUALITY INSPECTION</h4>
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
                                <form  action="{{url('quality/quality-check')}}" method="POST" autocomplete="off" >
                                    {{ csrf_field() }}  
                                    
                                    <div class="row">
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label>Batch Creation Date *</label>
                                        <input type="text" class="form-control"  
                                            value="{{ !empty($batchcards->start_date) ? date('Y-m-d', strtotime($batchcards->start_date)) : '' }}"  name="batch_creation_date" 
                                            placeholder="Start Date" 
                                            readonly>
                                    </div>
                                
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Batch Card No *</label>
                                            <input type="text" class="form-control"  value="{{$batchcards->batch_no ?? 'N/A'}}" name="batch_no" placeholder="Batch Card No" readonly>
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>SKU code *</label>
                                            <textarea value="" class="form-control" name="sku_name" placeholder="SKU Code"readonly>{{$batchcards->sku_code ?? 'N/A'}}</textarea>
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Item Description *</label>
                                            <textarea value="" class="form-control" name="description" placeholder="Description"readonly>{{$batchcards->discription ?? 'N/A'}}</textarea>
                                          
                                        </div><!-- form-group -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Product Group *</label>
                                            <input type="text" class="form-control"  value="{{$batchcards->groupname ?? 'N/A'}}" name="product_group" placeholder="Product Group" readonly>
                                        </div>
                                        @if(!empty($batchcards->multiple_batch))
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Material Lot No*</label>
                                            @if(!empty($batchcards->lot_number))
                                                <input type="text" class="form-control" name="material_lot_no" value="{{ $batchcards->lot_number }}" readonly>
                                            @else
                                            @php
                                                $selected_batches = !empty($batchcards->multiple_batch) ? explode(',', $batchcards->multiple_batch) : [];
                                            @endphp
                                            <select class="form-control batchcard_no" name="material_lot_no[]" id="batchcard_no" multiple required>
                                                <option value="">--- select one ---</option>
                                                @foreach ($selected_batches as $batch) 
                                                    <option value="{{ $batch }}" {{ in_array($batch, $selected_batches) ? 'selected' : '' }}>
                                                        {{ $batch }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </div>
                                        @else
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Material Lot No*</label>
                                            @if(!empty($batchcards->lot_number))
                                                <input type="text" class="form-control" name="material_lot_no" value="{{ $batchcards->lot_number }}" readonly>
                                            @else
                                                <select class="form-control batchcard_no" name="material_lot_no[]" id="batchcard_no" multiple required>
                                                    <option value="">--- select one ---</option>
                                                </select>
                                            @endif
                                        </div>
                                        @endif
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label>QC Inward Date *</label>
                                        <input type="date" class="form-control"  
                                            value="{{ !empty($batchcards->inward_doc_date) ? date('Y-m-d', strtotime($batchcards->inward_doc_date)) : '' }}" 
                                            name="inward_doc_date" 
                                            placeholder="Inward doc Date" 
                                            required>
                                    </div>

                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>Inspection Start Date *</label>
                                            <input type="date" class="form-control"  value="{{date('Y-m-d')}}" name="start_date" placeholder="Start Date" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>Inspection Start Time *</label>
                                                <input type="time" class="form-control start_time" value="{{ date('H:i') }}" name="start_time" placeholder="Start Time" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>Inspection End Date *</label>
                                            <input type="date" class="form-control end_date"  id="pending_status_group" value="" name="end_date" placeholder="End Date" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>Inspection End Time *</label>
                                                <input type="time" class="form-control end_time" value="" name="end_time" placeholder="End Time">
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>BatchCard Inward Qty *</label>
                                            <input type="text" class="form-control" name="batchcard_inward_qty" placeholder="Batchcard Inward Quantity" value="{{$batchcards->quantity ?? 'N/A' }}"  required>
                                        </div>
                                     
                                
                                        <!-- <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Inspector Name *</label>
                                                <select class="form-control inspector" name="inspector_name[]"  multiple="multiple" required>
                                                         <option value="">--- select one ---</option>
                                                    @if(!empty($usernames))
                                                    @foreach($usernames as $username)
                                                        <option value="{{ $username }}">{{ $username }}</option>
                                                    @endforeach
                                                @endif
                                                </select>
                                        </div> -->
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>Inspector Name *</label>
    <select class="form-control inspector" name="inspector_name[]" multiple="multiple" required>
        <option value="">--- select one ---</option>
        <option value="Satyawan Jadhav">Satyawan Jadhav</option>
        <option value="Ganesh Bothare">Ganesh Bothare</option>
        <option value="Sumit Vele">Sumit Vele</option>
        <option value="Mahesh Bandagle">Mahesh Bandagle</option>
        <option value="Santosh Gopal">Santosh Gopal</option>
        <option value="Ravindra Zepale">Ravindra Zepale</option>
        <option value="Pravin Sawant">Pravin Sawant</option>
        <option value="Ramesh Patere">Ramesh Patere</option>
        <option value="Prasad Sawant">Prasad Sawant</option>
        <option value="Rupesh Kirve">Rupesh Kirve</option>
        <option value="Rushikesh Surve">Rushikesh Surve</option>
        <option value="Ashish Salaskar">Ashish Salaskar</option>
        <option value="Amol Mane">Amol Mane</option>
        <option value="Rajendra Ghanekar">Rajendra Ghanekar</option>
        <option value="Kunal Pawar">Kunal Pawar</option>
        <option value="Aswin">Aswin</option>
        <option value="Chandrakant patere">Chandrakant patere</option>
    </select>
</div>

                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Inspected Qty *</label>
                                            <input type="text" class="form-control"  value="" name="inspected_qty"  placeholder="Inspected Quantity"required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Accepted Qty *</label>
                                            <input type="text" class="form-control" id="accepted_quantity" value="" name="accepted_quantity" placeholder="Accepted Quantity" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Rejected Qty </label>
                                            <input type="text" class="form-control" id="rejected_qty"  value="" name="rejected_qty" placeholder="Rejected Quantity" >
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6" id="rejected_reason_container" style="display: none;">
                                            <label>Rejected Reason </label>
                                            <textarea class="form-control" name="rejected_reason" placeholder="Reason for Rejection"></textarea>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Rework Qty </label>
                                            <input type="text" class="form-control"  id="rework_quantity"  value="0" name="rework_quantity" placeholder="Rework Quantity" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6" style="display: none;">
                                            <label>Rework Reason</label>
                                            <textarea value="" class="form-control rework" name="rework_reason" id="rework_reason" placeholder=" Rework Reason"></textarea>
                                        </div>
                                       
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Remaining Qty </label>
                                            <input type="text" class="form-control"  id="remaining_quantity"  value="0" name="remaining_quantity" placeholder="Remaining Quantity" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6" id="remaining_reason_container" style="display: none;">
                                            <label>Remaining Reason </label>
                                            <textarea class="form-control" name="remaining_reason" placeholder="Remaining Reason"></textarea>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Accepted Qty with deviation </label>
                                            <input type="text" class="form-control"  value="" name="accepted_quantity_with_deviation" placeholder="Acepted qty with deviation" >
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6" style="display: none;">
                                            <label>Reason for deviation </label>
                                            <textarea value="" class="form-control remark" name="reason_for_deviation" id="reason_for_deviation" placeholder="Reason for deviation"></textarea>
                                        </div>
                                      
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Pending Status *</label>
                                            <select class="form-control" name="pending_status" required>
                                                <option value="0">----Select----</option>
                                                <option value="1" class="settled_option">Settled</option>
                                                <option value="0">Pending</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Remarks *</label>
                                            <textarea value="" class="form-control remark" name="remark" placeholder="Remarks" required></textarea>
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
   $(document).ready(function () {
    const acceptedQuantityInput = $('#accepted_quantity');
    const rejectedQuantityInput = $('#rejected_qty');
    const reworkQuantityInput = $('#rework_quantity');
    const remainingQuantityInput = $('#remaining_quantity');
    const inspectedQuantityInput = $('input[name="inspected_qty"]');
    const deviationQuantityInput = $('input[name="accepted_quantity_with_deviation"]');
    const endDateInput = $('#pending_status_group'); // Fixed typo
    const pendingStatusSelect = $('select[name="pending_status"]');
    const settledOption = $('.settled_option');

    function updateVisibility() {
        const acceptedQuantity = parseFloat(acceptedQuantityInput.val()) || 0;
        const rejectedQuantity = parseFloat(rejectedQuantityInput.val()) || 0;
        const reworkQuantity = parseFloat(reworkQuantityInput.val()) || 0;
        const remainingQuantity = parseFloat(remainingQuantityInput.val()) || 0;
        const deviationQuantity = parseFloat(deviationQuantityInput.val()) || 0;
        const inspectedQuantity = acceptedQuantity + rejectedQuantity + reworkQuantity + remainingQuantity + deviationQuantity;

        // Update inspected quantity dynamically
        inspectedQuantityInput.val(inspectedQuantity);

        // Show reason containers if respective quantities have values
        $('#rejected_reason_container').toggle(rejectedQuantity > 0);
        $('#rework_reason').closest('.form-group').toggle(reworkQuantity > 0);
        $('#remaining_reason_container').toggle(remainingQuantity > 0);

        // Ensure "Settled" option is selected if end_date exists
        if (endDateInput.val().trim() !== "") {
            pendingStatusSelect.val("1"); // Settled
            settledOption.show();
        } else {
            settledOption.hide();
        }

        // Automatically select "Settled" if total processed equals inspected quantity
        if (inspectedQuantity > 0) {
            pendingStatusSelect.val("1"); // Settled
        } else {
            pendingStatusSelect.val("0"); // Pending
        }
    }

    // Initial check on page load
    updateVisibility();

    // Event listeners for input changes
    acceptedQuantityInput.on('input', updateVisibility);
    rejectedQuantityInput.on('input', updateVisibility);
    reworkQuantityInput.on('input', updateVisibility);
    remainingQuantityInput.on('input', updateVisibility);
    deviationQuantityInput.on('input', updateVisibility);
    endDateInput.on('input', updateVisibility);

    // Show "Reason for Deviation" when "Accepted Qty with Deviation" has a value
    deviationQuantityInput.on('input', function() {
        const acceptedQtyDeviation = $(this).val().trim();
        $('textarea[name="reason_for_deviation"]').closest('.form-group').toggle(!!acceptedQtyDeviation);
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