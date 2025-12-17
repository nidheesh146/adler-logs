
<?php $__env->startSection('content'); ?>

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="<?php echo e(url('inventory/get-purchase-reqisition')); ?>" style="color: #596881;">PURCHASE
                            DETAILS</a></span>
                    <span><a href="<?php echo e(url('inventory/get-purchase-reqisition')); ?>" style="color: #596881;">
                            REQUISITION</a></span>
                    <span><a href="">
                            <?php if(request()->pr_id): ?>
                                Purchase Requisition Details (
                                <?php echo e($data['master']['pr_no']); ?> )
                            <?php endif; ?>
                            <?php if(request()->sr_id): ?>
                                 service requisition Details (
                                <?php echo e($data['master']['pr_no']); ?> )
                            <?php endif; ?>
                        </a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    <?php if(request()->pr_id): ?>
                         Purchase Requisition Details (
                        <?php echo e($data['master']['pr_no']); ?> )
                    <?php endif; ?>
                    <?php if(request()->sr_id): ?>
                         Service Requisition Details (
                        <?php echo e($data['master']['pr_no']); ?> )
                    <?php endif; ?>
                </h4>
                <div class="az-dashboard-nav">
                    <nav class="nav">
                        <?php if(request()->pr_id): ?>
                            <a class="nav-link    "
                                href="<?php echo e(url('inventory/edit-purchase-reqisition?pr_id=' . request()->pr_id)); ?>">Purchase
                                Requestor Details </a>
                            <a class="nav-link  active" <?php if(request()->pr_id): ?> href="<?php echo e(url('inventory/get-purchase-reqisition-item?pr_id=' . request()->pr_id)); ?>" <?php endif; ?>> Purchase Requisition Details </a>
                            <a class="nav-link  " href=""> </a>
                        <?php endif; ?>
                        <?php if(request()->sr_id): ?>
                            <a class="nav-link    "
                                href="<?php echo e(url('inventory/edit-purchase-reqisition?sr_id=' . request()->sr_id)); ?>">Service
                                Requestor Details </a>
                            <a class="nav-link  active" <?php if(request()->sr_id): ?> href="<?php echo e(url('inventory/get-purchase-reqisition-item?sr_id=' . request()->sr_id)); ?>" <?php endif; ?>> Service Requisition Details </a>
                            <a class="nav-link  " href=""> </a>
                        <?php endif; ?>
                    </nav>

                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="post" id="commentForm" novalidate="novalidate">
                            <?php echo e(csrf_field()); ?>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                        <i class="fas fa-address-card"></i> Basic details </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row">

                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errorr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="alert alert-danger " role="alert" style="width: 100%;">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">×</button>
                                        <?php echo e($errorr); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <table class="table table-bordered">
                                    <tbody id="dynamic_field">

                                        <tr id="row1" rel="1">
                                            <td>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label for="exampleInputEmail1">Item code * </label>
                                                    <select class="form-control Item-code item_code1" id="1"
                                                        name="moreItems[0][Itemcode]" id="Itemcode">
                                                    </select>
                                                    <a href="#" style="float: right;" onclick="get_data(1)"><i
                                                            class="fas fa-search"></i> Search by description</a>

                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2"
                                                    style="float:left;">
                                                    <label>Item type * </label>
                                                    <input type="text" readonly class="form-control" name="Itemtype"
                                                        id="Itemtype1" placeholder="Item type">
                                                    <input type="hidden"
                                                        value="<?php echo e(!empty($datas) ? $datas['item']['item_type_id'] : ''); ?>"
                                                        name="Itemtypehidden" id="Itemtypehidden">
                                                </div><!-- form-group -->
                                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 qty" style="float:left;">
                                                    <label>Order Qty *</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control"
                                                            name="moreItems[0][ActualorderQty]" id="ActualorderQty"
                                                            placeholder="Order Qty" aria-label="Recipient's username"
                                                            aria-describedby="unit-div1">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text unit-div"
                                                                id="unit-div1">Unit</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3"
                                                    style="float:left;">
                                                    <label>Description * </label>
                                                    <textarea type="text" readonly class="form-control" id="Itemdescription1"name="Description"
                                                        placeholder="Description"></textarea>
                                                </div>
                                                <button type="button" name="add" id="add" class="btn btn-success"
                                                    style="height:38px;margin-top:28px;"><i
                                                        class="fas fa-plus"></i></button>

                                            </td>
                                        </tr>


                                    </tbody>

                                </table>

                                <div id="myModal1" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg" style="min-width: 98% !important;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"> Search by description</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body" style="min-height: 600px !important;">
                                                <p>

                                                <table  class="table-1 table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="wi">Item code</th>
                                                            <th>Description</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                       
                                                    </tfoot>
                                                </table>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                            class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        <?php echo e(request()->item ? 'Update' : 'Save'); ?>

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
  var table = $('.table-1').DataTable({
       processing: true,
       search: {
            return: true,
        },
        serverSide: true,
        processing: true,
        "language": {
                "processing": '<i style="margin-left : 40%;" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
            },
        ajax: {
                "url": "<?=url('inventory/get-description');?>",
                cache: false,
                "type": "GET",
                data:function(dtp){
                    return { draw : dtp.draw,length:dtp.length,start:dtp.start,value:dtp.search.value}
                }
            },
            "createdRow": function( row, data, dataIndex ) {
                $('td', row).eq(2).html(`<span><a class="badge badge-info" onclick="selectItem(${data.id},${divid})" rel="" style="font-size: 13px;" href="#"><i class="fas fa-check" aria-hidden="true"></i> Select</a></span>`);
              //  $('td', row).eq(1).html(`<span>${data.discription.replace(getsearch(),`<span style="color: var(--orange);font-weight: 900;">${getsearch()}</span>`)} </span>`);
                $('td', row).eq(2).addClass('sorting_1' );
             },
        columns: [
            { data: 'item_code' },
            { data: 'discription' },
            { data: 'id' },
       
        ],
    });

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
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;"><label for="exampleInputEmail1">Item code * </label>
                                <select class="form-control Item-code item_code${i}" id="${i}" name="moreItems[${i}][Itemcode]"  id="" required></select>
                                <a href="#" style="float: right;"  onclick="get_data(${i})"><i class="fas fa-search"></i> Search by description</a>
                                </div>
                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;"><label>Item type * </label><input type="text" readonly class="form-control" value="" name="Itemtype" id="Itemtype${i}" placeholder="Item type"></div>
                                <div class="col-lg-3" style="float:left;">
                                    <label>order Qty *</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value=""  name="moreItems[${i}][ActualorderQty]"  id="ActualorderQty" placeholder="Order Qty"   aria-describedby="unit-div${i}" >
                                        <div class="input-group-append"><span class="input-group-text unit-div" id="unit-div${i}">Unit</span></div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                    <label>Description * </label>
                                    <textarea type="text" readonly  class="form-control "  name="Description" id="Itemdescription${i}"  placeholder="Description"></textarea>
                                </div>
                                <button name="remove" id="${i}" class="btn btn-danger btn_remove" style="height:38px;margin-top:28px;">X</button>
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
                        url: "<?php echo e(url('inventory/itemcodesearch')); ?>",
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
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find(':submit').prop('disabled', true);
                });
            }); 
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\adler-erp\resources\views/pages/purchase-details/purchase-requisition/purchase-requisition-item-add.blade.php ENDPATH**/ ?>