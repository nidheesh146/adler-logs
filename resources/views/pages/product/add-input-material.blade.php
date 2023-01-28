@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">PRODUCT</a></span> 
                <span><a href="">ADD INPUT MATERIAL</a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">Product- Input Material</h4>
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
                        <div class="alert alert-danger "  role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $errorr }}
                        </div>
                        @endforeach
                        <h5>Add Input Material</h5>
                        <div class="form-devider"></div>
                        <form method="POST" id="commentForm" autocomplete="off" >
                        {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label>Product SKU Code</label>
                                    <input type="text" class="form-control"  value="@if($product) {{$product['sku_code'] }} @endif" name="batchcard" placeholder="SKU Code" readonly>
                                    <input type="hidden" class="form-control"  value="@if($product) {{$product['id'] }} @endif" name="product_id" >
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label>Product Description</label>
                                    <textarea value="" class="form-control" name="description" placeholder="Product Description" readonly>@if($product) {{$product['discription'] }} @endif</textarea>
                                </div><!-- form-group -->
                                <table class="table table-bordered">
                                    <tbody id="dynamic_field">
                                        <tr id="row1" rel="1">
                                            <td>
                                                <div class="form-group col-sm-5 col-md-5 col-lg-5 col-xl-5" style="float:left;">
                                                    <label for="exampleInputEmail1">Item code * </label><br/>
                                                    <select class="form-control Item-code item_code1" id="1" name="moreItems[0][Itemcode]" id="Itemcode">
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-5 col-md-5 col-lg-5 col-xl-5" style="float:left;">
                                                    <label for="exampleInputEmail1">Quantity</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" name="moreItems[0][quantity]" id="quantity"
                                                            placeholder="Quantity"  aria-describedby="unit-div1">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text unit-div"
                                                                id="unit-div1">Unit</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <button type="button" name="add" id="add" class="btn btn-success" style="height:38px;margin-top:29px;">
                                                    <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> 
                            <br/>
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded" style="float: right;"><i
                                    class="fas fa-save"></i> Submit</button>
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
                        <h5>Product @if($product) ({{$product['sku_code'] }}) @endif - Input Material</h5>
                        <div class="form-devider"></div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <!-- <th>Description</th> -->
                                        <th>Quantity</th> 
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $material)
                                    <tr>
                                        <td><a href="#" style="color:#3b4863;" data-toggle="tooltip" data-placement="top" title="{{$material->short_description}}" >{{$material->item_code}}</a></td>
                                        <td style>{{$material->quantity}} {{$material->unit_name}}</td>
                                        <!-- <td width="10%">{{$material->short_description}}</td> -->
                                        <td><a href="{{url('product/delete-input-material?id='.$material->id)}}" onclick="return confirm('Are you sure you want to delete this ?');" class="badge badge-danger"><i class="fas fa-trash-alt"></i>  Delete</a> </td>
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
    $('#add').click(function()
    {
        //alert('kk');
        i++;
        $('#dynamic_field').append(`
            <tr id="row${i}" rel="${i}">
            <td>
                <div class="form-group col-sm-5 col-md-5 col-lg-5 col-xl-5" style="float:left;"><label for="exampleInputEmail1">Item code * </label><br/>
                    <select class="form-control Item-code item_code${i}" id="${i}" name="moreItems[${i}][Itemcode]"  id="" required></select>
                </div>

                <div class="form-group col-sm-5 col-md-5 col-lg-5 col-xl-5" style="float:left;">
                    <label for="exampleInputEmail1">Quantity</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="moreItems[${i}][quantity]" id="quantity" placeholder="Quantity"  aria-describedby="unit-div${i}">
                        <div class="input-group-append">
                            <span class="input-group-text unit-div" id="unit-div${i}">Unit</span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-2 col-md-2 col-lg-2 col-xl-2" style="float:left; margin-top:29px;">
                    <button name="remove" id="${i}" class="btn btn-danger btn_remove" style="height:38px;">X</button>
                </div>
            </td>
            </tr>`);
            initSelect2();
    });
    $(document).on('click','.btn_remove', function()
    {
        var button_id = $(this).attr("id");
        $("#row"+button_id+"").remove();
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
            //alert(select_id);
            $('#Itemcode-error').remove();
            let res = $(this).select2('data')[0];
            if(typeof(res) != "undefined" ){
                if(res.unit_name){
                    $('#Unit').val(res.unit_name);
                    $("#unit-div"+select_id+"").text(res.unit_name);
                }
            }
        });   
    } 
    function selectItem(itemId,divId){
        $('#Itemtype'+divId).val('');
        $.get("<?=url('inventory/get-single-item');?>?id="+itemId,function(response){
            let datas = [JSON.parse(response)];
            $(".item_code"+divId).select2({
                data: datas
            });
            $('#Itemtype'+divId).val(datas[0].type_name);
            $('#Itemdescription'+divId).val(datas[0].discription);
            $("#unit-div"+divId+"").text(datas[0].unit_name);
            //$('#u'+divId).val(datas[0].discription);
            $('#myModal1').modal('hide');
            initSelect2();
        });
    }
    $(function(){
        $("#commentForm").validate({
            rules: {
                Itemcode: {
                    required: true,
                },
                quantity: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>


@stop