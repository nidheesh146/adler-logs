@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;"> Stock Adjustment - Decrease(SAD)</a></span>
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">

                    </a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Stock Adjustment - Decrease(SAD)
            </h4>
            <div class="az-dashboard-nav">

            </div>

            <div class="row">

                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
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
                    @foreach ($errors->all() as $errorr)
                    <div class="alert alert-danger " role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $errorr }}
                    </div>
                    @endforeach

                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" autocomplete="off">


                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details
                                </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Location Decrease *</label>
                                <select class="form-control" name="location">
                                    <option value="">Select one...</option>
                                    @foreach($locations as $loc)
                                    @if($loc['location_name']!='Quarantine' && $loc['location_name']!='Consignment' && $loc['location_name']!='Loaner' && $loc['location_name']!='Demo' && $loc['location_name']!='Replacement' && $loc['location_name']!='Samples')
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>SAD Date *</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control sad_date" id="sad_date" name="sad_date" placeholder="">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Created by: *</label>
                                <select class="form-control user_list" name="created_by">
                                @foreach ($users as $user)
                                    <option value="{{$user->user_id}}" @if(config('user')['user_id']== $user['user_id']) selected  @endif>{{$user->f_name}} {{$user->l_name}}</option>
                                @endforeach                                          
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-12 col-xl-12">
                                <label for="exampleInputEmail1">Remarks </label>
                                <textarea type="text" class="form-control" name="remarks" value="" placeholder=""></textarea>
                            </div>
                           {{-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Select File </label>
                                
                                <input type="file"  accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control" name="file" value="" >
                                <a href="{{ asset('uploads/SAI_sample.xlsx') }}"  target="_blank" style="
                                float: right;
                                font-size: 10px;
                            "> Download Template</a>
                            </div> --}}
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save & Next

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
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

<script>
    $("#commentForm").validate({
        rules: {
            location: {
                required: true,
            },
            sai_date: {
                required: true,
            },
            created_by: {
                required: true,
            },
           
        },
        submitHandler: function(form) {
            $('.spinner-button').show();
            form.submit();
        }
    });
    $(".customer").select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('fgs/customersearch') }}",
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    }).on('change', function(e) {
        $('#Itemcode-error').remove();
        $("#billing_address").text('');
        $("#shipping_address").text('');
        let res = $(this).select2('data')[0];
        if (typeof(res) != "undefined") {
            if (res.billing_address) {
                $("#billing_address").val(res.billing_address);
            }
            if (res.shipping_address) {
                $("#shipping_address").val(res.shipping_address);
            }
        }
    });
</script>


@stop