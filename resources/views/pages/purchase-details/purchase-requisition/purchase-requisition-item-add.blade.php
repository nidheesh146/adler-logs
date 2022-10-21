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
                        <form  method="post" id="commentForm" novalidate="novalidate">
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
                                <table class="table table-bordered" id="dynamic_field">
                                    <tr>
                                        <td>
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                <label for="exampleInputEmail1">Item code * </label>
                                                <select class="form-control Item-code" id="1" name="moreItems[0][Itemcode]" id="Itemcode">
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                <label>Item type * </label>
                                                <input type="text" readonly  class="form-control"  name="Itemtype" id="Itemtype1" placeholder="Item type">
                                                <input type="hidden"
                                                    value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}"
                                                    name="Itemtypehidden" id="Itemtypehidden">
                                            </div><!-- form-group -->
                                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 qty" style="float:left;">
                                                <label>Order Qty *</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control"  name="moreItems[0][ActualorderQty]" id="ActualorderQty" placeholder="Order Qty" 
                                                    aria-label="Recipient's username" aria-describedby="unit-div1">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text unit-div" id="unit-div1">Unit</span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <button type="button" name="add" id="add" class="btn btn-success" style="height:38px;margin-top:28px;"><i class="fas fa-plus"></i></button>
                                        </td>
                                    </tr>
                                </table>                            
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
    $(document).ready(function(){
        initSelect2();
        var i = 1;
        $('#add').click(function(){
            //alert('kk');
            i++;
            $('#dynamic_field').append('<tr id="row'+i+'"><td><div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;"><label for="exampleInputEmail1">Item code * </label><select class="form-control Item-code" id="'+i+'" name="moreItems['+i+'][Itemcode]"  id="Itemcode" required></select></div><div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;"><label>Item type * </label><input type="text" readonly class="form-control" value="" name="Itemtype" id="Itemtype'+i+'" placeholder="Item type"></div><div class="col-lg-3" style="float:left;"><label>order Qty *</label><div class="input-group mb-3"><input type="text" class="form-control" value=""  name="moreItems['+i+'][ActualorderQty]"  id="ActualorderQty" placeholder="Order Qty"   aria-describedby="unit-div'+i+'" ><div class="input-group-append"><span class="input-group-text unit-div" id="unit-div'+i+'">Unit</span></div></div></div><button name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="height:38px;margin-top:28px;">X</button></td></tr>');
            initSelect2();
        });
        $(document).on('click','.btn_remove', function(){
            var button_id = $(this).attr("id");
            $("#row"+button_id+"").remove();
        });

        // $('#sumbit').click(function(){
        //     $.ajax({
        //         type:'POST',
        //         url:"{{ url('inventory/add-requisition-items') }}",
        //         data:$('#commentForm').serialize(),
        //         success:function(data)
        //         {
        //             alert(data);
        //             $('#commentForm')[0].reset();
        //         }
        //     });
        // });
    });
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
        function initSelect2() {
            $(".Item-code").select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
                minimumInputLength: 6,
                allowClear: true,
                ajax: {
                    url: "{{ url('inventory/itemcodesearch') }}",
                    processResults: function (data) {
                            return { results: data };
                    }
                }
            }).on('change', function (e) {
                var select_id = $(this).attr("id");
                $('#Itemcode-error').remove();
                $('#Itemdescription').text('');
                $("#Itemtype"+select_id+"").val('');
                let res = $(this).select2('data')[0];
                    
                    if(res.type_name){
                        $("#Itemtype"+select_id+"").val(res.type_name);
                    }
                   
                    if(res.unit_name){
                        $('#Unit').val(res.unit_name);
                        //alert($(this));
                        //$('.unit-div').text(res.unit_name);
                        $("#unit-div"+select_id+"").text(res.unit_name);
                        //$(this).parent().find('.qty').closest( "#unit-div" ).text(res.unit_name)
                    }
                });   
        }   
            
            

</script>


@stop
