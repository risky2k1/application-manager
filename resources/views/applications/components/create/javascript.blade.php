<script src="{{asset('assets/js/custom/date-picker.js')}}"></script>
<script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>

<script>
    csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    $('.repeater-table').repeater({
        initEmpty: false,

        show: function () {
            $(this).slideDown();
            $('.form-select').select2();
            const collectionDate = document.getElementsByClassName("date-picker");
            for (let i = 0; i < collectionDate.length; i++) {
                new tempusDominus.TempusDominus(collectionDate[i], {
                    useCurrent: false,
                    display: {
                        viewMode: "calendar",
                        components: {
                            decades: true,
                            year: true,
                            month: true,
                            date: true,
                            hours: false,
                            minutes: false,
                            seconds: false
                        },
                        buttons: {
                            today: true,
                            clear: true,
                            close: true
                        },

                    },
                    localization: {
                        locale: "vi",
                        startOfTheWeek: 1,
                        format: "L"
                    }
                });
            }
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
            // const table = $(this).closest('table').data('table');
            {{--if (id) {--}}
            {{--    $.ajax({--}}
            {{--        url: "{{route('ajax.profiles.table.destroy', $profile)}}",--}}
            {{--        method: "delete",--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': csrfToken--}}
            {{--        },--}}
            {{--        data: {'id': id, 'table': table},--}}
            {{--        success: function (response) {--}}

            {{--        },--}}
            {{--        error: function (request, error) {--}}
            {{--            alert(" Can't do because: " + error);--}}
            {{--        },--}}
            {{--    })--}}
            {{--}--}}
        }
    });
</script>
