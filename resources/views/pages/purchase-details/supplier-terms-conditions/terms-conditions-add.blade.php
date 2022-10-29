@extends('layouts.default')
@section('content')
<style>
.autosize {
  resize: none;
  overflow: hidden;
  min-height: 420px;
}
</style>
<div class="az-content az-content-dashboard">
    <br>
      <div class="container">
          <div class="az-content-body">
              <div class="az-content-breadcrumb"> 
              <span><a href="{{url('inventory/terms-and-conditions-list')}}" style="color: #596881;">Terms and Conditions</a></span> 
                  <span><a href="">{{ $id ? "Edit" : "Add" }} Terms and Conditions </a></span>
              </div>
              <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">{{ $id ? "Edit" : "Add" }} Terms and Conditions</h4>
              @foreach ($errors->all() as $errorr)
              <div class="alert alert-danger "  role="alert" style="width: 100%;">
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
              <div class="row">  
                  <div class="col-sm-12   col-md-8 col-lg-8 col-xl-8 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                                                                  
                      <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                      <form method="POST" id="commentForm" autocomplete="off" novalidate="novalidate">
                         
                        {{ csrf_field() }}  
                          <div class="form-devider"></div>
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <label>Title *</label>
                              <input type="text" name="title" class="form-control"  value="{{(!empty($datas['terms_conditions'])) ? $datas['terms_conditions']->title: ""}}" placeholder="Title"> 
                              </div>
                             
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <label>Terms and Conditions *</label>
                                  <textarea value="" class="form-control autosize" name="conditions" placeholder="Terms and Conditions"><?php echo (!empty($datas['terms_conditions'])) ? $datas['terms_conditions']->terms_and_conditions: ""; ?></textarea>
                              </div>
                          </div> 
              
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                   
                                    {{ $id ? "Update" : "Save" }}    
                                                                  
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
autosize();
function autosize(){
    var text = $('.autosize');

    text.each(function(){
        $(this).attr('rows',1);
        resize($(this));
    });

    text.on('input', function(){
        resize($(this));
    });
    
    function resize ($text) {
        $text.css('height', 'auto');
        $text.css('height', $text[0].scrollHeight+'px');
    }
}


$("#commentForm").validate({
            rules: {
                title: {
                    required: true,
                },
                conditions: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
 });

</script>






  @stop