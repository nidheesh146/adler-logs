@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="" style="color: #596881;">RAW MATERIAL</a></span> 
                <span><a href="" style="color: #596881;">
                FIXED RATE RAW MATERIAL 
                </a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">Fixed Rate Raw Materials
			<button style="float: right;font-size: 14px;" onclick="document.location.href='fixed-rate/add'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Fixed Rate Row Material</button>
            </h4>
			
		   @if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
		   </div>
		   @endif
			<div class="tab-content">
				<div class="row row-sm mg-b-20 mg-lg-b-0">
						<div class="table-responsive" style="margin-bottom: 13px;">
							<table class="table table-bordered mg-b-0">
								<tbody>
									<tr>
										<style>
											.select2-container .select2-selection--single {
												height: 26px;
												/* width: 122px; */
											}
											.select2-selection__rendered {
												font-size:12px;
											}
										</style>
										<form autocomplete="off" id="formfilter">
											<th scope="row">
												<div class="row filter_search" style="margin-left: 0px;">
													<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
														<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
															<label>Item Code:</label>
															<input type="text" value="{{request()->get('item_code')}}" name="item_code"  id="item_code" class="form-control" placeholder="ITEM CODE">
														</div><!-- form-group -->
									
														<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
															<label  style="font-size: 12px;">Supplier</label>
															<input type="text" value="{{request()->get('supplier')}}" id="type1" class="form-control" name="supplier" placeholder="SUPPLIER">
														</div> 					
													</div>
													<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
														<!-- <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;"> -->
															<label style="width: 100%;">&nbsp;</label>
															<button type="submit" class="badge badge-pill badge-primary search-btn" 
															onclick="document.getElementById('formfilter').submit();"
															style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
															@if(count(request()->all('')) > 1)
																<a href="{{url()->current();}}" class="badge badge-pill badge-warning"
																style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
															@endif
														<!-- </div>  -->
													</div>
												</div>
											</th>
										</form>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
                    <form action="{{ route('purchase.applyChanges') }}" method="POST">
    @csrf

    <!-- Hidden field for selected checkboxes -->
    <input type="hidden" id="selectedItems" name="selected_items[]" value="">

    <!-- Activation Date -->
    <div class="mb-3" id="date-container" style="display: none;">
        <label for="activation_date">New Expiry Date:</label>
        <input type="date" id="activation_date" class="form-control form-control-sm w-50" name="activation_date">
    </div>

    <!-- Apply Changes Button -->
    <button type="submit" id="applyChangesBtn" class="btn btn-primary mt-2" style="display:none;">Apply Changes</button>
</form>
<button type="button" id="resetSelectionBtn" class="btn btn-warning mt-2" style="display: none;">Reset Selection</button>
<div class="d-flex align-items-center mt-2">
    <button type="button" id="resetSelectionBtn" class="btn btn-warning me-2" style="display: none;">
        Reset
    </button>
    <p class="mb-0 text-danger" id="message-warning"><small>Click the reset button after updating the expiry dates.</small></p>
</div>

    <div class="table-responsive">
        <table class="table table-bordered mg-b-0" id="example1">
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Supplier</th>
                    <th>Rate</th>
                    <th>Rate Expiry Start Date</th>
                    <th>Rate Expiry End Date</th>
                    <th>GST</th>
                    <th>Discount</th>
                    <th>Delivery Within</th>
                    <th>Currency</th>
                    <th>Action</th>
                    <th>Status</th>
                    <th>Select</th> <!-- Checkbox Column -->
                </tr>
            </thead>
            <tbody>
                @foreach($data['items'] as $item)
                <tr>
                    <td>{{$item['item_code']}}</td>
                    <td>{{$item['vendor_name']}}</td>
                    <td>{{$item['rate']}}</td>
                    <td>{{$item['rate_expiry_startdate'] ? date('d-m-Y',strtotime($item['rate_expiry_startdate'])) : '-'}}</td>
                    <td>{{$item['rate_expiry_enddate'] ? date('d-m-Y',strtotime($item['rate_expiry_enddate'])) : '-'}}</td>
                    <td>
                        @if($item['gst']==NULL)
                            -
                        @else
                            @if($item['igst']!=0) IGST:{{$item['igst']}}% &nbsp; @endif
                            @if($item['sgst']!=0) SGST:{{$item['sgst']}}%, &nbsp; @endif
                            @if($item['sgst']!=0) CGST:{{$item['sgst']}}% @endif
                        @endif
                    </td>
                    <td>{{($item['discount']!=NULL) ? $item['discount'] : 0}}</td>
                    <td>{{$item['delivery_within']}} Days</td>
                    <td>{{$item['currency_code']}}</td>
                    <td>
                        <a href="{{url('row-material/fixed-rate/edit/'.$item['id'])}}" class="badge badge-success">
                            <i class="fas fa-edit"></i> Edit
                        </a> 
                    </td>
                    <td>
                        @if($item['is_active']==2)
                            <a href="{{url('row-material/fixed-rate/status/'. $item['id'] . '/1')}}" 
                               onclick="return confirm('Are you sure you want to Activate this ?');" 
                               class="badge badge-success">
                                <i class="fas fa-edit"></i> Active
                            </a> 
                        @elseif($item['is_active']==1)
                            <a href="{{url('row-material/fixed-rate/status/'. $item['id'] . '/2')}}" 
                               onclick="return confirm('Are you sure you want to Deactivate this ?');" 
                               class="badge badge-danger">
                                <i class="fas fa-edit"></i> Deactive
                            </a> 
                        @endif
                    </td>
                    <td>
                        <input type="checkbox" class="item-checkbox" data-id="{{$item['id']}}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="box-footer clearfix">
            {{ $data['items']->appends(request()->input())->links() }}
        </div> 
    </div>
</div>



<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".item-checkbox");
    const dateContainer = document.getElementById("date-container");
    const activationDate = document.getElementById("activation_date");
    const selectedItemsInput = document.getElementById("selectedItems");
    const applyChangesBtn = document.getElementById("applyChangesBtn");
    const resetSelectionBtn = document.getElementById("resetSelectionBtn"); // Reset Button
	const resetmessage=document.getElementById("message-warning");

    function updateVisibility() {
        let hasCheckedItems = document.querySelectorAll(".item-checkbox:checked").length > 0;
		if (resetmessage) resetmessage.style.display=hasCheckedItems ? "block" : "none";
        if (dateContainer) dateContainer.style.display = hasCheckedItems ? "block" : "none";
        if (applyChangesBtn) applyChangesBtn.style.display = hasCheckedItems ? "block" : "none";
        if (resetSelectionBtn) resetSelectionBtn.style.display = hasCheckedItems ? "inline-block" : "none"; // Show/Hide Reset Button
    }

    function loadStoredValues() {
        let storedSelectedItems = JSON.parse(sessionStorage.getItem("selectedItems") || "[]");
        let savedDate = sessionStorage.getItem("activation_date");

        checkboxes.forEach(checkbox => {
            checkbox.checked = storedSelectedItems.includes(checkbox.dataset.id);
        });

        if (activationDate && savedDate) {
            activationDate.value = savedDate;
        }

        if (selectedItemsInput) {
            selectedItemsInput.value = storedSelectedItems.join(",");
        }

        updateVisibility();
    }

    function saveToStorage() {
        let storedSelectedItems = JSON.parse(sessionStorage.getItem("selectedItems") || "[]");
        let selectedIds = Array.from(document.querySelectorAll(".item-checkbox:checked")).map(cb => cb.dataset.id);
        
        let mergedSelectedItems = [...new Set([...storedSelectedItems, ...selectedIds])];

        sessionStorage.setItem("selectedItems", JSON.stringify(mergedSelectedItems));

        if (activationDate) {
            sessionStorage.setItem("activation_date", activationDate.value);
        }

        updateVisibility(); // Ensure visibility updates
    }

    document.addEventListener("change", function (event) {
        if (event.target.classList.contains("item-checkbox")) {
            saveToStorage();
        }
    });

    if (activationDate) {
        activationDate.addEventListener("input", function () {
            sessionStorage.setItem("activation_date", activationDate.value);
        });
    }

    if (applyChangesBtn) {
        applyChangesBtn.addEventListener("click", function () {
            let storedSelectedItems = JSON.parse(sessionStorage.getItem("selectedItems") || "[]");
            if (selectedItemsInput) {
                selectedItemsInput.value = storedSelectedItems.join(",");
            }
        });
    }

    if (resetSelectionBtn) {
        resetSelectionBtn.addEventListener("click", function () {
            checkboxes.forEach(checkbox => (checkbox.checked = false));
            sessionStorage.removeItem("selectedItems");
            sessionStorage.removeItem("activation_date");

            if (selectedItemsInput) selectedItemsInput.value = "";
            if (activationDate) activationDate.value = "";

            updateVisibility();
        });
    }

    document.addEventListener("ajaxComplete", function () {
        setTimeout(loadStoredValues, 100);
    });

    loadStoredValues();
});


</script>



@stop