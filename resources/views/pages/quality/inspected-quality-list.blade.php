@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">QUALITY</a></span> 
                <span><a href="" style="color: #596881;">
                INSPECTED QUALITY LIST 
                </a></span>
            </div>
          
            <h4 class="az-content-title" style="font-size: 20px;">Inspected Quality List
            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('quality/quality-analysis-report').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button> 
            </h4>
            
           @if (Session::get('success'))
           <div class="alert alert-success " style="width: 100%;">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
               <i class="icon fa fa-check"></i> {{ Session::get('success') }}
           </div>
           @endif
           @if (Session::get('error'))
           <div class="alert alert-danger " style="width: 100%;">
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
                                            #example1_filter{
                                                display:none;
                                            }
                                        </style>
                                        <form autocomplete="off" id="formfilter">
                                            <th scope="row">
                                                <div class="row filter_search" style="margin-left: 0px;">
                                                    <div class="col-md-12 row">
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label>BATCH NO:</label>
                                                            <input type="text" value="{{request()->get('batch_no')}}" name="batch_no"  id="batch_no" class="form-control" placeholder="BATCH NO">
                                                        </div>
                                    
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label  style="font-size: 12px;">SKU CODE:</label>
                                                            <input type="text" value="{{request()->get('sku_code')}}" id="sku_code" class="form-control" name="sku_code" placeholder="SKU NAME">
                                                        </div>      
                                                                            
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label style="font-size: 12px;">From Date</label>
                                                            <input type="text" id="from" class="form-control datepicker" 
                                                                value="{{ request()->get('from') }}" name="from" placeholder="DD-MM-YYYY">
                                                        </div>

                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label style="font-size: 12px;">To Date</label>
                                                            <input type="text" id="to" class="form-control datepicker" 
                                                                value="{{ request()->get('to') }}" name="to" placeholder="DD-MM-YYYY">
                                                        </div>
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label  style="font-size: 12px;">INSPECTOR NAME:</label>
                                                            <input type="text" value="{{request()->get('inspector_name')}}" id="inspector_name" class="form-control" name="inspector_name" placeholder="INSPECTOR NAME">
                                                        </div> 


                                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
                                                        
                                                            <label style="width: 100%;">&nbsp;</label>
                                                            <button type="submit" class="badge badge-pill badge-primary search-btn" 
                                                            onclick="document.getElementById('formfilter').submit();"
                                                            style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                            @if(count(request()->all('')) > 1)
                                                            <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
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
                    </div>
                <div class="tab-pane tab-pane active">
                    <form autocomplete="off" method="post" action="">
                    {{ csrf_field() }}  
                    <div class="table-responsive">
                        <table class="table table-bordered mg-b-0" id="example1" style="margin-top:10px;">
                            <thead>
                                <tr>
                                    <th>Batch Creation Date</th>
                                    <th>Inward Doc Date</th>
                                    <th>Batch No </th>
                                    <!-- <th>SKU code</th> -->
                                    <th>SKU code</th>
                                    <th>Item description</th>
                                    <th>Batch Inward Qty </th>
                                    <th>Material Lot No</th>
                                    <th>Inspection Start Date</th>
                                    <th>Inspection Start Time </th>
                                    <th>Inspection End Date</th>
                                    <th>Inspection End Time</th>
                                    <th>Inspected qty</th>
                                    <th>Inspector Name</th>
                                    <th>Accepted Qty</th>
                                    <th>Rejected Qty</th>
                                    <th>Rejected Reason</th>
                                    <th>Rework Qty</th>
                                    <th>Rework Reason</th>
                                    <th>Accepted Qty With Deviation</th>
                                    <th>Deviation Reason</th>
                                    <th>Remaining Qty</th>
                                    <th>Remaining Reason</th>
                                    <th>Product Group</th>
                                    <th>Pending Status</th>
                                    <th>Remark</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($checkedQuality as $card)
                                <tr>
                                    <td>@if(!empty($card['batch_creation_date'])) {{date('d-M-Y', strtotime($card['batch_creation_date']))}} @endif</td>
                                    <td>@if(!empty($card['inward_doc_date'])) {{date('d-M-Y', strtotime($card['inward_doc_date']))}} @endif</td>
                                    <td>{{$card['batch_no'] ?? 'N/A'}}</td>
                                    <td>{{$card['sku_name'] ?? 'N/A'}}</td>
                                    <td>{{$card['description'] ?? 'N/A'}}</td>
                                    <td>{{$card['batchcard_inward_qty'] ?? 'N/A'}}</td>
                                    <td> {{$card['material_lot_no'] ?? 'N/A'}}</td>
                                    <td>@if(!empty($card['start_date'])) {{date('d-M-Y', strtotime($card['start_date']))}} @endif</td>
                                    <td>{{$card['start_time']}}</td>
                                    <td>@if(!empty($card['end_date'])) {{date('d-M-Y', strtotime($card['end_date']))}} @endif</td>
                                    <td>{{$card['end_time']}}</td>
                                    <td>{{ $card['inspected_qty']}}</td>
                                    <td>{{$card['inspector_name']}}</td>
                                    <td>{{$card['accepted_quantity']}}</td>
                                    <td>{{$card['rejected_qty']}}</td>
                                    <td>{{$card['rejected_reason'] ?? 'N/A'}}</td>
                                    <td>{{$card['rework_quantity']}}</td>
                                    <td>{{$card['rework_reason']}}</td>
                                    <td>{{$card['accepted_quantity_with_deviation']}}</td>
                                    <td>{{$card['reason_for_deviation']}}</td>
                                    <td>{{$card['remaining_quantity']}}</td>
                                    <td>{{$card['remaining_reason'] ?? 'N/A'}}</td>
                                    <td>{{$card['product_group']}}</td>
                                    <td>{{ $card['pending_status'] == 1 ? 'Settled' : 'Pending' }}</td>
                                    <td>{{$card['remark']}}</td>
                                    
                                  
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                        <div class="box-footer clearfix">
                        {{ $checkedQuality->appends(request()->input())->links() }} 
                        </div> 
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- az-content-body -->
    <div class="modal fade" id="addInputMaterialModal" role="dialog">
        <div class="modal-dialog modal-xs">
              <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="display: block;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Add BatchCard Input Material<span id="batchcard_number"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                            <form method="POST" id="commentForm"  action="{{url('batchcard/add-input-material')}}" novalidate="novalidate" enctype='multipart/form-data'>
                                {{ csrf_field() }}
                                <div class="row">
                                    <table class="table table-bordered mg-b-0 sku">
                                    </table>
                                    
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <br/>
                                        <h5>Input Materials</h5>
                                        <input type="hidden" name="batch_id" id="batch_id" value="0">
                                        <input type="hidden" name="product_id" id="product_id" value="0">
                                        <table class="table table-bordered mg-b-0 input-material">
                                            
                                        </table>
                                    </div> 
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                                class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                                role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<!-- <script>
    // var dataTable = $('#example1').dataTable({
    //     "sPaginationType": "full_numbers",
    //     "ordering": false,
    // });
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

    //$('#prbody').show();
  });
  
    $('.search-btn').on( "click", function(e)  {
        //var supplier = $('#supplier').val();
        var batch_no = $('#batch_no').val();
        var sku_code = $('#sku_code').val();
        var process_sheet = $('#process_sheet').val();
        if(!batch_no & !sku_code & !process_sheet)
        {
            e.preventDefault();
        }
    });
    
    
    $(".inputmaterial-add").on( "click", function() {
        var batch_number = $(this).data('batchno');
        $('#batchcard_number').html(' ('+batch_number+')');
        var batch_id = $(this).data('batchid');
        $('#batch_id').val(batch_id);
        var sku = $(this).data('sku');
        $('.sku').html('<tr><th>SKU CODE</th><th>'+sku +'</th></tr>');
        var product_id = $(this).data('productid');
        $('#product_id').val(product_id);
        $('.input-material').html('');
        if(product_id!=0)
        {
            $.get("{{ url('batchcard/get-InputMaterial') }}?product_id="+product_id+"&&batch_id="+batch_id,function(data)
            {
                //console.log(data);
                    $('.input-material').html(data);
            });
        }
    });
    $(".check-all").click(function () {
        $('.check_batchcard').not(this).prop('checked', this.checked);
    });

</script> -->
<script>
   $(function(){
    'use strict'
    var date = new Date();
    date.setDate(date.getDate());
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",  // Use yyyy-mm-dd format for consistency with the backend
        autoclose: true
    });
});


</script>

@stop