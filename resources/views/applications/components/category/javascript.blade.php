<script>
    $(document).ready(function () {

        $('#category_create').on('click', function () {
            const categoryKey = $('#category_key').val();
            const categoryName = $('#category_name').val();
            $.ajax({
                url: '{{route('ajax.applications.category.store')}}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_key: categoryKey,
                    category_name: categoryName,
                },
                success: function (response) {
                    showToast('Thành công', null, 'success');
                    const newRow = $('<tr class="odd text-gray-800">' +
                        '<td class="text-start">' +
                        '<div>' + response.category.id + '</div>' +
                        '</td>' +
                        '<td>' +
                        '<div>' + response.category.key + '</div>' +
                        '</td>' +
                        '<td>' +
                        '<div>' + response.category.name + '</div>' +
                        '</td>' +
                        '<td class="d-flex align-items-center justify-content-end">' +
                        '<button class="btn btn-sm btn-warning me-5">' +
                        'Sửa' +
                        '</button>' +
                        '<form action="" method="post">' +
                        '<button type="submit" class="btn btn-sm btn-danger">' +
                        'Xoá' +
                        '</button>' +
                        '</form>' +
                        '</td>' +
                        '</tr>');

                    $('#new_category').before(newRow);
                },
                error: function (response) {
                    if (response.status === 422) {
                        var errors = response.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            showToast('Thất bại', value[0], 'error');
                        });
                    } else {
                        showToast('Thất bại', 'Có lỗi xảy ra. Vui lòng thử lại sau.', 'error');
                    }
                }
            });
        })

        $('.edit_category_button').on('click', function () {

            const categoryId = $(this).data('id');
            const categoryKey = $(this).data('key');
            const categoryName = $(this).data('name');

            $('#edit_category_key').val(categoryKey)
            $('#edit_category_name').val(categoryName)

            $('#category_update').on('click', function () {
                const categoryKeyUpdate = $('#edit_category_key').val();
                const categoryNameUpdate = $('#edit_category_name').val();
                $.ajax({
                    url: '{{route('ajax.applications.category.update')}}',
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        category_id: categoryId,
                        category_key: categoryKeyUpdate,
                        category_name: categoryNameUpdate,
                    },
                    success: function (response) {
                        showToast('Thành công', null, 'success');
                        $('#category_name_' + response.category.id).text(response.category.name);
                        $('#category_key_' + response.category.id).text(response.category.key);
                        $('#edit_category_modal').modal('hide');
                    },
                    error: function (response) {
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                showToast('Thất bại', value[0], 'error');
                            });
                        } else {
                            showToast('Thất bại', 'Có lỗi xảy ra. Vui lòng thử lại sau.', 'error');
                        }
                    }
                });

            })

        })

        $('.delete_category_button').on('click', function () {
            const categoryId = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận xoá',
                text: 'Bạn có chắc chắn muốn xoá Loại đơn này?',
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
                        url: '{{route('ajax.applications.category.destroy')}}',
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            category_id: categoryId,
                        },
                        success: function (response) {
                            $('#category_id_' + categoryId).remove();
                            showToast('Thành công', null, 'success');
                        },
                        error: function (error) {
                            showToast('Thất bại', error, 'error');
                        }
                    });
                }
            })

        });


    });
</script>