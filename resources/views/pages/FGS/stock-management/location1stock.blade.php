@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #596881;">Stock Management</a></span>
             <span><a href="">Location1</a></span>
        </div>
        @include('includes.fgs.stock-location-tab')
        <br/><br/>
       
        <div class="row ">
            <div class="col-lg-12 col-xl-12 mg-t-20 mg-lg-t-0">
                <!-- <div class="card card-table-one" style="min-height: 500px;"> -->
                    <h4 class="az-content-title" style="font-size: 20px;">
                        {{$title}}
                        <div class="right-button">
                            @if($location=='all')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/all')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @elseif($location=='location1')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/location1')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @elseif($location=='location2')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/location2')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @elseif($location=='location3')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/location3')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @elseif($location=='MAA')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/MAA')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @elseif($location=='SNN_Mktd')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/SNN')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @elseif($location=='AHPL_Mktd')
                            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/stock-report/AHPL')}}'" class="badge badge-pill badge-info "><i class="fas fa-file-excel"></i> Report</button>
                            @endif
                        <div>  
                            
                        </div>
                    </div>
                    </h4>
                    @if (Session::get('succs'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('succs') }}
                    </div>
                    @endif
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
                                 <form autocomplete="off" >
                                  <th scope="row">
                            <div class="row filter_search" style="margin-left: 0px;">
                               <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
                                  <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>SKU Code</label>
                                    <input type="text" value="{{request()->get('sku_code')}}" name="sku_code"  id="sku_code" class="form-control" placeholder="SKU Code">
                                  </div><!-- form-group -->
                                    
                                 <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                  <label  style="font-size: 12px;">BATCH No</label>
                                     <input type="text" value="{{request()->get('batch_no')}}" id="batch_no" class="form-control" name="batch_no" placeholder="BATCH No">
                                </div> 
                               <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label  style="font-size: 12px;">Product Category</label>
                                    <select name="category_name" id="category_name" class="form-control">
                                    <option value="">-- Select one ---</option>
                                 @foreach ($pcategory as $item)
                                        <option value="{{$item->category_name}}">{{$item->category_name}}</option>
                                    @endforeach
                                </select>
                               </div> 
                               <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                 <label  style="font-size: 12px;">Product Condition</label>
                                    <select name="is_sterile" id="is_sterile" class="form-control">
                                    <option value="">-- Select one ---</option>
                                 @foreach ($pcondition as $item)
                                 @if($item['is_sterile']==1)
                                        <option value="{{$item->is_sterile}}">Sterile  </option>
                                        @elseif($item['is_sterile']==0)
                                        <option value="{{$item->is_sterile}}">Non-Sterile  </option>
                                 @endif     
                                    @endforeach
                                </select>
                                                            
                                     </div> 
                                                        
                                                                            
                                 </div>
                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                 <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                  <label style="width: 100%;">&nbsp;</label>
                                  <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                   @if(count(request()->all('')) > 2)
                                      <a href="{{url()->current()}}" class="badge badge-pill badge-warning"    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
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
                    </div>
                            <p class="az-content-text mg-b-20"></p>
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0">
                                    <thead>
                                        <tr>
                                            <th>SKU Code</th>
                                            <!-- <th>Description</th> -->
                                            <th>Batch Number</th>
                                            <th>Qty.</th>
                                            <th>UOM</th>
                                            <th>Location</th>
                                            <th>Mfg. Date</th>
                                            <th>Expiry Date</th>
                                            <th>Product Type</th>
                                            <th>HSN Code</th>
                                            <th>Product Condition</th>
                                            <th>Product Category</th>
                                            <th>Product Group</th>
                                            <th>OEM</th>
                                            <th>Std. Pack Size</th>
                                        </tr>
                                    </thead>
                                    <tbody id="prbody1">
                                        @if(count($stock)>0)
                                        @foreach($stock as $stck)
                                        <tr>
                                            <td>{{$stck['sku_code']}}</td>
                                            <!-- <td>{{$stck['discription']}}</td> -->
                                            <td>{{$stck['batch_no']}}</td>
                                            <td>{{$stck['quantity']}} </td>
                                            <td>Nos </td>
                                            <td>{{$stck['location_name']}} </td>
                                            <td>{{date('d-m-Y', strtotime($stck['manufacturing_date']))}}</td>
                                            <td>@if($stck['expiry_date']!='0000-00-00') {{date('d-m-Y', strtotime($stck['expiry_date']))}} @else NA  @endif</td>
                                            <td>{{$stck['product_type_name']}}</td>
                                            <td>{{$stck['hsn_code']}}</td>
                                            <td>@if($stck['is_sterile']==1) Sterile @else Non-Sterile @endif</td>
                                            <td>{{$stck['category_name']}}</td>
                                            <td>{{$stck['group_name']}}</td>
                                            <td>{{$stck['oem_name']}}</td>
                                            <td>{{$stck['quantity_per_pack']}}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="13">
                                            <center>No data found...</center>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="box-footer clearfix">
                                    {{ $stock->appends(request()->input())->links() }}
                                </div>
                            </div><!-- table-responsive -->
                        <!-- </div>card -->
                    </div>
                </div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->

	
      

<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script>
    $("#commentForm").validate({
            rules: {
              role:{
                  required: true,
                  minlength: 1,
                  maxlength: 20
               },
               description:{
                  required: true,
                  minlength: 1,
                   maxlength: 115
               },
              
            }

     
          });
</script>
<script>
  $(function(){
    'use strict'
    var date = new Date();
    date.setDate(date.getDate());
    $(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });

    $('#prbody').show();
  });
  
    $('.search-btn').on( "click", function(e)  {
        var sku_code = $('#sku_code').val();
        var batch_no = $('#batch_no').val();
        var category_name = $('#category_name').val();
        var is_sterile = $('#is_sterile').val();
        if(!sku_code & !batch_no & !category_name & !is_sterile)
        {
            e.preventDefault();
        }
    });

</script>

@stop