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
                <span><a href="" style="color: #596881;">FGS Item Master</a></span>
                <span><a href="" style="color: #596881;">
                        FGS Report
                    </a></span>
            </div>
             <h4 class="az-content-title" style="font-size: 20px;">FGS Report
            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/fgs-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
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
                                                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-4 row">
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">Item Code</label>
                                                        <input type="text"  id="item_code" class="form-control" value="{{request()->get('item_code')}}" name="item_code" placeholder="Item Code">
                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">From Date</label>
                                                        <input type="text"  id="from" class="form-control datepicker" value="{{request()->get('from')}}" name="from" placeholder="Month(MM-YYYY)">
                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">To Date</label>
                                                        <input type="text"  id="to" class="form-control datepicker" value="{{request()->get('to')}}" name="to" placeholder="Month(MM-YYYY)">
                                                    </div>
                                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
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
                                    <th rowspan="2">Batch </th>
                                    <th rowspan="2">Description </th>
                                    <th rowspan="2">Date of Mfg. </th>
                                    <th rowspan="2">Date of Expiry. </th> 
                                    <th colspan="4">MRN</th>
                                    <th colspan="4">OEF</th>
                                    <th colspan="4">COEF</th>
                                    <th colspan="3">GRS</th>
                                    <th colspan="3">CGRS</th>
                                    <th colspan="3">PI</th>
                                    <th colspan="3">CPI</th>
                                    <th colspan="3">MIN</th>
									<th colspan="3">CMIN</th>
                                    <th colspan="3">MTQ</th>
                                    <th colspan="3">CMTQ</th>
                                    <th colspan="3">MIS</th>
                                    
                                </tr>
                                <tr>
                                    <td>MRN number</td>
                                    <td>Qty</td>
                                    <td>MRN date</td>
                                    <td>WEF</td>

                                    <td>OEF number</td>
                                    <td>Qty</td>
                                    <td>OEF date</td>
                                    <td>WEF</td>

                                    <td>COEF number</td>
                                    <td>Qty</td>
                                    <td>COEF date</td>
                                    <td>WEF</td>

                                    <td>GRS number</td>
                                    <td >GRS date</td>
                                    <td>WEF</td>

                                    <td>CGRS number</td>
                                    <td>CGRS date</td>
                                    <td>WEF</td> 

                                    <td>PI number</td>
                                    <td>Qty</td>
                                    <td>PI date</td>

                                    <td>CPI number</td>
                                    <td>Qty</td>
                                    <td>CPI date</td>

                                    <td>MIN number</td>
                                    <td>MIN date</td>
                                    <td>WEF</td>

                                    <td>CMIN number</td>
                                    <td>CMIN date</td>
                                    <td>WEF</td>

                                    <td>MTQ number</td>
                                    <td>MTQ date</td>
                                    <td>WEF</td>

                                    <td>CMTQ number</td>
                                    <td>CMTQ date</td>
                                    <td>WEF</td>

                                    <td>MIS number</td>
                                    <td>MIS date</td>
                                    <td>WEF</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$item->sku_code}}</td>
                                    <td>{{$item->batch_no}}</td>
                                    <td>{{$item->discription}}</td>
                                    <td>{{date('d-m-Y',strtotime($item->manufacturing_date))}}</td>
                                    <td>@if($item['expiry_date']!='0000-00-00') {{date('d-m-Y', strtotime($item['expiry_date']))}} @else NA  @endif</td> 
                                    <!-- mrn -->
                                    <td>{{$item->mrn_number}}</td>
                                    <td>{{$item->quantity}} Nos</td>
                                    <td>{{date('d-m-Y',strtotime($item->mrn_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($item->mrn_wef))}}</td>
                                    <!-- oef -->
                                    <?php $oef_data=$fn->getOEFDetails($item->mrn_item_id);?>
                                    @if($oef_data)
                                    <td>{{$oef_data->oef_number}}</td>
                                    <td>{{$oef_data->remaining_qty_after_cancel}}Nos</td>
                                    <td>{{date('d-m-Y',strtotime($oef_data->oef_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($oef_data->oef_wef))}}</td> 
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td> 
                                    @endif       
                                    <!-- coef -->
                                    @if($oef_data)
                                    <?php $coef_data=$fn->getCOEFDetails($oef_data->oef_item_id);?>
                                    @endif
                                    @if( $oef_data && $coef_data)
                                    <td>{{$coef_data->coef_number}}</td>
                                    <td>{{$coef_data->quantity}}Nos</td>
                                    <td>{{date('d-m-Y',strtotime($coef_data->coef_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($coef_data->coef_wef))}}</td>         
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @endif
                                    <!-- GRS -->
                                    <?php $grs_datas = $fn->getGRSDetails($item->mrn_item_id); ?>
                                    @if($grs_datas)
                                    <td>
                                        @foreach($grs_datas as $grs_data)
                                        {{$grs_data->grs_number}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($grs_datas as $grs_data)
                                        {{date('d-m-Y',strtotime($grs_data->grs_date))}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($grs_datas as $grs_data)   
                                        {{date('d-m-Y',strtotime($grs_data->grs_wef))}}<br/>
                                        @endforeach
                                    </td>
                                    <!-- <td></td>    -->
                                    @else  
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                    @endif     
                                    
                                    <!-- CGRS -->
                                    @if($grs_datas)
                                        <td>
                                            @foreach($grs_datas as $grs_data)
                                            <?php $cgrs_data = $fn->getCGRSDetails($grs_data->grs_item_id); ?>
                                            @if($cgrs_data) 
                                                {{$cgrs_data->cgrs_number}}<br/>
                                            @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($grs_datas as $grs_data)
                                            <?php $cgrs_data = $fn->getCGRSDetails($grs_data->grs_item_id); ?>
                                            @if($cgrs_data) 
                                                {{date('d-m-Y',strtotime($cgrs_data->cgrs_date))}}<br/>
                                            @endif
                                            @endforeach
                                            
                                        </td>
                                        <td>@foreach($grs_datas as $grs_data)
                                            <?php $cgrs_data = $fn->getCGRSDetails($grs_data->grs_item_id); ?>
                                            @if($cgrs_data) 
                                                {{date('d-m-Y',strtotime($cgrs_data->cgrs_wef))}}<br/>
                                            @endif
                                            @endforeach
                                        </td>  
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
                                        {{$pi_data->pi_number}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($pi_datas as $pi_data)
                                        {{$pi_data->remaining_qty_after_cancel}} Nos<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($pi_datas as $pi_data)
                                        {{date('d-m-Y',strtotime($pi_data->pi_date))}}<br/>
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
                                        {{$cpi_data->cpi_number}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($cpi_datas as $cpi_data)
                                        {{$cpi_data->quantity}} Nos<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($cpi_datas as $cpi_data)
                                        {{date('d-m-Y',strtotime($cpi_data->cpi_date))}}<br/>
                                        @endforeach
                                    </td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @endif
                                           
                                    
                                    <!-- min -->
                                    <?php $min_datas = $fn->getMINDetails($item->batch_id); ?>
                                    @if($min_datas)
                                    <td>
                                        @foreach($min_datas as $min_data)
                                        {{$min_data->min_number}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($min_datas as $min_data)
                                        {{date('d-m-Y',strtotime($min_data->min_date))}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($min_datas as $min_data)
                                        {{date('d-m-Y',strtotime($min_data->min_wef))}}<br/>
                                        @endforeach
                                    </td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @endif
                                             
                                
                                    <!-- cmin -->
                                    <?php $cmin_datas = $fn->getCMINDetails($item->batch_id); ?>
                                    @if($cmin_datas)
                                    <td>
                                        @foreach($cmin_datas as $cmin_data)
                                        {{$cmin_data->cmin_number}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($cmin_datas as $cmin_data)
                                        {{date('d-m-Y',strtotime($cmin_data->cmin_date))}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($cmin_datas as $cmin_data)
                                        {{date('d-m-Y',strtotime($cmin_data->cmin_wef))}}<br/>
                                        @endforeach
                                    </td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @endif       
                                              
                                    
                                    <!-- dni -->
                                   
                                     <!-- mtq -->
                                     <?php $mtq_datas = $fn->getMTQDetails($item->batch_id); ?>
                                    @if($mtq_datas)
                                    <td>
                                        @foreach($mtq_datas as $mtq_data)
                                        {{$mtq_data->mtq_number}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($mtq_datas as $mtq_data)
                                        {{date('d-m-Y',strtotime($mtq_data->mtq_date))}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($mtq_datas as $mtq_data)
                                        {{date('d-m-Y',strtotime($mtq_data->mtq_wef))}}<br/>
                                        @endforeach
                                    </td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @endif   
                                     <!-- cmtq -->
                                     <?php $cmtq_datas = $fn->getCMTQDetails($item->batch_id); ?>
                                    @if($cmtq_datas)
                                    <td>
                                        @foreach($cmtq_datas as $cmtq_data)
                                        {{$cmtq_data->cmtq_number}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($cmtq_datas as $cmtq_data)
                                        {{date('d-m-Y',strtotime($cmtq_data->cmtq_date))}}<br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($cmtq_datas as $cmtq_data)
                                        {{date('d-m-Y',strtotime($cmtq_data->cmtq_wef))}}<br/>
                                        @endforeach
                                    </td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @endif  
                                    
                                    <!--mis -->
                                    @if($mtq_datas)
                                        <td>
                                            @foreach($mtq_datas as $mtq_data)
                                            <?php $mis_data = $fn->getMISDetails($mtq_data->mtq_item_id); ?>
                                            @if($mis_data) 
                                                {{$mis_data->mis_number}}<br/>
                                            @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($mtq_datas as $mtq_data)
                                            <?php $mis_data = $fn->getMISDetails($mtq_data->mtq_item_id); ?>
                                            @if($mis_data)  
                                                {{date('d-m-Y',strtotime($mis_data->mis_date))}}<br/>
                                            @endif
                                            @endforeach
                                            
                                        </td>
                                        <td>
                                            @foreach($mtq_datas as $mtq_data)
                                            <?php $mis_data = $fn->getMISDetails($mtq_data->mtq_item_id); ?>
                                            @if($mis_data)  
                                                {{date('d-m-Y',strtotime($mis_data->mis_wef))}}<br/>
                                            @endif
                                            @endforeach
                                        </td>  
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
        var from = $('#from').val();
        var to = $('#to').val();
        var brand = $('#brand').val();
        if (!item_code & !from & !to ) {
            e.preventDefault();
        }
    });
</script>


@stop