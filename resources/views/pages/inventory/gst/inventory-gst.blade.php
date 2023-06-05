@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;">Inventory</a></span>
                <span><a href="">Inventory GST</a></span>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6" style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    <div class="card card-table-one" style="min-height: 500px;">
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
                        <div class="alert alert-danger " role="alert" style="width: 100%;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ $errorr }}
                        </div>
                        @endforeach
                        <h5>Input Material</h5>
                        <div class="form-devider"></div>
                        <form method="POST" id="orderform" autocomplete="off" action="{{url('inventory/inventory-gst_add')}}">
                            @csrf
                            <div class="row">
                                <!-- <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label>GST</label>
                                    <input type="text" class="form-control" name="gst" value="0">
                                </div> -->
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label>IGST</label>
                                    <input type="text" class="form-control" name="igst" value="0"id="igstField">
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label>CGST</label>
                                    <input type="text" class="form-control" name="cgst" value="0" id="cgstField">
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label>SGST</label>
                                    <input type="text" class="form-control" name="sgst" value="0" id="sgstField">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded" style="float: right;"><i class="fas fa-save"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6" style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    <div class="card card-table-one" style="min-height: 800px;">
                        @if (Session::get('succ'))
                        <div class="alert alert-success " style="width: 100%;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fa fa-check"></i> {{ Session::get('succ') }}
                        </div>
                        @endif
                        @if (Session::get('err'))
                        <div class="alert alert-danger " style="width: 100%;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fa fa-check"></i> {{ Session::get('err') }}
                        </div>
                        @endif
                        <h5>Inventory-GST</h5>
                        <div class="form-devider"></div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>SL NO</th> 
                                        <th>IGST</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $sl=1;
                                    @endphp
                                    @foreach($gst_details as $gst_detail)
                                    <tr>

                                        <td>{{$sl++}}</td>
                                        <td>{{$gst_detail->igst}}</td>
                                        <td>{{$gst_detail->cgst}}</td>
                                        <td>{{$gst_detail->sgst}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
</div>




<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    $('#orderform').submit(function() {
        if ($('#igstField').val() > '0' && $('#cgstField').val() > '0' && $('#sgstField').val() > '0') {
            alert('Do not add CGST and SGST and IGST');
            return false;
        }
        else if($('#igstField').val() > '0' && $('#cgstField').val() > '0' ){
            alert('Do not add CGST and IGST');
            return false;
        }else if($('#igstField').val() > '0' && $('#sgstField').val() > '0' ){
            alert('Do not add SGST and IGST');
            return false;
        }

        if ($('#cgstField').val() != $('#sgstField').val() ) {
            alert('Add same value in SGST and CGST');
            return false;
        }
    });
    

</script>


@stop