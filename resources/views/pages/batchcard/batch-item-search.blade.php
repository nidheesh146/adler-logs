@extends('layouts.default')
@section('content')
@php
use App\Http\Controllers\Web\BatchCardController;
$fn= new BatchCardController;
@endphp
<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;">Batch Card</a></span>
                <span><a href="" style="color: #596881;">
                        Batch Item
                    </a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;">Item Report
                {{--<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('batchcard/batch-item-search-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>--}}
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
                                            font-size: 12px;
                                        }
                                    </style>
                                    <form autocomplete="off">
                                        <th scope="row">
                                            <div class="row filter_search" style="margin-left: 0px;">
                                                <div class="col-sm-4 col-md-12 col-lg-12 col-xl-12 row">
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">Item Code</label>
                                                        <input type="text" id="item_code" class="form-control" value="{{request()->get('item_code')}}" name="item_code" placeholder="Item Code">
                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label>Batch No:</label>
                                                        <input type="text" value="{{request()->get('batch_no')}}" name="batch_no" id="batch_no" class="form-control" placeholder="BATCH NO">
                                                    </div>
                                                    {{--<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">To Date</label>
                                                        <input type="text" id="to" class="form-control datepicker" value="{{date('m-Y')}}" name="to" placeholder="Month(MM-YYYY)">
                                                </div>--}}
                                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
                                                    <label style="width: 100%;">&nbsp;</label>
                                                    <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                    @if(count(request()->all('')) > 2)
                                                    <a href="{{url()->current()}}" class="badge badge-pill badge-warning" style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
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
            <div class="tab-pane tab-pane active  show" id="purchase">
                @php
                $sl=1;
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
                                <!-- <th rowspan="2">SL</th> -->
                                <th rowspan="2">Item Code </th>
                                <th rowspan="2">PR NUMBER </th>
                                <th rowspan="2">Description </th>
                                <th rowspan="2">BatchCard </th>
                                <th rowspan="2">Supplier </th>
                                <th rowspan="2">Customers </th>
                                <th>Action</th>
                            </tr>
                            
                        </thead>
                        <tbody>
                            @php
                            $sl=1;
                            @endphp
                            @foreach($data['items'] as $items)
                            @if($items['pr_no'])
                            <tr>
                                <?php $customers = $fn->getCustomers($items['batch_id']); ?>
                                <!-- <td>{{$sl++}}</td> -->
                                <td>{{$items['item_code']}}</td>
                                <td>{{$items['pr_no']}}</td>
                                <td>{{$items['discription']}}</td>
                                <td>{{$items['batch_no']}}</td>
                                <td>{{$items['vendor_name']}}</td>
                                <td>
                                    @if($customers)
                                    @foreach($customers as $customer)
                                        <ul style="margin-left:-40px;">
                                        <li>{{$customer->firm_name}}</li>
                                        </ul>
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                <a href="{{url('batchcard/item-more/'.$items['batch_id'])}}" class="badge badge-primary"> View more</a> 
                                <a href="{{url('batchcard/batch-item-pdf/'.$items['batch_id'])}}" class="badge badge-danger" target="_blank"><i class="fas fa-file-pdf"></i>&nbsp;&nbsp;PDF</button>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{$data['items']->appends(request()->input())->links();}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- az-content-body -->
</div>


<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?= url(''); ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script>
    $(function() {
        'use strict'
        var date = new Date();
        date.setDate(date.getDate());
        $(".datepicker").datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            // startDate: date,
            autoclose: true
        });

        //$('#prbody').show();
    });

    $('.search-btn').on("click", function(e) {
        var item_code = $('#item_code').val();
        var batch_no = $('#batch_no').val();

        if (!item_code & !batch_no) {
            e.preventDefault();
        }
    });
</script>


@stop