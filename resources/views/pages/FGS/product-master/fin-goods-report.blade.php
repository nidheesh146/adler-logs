@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
@php
use App\Http\Controllers\Web\FGS\FgsreportController;
$obj_fgs=new FgsreportController();
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
            <!-- <h4 class="az-content-title" style="font-size: 20px;">Products
			<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/product-master/add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Product</button>
            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/product-master/excel-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
			</h4> -->

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
                                    <form autocomplete="off" id="formfilter" method="post" action="{{url('fgs/fgs-report-search')}}">
                                        @csrf
                                        <th scope="row">
                                            <div class="row filter_search" style="margin-left: 0px;">
                                                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-4 row">
                                                    
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">Item CODE</label>
                                                        <input type="text"  id="itm_code" class="form-control " name="itm_code" placeholder="Item CODE">
                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">From Date</label>
                                                        <input type="text"  id="group" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">
                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label style="font-size: 12px;">To Date</label>
                                                        <input type="text"  id="group" class="form-control datepicker" name="to" placeholder="Month(MM-YYYY)">
                                                    </div>


                                                </div>
                                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
                                                    <!-- <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;"> -->
                                                    <label style="width: 100%;">&nbsp;</label>
                                                    <button type="submit" class="badge badge-pill badge-primary search-btn" onclick="document.getElementById('formfilter').submit();" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                   
                                                    <a href="{{url('fgs/fgs-report')}}" class="badge badge-pill badge-warning" style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                    
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
                                     <th rowspan="2">Date </th> 
                                    <th rowspan="2">Description </th>
                                    <th colspan="4">MRN</th>
                                    <th colspan="4">OEF</th>
                                    <th colspan="4">COEF</th>
                                    <th colspan="4">PI</th>
                                    <th colspan="4">CPI</th>
                                    <th colspan="3">GRS</th>
                                    <th colspan="3">CGRS</th>
                                    <th colspan="3">MIN</th>
									<th colspan="3">CMIN</th>
                                    <th colspan="3">MIS</th>
                                    
                                    <th colspan="3">MTQ</th>
                                    <th colspan="3">CMTQ</th>
                                    
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

                                    <td>PI number</td>
                                    <td>Qty</td>
                                    <td>PI date</td>
                                    <td>WEF</td>

                                    <td>CPI number</td>
                                    <td>Qty</td>
                                    <td>CPI date</td>
                                    <td>WEF</td>

                                    <td>GRS number</td>
                                    <td>GRS date</td>
                                    <td>WEF</td>

                                    <td>CGRS number</td>
                                    <td>CGRS date</td>
                                    <td>WEF</td>

                                    <td>MIN number</td>
                                    <td>MIN date</td>
                                    <td>WEF</td>

                                    <td>CMIN number</td>
                                    <td>CMIN date</td>
                                    <td>WEF</td>

                                    <td>MIS number</td>
                                    <td>MIS date</td>
                                    <td>WEF</td>

                                    <td>MTQ number</td>
                                    <td>MTQ date</td>
                                    <td>WEF</td>

                                    <td>CMTQ number</td>
                                    <td>CMTQ date</td>
                                    <td>WEF</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product_details as $product_detail)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$product_detail->sku_code}}</td>
                                    <td>{{$product_detail->batch_no}}</td>
                                     <td>{{date('d-m-Y',strtotime($product_detail->created))}}</td> 
                                    <td>{{$product_detail->discription}}</td>
                                    <!-- mrn -->
                                    @if(!empty($obj_fgs->get_mrn($product_detail->id)))
                                    <td>{{$obj_fgs->get_mrn($product_detail->id)->mrn_number}}</td>
                                    <td>{{$obj_fgs->get_mrn($product_detail->id)->quantity}}</td>
                                    <td>{{$obj_fgs->get_mrn($product_detail->id)->mrn_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_mrn($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>          
                                    @endif
                                <!-- oef -->
                                @if(!empty($obj_fgs->get_oef($product_detail->id)))
                                    <td>{{$obj_fgs->get_oef($product_detail->id)->oef_number}}</td>
                                    <td>{{$obj_fgs->get_oef($product_detail->id)->quantity}}</td>
                                    <td>{{$obj_fgs->get_oef($product_detail->id)->oef_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_oef($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>        
                                    @endif
                                    <!-- coef -->
                                @if(!empty($obj_fgs->get_coef($product_detail->id)))
                                    <td>{{$obj_fgs->get_coef($product_detail->id)->coef_number}}</td>
                                    <td>{{$obj_fgs->get_coef($product_detail->id)->quantity}}</td>
                                    <td>{{$obj_fgs->get_coef($product_detail->id)->coef_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_coef($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>        
                                    @endif
                                    <!-- PI -->
                                    @if(!empty($obj_fgs->get_pi($product_detail->id)))
                                    <td>{{$obj_fgs->get_pi($product_detail->id)->pi_number}}</td>
                                    <td>{{$obj_fgs->get_pi($product_detail->id)->batch_qty}}</td>
                                    <td>{{$obj_fgs->get_pi($product_detail->id)->pi_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_pi($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>          
                                    @endif
                                    <!-- CPI -->
                                    @if(!empty($obj_fgs->get_cpi($product_detail->id)))
                                    <td>{{$obj_fgs->get_cpi($product_detail->id)->cpi_number}}</td>
                                    <td>{{$obj_fgs->get_cpi($product_detail->id)->batch_qty}}</td>
                                    <td>{{$obj_fgs->get_cpi($product_detail->id)->cpi_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_cpi($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>          
                                    @endif
                                    <!-- grs -->
                                    @if(!empty($obj_fgs->get_grs($product_detail->id)))
                                    <td>{{$obj_fgs->get_grs($product_detail->id)->grs_number}}</td>
                                    
                                    <td>{{$obj_fgs->get_grs($product_detail->id)->grs_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_grs($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                           
                                    @endif
                                    <!-- cgrs -->
                                    @if(!empty($obj_fgs->get_cgrs($product_detail->id)))
                                    <td>{{$obj_fgs->get_cgrs($product_detail->id)->cgrs_number}}</td>
                                    
                                    <td>{{$obj_fgs->get_cgrs($product_detail->id)->cgrs_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_cgrs($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                           
                                    @endif
                                    <!-- min -->
                                    @if(!empty($obj_fgs->get_min($product_detail->id)))
                                    <td>{{$obj_fgs->get_min($product_detail->id)->min_number}}</td>
                                    
                                    <td>{{$obj_fgs->get_min($product_detail->id)->min_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_min($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                             
                                    @endif
                                    <!-- cmin -->
                                    @if(!empty($obj_fgs->get_cmin($product_detail->id)))
                                    <td>{{$obj_fgs->get_cmin($product_detail->id)->cmin_number}}</td>
                                    
                                    <td>{{$obj_fgs->get_cmin($product_detail->id)->cmin_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_cmin($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                             
                                    @endif
                                    <!-- mis -->
                                    @if(!empty($obj_fgs->get_mis($product_detail->id)))
                                    <td>{{$obj_fgs->get_mis($product_detail->id)->mis_number}}</td>
                                    
                                    <td>{{$obj_fgs->get_mis($product_detail->id)->mis_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_mis($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                             
                                    @endif
                                    <!-- dni -->
                                   
                                     <!-- mtq -->
                                     @if(!empty($obj_fgs->get_mtq($product_detail->id)))
                                    <td>{{$obj_fgs->get_mtq($product_detail->id)->mtq_number}}</td>
                                    
                                    <td>{{$obj_fgs->get_mtq($product_detail->id)->mtq_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_mtq($product_detail->id)->created_at))}}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                             
                                    @endif
                                     <!-- cmtq -->
                                     @if(!empty($obj_fgs->get_cmtq($product_detail->id)))
                                    <td>{{$obj_fgs->get_cmtq($product_detail->id)->cmtq_number}}</td>
                                    
                                    <td>{{$obj_fgs->get_cmtq($product_detail->id)->cmtq_date}}</td>
                                    <td>{{date('d-m-Y',strtotime($obj_fgs->get_cmtq($product_detail->id)->created_at))}}</td>
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
                            {{$product_details->appends(request()->input())->links();}}
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
        var hsn_code = $('#hsn_code').val();
        var sku_code = $('#sku_code').val();
        var group = $('#group').val();
        var brand = $('#brand').val();
        if (!sku_code & !group & !brand & !hsn_code) {
            e.preventDefault();
        }
    });
</script>


@stop