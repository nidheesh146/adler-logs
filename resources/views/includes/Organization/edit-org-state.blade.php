@if ($org == 'state')
    <script>
        $("#commentForm").validate({
            rules: {
                orgname: {
                    required: true,
                    minlength: 1,
                    maxlength: 40
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 12
                },
                email: {
                    required: true,
                    email: true,
                    remote: "<?= url('') ?>/check-email?module=edit_org_reg&id=<?=$id?>"
                },
                owner_name: {
                    minlength: 1,
                    maxlength: 40
                },
                address: {
                    required: true,
                },
            },
            messages: {
                email: {
                    remote: "given email address is already taken",
                },
            }
        });

    </script>
@endif



@if ($org == 'district')
    <script>
        $("#commentForm").validate({
            rules: {
                orgname: {
                    required: true,
                    minlength: 1,
                    maxlength: 40
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 12
                },
                email: {
                    required: true,
                    email: true,
                    remote: "<?= url('') ?>/check-email?module=edit_org_reg&id=<?=$id?>"
                },
                owner_name: {
                    minlength: 1,
                    maxlength: 40
                },
                state: {
                    required: true,
                },
                address: {
                    required: true,
                },
                commission: {
                    number: true,
                },
            },
            messages: {
                email: {
                    remote: "given email address is already taken",
                },
            }
        });

    </script>
@endif

@if ($org == 'mekhala')
    <script>
        $(function() {
            'use strict'
            $("#commentForm").validate({
                rules: {
                    orgname: {
                        required: true,
                        minlength: 1,
                        maxlength: 40
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 12
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: "<?= url('') ?>/check-email?module=edit_org_reg&id=<?=$id?>"
                    },
                    owner_name: {
                        minlength: 1,
                        maxlength: 40
                    },
                    state: {
                        required: true,
                    },
                    district: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    commission: {
                        number: true,
                    },
                },
                messages: {
                    email: {
                        remote: "given email address is already taken",
                    },
                }
            });
            $('.under_state').change(function() {
                let val = $(this).val() ? $(this).val() : 0;
                $(".district_under").val('').trigger('change');
                $('.District-spinner').show();
                $(".district_under").html('<option value="">Choose one</option>').select2({
                    data: null
                });
                $.get("<?= url('') ?>/organization-select/state/" + val, function(
                data) {
                    $('.District-spinner').hide();
                    $(".district_under").select2({
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
                    orgname: {
                        required: true,
                        minlength: 1,
                        maxlength: 40
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 12
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: "<?= url('') ?>/check-email?module=edit_org_reg&id=<?=$id?>"
                    },
                    owner_name: {
                        minlength: 1,
                        maxlength: 40
                    },
                    state: {
                        required: true,
                    },
                    district: {
                        required: true,
                    },
                    mekhala: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    commission: {
                        number: true,
                    },
                },
                messages: {
                    email: {
                        remote: "given email address is already taken",
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

            $('.under_state').change(function() {
                let val = $(this).val() ? $(this).val() : 0;
                $(".district_under").val('').trigger('change');
                $(".mekhala_under").val('').trigger('change');
                $('.District-spinner').show();
                $('.Mekhala-spinner').show();
                $(".mekhala_under").html('<option value="">Choose one</option>').select2({
                    data: null
                });
                $(".district_under").html('<option value="">Choose one</option>').select2({
                    data: null
                });
                $.get("<?= url('') ?>/organization-select/state/" + val, function(
                data) {
                    $('.District-spinner').hide();
                    $('.Mekhala-spinner').hide();
                    $(".district_under").select2({
                        data: data
                    })
                });
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
        });

    </script>
@endif
@if ($org == 'agent')
    <script>
        $(function() {
            'use strict'
            $('.parend').click(function() {
                if ($(this).is(":checked")) {
                     $('.agent_district').hide();
                } else {
                     $('.agent_district').show();
                }
            });

            $('.under_state').change(function() {
                let val = $(this).val() ? $(this).val() : 0;
                $(".district_under").val('').trigger('change');
                $('.District-spinner').show();
                $(".district_under").html('<option value="">Choose one</option>').select2({
                    data: null
                });
                $.get("<?= url('') ?>/organization-select/state/" + val, function(
                data) {
                    $('.District-spinner').hide();
                    $(".district_under").select2({
                        data: data
                    })
                });
            });
            $("#commentForm").validate({
                rules: {
                    orgname: {
                        required: true,
                        minlength: 1,
                        maxlength: 40
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 12
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: "<?= url('') ?>/check-email?module=edit_org_reg&id=<?=$id?>"
                    },
                    owner_name: {
                        minlength: 1,
                        maxlength: 40
                    },
                    state: {
                        required: true,
                    },
                    district: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    commission: {
                        number: true,
                    },
                },
                messages: {
                    email: {
                        remote: "given email address is already taken",
                    },
                }
            });
            $('.district_under').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search'
            });
        });

    </script>
@endif
@if ($org == 'agent')
@if($data['organization']['state'] && !$data['organization']['district'])
<script>
setTimeout(function(){$('.agent_district').hide(); }, 100);
</script>
@endif
@endif