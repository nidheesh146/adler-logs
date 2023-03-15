@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="" style="color: #596881;">
                    Material Transferred To Qurantine(MTQ)</a></span>
                    <span><a href="" style="color: #596881;">
                            MTQ Item</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                MTQ Item
                </h4>
                <div class="az-dashboard-nav">
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="post" id="commentForm" novalidate="novalidate">
                            {{ csrf_field() }}
                            <div class="row">

                                @foreach ($errors->all() as $errorr)
                                    <div class="alert alert-danger " role="alert" style="width: 100%;">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">×</button>
                                        {{ $errorr }}
                                    </div>
                                @endforeach
                                <table class="table table-bordered">
                                    <tbody id="dynamic_field">

                                        <tr id="row1" rel="1">
                                            <td>
                                                <div class="row">
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label for="exampleInputEmail1">Product code * </label>
                                                    <select class="form-control Item-code item_code1" id="1"
                                                        name="moreItems[0][Itemcode]" id="Itemcode">
                                                    </select>
                                                    
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                                    style="float:left;">
                                                    <label>HSN Code * </label>
                                                    <input type="text" readonly class="form-control" name="Itemtype"
                                                        id="Itemtype1" placeholder="HSN Code">
                                                    <input type="hidden"
                                                        value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}"
                                                        name="Itemtypehidden" id="Itemtypehidden">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Description * </label>
                                                    <textarea type="text" readonly class="form-control" id="Itemdescription1"name="Description"
                                                        placeholder="Description"></textarea>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label for="exampleInputEmail1">Batch No* </label>
                                                    <select class="form-control Item-code item_code1" id="1"
                                                        name="moreItems[0][Itemcode]" id="Itemcode">
                                                    </select>
                                                    
                                                </div>
                                                </div>
                                            <div class="row"> 
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Batch Qty * </label>
                                                    <input type="text" readonly class="form-control" name="Itemtype"
                                                        id="Itemtype1" placeholder="Batch Qty">
                                                </div>
                                            
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                                    style="float:left;">
                                                    <label>UOM </label>
                                                    <input type="text"  class="form-control" name="Itemtype"
                                                        id="Itemtype1" placeholder="UOM">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Date of Mfg. * </label>
                                                    <input type="text"  class="form-control" name="Itemtype"
                                                        id="Itemtype1" placeholder="Date of Mfg.">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Date of Expiry * </label>
                                                    <input type="text"  class="form-control" name="Itemtype"
                                                        id="Itemtype1" placeholder="Date of Expiry">
                                                </div>
                                                <button type="button" name="add" id="add" class="btn btn-success"
                                                    style="height:38px;margin-top:28px;"><i
                                                        class="fas fa-plus"></i></button>
                                            </div>
                                            </td>
                                        </tr>


                                    </tbody>

                                </table>
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label for="exampleInputEmail1">Remarks  *</label>
                                    <textarea type="text" class="form-control" name="f_name" value="" placeholder=""></textarea>
                                </div>
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
        <script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
        <script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
        <script src="<?= url('') ?>/js/azia.js"></script>
        <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
        <script src="<?= url('') ?>/js/jquery.validate.js"></script>
        <script src="<?= url('') ?>/js/additional-methods.js"></script>
        <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
        <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
        <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
        <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
       

       <script>
  var divid = "";


function getsearch(){
 return   table.search();
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
    function get_data(id){
                    $('#myModal1').modal('show');
                    divid = id 
                    table.search('').columns().search('').draw();            
    }

        $(document).ready(function(){
            initSelect2();
            var i = 1;
            $('#add').click(function(){
                //alert('kk');
                i++;
                $('#dynamic_field').append(`
                      <tr id="row${i}" rel="${i}">
                      <td>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label for="exampleInputEmail1">Product code * </label>
                                <select class="form-control Item-code item_code${i}" id="${i}" name="moreItems[0][Itemcode]" id="Itemcode">
                                </select>                        
                            </div>
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label>HSN Code * </label>
                                <input type="text" readonly class="form-control" name="Itemtype" id="Itemtype${i}" placeholder="HSN Code">
                                <input type="hidden" value="{{ !empty($datas) ? $datas['item']['item_type_id'] : '' }}" name="Itemtypehidden" id="Itemtypehidden">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Description * </label>
                                <textarea type="text" readonly class="form-control" id="Itemdescription${i}"name="Description" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label for="exampleInputEmail1">Batch No* </label>
                                <select class="form-control Item-code item_code1" id="1" name="moreItems[0][Itemcode]" id="Itemcode${i}">
                                </select>                
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Batch Qty * </label>
                                <input type="text" readonly class="form-control" name="Itemtype" id="Itemtype${i}" placeholder="Batch Qty">
                            </div>
                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                <label>UOM </label>
                                <input type="text"  class="form-control" name="Itemtype" id="Itemtype${i}" placeholder="UOM">
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Date of Mfg. * </label>
                                <input type="text"  class="form-control" name="Itemtype" id="Itemtype${i}" placeholder="Date of Mfg.">
                            </div>
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                <label>Date of Expiry * </label>
                                <input type="text"  class="form-control" name="Itemtype" id="Itemtype${i}" placeholder="Date of Expiry">
                            </div>
                            <button name="remove" id="${i}" class="btn btn-danger btn_remove" style="height:38px;margin-top:28px;">X</button>
                        </div>
                    </td>                        
                </tr>`);
                initSelect2();
            });
            $(document).on('click','.btn_remove', function(){
                var button_id = $(this).attr("id");
                $("#row"+button_id+"").remove();
            });
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
                    $("#Itemdescription"+select_id+"").text('');
                    $("#Itemtype"+select_id+"").val('');
                    $("#Itemdescription"+select_id+"").val('');

                    Itemdescription1
                    let res = $(this).select2('data')[0];
                        if(typeof(res) != "undefined" ){
                            if(res.type_name){
                                $("#Itemtype"+select_id+"").val(res.type_name);
                            }
                            if(res.unit_name){
                                $('#Unit').val(res.unit_name);
                                $("#unit-div"+select_id+"").text(res.unit_name);
                            }
                            if(res.discription){
                                $("#Itemdescription"+select_id+"").val(res.discription);
                            }
                       }
                    });   
            }   
    </script>
@stop
