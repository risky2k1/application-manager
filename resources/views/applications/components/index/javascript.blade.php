<script>
    $(document).ready(function () {

        //Change application state

        $(document).on('click', '.application-state-label', function () {
            const applicationId = $(this).data('id');
            const applicationUpdateStateUrl = $(this).data('url');
            $('.input-application-id').val(applicationId)
            $('.update-state-form').attr('action', applicationUpdateStateUrl);
        })
        $(document).on('change', '.application-state-filter', function () {
            $('#form_filter').submit();
        })

        // Select Applications

        let selectedApplicationIds = [];

        $('.application-select-all-checkbox').on('change', function () {
            const isChecked = $(this).prop('checked');
            $('.application-checkbox').prop('checked', isChecked);
            updateSelectedIds();
            updateSelectedAllStatus();
        });

        $('.application-checkbox').change(function () {
            if ($('.application-checkbox:checked').length === $('.application-checkbox').length) {
                $('.application-select-all-checkbox').prop('checked', true);
            } else {
                $('.application-select-all-checkbox').prop('checked', false);
            }
            updateSelectedIds();

            if ($(this).data('deleted')) {
                updateSelectedStatus('restore');
            } else {
                updateSelectedStatus('delete');
            }
        });

        const updateSelectedIds = () => {
            selectedApplicationIds = $('.application-checkbox:checked').map(function () {
                return $(this).closest('.form-check').data('id');
            }).get();
            $('.selected-application').text(selectedApplicationIds.length);
        }

        const updateSelectedStatus = (status) => {
            toggleButton(status);
        }

        const updateSelectedAllStatus = () => {
            const isDeletedAll = $('.application-checkbox').first().data('deleted');
            if (isDeletedAll) {
                updateSelectedStatus('restore');
            } else {
                updateSelectedStatus('delete');
            }
        }

        const toggleButton = (status) => {
            if (status === 'delete') {
                if (selectedApplicationIds.length > 0) {
                    $('#delete_selected_applications').show();
                } else {
                    $('#delete_selected_applications').hide();
                }
            } else {
                if (selectedApplicationIds.length > 0) {
                    $('#restore_selected_applications').show();
                    $('#force_delete_selected_applications').show();
                } else {
                    $('#restore_selected_applications').hide();
                    $('#force_delete_selected_applications').hide();
                }
            }

        }

        $('#delete_selected_applications').on('click', function () {
            updateSelectedIds();

            if (selectedApplicationIds.length > 0) {

                Swal.fire({
                    title: 'Xác nhận xoá',
                    text: 'Bạn có chắc chắn muốn xoá các đơn đã chọn?',
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-danger"
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{route('applications.destroy.selected')}}',
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                selected_applications_id: selectedApplicationIds,
                            },
                            success: function () {
                                $.each(selectedApplicationIds, function (key, val) {
                                    $('#application_' + val).remove();
                                })
                                $('#delete_selected_applications').hide();
                                showToast('Thành công', null, 'success');
                            },
                            error: function (error) {
                                showToast('Thất bại', error, 'error');
                            }
                        });
                    }
                })

            }
        });

        $('#restore_selected_applications').on('click', function () {
            updateSelectedIds();

            if (selectedApplicationIds.length > 0) {

                Swal.fire({
                    title: 'Xác nhận khôi phục',
                    text: 'Bạn có chắc chắn muốn khôi phục các đơn đã chọn?',
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-danger"
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{route('applications.restore.selected')}}',
                            method: 'GET',
                            data: {
                                selected_applications_id: selectedApplicationIds,
                            },
                            success: function () {
                                $.each(selectedApplicationIds, function (key, val) {
                                    $('#application_' + val).remove();
                                })
                                showToast('Thành công', null, 'success');
                            },
                            error: function (error) {
                                showToast('Thất bại', error, 'error');
                            }
                        });
                    }
                })

            }
        });

        $('#force_delete_selected_applications').on('click', function () {
            updateSelectedIds();

            if (selectedApplicationIds.length > 0) {

                Swal.fire({
                    title: 'Xác nhận xoá',
                    text: 'Bạn có chắc chắn muốn xoá vĩnh viễn các đơn đã chọn?',
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-danger"
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{route('applications.force.destroy.selected')}}',
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                selected_applications_id: selectedApplicationIds,
                            },
                            success: function () {
                                $.each(selectedApplicationIds, function (key, val) {
                                    $('#application_' + val).remove();
                                })
                                showToast('Thành công', null, 'success');
                            },
                            error: function (error) {
                                showToast('Thất bại', error, 'error');
                            }
                        });
                    }
                })

            }
        });


    })
    const updateApplicationState = (state) => {
        $('.input-application-state').val(state)
        $('.update-state-form').submit();
    };
</script>