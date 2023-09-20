@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb">
                <span>Delivery Challan</span>
                <span><a href="">
                        Challan List
                    </a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;">
                Challan List
                <div class="right-button">
                    <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/Delivery_challan/Challan-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i>
                        Delivery Challan
                    </button>
                    <div>

                    </div>
                </div>
            </h4>
            @if(Session::get('error'))
            <div class="alert alert-danger " role="alert" style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{Session::get('error')}}
            </div>
            @endif
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
                                                <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">



                                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="exampleInputEmail1" style="font-size: 12px;">Customer</label>
                                                        <input type="text" value="{{request()->get('oef_no')}}" name="customer" id="customer" class="form-control" placeholder="Customer">
                                                    </div>

                                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label style="font-size: 12px;">Doc Date</label>
                                                        <input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="dd-MM-YYYY">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
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


                <div class="tab-pane  active  show " id="purchase">

                    <div class="table-responsive">
                        <table class="table table-bordered mg-b-0">
                            <thead>
                                <tr>
                                    {{--<th>Sl</th>--}}
                                    <th>Doc Number</th>
                                    <th>Doc Date</th>
                                    <th>Ref Number</th>
                                    <th>Ref Date</th>
                                    <th>Product Category</th>
                                    <th>Transaction Condition </th>
                                    <th>Transaction Type</th>
                                    <th>Stock Location1(Decrease)</th>
                                    <th>Stock Location2(Increase)</th>
                                    <th>Customer</th>
                                    {{--<th>Address</th>--}}
                                    
                                    
                                   
                                    
                                    
                                   
                                    


                                    <th>Action</th>
                                </tr>
                            </thead>
                            @php
                            $sl=1;
                            @endphp
                            <tbody id="prbody1">
                                @foreach($challan_details as $item)
                                <tr>
                                    {{--<td>{{$sl++}}</td>--}}
                                    <td>{{$item->doc_no}}</td>
                                    <td>{{date('d-m-Y', strtotime($item->doc_date))}}</td>
                                    <td>{{$item->ref_no}}</td>
                                    <td>{{date('d-m-Y', strtotime($item->ref_date))}}</td>
                                    <td>{{$item->category_name}}</td>
                                    @if($item->transaction_condition==1)
                                    <td>Returnable</td>
                                    @else
                                    <td>Non Returnable</td>
                                    @endif
                                    
                                    <td>{{$item->transaction_name}}</td>
                                    <td>{{$item->location_decrease}}</td>
                                    <td>{{$item->location_increase}}</td>
                                    <td>{{$item->firm_name}}</td>
                                    {{--<td>{{$item->shipping_address}}</td>--}}
                                    
                                    
                                    
                                    
                                    <td>
                                    <a class="badge badge-info" style="font-size: 13px;" href="{{url('fgs/Delivery_challan/Challan-item-list/'.$item->id)}}" class="dropdown-item"><i class="fas fa-eye"></i> Item</a>

                                    <a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;margin-top:2px;" href="{{url('fgs/Delivery_challan/Delivery_Challan_pdf/'.$item->id)}}" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;PDF</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $challan_details->appends(request()->input())->links() }}
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

<script>
    $(function() {
        'use strict'
        var date = new Date();
        date.setDate(date.getDate());
        $(".datepicker").datepicker({
            format: "dd-mm-yyyy",
            // viewMode: "months",
            // minViewMode: "months",
            // startDate: date,
            autoclose: true
        });
        $('#prbody1').show();
        $('#prbody2').show();
    });

    $('.search-btn').on("click", function(e) {
        var customer = $('#customer').val();
        var from = $('#from').val();
        if (!customer & !from) {
            e.preventDefault();
        }
    });
</script>


@stop