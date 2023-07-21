@extends('layouts.default')
@section('content')
<style>
    .autosize {
        resize: none;
        overflow: hidden;
        min-height: 220px;
    }
</style>
<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb">
                <span><a href="{{url('inventory/suppliers-list')}}" style="color: #596881;">Product </a></span>
                <span><a href=""> Product Brand</a></span>
            </div>
            @include('includes.product.product_addfamily_tab')
            <br><br>
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;"> Product Brand Add</h4>
            @foreach ($errors->all() as $errorr)
            <div class="alert alert-danger " role="alert" style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ $errorr }}
            </div>
            @endforeach
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
            <div class="row">
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">

                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" autocomplete="off" novalidate="novalidate" action="{{url('product/Product-addbrand')}}" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        <div class="form-devider"></div>
                        <div class="row">
                            <!-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                  <label>Product family</label>

                                 <input type="text" name="pr_family" id="pr_family"  placeholder="Product Family" > 

                            </div> -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Product Brand</label>

                                <input type="text" name="pr_brand" id="pr_brand" class="form-control" placeholder="Product Brand">

                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                        <div class="form-devider"></div>
                    </form>

                </div>
            </div>
            <div class="tab-pane  active  show " id="purchase">

                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0">
                        <thead>
                            <tr style="text-align:center;">
                                <th >Sl</th>
                                {{--<th >Id</th>--}}
                                <th >Product Group</th>


                            </tr>
@php 
$sl=1;
@endphp
                            @foreach($product_brand as $pbrand)
                            
                            <tr>
                                <th>{{$sl++}}</th>
                               {{-- <th>{{$pgroup->id}}</th>--}}
                                <th>{{$pbrand->brand_name}}</th>
                            </tr>
                            @endforeach



                        </thead>
                        <tbody id="prbody1">

                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                    <div class="box-footer clearfix">
							{{ $product_brand->appends(request()->input())->links() }}
						</div>
                    </div>
                </div>
            </div>

        </div>
        
    </div>
    <!-- az-content-body -->
</div>
<script src="<?= url('') ?>/js/azia.js"></script>

<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>

<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>

<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>

<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>







@stop