@extends('layouts.subscriber-default')
@section('content')

<style>
  .select2-container .select2-selection--single {
  height: 38px !important;
  }
</style>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <h4 class="az-content-title" style="font-size: 20px;">List Label</h4>
                <div class="az-dashboard-nav">
                    <nav class="nav">
                        <a class="nav-link" href="{{ url('') }}">Add Label </a>
                        <a class="nav-link active" href="{{ url('label/list') }}">List Label</a>
                        {{-- <a class="nav-link " href="{{ url('subscribers-renew') }}">Subscription renew</a> --}}
                    </nav>
                    <nav class="nav">
                    </nav>
                </div>
                @if (Session::get('success'))
                <div class="alert alert-success " style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                </div>
                @endif
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ref</th>
                            <th>lot</th>
                            <th>Sterile</th>
                            <th>Date</th>
                            <th>Expiry Date </th>
                            <th>QTY</th>
                            <th></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($data['label_name'] as $item)
                            <tr>
                                <th>{{$item->id}}</th>
                                <th>{{$item->ref}}</th>
                                <td>{{$item->lot}}</td>
                                <td>{{$item->sterile}}</td>
                                <td>{{$item->created_date}}</td>
                                <td>{{$item->expiry_date}}</td>
                                 <td>{{$item->qty}}</td>
                            <td>
                                <button data-toggle="dropdown" style="width: 64px;" class="badge  badge-success">Action <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                            <div class="dropdown-menu">
                                <a href="{{url($item->id)}}"class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{url('download/'.$item->id)}}"  target="_blank" class="dropdown-item"><i class="fas fa-print"></i> Print</a>
                            </div>
                            </td>
                                </tr>
                             @endforeach
                    </tbody>
                </table>
                <div class="box-footer clearfix">
                    {{ $data['label_name']->links() }}
                    </div>
            </div>


            </div><!-- az-content-body -->
        </div>
    </div><!-- az-content -->
  
    <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('') ?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/lib/spectrum-colorpicker/spectrum.js"></script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="<?= url('') ?>/lib/pickerjs/picker.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/js/moment.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
  

@stop