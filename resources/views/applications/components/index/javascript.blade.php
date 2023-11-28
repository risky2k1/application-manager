<script>
    $(document).ready(function () {
        $(document).on('click', '.application-state-label', function () {
            const applicationId = $(this).data('id');
            const applicationUpdateStateUrl = $(this).data('url');
            $('.input-application-id').val(applicationId)
            $('.update-state-form').attr('action', applicationUpdateStateUrl);
        })
        $(document).on('change', '.application-state-filter', function () {
            $('#form_filter').submit();
        })

        var selectedApplicationIds = [];

        $('.application-select-all-checkbox').on('change', function () {
            const isChecked = $(this).prop('checked');
            $('.application-checkbox').prop('checked', isChecked);
            updateSelectedIds();
            toggleDeleteButton();
        });

        $('.application-checkbox').change(function () {
            if ($('.application-checkbox:checked').length === $('.application-checkbox').length) {
                $('.application-select-all-checkbox').prop('checked', true);
            } else {
                $('.application-select-all-checkbox').prop('checked', false);
            }
            updateSelectedIds();
            toggleDeleteButton();
        });

        const updateSelectedIds = () => {
            selectedApplicationIds = $('.application-checkbox:checked').map(function () {
                return $(this).closest('.form-check').data('id');
            }).get();
            $('#selected_application').text(selectedApplicationIds.length);
        }

        const toggleDeleteButton = () => {
            if (selectedApplicationIds.length > 0) {
                $('#delete_selected_applications').show();
            } else {
                $('#delete_selected_applications').hide();
            }
        }

        $('#delete_selected_applications').on('click', function () {
            updateSelectedIds();

            if (selectedApplicationIds.length > 0) {
                $.ajax({
                    url: '{{route('applications.destroy.selected')}}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        selected_applications_id: selectedApplicationIds,
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });

    })
    const updateApplicationState = (state) => {
        $('.input-application-state').val(state)
        $('.update-state-form').submit();
    };
</script>