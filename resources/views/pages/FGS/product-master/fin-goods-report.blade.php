@extends('layouts.default')
@section('content')
@php
use App\Http\Controllers\Web\FGS\FgsreportController;
$fn=new FgsreportController();
@endphp
<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;">FGS</a></span>
                <span><a href="" style="color: #596881;">
                        FGS Sales Transaction Report
                    </a></span>
            </div>
            @include('includes.fgs.sales-trans-tab')
<br><br>
            <h4 class="az-content-title" style="font-size: 20px;">FGS Sales Transaction Report
                <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/fgs-sales-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
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
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">From Date</label>
                                                        <input type="date" id="from" class="form-control datepicker" value="{{request()->get('from')}}" name="from" placeholder="Month(MM-YYYY)">
                                                    </div>
                                                   <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">To Date</label>
                                                        <input type="date" id="to" class="form-control datepicker" value="{{request()->get('to')}}" name="to" placeholder="Month(MM-YYYY)">
                                                    </div>
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
                                    <th rowspan="2">Sl</th>
                                    <th rowspan="2">Item Code </th>
                                    <th rowspan="2">Description </th>
                                    {{--<th rowspan="2">Date of Mfg. </th>
                                    <th rowspan="2">Date of Expiry. </th> --}}
                                   <th rowspan="2">Customer Name</th>
                                    <th colspan="3">OEF</th>
                                    <th colspan="3">COEF</th>
                                    
                                    <th colspan="3">PI</th>
                                    <th colspan="3">CPI</th>
                                    <th colspan="3">DNI</th>
                                    
                                    

                                </tr>
                                <tr>
                                   

                                    <td>OEF number</td>
                                    <td>OEF date</td>
                                    <td>Qty</td>
                                    {{--<td>WEF</td> --}}

                                    <td>COEF number</td>
                                    <td>COEF date</td>
                                    <td>Qty</td>
                                    {{-- <td>WEF</td> --}}

                                   

                                   

                                    <td>PI number</td>
                                    <td>PI date</td>
                                    <td>Qty</td>

                                    <td>CPI number</td>
                                    <td>CPI date</td>
                                    <td>Qty</td>

                                    <td>DNI/EXI number</td>
                                    <td>DNI/EXI date</td>
                                    <td>Qty</td>

                                   
                                </tr>
                            </thead>
                            <tbody>
    @foreach($items as $item)
    <tr>
        <td>{{ $sl++ }}</td>
        <td>{{ $item->sku_code }}</td>
        <td>{{ $item->discription }}</td>

        <!-- OEF Data Directly from Backend Query -->
        <td>{{ $item->firm_name ?? '' }}</td> {{-- Optional, if included in backend --}}
        <td>{{ $item->oef_number ?? '' }}</td> {{-- Optional, if included in backend --}}
        <td>{{ $item->oef_date ? date('d-m-Y', strtotime($item->oef_date)) : '' }}</td>
        <td>{{ $item->remaining_qty_after_cancel ? $item->remaining_qty_after_cancel . ' Nos' : '' }}</td>

        <!-- COEF -->
        <?php $coef_data = $fn->getCOEFDetails($item->mrn_item_id); ?>
        @if($coef_data)
        <td>{{ $coef_data->coef_number }}</td>
        <td>{{ $coef_data->coef_date ? date('d-m-Y', strtotime($coef_data->coef_date)) : '' }}</td>
        <td>{{ $coef_data->quantity }} Nos</td>
        @else
        <td></td>
        <td></td>
        <td></td>
        @endif

        <!-- PI -->
        <?php $pi_datas = $fn->getPIDetails($item->mrn_item_id); ?>
        @if($pi_datas)
        <td>
            @foreach($pi_datas as $pi_data)
                {{ $pi_data->pi_number }}<br />
            @endforeach
        </td>
        <td>
            @foreach($pi_datas as $pi_data)
                {{ $pi_data->pi_date ? date('d-m-Y', strtotime($pi_data->pi_date)) : '' }}<br />
            @endforeach
        </td>
        <td>
            @foreach($pi_datas as $pi_data)
                {{ $pi_data->remaining_qty_after_cancel }} Nos<br />
            @endforeach
        </td>
        @else
        <td></td>
        <td></td>
        <td></td>
        @endif

        <!-- CPI -->
        <?php $cpi_datas = $fn->getCPIDetails($item->mrn_item_id); ?>
        @if($cpi_datas)
        <td>
            @foreach($cpi_datas as $cpi_data)
                {{ $cpi_data->cpi_number }}<br />
            @endforeach
        </td>
        <td>
            @foreach($cpi_datas as $cpi_data)
                {{ $cpi_data->cpi_date ? date('d-m-Y', strtotime($cpi_data->cpi_date)) : '' }}<br />
            @endforeach
        </td>
        <td>
            @foreach($cpi_datas as $cpi_data)
                {{ $cpi_data->quantity }} Nos<br />
            @endforeach
        </td>
        @else
        <td></td>
        <td></td>
        <td></td>
        @endif

        <!-- DNI -->
        <?php $dni_datas = $fn->getDNIDetails($item->mrn_item_id); ?>
        @if($dni_datas)
        <td>{{ $dni_datas->dni_number }}</td>
        <td>{{ $dni_datas->dni_date ? date('d-m-Y', strtotime($dni_datas->dni_date)) : '' }}</td>
        <td>{{ $dni_datas->remaining_qty_after_cancel }} Nos</td>
        @else
        <td></td>
        <td></td>
        <td></td>
        @endif
    </tr>
    @endforeach
</tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{$items->appends(request()->input())->links();}}
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
   
   
</script>


@stop