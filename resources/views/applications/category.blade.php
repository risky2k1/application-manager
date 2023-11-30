@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title text-uppercase">
                <h2>Thiết lập đơn từ</h2>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-toolbar">
            </div>
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <a href="#">
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add customer-->
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#add_category">
                            Thêm
                            <i class="fa-solid fa-plus text-white"></i>
                        </button>
                        <!--end::Add customer-->
                    </div>
                </a>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        @if($categories->count() > 0)
            <div class="card-body pt-0">
                <!--begin::Table-->
                <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead>
                            <!--begin::Table row-->
                            <tr class="text-center fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-start">STT</th>
                                <th>Tên Key</th>
                                <th>Tên loại</th>
                                <th class="text-end sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">Hành động</th>
                            </tr>
                            <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-center fw-semibold text-gray-600">
                            @foreach($categories as $category)
                                <tr class="odd text-gray-800" id="category_id_{{$category->id}}">
                                    <td class="text-start">
                                        <div>{{$loop->increment}}</div>
                                    </td>
                                    <td>
                                        <div id="category_key_{{$category->id}}">
                                            {{$category->key}}
                                        </div>
                                    </td>
                                    <td>
                                        <div id="category_name_{{$category->id}}">
                                            {{$category->name}}
                                        </div>
                                    </td>
                                    <td class="d-flex align-items-center justify-content-end">
                                        <button class="btn btn-sm btn-warning me-5 edit_category_button"
                                                data-id="{{$category->id}}"
                                                data-name="{{$category->name}}"
                                                data-key="{{$category->key}}"
                                                data-bs-toggle="modal" data-bs-target="#edit_category_modal">
                                            Sửa
                                        </button>
                                        <button class="btn btn-sm btn-danger delete_category_button" data-id="{{$category->id}}">
                                            Xoá
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr id="new_category"></tr>
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        {{$categories->links()}}
                    </div>
                </div>
                <!--end::Table-->
            </div>
        @else
            @if(check_url_parameters(request()))
                <h3 class="mt-5 text-center text-danger">Không tìm thấy loại đơn từ nào</h3>
            @else
                <h3 class="mt-5 text-center text-danger">Chưa có loại đơn từ nào.</h3>
            @endif
        @endif
        <!--end::Card body-->
    </div>
    <div class="modal fade" tabindex="-1" id="add_category">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Thêm mới</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="mb-5">
                        <span class="text-danger">Chú ý, key và tên loại nên liên quan đến nhau</span>
                    </div>
                    <div>
                        <label for="category_name" class="fs-5 form-label mb-2">Tên Key</label>
                        <input type="text" class="form-control" name="category_name" id="category_key">
                    </div>
                    <div>
                        <label for="category_name" class="fs-5 form-label mb-2">Tên loại đơn</label>
                        <input type="text" class="form-control" name="category_name" id="category_name">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-sm btn-primary" id="category_create">Tạo</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="edit_category_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Sửa</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div>
                        <label for="category_name" class="fs-5 form-label mb-2">Tên key</label>
                        <input type="text" class="form-control" name="category_name" id="edit_category_key">
                    </div>
                    <div>
                        <label for="category_name" class="fs-5 form-label mb-2">Tên loại đơn</label>
                        <input type="text" class="form-control" name="category_name" id="edit_category_name">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-sm btn-primary" id="category_update">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    @include('application-manager::applications.components.category.javascript')
@endsection