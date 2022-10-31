@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">PURCHASE
                            DETAILS</a></span>
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">
                            REQUISITION</a></span>
                    <span><a href="">
                    @if(request()->pr_id)
                        {{ request()->item ? 'Edit' : 'Add' }} Purchase Requisition Details ( {{$data["master"]['pr_no']}}  )
                    @endif
                    @if(request()->sr_id)
                        {{ request()->item ? 'Edit' : 'Add' }} service requisition Details ( {{$data["master"]['pr_no']}}  )
                    @endif
                    </a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    @if(request()->pr_id)
                        {{ request()->item ? 'Edit' : 'Add' }} Purchase Requisition Details ( {{$data["master"]['pr_no']}}  )
                    @endif
                    @if(request()->sr_id)
                            {{ request()->item ? 'Edit' : 'Add' }} Service Requisition Details ( {{$data["master"]['pr_no']}}  )
                    @endif
                </h4>
                <div class="az-dashboard-nav">
                    <nav class="nav">
                    @if(request()->pr_id)
                        <a class="nav-link    "
                            href="{{ url('inventory/edit-purchase-reqisition?pr_id=' . request()->pr_id) }}">Purchase
                            Requestor Details </a>
                        <a class="nav-link  active" @if (request()->pr_id) href="{{ url('inventory/get-purchase-reqisition-item?pr_id=' . request()->pr_id) }}" @endif> Purchase Requisition Details  </a>
                        <a class="nav-link  " href=""> </a>
                    @endif
                    @if(request()->sr_id)
                        <a class="nav-link    "
                            href="{{ url('inventory/edit-purchase-reqisition?sr_id=' . request()->sr_id) }}">Service 
                            Requestor Details </a>
                        <a class="nav-link  active" @if (request()->sr_id) href="{{ url('inventory/get-purchase-reqisition-item?sr_id=' . request()->sr_id) }}" @endif> Service Requisition Details </a>
                        <a class="nav-link  " href=""> </a>
                    @endif
                    </nav>

                </div>

                <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">

                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="POST" id="commentForm" novalidate="novalidate">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                        <i class="fas fa-address-card"></i> Basic details </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row">

                            @foreach ($errors->all() as $errorr)
                    <div class="alert alert-danger "  role="alert" style="width: 100%;">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      {{ $errorr }}
                    </div>
                   @endforeach 
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label for="exampleInputEmail1">Item code * </label>
                                    <select class="form-control Item-code" name="Itemcode" id="Itemcode">
                                        @if (!empty($datas["item"]))
                                            <option value="{{$datas["item"]['Item_code']}}" selected>{{$datas["item"]['item_code']}}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                    <label>Item type * </label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ !empty($datas) ? $datas['item']['type_name'] : '' }}"
                                        name="Itemtype" id="Itemtype" placeholder="Item type">
                                    <input type="hidden"
                                        value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}"
                                        name="Itemtypehidden" id="Itemtypehidden">
                                </div><!-- form-group -->
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 qty" style="float:left;">
                                    <label>Order Qty *</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{ !empty($datas) ? $datas['item']['actual_order_qty'] : '' }}" name="ActualorderQty" id="ActualorderQty" placeholder="Order Qty" 
                                                    aria-label="Recipient's username" aria-describedby="unit-div1">
                                            <div class="input-group-append">
                                                <span class="input-group-text unit-div" id="unit-div1">{{ !empty($datas) ? $datas['item']['unit_name'] : '' }}</span>
                                            </div>
                                        </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                    <label>Description * </label>
                                    <textarea type="text" readonly class="form-control"  id="Itemdescription1"name="Description" placeholder="Description">{{ !empty($datas) ? $datas['item']['discription'] : '' }}</textarea>
                                </div>  
                            </div>        
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                            class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        {{ request()->item ? 'Update' : 'Save' }}
                                    </button>
                                </div>
                            </div>
                            <div class="form-devider"></div>
                        </form>

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
        $(function(){

            $("#commentForm").validate({
                rules: {
                    Itemcode: {
                            required: true,
                    },           
                    ActualorderQty: {
                                required: true,
                                number: true
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

        });  

        </script>


@stop
