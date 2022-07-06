@extends('layouts.default')
@section('content')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('organization/' . $org) }}" >Organization</a></span>
                    <span><a href="{{ url('organization/role/' . $org.'/'.$orgid) }}" >Role</a></span>
                    <span>Role permission</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">Role permission</h4>
                <div class="az-dashboard-nav">
                    @include('includes.user-nav')

                    <nav class="nav">
                        <a class="nav-link" href="#"></a>
                        {{-- <a class="nav-link" href="{{ url('create-organization/' . $org) }}"><i class="fa fa-plus"
                                aria-hidden="true"></i> Add user</a> --}}
                        {{-- <a class="nav-link" href="#"><i class="far fa-file-pdf"></i> Export to PDF</a> --}}
                        <!-- <a class="nav-link" href="#"><i class="far fa-envelope"></i>Send to Email</a> -->
                        <a class="nav-link" href="#"><i class="fas fa-ellipsis-h"></i></a>
                    </nav>
                </div>


                <div class="table-responsive">
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                      </div>
                      @endif
                    <form  method="POST"  id="commentForm">
                        {{ csrf_field() }}   
                    <table class="table table-bordered mg-b-0">
                        <thead>
                            <tr>
                                <th><input type="checkbox"  class="parend-module"> Module</th>
                                <th>Permission</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($data['permission'] as $key => $organization)
                                <tr style="background: #efefef;">
                                    <th><input type="checkbox" id="{{$key}}" class="parend allmodule"> {{ ucfirst($key) }}</th>
                                    <td></td>
                                </tr>
                                @foreach ($organization as $keys => $organizations)
                                    <tr>
                                        <th></th>
                                    <td><input type="checkbox" class="{{$key}} allmodule"  name="permission[]" {{ $organizations['checked'] }} value="{{$keys}}" > {{ $organizations['name'] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr> 
                                <th></th>
                                <td><button class="btn btn-primary btn-rounded btn-block"
                                        style="width: 147px;float: right;"><i class="fas fa-save"></i> Submit</button></td>
                            </tr>

                        </tbody>
                    </table>
                    </form>



                </div>
            </div>



        </div><!-- az-content-body -->
    </div>
    </div><!-- az-content -->


    <script src="<?= url('') ?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>

<script>
$('.parend').click(function(){
    let role = $(this).attr('id');
   if($(this).is(":checked")){
       $('.'+role).prop('checked',true) ;
   }else{
       $('.'+role).prop('checked',false) ;
   }
});

$('.parend-module').click(function(){
 
   if($(this).is(":checked")){
       $('.allmodule').prop('checked',true) ;
   }else{
       $('.allmodule').prop('checked',false) ;
   }
});



</script>

@stop
