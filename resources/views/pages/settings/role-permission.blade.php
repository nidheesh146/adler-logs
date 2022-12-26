@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #596881;">Settings</a></span>
             <span><a href="">Permission</a></span>
        </div>
        @include('includes.settings.settings-tab')
        <br/><br/>
        <div class="row ">
            <div class="col-lg-12 col-xl-12 mg-t-20 mg-lg-t-0">
                <!-- <div class="card card-table-one" style="min-height: 500px;"> -->
                    @if (Session::get('succs'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('succs') }}
                    </div>
                    @endif
                            <p class="az-content-text mg-b-20"></p>
                            <div class="table-responsive">
                                <form  method="POST"  id="commentForm">
                                {{ csrf_field() }}   
                                <table class="table table-bordered mg-b-0">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;<input type="checkbox"  class="parend-module">&nbsp;Module</th>
                                            <th>&nbsp;&nbsp;Permission</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['permission'] as $key => $organization)
                                        <tr style="background: #efefef;">
                                            <th><input type="checkbox" id="{{str_replace(' ', '', $key)}}" class="parend allmodule">&nbsp;{{ucfirst($key )}}</th>
                                                <td></td>
                                            </tr>
                                            @foreach ($organization as $keys => $organizations)
                                            <tr>
                                            <th></th>
                                            <td><input type="checkbox" class="{{str_replace(' ', '', $key)}} allmodule"  name="permission[]"  value="{{$keys}}" >&nbsp;{{$organizations}}</td>
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
                            </div><!-- table-responsive -->
                        <!-- </div>card -->
                    </div>
                </div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->

	
      

<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
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