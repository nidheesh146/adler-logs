@if ($org == 'state')
    <script>
        $("#commentForm").validate({
            rules: {
                f_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                l_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                careof: {
                    minlength: 1,
                    maxlength: 50
                },
                 email: {
                     email: true,
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 12
                },
                house_name:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                place:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                postoffice:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                pincode:{
                    required: true,
                    number: true,
                    minlength: 6,
                    maxlength: 6
                },
                ship_address:{
                    required: true, 
                },
                bill_address:{
                    required: true, 
                }
              
            },
           
        });

    </script>
@endif




@if ($org == 'district')
    <script>
        $("#commentForm").validate({
            rules: {
                f_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                l_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                careof: {
                    minlength: 1,
                    maxlength: 50
                },
                 email: {
                     email: true,
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 12
                },
                house_name:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                place:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                postoffice:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                pincode:{
                    required: true,
                    number: true,
                    minlength: 6,
                    maxlength: 6
                },
                district:{
                    required: true,
                },
                ship_address:{
                    required: true, 
                },
                bill_address:{
                    required: true, 
                }
            },
        });

        $('.district_under').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
        });
    </script>
@endif

@if ($org == 'mekhala')
    <script>
        $(function() {
            'use strict'
            $("#commentForm").validate({
                rules: {
                f_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                l_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                careof: {
                    minlength: 1,
                    maxlength: 50
                },
                 email: {
                     email: true,
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 12
                },
                house_name:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                place:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                postoffice:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                pincode:{
                    required: true,
                    number: true,
                    minlength: 6,
                    maxlength: 6
                },
                district:{
                    required: true,
                },
                mekhala:{
                    required: true,
                },
                ship_address:{
                    required: true, 
                },
                bill_address:{
                    required: true, 
                }
              }
            });
            $('.district_under').on('select2:selecting', function(e) {
                let val = e.params.args.data.id ? e.params.args.data.id : 0;
                $(".mekhala_under").html('<option value="">Choose one</option>').select2({
                    data: null
                });
                $(".mekhala_under").val('').trigger('change');
                $('.Mekhala-spinner').show();
                $.get("<?= url('') ?>/organization-select/district/" + val, function(
                    data) {
                    $('.District-spinner').hide();
                    $('.Mekhala-spinner').hide();
                    $(".mekhala_under").select2({
                        data: data
                    })
                });
            });


            $('.district_under').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
            });
        });

    </script>
@endif

@if ($org == 'unit')
    <script>
        $(function() {
            'use strict'
            $("#commentForm").validate({
            rules: {
                f_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                l_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                careof: {
                    minlength: 1,
                    maxlength: 50
                },
                 email: {
                     email: true,
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 12
                },
                house_name:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                place:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                postoffice:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                pincode:{
                    required: true,
                    number: true,
                    minlength: 6,
                    maxlength: 6
                },
                district:{
                    required: true,
                },
                mekhala:{
                    required: true,
                },
                unit:{
                    required: true,  
                },
                ship_address:{
                    required: true, 
                },
                bill_address:{
                    required: true, 
                },
                }
            });
            $('.district_under').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
            });
            $('.mekhala_under').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search',
            });

            $('.district_under').on('select2:selecting', function(e) {
                let val = e.params.args.data.id ? e.params.args.data.id : 0;
                $(".mekhala_under").html('<option value="">Choose one</option>').select2({
                    data: null
                });
                $(".mekhala_under").val('').trigger('change');
                $(".unit_under").html('<option value="">Choose one</option>').select2({
                    data: null
                });
                $(".unit_under").val('').trigger('change');
                $('.Mekhala-spinner').show();
                $.get("<?= url('') ?>/organization-select/district/" + val, function(
                    data) {
                    $('.Mekhala-spinner').hide();
                    $(".mekhala_under").select2({
                        data: data
                    })
                });
            });
            
            $('.mekhala_under').on('select2:selecting', function(e) {
                let val = e.params.args.data.id ? e.params.args.data.id : 0;
                $(".unit_under").html('<option value="">Choose one</option>').select2({
                    data: null
                });
                $(".unit_under").val('').trigger('change');
                $('.Unit-spinner').show();
                $.get("<?= url('') ?>/organization-select/mekhala/" + val, function(
                    data) {
                    $('.Unit-spinner').hide();
                    $(".unit_under").select2({
                        data: data
                    })
                });
            });

        });

    </script>
@endif
