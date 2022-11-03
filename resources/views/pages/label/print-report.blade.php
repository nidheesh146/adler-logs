@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">LABEL CARD</a></span> 
                <span><a href="" style="color: #596881;">
                Label Printing Report
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Label Printing Report
			<a style="float: right;font-size: 14px;" href="{{ route('ExportPrintingData', array_merge(request()->all())) }}" class="badge badge-pill badge-dark ">
			<i class="fa fa-download"></i> 
					Excel Report
			</a>
            </h4>
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
									<form autocomplete="off">
										<th scope="row">
											<div class="row filter_search" style="margin-left: 0px;">
												<div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
								
													<div class="form-group col-sm-12 col-md-3 col-lg- col-xl-4">
														<label>BatchCard</label>
														<input type="text" value="{{request()->get('batchcard')}}" name="batchcard" id="batchcard" class="form-control" placeholder="BATCHCARD">
													</div><!-- form-group -->
													
													
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="exampleInputEmail1" style="font-size: 12px;">Label</label>
														<input type="text" value="{{request()->get('label')}}" name="label" id="label" class="form-control" placeholder="LABEL">
													</div>
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label  style="font-size: 12px;">Manufaturing Month</label>
														<input type="text" value="{{request()->get('manufaturing_from')}}" id="manufaturing_from" class="form-control datepicker" name="manufaturing_from" placeholder="Month(MM-YYYY)">
													</div>							
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
													<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
														<label style="width: 100%;">&nbsp;</label>
														<button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
														@if(count(request()->all('')) > 2)
															<a href="{{url()->current()}}" class="badge badge-pill badge-warning"
															style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
														@endif
													</div> 
												</div>
											</div>
										</th>
									</form>
								</tr>
								</tbody>
							</table>
						</div>
			
			<div class="row">    
            	<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
							<th>Batchcard </th>
							<th>Product </th>
							<th>Label</th>
							<th>No. of labels printed </th>
							<th>manufacturing Date</th>
							<th>Expiry Date</th>
						</tr>
					</thead>
					<tbody >
					@foreach($data['labels'] as $label)
						<tr>
							<td>{{$label['batch_no']}}</td>
							<td>{{$label['sku_code']}}</td>
							<td>{{$label['label_name']}}</td>
							<td>{{$label['no_of_labels_printed']}}</td>
							<td>{{$label['manufacturing_date']}}</td>
							<td>{{$label['expiry_date']}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				<div class="box-footer clearfix">
					{{ $data['labels']->appends(request()->input())->links() }}
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
		$(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });							
</script>
@stop