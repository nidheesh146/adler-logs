@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\PurchaseController')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="">Stock Issue To Production</a></span>
                    <span>View</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Stock Issue To Production - View

                </h4>
                

                <!-- <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            Supplier Invoice :
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div> -->
                
                <div class="data-bindings">
               
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            <i class="fas fa-hand-point-right"></i>
                            Stock Issue To Production({{$sip['sip_number']}})
                            </label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <table class="table table-bordered mg-b-0">    
                        <tbody>
                            <tr>
                                <th>SIP No</th>
                                <th>{{$sip['sip_number']}}</th>
                                <th>Created Date</th>
                                <th>{{date('d-m-Y', strtotime($sip['created_at']))}}</th>
                            </tr>
                            <tr>
                                <th>Item Code</th>
                                <th>{{ $sip['item_code'] }}</th>
                                <th>Item Description</th>
                                <th>{{$sip['discription']}}</th>
                            </tr>
                            <tr>
                                <th>Item Type</th>
                                <th>@if($sip['type']==2) Direct  @else Indirect @endif</th>
                                <th>Quantity to Production</th>
                                <th>{{$sip['qty_to_production']}} {{$sip['unit_name']}}</th>
                            </tr>
                            <tr>
                                <th>Work Centre Code & Description</th>
                                <th>{{$sip['centre_code']}} <br/> {{$sip['centre_description']}}</th>
                                <th>Transaction Slip No</th>
                                <th>{{$sip['transaction_slip']}}</th>
                            </tr>
                            
                        </tbody> 
                        <!-- <tbody>
                            <tr>
                                <td>{{date('d-m-Y',strtotime('18-09-2022'))}}</td>
                                <td>{{date('d-m-Y',strtotime('19-09-2022'))}}</td>
                            </tr>
                        </tbody> -->
                    </table>
                   
                    <br/>
                    <div class="form-devider"></div>
                    
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            <i class="fas fa-hand-point-right"></i>
                               Lot Number Info
                            </label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <table class="table table-bordered mg-b-0">     
                        <tbody>
                            <tr>
                                <th>Lot Number</th>
                                <th>{{$sip['lot_number']}}</th>
                            </tr>
                            </tr>
                                <th>Lot Quantity</th>
                                <th>{{$sip['qty_to_production']}} {{$sip['unit_name']}}</th>
                            </tr>
                        </tbody>
                    </table><br>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            <i class="fas fa-hand-point-right"></i>
                            BatchCard Info</label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                            <table class="table table-bordered mg-b-0" id="example1">
                                <thead>
                                    <tr>
                                        <th>Batchcard</th>
                                        <th>Item Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($sip['items'] as $item)
                                    <tr>
                                        <th>{{$item['batch_no']}}</th>
                                        <th>@if($item['qty_to_production']!=NULL) {{$item['qty_to_production']}} Nos 
                                            @else 
                                            Not Specified
                                            @endif
                                        </th>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                    @
                </div>
            </div>
        </div>
        <!-- az-content-body -->
    </div>

    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>

@stop
