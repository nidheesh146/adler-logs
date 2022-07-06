@extends('layouts.default')
@section('content')
@inject('AgentController', 'App\Http\Controllers\Web\AgentController')
@inject('Controller', 'App\Http\Controllers\Controller')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('agent/agents') }}" >Agents</a></span>
                    {{-- <span><a href="{{ url('agent/create/agent/' . $org) }}"
                            >ALL Agent</a></span> --}}
                    <span> Invoice</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Invoice for '{{$datas['suscriber']['subr_id']}} - {{$datas['suscriber']['f_name'].' '.$datas['suscriber']['l_name']}} '
                    {{-- @if($subs_id) <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('subscription/create/' . $org . '/' . $id)}}'" class="badge badge-pill badge-dark "><i
                        class="fas fa-plus"></i> subscription  </button> @endif --}}
                    </h4>

                <div class="row">

                    <div class="col-md-4 col-lg-3">
                        <div class="pd-20 bg-gray-200"
                            style="border: 1px solid rgba(28, 39, 60, 0.12);background-color: #ffffff;">
                            @include('includes.agent-nav')
                        </div><!-- pd-10 -->
                    </div>
            
                    <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9"
                       >
                        <div class="row row-sm mg-b-20 mg-lg-b-0">
                            <div class="table-responsive" style="margin-bottom: 13px;">
                               
                                
                                <table class="table table-bordered mg-b-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row">
        
                                                <div class="row">
        
                                                    <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
        
                                       
        
                                                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                            <label> SUBSCRIPTION ID</label>
                                                            <input type="text" class="form-control" placeholder="Enter name">
                                                        </div>
        
                                                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                            <label> MAGAZINE</label>
                                                            <input type="text" class="form-control" placeholder="Enter name">
                                                        </div>
        
                                                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                            <label> Month & year</label>
                                                            <input type="text" class="form-control" placeholder="Enter name">
                                                        </div>
        
                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
                                                        <label style="    width: 100%;">&nbsp;</label>
                                                        <button type="submit" class="btn btn-primary btn-rounded" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                    </div>
                                                </div>
        
                                            </th>
                                    </tr></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="">
                            <table class="table table-bordered mg-b-0">
                                <thead>
                                    <tr>
                                        <th>Invoice <br>NO</th>
                                        <th>Subscription <br>ID</th>
                                        <th>Magazine<br>& Quantity</th>
                                        <th>Month <br>& Year  </th>
                                        <th>Issued </th>
                                        <th>Due Date </th>
                                        <th>Total Due</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas['invoice']  as $subscription)
                                       <tr>
                                        <td>{{$subscription['invoice_id']}}</td>
                                        <td>{{$subscription['subscription_id']}}</td>
                                        <th ><?=$AgentController->book_num($subscription['subn_id']);?> - {{$subscription['quantity']}} </th>
                                        <td>{{date('M-Y',strtotime($subscription['invoice_month']))}}</td>
                                        <td>{{date('d-m-Y',strtotime($subscription['issued_date']))}}</td>
                                        <td>{{date('d-m-Y',strtotime($subscription['expire_date']))}}</td>
                      
                                        <td  data-toggle="tooltip" data-html="true" data-placement="top"
                                       title="{{'Sub-Total : '.$subscription['amount'].'<br>'.'Commission('.$subscription['current_commission'].' % ) :' .$subscription['commission'] .'<br>Total Due :'. ($subscription['amount'] - $subscription['commission']) }}"     >
                                       {{sprintf("%.3f",($subscription['amount'] - $subscription['commission']))}}</td>

                                        <td>
                                            <button data-toggle="dropdown" class="badge  badge-primary">Action <i
                                                    class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                                            <div class="dropdown-menu">
                                            <a target="_blank" href="{{url('agent-invoice-pdf/'.$Controller->hashEncode($subscription['inv_id']))}}"  class="dropdown-item"><i class="fas fa-eye"></i> View</a>
                                            <a target="_blank"href="{{url('agent-invoice-pdf/'.$Controller->hashEncode($subscription['inv_id']))}}?download=pdf"  class="dropdown-item"><i class="fas fa-download"></i> Download</a>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="box-footer clearfix">
                                {{ $datas['invoice']->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- az-content-body -->
        </div>


    </div><!-- modal -->
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

            <script>
                   $('[data-toggle="tooltip"]').tooltip();
            $('.subscription-view').click(function(){
                $.get("<?= url('') ?>/agent-subscription-view/"+$(this).attr('id'), function( data ) {
                  $('.timelines').html(data);
                  $('#modaldemo2').modal('show');
                });
                });
            </script>




@stop
